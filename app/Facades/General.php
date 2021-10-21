<?php

namespace App\Facades;

use App\Models\UserAction;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;


class General extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'general';
    }

    /**
     * get Week days
     * @return array
     */
    public static function WeekDays()
    {
        return [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            0 => 'Sunday',
        ];
    }

    /**
     * get Access Levels
     * @return string[]
     */
    public static function getAccessLevels()
    {
        return [
            0 => 'Muval Agents',
            1 => 'Company Agents',
        ];
    }

    /**
     * @return array
     */
    public static function debitStatus()
    {
        return [
            ''            => 'Select Status',
            'PLANNED'     => 'Planned',
            'PENDING'     => 'Pending',
            'SUCCESSFUL'  => 'Succcessful',
            'FAILED'      => 'Failed',
            'FAILEDTWICE' => 'Failed Twice',
        ];
    }

    /**
     * get File Size Format
     *
     * @param $bytes
     * @return string
     */
    public static function fileSizeFormate($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * get Gender
     *
     * @return string[]
     */
    public static function gender()
    {
        return [
            'Male'   => 'Male',
            'Female' => 'Female',
        ];
    }

    /**
     * checking the access of muval agent
     * return true if yes.
     *
     * @return bool
     */
    public static function isSuperAdmin(){
        $userAccess = Auth::user()->access_level;
        if ($userAccess == 0){
            return true;
        }

        return false;
    }

    /**
     * checking the access of company agent
     * return true if yes.
     *
     * @return bool
     */
    public static function isCompanyAgent(){
        $userAccess = Auth::user()->access_level;
        if ($userAccess == 1){
            return true;
        }

        return false;
    }

    /**
     * Get File Icon
     *
     * @param string $fileType
     * @return string
     */
    public static function getFileIcon($fileType = '')
    {
        $icon = [
            'application/msword'                                                      => 'fa fa-file-word-o',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa fa-file-word-o',
            'application/mspowerpoint'                                                => 'fa fa-file-powerpoint-o',
            'application/powerpoint'                                                  => 'fa fa-file-powerpoint-o',
            'application/vnd.ms-powerpoint'                                           => 'fa fa-file-powerpoint-o',
            'application/x-mspowerpoint'                                              => 'fa fa-file-powerpoint-o',
            'video/mpeg'                                                              => 'fa fa-file-video-o',
            'audio/x-mpeg-3'                                                          => 'fa fa-file-video-o',
            'audio/mpeg3'                                                             => 'fa fa-file-video-o',
            'video/x-mpeg'                                                            => 'fa fa-file-video-o',
            'video/quicktime'                                                         => 'fa fa-file-video-o',
            'image/jpeg'                                                              => 'fa fa-file-image-o',
            'image/pjpeg'                                                             => 'fa fa-file-image-o',
            'image/png'                                                               => 'fa fa-file-photo-o',
            'application/pdf'                                                         => 'fa fa-file-pdf-o',
            'application/x-compressed'                                                => 'fa fa-file-zip-o',
            'application/zip'                                                         => 'fa fa-file-zip-o',
            'application/x-compressed'                                                => 'fa fa-file-zip-o',
            'application/x-zip-compressed'                                            => 'fa fa-file-zip-o',
            'multipart/x-zip'                                                         => 'fa fa-file-zip-o',
            'application/excel'                                                       => 'fa fa-file-excel-o',
            'application/vnd.ms-excel'                                                => 'fa fa-file-excel-o',
            'application/x-excel'                                                     => 'fa-file-excel-o',
            'application/x-msexcel'                                                   => 'fa fa-file-excel-o',
            'application/x-msexcel'                                                   => 'fa fa-file-excel-o',
        ];

        return isset($icon[$fileType]) ? $icon[$fileType] : 'fa fa-file';
    }

    /**
     * Log all user activity
     *
     * Return  boolean
     * @param $activityId
     * @param array $options
     * @param string $actionModule
     * @return bool
     */
    public static function log($activityId, $options = [])
    {
        $disallowModules = [];
        if (isset($options['action_module']) && in_array($options['action_module'], $disallowModules)) {
            return false;
        }

        $userId = is_null(Auth::user()->id) ? '' : Auth::user()->id;
        $roleId = is_null(Auth::user()->role_id) ? '' : Auth::user()->role_id;
        $roleId = !is_null($roleId) ? $roleId : $options['role'];

        // get messages
        $title        = ucfirst(trans("slmsg.$activityId.TITLE"));
        $eventData    = isset($options['event_data']) ? $options['event_data'] : [];
        $parentId     = isset($options['parent_id']) ? $options['parent_id'] : null;
        $actionModule = isset($options['action_module']) ? $options['action_module'] : null;
        $message      = ucfirst(trans("slmsg.$activityId.MSG", $eventData));

        $data = [
            'user_id'       => $userId,
            'parent_id'     => $parentId,
            'action_module' => $actionModule,
            'ip'            => Request::ip(),
            'user_client'   => Request::header('User-Agent'),
            'role'          => $roleId,
            'title'         => $title,
            'message'       => $message,
        ];

        UserAction::create($data);
    }

    /**
     * search key in array
     *
     * @param array $array
     * @param $search
     * @return bool|mixed
     */
    public static function searchArrayValueByKey(array $array, $search)
    {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === $key) {
                return $value;
            }
        }
        return false;
    }

    /**
     * Return matched array value
     * @param $array
     * @param $field
     * @param $value
     * @return bool|int|string
     */
    public static function findArrayByKeyValue($array, $field, $value)
    {
        foreach ($array as $key => $row) {
            if ($row[$field] === $value) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Return Universal date format across system
     * @param $date
     * @return false|string
     */
    static public function dateFormat($date)
    {
        if (isset($date)) {
            return date('Y-m-d', strtotime($date));
        }
        return $date;
    }

    /**
     * Return short name for days
     * @param $day
     * @return mixed|string
     */
    static public function getDayShortName($day)
    {
        $days = ["Monday"    => "M",
                 "Tuesday"   => "T",
                 "Wednesday" => "W",
                 "Thursday"  => "Th",
                 "Friday"    => "F",
                 "Saturday"  => "S",
                 "Sunday"    => "Su"];
        return isset($days[$day]) ? $days[$day] : "";
    }

    /**
     * Return all Ids for related messages
     * @param $msgId
     * @return array
     */
    public static function getParentId($msgId)
    {
        $msgList       = DB::table('messages')->select('parent_id')->whereIn('id', $msgId)->get();
        $resultMessage = [];
        foreach ($msgList as $message) {
            $resultMessage[] = $message->parent_id;
        }
        return $resultMessage;
    }

    /**
     * Return Weekdays
     * @return array
     */
    public static function getDaysInWeek()
    {
        $day = [
            "Monday"    => "Monday",
            "Tuesday"   => "Tuesday",
            "Wednesday" => "Wednesday",
            "Thursday"  => "Thursday",
            "Friday"    => "Friday",
            "Saturday"  => "Saturday",
            "Sunday"    => "Sunday"
        ];
        return $day;
    }

    /**
     * Return Device token
     *
     * @param $userid
     * @return mixed
     */
    public static function getDeviceToken($userId)
    {
        $user = User::find($userId);
        return $user->device_token;
    }

    /**
     * Create a file js uploader
     *
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function filejs($options = [])
    {
        $options     = $options + self::getFileUploadDefaultOptions();
        $name        = $options["selector"];
        $previewHtml = "";
        if (!empty($options["initialPreview"])) {
            foreach ($options["initialPreview"] as $img) {
                $previewHtml .= "\"<img src='" . $img . "' class='file-preview-image' alt=''>\",";
            }
        }

        $filejs = <<<filejs
<script type='text/javascript'>
    $(function () {
        // Basic example
        $("$name").fileinput({
            browseClass: '$options[browseClass]',
            browseLabel: '',
            showUpload: $options[showUpload],
            initialPreview;: [
                $previewHtml;
            ],
            ['image'],
            initialCaption;: "No file selected",
        })
    });
</script>
filejs;
        return $filejs;
    }

    /**
     * File Upload Default Options
     * @return string[]
     */
    public static function getFileUploadDefaultOptions()
    {
        return [
            "selector"    => ".file-input",
            "browseClass" => "btn btn-primary btn-icon",
            "showUpload"  => 'false'
        ];
    }

    /**
     * Remove the special character from input value
     *
     * @param $inputVal
     * @return string|string[]|null
     */
    public static function removeSpecialCharacter($inputVal)
    {
        return preg_replace("/[^0-9\.]/i", "", $inputVal);
    }

    /**
     * Return first letter of the first name and last name
     *
     * @param $name
     * @return string
     */
    public static function getFirstLetterFromName($name)
    {
        preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#',
            $name, $results);

        $firstLetter  = isset($results[2]) ? substr($results[2], 0, 1) : '';
        $secondLetter = isset($results[3]) ? substr($results[3], 0, 1) : '';

        return $firstLetter . $secondLetter;
    }

    /**
     * Return first name of the name
     *
     * @param $name
     * @return string
     */
    public static function getFirstname($name)
    {
        preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#',
            $name, $results);

        return isset($results[2]) ? $results[2] : '';
    }

    /**
     * Return weekdays
     *
     * @return array
     */
    public static function getWeekDays()
    {
        return ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    }

    /**
     * Return job checklists
     *
     * @return array
     */
    public static function getChecklists()
    {
        return ["confirm inventory", "confirm access", "check prices", "contact customer", "follow up for a review"];
    }

    /**
     * Return job status in array shown on tabs
     *
     * @return array
     */
    public static function jobStatusArray()
    {
        return [
            'pending'     => [
                "id"         => 1,
                "title"      => "Pending",
                "count"      => 0,
                "moveStatus" => [0, 12],
                'active'     => true,
            ],
            'in_progress' => [
                "id"         => 2,
                "title"      => "In Progress",
                "count"      => 0,
                "moveStatus" => [8, 9, 11, 13, 14],
                'active'     => false,
            ],
            'completed'   => [
                "id"         => 3,
                "title"      => "Completed",
                "count"      => 0,
                "moveStatus" => [15, 10],
                'active'     => false,
            ],
            /*'closed'      => [
                "id"         => 4,
                "title"      => "Closed",
                "count"      => 0,
                "moveStatus" => [10],
                'active'     => false,
            ],*/
        ];
    }

    /**
     * Remove Australia work on some address
     *
     * @param $string
     * @return string|string[]
     */
    public static function removeAusWord($string)
    {
        $string = str_replace(", australia", "", $string);
        $string = str_replace(", Australia", "", $string);
        $string = str_replace("australia", "", $string);
        $string = str_replace("Australia", "", $string);

        return $string;
    }

    /**
     * Calculate the estimate moving time, based on space and access
     *
     * @param $accessPickupArr
     * @param $accessDeliveryArr
     * @param $space
     * @return float|int|string
     */
    public static function calculateEstimatedMovingTime($accessPickupArr, $accessDeliveryArr, $space)
    {
        $accessPickup   = [];
        $accessDelivery = [];
        foreach ($accessPickupArr as $key => $value) {
            if ($value['item'] != '' && $value['item'] != null) {
                array_push($accessPickup, strtolower($value['item']));
            }
        }
        foreach ($accessDeliveryArr as $key => $value) {
            if ($value['item'] != '' && $value['item'] != null) {
                array_push($accessDelivery, strtolower($value['item']));
            }
        }

        // Work out additional charges
        $surcharges       = Config::get('muval.ACCESS_SURCHARGES');
        $workRatePickup   = Config::get('muval.ACCESS_WORK_RATE_PICKUP');
        $workRateDelivery = Config::get('muval.ACCESS_WORK_RATE_DELIVERY');
        $accessDriveway   = Config::get('muval.ACCESS_DRIVEWAY');
        $accessStairs     = Config::get('muval.ACCESS_STAIRS');
        $accessElevator   = Config::get('muval.ACCESS_ELEVATOR');
        $accessParking    = Config::get('muval.ACCESS_PARKING');

        // Pickup charges
        // Set number of extra hours required for each type of obstacle (i.e. stairs, elevator, no parking)
        if (in_array($accessDriveway, $accessPickup)) {

        }
        if (in_array($accessStairs, $accessPickup)) {
            // 2 flights of stairs
            $workRatePickup = 6;
            if (in_array($accessParking, $accessPickup)) {
                // 2 flights of stairs and no parking - 50m walk
                $workRatePickup = 4;
            }
        }
        if (in_array($accessElevator, $accessPickup)) {
            // Elevator
            $workRatePickup = 4;
            if (in_array($accessParking, $accessPickup)) {
                // Elevator and no parking - 50m walk
                $workRatePickup = 3.5;
            }
        }
        if (in_array($accessParking, $accessPickup)) {
            // No parking - 50m walk
            $workRatePickup = 6;
        }

        // Delivery charges
        if (in_array($accessDriveway, $accessDelivery)) {

        }
        if (in_array($accessStairs, $accessDelivery)) {
            // 2 flights of stairs
            $workRateDelivery = 8;
            if (in_array($accessParking, $accessDelivery)) {
                // 2 flights of stairs and no parking - 50m walk
                $workRateDelivery = 5;
            }
        }
        if (in_array($accessElevator, $accessDelivery)) {
            // Elevator
            $workRateDelivery = 5;
            if (in_array($accessParking, $accessDelivery)) {
                // Elevator and no parking - 50m walk
                $workRateDelivery = 4.5;
            }
        }
        if (in_array($accessParking, $accessDelivery)) {
            // No parking - 50m walk
            $workRateDelivery = 8;
        }

        // Perform calculations to work out $totalTime required for the job
        $space        = ceil($space);
        $pickupTime   = $space / $workRatePickup;
        $deliveryTime = $space / $workRateDelivery;
        $transitTime  = 0;
        $totalTime    = $pickupTime + $deliveryTime;
        $totalTime    = number_format($totalTime, 2);
        $totalTime    = floor(($totalTime) * 2) / 2;

        return $totalTime;
    }

    /**
     * Return the access attributes with the filed and the questions
     *
     * @return array
     */
    public static function getAccessAttribute(){

        return [
            [
                'item'     => 'Height restraints', // Steep Driveway (long_driveway)
                'field' => 'long_driveway',
                'questions' => ['Haw far is a height restraints?'],

            ],
            [
                'item'     => 'Stairs', //Stairs (stairs)
                'field' => 'stairs',
                'questions' => ['how many flights of stairs are at each property?'],
            ],
            [
                'item'     => 'Narrow street', //Ferry Vehicle (ferry_vehicle)
                'field' => 'ferry_vehicle',
                'questions' => ['How far is a narrow street?'],
            ],
            [
                'item'     => 'Elevator', //Elevator (elevator)
                'field' => 'elevator',
                'questions' => ['How many elevators are there?'],
            ],
            [
                'item'     => 'Ground floor',
                'field' => '',
                'questions' => null,
            ],
            [
                'item'     => 'No parking', //Extra kms (extra_kms)
                'field' => 'extra_kms',
                'questions' => ['How far is a long walk?'],
            ],
        ];
    }

    /**
     * Return the list of inventory titles
     *
     * @return array
     */
    public static function getInventoryList(){
        return [
            [
                'title' => 'Bedroom',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Lounge',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Kitchen',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Laundry',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Office',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Hall',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Garage',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
            [
                'title' => 'Cartons',
                "img"     => asset('assets-front/images/living-room.svg'),
                "imgGray" => asset('assets-front/images/kitchen.svg'),
            ],
        ];
    }

    /**
     * Return list of the inventory items
     *
     * @return array
     */
    public static function getInventoryItems(){
        return [
            [
                'title' => 'Bedroom',
                "items"     => array_keys(Config::get('inventoryList.Bedroom'))
            ],
            [
                'title' => 'Lounge',
                "items"     => array_keys(Config::get('inventoryList.Lounge'))
            ],
            [
                'title' => 'Kitchen',
                "items"     => array_keys(Config::get('inventoryList.Kitchen'))
            ],
            [
                'title' => 'Laundry',
                "items"     => array_keys(Config::get('inventoryList.Laundry'))
            ],
            [
                'title' => 'Office',
                "items"     => array_keys(Config::get('inventoryList.Office'))
            ],
            [
                'title' => 'Hall',
                "items"     => array_keys(Config::get('inventoryList.Hall'))
            ],
            [
                'title' => 'Garage',
                "items"     => array_keys(Config::get('inventoryList.Garage'))
            ],
            [
                'title' => 'Cartons',
                "items"     => array_keys(Config::get('inventoryList.Cartons'))
            ],
        ];
    }
}
