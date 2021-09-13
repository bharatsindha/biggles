@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.staff')]) @stop
@section('pageHeader')
@include('layouts.modules.header', [
        'moduleTitle' => trans('common.user'),
        'subTitle' => isset($user) ? trans('common.edit'). ' '. trans('common.user') : trans('common.add').' '. trans('common.user') ,
        'moduleLink' => route($moduleName.'.index')
    ])
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: none;
            border-width: 5px 4px 0 4px;
            height: 9px;
            left: 50%;
            margin-left: -15px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
    </style>
@stop


{{-- @section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">
                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">
                    @php
                        $passwordRequired = true
                    @endphp
                    <!--begin::Form-->
                        @if(isset($user))
                            @php
                                $passwordRequired = false
                            @endphp
                           {{ Form::model($user, [
                            'route' => [$moduleName.'.update', $user->id],
                            'method' => 'patch',
                            'class' => 'form-validate'
                            ]) }}
                        @else
                        {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
                        @endif
                        @csrf
                        {!!  Form::hidden('remove_avatar', 0,['id' => 'remove_avatar']) !!}
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.name') }}:</label>
                                    {!!  Form::text('name', old('name'),['id' => 'name','class' => 'form-control', 'placeholder' => 'name','required' => 'required']) !!}
                                    @if($errors->has('name'))
                                        <div class="text text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.email') }}:</label>
                                    {!!  Form::text('email', old('email'),['id' => 'email','class' => 'form-control','placeholder' => 'Email','required' => 'required']) !!}
                                    @if($errors->has('email'))
                                        <div class="text text-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.password') }}:</label>
                                    {!!  Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder' => 'Password','required' => $passwordRequired]) !!}
                                    @if($errors->has('password'))
                                        <div class="text text-danger">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.confirm_assword') }}:</label>
                                    {!!  Form::password('password_confirmation', ['id' => 'password_confirmation','class' => 'form-control','placeholder' => 'Confirm Password','required' => $passwordRequired]) !!}
                                    @if($errors->has('password_confirmation'))
                                        <div class="text text-danger">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @php
                                use Illuminate\Support\Facades\Auth;$user = Auth::user()
                            @endphp
                            @if($user->access_level == 0)
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label>{{ trans('common.access_level') }}:</label>
                                        {!!  Form::select('access_level', $accessLevels,old('access_level'), ['id' => 'access_level','class' => 'form-control' , 'required' => true ]) !!}
                                        @if($errors->has('access_level'))
                                            <div class="text text-danger">
                                                {{ $errors->first('access_level') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">{{ trans('common.role') }}:</label>
                                        {!!  Form::select('role_id', $roles,old('role_id'), ['id' => 'role_id','class' => 'form-control','required' => true ]) !!}
                                        @if($errors->has('role_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('role_id') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-4">
                                        <div id="div-company" class="kt-hide">
                                            <label class="">{{ trans('common.company') }}:</label>
                                            {!!  Form::select('company_id', $companies, old('company_id'), ['id' => 'company_id','class' => 'form-control' ]) !!}
                                            @if($errors->has('company_id'))
                                                <div class="text text-danger">
                                                    {{ $errors->first('company_id') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-lg-6 new_user">
                                {!!  Form::file('avatar',['class' => 'file-input']) !!}
                                @push("scripts")
                                    <?php
                                    $options = [];
                                    if (isset($user) && !empty($user->avatar)) {
                                        // $options["initialPreview"][] = asset('storage/'.$user->avatar);
                                    }
                                    ?>

                                    <!-- {!!  \App\Facades\General::filejs($options) !!} -->
                                    @endpush
                                </div>
                            </div>


                        </div>
                    @include('layouts.forms.actions')

                    {{ Form::close() }}

                    <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4">
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop --}}



@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->

        @php
        $passwordRequired = true
    @endphp

       @if(isset($user))
            @php
                $passwordRequired = false
            @endphp
           {{ Form::model($user, [
            'route' => [$moduleName.'.update', $user->id],
            'method' => 'patch',
            'class' => 'form-validate'
            ]) }}
        @else
        {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
        @endif
        @csrf
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="name">
                                        {{ trans('common.name') }}
                                    </label>
                                    {!!  Form::text('name', old('name'),[
                                        'id' => 'name',
                                        'class' => 'form-control '. (($errors->has('name')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Truck Name'
                                        ]) !!}
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="email">
                                        {{ trans('common.email') }}
                                    </label>
                                    {!!  Form::text('email', old('email'),[
                                        'id' => 'email',
                                        'class' => 'form-control '. (($errors->has('email')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter truck email'
                                        ]) !!}
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="password">
                                        {{ trans('common.password') }}
                                    </label>
                                    {!!  Form::text('', old('password'),[
                                        'id' => 'password',
                                        'class' => 'form-control '. (($errors->has('password')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Password',
                                        'required' => $passwordRequired
                                        ]) !!}
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                         
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="confirmpossword">
                                        {{ trans('common.confirm_password') }}
                                    </label>
                                    {!!  Form::text('confirm_password', old('confirm_password'),[
                                        'id' => 'confirm_password',
                                        'class' => 'form-control '. (($errors->has('confirm_password')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter confirm_password',
                                        'required' => $passwordRequired
                                        ]) !!}
                                    @if($errors->has('confirm_password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('confirm_password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                              @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level   @endphp
                              @if($userAccess != 1)
                                 <div class="col-md-4">
                                 <div class="mb-1">
                                    
                                    <label class="form-label" for="accesslevel">
                                        {{ trans('common.access_level') }}<span class="required"> * </span>
                                    </label>
                                    {!!  Form::select('access_level',  $accessLevels, old('access_level'),[
                                        'id' => 'access_level',
                                        'class' => 'form-select select2 '.
                                        (($errors->has('access_level')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please select Access level',
                                        'required' => 'required'
                                        ]) !!}
                                    @if($errors->has('access_level'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('access_level') }}
                                        </div>
                                    @endif
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-1">
                                       
                                       <label class="form-label" for="role">
                                           {{ trans('common.role') }}<span class="required"> * </span>
                                       </label>
                                       {!!  Form::select('roles',  $roles   , old('roles'),[
                                           'id' => 'roles',
                                           'class' => 'form-select select2 '.
                                           (($errors->has('roles')) ? 'is-invalid' : ''),
                                           'placeholder' => 'Please select Role',
                                           'required' => 'required'
                                           ]) !!}
                                       @if($errors->has('roles'))
                                           <div class="invalid-feedback">
                                               {{ $errors->first('roles') }}
                                           </div>
                                       @endif
                                   </div>
                                   </div>

                                   <div class="col-md-4">
                                    <div class="mb-1">
                                       
                                       <label class="form-label" for="company_id">
                                           {{ trans('common.company') }}<span class="required"> * </span>
                                       </label>
                                       {!!  Form::select('company_id',  $companies, old('company_id'),[
                                           'id' => 'company_id',
                                           'class' => 'form-select select2 '.
                                           (($errors->has('company_id')) ? 'is-invalid' : ''),
                                           'placeholder' => 'Please select Company',
                                           'required' => 'required'
                                           ]) !!}
                                       @if($errors->has('company_id'))
                                           <div class="invalid-feedback">
                                               {{ $errors->first('company_id') }}
                                           </div>
                                       @endif
                                   </div>
                                   </div>
                              @endif
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-1">
                                     {!!  Form::file('avatar',[
                                    'id' => 'customFile1',
                                    'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.forms.actions')
        {{ Form::close() }}
    </section>
    <!-- /page content -->
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/plugins/uploaders/fileinput.min.js') }}"></script>
    <script>
        $(function () {
            $('.file-input').fileinput({
                removeLabel: '',
                uploadLabel: '',
                uploadClass: 'btn btn-default btn-icon d-none',
                // browseIcon: '<i class="la la-search"></i> ',
                uploadIcon: '<i class="la la-upload"></i> ',
                removeClass: 'btn btn-danger btn-icon',
                removeIcon: '<i class="la la-remove"></i> ',
                allowedFileTypes: ['image'],
                @if(isset($user->avatar) && !empty($user->avatar))
                initialPreview: '<img src="{{  asset('storage/'.$user->avatar) }}" class="file-preview-image" title="avatar">',
                @endif
                initialCaption: "No file selected"
            });

            $('.fileinput-remove').click(function (event) {

                event.preventDefault(event);
                $('#remove_avatar').val(1);
            });

            function hideCompanyDetails() {
                var accessLevel = $("#access_level").val();
                if (accessLevel == 1) {
                    $(document).find('#div-company').removeClass(' kt-hide ');
                } else {
                    $(document).find('#div-company').addClass(' kt-hide ');
                }
            }

            $(document).on('change', "#access_level", function (e) {
                hideCompanyDetails();
            });

            hideCompanyDetails();
        });
    </script>
@stop
