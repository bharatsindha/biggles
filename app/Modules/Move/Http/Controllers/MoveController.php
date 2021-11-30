<?php

namespace App\Modules\Move\Http\Controllers;

use App\Facades\General;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMove;
use App\Jobs\SendEmail;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Move;
use App\Models\Payment;
use App\Models\Truck;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Throwable;

class MoveController extends Controller
{
    /**
     * Module variable
     *
     * @var array
     */
    protected $moduleName;

    /**
     * Class Constructor.
     *
     * @return string
     */
    public function __construct()
    {
        $this->moduleName = $this->getModuleName();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->keyword) && !empty($request->keyword)) {
                // Get the moves for global search matched with keyword
                return (new Move())->getSearchResults($request->keyword);
            } else {
                // Get the move listing
                return Move::getMoves($request);
            }
        }

        $data['jobTitles'] = Move::getJobTitleCount();
        General::log('SL000701', ['action_module' => $this->moduleName]);

        return View('move::index', ['moduleName' => $this->moduleName, 'data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the configuration options used to create move
        $data = Move::getOptionsFromConfiguration();

        return View('move::createOrUpdate', compact('data'))->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMove $request
     * @return RedirectResponse|Redirector
     */
    public function store(StoreMove $request)
    {
        // Save the move into storage
        $move = new Move($request->all());
        if (General::isCompanyAgent()) {
            $move->company_id = Auth::user()->company_id;
        }
        $move->save();

        if ($move) {
            // Manage the Ancillaries for the move
            $move->ancillaryServices()->attach($request->get('ancillaryServices'));
        }

        Session::flash('success', trans(
            'common.success_add_msg',
            array('module_name' => is_string($this->moduleName) ? ucfirst($this->moduleName) : '')
        ));

        General::log('SL000702', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        return redirect('move');
    }

    /**
     * Display the specified resource.
     *
     * @param Move $move
     * @return Factory|View
     */
    public function show(Move $move)
    {
        // Get the company name
        $data['companyName'] = Company::getCompanyNameById($move->company_id);
        // Get the customer name
        $customer = Customer::getCustomerById($move->customer_id);
        $data['firstLetter'] = General::getFirstLetterFromName($customer->first_name . ' ' . $customer->last_name);

        // Get the options value from the configuration
        $move = Move::setOptionsValueFromConfiguration($move);

        General::log('SL000705', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        return View('move::show', compact('move', 'data', 'customer'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Display the specified resource.
     *
     * @param Move $move
     * @return Factory|View
     */
    public function showJobDetails(Move $move)
    {
        // Get the customer details
        $customer = Customer::getCustomerById($move->customer_id);
        // Get the checklist data
        $checklistArr = General::getChecklists();
        $move->checklists = json_decode($move->checklists);

        // Get the options value from the configuration
        $move = Move::setOptionsValueFromConfiguration($move);

        General::log('SL000705', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        return View('move::jobDetail', compact('move', 'customer', 'checklistArr'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Move $move
     * @return Factory|View
     */
    public function edit(Move $move)
    {
        // Get the options from the configuration
        $data = Move::getOptionsFromConfiguration();

        return View('move::createOrUpdate', compact('move', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * pdate the specified resource in storage.
     *
     * @param StoreMove $request
     * @param Move $move
     * @return RedirectResponse|Redirector
     */
    public function update(StoreMove $request, Move $move)
    {
        // Save move details into storage
        $move->fill($request->all());
        $move->save();
        if ($move) {
            // Manage the ancillary for the move
            $move->ancillaryServices()->detach();
            $move->ancillaryServices()->attach($request->get('ancillaryServices'));
        }

        Session::flash('success', trans(
            'common.success_update_msg',
            array('module_name' => is_string($this->moduleName) ? ucfirst($this->moduleName) : '')
        ));

        General::log('SL000703', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        return redirect('move');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Move $move
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Move $move)
    {
        General::log('SL000704', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr],
        ]);

        Session::flash(
            'success',
            trans('common.success_delete_msg', array(
                'module_name' => is_string($this->moduleName) ? ucfirst($this->moduleName) : '',
            ))
        );
        $move->delete();

        return redirect('move');
    }

    /**
     * update the decline job on the storage
     *
     * @param Move $move
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function declineJob(Move $move, Request $request)
    {
        // Save the decline job status into storage
        $request->status = trim($request->status);
        $declineComment = trim($request->decline_comment);
        $move->status = Configuration::getOptionIdByValue('move_status', 'Declined');
        $move->save();

        Session::flash(
            'success',
            trans('common.success_declined_msg', array(
                'module_name' => is_string($this->moduleName) ? ucfirst($this->moduleName) : '',
            ))
        );

        General::log('SL000707', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        $details = [
            'view' => "mails.declined_email",
            'data' => ['move' => $move, 'reason' => $declineComment],
            'to' => $move->customer->email,
            'subject' => 'Job Declined For Customer ' . $move->customer->first_name . ' By Company ' . $move->company->name,
        ];
        // Send an email notification to customer about decline the job
        SendEmail::dispatch($details);

        return redirect()->back();
    }

    /**
     * Return HTML code to accept job for modal
     *
     * @param Move $move
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function acceptJobHtml(Move $move, Request $request)
    {
        // Get the trucks
        $truckOptions = Truck::getTruckOptions();
        // Get the customer name by id
        $customerName = Customer::getCustomerNameById($move->customer_id);
        $updateStatus = $request->update_status;

        $data = view('move::modal.acceptJob', compact(
            'move',
            'truckOptions',
            'customerName',
            'updateStatus'
        ))->render();

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'We have received your message.',
        ]);
    }

    /**
     * Return HTML code to accept job for scheduler modal
     *
     * @param Move $move
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function acceptJobSchedulerHtml(Move $move, Request $request)
    {
        // Get the customer name by id
        $customerName = Customer::getCustomerNameById($move->customer_id);
        $truckName = '';
        if (isset($request->truckId)) {
            // Get the truck name
            $truckName = Truck::where('id', $request->truckId)->pluck('name')->first();
        }

        $data = view('move::modal.acceptJobScheduler', compact(
            'move',
            'customerName',
            'request',
            'truckName'
        ))->render();

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'We have received your message.',
        ]);
    }

    /**
     * Return HTML code to decline job for modal
     *
     * @param Move $move
     * @return JsonResponse
     * @throws Throwable
     */
    public function declineJobHtml(Move $move)
    {
        $data = view('move::modal.declineJob', compact('move'))->render();

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Rendered the Decline Job Comment box',
        ]);
    }

    /**
     * Update the resource in the storage for
     * accepted job
     *
     * @param Move $move
     * @param Request $request
     * @return RedirectResponse
     */
    public function acceptJob(Move $move, Request $request)
    {
        $move->fill($request->all());

        $move->status = Configuration::getOptionIdByValue('move_status', 'Accepted');

        # validate date & make the same format uses in storage
        $move->pickup_window_start = self::validateDate($request->pickup_window_start);
        $move->pickup_window_end = self::validateDate($request->pickup_window_end);
        $move->delivery_window_start = self::validateDate($request->delivery_window_start);
        $move->delivery_window_end = self::validateDate($request->delivery_window_end);

        $move->save();

        try {
            // Check the first payment is deposited ot not
            $paymentCount = Payment::where('move_id', $move->id)->count();
            if ($paymentCount == 0) {
                // Deposit first half payment on accepting the job
                Payment::StripePaymentProcess($move);
            }
        } catch (Exception $e) {
            $muvalSupportEmail = Config::get('muval.MUVAL_SUPPORT_ADDRESS');

            $details = [
                'view' => "mails.payment_error_accept",
                'data' => [
                    'errorCode' => $e->getCode(),
                    'errorMessage' => $e->getMessage(),
                    'move' => $move,
                ],
                'to' => $muvalSupportEmail,
                'subject' => 'Payment Error on Accept job'
            ];
            // Send an email notification to muval support about error on stripe payment
            SendEmail::dispatchSync($details);
        }

        Session::flash('success', trans(
            'common.success_accepted_msg',
            array('module_name' => is_string($this->moduleName) ? ucfirst($this->moduleName) : '')
        ));

        General::log('SL000706', [
            'action_module' => $this->moduleName,
            'parent_id' => $move->id,
            'event_data' => ['name' => $move->start_addr, 'id' => $move->id],
        ]);

        return redirect()->back();
    }

    /**
     * Return formatted date use in the storage
     *
     * @param $date
     * @return string|null
     */
    public static function validateDate($date)
    {
        return isset($date) && $date != 'Invalid date' ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Add Customer to Stripe
     *
     * @param Customer $customer
     * @return RedirectResponse|Redirector
     * @throws ApiErrorException
     */
    public function createStripeUser(Customer $customer)
    {
        if ($customer) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $searchResults = \Stripe\Customer::all([
                "email" => $customer->email,
            ]);

            $customerCard = \Stripe\Customer::createSource(
                $customer->stripe_id,
                [
                    'source' => [
                        'number' => '4242424242424242',
                        'exp_month' => '01',
                        'exp_year' => '21',
                        'cvc' => '123',
                        'object' => 'card',
                    ],
                ]
            );
            dd($customerCard);

            // Charge the Customer instead of the card:
            $charge = Charge::create([
                'amount' => 1000,
                'currency' => 'aud',
                'customer' => $customer->stripe_id,
            ]);
            // YOUR CODE: Save the customer ID and other info in a database for later.

            // When it's time to charge the customer again, retrieve the customer ID.
            $charge = Charge::create([
                'amount' => 1500, // $15.00 this time
                'currency' => 'usd',
                'customer' => $customer->id, // Previously stored, then retrieved
            ]);

            $stripeCustomer = \Stripe\Customer::create([
                'name' => $customer->name,
                'description' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
            ]);

            if ($stripeCustomer && isset($stripeCustomer->id)) {
                $customer->stripe_id = $stripeCustomer->id;
                Session::flash('success', trans('common.stripe_connect'));
            }
        }

        return redirect('move');
    }

    /**
     * Return the payment details
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getPaymentDetails(Request $request)
    {
        if ($request->ajax()) {
            // Get the payments data
            return Payment::getPayments($request);
        }
    }

    /**
     * update the decline job on the storage
     *
     * @param Move $move
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function completedJob(Move $move, Request $request)
    {
        // Company has completed the job
        $move->status = Configuration::getOptionIdByValue('move_status', 'Completed');
        $move->save();

        return redirect()->back();
    }

    public function updateMoveChecklist(Move $move, Request $request)
    {
        // Update the checklist on the storage
        $move->checklists = json_encode($request->checklists);
        $move->save();

        return redirect()->back();
    }

    /**
     * Get Module name
     *
     * @return void
     */
    public function getModuleName()
    {
        return "move";
    }
}
