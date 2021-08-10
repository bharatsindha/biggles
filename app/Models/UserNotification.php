<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read'];

    /**
     * @return BelongsTo
     */
    public function notification()
    {
        return $this->belongsTo('App\Models\Notification', 'notification_id')->withDefault();
    }


}
