@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.staff')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View staff', 'actionEdit' => route($moduleName.'.edit', $user->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet user_view">

                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.name') }}:</label>
                                    <span class="form-text text-muted">
                                        {{ $user->name }}
                                    </span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.email') }}:</label>
                                    <span class="form-text text-muted">{{ $user->email }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.access_level') }}:</label>
                                    {{ $accessLevels[$user->access_level] }}
                                </div>


                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.role') }}:</label>
                                    @if(!is_null($user->role_id))
                                        {{ $user->role->name  }}
                                    @else
                                        N/A
                                    @endif
                                </div>

                                @if($user->access_level == 1)
                                    <div class="col-lg-6">
                                        <div id="div-company" class="kt-hide">
                                            <label class="">{{ trans('common.company') }}:</label>
                                            {{ $user->company->name }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.avatar') }}:</label>
                                    <span class="form-text text-muted">
                                        @if(!is_null($user->avatar))
                                            <img src="{{  asset('storage/'.$user->avatar) }}" class="file-preview-image"
                                                 title="avatar">
                                        @endif
                                    </span>
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

