<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use App\Facades\General;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Yajra\DataTables\Facades\DataTables;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const ROLE_MUVAL_AGENT   = 0;
    const ROLE_COMPANY_AGENT = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'access_level',
        'role_id',
        'avatar',
        'company_id'
    ];


    /**
     * Get the attachments
     */
    public function attachment()
    {
        return $this->hasMany(Attachment::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id')->withDefault();
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
        $options['match'] = [
            "companies.name" => $keyword,
            "users.name"     => $keyword,
            "users.email"    => $keyword,
        ];

        // Get the global search data
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]) {
            // Get the count of matched search result
            $result = $model->count();
        } else {
            // Get the datatable of moves
            $result = \App\User::getDatatable($model);
        }

        return $result;
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of move model
        return \App\User::getUserModel();
    }

    /**
     * Get all users lists
     *
     * @return JsonResponse
     * @throws Exception
     */
    static public function getUsers()
    {
        $model = self::getUserModel();

        return self::getDatatable($model);
    }

    /**
     * Get the object of datatable for staff
     *
     * @param $model
     * @return JsonResponse
     * @throws Exception
     */
    public static function getDatatable($model){
        $user  = Auth::user();
        return Datatables::eloquent($model)
            ->addColumn('role_name', function ($user) {
                return isset($user->role) ? $user->role->name : '';
            })
            /*->addColumn('company_name', function (User $user) {
                return isset($user->company) ? $user->company->name : '';
            })*/
            ->addColumn('action', function ($user) {

                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $user)->with('route', 'user.show');
                $action .= View('layouts.actions.edit')->with('model', $user)->with('route', 'user.edit');
                $action .= View('layouts.actions.delete')->with('model', $user)->with('route', 'user.destroy');
                return $action .= '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Return User Name by user Id
     *
     * @param $userId
     * @return string
     */
    public static function getUserNameById($userId)
    {
        try {
            return self::where('id', $userId)->pluck('name')->first();
        } catch (Exception $e) {
            return '';
        }

    }

    /**
     * Return the user model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getUserModel(){
        $model = self::query()->with('company');

        $cols = ["users.*", 'companies.name as company_name'];
        $model->select($cols);

        $user  = Auth::user();
        if (General::isCompanyAgent()) {
            $model->where('company_id', $user->company_id);
        }
        $model->leftJoin('companies', 'companies.id', '=', 'users.company_id');

        return $model;
    }
}
