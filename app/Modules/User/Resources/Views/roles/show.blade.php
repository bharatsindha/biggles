@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.roles')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View role', 'actionEdit' => route($moduleName.'.edit', $role->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet">

                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.role_name') }}:</label>
                                    <span class="form-text text-muted">
                                        {{ $role->name }}
                                    </span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.description') }}:</label>
                                    <span class="form-text text-muted">{{ $role->description }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.permissions') }}:</label> <br/>
                                    @if(isset($role->permissions) && $role->permissions->count() > 0 )
                                        @foreach($role->permissions as $permission)
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                {!!  Form::checkbox('permissions[]', $permission->id, true, ['id' => 'permission_'.$permission->id,'class' => 'form-control', 'disabled' => 'disabled' ]) !!} {{ $permission->name  }}
                                                <span></span>
                                            </label>
                                            <br/>
                                        @endforeach
                                    @else
                                        <label>{{ trans('common.no_permissions') }}</label>
                                    @endif
                                </div>
                            </div>

                        </div>

                    @include('layouts.modules.form-footer')

                    <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

