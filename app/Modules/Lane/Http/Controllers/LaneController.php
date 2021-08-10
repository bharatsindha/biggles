<?php

namespace App\Modules\Lane\Http\Controllers;

use App\Http\Requests\StoreLane;
use App\Models\Company;
use App\Models\Lane;
use App\Facades\General;
use App\Models\LaneTieredPrice;
use App\Models\Truck;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LaneController extends Controller
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
        return "lane";
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

            if (isset($request->keyword) && !empty($request->keyword)){
                // Get the lanes for global search matched with keyword
                return (new Lane())->getSearchResults($request->keyword);
            }else{
                $companyId = isset($request->companyId) && $request->companyId>0 ? $request->companyId : 0;
                // Get the lane listing
                return Lane::getLanes(false, 0, $companyId);
            }

        }
        General::log('SL000601', ['action_module' => $this->moduleName]);

        return View('lane::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the companies data
        $companies = Company::get()->pluck('name', 'id')->toArray();

        // Get the trucks
        $data['truckOptions']  = Truck::getTruckOptions();
        // Get the weekdays
        $data['pickupDaysArr'] = General::getWeekDays();
        // Get the companies
        $data['companyOptions']      = Company::getCompanyOptions();

        return View('lane::createOrUpdate', compact('companies', 'data'))
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
        $lane             = new Lane($request->all());
        $lane->waypoint   = (isset($lane->waypoint) && !is_null($lane->waypoint)) ? json_encode($lane->waypoint) : null;
        $lane->created_by = Auth::user()->id;
        if (General::isCompanyAgent()) {
            $lane->company_id = Auth::user()->company_id;
        }
        $lane->pickup_days   = json_encode($request->pickup_days);
        $lane->delivery_days = json_encode($request->delivery_days);
        $lane->save();

        // Save lane price single or tiered
        $this->storeTieredPrice($request, $lane);

        Session::flash('success', trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        General::log('SL000602', [
            'action_module' => $this->moduleName,
            'parent_id'     => $lane->id,
            'event_data'    => ['name' => $lane->start_addr, 'id' => $lane->id]
        ]);

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
    public function show(Lane $lane)
    {
        General::log('SL000605', [
            'action_module' => $this->moduleName,
            'parent_id'     => $lane->id,
            'event_data'    => ['name' => $lane->start_addr, 'id' => $lane->id]
        ]);

        return View('lane::show', compact('lane'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lane $lane
     * @return Response
     */
    public function edit(Lane $lane)
    {
        // Get the company data
        $companies = Company::get()->pluck('name', 'id')->toArray();

        $lane->pickup_days   = json_decode($lane->pickup_days);
        $lane->delivery_days = json_decode($lane->delivery_days);

        // Get the trucks
        $data['truckOptions']  = Truck::getTruckOptions();
        // Get the weekdays
        $data['pickupDaysArr'] = General::getWeekDays();
        // Get the companies
        $data['companyOptions']      = Company::getCompanyOptions();

        return View('lane::createOrUpdate', compact('companies', 'lane', 'data'))
            ->with('moduleName',
                $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreLane $request
     * @param Lane $lane
     * @return Response
     */
    public function update(StoreLane $request, Lane $lane)
    {
        // Save the lane into storage
        $lane->fill($request->all());
        $lane->waypoint      = (isset($lane->waypoint) && !is_null($lane->waypoint)) ? json_encode($lane->waypoint) : null;
        $lane->pickup_days   = json_encode($request->pickup_days);
        $lane->delivery_days = json_encode($request->delivery_days);
        $lane->save();

        // Save lane price single or tiered
        $this->storeTieredPrice($request, $lane);

        General::log('SL000603', [
            'action_module' => $this->moduleName,
            'parent_id'     => $lane->id,
            'event_data'    => ['name' => $lane->start_addr, 'id' => $lane->id]
        ]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));

        // Add and go for one more create
        if ($request->has('__save_add_another')){
            return redirect()->route('lane.create');
        }
        return redirect()->route('lane.inter_state');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lane $lane
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Lane $lane)
    {
        General::log('SL000602', [
            'action_module' => $this->moduleName,
            'parent_id'     => $lane->id,
            'event_data'    => ['name' => $lane->start_addr]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $lane->delete();

        return redirect()->route('lane.inter_state');
    }

    /**
     * Save the tiered prices in the storage
     *
     * @param Request $request
     * @param $lane
     */
    public function storeTieredPrice(Request $request, $lane)
    {
        // If it is tiered price
        if ($request->price_type == 'tiered') {
            $tieredPrices = $request->tiered_price;
            if (count($tieredPrices) > 0) {

                // For each tiered price
                foreach ($tieredPrices as $key => $tieredPrice) {

                    if ($tieredPrice['space_start_range'] != null &&
                        $tieredPrice['space_end_range'] != null &&
                        $tieredPrice['price'] != null) {

                        $id = isset($tieredPrice["id"]) ? $tieredPrice["id"] : null;

                        $tieredPriceVal["lane_id"]           = $lane->id;
                        $tieredPriceVal["space_start_range"] = $tieredPrice['space_start_range'];
                        $tieredPriceVal["space_end_range"]   = $tieredPrice['space_end_range'];
                        $tieredPriceVal["price"]             = $tieredPrice['price'];
                        $tieredPriceVal["price_type"]        = 'tiered';

                        // Save the price into storage
                        LaneTieredPrice::updateOrCreate(["id" => $id], $tieredPriceVal);
                    }
                }
            }
        } else {
            // If it is single price
            if ($request->min_price != null){

                $id = isset($request->single_price_id) ? $request->single_price_id : null;

                $tieredPriceVal["lane_id"]           = $lane->id;
                $tieredPriceVal["space_start_range"] = 0;
                $tieredPriceVal["space_end_range"]   = 5000;    // Set the default price 5000
                $tieredPriceVal["price"]             = $request->min_price;
                $tieredPriceVal["price_type"]        = 'single';

                LaneTieredPrice::updateOrCreate(["id" => $id], $tieredPriceVal);
            }
        }
    }
}
