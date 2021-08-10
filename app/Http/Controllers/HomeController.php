<?php

namespace App\Http\Controllers;

use App\Facades\General;
use App\Models\Configuration;
use App\Models\Move;
use App\Models\Notification;
use App\Models\Permission;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * Module variable
     *
     * @var array
     */
    protected $moduleName;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->moduleName = $this->getModuleName();
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return Move::getPendingMovesDatatable();
        }

        // This logic is to show the on boarding flag to the first time company agent
        $onBoarding = false;
        if (!General::isSuperAdmin()) {
            $companyRef = Auth::user()->company;
            if ($companyRef->onboarding_flag == 1) {
                $onBoarding                  = true;
                $companyRef->onboarding_flag = 2;
                $companyRef->save();
            }
        }

        $graphType = 'monthly';
        if (isset($request->graph) && $request->graph != ''){
            $graphType = $request->graph;
        }

        $chartData = Move::getChartData($graphType);

        return view('home.dashboard',
            ['moduleName' => $this->moduleName, 'onBoarding' => $onBoarding, 'chartData' => $chartData,
                'graphType' => $graphType]);
    }

    /**
     * Update the seen notification
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function notificationSeen(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::seenNotification();

            return response()->json(['msg' => trans('common.notification_updated')], 200);
        }
    }

    /**
     * search keyword globally
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $result       = array();
        $totalRecords = 0;
        $keyword      = trim($request->input('keyword'));
        $options      = ["count" => true];


        if (isset($keyword) && !empty($keyword) && !is_null($keyword)) {
            $modules = Configuration::getModuleDetails();

            foreach ($modules as $key => $module) {
                if (Permission::checkAccessAllow($key) && $module["searchable"]) {

                    $model   = new $module["model"]();
                    $results = $model->getSearchResults($keyword, $options);
                    if (is_int($results) && $results == 0) {
                        continue;
                    }
                    $totalRecords += $results;
                    $result[$key] = $results;
                }
            }
        }

        return view("search.index", ['moduleName' => 'search'])
            ->withResults($result)
            ->withKeyword($keyword)->with('totalRecords', $totalRecords);
    }

    /**
     * Get Module name
     *
     * @return string
     */
    public function getModuleName()
    {
        return "dashboard";
    }
}
