<?php

namespace App\Models;

use App\Jobs\SendEmail;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Yajra\DataTables\Facades\DataTables;

class Payment extends Model
{
    use SoftDeletes;

    const PAYMENT_TYPE_DEPOSIT            = 0;
    const PAYMENT_TYPE_FINAL_PAYMENT      = 1;
    const PAYMENT_TYPE_ADDITIONAL_PAYMENT = 2;

    const PAYMENT_METHOD_STRIPE  = 0;
    const PAYMENT_METHOD_ZIP_PAY = 1;

    protected $fillable = ['customer_id', 'move_id', 'method', 'response', 'type', 'amount'];

    /**
     * Return payment information
     *
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    static public function getPayments($request)
    {
        $model = self::query();
        $user  = Auth::user();

        if (isset($request->moveId) && !empty($request->moveId)) {
            $model->where('payments.move_id', $request->moveId);
        }

        return DataTables::eloquent($model)
            ->addColumn('created_at', function ($q) {
                return date("d F, Y g:i A", strtotime($q->created_at));
            })
            ->addColumn('amount', function ($q) {
                return "$" . sbNumberFormat($q->amount);
            })
            ->make(true);
    }


    /**
     *  Make the first payment through stripe from customer stripe id
     *  it will cut half amount from the customer
     *  and then it will move 20% amount to muval account
     *  and remaining 80% amount to company
     */
    public static function StripePaymentProcess($move)
    {
        $muvalSupportEmail = Config::get('muval.MUVAL_SUPPORT_ADDRESS');

        # set up the stripe secret
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = $move->customer;
        if ($customer) {

            // 1. Get the payment method first
            $paymentMethod = PaymentMethod::all([
                'customer' => $customer->stripe_id,
                'type'     => 'card',
            ]);

            if ($paymentMethod) {
                $paymentMethods = $paymentMethod->toArray();

                if (is_array($paymentMethods) && isset($paymentMethods['data']) && isset($paymentMethods['data'][0]['id'])) {
                    $paymentMethodId = $paymentMethods['data'][0]['id'];
                    // 2. Charge the Customer instead of the card:

                    if ($move->total_price != null && $move->total_price != 0 && $move->total_price != '') {

                        $firstDepositAmount = round($move->total_price / 2, 2);
                        $muvalAmount        = round($firstDepositAmount * 0.2, 2);
                        $companyAmount      = round($firstDepositAmount * 0.8, 2);
                        // 4. Payout to company
                        // Send 80% of amount to Company
                        if ($firstDepositAmount > 0) {

                            $companyDetails = $move->company;

                            if ($companyDetails && !empty($companyDetails->stripe_auth_credentials)) {
                                $stripeDetails = json_decode($companyDetails->stripe_auth_credentials, true);

                                if (is_array($stripeDetails) && isset($stripeDetails['stripe_user_id'])) {

                                    $paymentResponse = null;
                                    try {
                                        $paymentResponse = PaymentIntent::create([
                                            'amount'                 => $firstDepositAmount,
                                            'currency'               => 'aud',
                                            'customer'               => $customer->stripe_id,
                                            'payment_method'         => $paymentMethodId,
                                            'off_session'            => true,
                                            'confirm'                => true,
                                            'payment_method_types'   => ['card'],
                                            'application_fee_amount' => $muvalAmount,
                                            'transfer_data'          => [
                                                'destination' => $stripeDetails['stripe_user_id'],
                                            ],
                                        ]);

                                        // 3. store response into database
                                        if ($paymentResponse) {
                                            $paymentObj              = new Payment();
                                            $paymentObj->customer_id = $move->customer_id;
                                            $paymentObj->move_id     = $move->id;
                                            $paymentObj->method      = Payment::PAYMENT_METHOD_STRIPE;
                                            $paymentObj->response    = $paymentResponse->toJSON();

                                            if ($move->amount_due == $move->total_price) {
                                                $paymentObj->type = Payment::PAYMENT_TYPE_DEPOSIT;
                                            } else {
                                                $paymentObj->type = Payment::PAYMENT_TYPE_FINAL_PAYMENT;
                                            }

                                            $paymentObj->amount = $firstDepositAmount;
                                            $paymentObj->save();

                                            if ($paymentObj) {
                                                // Update the move with amount due
                                                $move->amount_due = $move->amount_due - $firstDepositAmount;
                                                // Update the deposit amount
                                                $move->deposit = $firstDepositAmount;
                                                $move->save();
                                            }

                                            return 'success';
                                        }


                                    } catch (Exception $e) {

                                        // Error code will be authentication_required if authentication is needed
                                        Log::critical('Payment Process Error Code:' . $e->getError()->code);
                                        Log::critical('Payment Process Error Message:' . $e->getError()->message);

                                        // Send an email to muval support about payment error
                                        $details = ['view'    => "mails.payment_error_accept",
                                                    'data'    => [
                                                        'errorCode'    => $e->getCode(),
                                                        'errorMessage' => $e->getMessage(),
                                                        'move' => $move
                                                    ],
                                                    'to'      => $muvalSupportEmail,
                                                    'subject' => 'Payment Error on Accept job'];
                                        SendEmail::dispatchSync($details);

                                        $payment_intent_id = $e->getError()->payment_intent->id;
                                        $payment_intent    = PaymentIntent::retrieve($payment_intent_id);

                                        return 'payment_error';
                                    }
                                }
                            }

                        }


                    }
                }

            }
        }
    }


}
