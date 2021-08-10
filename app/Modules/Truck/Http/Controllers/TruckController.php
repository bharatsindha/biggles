<?php

namespace App\Modules\Truck\Http\Controllers;

use App\Http\Requests\StoreTrip;
use App\Http\Requests\StoreTruck;
use App\Models\Truck;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use App\Facades\General;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TruckController extends Controller
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
        return "truck";
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
                // Get the trucks for global search matched with keyword
                return (new Truck())->getSearchResults($request->keyword);
            }else{
                // Get the trucks listing
                return Truck::getTrucksDatatable();
            }
        }

        General::log('SL001501', ['action_module' => $this->moduleName]);
        return View('truck::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the companies
        $data['companyOptions'] = Company::getCompanyOptions();

        return View('truck::createOrUpdate', compact('data'))->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTruck $request
     * @return RedirectResponse
     */
    public function store(StoreTruck $request)
    {
        // Save the truck into storage
        $truck             = new Truck($request->all());
        $truck->created_by = Auth::user()->id;
        if (General::isCompanyAgent()) {
            $truck->company_id = Auth::user()->company_id;
        }
        $truck->save();

        General::log('SL001502', [
            'action_module' => $this->moduleName,
            'parent_id'     => $truck->id,
            'event_data'    => ['name' => $truck->name, 'id' => $truck->id]
        ]);

        Session::flash('success', trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        return redirect('truck');
    }

    /**
     * Display the specified resource.
     *
     * @param Truck $truck
     * @return Factory|View
     */
    public function show(Truck $truck)
    {
        General::log('SL001505', [
            'action_module' => $this->moduleName,
            'parent_id'     => $truck->id,
            'event_data'    => ['name' => $truck->name, 'id' => $truck->id]
        ]);

        return View('truck::show', compact('truck'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Truck $truck
     * @return Factory|View
     */
    public function edit(Truck $truck)
    {
        // Get the companies
        $data['companyOptions'] = Company::getCompanyOptions();

        return View('truck::createOrUpdate', compact('data', 'truck'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTruck $request
     * @param Truck $truck
     * @return RedirectResponse
     */
    public function update(StoreTruck $request, Truck $truck)
    {
        $truck->fill($request->all());
        $truck->save();

        General::log('SL001503', [
            'action_module' => $this->moduleName,
            'parent_id'     => $truck->id,
            'event_data'    => ['name' => $truck->name, 'id' => $truck->id]
        ]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));
        return redirect('truck');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Truck $truck
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Truck $truck)
    {
        General::log('SL001504', [
            'action_module' => $this->moduleName,
            'parent_id'     => $truck->id,
            'event_data'    => ['name' => $truck->name]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $truck->delete();

        return redirect('truck');
    }

}
