<?php

namespace App\Modules\Company\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\StoreCompany;
use App\Models\Company;
use App\Models\Attachment;
use App\Models\InterstateConfiguration;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Exception\OAuth\OAuthErrorException;
use Stripe\OAuth;
use Stripe\Stripe;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Image;
use Storage;
use URL;
use Illuminate\Support\Facades\Config;

class CompanyController extends Controller
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
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "company";
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
            if (isset($request->keyword) && !empty($request->keyword)){
                // Get the companies for global search matched with keyword
                return (new Company())->getSearchResults($request->keyword);
            }else{
                // Get the company listing
                return Company::getCompanies($request);
            }
        }

        General::log('SL000301', ['action_module' => $this->moduleName]);

        return View('company::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('company::createOrUpdate')->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreCompany $request)
    {
        // Save the company details into storage
        $company             = new Company($request->all());
        $company->created_by = Auth::user()->id;
        $company->save();

        // Save the interstate details for the company
        $interState = new InterstateConfiguration();
        $interState->fill($request->all());
        $interState->company_id = $company->id;
        $interState->created_by = Auth::user()->id;
        $interState->save();

        Session::flash('success', trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        // User log
        General::log('SL000302', [
            'action_module' => $this->moduleName,
            'parent_id'     => $company->id,
            'event_data'    => ['name' => $company->name, 'id' => $company->id]
        ]);

        return redirect('company');
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @return Response
     */
    public function show(Company $company)
    {
        //interState
        General::log('SL000306',
            ['action_module' => $this->moduleName,
             'parent_id'     => $company->id,
             'event_data'    => ['name' => $company->name, 'id' => $company->id]]);

        return View('company::show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @return Response
     */
    public function edit(Company $company)
    {
        return View('company::createOrUpdate', compact('company'))->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function update(Request $request, Company $company)
    {
        // Update company details
        $company->fill($request->all());
        if ($request->hasFile("logo")) {
            $company->logo = $this->uploadCompanyLogo($request);
        }
        $company->save();

        // Add or update Interstate setting for the company
        if (isset($request->inter_state_id)) {
            $interState = InterstateConfiguration::find($request->inter_state_id);
            $insert     = false;
            if ($interState == null) {
                // Add new interstate setting if not exist
                $interState = new InterstateConfiguration();
                $insert     = true;
            }
            $interState->fill($request->all());
            $interState->company_id = $company->id;
            if ($insert == true) {
                $interState->created_by = Auth::user()->id;
            }
            $interState->storage_toggle = ($request->storage_toggle == 'on') ? 1 : 0;
            $interState->save();
        }


        General::log('SL000303',
            ['action_module' => $this->moduleName,
             'parent_id'     => $company->id,
             'event_data'    => ['name' => $company->name, 'id' => $company->id]]);


        if (strpos(URL::previous(), 'profile') !== false && !General::isSuperAdmin()) {
            Session::flash('success', trans('common.success_update_msg', array('module_name' => 'Company Profile')));
            return redirect(route('company.profile'));
        } elseif (strpos(URL::previous(), 'interstate') !== false && !General::isSuperAdmin()) {
            Session::flash('success', trans('common.success_update_msg', array('module_name' => 'Interstate setting')));
            return redirect(route('company.interstate'));
        } elseif (strpos(URL::previous(), 'company-setting') !== false && !General::isSuperAdmin()) {
            Session::flash('success', trans('common.success_update_msg', array('module_name' => 'Company setting')));
            return redirect(route('company.setting'));
        } else {
            Session::flash('success',
                trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));
            return redirect('company');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Company $company)
    {
        General::log('SL000304', [
            'action_module' => $this->moduleName,
            'parent_id'     => $company->id,
            'event_data'    => ['name' => $company->name]]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $company->delete();
        return redirect('company');
    }

    /**
     * Stripe OAuth Connect
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     * @throws OAuthErrorException
     */
    public function connectStripe(Request $request)
    {
        // Get the code from stripe
        if ($request->has('code')) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            // Get Access Tokens
            $response = OAuth::token([
                'grant_type' => 'authorization_code',
                'code'       => $request->get('code'),
            ]);
            
            // store the token into database
            $storeResponse = Company::storeStripeToken($request, $response);
            if ($storeResponse) {
                Session::flash('success', trans('common.stripe_connect'));
                return redirect('company-profile');
            }
        }

        Session::flash('error', trans('common.something_went_wrong'));
        return redirect('company-profile');
    }

    /**
     * @param Company $company
     * @return Application|RedirectResponse|Redirector
     * @throws OAuthErrorException
     */
    public function disconnectStripe(Company $company)
    {
        $stripeDetails = json_decode($company->stripe_auth_credentials, true);
        if ($stripeDetails && isset($stripeDetails['stripe_user_id'])) {
            $stripeUserId = $stripeDetails['stripe_user_id'];
            Stripe::setApiKey(env('STRIPE_SECRET'));
            // Get Access Tokens
            $response = OAuth::deauthorize([
                'client_id'      => Config::get('muval.STRIPE_CLIENT_ID'),
                'stripe_user_id' => $stripeUserId,
            ]);
            //update the token into database
            $company->stripe_auth_credentials = null;
            $company->save();
            Session::flash('success', trans('common.stripe_connect'));
        } else {
            Session::flash('error', trans('common.something_went_wrong'));
        }

        return redirect('company-profile');
    }

    /**
     * View Company Profile
     *
     * @param Request $request
     * @param int $companyId
     * @return Factory|View
     */
    public function profile(Request $request, $companyId = 0)
    {
        $company = null;
        $user    = Auth::user();
        if ($user) {
            if (!General::isSuperAdmin() && $user->company_id > 0) {
                $company = Company::find($user->company_id);
            }
        }
        if ($companyId > 0) {
            $company = Company::find($companyId);
        }
        if ($company !== null) {
            $url     = Company::stripeConnectURL($company);
            return View('company::profile', compact('company'))
                ->with('moduleName', $this->moduleName)
                 ->with('url', $url);
        }
    }

    /**
     * Company setting
     *
     * @param Request $request
     * @param int $companyId
     * @return Factory|View
     */
    public function companySetting(Request $request, $companyId=0)
    {
        $company = null;
        $user    = Auth::user();
        if ($user) {
            // If it is company agent
            if (!General::isSuperAdmin() && $user->company_id > 0) {
                $company = Company::find($user->company_id);
            }
        }
        if ($companyId > 0) {
            $company = Company::find($companyId);
        }

        if ($company !== null) {
            $url     = Company::stripeConnectURL($company);
            return View('company::companySetting', compact('company'))
                ->with('moduleName', $this->moduleName)
                ->with('url', $url);
        }
    }

    /**
     * View company setting
     *
     * @param Request $request
     * @param int $companyId
     * @return Factory|View
     */
    public function setting(Request $request, $companyId=0)
    {
        $company = null;
        $user    = Auth::user();
        if ($user) {
            // If it is company agent
            if (!General::isSuperAdmin() && $user->company_id > 0) {
                $company = Company::find($user->company_id);
            }
        }
        if ($companyId > 0) {
            $company = Company::find($companyId);
        }

        if ($company !== null) {
            $url     = Company::stripeConnectURL($company);
            return View('company::setting', compact('company'))
                ->with('moduleName', $this->moduleName)
                ->with('url', $url);
        }
    }

    /**
     * Manage the company status
     *
     * @param Company $company
     * @param Request $request
     * @return RedirectResponse
     */
    public function manageStatus(Company $company, Request $request)
    {
        $request->flag = trim($request->flag);
        // Active or Inactive company
        // 1=active, 0-Inactive
        $company->flag = ($request->flag == 0) ? 1 : 0;
        $company->save();

        $activityId = ($company->flag == 1) ? 'SL000308' : 'SL000309';
        General::log($activityId, [
            'action_module' => $this->moduleName,
            'parent_id'     => $company->id,
            'event_data'    => ['name' => $company->name, 'id' => $company->id]
        ]);

        return redirect()->back();
    }

    /**
     * Upload company logo
     *
     * @param Request $request
     * @return string
     */
    protected function uploadCompanyLogo(Request $request)
    {
        try {
            $path  = $request->file('logo');
            $file  = $request->file('logo');
            $path  = $file->hashName('logo');
            $image = Image::make($file);
            Storage::disk('public')->put($path, (string)$image->encode());
            return $path;
        } catch (Exception $exception) {

        }
    }

}
