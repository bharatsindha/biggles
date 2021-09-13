<?php

namespace App\Modules\Configuration\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\StoreCompany;
use App\Models\Company;
use App\Models\Configuration;
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
use Stripe\Stripe;
use Illuminate\View\View;

class ConfigurationController extends Controller
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
        return "configuration";
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
        // Get the configuration options
        $data = Configuration::getConfigurations();

        return View('configuration::createOrUpdate', compact('data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        // Manage the configuration
        $result = Configuration::manageConfiguration($request);

        General::log('SL001101', ['action_module' => $this->moduleName]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));

        return redirect('configuration');
    }

}
