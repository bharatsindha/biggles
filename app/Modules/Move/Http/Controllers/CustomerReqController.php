<?php

namespace App\Modules\Move\Http\Controllers;

use App\Http\Requests\StoreCompany;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Move;
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

class CustomerReqController extends Controller
{

    /**
     * Class Constructor.
     *
     * @return string
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param moveId
     * @return RedirectResponse|Redirector
     */
    public function index(Request $request, $moveId)
    {
        try {
            $moveId = decrypt($moveId);
            $moveCount = Move::where('id', $moveId)->count();
            if ($moveCount > 0){
                $redirectUrl = URL::to('find-space/form/lead/verify-customer/'.$moveId);
                return redirect($redirectUrl);
            }else{
                return abort(404);
            }
        } catch (Exception $e){
            return abort(404);
        }
    }

    public function verify($moveId, $email){

        try {
            $moveId = decrypt($moveId);
            $email = decrypt($email);
            $move = (new Move())->find($moveId);
            if ($move){
                $move->is_verify = 2;
                $move->save();

                $redirectUrl = URL::to('find-space/form/lead/verified/'.$moveId);
                return redirect($redirectUrl);
            }else{
                return abort(404);
            }
        } catch (Exception $e){
            return abort(404);
        }

    }

}
