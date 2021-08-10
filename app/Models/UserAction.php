<?php

namespace App\Models;

use App\Facades\General;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserAction extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'parent_id', 'action_module', 'role', 'ip', 'user_client', 'title', 'message'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault();
    }

    /**
     * Get all user actions
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public static function getUserActions()
    {
        $model = self::query();
        $user  = Auth::user();

        $cols = [
            "user_actions.*",
            'user_actions.title as activity',
            'user_actions.ip as remote_ip',
            'users.name as action_by'
        ];
        $model->select($cols);

        if (General::isCompanyAgent()) {
            $model->where('user_actions.user_id', $user->id);
        }

        $model->leftJoin(
            'users', 'users.id', '=', 'user_actions.user_id'
        );

        $model->orderBy('created_at', 'desc');

        return DataTables::eloquent($model)
            ->addColumn('date', function ($q) {
                return date("d F, Y g:i A", strtotime($q->created_at));
            })
            ->rawColumns(['message'])
            ->make(true);
    }

}
