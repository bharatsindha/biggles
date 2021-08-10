<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\DataTables\Facades\DataTables;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'created_by'];

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    /**
     * Get all role lists
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    static public function getRoles()
    {

        $model = self::query();
        return Datatables::eloquent($model)
            ->addColumn('action', function ($role) {
                $action = '';
                $action .= View('layouts.actions.view')->with('model', $role)->with('route', 'role.show');
                $action .= View('layouts.actions.edit')->with('model', $role)->with('route', 'role.edit');
                $action .= View('layouts.actions.delete')->with('model', $role)->with('route', 'role.destroy');
                return $action .= '';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
}
