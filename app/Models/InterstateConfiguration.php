<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterstateConfiguration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'min_price',
        'stairs',
        'elevator',
        'long_driveway',
        'ferry_vehicle',
        'heavy_items',
        'extra_kms',
        'packing',
        'storage',
        'storage_toggle',
        'storage_cost',
        'free_storage_weeks',
        'created_by'];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id')->withDefault();
    }


}
