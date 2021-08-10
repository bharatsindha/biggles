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

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
}
