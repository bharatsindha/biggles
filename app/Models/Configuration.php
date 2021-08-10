<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Configuration extends Model
{
    const MODULE_MOVES    = 'move';
    const MODULE_DEPOT    = 'depot';
    const MODULE_LOCAL    = 'local';
    const MODULE_FLEET    = 'truck';
    const MODULE_LANE     = 'lane';
    const MODULE_CUSTOMER = 'customer';
    const MODULE_COMPANY  = 'company';
    const MODULE_STAFF    = 'user';
    const STAGE_PROSPECT  = 1;
    const STAGE_LEAD      = 2;
    const STAGE_JOB       = 3;
    const TYPE_LOCAL      = 4;
    const TYPE_INTERSTATE = 5;

    use SoftDeletes;

    protected $fillable = ['option_type', 'option_value', 'created_by'];

    /**
     * Return Options by Type
     *
     * @param $optionType
     * @return array
     */
    public static function getOptionsByType($optionType)
    {
        // Get the config result for option type
        $configResults = self::where('option_type', $optionType)->get();
        $optionValues  = [];

        foreach ($configResults as $configResult) {
            $optionValues[$configResult->id] = $configResult->option_value;
        }

        return $optionValues;
    }

    /**
     * Return Option value by option id
     *
     * @param $optionId
     * @return string
     */
    public static function getOptionValueById($optionId)
    {
        try {
            return self::where('id', $optionId)->pluck('option_value')->first();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Return option id by option value
     *
     * @param $optionType
     * @param $optionVal
     * @return string
     */
    public static function getOptionIdByValue($optionType, $optionVal)
    {
        try {
            return self::where('option_type', $optionType)
                ->where('option_value', $optionVal)->pluck('id')
                ->first();
        } catch (Exception $e) {
            return '';
        }
    }


    /**
     * Return configuration data
     *
     * @return array
     */
    public static function getConfigurations()
    {
        $configReturnArray = [];

        # Fetch all move stages
        $configResults = self::orderBy('option_type')->get();

        foreach ($configResults as $configResult) {
            $configReturnArray[$configResult->option_type][$configResult->id] = $configResult->option_value;
        }

        return $configReturnArray;
    }

    /**
     * Update or Insert the configuration details
     *
     * @param $request
     * @return bool
     */
    public static function manageConfiguration($request)
    {
        try {
            $requestConfig = $request->except(['_token', '_method', 'option_config', 'temp_index']);

            # Iteration for each Option type
            foreach ($requestConfig as $optionType => $value) {

                $optionIds  = [];
                $optionType = trim($optionType);
                # Iteration of each option value for the specific option type
                foreach ($value as $index => $option) {

                    $optionValue = trim($option['value']);
                    $id          = isset($option["id"]) ? $option["id"] : null;

                    $options                 = [];
                    $options['option_value'] = $optionValue;
                    if ($id == null) {
                        $options["created_by"]  = Auth::user()->id;
                        $options['option_type'] = $optionType;
                    }
                    if (!empty($optionValue)) {

                        # Update the data or create if not exist
                        $optionData = self::updateOrCreate(['id' => $id], $options);
                        if ($optionData) {
                            array_push($optionIds, $optionData->id);
                        }
                    }
                }

                # Remove the data which are not in the request
                self::where('option_type', $optionType)->whereNotIn('id', $optionIds)->delete();
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    /*
     * Return module details
     *
     * @return array
     * */
    public static function getModuleDetails()
    {
        $searchModule = [
            self::MODULE_MOVES    => [
                "model"      => '\App\Models\Move',
                "searchable" => true,
            ],
            self::MODULE_DEPOT    => [
                "model"      => '\App\Models\Depot',
                "searchable" => true,
            ],
            self::MODULE_LOCAL    => [
                "model"      => '\App\Models\Local',
                "searchable" => true,
            ],
            self::MODULE_FLEET    => [
                "model"      => '\App\Models\Truck',
                "searchable" => true,
            ],
            self::MODULE_LANE     => [
                "model"      => '\App\Models\Lane',
                "searchable" => true,
            ],
            self::MODULE_CUSTOMER => [
                "model"      => '\App\Models\Customer',
                "searchable" => true,
            ]
        ];

        // search into the companies if it is muval agent
        if (General::isSuperAdmin()){
            $searchModule += [
                self::MODULE_COMPANY  => [
                    "model"      => '\App\Models\Company',
                    "searchable" => true,
                ]
            ];
            $searchModule += [
                self::MODULE_STAFF => [
                    "model"      => '\App\Models\User',
                    "searchable" => true,
                ]
            ];
        }

        return $searchModule;
    }

}
