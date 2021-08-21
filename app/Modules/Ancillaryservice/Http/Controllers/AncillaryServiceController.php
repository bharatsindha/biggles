<?php

namespace App\Modules\Ancillaryservice\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\StoreAncillaryService;
use App\Models\AncillaryService;
use App\Models\Customer;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AncillaryServiceController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return AncillaryService::getAncillaryService($request);
        }

        General::log('SL000401', ['action_module' => $this->moduleName]);

        return View('ancillaryservice::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $data = AncillaryService::getOptionsFromConfiguration();

        return View('ancillaryservice::createOrUpdate', compact('data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAncillaryService $request
     * @return RedirectResponse|Redirector
     */
    public function store(StoreAncillaryService $request)
    {
        $ancillaryService = new AncillaryService($request->all());
        

        // 
        $ancillaryService->valued_inventory = !empty($request->valued_inventory) && 
        !is_null($request->valued_inventory) ? $request->valued_inventory : '0.00';
        // 
        $ancillaryService->price = !empty($request->price) && !is_null($request->price) ? $request->price : '0.00';

        # If added by a company agent
        $ancillaryService->created_by = Auth::user()->id;
        if (!General::isSuperAdmin()) {
            $ancillaryService->company_id = Auth::user()->company_id;
        }
        $ancillaryService->questions = json_encode(array_filter($request->questions));
        $ancillaryService->save();

        Session::flash('success', trans('common.success_add_msg', array(
            'module_name' => ucfirst($this->moduleName)
        )));

        General::log('SL000402', [
            'action_module' => $this->moduleName,
            'parent_id'     => $ancillaryService->id,
            'event_data'    => ['name' => $ancillaryService->type, 'id' => $ancillaryService->id]
        ]);

        return redirect('ancillaryservice');
    }

    /**
     * Display the specified resource.
     *
     * @param AncillaryService $ancillaryservice
     * @return Factory|View
     */
    public function show(AncillaryService $ancillaryservice)
    {
        $data['customerName'] = Customer::getCustomerNameById($ancillaryservice->customer_id);
        $ancillaryservice     = AncillaryService::setOptionsValueFromConfiguration($ancillaryservice);

        General::log('SL000405', [
            'action_module' => $this->moduleName,
            'parent_id'     => $ancillaryservice->id,
            'event_data'    => ['name' => $ancillaryservice->type, 'id' => $ancillaryservice->id]
        ]);

        return View('ancillaryservice::show', compact('ancillaryservice', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AncillaryService $ancillaryservice
     * @return Factory|View
     */
    public function edit(AncillaryService $ancillaryservice)
    {
        $ancillaryservice->questions = json_decode($ancillaryservice->questions);

        $data = AncillaryService::getOptionsFromConfiguration();

        return View('ancillaryservice::createOrUpdate', compact('ancillaryservice', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreAncillaryService $request
     * @param AncillaryService $ancillaryservice
     * @return RedirectResponse|Redirector
     */
    public function update(StoreAncillaryService $request, AncillaryService $ancillaryservice)
    {
        $ancillaryservice->fill($request->all());
        $ancillaryservice->questions = json_encode(array_filter($request->questions));
        $ancillaryservice->save();

        Session::flash('success', trans(
            'common.success_update_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL000403', [
            'action_module' => $this->moduleName,
            'parent_id'     => $ancillaryservice->id,
            'event_data'    => ['name' => $ancillaryservice->type, 'id' => $ancillaryservice->id]
        ]);

        return redirect('ancillaryservice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AncillaryService $ancillaryservice
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(AncillaryService $ancillaryservice)
    {
        General::log('SL000404',
            ['action_module' => $this->moduleName,
             'parent_id'     => $ancillaryservice->id,
             'event_data'    => ['name' => $ancillaryservice->type]]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $ancillaryservice->delete();

        return redirect('ancillaryservice');
    }

    /**
     * Get Module name
     *
     * @return string
     */
    public function getModuleName()
    {
        return "ancillaryservice";
    }
}
