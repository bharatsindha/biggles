<?php

namespace App\Modules\Depot\Http\Controllers;

use App\Http\Requests\StoreLocal;
use App\Models\Company;
use App\Models\Depot;
use App\Models\Local;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Facades\General;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LocalController extends Controller
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
        return "local";
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
                // Get the locals for global search matched with keyword
                return (new Local())->getSearchResults($request->keyword);
            }else{
                // Get the list of locals
                return Local::getLocals($request);
            }
        }

        General::log('SL001201', ['action_module' => $this->moduleName]);
        return View('depot::local.index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        // Get the depots
        $depotOptions           = Depot::getDepotOptions();
        // Get the weekdays
        $weekDaysArr            = General::getWeekDays();
        // Get the companies
        $data['companyOptions'] = Company::getCompanyOptions();

        return View('depot::local.createOrUpdate', compact('depotOptions', 'weekDaysArr', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $depotId
     * @return Factory|View
     */
    public function createLocal($depotId)
    {
        // Get the depot details by id
        $depot = Depot::getDepotById($depotId);

        return View('depot::local.createOrUpdate', compact('depot'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLocal $request
     * @return RedirectResponse
     */
    public function store(StoreLocal $request)
    {

        // Save the local data into storage
        $local             = new Local($request->all());
        $local->weekdays   = json_encode($request->weekdays);
        $local->created_by = Auth::user()->id;
        if (!General::isSuperAdmin()) {
            $local->company_id = Auth::user()->company_id;
        }

        $local->min_price = !is_null($local->min_price) && !empty($local->min_price) ? $local->min_price : "0.00";
        $local->save();

        Session::flash('success', trans(
            'common.success_add_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL001202', [
            'action_module' => $this->moduleName,
            'parent_id'     => $local->id,
            'event_data'    => ['name' => $local->price_per, 'id' => $local->id]
        ]);

        return redirect()->route('local.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Local $local
     * @return Factory|View
     */
    public function show(Local $local)
    {
        $data['createdBy'] = User::getUserNameById($local->created_by);
        // Get the weekdays
        $weekDaysArr       = General::getWeekDays();
        $local->weekdays   = json_decode($local->weekdays);

        General::log('SL001205', [
            'action_module' => $this->moduleName,
            'parent_id'     => $local->id,
            'event_data'    => ['name' => $local->radius, 'id' => $local->id]
        ]);

        return View('depot::local.show', compact('local', 'data', 'weekDaysArr'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Local $local
     * @return Factory|View
     */
    public function edit(Local $local)
    {
        // Get the depots
        $depotOptions           = Depot::getDepotOptions();
        // Get the weekdays
        $weekDaysArr            = General::getWeekDays();
        $local->weekdays        = json_decode($local->weekdays);
        // Get the companies
        $data['companyOptions'] = Company::getCompanyOptions();

        return View('depot::local.createOrUpdate', compact('local', 'depotOptions', 'weekDaysArr', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreLocal $request
     * @param Local $local
     * @return RedirectResponse
     */
    public function update(StoreLocal $request, Local $local)
    {
        $local->fill($request->all());
        if (!General::isSuperAdmin()) {
            $local->company_id = Auth::user()->company_id;
        }
        $local->weekdays = json_encode($request->weekdays);
        $local->min_price = !is_null($local->min_price) && !empty($local->min_price) ? $local->min_price : "0.00";
        $local->save();

        Session::flash('success', trans(
            'common.success_update_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL001203', [
            'action_module' => $this->moduleName,
            'parent_id'     => $local->id,
            'event_data'    => ['name' => $local->price_per, 'id' => $local->id]
        ]);

        return redirect()->route('local.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Local $local
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Local $local)
    {
        General::log('SL001204', [
            'action_module' => $this->moduleName,
            'parent_id'     => $local->id,
            'event_data'    => ['name' => $local->price_per]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $local->delete();

        return redirect()->route('depot.show', $local->depot_id);
    }
}
