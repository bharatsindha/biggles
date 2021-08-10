<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'route_name', 'created_by'];

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
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    /**
     * Check user has got access to view a page or not
     *
     * @param $routeName
     * @return bool
     */
    public static function checkAccessAllow($routeName)
    {
        $allowRoutes = ["dashboard", "profile", "change_password", "user.update", "profile_pic_update"];
        if (in_array($routeName, $allowRoutes)) {
            return true;
        }
        if (Auth::check()) {
            $accessLevel = Auth::user()->access_level;
            $role        = Role::find(Auth::user()->role_id);

            // First, check for the exact match
            $permission = Permission::where('route_name', $routeName)->first();

            // Match with the master route name
            if (!$permission) {
                $parts = explode(".", $routeName);
                if (!$permission) {
                    $permission = Permission::where('route_name', $parts[0])->first();
                }

                if (!$permission) {
                    $urlParts   = explode("-", $routeName);
                    $permission = Permission::where('route_name', $urlParts[0])->first();
                }

                if (!$permission) {
                    $parentRoute = Permission::getDependantRoutes($parts[0]);
                    $permission  = Permission::where('route_name', $parentRoute)->first();
                }
            }

            if ($permission) {

                if ($permission->count() > 0) {
                    $userPermissions = [];
                    if (!is_null($role) && $role->permissions->contains($permission)) {
                        if ($accessLevel == 0 || $permission->is_company == 1) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get dependant route
     *
     * @param $route
     * @return mixed
     */
    public static function getDependantRoutes($route)
    {
        $dependant = ["role" => "user"];
        if (isset($dependant[$route])) {
            return $dependant[$route];
        } else {
            return $route;
        }
    }
}
