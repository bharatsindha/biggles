@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.staff')]) @stop
@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.user'),
    'subTitle' => isset($truck) ? trans('common.edit'). ' '. trans('common.user') : trans('common.add').' '. trans('common.user') ,
    'moduleLink' => route($moduleName.'.index')
])
@stop

@section('content')
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
                            {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'post', 'enctype' => "multipart/form-data"]) }}
                            @method('PATCH')
                        @else
                            {{ Form::open(['route' => 'user.store', 'enctype' => "multipart/form-data"]) }}
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
@stop


