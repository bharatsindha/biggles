@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.lane')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View lane', 'actionEdit' => route('lane.edit', $lane->id) ])
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
                                    <label>{{ trans('lane::lane.company_name') }}:</label>
                                    <span class="form-text text-muted">
                                        {{ $lane->company->name }}
                                    </span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.start_address') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_addr }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.start_latitude') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_lat }}</span>

                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.start_longitude') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_lng }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.start_city') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_city }}</span>

                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.start_postcode') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_postcode }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.start_city') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_city }}</span>

                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.start_postcode') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->start_postcode }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.end_address') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->end_addr }}</span>
                                </div>

                                <div class="col-lg-3">
                                    <label>{{ trans('lane::lane.end_latitude') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->end_lat }}</span>

                                </div>
                                <div class="col-lg-3">
                                    <label class="">{{ trans('lane::lane.end_lng') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->end_lng }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.end_city') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->end_city }}</span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.end_postcode') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->end_postcode }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.transit_time') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->transit_time }}</span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.pickup_notice') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->pickup_notice }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.price_per') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->price_per }}</span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.min_price') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->min_price }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.capacity') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->capacity }}</span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="">{{ trans('lane::lane.transport') }}:</label>
                                    <span class="form-text text-muted">{{ $lane->transport }}</span>
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

