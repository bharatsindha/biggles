<?php

namespace App\Models;

use App\Facades\General;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Depot extends Crud
{
    use SoftDeletes;

    protected $fillable = ['company_id',
        'name',
        'addr',
        'lat',
        'lng',
        'city',
        'postcode',
        'created_by'];

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
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of depot model
        return self::getDepotModel();
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
        $options['match'] = ["depots.name" => $keyword, "depots.addr" => $keyword,];
        // get the query of global search
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            $result = $model->count();
        }else{
            // Get the datatable of depot
            $result = self::getDatatableObject($model);
        }

        return $result;
    }

    /**
     * Return the Depot model
     *
     * @return Builder
     */
    public static function getDepotModel()
    {
        $model = self::query();
        $user  = Auth::user();
        // If it is company agent
        if (General::isCompanyAgent()) {
            $model = $model->where('company_id', $user->company_id);
        }

        return $model;
    }

    /**
     * Get all depot lists
     *
     * @return JsonResponse
     * @throws Exception
     */
    static public function getDepots()
    {
        // Ge the object of the depot model
        $model = self::getDepotModel();

        // Get the datatable of depot list
        return self::getDatatableObject($model);
    }

    /**
     * Generate the datatable object of depot
     *
     * @param $model
     * @return JsonResponse
     * @throws Exception
     */
    public static function getDatatableObject($model){
        return Datatables::eloquent($model)
            ->addColumn('company_name', function ($depot) {
                return isset($depot->company) ? $depot->company->name : '';
            })
            ->addColumn('action', function ($depot) {
                $action = '';
                $action .= View('layouts.actions.view')->with('model', $depot)->with('route', 'depot.show');
                $action .= View('layouts.actions.edit')->with('model', $depot)->with('route', 'depot.edit');
                $action .= View('layouts.actions.delete')->with('model', $depot)->with('route', 'depot.destroy');
                return $action .= '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Return the all Depot Options to show on the form
     *
     * @return array
     */
    public static function getDepotOptions()
    {
        // Get the all depots data
        $depotOptions = self::getDepotModel()->get();
        $optionValues = [];

        foreach ($depotOptions as $depotOption) {
            $optionValues[$depotOption->id] = $depotOption->name;
        }

        return $optionValues;
    }

    /**
     * Return depot option's value by id
     *
     * @param $optionId
     * @return string
     */
    public static function getDepotOptionValueById($optionId)
    {
        try {
            // Get the depot details by id
            $depotResult = self::where('id', $optionId)->first();

            return $depotResult->addr . ', ' . $depotResult->postcode;

        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Return Depot details by Id
     *
     * @param $depotId
     * @return mixed
     */
    public static function getDepotById($depotId)
    {
        return self::find($depotId);
    }

    /**
     * Check the item exist into the array
     *
     * @param $itemName
     * @param $array
     * @return |null
     */
    public static function searchForItemName($itemName, $array) {
        foreach ($array as $key => $val) {
            if ($val['item'] === $itemName) {
                return $val;
            }
        }
        return null;
    }

    /**
     * Generate the columns of mysql query for access attribute
     *
     * @param $cols
     * @param $startAccessArr
     * @param $endAccessArr
     * @param $volume
     * @return mixed
     */
    public static function generateColumnsForAccess($cols, $startAccessArr, $endAccessArr, $volume){
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
            $matchedItem = self::searchForItemName($accessItem['item'], $startAccessArr);
            $accessItemVal = 0;
            if ($matchedItem != null){
                $accessItemVal = $matchedItem['answers'][0]['ans'];
            }
            // Check the access attribute for end access
            $matchedItem2 = self::searchForItemName($accessItem['item'], $endAccessArr);
            $accessItemVal2 = 0;
            if ($matchedItem2!=null){
                $accessItemVal2 = $matchedItem2['answers'][0]['ans'];
            }
            array_push($cols, DB::raw("(($accessItemVal+$accessItemVal2)*$volume*$interstateColumn) As ".$interstateColumn."_price"));

            // Generate the column to calculate the total access pricing
            $totalPriceColumn .= "+(($accessItemVal+$accessItemVal2)*$volume*$interstateColumn)";
        }
        $totalPriceColumn .= "+(CASE WHEN locals.price_per*2*" . $commonSpace . " < locals.min_price THEN locals.min_price ELSE locals.price_per*2*" . $commonSpace . " END)";

        array_push($cols, DB::raw("($totalPriceColumn) As removal_price"));

        return $cols;
    }

    /**
     * Return depot object
     *
     * @param $request
     * @param $calenderDate
     * @return Builder|\Illuminate\Database\Query\Builder|null
     * @throws Exception
     */
    public static function getDepotObject($request, $calenderDate)
    {
        $weekDay = strtolower((new carbon($calenderDate))->dayName);
        $totalSpace = floatval($request->space);

        // check if the request has all params
        if ($request->has('start_lat') && $request->has('start_lng') && $request->has('end_lat') && $request->has('end_lng')) {
            // check if start address within a radius 6371
            $startAddrRadiusCalc = " 3959 * acos(
            cos( radians(" . $request->get('start_lat') . ") )
            * cos( radians( depots.lat ) )
            * cos( radians( depots.lng ) - radians(" . $request->get('start_lng') . ") )
            + sin( radians(" . $request->get('start_lat') . ") )
            * sin( radians( depots.lat ) )
            )";

            // check if end address within a radius
            $endAddrRadiusCalc = " 3959 * acos(
            cos( radians(" . $request->get('end_lat') . ") )
            * cos( radians( depots.lat ) )
            * cos( radians( depots.lng ) - radians(" . $request->get('end_lng') . ") )
            + sin( radians(" . $request->get('end_lat') . ") )
            * sin( radians( depots.lat ) )
            )";

            $model       = self::query();

            $cols = [
                "depots.*",
                'locals.location',
                'locals.radius',
                'locals.price_per',
                'locals.min_price',
                'locals.extra_person_price',
                'locals.weekdays',
                'companies.logo',
                'companies.about_us',
                DB::raw('companies.name as company_name'),
                DB::raw('companies.phone as company_phone')
//                DB::raw("(CASE WHEN locals.price_per*2*" . $commonSpace . " < locals.min_price THEN locals.min_price ELSE locals.price_per*2*" . $commonSpace . " END ) AS removal_price")
            ];
            // Generate the columns for the start access and end access
            $cols = self::generateColumnsForAccess($cols, $request->start_access, $request->end_access, $totalSpace);
            $model->select($cols);

            $model = $model->join(
                'locals', 'locals.depot_id', '=', 'depots.id'
            )->join(
                'companies', 'companies.id', '=', 'depots.company_id'
            )->leftJoin('interstate_configurations', 'depots.company_id', 'interstate_configurations.company_id')
            ->where(function ($query) use ($startAddrRadiusCalc) {
                // If start address is in radius range
                $query->where(DB::raw('locals.radius'), '>', DB::raw("( $startAddrRadiusCalc )"));
                $query->orWhereNull(DB::raw("( $startAddrRadiusCalc )"));
            })
            ->where(function ($query) use ($endAddrRadiusCalc) {
                // If end access is in radius range
                $query->where(DB::raw('locals.radius'), '>', DB::raw("( $endAddrRadiusCalc )"));
                $query->orWhereNull(DB::raw("( $endAddrRadiusCalc )"));
            })
                ->whereRaw("json_contains(`locals`.`weekdays`, '\"".$weekDay."\"')");

            return $model;
        }

        return null;
    }

    /**
     * Get depots within a radius
     *
     * @param $request
     * @return array
     * @throws Exception
     */
    public static function getDepotsByRadius($request)
    {
        $startDate = new Carbon($request->start_date);
        $endDate   = new Carbon($request->end_date);
        $moveSpace = $request->space;

        $depots = [];
        // find the depot matching for each date between start and end date
        while ($startDate->lte($endDate)) {
            $calenderDate = $startDate->toDateString();
            // Get the matched depots for particular date
            $model        = self::getDepotObject($request, $calenderDate);
            $depotResult = $model->groupBy('depots.company_id', 'depots.id')
                ->orderBy('removal_price')->get();

            $depotObj          = [];
            $depotObj['date']  = $calenderDate;
            $depotObj['price'] = 0;
            if (!is_null($depotResult)) {
                foreach ($depotResult as $depot) {
                    $depotObj['price'] = isset($depot) ? round(floatval($depot->removal_price), 2) : 0;
                    break;
                }
            }

            $depots[] = $depotObj;
            $startDate->addDay();
        }

        return $depots;
    }

    /**
     * Check the logic to match the depot radius
     *
     * @param $depot
     * @param $startLat
     * @param $startLng
     * @param $endLat
     * @param $endLng
     * @return bool
     */
    public static function filterMatchDepot($depot, $startLat, $startLng, $endLat, $endLng){
        // Work out distances from depot to uplift and delivery locations
        $startDistance 	= distance($depot->lat, $depot->lng, $startLat, $startLng);
        $endDistance 		= distance($depot->lat, $depot->lng, $endLat, $endLng);
        $betweenDistance 	= distance($startLat, $startLng, $endLat, $endLng);

        // fix weird bug with NAN when lead and depot locations are exactly the same
        if (is_nan($endDistance)) {
            $endDistance = 0.00;
        }
        if (is_nan($startDistance)) {
            $startDistance = 0.00;
        }
        if (is_nan($betweenDistance)) {
            $betweenDistance = 0.00;
        }
        if ($startDistance <= $depot->radius && $endDistance <= $depot->radius) {
            return true;
        }

        return false;
    }

    /**
     * Return local removal lists
     *
     * @param $request
     * @return array|null
     * @throws Exception
     */
    public static function getLocalRemovalLists($request)
    {
        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        // Get the depots data for the pickup date
        $model    = self::getDepotObject($request, $pickupDate);
        $depots = $model->groupBy('depots.company_id', 'depots.id')
            ->orderBy('removal_price')->get();

        $removals = [];
        $companyUniqueList = [];
        foreach ($depots as $key => $depot) {
            // If the company is already listed out from depot
            // second time it will not show
            if (in_array($depot->company_id, $companyUniqueList)){
                continue;
            }
            // Ge the delivery date from the pickup date
            $deliveryDateInitial = self::getDeliveryDate($request->pickup_date);
            $deliveryDate = ($request->delivery_date) ?? $deliveryDateInitial;

            $removalArr = [];
            $removalArr['id'] = $depot->id;
            $removalArr['company_id'] = $depot->company_id;
            $removalArr['company_name'] = $depot->company_name;
            $removalArr['logoFullUrl'] = !is_null($depot->logo) ? asset('storage/' . $depot->logo) : asset('storage/logo/company_logo.svg');
            $removalArr['company_phone'] = $depot->company_phone;
            $removalArr['delivery_date'] = $deliveryDate;
            $removalArr['removal_price'] = round(floatval($depot->removal_price), 2);
            $removalArr['storage_price'] = floatval(0);
            $removalArr['total_price'] = round(floatval($depot->removal_price), 2);
            $removalArr['ancillary_price'] = floatval(0);

            $removals[] = $removalArr;
            array_push($companyUniqueList, $depot->company_id);
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
        return (new Carbon($pickupDate))->addDay(1)->toDateString();
    }

    /**
     * Get removal details
     *
     * @param $request
     * @return |null
     * @throws Exception
     */
    public static function getRemovalDetails($request)
    {

        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        if ($request->matchId == ''){
            return null;
        }
        // Get the depot details for the pickup date
        $model = self::getDepotObject($request, $pickupDate);
        $removalDetails = $model->where('companies.id', $request->companyId)
            ->where('depots.id', $request->matchId)
            ->groupBy('depots.company_id', 'depots.id')
            ->get()->first();

        // Get the storage price
        $storagePrice = Lane::getStoragePrice((object)[
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
        $removalDetailsArr['removal_price'] = round(floatval($removalDetails->removal_price) , 2);
        $removalDetailsArr['storage_price'] = round(floatval($storagePrice),2);
        $removalDetailsArr['ancillary_price'] = round(floatval($ancillaryPrice['totalPrice']), 2);
        // Total price = removal price + storage price for extended date + ancillaries price
        $removalDetailsArr['total_price'] = round(floatval((floatval($removalDetails->removal_price) + floatval($storagePrice) + floatval($ancillaryPrice['totalPrice']))), 2);
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
    public static function getDepotRemovalPricing($request){

        $pickupDate = new Carbon($request->pickup_date);
        $pickupDate = $pickupDate->toDateString();
        $moveSpace  = $request->space;

        // Get the depot data for the pickup date
        $model = self::getDepotObject($request, $pickupDate);
        $removalDetails = $model->where('companies.id', $request->companyId)
            ->where('depots.id', $request->matchId)
            ->groupBy('depots.company_id', 'depots.id')
            ->get()->first();

        $pricingBreakdown = [];
        // Get the storage price
        $storagePrice = Lane::getStoragePrice((object)[
            'companyId'     => $request->companyId,
            'pickup_date'   => $request->pickup_date,
            'delivery_date' => (new Carbon($request->delivery_date))->addDay(1)->toDateString(),
            'removalType' => $request->removal_type]);

        // Get the ancillary price
        $ancillaryPrice = AncillaryService::getAncillaryServicePrice($request->moveId);
        $pricingBreakdown['Removal Price'] = round(floatval($removalDetails->removal_price), 2);
        $pricingBreakdown['Storage'] = round(floatval($storagePrice), 2);

        foreach ($ancillaryPrice as $key => $price){
            if(isset($price['type'])){
                $pricingBreakdown[$price['type']] = round($price['price']);
            }
        }

        // Total price = removal price + storage price for extended date + ancillaries price
        $totalPrice = round(floatval((
            $pricingBreakdown['Removal Price'] + $pricingBreakdown['Storage'] + $ancillaryPrice['totalPrice'])), 2);

        return ['priceDetails' => $pricingBreakdown, 'totalPrice' => $totalPrice];
    }
}
