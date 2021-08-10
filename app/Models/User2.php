<?php

namespace App\Models;

use App\Facades\General;
use App\Models\Crud;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User2 extends Crud
{
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
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // Get the object of move model
        return \App\User::getUserModel();
    }

    /**
     * Global search function
     *
     * @param $keyword
     * @param array $option
     * @return JsonResponse
     * @throws \Exception
     */
    public function getSearchResults($keyword, $option = [])
    {
        $options['match'] = [
            "companies.name" => $keyword,
            "users.name" => $keyword,
            "users.email" => $keyword,
        ];

        // Get the global search data
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            // Get the count of matched search result
            $result = $model->count();
        }else{
            // Get the datatable of moves
            $result = \App\User::getDatatable($model);
        }

        return $result;
    }

}
