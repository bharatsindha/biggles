<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Truck extends Crud
{
    use SoftDeletes;

    protected $fillable = ['name', 'company_id', 'description', 'capacity', 'created_by'];

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
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by')->withDefault();
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the trucks data
        return self::getTruckModal();
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
        $options['match'] = ["trucks.name" => $keyword];
        // Get the global search data which are match with keyword
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]) {
            $result = $model->count();
        } else {
            // Get the datatable of truck
            $result = self::getTruckDatatable($model);
        }

        return $result;
    }

    /**
     * Return all the truck details for options
     *
     * @return array
     */
    public static function getTruckOptions()
    {
        // Get trucks data
        $truckResults = self::getTrucks();
        $optionValues = [];

        foreach ($truckResults as $truckResult) {
            $optionValues[$truckResult->id] = $truckResult->name;
        }

        return $optionValues;
    }

    /**
     * Return all trucks
     *
     * @return Builder[]|Collection
     */
    public static function getTrucks()
    {
        // Get the object of truck model
        $truckObj = self::getTruckModal();
        return $truckObj->get();
    }

    /**
     * Return truck model
     *
     * @return Builder
     */
    public static function getTruckModal()
    {
        // Get the query
        $truckObj = self::query();
        $user = Auth::user();

        if (General::isCompanyAgent()) {
            $truckObj->where('trucks.company_id', $user->company_id);
        }

        return $truckObj;
    }

    /**
     * Get all truck lists
     *
     * @return JsonResponse
     * @throws Exception
     */
    public static function getTrucksDatatable()
    {
        // Get the object of truck model
        $model = self::getTruckModal();

        // Get the datatable of trucks
        return self::getTruckDatatable($model);
    }

    /**
     * Generate datatable object of truck
     *
     * @param $model
     * @return JsonResponse
     * @throws Exception
     */
    public static function getTruckDatatable($model)
    {
        return DataTables::eloquent($model)
            ->addColumn('company_name', function ($truck) {
                return isset($truck->company) ? $truck->company->name : '';
            })
            ->addColumn('action', function ($truck) {

                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $truck)->with('route', 'truck.show');
                $action .= View('layouts.actions.edit')->with('model', $truck)->with('route', 'truck.edit');
                $action .= View('layouts.actions.delete')->with('model', $truck)->with('route', 'truck.destroy');
                return $action .= '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Return all truck resources
     *
     * @return void
     */
    public static function getTruckResources()
    {
        $truckObj = self::query();
        $user = Auth::user();

        $cols = ["trucks.id", 'trucks.name as title', DB::raw("'orange' as eventColor")];
        $truckObj->select($cols);

        if (General::isCompanyAgent()) {
            $truckObj->where('trucks.company_id', $user->company_id);
        }

        return $truckObj->get();
    }

}
