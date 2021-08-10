<?php

namespace App\Models;

use App\Facades\General;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class Move extends Crud
{
    use SoftDeletes;

    protected $fillable = ['customer_id',
        'company_id',
        'truck_id',
        'match_id',
        'start_addr',
        'start_lat',
        'start_lng',
        'start_city',
        'start_postcode',
        'start_access',
        'start_date',
        'end_addr',
        'end_lat',
        'end_lng',
        'end_city',
        'end_postcode',
        'end_access',
        'end_date',
        'stage',
        'type',
        'status',
        'total_price',
        'amount_due',
        'deposit',
        'fee',
        'space',
        'matches',
        'inventory',
        'dwelling_type',
        'dwelling_size',
        'pickup_window_start',
        'pickup_window_end',
        'delivery_window_start',
        'delivery_window_end',
        'current_stage',
        'created_by'];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function payment()
    {
        return $this->hasMany('App\Models\Payment', 'move_id')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function ancillaryServices()
    {
        return $this->belongsToMany(AncillaryService::class, 'move_ancillary_rel', 'move_id', 'ancillary_id')->withPivot('answers');;
    }

    /**
     * Return job status id in array
     *
     * @param $status
     * @return array
     */
    public static function getJobStatusId($status)
    {
        // Get the job status
        $arrayStatus = General::jobStatusArray();
        foreach ($arrayStatus as $statusArr) {
            if ($statusArr['id'] == $status) {
                return $statusArr['moveStatus'];
            }
        }

        return $arrayStatus['pending']['moveStatus'];
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of move model
        return self::getMovesModel();
    }

    /**
     * Global search function
     *
     * @param $keyword
     * @param array $option
     * @return JsonResponse
     * @throws Exception
     */
    public function getSearchResults($keyword, $option = [])
    {
        $options['matchRaw'] = [
            "CONCAT(customers.first_name, ' ', customers.last_name)" => $keyword,
        ];
        $options['match'] = [
            "companies.name" => $keyword,
            "start_addr" => $keyword,
            "end_addr" => $keyword,
        ];

        // Get the global search data
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            // Get the count of matched search result
            $result = $model->count();
        }else{
            // Get the datatable of moves
            $result = self::moveDatatableObject($model);
        }

        return $result;
    }

    /**
     * Get Job Title
     * 
     * @return array|array[]
     */
    public static function getJobTitleCount()
    {
        // Get the move data
        $model     = self::getMovesModel();
        $jobCounts = $model->select('moves.status as statusId', DB::raw('count(*) as totalStatus'))
            ->groupBy('moves.status')->get();

        // Get the job status
        $arrayStatus = General::jobStatusArray();

        foreach ($jobCounts as $jobCount) {
            if (in_array($jobCount->statusId, self::getJobStatusId(1))) {
                $arrayStatus["pending"]["count"] += $jobCount->totalStatus;
            }
            if (in_array($jobCount->statusId, self::getJobStatusId(2))) {
                $arrayStatus["in_progress"]["count"] += $jobCount->totalStatus;
            }
            if (in_array($jobCount->statusId, self::getJobStatusId(3))) {
                $arrayStatus["completed"]["count"] += $jobCount->totalStatus;
            }
            /*if (in_array($jobCount->statusId, self::getJobStatusId(4))) {
                $arrayStatus["closed"]["count"] += $jobCount->totalStatus;
            }*/
        }

        return $arrayStatus;
    }

    /**
     * Get all Move Lists
     *
     * @return JsonResponse
     * @throws Exception
     */
    static public function getMoves($request)
    {
        // Get the job status ids
        $statusArr = self::getJobStatusId($request->status);

        // Get the object of move model
        $model = self::getMovesModel();
        $model->whereIn('moves.status', $statusArr);

        $moveStatus = $request->status;
        // Get the datatable of moves
        return self::moveDatatableObject($model, $moveStatus);
    }

    /**
     * Generate the datatable object
     *
     * @param $model
     * @param int $moveStatus
     * @return JsonResponse
     * @throws Exception
     */
    public static function moveDatatableObject($model, $moveStatus = 0){
        $user = Auth::user();

        return Datatables::eloquent($model)
            ->addColumn('actions', function ($move) use ($user, $moveStatus) {
                $action = '';
                // If it is pending to accept jobs
                if ($moveStatus == 1) {
                    $action .= View('layouts.actions.view')->with('model', $move)->with('route',
                        'move.job_details')->with('title', 'View and accept');
                    if ($user->access_level == 0) {
                        $action .= View('layouts.actions.delete')->with('model', $move)
                            ->with('route', 'move.destroy');
                    }
                } else {
                    $action .= View('layouts.actions.view')->with('model', $move)->with('route', 'move.job_details');
                    if ($user->access_level == 0) {
                        $action .= View('layouts.actions.edit')->with('model', $move)->with('route', 'move.edit');
                        $action .= View('layouts.actions.delete')->with('model', $move)
                            ->with('route', 'move.destroy');
                    }
                }

                return $action .= '';
            })
            ->addColumn('status', function ($q) {
                if ($q->status == '') {
                    $q->status = 'Pending';
                }
                return '<span class="kt-badge--inline kt-badge--success kt-font-bold status_bg">' . $q->status . '</span>';

                return $action;
            })
            ->addColumn('pickup_date', function ($q) {
                return Carbon::parse($q->pickup_window_start)->format('d/m/Y');
            })
            ->addColumn('is_complete', function ($q) {
                $isComplete = '';
                $isActive   = '';
                $clickable  = 'onclick="changeToComplete( ' . $q->id . ')"';
                if ($q->statusId == Config::get('muval.MOVE_COMPLETED_STATUS_ID')) {
                    $isComplete = 'checked';
                    $clickable  = 'disabled';
                    $isActive   = 'active';
                }
                return '<div class="switch switch__is_complete"><label class="switch__is_complete"><input type="checkbox" ' . $isComplete . $clickable . ' name="switch__is_complete" class="alert-status switch__is_complete" ><span class="lever switch__is_complete ' . $isActive . '"></span></label></div>';
            })
            ->addColumn('start_address', function ($q) {
                if (!is_null($q->start_addr) && !empty($q->start_addr)) {
                    return $q->start_addr;
                } else {
                    return '';
                }
            })
            ->addColumn('end_address', function ($q) {
                if (!is_null($q->end_addr) && !empty($q->end_addr)) {
                    return $q->end_addr;
                } else {
                    return '';
                }
            })->addColumn('space', function ($q) {
                return $q->space . 'm3';
            })
            ->addColumn('total_price', function ($q) {
                return "$" . sbNumberFormat($q->total_price);
            })
            ->rawColumns(['actions', 'start_address', 'status', 'is_complete'])
            ->make(true);
    }

    /**
     * Get all move lists pending for approval
     *
     * @return JsonResponse
     * @throws Exception
     */
    static public function getPendingMovesDatatable()
    {
        $options['pendingStatus'] = true;
        $options['stage'] = Configuration::STAGE_JOB;

        // Get the pending move data
        $model = self::getMovesModel($options);
        $user  = Auth::user();

        return Datatables::eloquent($model)
            ->addColumn('status', function ($move) {
                return '<span class="kt-badge--inline kt-badge--success kt-font-bold status_bg">Pending</span>';
            })
            ->addColumn('actions', function ($move) use ($user) {
                $action = '';
                $action .= View('layouts.actions.view')->with('model', $move)->with('route',
                    'move.job_details')->with('title', 'View and accept');
                if ($user->access_level == 0) {
                    $action .= View('layouts.actions.delete')->with('model', $move)
                        ->with('route', 'move.destroy');
                }
                $action .= '';

                return $action;
            })
            ->addColumn('is_complete', function ($q) {
                $isComplete = '';
                $clickable  = 'onclick="changeToComplete( ' . $q->id . ')"';
                if ($q->status == Config::get('muval.MOVE_COMPLETED_STATUS_ID')) {
                    $isComplete = 'checked';
                    $clickable  = 'disabled';
                }
                return '<div class="switch switch__is_complete"><label class="switch__is_complete"><input type="checkbox" ' . $isComplete . $clickable . ' name="switch__is_complete" class="alert-status switch__is_complete" ><span class="lever switch__is_complete"></span></label></div>';
            })
            ->addColumn('start_address', function ($q) {
                if (!is_null($q->start_addr) && !empty($q->start_addr)) {
                    return $q->start_addr . ', ' . $q->start_city . ', ' . $q->start_postcode;
                } else {
                    return '';
                }
            })
            ->addColumn('end_address', function ($q) {
                if (!is_null($q->end_addr) && !empty($q->end_addr)) {
                    return $q->end_addr . ', ' . $q->end_city . ', ' . $q->end_postcode;
                } else {
                    return '';
                }
            })->addColumn('space', function ($q) {
                return $q->space . 'm3';
            })
            ->addColumn('total_price', function ($q) {
                return "$" . sbNumberFormat($q->total_price);
            })
            ->rawColumns(['actions', 'status', 'is_complete'])
            ->make(true);

    }

    /**
     * Return the leads
     *
     * @param null $id
     * @param null $request
     * @return Builder[]|Collection
     */
    public static function getLeads($id = null, $request = null)
    {
        $model = self::query();
        $cols  = ["moves.*",
            DB::raw('concat(customers.first_name, " ", customers.last_name) as customer_name'),
            'customers.email as customer_email',
            'customers.phone as customer_phone',
            'customers.created_at as created_date',
            'conf.option_value as status_value'
        ];

        if (isset($id) && $id > 0) {
            $model->where('moves.id', '=', $id);
        }

        // If request has status filter
        if (!is_null($request) && $request->has('status')) {
            $status = $request->get('status');
            if($status && $status!=''){
                // check if valid move status
                $statusId = Configuration::getOptionIdByValue('move_status', $status);
                if($statusId && $statusId > 0){
                    $model->where('moves.status', $statusId);
                } else {
                    $model->whereNull('moves.status');
                }

            }
        }

        // If request has stage filter
        if (!is_null($request) && $request->has('stage')) {
            $stage = $request->get('stage');
            if($stage && $stage!=''){
                // check if valid move stage
                $stageId = Configuration::getOptionIdByValue('move_stage', $stage);
                if($stageId && $stageId > 0){
                    $model->where('moves.stage', $stageId);
                } else{
                    $model->whereNull('moves.stage');
                }

            }
        }

        // If request has start date filter
        if (!is_null($request) && $request->has('start_date')) {
            $startDate = $request->get('start_date');
            if($startDate && $startDate!=''){
                // check if valid date format
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$startDate)) {
                    $model->where('moves.created_at', '>=', $startDate);
                }
            }
        }

        // If request has end date filter
        if (!is_null($request) && $request->has('end_date')) {
            $endDate = $request->get('end_date');
            if($endDate && $endDate!=''){
                // check if valid date format
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$endDate)) {
                    $model->where('moves.created_at', '<=', $endDate);
                }

            }
        }

        return $model->select($cols)
            ->join('configurations as conf', 'conf.id', '=', 'moves.status')
            ->join('customers', 'customers.id', '=', 'moves.customer_id')
            ->get();
    }

    /**
     * Return Pending move model details
     *
     * @param array $options
     * @return Builder
     */
    public static function getMovesModel($options = [])
    {
        // Get the query
        $model = self::query();
        $user  = Auth::user();

        if (isset($options['cols']) && !is_null($options['cols'])) {
            $cols = $options['cols'];
        } else {
            $cols = ["moves.*",
                DB::raw('concat(customers.first_name," ", customers.last_name) as customer_name'),
                'companies.name as company_name',
                'conf1.option_value as stage',
                'conf2.option_value as type',
                'conf3.option_value as status',
                'moves.status as statusId'
            ];
        }
        $model->select($cols);

        if (General::isCompanyAgent()) {
            $model->where('moves.company_id', $user->company_id);
        }

        $model->leftJoin(
            'customers', 'customers.id', '=', 'moves.customer_id'
        );
        $model->leftJoin(
            'companies', 'companies.id', '=', 'moves.company_id'
        );
        $model->leftJoin(
            'configurations as conf1', 'conf1.id', '=', 'moves.stage'
        );
        $model->leftJoin(
            'configurations as conf2', 'conf2.id', '=', 'moves.type'
        );
        $model->leftJoin(
            'configurations as conf3', 'conf3.id', '=', 'moves.status'
        );

        // If requested for the date range
        if (isset($options['start']) && !is_null($options['start']) &&
            isset($options['end']) && !is_null($options['end'])) {
            $startDate = Carbon::parse($options['start'])->format('Y-m-d H:i:s');
            $endDate   = Carbon::parse($options['end'])->format('Y-m-d H:i:s');

            $model->whereRaw('moves.pickup_window_start between "' . $startDate . '" and "' . $endDate . '" ');
        }

        if (isset($options['id']) && $options['id'] > 0) {
            $model->whereIn('moves.id', $options['id']);
        }
        if (isset($options['stage']) && $options['stage']) {
            $model->where('moves.stage', $options['stage']);
        }
        if (isset($options['pendingStatus']) && $options['pendingStatus']) {
            return $model->whereIn('moves.status', [0, 12]);
        } else {
            return $model;
        }
    }

    /**
     * Return pending move to schedule event
     *
     * @param string $jobName
     * @return Builder[]|Collection
     */
    public static function getPendingMovesToSchedule($jobName = '')
    {
        $options['pendingStatus'] = true;

        if (isset($jobName) && !empty($jobName)) {
            // Get the moves data which are matches with search
            return self::getMovesModel($options)
                ->where(function ($query) use ($jobName) {
                    $query->orWhere('start_city', 'like', $jobName . '%');
                    $query->orWhere('end_city', 'like', $jobName . '%');
//                    $query->orWhere('customers.name', 'like', $jobName . '%');
                })
                ->get();
        } else {
            // Get the moves data
            return self::getMovesModel($options)->get();
        }

    }

    /**
     * Return scheduled jobs with calender date range
     *
     * @param $request
     * @return array
     * @throws Throwable
     */
    public static function getScheduledJobs($request)
    {
        $option['cols'] = [
            'moves.id',
            'moves.truck_id',
            'moves.start_addr',
            'moves.start_addr',
            'moves.start_city',
            'moves.end_addr',
            'moves.end_city',
            'moves.total_price',
            'moves.pickup_window_start',
            'moves.delivery_window_end',
            DB::raw('concat(customers.first_name, customers.last_name) as customer_name')
        ];

        $option['start'] = isset($request->start) && !is_null($request->start) ? $request->start : null;
        $option['end']   = isset($request->end) && !is_null($request->end) ? $request->end : null;
        $option['id']    = isset($request->move_id) && !is_null($request->move_id) ? $request->move_id : null;
        $option['stage']    = Configuration::STAGE_JOB;

        // Get the move data
        $results = self::getMovesModel($option)->whereNotIn('moves.status', [0, 12])->get();

        $arr     = [];
        foreach ($results as $key => $result) {

            // Remove the 'australia' word from the start and end address
            $result->start_addr = General::removeAusWord($result->start_addr);
            $result->end_addr   = General::removeAusWord($result->end_addr);

            $arr[$key]['id']          = $result->id;
            $arr[$key]['resourceId']  = (isset($result->truck_id) && !is_null($result->truck_id) && !empty($result->truck_id)) ? $result->truck_id : 0;
            $arr[$key]['title']       = view('move::schedule_job.event_title', compact('result'))->render();
            $arr[$key]['description'] = $result->customer_name;
            $arr[$key]['start']       = $result->pickup_window_start;
            $arr[$key]['end']         = $result->delivery_window_end;
        }

        return $arr;
    }

    /**
     * Return Move details by Id
     *
     * @param $moveId
     * @return mixed
     */
    public static function getMoveById($moveId)
    {
//        return (new Move())->findById($moveId);
        return Move::find($moveId);
    }

    /**
     * Return all the options details of dropdown from configuration and using it in the form.
     *
     * @return mixed
     */
    public static function getOptionsFromConfiguration()
    {
        try {
            $data['companyOptions']      = Company::getCompanyOptions();
            $data['customerId']          = Customer::getCustomerOptions();
            $data['stageOptions']        = Configuration::getOptionsByType('move_stage');
            $data['typeOptions']         = Configuration::getOptionsByType('move_type');
            $data['statusOptions']       = Configuration::getOptionsByType('move_status');
            $data['dwellingTypeOptions'] = Configuration::getOptionsByType('dwelling_type');
            $data['dwellingSizeOptions'] = Configuration::getOptionsByType('dwelling_size');

            $data['ancillaryServices'] = AncillaryService::getAncillaries();

        } catch (Exception $e) {
            return null;
        }

        return $data;
    }

    /**
     * Select the options values from configuration by option id to show in the move view section.
     *
     * @param $move
     * @return mixed
     */
    public static function setOptionsValueFromConfiguration($move)
    {

        try {
            $move->stageVal  = Configuration::getOptionValueById($move->stage);
            $move->typeVal   = Configuration::getOptionValueById($move->type);
            $move->statusVal = Configuration::getOptionValueById($move->status);

        } catch (Exception $e) {

            return null;
        }

        return $move;
    }

    /**
     * Return all truck resources
     *
     * @return string
     */
    public static function getJobEvents()
    {
        // Get the query
        $moveObj = self::query();
        $user    = Auth::user();

        $cols = ["moves.truck_id as resourceId", 'trucks.name as title'];
        $moveObj->select($cols);

        if (General::isCompanyAgent()) {
            $moveObj->where('moves.company_id', $user->company_id);
        }

        return $moveObj->get();
    }

    /**
     * Generate the chart data
     *
     * @param $graphType
     * @return array
     */
    public static function getChartData($graphType)
    {
        $data = [];

        // Get the last 3 month or week sales data for chart
        $data['sales'] = self::getLast3MonthSales($graphType);
        // Get the last 3 month or week visits data for chart
        $data['visit'] = self::getLast3MonthVisits($graphType);

        return $data;
    }

    /**
     * Return the sales report of last 3 month
     *
     * @param $graphType
     * @return array
     */
    public static function getLast3MonthSales($graphType)
    {
        $result = [];


        if ($graphType == 'monthly'){
            //returns first day of current month
            $currentMonthDate = (Carbon::now())->firstOfMonth();
            //returns first day of last 2 month
            $last2MonthDate = (Carbon::now())->firstOfMonth()->subMonths(2);
        }else{
            //returns first day of current week
            $currentMonthDate = (Carbon::now())->startOfWeek();
            //returns first day of last 2 week
            $last2MonthDate = (Carbon::now())->startOfWeek()->subWeeks(2);
        }

        while ($last2MonthDate->lte($currentMonthDate)) {
            $checkCondDate = $last2MonthDate->toDateString();
            // Get the move sales data
            $salesData = self::getMoveSalesData($checkCondDate, $graphType);

            array_push($result, [
                'date' => $checkCondDate,
                'ttl_moves' => isset($salesData) ? $salesData->ttl_moves : 0,
                'chart_moves' => isset($salesData) ? $salesData->chart_moves : 0,
                'total_price' => isset($salesData) ? $salesData->total_price : 0,
                'chart_price' => isset($salesData) ? $salesData->chart_price : 0
            ]);

            if ($graphType == 'monthly'){
                // Move to the next month of same date
                $last2MonthDate->addMonth();
            }else{
                // Move to the next week of date
                $last2MonthDate->addWeek();
            }
        }

        // calculate the average of last 2 month, if it is more than
        // current month it will replace with that
        $result[2]['chart_price'] = round(($result[0]['chart_price'] + $result[1]['chart_price']) / 2, 2);
        $result[2]['chart_price'] = ($result[2]['total_price'] > $result[2]['chart_price'] ? $result[2]['total_price'] : $result[2]['chart_price']);

        $result[2]['chart_moves'] = round(($result[0]['chart_moves'] + $result[1]['chart_moves']) / 2, 2);
        $result[2]['chart_moves'] = ($result[2]['ttl_moves'] > $result[2]['chart_moves'] ? $result[2]['ttl_moves'] : $result[2]['chart_moves']);

        return $result;
    }

    /**
     * Get the move visit data
     *
     * @param $date
     * @param $graphType
     * @return mixed
     */
    public static function getMoveSalesData($date, $graphType)
    {
        // Get the query
        $moveObj = self::query();
        $user    = Auth::user();

        $cols = [DB::raw('count(*) as ttl_moves'), DB::raw('count(*) as chart_moves'),
            DB::raw('sum(payments.amount) as total_price'), DB::raw('sum(payments.amount) as chart_price')];
        $moveObj->select($cols);

        $moveObj->join('payments', 'payments.move_id', '=', 'moves.id');

        // If it is company agent
        if (General::isCompanyAgent()) {
            $moveObj->where('moves.company_id', $user->company_id);
        }
        $moveObj->where('stage', '=', Configuration::STAGE_JOB);
        $moveObj->whereRaw(DB::raw('year(payments.created_at) = year("' . $date . '")'));
        if ($graphType == 'monthly') {
            $moveObj->whereRaw(DB::raw('month(payments.created_at) = month("' . $date . '")'));
            $moveObj->groupByRaw('month(payments.created_at), year(payments.created_at)');
            $moveObj->orderByRaw('year(payments.created_at), month(payments.created_at)');
        } else {
            $moveObj->whereRaw(DB::raw('WEEK(payments.created_at) = WEEK("' . $date . '")'));
            $moveObj->groupByRaw('WEEK(payments.created_at), year(payments.created_at)');
            $moveObj->orderByRaw('year(payments.created_at), WEEK(payments.created_at)');
        }

        return $moveObj->get()->first();
    }

    /**
     * Return the sales report of last 3 month
     *
     * @param $graphType
     * @return array
     */
    public static function getLast3MonthVisits($graphType)
    {
        $result = [];

        if ($graphType == 'monthly'){
            //returns first day of current month
            $currentMonthDate = (Carbon::now())->firstOfMonth();
            //returns first day of last 2 month
            $last2MonthDate = (Carbon::now())->firstOfMonth()->subMonths(2);
        }else{
            //returns first day of current week
            $currentMonthDate = (Carbon::now())->startOfWeek();
            //returns first day of last 2 week
            $last2MonthDate = (Carbon::now())->startOfWeek()->subWeeks(2);
        }

        while ($last2MonthDate->lte($currentMonthDate)) {
            $checkCondDate = $last2MonthDate->toDateString();
            // Get the move visits data
            $visitData = self::getMoveVisitData($checkCondDate, $graphType);

            array_push($result, [
                'date' => $checkCondDate,
                'ttl_visits' => isset($visitData) ? $visitData->ttl_visits : 0,
                'chart_visits' => isset($visitData) ? $visitData->chart_visits : 0
            ]);

            if ($graphType == 'monthly'){
                // Move to the next month of same date
                $last2MonthDate->addMonth();
            }else{
                // Move to the next week of date
                $last2MonthDate->addWeek();
            }
        }

        // calculate the average of last 2 month, if it is more than
        // current month it will replace with that
        $result[2]['chart_visits'] = round(($result[0]['chart_visits'] + $result[1]['chart_visits']) / 2, 0);
        $result[2]['chart_visits'] = ($result[2]['ttl_visits'] > $result[2]['chart_visits'] ? $result[2]['ttl_visits'] : $result[2]['chart_visits']);

        return $result;
    }

    /**
     * Get the move visit data
     *
     * @param $date
     * @param $graphType
     * @return mixed
     */
    public static function getMoveVisitData($date, $graphType)
    {
        $moveObj = self::query();
        $user    = Auth::user();

        $cols = [DB::raw('count(*) as ttl_visits'), DB::raw('count(*) as chart_visits')];
        $moveObj->select($cols);

        $moveObj->leftJoin('lanes', function ($join) {
            $join->on('moves.type', '=', DB::raw('"' . Configuration::TYPE_INTERSTATE . '"'));
            $join->whereRaw("JSON_CONTAINS(moves.matches, CAST(lanes.id as JSON), '$')");
        });

        $moveObj->leftJoin('depots', function ($join) {
            $join->on('moves.type', '=', DB::raw('"' . Configuration::TYPE_LOCAL . '"'));
            $join->whereRaw("JSON_CONTAINS(moves.matches, CAST(depots.id as JSON), '$')");
        });

        if (General::isCompanyAgent()) {
            $moveObj->where(function ($query) use($user) {
                $query->orWhere('lanes.company_id', $user->company_id);
                $query->orWhere('depots.company_id', $user->company_id);
            });
        }

        $moveObj->whereRaw(DB::raw('year(moves.start_date) = year("' . $date . '")'));
        if ($graphType == 'monthly') {
            $moveObj->whereRaw(DB::raw('month(moves.start_date) = month("' . $date . '")'));
            $moveObj->groupByRaw('month(moves.start_date), year(moves.start_date)');
            $moveObj->orderByRaw('year(moves.start_date), month(moves.start_date)');
        } else {
            $moveObj->whereRaw(DB::raw('WEEK(moves.start_date) = WEEK("' . $date . '")'));
            $moveObj->groupByRaw('WEEK(moves.start_date), year(moves.start_date)');
            $moveObj->orderByRaw('year(moves.start_date), WEEK(moves.start_date)');
        }

        return $moveObj->get()->first();
    }
}
