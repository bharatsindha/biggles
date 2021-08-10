<?php

namespace App\Modules\Move\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\StoreDeal;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Move;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DealController extends Controller
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
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get the deal listing
            return Deal::getDeals($request);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $moveId
     * @return Factory|View
     */
    public function create($moveId)
    {
        // Get the move details by id
        $move = Move::getMoveById($moveId);
        $data['companyOptions']      = Company::getCompanyOptions();

        return View('move::deal.createOrUpdate', compact('move', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDeal $request
     * @return RedirectResponse
     */
    public function store(StoreDeal $request)
    {
        // Save the deal into storage
        $deal             = new Deal($request->all());
        $deal->created_by = Auth::user()->id;
        $deal->save();

        Session::flash('success', trans(
            'common.success_add_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL000802', [
            'action_module' => $this->moduleName,
            'parent_id'     => $deal->id,
            'event_data'    => ['name' => $deal->total_price, 'id' => $deal->id]
        ]);

        return redirect()->route('move.job_details', $deal->move_id);
    }

    /**
     * Display the specified resource.
     *
     * @param Deal $deal
     * @return Factory|View
     */
    public function show(Deal $deal)
    {
        $data['companyName'] = Company::getCompanyNameById($deal->company_id);
        $data['createdBy']   = User::getUserNameById($deal->created_by);

        General::log('SL000805', [
            'action_module' => $this->moduleName,
            'parent_id'     => $deal->id,
            'event_data'    => ['name' => $deal->total_price, 'id' => $deal->id]
        ]);

        return View('move::deal.show', compact('deal', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Deal $deal
     * @return Factory|View
     */
    public function edit(Deal $deal)
    {
        $data['companyOptions']      = Company::getCompanyOptions();

        return View('move::deal.createOrUpdate', compact('deal', 'data'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDeal $request
     * @param Deal $deal
     * @return RedirectResponse|Redirector
     */
    public function update(StoreDeal $request, Deal $deal)
    {
        // Save the deal details into storage
        $deal->fill($request->all());
        $deal->save();

        Session::flash('success', trans(
            'common.success_update_msg',
            array('module_name' => ucfirst($this->moduleName))
        ));

        General::log('SL000803', [
            'action_module' => $this->moduleName,
            'parent_id'     => $deal->id,
            'event_data'    => ['name' => $deal->total_price, 'id' => $deal->id]
        ]);

        return redirect()->route('move.show', $deal->move_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Deal $deal
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Deal $deal)
    {
        General::log('SL000804', [
            'action_module' => $this->moduleName,
            'parent_id'     => $deal->id,
            'event_data'    => ['name' => $deal->total_price]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));
        $deal->delete();

        return redirect()->route('move.show', $deal->move_id);
    }

    /**
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "deal";
    }
}
