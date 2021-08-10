<?php

namespace App\Modules\Company\Http\Controllers;

use App\Http\Requests\StoreCompany;
use App\Models\Company;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use URL;

class CompanyReqController extends Controller
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
        return "request_company";
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
        return View('company::request.index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Register the company and save the storage into resources
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|max:255|unique:companies'
        ]);

        # Save the company details
        $company             = new Company($request->all());
        $company->created_by = 1;
        $company->save();

        # Save the user details
        $user               = new User($request->all());
        $user->name         = $request->user_name;
        $user->password     = bcrypt('password');
        $user->access_level = 1;
        $user->role_id      = 1;
        $user->company_id   = $company->id;
        $user->save();

        Session::flash('success', trans('common.success_company_request'));
        return redirect('register-company');
    }
}
