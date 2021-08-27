<?php

namespace App\Modules\Trip\Http\Controllers;

use App\Http\Requests\StoreLane;
use App\Http\Requests\StoreTrip;
use App\Models\Lane;
use App\Models\LaneTieredPrice;
use App\Models\Truck;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Facades\General;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class TripController extends Controller
{
    /**
     * Module variable
     *
     * @var array
     */
    protected $moduleName;

    /**
     * Class Constructor.
     *
     * @return string
     */
    public function __construct()
    {
        $this->moduleName = $this->getModuleName();
    }

    /**
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "trip";
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companyId = isset($request->companyId) && $request->companyId>0 ? $request->companyId : 0;
            // Get the lanes data
            return Lane::getLanes(true, 0, $companyId);
        }
        General::log('SL001401', ['action_module' => $this->moduleName]);
        return View('trip::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the trucks
        $data['truckOptions']  = Truck::getTruckOptions();
        // Get the weekdays
        $data['pickupDaysArr'] = General::getWeekDays();
        // Get the companies
        $data['companyOptions']      = Company::getCompanyOptions();

        // Get the company name
        $companies = Company::get()->pluck('name', 'id')->toArray();
        return View('trip::createOrUpdate', compact('companies', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Create trip for lane
     *
     * @param Lane $trip
     * @return Factory|View
     */
    public function createTrip(Lane $trip)
    {
        // Get the trucks
        $data['truckOptions']  = Truck::getTruckOptions();
        // Get the weekdays
        $data['pickupDaysArr'] = General::getWeekDays();

        $trip->pickup_days   = json_decode($trip->pickup_days);
        $trip->delivery_days = json_decode($trip->delivery_days);

        $tripCreatedFromLane = true;

        // Get the companies
        $companies = Company::get()->pluck('name', 'id')->toArray();
        return View('trip::createOrUpdate',
            compact('companies', 'trip', 'data', 'tripCreatedFromLane'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreLane $request)
    {
        // Save trip into storage
        $trip             = new Lane($request->all());
        $trip->created_by = Auth::user()->id;
        $trip->waypoint   = (isset($trip->waypoint) && !is_null($trip->waypoint)) ? json_encode($trip->waypoint) : null;
        if (!General::isSuperAdmin()) {
            $trip->company_id = Auth::user()->company_id;
        }
        $trip->pickup_days   = json_encode($request->pickup_days);
        $trip->delivery_days = json_encode($request->delivery_days);
        $trip->save();

        // Loop through all if recurring trip
        $this->storeRecurringTrip($request, $trip);

        // Save all the tiered price for the trip
        $this->storeTieredPrice($request, $trip);

        Session::flash('success', trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        General::log('SL001402', [
            'action_module' => $this->moduleName,
            'parent_id'     => $trip->id,
            'event_data'    => ['name' => $trip->start_addr, 'id' => $trip->id]
        ]);

        // Add and go for one more create
        if ($request->has('__save_add_another')){
            return redirect()->route('lane.create');
        }
        return redirect()->route('lane.inter_state');
    }

    /**
     * Display the specified resource.
     *
     * @param Lane $lane
     * @return Response
     */
    public function show(Lane $trip)
    {
        General::log('SL001405', [
            'action_module' => $this->moduleName,
            'parent_id'     => $trip->id,
            'event_data'    => ['name' => $trip->start_addr, 'id' => $trip->id]
        ]);
        return View('trip::show', compact('trip'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lane $lane
     * @return Response
     */
    public function edit(Lane $trip)
    {
        $trip->pickup_days   = json_decode($trip->pickup_days);
        $trip->delivery_days = json_decode($trip->delivery_days);

        // Get trucks
        $data['truckOptions']  = Truck::getTruckOptions();
        // Get weekdays
        $data['pickupDaysArr'] = General::getWeekDays();
        // Get the companies
        $data['companyOptions']      = Company::getCompanyOptions();

        // Get the company name
        $companies = Company::get()->pluck('name', 'id')->toArray();
        return View('trip::createOrUpdate', compact('companies', 'trip', 'data'))->with('moduleName',
            $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Lane $lane
     * @return Response
     */
    public function update(Request $request, Lane $trip)
    {
        // Save trip into storage
        $previousDate      = $trip->start_date;
        $previousFrequency = $trip->frequency;
        $trip->fill($request->all());
        $trip->waypoint      = (isset($trip->waypoint) && !is_null($trip->waypoint)) ? json_encode($trip->waypoint) : null;
        $trip->pickup_days   = json_encode($request->pickup_days);
        $trip->delivery_days = json_encode($request->delivery_days);
        $trip->save();

        // Loop through all if the recurring trip
        $this->updateRecurringTrip($request, $trip, $previousDate, $previousFrequency);
        // Save tiered price for the trip
        $this->storeTieredPrice($request, $trip);        
        
        General::log('SL001403', [
            'action_module' => $this->moduleName,
            'parent_id'     => $trip->id,
            'event_data'    => ['name' => $trip->start_addr, 'id' => $trip->id]
        ]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));
        if ($request->has('__save_add_another')){
            return redirect()->route('lane.create');
        }
        return redirect()->route('lane.inter_state');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lane $trip
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroy(Lane $trip)
    {
        General::log('SL001402', [
            'action_module' => $this->moduleName,
            'parent_id'     => $trip->id,
            'event_data'    => ['name' => $trip->start_addr]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $trip->delete();

        return redirect()->route('lane.inter_state');
    }

    /**
     * Filter Lane by start and end points
     *
     * @param string $lane_name
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function filterLane($lane_name = '', Request $request)
    {
        if ($request->ajax()) {
            $trip_time = Config::get('muval.TRIP_TIME');
            if ($lane_name != '') {
                $lanes = Lane::where('name', 'like', $lane_name . '%')
                    ->limit(Config::get('muval.MAX_ITEMS_PER_PAGE'))
                    ->get()->toArray();
            } else {
                $lanes = Lane::limit(Config::get('muval.MAX_ITEMS_PER_PAGE'))->get()->toArray();
            }

            $html = view('trip::filter-lanes', compact('lanes', 'trip_time'))->render();
            return response()->json(['html' => $html, 'msg' => Lang::get('trip::trip.search_result_lanes')], 200);
        }
        return response()->json(['msg' => Lang::get('common.something_went_wrong')], 422);
    }

    /**
     * Add New Trip
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function addTrip(Request $request)
    {
        $lane = null;
        if ($request->has('lane_id') && $request->get('lane_id') > 0) {
            $lane = Lane::find($request->get('lane_id'));
        }
        $html = view('trip::add-trip', compact('lane'))->render();
        return response()->json(array('success' => true, 'html' => $html));
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function calendarView()
    {
        General::log('SL001406', ['action_module' => $this->moduleName]);
        return View('trip::calendar', ['moduleName' => $this->moduleName]);
    }

    /**
     * Store the recurring trip
     *
     * @param Request $request
     * @param $trip
     */
    protected function storeRecurringTrip(Request $request, $trip)
    {
        if ($request->has('recurring_trip') && $request->get('recurring_trip') == "on") {

            $startDate  = date('Y-m-d', strtotime($request->get('start_date')));
            $expiryDate = date('Y-m-d', strtotime($request->get('expiry_date')));

            # Loop through till expiry date
            if ($request->get('frequency') == 'weekly') {
                $startDate = date('Y-m-d', strtotime($startDate . ' +7 days'));
            } else {
                $startDate = date('Y-m-d', strtotime($startDate . ' +1 months'));
            }
            while (strtotime($startDate) <= strtotime($expiryDate)) {

                # create a new trip
                $newTrip             = new Lane($request->all());
                $newTrip->start_date = $startDate;
                $newTrip->parent_id  = $trip->id;
                $newTrip->created_by = Auth::user()->id;
                $newTrip->waypoint   = (isset($newTrip->waypoint) && !is_null($newTrip->waypoint)) ? json_encode($newTrip->waypoint) : null;
                if (General::isCompanyAgent()) {
                    $newTrip->company_id = Auth::user()->company_id;
                }
                $newTrip->save();

                if ($request->get('frequency') == 'weekly') {
                    $tempStartDate = date('Y-m-d', strtotime($startDate . ' +7 days'));
                } else {
                    $tempStartDate = date('Y-m-d', strtotime($startDate . ' +1 months'));
                }
                $startDate = $tempStartDate;

            }
        }
    }

    /**
     * Save trip tiered price
     *
     * @param Request $request
     * @param $trip
     */
    public function storeTieredPrice(Request $request, $trip)
    {
        // If it is tiered price
        if ($request->price_type == 'tiered') {
            $tieredPrices = $request->tiered_price;
            if (count($tieredPrices) > 0) {

                foreach ($tieredPrices as $key => $tieredPrice) { // For each tiered price

                    if ($tieredPrice['space_start_range'] != null &&
                        $tieredPrice['space_end_range'] != null &&
                        $tieredPrice['price'] != null) {

                        $id = isset($tieredPrice["id"]) ? $tieredPrice["id"] : null;

                        $tieredPriceVal["lane_id"]           = $trip->id;
                        $tieredPriceVal["space_start_range"] = $tieredPrice['space_start_range'];
                        $tieredPriceVal["space_end_range"]   = $tieredPrice['space_end_range'];
                        $tieredPriceVal["price"]             = $tieredPrice['price'];
                        $tieredPriceVal["price_type"]        = 'tiered';

                        // Save the tiered price
                        LaneTieredPrice::updateOrCreate(["id" => $id], $tieredPriceVal);
                    }
                }
            }
        } else {
            // If it is single price
            if ($request->min_price != null){

                $id = isset($request->single_price_id) ? $request->single_price_id : null;

                $tieredPriceVal["lane_id"]           = $trip->id;
                $tieredPriceVal["space_start_range"] = 0;
                $tieredPriceVal["space_end_range"]   = 5000;
                $tieredPriceVal["price"]             = $request->min_price;
                $tieredPriceVal["price_type"]        = 'single';

                // Save single price into storage
                LaneTieredPrice::updateOrCreate(["id" => $id], $tieredPriceVal);
            }
        }
    }


    /**
     * Update the recurring trip
     *
     * @param Request $request
     * @param $trip
     */
    protected function updateRecurringTrip(Request $request, $trip, $previousDate, $previousFrequency)
    {

        if ($request->has('recurring_trip') && $request->get('recurring_trip') == 'on') {

            $startDate  = date('Y-m-d', strtotime($request->get('start_date')));
            $expiryDate = date('Y-m-d', strtotime($request->get('expiry_date')));

            # Loop through till expiryDate
            if ($request->get('frequency') == 'weekly') {
                $startDate = date('Y-m-d', strtotime($startDate . ' +7 days'));
            } else {
                $startDate = date('Y-m-d', strtotime($startDate . ' +1 months'));
            }

            if ($previousFrequency == 'weekly') {
                $previousDate = date('Y-m-d', strtotime($previousDate . ' +7 days'));
            } else {
                $previousDate = date('Y-m-d', strtotime($previousDate . ' +1 months'));
            }

            while (strtotime($startDate) <= strtotime($expiryDate)) {

                # create a new trip
                $newTrip = Lane::where('parent_id', $trip->id)
                    ->where('start_date', $previousDate)
                    ->first();

                if (is_null($newTrip)) {
                    $newTrip = new Lane($request->all());
                }

                // Save recurring trip into storage
                $newTrip->start_date = $startDate;
                $newTrip->parent_id  = $trip->id;
                $newTrip->created_by = Auth::user()->id;
                $newTrip->waypoint   = json_encode($request->waypoint);
                $newTrip->save();

                if ($request->get('frequency') == 'weekly') {
                    $tempStartDate = date('Y-m-d', strtotime($startDate . ' +7 days'));
                } else {
                    $tempStartDate = date('Y-m-d', strtotime($startDate . ' +1 months'));
                }
                // update previous
                if ($previousFrequency == 'weekly') {
                    $previousDate = date('Y-m-d', strtotime($previousDate . ' +7 days'));
                } else {
                    $previousDate = date('Y-m-d', strtotime($previousDate . ' +1 months'));
                }
                $startDate = $tempStartDate;
            }

            // remove all trips after expiry
            $newTrip = Lane::where('parent_id', $trip->id)
                ->where('start_date', '>', $expiryDate)
                ->delete();

        }

    }

    /**
     * Display a recurring trips.
     *
     * @param Lane $trip
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function recurringTrips(Lane $trip, Request $request)
    {
        if ($request->ajax()) {
            return Lane::getLanes(true, $trip->id);
        }
    }

    /**
     * get Trips when month changes
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrips(Request $request)
    {
        #General::log('SL000301');
        $trips = Lane::getTripsByMonth($request);
        return response()->json($trips);
    }

}
