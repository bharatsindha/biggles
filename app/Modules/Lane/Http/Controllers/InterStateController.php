<?php

namespace App\Modules\Lane\Http\Controllers;

use App\Http\Requests\StoreLane;
use App\Models\Company;
use App\Models\Lane;
use App\Facades\General;
use App\Models\Permission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InterStateController extends Controller
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
     * @return Response
     */
    public function index()
    {
        $isAccess = [];
        # To check the ACL access for lane
        if (Permission::checkAccessAllow('lane')) {
            General::log('SL000601', ['action_module' => 'lane']);
            $isAccess['lane'] = true;

            // Get the lane data
            $lanes = Lane::getAllLanes();
        }
        # To check the ACL access for the trip
        if (Permission::checkAccessAllow('trip')) {
            General::log('SL001401', ['action_module' => 'trip']);
            $isAccess['trip'] = true;
        }

        # If ACL access is not assigned to both the route
        if (empty($isAccess)) {
            Session::flash('error', "You don't have permission to access this module. Kindly contact administration.");
            return redirect('dashboard');
        }

        $templateFile = General::isSuperAdmin() ? 'index' : 'index_v2';
        return View('lane::interState.' . $templateFile, compact('isAccess', 'lanes'));
    }
}
