<?php

namespace App\Modules\Depot\Http\Controllers;

use App\Models\Company;
use App\Models\Depot;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Facades\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreRole;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DepotController extends Controller
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
        return "depot";
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
                // Get the depots for global search matched with keyword
                return (new Depot())->getSearchResults($request->keyword);
            }else{
                // Get the depots
                return Depot::getDepots();
            }
        }
        General::log('SL000501', ['action_module' => $this->moduleName]);
        return View('depot::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Exception
     */
    public function indexCompany(Request $request)
    {
        // Get the depots data
        $depots = Depot::getDepotModel()->get();
        General::log('SL000501', ['action_module' => $this->moduleName]);
        return View('depot::index_company', ['moduleName' => $this->moduleName, 'depots' => $depots]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the list of companies
        $companies = Company::get()->pluck('name', 'id')->toArray();
        return View('depot::createOrUpdate', compact('companies'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $depot             = new Depot($request->all());
        $depot->created_by = Auth::user()->id;
        // If it is company agent
        if (General::isCompanyAgent()) {
            $depot->company_id = Auth::user()->company_id;
        }
        $depot->save();

        Session::flash('success', trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        General::log('SL000502', [
            'action_module' => $this->moduleName,
            'parent_id'     => $depot->id,
            'event_data'    => ['name' => $depot->addr, 'id' => $depot->id]
        ]);

        if (General::isSuperAdmin()) {
            return redirect('depot');
        } else {
            return redirect('depot-company');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Depot $depot
     * @return Response
     */
    public function show(Depot $depot)
    {
        General::log('SL000505', [
            'action_module' => $this->moduleName,
            'parent_id'     => $depot->id,
            'event_data'    => ['name' => $depot->addr, 'id' => $depot->id]
        ]);

        return View('depot::show', compact('depot'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Depot $depot
     * @return Response
     */
    public function edit(Depot $depot)
    {
        // Get the list of companies
        $companies = Company::get()->pluck('name', 'id')->toArray();

        return View('depot::createOrUpdate', compact('companies', 'depot'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Depot $depot
     * @return Response
     */
    public function update(Request $request, Depot $depot)
    {
        // Save the depot into storage
        $depot->fill($request->all());
        $depot->save();

        General::log('SL000503', [
            'action_module' => $this->moduleName,
            'parent_id'     => $depot->id,
            'event_data'    => ['name' => $depot->addr, 'id' => $depot->id]
        ]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));

        if (General::isSuperAdmin()) {
            return redirect('depot');
        } else {
            return redirect('depot-company');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Depot $depot
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Depot $depot)
    {
        General::log('SL000503', [
            'action_module' => $this->moduleName,
            'parent_id'     => $depot->id,
            'event_data'    => ['name' => $depot->addr]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $depot->delete();
        return redirect('depot');
    }
}
