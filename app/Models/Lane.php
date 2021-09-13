<?php

namespace App\Models;

use App\Facades\General;
use Carbon\Carbon;
use DB;
use Exception;
use GeoTools\LatLngCollection;
use GeoTools\RouteBoxer;
use http\Client\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class Lane extends Crud
{
    use SoftDeletes;

    protected $fillable = ['company_id',
        'truck_id',
        'parent_id',
        'start_addr',
        'start_lat',
        'start_lng',
        'start_city',
        'start_postcode',
        'start_date',
        'end_addr',
        'end_lat',
        'end_lng',
        'end_city',
        'end_postcode',
        'route',
        'waypoint',
        'transit_time',
        'pickup_notice',
//        'price_per',
//        'min_price',
        'discount',
        'created_by',
        'capacity',
        'transport',
        'frequency',
        'expiry_date',
        'delivery_within'];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function laneTieredPrice()
    {
        return $this->hasMany('App\Models\LaneTieredPrice', 'lane_id');
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of lane model
        return $model = self::getLaneModal(false, 0, 0);
    }

    /**
     * Global search function
     *
     * @param $keyword
     * @param array $option
     * @return JsonResponse
     * @throws Exception
     */
    public function getSearchResults($keyword, $option = [])
    {
        $options['match'] = [
            "lanes.start_addr" => $keyword,
            "lanes.end_addr" => $keyword,
            "start_city" => $keyword,
            "end_city" => $keyword,
        ];

        // Get the global search data which are matched with keyword
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            $result = $model->count();
        }else{
            // Get the lane datatable
            $result = self::getLaneDatatable($model, false);
        }
        return $result;
    }

    /**
     * Get all lane lists
     *
     * @param bool $trip
     * @param int $tripId
     * @param int $companyId
     * @return JsonResponse
     * @throws Exception
     */
    static public function getLanes($trip = false, $tripId = 0, $companyId = 0)
    {
        // Get the lane data for listing
        $model = self::getLaneModal($trip, $tripId, $companyId);

        // Generate the datatable for lanes
        return self::getLaneDatatable($model, $trip);
    }

    /**
     * @param $model
     * @param $trip
     * @return JsonResponse
     * @throws Exception
     */
    public static function getLaneDatatable($model, $trip){
        return Datatables::eloquent($model)
            ->addColumn('company_name', function ($lane) {
                return isset($lane->company) ? $lane->company->name : '';
            })
            ->addColumn('price_per', function ($lane) {
                return isset($lane->min_price) ? '$' . number_format($lane->min_price, 2) : '';
            })
            ->addColumn('transport', function ($lane) {
                if ($lane->transport == Config::get('biggles.TRANSPORT_RAIL')){
                    return 'Rail';
                }else if($lane->transport == Config::get('biggles.TRANSPORT_TRUCK')){
                    return 'Truck';
                }
                return '';
            })
            ->addColumn('capacity', function ($lane) {
                return isset($lane->capacity) ? $lane->capacity . 'm3' : '0m3';
            })->addColumn('transit_time', function ($lane) {

                return isset($lane->start_date) ?
                    Carbon::parse($lane->start_date)->format('d/m/y') . ' - ' .
                    Carbon::parse($lane->expiry_date)->format('d/m/Y')
                    : 'null';
            })
            ->addColumn('type', function ($lane) {
//                return '<span class="kt-badge--inline kt-badge--success kt-font-bold status_bg">Single</span>';
                return '<span class="badge badge-light-warning">Single</span>';
            })
            ->addColumn('action', function ($lane) use ($trip) {
                // check the request is from lane or trip
                if ($trip === false) {
                    $model = 'lane';
                } else {
                    $model = 'trip';
                }
                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $lane)->with('route', $model . '.show');
                $action .= View('layouts.actions.edit')->with('model', $lane)->with('route', $model . '.edit');
                $action .= View('layouts.actions.delete')->with('model', $lane)->with('route', $model . '.destroy');
                return $action .= '</div>';
            })
            ->rawColumns(['action', 'type'])
            ->make(true);
    }

    /**
     * Return lane modal
     *
     * @param $trip
     * @param $tripId
     * @param int $companyId
     * @return Builder
     */
    public static function getLaneModal($trip, $tripId, $companyId=0)
    {
        $model = self::query();
        $user  = Auth::user();
        // Check the request is for lane or trip
        if ($trip === false) {
            $model->whereNull('start_date');
        } else {
            $model->whereNotNull('start_date');
        }
        // If it is company agent
        if (General::isCompanyAgent()) {
            $model->where('lanes.company_id', $user->company_id);
        }
        if ($companyId > 0) {
            $model->where('lanes.company_id', $companyId);
        }

        $cols = [
            "lanes.*",
            DB::raw("(select price from lane_tiered_prices where lane_id=lanes.id order by id limit 1) as min_price")
        ];
        $model->select($cols);

        return $model;
    }

    /**
     * Return lanes
     *
     * @return Builder[]|Collection
     */
    public static function getAllLanes()
    {
        // Get the lane cities
        $laneCities = self::getLaneModal(false, 0)
            ->select('start_city')
            ->groupBy('start_city')
            ->orderBy(DB::raw('count(start_city)'), 'DESC')->get();
        $cityList   = [];
        foreach ($laneCities as $laneCity) {
            $cityList[] = $laneCity->start_city;
        }

        $data['cities']  = $cityList;
        // Get the lane data
        $data['records'] = self::getLaneModal(false, 0)->get();

        return $data;
    }

    /**
     * Search lanes
     *
     * @param null $searchKey
     * @return Builder[]|Collection
     */
    static public function searchLanes($searchKey = null)
    {
        $model = self::query();
        $model->whereNull('start_date');
        return $model->limit(10)->get();
    }

    /**
     * Get trips by month
     *
     * @param $request
     * @return array
     */
    static public function getTripsByMonth($request)
    {
        $model = self::query();
        $model->whereNotNull('start_date');

        $startDate = isset($request->start) && !is_null($request->start) ? $request->start : null;
        $endDate   = isset($request->end) && !is_null($request->end) ? $request->end : null;

        if (!is_null($startDate) && !is_null($endDate)) {
            $startDate = Carbon::parse($startDate)->format('Y-m-d H:i:s');
            $endDate   = Carbon::parse($endDate)->format('Y-m-d H:i:s');
            $model->whereRaw('start_date between "' . $startDate . '" and "' . $endDate . '" ');
        }

        $lanes     = $model->get();
        $tripLists = [];
        $tripCount = 0;
        // Loop through all trips and generate calendar events
        foreach ($lanes as $lane) {
            $tripLists[$tripCount]['id']    = $lane->id;
            $tripLists[$tripCount]['title'] = $lane->title;
            $tripLists[$tripCount]['start'] = $lane->start_date;
            $tripLists[$tripCount]['end']   = $lane->start_date;
            $tripCount++;
        }
        return $tripLists;
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->start_addr . ' ' . $this->end_addr;
    }


    /**
     * Generate the columns of mysql query for access attribute
     *
     * @param $cols
     * @param $startAccessArr
     * @param $endAccessArr
     * @param $totalSpace
     * @return mixed
     */
    public static function generateColumnsForAccess($cols, $startAccessArr, $endAccessArr, $totalSpace){
        $commonSpace = Config::get('muval.DEPOT_SPACE_VALUE');
        $totalPriceColumn = "0";

        // Array of access with db column name to match with access selected by client
        $accessItemArr = General::getAccessAttribute();

        foreach ($accessItemArr as $accessItem){
            $interstateColumn = $accessItem['field'];
            if ($interstateColumn == ''){
                continue;
            }
            // Check the access attribute for start access
            $matchedItem = Depot::searchForItemName($accessItem['item'], $startAccessArr);
            $accessItemVal = 0;
            if ($matchedItem != null){
                $accessItemVal = $matchedItem['answers'][0]['ans'];
            }
            // Check the access attribute for end access
            $matchedItem2 = Depot::searchForItemName($accessItem['item'], $endAccessArr);
            $accessItemVal2 = 0;
            if ($matchedItem2!=null){
                $accessItemVal2 = $matchedItem2['answers'][0]['ans'];
            }
            array_push($cols, \Illuminate\Support\Facades\DB::raw("(($accessItemVal+$accessItemVal2)*$totalSpace*$interstateColumn) As ".$interstateColumn."_price"));

            // Generate the column to calculate the total access pricing
            $totalPriceColumn .= "+(($accessItemVal+$accessItemVal2)*$totalSpace*$interstateColumn)";
        }
        $totalPriceColumn .= "+(CASE WHEN lane_tiered_prices.price*" . $totalSpace . " < interstate_configurations.min_price THEN interstate_configurations.min_price ELSE lane_tiered_prices.price*" . $totalSpace . " END )";

        array_push($cols, DB::raw("($totalPriceColumn) As removal_price"));

        return $cols;
    }

    /**
     * Return Lane object
     *
     * @param $request
     * @param $calenderDate
     * @return Builder|\Illuminate\Database\Query\Builder|null
     * @throws Exception
     */
    public static function getLaneObject($request, $calenderDate)
    {
        $weekDay = strtolower((new carbon($calenderDate))->dayName);
        $totalSpace = floatval($request->space);
        // check if the request has all params
        if ($request->has('space')) {

            // Get the query
            $model = self::query();
            $cols  = [
                "lanes.*",
                'companies.logo',
                'companies.about_us',
                DB::raw('companies.name as company_name'),
                DB::raw('companies.phone as company_phone'),
                DB::raw('if(trucks.capacity>0, trucks.capacity, 0) as truck_capacity'),
                DB::raw('if(sum(moves.space)>0, sum(moves.space), 0) as space_occupied'),
//                DB::raw("(CASE WHEN lane_tiered_prices.price*" . $totalSpace . " < interstate_configurations.min_price THEN interstate_configurations.min_price ELSE lane_tiered_prices.price*" . $totalSpace . " END ) AS removal_price"),
            ];

            // Generate the columns for the start access and end access
            $cols = self::generateColumnsForAccess($cols, $request->start_access, $request->end_access, $totalSpace);
            $model->select($cols);

            $model = $model->join(
                'companies', 'companies.id', '=', 'lanes.company_id'
            )->join(
                'interstate_configurations', 'companies.id', '=', 'interstate_configurations.company_id'
            )->join('lane_tiered_prices', function ($join) use($totalSpace) {
                $totalSpace = floatval($totalSpace);
                // check the condition for lane pricing for both single or tiered
                $join->on('lanes.id', '=', 'lane_tiered_prices.lane_id');
                $join->where('lane_tiered_prices.space_start_range', '<=', $totalSpace);
                $join->where('lane_tiered_prices.space_end_range', '>=', $totalSpace);
            })->leftJoin(
                'trucks', 'companies.id', '=', 'trucks.company_id'
            )->leftJoin('moves', function ($join) use ($calenderDate) {
                $join->on('moves.truck_id', '=', 'trucks.id');
                $join->on('moves.company_id', '=', 'companies.id');
                $join->on(DB::raw('date(moves.pickup_window_start)'), '=', DB::raw("( $calenderDate )"));
            })->whereRaw("if(lanes.start_date is not null, date(lanes.start_date)='".$calenderDate."', json_contains(`lanes`.`pickup_days`, '\"".$weekDay."\"') )");

            return $model;
        }

        return null;
    }


    /**
     * Return lanes by a radius
     *
     * @param $request
     * @return array
     * @throws Exception
     */
    public static function getLanesByRadius($request)
    {
        $startDate = new Carbon($request->start_date);
        $endDate   = new Carbon($request->end_date);
        $moveSpace = $request->space;

        $lanes = [];
        // find the lane matching for each date between start and end date
        while ($startDate->lte($endDate)) {
            $calenderDate = $startDate->toDateString();

            // Get the matched lanes for particular date
            $model   = self::getLaneObject($request, $calenderDate);
            $tripResults = $model->groupBy('lanes.company_id', 'lanes.id')
                ->having(DB::raw("(truck_capacity-space_occupied)"), '>', $moveSpace)
                ->orderBy('removal_price')->get();

            $laneObj = [];
            $laneObj['date']  = $calenderDate;
            $laneObj['price'] = 0;
            if (!is_null($tripResults)) {
                foreach ($tripResults as $trip) {

                    if ($trip->transport == 1){
                        // match the trip with the requested route using route boxer
                        $matchedFlag = self::filterMatchTrip($trip, $request->start_lng,
                        $request->start_lat, $request->end_lng, $request->end_lat);

                        // check if route match with the trip
                        if ($matchedFlag){
                            $laneObj['price'] = isset($trip) ? round(floatval($trip->removal_price), 2) : 0;

                            break;
                        }
                    }
                }
            }

            $lanes[] = $laneObj;

            $startDate->addDay();
        }

        return $lanes;
    }


    /**
     * Return interstate removal lists
     *
     * @param $request
     * @return array|null
     * @throws Exception
     */
    public static function getInterstateRemovalLists($request)
    {
        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        // Get the lanes data for the pickup date
        $model   = self::getLaneObject($request, $pickupDate);
        $trips = $model->groupBy('lanes.company_id', 'lanes.id')
            ->having(DB::raw("(truck_capacity-space_occupied)"), '>', $moveSpace)
            ->orderBy('removal_price')->get();

        $removals = [];
        $companyUniqueList = [];
        foreach ($trips as $key => $trip) {
            // If the company is already listed out from depot
            // second time it will not show
            if (in_array($trip->company_id, $companyUniqueList)){
                continue;
            }
            $removalArr = [];
            // If it is truck
            if ($trip->transport == 1){
                // match the trip with the requested route using route boxer
                $matchedFlag = self::filterMatchTrip($trip, $request->start_lng,
                    $request->start_lat, $request->end_lng, $request->end_lat);

                // check if route match with the trip
                if ($matchedFlag){

                    // Ge the delivery date from the pickup date
                    $delivery_date_initial = self::getDeliveryDate($request->pickup_date);
                    $deliveryDate = ($request->delivery_date) ?? $delivery_date_initial;

                    $removalArr['id'] = $trip->id;
                    $removalArr['company_id'] = $trip->company_id;
                    $removalArr['company_name'] = $trip->company_name;
                    $removalArr['logoFullUrl'] = !is_null($trip->logo) ? asset('storage/' . $trip->logo) : asset('storage/logo/company_logo.svg');
                    $removalArr['company_phone'] = $trip->company_phone;
                    $removalArr['delivery_date'] = $deliveryDate;
                    $removalArr['removal_price'] = round(floatval($trip->removal_price), 2);
                    $removalArr['storage_price'] = floatval(0);
                    $removalArr['total_price'] = round(floatval($trip->removal_price) , 2);
                    $removalArr['ancillary_price'] = floatval(0);

                    $removals[] = $removalArr;
                    array_push($companyUniqueList, $trip->company_id);
                }
            }
        }

        return !empty($removals) ? $removals : null;
    }

    /**
     * Calculate the delivery date from the pickup date
     *
     * @param $pickupDate
     * @return string
     * @throws Exception
     */
    public static function getDeliveryDate($pickupDate){
        return (new Carbon($pickupDate))->addDay(2)->toDateString();
    }

    /**
     * Return interstate removal list details
     *
     * @param $request
     * @return array|null
     * @throws Exception
     */
    public static function getInterstateRemovalDetails($request)
    {
        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        if ($request->matchId == ''){
            return null;
        }
        // Get the lane details for the pickup date
        $model   = self::getLaneObject($request, $pickupDate);
        $removalDetails = $model->where('companies.id', $request->companyId)
            ->where('lanes.id', $request->matchId)
            ->groupBy('lanes.company_id', 'lanes.id')
            ->having(DB::raw("(truck_capacity-space_occupied)"), '>', $moveSpace)
            ->orderBy('removal_price')->get()->first();

        // Get the storage price
        $storagePrice = self::getStoragePrice((object)[
            'companyId'     => $request->companyId,
            'pickup_date'   => $request->pickup_date,
            'delivery_date' => (new Carbon($request->delivery_date))->addDay(1)->toDateString(),
            'removalType' => $request->removal_type]);

        // Get the price for selected ancillary services
        $ancillaryPrice = AncillaryService::getAncillaryServicePrice($request->moveId);

        // Get the delivery date from the pickup date
        $deliveryDateInitial = self::getDeliveryDate($request->pickup_date);
        $deliveryDate = ($request->delivery_date) ?? $deliveryDateInitial;

        $removalDetailsArr = [];
        $removalDetailsArr['id'] = $removalDetails->id;
        $removalDetailsArr['company_id'] = $removalDetails->company_id;
        $removalDetailsArr['company_name'] = $removalDetails->company_name;
        $removalDetailsArr['logoFullUrl'] = isset($removalDetails->logo) && !is_null($removalDetails->logo) ? asset('storage/' . $removalDetails->logo) : asset('storage/logo/company_logo.svg');
        $removalDetailsArr['company_phone'] = $removalDetails->company_phone;
        $removalDetailsArr['about_us'] = $removalDetails->about_us;
        $removalDetailsArr['delivery_date'] = $deliveryDate;
        $removalDetailsArr['removal_price'] = round(floatval($removalDetails->removal_price), 2);
        $removalDetailsArr['storage_price'] = round(floatval($storagePrice), 2);
        $removalDetailsArr['ancillary_price'] = round(floatval($ancillaryPrice['totalPrice']), 2);
        $removalDetailsArr['total_price'] = round(floatval((floatval($removalDetails->removal_price) + floatval($storagePrice) + floatval($ancillaryPrice['totalPrice']) )), 2);
        $removalDetailsArr['total_removal_storage'] = round(floatval((floatval($removalDetails->removal_price) + floatval($storagePrice) )), 2);

        return !empty($removalDetailsArr) ? $removalDetailsArr : null;
    }

    /**
     * Return removal pricing details
     *
     * @param $request
     * @return array
     * @throws Exception
     */
    public static function getInterstateRemovalPricing($request){
        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        $pricingBreakdown = [];

        // Get the lane data for the pickup date
        $model   = self::getLaneObject($request, $pickupDate);
        $removalDetails = $model->where('companies.id', $request->companyId)
            ->where('lanes.id', $request->matchId)
            ->groupBy('lanes.company_id', 'lanes.id')
            ->having(DB::raw("(truck_capacity-space_occupied)"), '>', $moveSpace)
            ->orderBy('removal_price')->get()->first();

        if ($removalDetails == null){
            return ['priceDetails' => null, 'totalPrice' => null];
        }

        // Get the storage price
        $storagePrice = self::getStoragePrice((object)[
            'companyId'     => $request->companyId,
            'pickup_date'   => $request->pickup_date,
            'delivery_date' => (new Carbon($request->delivery_date))->addDay(1)->toDateString(),
            'removalType' => $request->removal_type]);

        // Check the ancillaries
        $ancillaryPrice = AncillaryService::getAncillaryServicePrice($request->moveId);
        $pricingBreakdown['Removal Price'] = round(floatval($removalDetails->removal_price), 2);
        $pricingBreakdown['Storage'] = round(floatval($storagePrice), 2);

        foreach ($ancillaryPrice as $key => $price){
            if(isset($price['type'])){
                $pricingBreakdown[$price['type']] = $price['price'];
            }
        }

        // Total price = removal price + storage price for extended date + ancillaries price
        $totalPrice = round(floatval((
            $pricingBreakdown['Removal Price'] + $pricingBreakdown['Storage'] + $ancillaryPrice['totalPrice'])), 2);

        return ['priceDetails' => $pricingBreakdown, 'totalPrice' => $totalPrice];
    }

    /**
     * Check the storage charge date
     *
     * @param $params
     * @return array
     * @throws Exception
     */
    public static function getStorageChargeDate($params){
        // fetch the interstate details
        $interstateSetting = InterstateConfiguration::where('company_id', $params->companyId)->get()->first();

        $initialPickupDate = new Carbon($params->pickup_date);
        if (isset($params->removalType) && $params->removalType == 'local'){
            $storageChargeDate = $initialPickupDate->addDays(2)->toDateString();
        }else{
            $storageChargeDate = $initialPickupDate->addDays(3)->toDateString();
        }


        // checking the total days free storage offer available
        if ($interstateSetting->storage_toggle == 1 && $interstateSetting->free_storage_weeks>0) {
            $storageChargeDate = $initialPickupDate->addDays($interstateSetting->free_storage_weeks * 7)->toDateString();
        }

        // If storage price not setup from company then it will be 0
        $storagePrice = ($interstateSetting->storage_cost) ?? 0;

        return [ "storageChargeDate" => $storageChargeDate, "storagePrice" => $storagePrice];
    }

    /**
     * Calculate the storage price
     *
     * @param $param
     * @return float|int
     * @throws Exception
     */
    public static function getStoragePrice($param){
        // Get the storage charge data
        $storage = self::getStorageChargeDate($param);
        $storageChargeDate = $storage['storageChargeDate'];
        $storagePrice      = $storage['storagePrice'];
        $deliveryDate = $param->delivery_date;

        $storageChargeDate = Carbon::parse($storageChargeDate);
        $deliveryDate = Carbon::parse($deliveryDate);
        $diff = 0;
        if ($deliveryDate->gt($storageChargeDate)){
            // Find the difference in days between storage charge and delivery date
            $diff = $storageChargeDate->diffInDays($deliveryDate);
        }

        return $storagePrice*$diff;
    }

    /**
     * Get matching trips for requested routes
     *
     * @param $trip
     * @param $startLng
     * @param $startLat
     * @param $endLng
     * @param $endLat
     * @return bool
     */
    public static function filterMatchTrip($trip, $startLng, $startLat, $endLng, $endLat){

        $start = 0;
        $end   = 0;

        // Find distance between searched start address and lane address
        $startDistance = distance($trip->start_lat, $trip->start_lng, $startLat, $startLng);
        $startRadius   = $startDistance;
        // Find distance between searched end address and lane address
        $endDistance   = distance($trip->start_lat, $trip->start_lng, $endLat, $endLng);
        $endRadius     = distance($trip->end_lat, $trip->end_lng, $endLat, $endLng);

        // fix weird bug with NAN when lead and trip locations are exactly the same
        $endRadius = is_nan($endRadius) ? 0.00 : $endRadius;
        $startRadius = is_nan($startRadius) ? 0.00 : $startRadius;

        // add all points from calculated route
        $points = json_decode($trip->route);
        foreach ($points as $key => $point){
            $points[$key] = [$point[1], $point[0]];
        }

        $collection = new LatLngCollection($points);
        $boxer = new RouteBoxer();

        // calculate boxes with 50km distance from the line between points
        $boxes = (array) $boxer->box($collection, $distance = 50);

        foreach($boxes as $row){
            $southWestLtd = $row->getSouthWest()->getLatitude();
            $southWestLng = $row->getSouthWest()->getLongitude();
            $northEastLtd = $row->getNorthEast()->getLatitude();
            $northEastLng = $row->getNorthEast()->getLongitude();
            if(($endLat> $southWestLtd && $endLat< $northEastLtd) && ($endLng> $southWestLng && $endLng< $northEastLng)){
                $end++;
            }
            if(($startLat> $southWestLtd && $startLat< $northEastLtd) && ($startLng> $southWestLng && $startLng< $northEastLng)){
                $start++;
            }
            if(($start == 1 && $end == 1) && ($startDistance <= $endDistance)) break;
        }

        if(($start>0 && $end>0 && ($startDistance <= $endDistance))){
            return true;
        }

        return false;
    }

    /**
     * Calculate the pricing for access attribute
     *
     * @param $companyId
     * @param $volume
     * @param $accessArr
     * @return float|int
     */
    public static function getAccessAttributePricing($companyId, $volume, $accessArr){
        // calculate interstate pricing
        $totalAccessPrice = 0.00;
        $accessPriceArr = [];
        $accessItemArr = ['Height restraints', 'Stairs', 'Narrow street', 'Elevator', 'No parking'];
        // If access is been selected by customer
        if (isset($accessArr) && is_array($accessArr) && $accessArr!=null && $companyId>0 && $volume>0){
            // Get the interstate details for the company
            $InterstateSetting = InterstateConfiguration::where('company_id', $companyId)->get()->first();

            // Id setting is configured
            if ($InterstateSetting != null) {
                foreach ($accessArr as $access) {

                    // Match and calculate the access charge
                    if ( in_array($access['item'], $accessItemArr)) {
                        $accArr = [];
                        $totalPrice = 0;

                        if ($access['answers'] == null)
                            continue;

                        foreach ($access['answers'] as $answer) {

                            if (!empty($answer['ans']) && $answer['ans'] != null && $answer['ans'] > 0) {

                                if ($access['item'] == 'Height restraints'){
                                    $totalPrice += (floatval($volume) *
                                        floatval($answer['ans']) * floatval($InterstateSetting->long_driveway));
                                }else if($access['item'] == 'Stairs'){
                                    $totalPrice += (floatval($volume) *
                                        floatval($answer['ans']) * floatval($InterstateSetting->stairs));
                                }else if($access['item'] == 'Narrow street'){
                                    $totalPrice += (floatval($volume) *
                                        floatval($answer['ans']) * floatval($InterstateSetting->ferry_vehicle));
                                }else if($access['item'] == 'Elevator'){
                                    $totalPrice += (floatval($volume) *
                                        floatval($answer['ans']) * floatval($InterstateSetting->elevator));
                                }else if($access['item'] == 'No parking'){
                                    $totalPrice += (floatval($volume) *
                                        floatval($answer['ans']) * floatval($InterstateSetting->extra_kms));
                                }
                            }
                        }
                        $totalAccessPrice += $totalPrice;
                        $accArr['price'] = $totalPrice;
                        $accArr['item'] = $access['item'];
                        array_push($accessPriceArr, $accArr);
                    }
                }
            }
        }
        $accessPriceArr['totalAccessPrice'] = $totalAccessPrice;

        return $accessPriceArr;
    }
}
