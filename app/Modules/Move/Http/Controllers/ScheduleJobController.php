<?php

namespace App\Modules\Move\Http\Controllers;

use App\Facades\General;
use App\Models\Move;
use App\Models\Truck;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;
use Throwable;

class ScheduleJobController extends Controller
{
    /*
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
        return "schedule-job";
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
        // Get the pending moves to schedule
        $pendingJobs = Move::getPendingMovesToSchedule();

        // This logic is to show the welcome scheduler screen to the first time visitor
        $firstTimeVisitor = false;
        if (Auth::user()->viewed_scheduler == 0) {
            $firstTimeVisitor = true;
            $user = User::find(Auth::user()->id);
            if ($user !== null) {
                $user->viewed_scheduler = 1;
                $user->save();
            }
        }

        return View('move::schedule_job.index', [
            'moduleName'       => $this->moduleName,
            'firstTimeVisitor' => $firstTimeVisitor,
            'pendingJobs'      => $pendingJobs]);
    }

    /**
     * Return truck resources in JSON format
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTruckResources(Request $request)
    {
        // Get the truck resources
        $truckResource = (Truck::getTruckResources())->toArray();
//        array_unshift($truckResource, [
//            'id'         => 0,
//            'title'      => 'Unassigned truck',
//            'eventColor' => 'orange'
//        ]);

        return response()->json($truckResource);
    }

    /**
     * Return scheduled jobs in calender date range
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getScheduledJobs(Request $request)
    {
        // Get the scheduled job lists
        DB::enableQueryLog();
        $scheduledJobs = Move::getScheduledJobs($request);

        return response()->json($scheduledJobs);
    }

    /**
     * Filter the pending jobs to schedule
     *
     * @param string $jobName
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function filterJobs($jobName = '', Request $request)
    {
        if ($request->ajax()) {
            // Get the pending jobs to schedule
            $pendingJobs = Move::getPendingMovesToSchedule($jobName);
            $html = view('move::schedule_job.filter_jobs', compact('pendingJobs'))->render();

            return response()->json([
                'html' => $html,
                'msg'  => Lang::get('move::move.search_result_job')], 200);
        }

        return response()->json(['msg' => Lang::get('common.something_went_wrong')], 422);
    }

    /**
     * Return the html design of scheduler job title
     *
     * @param Move $move
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getDragJobHtml(Move $move, Request $request)
    {
        if ($request->ajax()) {
            $result = $move;
            // Remove australia  word from the start and end address
            $result->start_addr = General::removeAusWord($result->start_addr);
            $result->end_addr = General::removeAusWord($result->end_addr);
            $html = view('move::schedule_job.event_title', compact('result'))->render();

            return response()->json([
                'html' => $html,
                'msg'  => Lang::get('move::move.search_result_job')], 200);
        }

        return response()->json(['msg' => Lang::get('common.something_went_wrong')], 422);
    }
}
