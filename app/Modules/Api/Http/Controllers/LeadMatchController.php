<?php

namespace App\Modules\Api\Http\Controllers;

use App\Facades\General;
use App\Models\Company;
use App\Models\Depot;
use App\Models\InterstateConfiguration;
use App\Models\Lane;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeadMatchController extends Controller
{
    /**
     * Calculate Volume
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function findMatch(Request $request)
    {
        // check if the request has all required params
        if ($request->has('start_lat') && $request->has('start_lng') &&
            $request->has('end_lat') && $request->has('end_lng')) {

            // Check the removal type
            $removalType = self::checkRemovalType($request);

            if ($removalType == 'local'){
                $matchResult = Depot::getDepotsByRadius($request);
            }else{
                $matchResult = Lane::getLanesByRadius($request);
            }

            // Calculate the estimate moving time
            $estimatedMovingTime = General::calculateEstimatedMovingTime(
                $request->start_access, $request->end_access, $request->space);

            return response()->json(["matches"       => $matchResult,
                                     "estimatedTime" => $estimatedMovingTime,
                                     "removalType"   => $removalType], 200);
        }
    }


    /**
     * Check the removal type
     *
     * @param $request
     * @return string
     */
    public function checkRemovalType($request){
        // calculate the distance between the start and end address
        $calculatedDistance = distance($request->start_lat, $request->start_lng,
            $request->end_lat, $request->end_lng);

        $removalType = 'interstate';
        // if the distance is less than 100 km then search for depots
        if ($calculatedDistance < 100) {
            $removalType = 'local';
        } else {
            $removalType = 'interstate';
        }

        return $removalType;
    }

    /**
     * Return removal lists
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getRemovalLists(Request $request)
    {
        // Check the removal type
        if ($request->removal_type == ''){
            $request->removal_type = self::checkRemovalType($request);
        }

        // If requested for local removals then pass according to that otherwise interstate
        if ($request->removal_type == 'local') {
            $removalLists = Depot::getLocalRemovalLists($request);
        } else {
            $removalLists = Lane::getInterstateRemovalLists($request);
        }

        // Calculate the estimate moving time
        $estimatedMovingTime = General::calculateEstimatedMovingTime(
            $request->start_access, $request->end_access, $request->space);

        return response()->json([
            "removalLists"  => $removalLists,
            "estimatedTime" => $estimatedMovingTime], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getRemovalDetails(Request $request)
    {
        // Check the removal type
        if ($request->removal_type == ''){
            $request->removal_type = self::checkRemovalType($request);
        }

        // If requested for local removals then pass according to that otherwise interstate
        if ($request->removal_type == 'local') {
            $removalDetails = Depot::getRemovalDetails($request);
        } else {
            $removalDetails = Lane::getInterstateRemovalDetails($request);
        }

        return response()->json(["removalDetails" => $removalDetails], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getDeliveryPrices(Request $request)
    {
        // make the date format
        $startDate = new Carbon($request->start_date);
        $endDate   = new Carbon($request->end_date);
        $pickupDate   = new Carbon($request->pickup_date);
        $removalType   = $request->removal_type;

        // Get the storage charge date based on pickup date
        $storage = Lane::getStorageChargeDate((object)['companyId'   => $request->companyId,
                                                       'pickup_date' => $request->pickup_date,
                                                       'delivery_date' => null,
                                                       'removalType' => $removalType]);

        $storageChargeDate     = $storage['storageChargeDate'];
        $storagePrice          = $storage['storagePrice'];
        $storageChargeDateTemp = new Carbon($storageChargeDate);
        $diffDays              = $storageChargeDateTemp->diffInDays($startDate);
        $deliveryPrices        = [];

        // If the storage charge date is greater than pickup date
        $price = ($startDate->gt($storageChargeDateTemp)) ? ($diffDays * $storagePrice) : 0;

        while ($startDate->lte($endDate)) {
            $calenderDate = $startDate->toDateString();

            if ($calenderDate >= $storageChargeDate) {
                $price += $storagePrice;
            }

            $deliveryPrice['date']  = $calenderDate;
            $deliveryPrice['price'] = $price;
            $deliveryPrices[]       = $deliveryPrice;

            $startDate->addDay();
        }

        return response()->json(["deliveryPrices" => $deliveryPrices], 200);
    }

    /**
     * Return pricing details
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getPricingDetail(Request $request)
    {
        // Check the removal type
        if ($request->removal_type == ''){
            $request->removal_type = self::checkRemovalType($request);
        }

        // If requested for local removals then pass according to that otherwise interstate
        if ($request->removal_type == 'local') {
            $removalDetails = Depot::getDepotRemovalPricing($request);
        } else {
            $removalDetails = Lane::getInterstateRemovalPricing($request);
        }

        return response()->json(["pricingBreakdown" => $removalDetails], 200);
    }

}
