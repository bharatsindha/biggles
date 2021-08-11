<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class Local extends Crud
{
    use SoftDeletes;

    protected $fillable = ['company_id',
        'depot_id',
        'location',
        'radius',
        'price_per',
        'min_price',
        'extra_person_price',
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
    public function depot()
    {
        return $this->belongsTo('App\Models\Depot', 'depot_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by')->withDefault();
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of local model
        return self::getLocalModel();
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
        $options['match'] = ["depots.name" => $keyword, "companies.name" => $keyword,];

        // Get the global search data for local
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            $result = $model->count();
        }else{
            // Get the local datatable
            $result = self::getLocalDatatable($model);
        }

        return $result;
    }

    /**
     * Return local model
     *
     * @param null $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public static function getLocalModel($request=null){
        $model = self::query();
        $user  = Auth::user();

        $cols = ["locals.*", 'depots.name as depot_name'];
        $model->select($cols);

        if (General::isCompanyAgent()) {
            $model->where('locals.company_id', $user->company_id);
        }
        // get local data for particular depot
        if (isset($request->depotId) && !empty($request->depotId)) {
            $model->where('locals.depot_id', $request->depotId);
        }
        $model = $model->join(
            'depots', 'depots.id', '=', 'locals.depot_id'
        );
        $model = $model->leftJoin(
            'companies', 'companies.id', '=', 'locals.company_id'
        );

        return $model;
    }

    /**
     * Return local data of table
     *
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public static function getLocals($request)
    {
        // Get the local data
        $model = self::getLocalModel($request);

        // Get the local datatable
        return self::getLocalDatatable($model);
    }

    /**
     * Generate datatable object for local
     *
     * @param $model
     * @return JsonResponse
     * @throws Exception
     */
    public static function getLocalDatatable($model){
        $user  = Auth::user();

        return DataTables::eloquent($model)
            ->addColumn('action', function ($local) use ($user) {
                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $local)->with('route', 'local.show');
                $action .= View('layouts.actions.edit')->with('model', $local)->with('route', 'local.edit');
                if ($user->access_level == 0) {
                    $action .= View('layouts.actions.delete')->with('model', $local)->with('route', 'local.destroy');
                }
                return $action .= '</div>';
            })
            ->addColumn('min_price', function ($q) {
                return "$" . sbNumberFormat($q->min_price);
            })
            ->addColumn('company_name', function ($q) {
                return $q->company->name;
            })
            ->addColumn('extra_person_price', function ($q) {
                return "$" . sbNumberFormat($q->extra_person_price);
            })
            ->addColumn('price_per', function ($q) {
                return "$" . sbNumberFormat($q->price_per);
            })
            ->addColumn('radius', function ($q) {
                return sbNumberFormat($q->radius) . "m";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
