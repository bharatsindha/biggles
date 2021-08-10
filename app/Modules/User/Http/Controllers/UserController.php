<?php

namespace App\Modules\User\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Models\Company;
use App\Models\Attachment;
use App\Models\Role;
use App\Facades\General;
use App\Rules\MatchOldPassword;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Image;
use Storage;
use URL;

class UserController extends Controller
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

            if (isset($request->keyword) && !empty($request->keyword)){
                // Get the moves for global search matched with keyword
                return (new \App\Models\User())->getSearchResults($request->keyword);
            }else{
                // Get the users listing
                return User::getUsers();
            }

        }

        General::log('SL000201', ['action_module' => $this->moduleName]);

        return View('user::index', ['moduleName' => $this->moduleName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get the roles
        $roles        = Role::get()->pluck('name', 'id')->toArray();
        // Get the companies
        $companies    = Company::get()->pluck('name', 'id')->toArray();
        // Get the access level
        $accessLevels = General::getAccessLevels();
        return View('user::createOrUpdate', compact('roles', 'accessLevels', 'companies'))->with('moduleName',
            $this->moduleName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUser $request
     * @return Response
     */
    public function store(StoreUser $request)
    {
        $user = new User($request->all());
        // Upload the avatar on the server
        if ($request->hasFile("avatar")) {
            $user->avatar = $this->uploadAvatar($request);
        }
        $user->save();

        Session::flash('success',
            trans('common.success_add_msg', array('module_name' => ucfirst($this->moduleName))));

        General::log('SL000202', [
            'action_module' => $this->moduleName,
            'parent_id'     => $user->id,
            'event_data'    => ['name' => $user->name, 'id' => $user->id]
        ]);

        return redirect('user');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        // Get the access level
        $accessLevels = General::getAccessLevels();

        General::log('SL000206', [
            'action_module' => $this->moduleName,
            'parent_id'     => $user->id,
            'event_data'    => ['name' => $user->name, 'id' => $user->id]
        ]);

        return View('user::show', compact('user', 'accessLevels'))->with('moduleName', $this->moduleName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        // Get the companies
        $companies    = Company::get()->pluck('name', 'id')->toArray();
        // Get the roles
        $roles        = Role::get()->pluck('name', 'id')->toArray();
        // Get the access level
        $accessLevels = General::getAccessLevels();
        return View('user::createOrUpdate', compact('user', 'roles', 'accessLevels', 'companies'))->with('moduleName',
            $this->moduleName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(StoreUser $request, User $user)
    {
        // Save the users details into storage
        $user->fill($request->all());
        if ($request->hasFile("avatar")) {
            // Upload avatar
            $user->avatar = $this->uploadAvatar($request);
        } else {
            if ($request->get("remove_avatar") == 1) {
                $user->avatar = null;
            }
        }
        if (!empty($request->input('password_confirmation'))) {
            $user->password = Hash::make($request->get('password_confirmation'));
        } else {
            unset($user->password);
        }
        $user->save();

        General::log('SL000203', [
            'action_module' => $this->moduleName,
            'parent_id'     => $user->id,
            'event_data'    => ['name' => $user->name, 'id' => $user->id]
        ]);

        if (strpos(URL::previous(), 'account') !== false) {
            Session::flash('success', trans('common.success_update_msg', array('module_name' => 'Email')));
            return redirect(route('user.account'));
        } elseif (strpos(URL::previous(), 'profile') !== false) {
            Session::flash('success', trans('common.success_update_msg', array('module_name' => 'Profile')));
            return redirect(route('profile'));
        } else {
            Session::flash('success',
                trans('common.success_update_msg', array('module_name' => ucfirst($this->moduleName))));
            return redirect('user');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(User $user)
    {
        General::log('SL000204', [
            'action_module' => $this->moduleName,
            'parent_id'     => $user->id,
            'event_data'    => ['name' => $user->name]
        ]);

        $user->delete();

        return redirect('user');
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function uploadAvatar(Request $request)
    {
        try {
            $path  = $request->file('avatar');
            $file  = $request->file('avatar');
            $path  = $file->hashName('avatars');
            $image = Image::make($file)->fit(150);
            Storage::disk('public')->put($path, (string)$image->encode());
            return $path;
        } catch (Exception $exception) {

        }
    }

    /**
     * View Profile
     * @return array|Factory|View|mixed
     */
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return View('user::profile', compact('user'));
    }

    /**
     * Get Module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return "user";
    }


    /**
     * Change the password of user
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function changePassword(Request $request)
    {

        $request->validate([
            'current_password'     => ['required', new MatchOldPassword()],
            'new_password'         => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        Session::flash('success', trans('common.success_change_pass', array('module_name' => 'Password')));
        return redirect(route('user.account'));
    }

    /**
     * View user account
     *
     * @param Request $request
     * @return Factory|View
     */
    public function account(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            return View('user::myAccount', compact('user'))
                ->with('moduleName', $this->moduleName);
        }

    }

    /**
     * upload attachment
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fileStore(Request $request)
    {

        $file = $request->file('file');
        $path = $file->hashName('attachment');
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $companyAttach                = new Attachment();
        $companyAttach->file_name     = $path;
        $companyAttach->original_name = $file->getClientOriginalName();
        $companyAttach->file_type     = $file->getClientMimeType();
        $companyAttach->file_size     = $file->getSize();
        $companyAttach->user_id       = Auth::user()->id;
        $companyAttach->save();

        return response()->json(['success' => $path]);
    }

    /**
     * Delete uploaded file from dropzone
     *
     * @param Request $request
     * @return mixed
     */
    public function fileDestroy(Request $request)
    {
        $filename      = $request->get('filename');
        $companyAttach = Attachment::where('original_name', $filename)
            ->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();

        $path = public_path() . '/storage/' . ($companyAttach->file_name) ?? '';
        if (file_exists($path)) {
            unlink($path);
        }
        $companyAttach->delete();

        return $filename;
    }

    /**
     * Delete attachment
     *
     * @param Request $request
     * @param $fileId
     * @return bool
     */
    public function attachDestroy(Request $request, $fileId)
    {
        $companyAttach = Attachment::find($fileId);
        $companyAttach->delete();

        return response()->json(['success' => true]);
    }
}
