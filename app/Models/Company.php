<?php

namespace App\Models;

use App\Facades\General;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Config;

class Company extends Crud
{
    use SoftDeletes;

    protected $fillable = ['name',
        'email',
        'phone',
        'hosted_phone',
        'address',
        'abn',
        'about_us',
        'summary',
        'website',
        'public_liability',
        'terms_conditions',
        'stripe_id',
        'stripe_auth_credentials',
        'bank_number',
        'bank_bsb',
        'created_by'];


    /**
     * Get the phone record associated with the user.
     */
    public function interState()
    {
        return $this->hasOne('App\Models\InterstateConfiguration', 'company_id')->withDefault();
    }

    /**
     * Prepare join query
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        // get the object of company modal
        return self::getCompanyModal();
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
        $options['match'] = [
            "companies.name" => $keyword,
            "companies.email" => $keyword,
            "companies.phone" => $keyword,
            "companies.address" => $keyword,
        ];

        // Generate the query from global search
        $model = $this->globalSearch($options);

        if (isset($option["count"]) && $option["count"]){
            // Get the count of companies matched with the search keyword
            $result = $model->count();
        }else{
            // Get the datatable of companies data
            $result = self::getCompaniesDatatable($model);
        }

        return $result;
    }

    /**
     * Get all company lists
     *
     * @param null $request
     * @return JsonResponse
     * @throws Exception
     */
    static public function getCompanies($request=null)
    {
        // Get the object of company model
        $model = self::getCompanyModal($request);

        // return the datatable of companies list
        return self::getCompaniesDatatable($model, $request);
    }

    /**
     * Generate the datatable for the companies
     *
     * @param $model
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public static function getCompaniesDatatable($model, $request=null){
        $user  = Auth::user();

        // return datatable
        return Datatables::eloquent($model)
            // check the stripe is connected or not
            ->addColumn('is_connected', function ($company) use ($user) {
                if (empty($company->stripe_auth_credentials)) {
                    return '<span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill status_bg">Not Connected</span>';
                } else {
                    return '<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Connected</span>';
                }
            })
            // company status
            ->addColumn('status', function ($q) {
                return View('layouts.actions.company-status')->with('model', $q)
                    ->with('route', 'company.status');
            })
            // To make the connection with stripe
            ->addColumn('connect_now', function ($company) use ($user) {
                if ( empty($company->stripe_auth_credentials)) {
                    $url = self::stripeConnectURL($company);
                    return View('layouts.actions.stripe-connect')->with('model', $company)->with('route', $url);
                } else {
                    return View('layouts.actions.stripe-disconnect')->with('model', $company)->with('route',
                            'company.stripe-disconnect');
                }
            })
            // Options to take action for company lists
            ->addColumn('action', function ($company) use ($user, $request) {
                $action = '<div class="d-flex align-items-center col-actions">';
                // If approval is pending
                if (isset($request) && isset($request->approvalPending) && $request->approvalPending == true){
                    $action .= View('layouts.actions.company-status')->with('model', $company)
                        ->with('route', 'company.status')->with('action', true);
                    $action .= View('layouts.actions.view')->with('model', $company)->with('route', 'company.show');
                }else{
                   
                    $action .= View('layouts.actions.view')->with('model', $company)->with('route', 'company.show');
                    $action .= View('layouts.actions.edit')->with('model', $company)->with('route', 'company.edit');

                    if ($user->access_level == 0) {
                        $action .= View('layouts.actions.delete')->with('model', $company)->with('route', 'company.destroy');
                    }
                }

                return $action .= '</div>';
            })
            ->rawColumns(['action', 'is_connected', 'connect_now'])
            ->make(true);
    }

    /**
     * Prepare company model query
     *
     * @param null $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getCompanyModal($request=null)
    {
        $model = self::query();
        $user  = Auth::user();

        // if it is company agent
        if (General::isCompanyAgent()) {
            $model = $model->where('id', $user->company_id);
        }

        // If approval is pending for company
        if (isset($request) && isset($request->approvalPending) && $request->approvalPending == true){
            $model = $model->where('companies.flag', 0);
        }

        return $model;
    }

    /**
     * return Company Name By Company Id.
     *
     * @param $companyId
     * @return string
     */
    public static function getCompanyNameById($companyId)
    {
        try {
            return self::where('id', $companyId)->pluck('name')->first();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @param $company
     * @return string
     */
    public static function stripeConnectURL($company)
    {
        if ($company) {
            $encryptedId = Crypt::encryptString($company->id);
            return Config::get('muval.STRIPE_AUTHORIZE_URL') . '?response_type=' . Config::get('muval.STRIPE_RESPONSE_TYPE') . '&scope=' . Config::get('muval.STRIPE_RESPONSE_SCOPE') . '&client_id=' . Config::get('muval.STRIPE_CLIENT_ID') . '&redirect_uri=' . route('company.stripe-connect') . '&state=' . $encryptedId;
        }
    }

    /**
     * Store Stripe access token into the database
     *
     * @param Request $request
     * @param $oAuthResponse
     * @return bool
     */
    public static function storeStripeToken($request, $oAuthResponse)
    {
        // Store the access details into database
        if ($request->has('state')) {
            // Get the encrypted Id
            $encryptedId = $request->get('state');
            $companyId   = Crypt::decryptString($encryptedId);
            if ($companyId > 0) {
                // Get the company details
                $company = self::find($companyId);
                if ($company) {
                    // Store access token into the database
                    $company->stripe_auth_credentials = $oAuthResponse->toJSON();
                    $company->stripe_id  = $oAuthResponse["stripe_user_id"];
                    $company->onboarding_flag = 1;
                    $company->save();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Return customer details for options
     *
     * @return array|string
     */
    public static function getCompanyOptions()
    {
        try {
            // Get the company lists
            $companyResults = self::getCompanyModal()->get();
            $optionValues   = [];
            // Make the array of listing format
            foreach ($companyResults as $companyResult) {
                $optionValues[$companyResult->id] = $companyResult->name;
            }

            return $optionValues;

        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Return company details
     *
     * @param $companyId
     * @return mixed
     */
    public static function getCompanyDetails($companyId)
    {
        return self::where('id', $companyId)->get()->first();
    }
}
