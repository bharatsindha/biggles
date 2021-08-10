@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.depot')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View depot', 'actionEdit' => route($moduleName.'.edit', $depot->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">
                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">

                        <div class="kt-portlet__body">

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.depot_name') }}:</label>
                                    <span class="form-text text-muted">
                                        {{ $depot->name }}
                                    </span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.address') }}:</label>
                                    <span class="form-text text-muted">{{ $depot->addr }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.latitude') }}:</label>
                                    <span class="form-text text-muted">{{ $depot->lat }}</span>

                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.longitude') }}:</label>
                                    <span class="form-text text-muted">{{ $depot->lng }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('depot::depot.city') }}:</label>
                                    <span class="form-text text-muted">{{ $depot->city }}</span>

                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('depot::depot.postcode') }}:</label>
                                    <span class="form-text text-muted">{{ $depot->postcode }}</span>
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

@section('scripts')

@stop
