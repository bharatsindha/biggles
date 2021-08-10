<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = ['company_id', 'move_id', 'total_price', 'deposit', 'fee', 'created_by'];

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
     * Return Deal details by Move Id
     *
     * @param $moveId
     * @return mixed
     */
    public static function getDealsByMoveId($moveId)
    {

        return self::where('move_id', $moveId)->get();
    }

    /**
     * Get all deal lists
     *
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    static public function getDeals($request)
    {
        $model = self::query();
        $user  = Auth::user();

        $cols = ["deals.*", 'users.name as created_by'];
        $model->select($cols);

        // If it is company agent
        if (General::isCompanyAgent()) {
            $model->where('deals.company_id', $user->company_id);
        }

        // If it is requested for particular Move
        if (isset($request->moveId) && !empty($request->moveId)) {
            $model->where('deals.move_id', $request->moveId);
        }

        $model = $model->leftJoin(
            'users', 'users.id', '=', 'deals.created_by'
        );

        // Return the datatable of deal
        return Datatables::eloquent($model)
            ->addColumn('action', function ($deal) use ($user) {
                $action = '';
                $action .= View('layouts.actions.view')->with('model', $deal)->with('route', 'deal.show');
                $action .= View('layouts.actions.edit')->with('model', $deal)->with('route', 'deal.edit');
                if ($user->access_level == 0) {
                    $action .= View('layouts.actions.delete')->with('model', $deal)->with('route', 'deal.destroy');
                }
                return $action .= '';
            })
            ->addColumn('created_at', function ($q) {
                return date("d F, Y g:i A", strtotime($q->created_at));
            })
            ->addColumn('total_price', function ($q) {
                return "$" . sbNumberFormat($q->total_price);
            })
            ->addColumn('deposit', function ($q) {
                return "$" . sbNumberFormat($q->deposit);
            })
            ->addColumn('fee', function ($q) {
                return "$" . sbNumberFormat($q->fee);
            })
            ->rawColumns(['action'])
            ->make(true);

    }

}
