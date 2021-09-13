<?php

namespace App\Modules\User\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Facades\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreRole;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
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
        return "role";
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get the roles listing
            return Role::getRoles();
        }

        General::log('SL000901', ['action_module' => $this->moduleName]);

        return View('user::roles/index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the role permission
        $permissions = Permission::all();

        return View('user::roles/createOrUpdate', compact('permissions'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreRole $request)
    {
        // Save the role into the storage
        $role             = new Role($request->all());
        $role->created_by = Auth::user()->id;
        $role->save();

        if ($role) {
            // Assign the permissions to the role into the storage
            $role->permissions()->attach($request->get('permissions'));
        }
        Session::flash('success',
            trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        General::log('SL000902', [
            'action_module' => $this->moduleName,
            'parent_id'     => $role->id,
            'event_data'    => ['name' => $role->name, 'id' => $role->id]
        ]);

        return redirect('role');
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return Response
     */
    public function show(Role $role)
    {
        General::log('SL000905', [
            'action_module' => $this->moduleName,
            'parent_id'     => $role->id,
            'event_data'    => ['name' => $role->name, 'id' => $role->id]
        ]);

        return View('user::roles/show', compact('role'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return Response
     */
    public function edit(Role $role)
    {
        // Get all permission
        $permissions = Permission::all();

        return View('user::roles/createOrUpdate', compact('permissions', 'role'))
            ->with('moduleName', $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return Response
     */
    public function update(StoreRole $request, Role $role)
    {
        // Save role details into storage
        $role->fill($request->all());
        $role->save();
        if ($role) {
            // Assign he permission to the role into storage
            $role->permissions()->detach();
            $role->permissions()->attach($request->get('permissions'));
        }

        General::log('SL000903', [
            'action_module' => $this->moduleName,
            'parent_id'     => $role->id,
            'event_data'    => ['name' => $role->name, 'id' => $role->id]
        ]);

        Session::flash('success',
            trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));

        return redirect('role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        General::log('SL000904', [
            'action_module' => $this->moduleName,
            'parent_id'     => $role->id,
            'event_data'    => ['name' => $role->name, 'id' => $role->id]
        ]);

        Session::flash('success',
            trans('common.success_delete_msg', array('module_name' => ucfirst($this->moduleName))));

        $role->permissions()->detach();
        $role->delete();

        return redirect('role');
    }


}
