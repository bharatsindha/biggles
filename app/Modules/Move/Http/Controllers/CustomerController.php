<?php

namespace App\Modules\Move\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\StoreCustomer;
use App\Http\Requests\StoreMove;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Move;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Stripe\Stripe;

class CustomerController extends Controller
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
            // Get the companies for global search matched with keyword
            if (isset($request->keyword) && !empty($request->keyword)){
                return (new Customer())->getSearchResults($request->keyword);
            }else{
                // Get the customer listing
                return Customer::getCustomer();
            }
        }

        General::log('SL001001', ['action_module' => $this->moduleName]);

        return View('move::customer.index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('move::customer.createOrUpdate')->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomer $request
     * @return RedirectResponse|Redirector
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function store(StoreCustomer $request)
    {
        // Save the customer into storage
        $customer = new Customer($request->all());
        $customer->save();

        // create or update stipe details
        Customer::createStripeCustomer($customer);

        Session::flash('success', trans(
            'common.success_add_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL001002', [
            'action_module' => $this->moduleName,
            'parent_id'     => $customer->id,
            'event_data'    => ['name' => $customer->first_name, 'id' => $customer->id]
        ]);

        return redirect('customer');
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return Factory|View
     */
    public function show(Customer $customer)
    {
        General::log('SL001005', [
            'action_module' => $this->moduleName,
            'parent_id'     => $customer->id,
            'event_data'    => ['name' => $customer->first_name, 'id' => $customer->id]
        ]);

        $data['firstLetter'] = General::getFirstLetterFromName($customer->first_name . ' '.$customer->last_name);

        return View('move::customer.show', compact('customer', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return Factory|View
     */
    public function edit(Customer $customer)
    {
        $stripeIntent = null;
        $stripePublicKey = null;

        // Setup the stripe intent for the customer by stripe id
        $stripeIntent = Customer::getSetupIntent($customer->stripe_id);
        $stripePublicKey = env('STRIPE_KEY');

        return View('move::customer.createOrUpdate', compact('customer','stripeIntent','stripePublicKey'))
                ->with('moduleName', $this->moduleName);
    }

    /**
     * update the specified resource in storage.
     *
     * @param StoreCustomer $request
     * @param Customer $customer
     * @return RedirectResponse|Redirector
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function update(StoreCustomer $request, Customer $customer)
    {
        $customer->fill($request->all());
        $customer->save();

        // create or update stipe details
        Customer::createStripeCustomer($customer);

        Session::flash('success', trans(
            'common.success_update_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL001003', [
            'action_module' => $this->moduleName,
            'parent_id'     => $customer->id,
            'event_data'    => ['name' => $customer->first_name, 'id' => $customer->id]
        ]);

        return redirect('customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Customer $customer)
    {
        General::log('SL001004', [
            'action_module' => $this->moduleName,
            'parent_id'     => $customer->id,
            'event_data'    => ['name' => $customer->first_name]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $customer->delete();

        return redirect('customer');
    }


    /**
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "customer";
    }
}
