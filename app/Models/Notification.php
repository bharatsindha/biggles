<?php

namespace App\Models;

use App\Facades\General;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'text'];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id')->withDefault();
    }

    /**
     * Return notification modal
     *
     * @return Builder
     */
    public static function notificationModal()
    {
        $notificationObj = self::query();
        $user            = Auth::user();

        // If it is company agent
        if (General::isCompanyAgent() && $user->company_id > 0) {
            $notificationObj->where('notifications.company_id', $user->company_id);
        }
        $notificationObj->join(
            'user_notifications', 'notification_id', '=', 'notifications.id'
        );
        $notificationObj->where('user_notifications.is_read', 1);
        $notificationObj->where('user_notifications.user_id', $user->id);

        return $notificationObj;
    }

    /**
     * Update the notification seen by the company or admin
     *
     * @return bool
     */
    public static function seenNotification()
    {
        // Get the pending notifications
        $notificationObj = self::notificationModal();
        $notificationObj->update(['user_notifications.is_read' => 0]);

        return true;
    }

    /**
     * Get the notification lists
     *
     * @return array
     */
    public static function getNotifications()
    {

        // Get the notifications
        $notificationObj = self::notificationModal();
        $cols            = ["notifications.*", "user_notifications.created_at as _created_at"];
        $notificationObj->select($cols);
        $notifications = $notificationObj->orderBy('user_notifications.created_at', 'DESC')->get();

        $notificationLists = [];
        $i                 = 0;
        foreach ($notifications as $notification) {
            $dayName = self::getDayName($notification->_created_at);
            $dayName = (!empty($dayName) ? $dayName . ', ' : '');
            $dayName .= !is_null($notification->_created_at) && !empty($notification->_created_at) ? Carbon::parse($notification->_created_at)->format('d M Y') : '';

            $notificationLists[$dayName][$i]['text'] = $notification->text;
            $i++;
        }
        $notificationReturnArray['notifications']     = $notificationLists;
        $notificationReturnArray['totalNotification'] = count($notifications);

        return $notificationReturnArray;
    }

    /**
     * Return the day name like
     * Today, yesterday...like that.
     *
     * @param $timestamp
     * @return string
     */
    public static function getDayName($timestamp)
    {
        $date        = Carbon::parse($timestamp);
        $isToday     = $date->isToday();
        $isYesterday = $date->isYesterday();

        if ($isToday) {
            return 'Today';
        } elseif ($isYesterday) {
            return 'Yesterday';
        }

        return '';
    }

}
