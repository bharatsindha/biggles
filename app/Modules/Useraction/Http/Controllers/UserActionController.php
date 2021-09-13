<?php

namespace App\Modules\Useraction\Http\Controllers;

use App\Facades\General;
use App\Models\UserAction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserActionController extends Controller
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
            // Get the user actions
            return UserAction::getUserActions($request);
        }

        General::log('SL000999', ['action_module' => $this->moduleName]);

        return View('useraction::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "useraction";
    }
}
