<?php

namespace App\Modules\Api\Http\Controllers;

use App\Facades\General;
use App\Http\Resources\MoveResource;
use App\Jobs\SendEmail;
use App\Models\AncillaryService;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Move;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;

class MoveController extends Controller
{


    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Muval API",
     *      description="Muval API description",
     *      @OA\Contact(
     *          email="admin@muval.com.au"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Muval API Server"
     * )
     *
     * @OA\Tag(
     *     name="Leads",
     *     description="API Endpoints of Leads"
     * )
     */
    /*
    *      security={
    *          {"passport": {}},
    *      },
    */

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public static function saveLeadDetails(Request $request)
    {

        if ($request->has('lead_id')) {

            // Find the move by id
            $move = Move::find($request->get('lead_id'));
            if ($move) {

                if (!is_null($request) && $request->has('status')) {
                    $status = $request->get('status');
                    if ($status && $status != '') {
                        // check if valid move status
                        $statusId = Configuration::getOptionIdByValue('move_status', $status);
                        if ($statusId && $statusId > 0) {
                            // update the move status into storage
                            $move->status = $statusId;
                            $move->save();
                        }
                    }
                }

                if ($move->customer) {
                    $customer = $move->customer;

                    if ($request->has('customer_name')) {
                        $name = explode(" ", $request->customer_name);
                        $customer->first_name = $name[0] ?? $customer->first_name;
                        $customer->last_name = $name[1] ?? $customer->last_name;
                    }

                    $customer->email = $request->customer_email ?? $customer->email;
                    $customer->phone = $request->customer_phone ?? $customer->customer_phone;
                    $customer->save();
                }

                return response()->json([
                    "status"  => "success",
                    "data"    => ["lead_id" => $move->id],
                    "message" => trans('common.success_update_msg', ['module_name' => 'lead'])]);
            }

        }

        return response()->json(["status" => "fail", "message" => "No record found"]);
    }


    /**
     * @OA\Get(
     *      path="/lead/{id}",
     *      operationId="getLeadDetails",
     *      tags={"leads"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Get lead detail",
     *      description="Returns lead detail",
     *      @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *            type="integer"
     *       )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                          "status": "success",
     *                        "status_code": 200,
     *                        "data": {
     *                                "lead": {
     *                                    {
     *                                     "lead_id": 1,
     *                                     "customer_name": "XYZ",
     *                                     "customer_phone": "701-529-9385",
     *                                     "customer_email": "vickie00@example.com",
     *                                     "created_date": "2020-06-27 09:31:33",
     *                                     "date_requested_for_move": "2020-06-27 09:31:33",
     *                                     "space": "68.83",
     *                                     "start_addr": "312 Reichel Crescent\nWest Taliaside, AZ 83189",
     *                                     "start_city": "Lake Deonfurt",
     *                                     "start_postcode": 3661,
     *                                     "end_addr": "8234 Rasheed Junctions\nAnastacioport, IA 45434-2594",
     *                                     "end_city": "Pagacburgh",
     *                                     "end_postcode": 1433,
     *                                     "status": "Lead"
     *                                    }
     *                                }
     *                        },
     *                          "message": "Lead details pulled out successfully"
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     ),
     * @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Unauthenticated."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     * @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Bad Request."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     * @OA\Response(
     *          response=404,
     *          description="not found",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "not found."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     * @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Forbidden."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *  )
     */

    /**
     * Return the access attributes with the questions
     *
     * @return array
     */
    public static function getAccessAttribute()
    {
        // Get the list of access attributes
        $attributeItems = General::getAccessAttribute();

        return response()->json(["attributeItems" => $attributeItems], 200);
    }

    /**
     * @OA\Post(
     *      path="/lead",
     *      operationId="setLeadDetails",
     *      tags={"leads"},
     *     security={
     *          {"passport": {}},
     *      },
     *      summary="Add lead detail",
     *      description="Add lead detail",
     *  @OA\Parameter(
     *      name="customer_first_name",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="customer_last_name",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="customer_phone",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="customer_email",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="space",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="from",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="to",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="status",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "status": "success",
     *    "data": {
     *        "lead_id": 1
     *    },
     *    "message": "lead has been added successfully"
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Unauthenticated."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Bad Request."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "not found."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Forbidden."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *  )
     */

    /**
     * @OA\POST(
     *      path="/leads",
     *      operationId="getLeadList",
     *      tags={"leads"},
     *      summary="Get list of leads",
     *      description="Returns list of leads",
     *      security={
     *       {"passport": {}}
     *      },
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="end_date",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="stage",
     *                     type="string"
     *                 ),
     *                 example={"status": "Hot", "start_date": "2020-06-07","end_date": "2020-07-07"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "status": "success",
     *                         "status_code": 200,
     *                         "data": {
     *                          "leads": {
     *                              {
     *                               "lead_id": 1,
     *                               "customer_name": "Conner Rodriguez",
     *                               "customer_phone": "701-529-9385 x43004",
     *                               "customer_email": "vickie00@example.com",
     *                               "created_date": "2020-06-27 09:31:33",
     *                               "date_requested_for_move": "2020-06-27 09:31:33",
     *                               "space": "68.83",
     *                               "start_addr": "312 Reichel Crescent\nWest Taliaside, AZ 83189",
     *                               "start_city": "Lake Deonfurt",
     *                               "start_postcode": 3661,
     *                               "end_addr": "8234 Rasheed Junctions\nAnastacioport, IA 45434-2594",
     *                               "end_city": "Pagacburgh",
     *                               "end_postcode": 1433,
     *                               "status": "Hot"
     *                              },
     *                           }
     *                          }
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Unauthenticated."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Bad Request."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "not found."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                            "message": "Forbidden."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *  )
     */
    public function getLeads(Request $request)
    {
        $leads = Move::getLeads(null, $request);

        return response()->json([
            'status'      => 'success',
            'status_code' => Response::HTTP_OK,
            'data'        => [
                'leads' => MoveResource::collection($leads)
            ],

            'message' => 'All leads pulled out successfully'
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    /**
     * Return lead details
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getLeadDetails(Request $request, $id)
    {
        $leads = Move::getLeads($id, $request);

        return response()->json([
            'status'      => 'success',
            'status_code' => Response::HTTP_OK,
            'data'        => [
                'lead' => MoveResource::collection($leads)
            ],

            'message' => 'Lead details pulled out successfully'
        ]);
    }

    /**
     * Calculate Volume
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateVolume(Request $request)
    {
        $estimatedVolume = 0.0;

        if ($request->has("itemLists")) {
            foreach ($request->get("itemLists") as $itemList) {
                foreach ($itemList['items'] as $item) {
                    // calculate items volume
                    if ($item["custom"]) {
                        $estimatedVolume += (((float)$item["volume"]) * $item["value"]);
                    } else {
                        $estimatedVolume += $this->_calculateVolume($itemList["title"], $item);
                    }
                }
            }
        }
        $estimatedVolume = number_format($estimatedVolume, 2, '.', '');

        return response()->json(["estimated_volume" => $estimatedVolume], 200);
    }

    /**
     * Calculate volume
     *
     * @param $itemName
     * @param $item
     * @return float|int
     */
    private function _calculateVolume($itemName, $item)
    {
        $itemVolume = 0.00;

        // calculate the volume
        if (is_array($item)) {
            $itemConfigs = Config::get("inventoryList.$itemName");
            // if item matched then calculate volume
            if (isset($item['item']) && key_exists($item['item'], $itemConfigs)) {
                $matchedItem = $itemConfigs[$item['item']];
                if (isset($matchedItem['volume'])) {
                    $itemVolume += $matchedItem['volume'] * $item['value'];

                }
            }
        }
        return $itemVolume;
    }

    /**
     * Get Configuration Item
     *
     * @return JsonResponse
     */
    public function getConfigurationItem()
    {
        // return the config items for volume calculations
        return response()->json([
            'inventoryList'   => General::getInventoryList(),
            'inventoryItems' => General::getInventoryItems()
        ], 200);

    }

    /**
     * Calculate rough volume
     *
     * @return Response
     */
    public function calculateRoughVolume(Request $request)
    {

        $roughEstimatedVolume = 0.0;

        // check if a property is an apartment
        if ($request->has('dwellingType') && ucfirst($request->get('dwellingType')) == 'Apartment') {
            $noOfBedRoom = $request->get('dwellingSize');
            $itemConfigs = Config::get("inventoryList.Apartment");
            $key = $noOfBedRoom . "_bedroom_apartment";
            if (key_exists($key, $itemConfigs)) {
                $matchedItem = $itemConfigs[$key];
                // check if items exist or not
                if (isset($matchedItem['volume'])) {
                    // calculate the volume based on the number of items
                    $roughEstimatedVolume += $matchedItem['volume'];
                }
            }
        }

        // check if the property is house
        if ($request->has('dwellingType') && ucfirst($request->get('dwellingType')) == 'House') {
            $noOfApartment = $request->get('dwellingSize');
            $itemConfigs = Config::get("inventoryList.House");
            $key = $noOfApartment . "_bedroom_house";
            if (key_exists($key, $itemConfigs)) {
                $matchedItem = $itemConfigs[$key];
                // check if items exist or not
                if (isset($matchedItem['volume'])) {
                    // calculate the volume based on the number of items
                    $roughEstimatedVolume += $matchedItem['volume'];
                }
            }
        }

        return response()->json(["estimated_volume" => $roughEstimatedVolume], 200);
    }

    /**
     * Set Move Details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setMoveDetails(Request $request)
    {
        // If it is null then by default it will consider local
        $request->removal_type = trim($request->removal_type) ?? 'Local';
        // make the same format to save into resource
        $request->pickup_date = Carbon::parse($request->pickup_date)->addDay(1)->format('Y-m-d H:i:s');
        $request->delivery_date = Carbon::parse($request->delivery_date)->addDay(1)->format('Y-m-d H:i:s');

        // find the move if it is requested previously
        $move = Move::find($request->__move_unique_key);
        if ($move == null) {
            $move = new Move();
        }

        // match the requested parameter with a move
        $move->fill($request->all());
        // get the id of move type e.g local or interstate
        $move->type = Configuration::getOptionIdByValue('move_type', $request->removal_type);

        // convert an array into JSON format
        $move->start_access = json_encode($request->start_access);
        $move->end_access = json_encode($request->end_access);
        $move->inventory = json_encode($request->inventory);
        $move->matches = json_encode($request->matches);
        // assign date into fields
        $move->start_date = $request->pickup_date;
        $move->requested_date = $request->pickup_date;
        $move->end_date = $request->delivery_date;
        $move->amount_due = $request->total_price;
        // Total fee is 20% of total move price
        $move->fee = round(floatval($request->total_price) * 0.2, 2);

        // set the default value of stage => Prospect and status => pending
        $move->stage = 1;
        $move->status = 12;
        // If customer details not been added
        if ($request->__customer_unique_key == null) {
            $move->stage = Configuration::STAGE_PROSPECT;
        } else {
            $move->stage = Configuration::STAGE_LEAD;
        }
        // If payment card details is updated from the client.
        if ($request->current_stage >= 14) {
            $move->stage = Configuration::STAGE_JOB;
        }
        $move->dwelling_size = $request->dwelling_size ?? 0;
        $move->save();

        if (isset($request->ancillaries) && !empty($request->ancillaries) && !is_null($request->ancillaries)) {
            AncillaryService::updateMoveAncillary($request, $move);
        }

        // Send the notification to company agent on dashboard if all steps are done
        if ($request->current_stage == 14 && $request->company_id > 0) {
            $notification = new Notification();
            $notification->company_id = $request->company_id;
            $notification->text = 'New job requested for the removal';
            $notification->save();

            // Get all the agents of the company which been requested by customer
            $companyAgents = User::where('company_id', $request->company_id)->get();
            foreach ($companyAgents as $companyAgent) {
                $userNotification = new UserNotification();
                $userNotification->notification_id = $notification->id;
                $userNotification->user_id = $companyAgent->id;
                $userNotification->save();
            }

        }

        // Send an email notification to customer once he finish the steps on the front
        /*if ($request->current_stage == 12 && $move->is_verify == 0){
            $encryptedId = encrypt($move->id);
            $encryptedEmail = encrypt($move->customer->email);
            $encryptedLink = route('customer_verification.verify', [$encryptedId, $encryptedEmail]);

            $details = [
                'view'    => "mails.customer_confirmation",
                'data'    => [
                    'move' => $move, 'reason' => '',
                    'name' => $move->customer->first_name . ' ' .$move->customer->last_name,
                    'encryptedLink' => $encryptedLink],
                'to'      => $move->customer->email,
                'subject' => 'Account verification'
            ];
            SendEmail::dispatchNow($details);

            $move->is_verify = 1;
            $move->save();
        }*/

        return response()->json(["__move_unique_key" => $move->id], 200);
    }

    /**
     * Get move details
     *
     * @param $move
     * @return JsonResponse
     */
    public function getMoveDetails($move)
    {
        $move = Move::find($move);
        if ($move == null) {
            return response()->json(["moveDetails" => null, 'message' => 'not found'], 200);
        }
        $ancillaries = [];
        // Get the ancillary service lists selected by customer
        foreach ($move->ancillaryServices as $key => $ancillary) {
            $ancillaries[$key]['ancillary_id'] = $ancillary->pivot->ancillary_id;
            $ancillaries[$key]['answers'] = $ancillary->pivot->answers;
            $ancillaries[$key]['move_id'] = $ancillary->pivot->move_id;
        }
        $move->ancillaries = $ancillaries;
        $move->companyName = $move->company->name;

        $move->start_access = json_decode($move->start_access);
        $move->end_access = json_decode($move->end_access);
        $move->inventory = json_decode($move->inventory);
        // Get the removal type
        $move->removal_type = Configuration::getOptionValueById($move->type);

        return response()->json(["moveDetails" => $move], 200);
    }

    /**
     * Add customer details
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function addCustomer(Request $request)
    {
        // Add or update the customer details
        $customer = Customer::addUpdateCustomerDetails($request);
        // Setup the intent for the customer
        $customer->intentToken = Customer::getSetupIntent($customer->stripe_id);

        return response()->json(["customerDetails" => $customer], 200);

//        return response()->json(["__customer_unique_key" => $customer->id], 200);
    }

    /**
     * Get all ancillary services
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAncillaryServices(Request $request)
    {
        // Get the ancillary service data
        $ancillaryServices = AncillaryService::getAncillaryServices($request);

        return response()->json(["ancillaryServices" => $ancillaryServices], 200);
    }

    /**
     * Get all booked ancillary services
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBookedAncillaries(Request $request)
    {
        // Get the booked ancillary services with the price of the customer
        $ancillaryServices = AncillaryService::getAncillaryServicePrice($request->moveId);

        return response()->json(["bookedAncillaries" => $ancillaryServices], 200);
    }

    /**
     * Return customer details
     *
     * @param $customerId
     * @return JsonResponse
     */
    public function getCustomerDetails($customerId)
    {
        // Get the customer details
        $customerDetails = Customer::getCustomerDetails($customerId);

        return response()->json(["customerDetails" => $customerDetails], 200);
    }

    /**
     * Creates an intent for payment so we can capture the payment
     * method for the user.
     *
     * @param $customerId
     * @return JsonResponse
     */
    public function getSetupIntent($customerId)
    {
        // Get the customer details
        $customer = Customer::getCustomerDetails($customerId);
        // Setup the intent on stripe for customer
        $intentToken = Customer::getSetupIntent($customer->stripe_id);
        $stripePublicKey = env('STRIPE_KEY');

        return response()->json([
            "intentToken"     => $intentToken,
            'stripeAPIToken'  => $stripePublicKey,
            "customerDetails" => $customer], 200);
    }

}
