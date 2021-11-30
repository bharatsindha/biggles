<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Yajra\DataTables\Facades\DataTables;

class Customer extends Crud
{
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'stripe_id', 'metadata'];

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        return self::getCustomerModel();
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
        $options['matchRaw'] = [
            "CONCAT(customers.first_name, ' ', customers.last_name)" => $keyword,
        ];
        $options['match'] = [
            "customers.email" => $keyword,
            "customers.phone" => $keyword,
        ];

        // generate the query for global search
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]) {
            // get the count of matched customer with keyword
            $result = $model->count();
        } else {
            // get the generated datatable for customer
            $result = self::getCustomerDatatable($model);
        }

        return $result;
    }

    /**
     * Return Customer name by customer Id.
     *
     * @param $customerId
     * @return string
     */
    public static function getCustomerNameById($customerId)
    {
        try {
            return self::where('id', $customerId)->pluck('name')->first();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @param $customerId
     * @return object
     */
    public static function getCustomerById($customerId)
    {
        try {
            return self::where('id', $customerId)->first();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Return object of the customer model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getCustomerModel()
    {
        $model = self::query();
        $user = Auth::user();

        $cols = ["customers.*"];

        $model->select($cols);

        // check the condition if it is company agent
        if (General::isCompanyAgent()) {
            $companyId = $user->company_id;
            $model = $model->whereIn('customers.id', function ($query) use ($companyId) {
                $query->select('customer_id')->from('moves')->where('company_id', $companyId);
            });
        }

        return $model;
    }

    /**
     * Return customer details for options
     *
     * @return array|string
     */
    public static function getCustomerOptions()
    {
        try {
            // Get the customer data
            $customerResults = self::getCustomerModel()->get();
            $optionValues = [];

            foreach ($customerResults as $customerResult) {
                $optionValues[$customerResult->id] = $customerResult->first_name . '' . $customerResult->last_name;
            }

            return $optionValues;

        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * get customer details
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public static function getCustomer()
    {
        // get the object of customer model
        $model = self::getCustomerModel();

        // get the customer datatable
        return self::getCustomerDatatable($model);
    }

    /**
     * Generate the datatable of customer
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public static function getCustomerDatatable($model)
    {
        $user = Auth::user();

        // Get the customer datatable
        return DataTables::eloquent($model)
            ->addColumn('action', function ($customer) use ($user) {
                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $customer)->with('route', 'customer.show');
                $action .= View('layouts.actions.edit')->with('model', $customer)->with('route', 'customer.edit');
                if ($user->access_level == 0) {
                    $action .= View('layouts.actions.delete')->with('model', $customer)
                        ->with('route', 'customer.destroy');
                }
                return $action .= '</div>';
            })
            ->addColumn('created_at', function ($q) {
                return date("d F, Y g:i A", strtotime($q->created_at));
            })
            ->addColumn('name', function ($q) {
                return $q->first_name . ' ' . $q->last_name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Return customer details
     *
     * @param $customerId
     * @return mixed
     */
    public static function getCustomerDetails($customerId)
    {
        return (new Customer())->find($customerId);
    }

    /**
     * Create or update customer details
     *
     * @param $request
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function addUpdateCustomerDetails($request)
    {
        // If no matching model exists, create one.
        $customer = self::updateOrCreate(
            ['email' => $request->email],
            ['first_name' => $request->firstName, 'last_name' => $request->lastName, 'email' => $request->email, 'phone' => $request->phone]
        );

        $move = Move::find($request->__move_unique_key);
        if ($move !== null) {
            $move->customer_id = $customer->id;
            $move->stage = Configuration::STAGE_LEAD;
            $move->save();
        }

        $encryptedId = encrypt($move->id);
        $encryptedLink = route('customer_access.index', $encryptedId);

        // generate the stripe id
        $customer = self::createStripeCustomer($customer);

        // To send the email notification to customer
        /*$details = [
        'view'    => "mails.register_customer",
        'data'    => [
        'move' => $move, 'reason' => '',
        'name' => $request->firstName . ' ' .$request->lastName,
        'encryptedLink' => $encryptedLink],
        'to'      => $request->email,
        'subject' => 'Your Muval account, ' . $request->email .', has been created'
        ];
        SendEmail::dispatchNow($details);*/

        return $customer;
    }

    /**
     * Create a Stripe Customer
     *
     * @param $customer
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    protected static function createStripeCustomer($customer)
    {
        if ($customer) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $searchResults = \Stripe\Customer::all([
                "email" => $customer->email,
            ]);

            if ($searchResults && count($searchResults->data) > 0) {
                $stripeData = $searchResults->data[0];
                // Customer already exists
                $stripeCustomer = \Stripe\Customer::update($stripeData->id, [
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'description' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                ]);

                if ($stripeCustomer && isset($stripeCustomer->id)) {
                    $customer->stripe_id = $stripeCustomer->id;
                    $customer->save();
                }
            } else {
                // Add new customer
                $stripeCustomer = \Stripe\Customer::create([
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'description' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                ]);

                if ($stripeCustomer && isset($stripeCustomer->id)) {
                    $customer->stripe_id = $stripeCustomer->id;
                    $customer->save();
                }
            }
        }

        return $customer;
    }

    /**
     * Get the setup intent
     *
     * @param $stripeId
     * @return \Stripe\SetupIntent|null
     */
    public static function getSetupIntent($stripeId)
    {
        if ($stripeId && !empty($stripeId)) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                // Create a SetupIntent is an object that represents your intent to set up a customer’s card for future payments.
                return $stripeIntent = \Stripe\SetupIntent::create([
                    'customer' => $stripeId,
                ]);

            } catch (Exception $exception) {

            }

        }

        return null;
    }
}
