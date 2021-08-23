@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.ancillaryservice')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.ancillaries'),
        'subTitle' => trans('common.view_details'),
        'moduleLink' => route($moduleName.'.index')
    ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-ancillary__custom"
         id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.details') }}</h3>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.type') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->type }}</p>
                                        </div>
                                        <div class="col-lg-4 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.created_by') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->created_by }}</p>
                                        </div>
                                        <div class="col-lg-4 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.created_at') }}</h5>
                                            <p class="card-text">{{ !is_null($ancillaryservice->created_at) && !empty($ancillaryservice->created_at) ? Carbon\Carbon::parse($ancillaryservice->created_at)->format('d M Y - H:i') : '' }}</p>
                                        </div>
                                    </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <!--begin::Form-->
                            <form class="form">
                                <div class="kt-portlet__head border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.insurance') }}</h3>
                                    </div>
                                </div>
                                    <div class="form-group row ">
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.premium') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->premium }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.basis') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->basis }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.add_ons') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->add_ons }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.valued_inventory') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->valued_inventory }}</p>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.packing_materials') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group row mb-1">
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.boxes') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->boxes }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.large_boxes') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->large_boxes }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.small_boxes') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->small_boxes }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.paper') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->paper }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.tape') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->tape }}</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.car_transport') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">

                                    <div class="form-group row mb-1">
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.pickup_toggle') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->pickup_toggle }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.pickup_depot') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->pickup_depot }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.delivery_toggle') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->delivery_toggle }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.delivery_depot') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->delivery_depot }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.car_rego') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->car_rego }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.car_make') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->car_make }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.car_model') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->car_model }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.car_type') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->car_type }}</p>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.cleaning') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.cleaning_options') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->cleaning_options }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.carpet_area') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->carpet_area }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.curtains') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->curtains }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('ancillaryservice::ancillary.blinds') }}</h5>
                                            <p class="card-text">{{ $ancillaryservice->blinds }}</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                </div>
                @include('layouts.modules.form-footer')
                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop
