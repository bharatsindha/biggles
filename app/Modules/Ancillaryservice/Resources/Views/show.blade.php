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
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.type') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->type }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.created_by') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->created_by }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.created_at') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ !is_null($ancillaryservice->created_at) && !empty($ancillaryservice->created_at) ? Carbon\Carbon::parse($ancillaryservice->created_at)->format('d M Y - H:i') : '' }}</span>
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
                                        <h3 class="kt-portlet__head-title">{{ trans('ancillaryservice::ancillary.insurance') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.premium') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->premium }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.basis') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->basis }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.add_ons') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->add_ons }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.valued_inventory') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">${{ sbNumberFormat($ancillaryservice->valued_inventory) }}</span>
                                        </div>
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
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.boxes') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->boxes }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.large_boxes') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->large_boxes }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.small_boxes') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->small_boxes }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.paper') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->paper }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('ancillaryservice::ancillary.tape') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->tape }}</span>
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

                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.pickup_toggle') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->pickup_toggle }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.pickup_depot') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted"> {!! $ancillaryservice->pickup_depot !!}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.delivery_toggle') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->delivery_toggle }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.delivery_depot') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{!! $ancillaryservice->delivery_depot !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.car_rego') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->car_rego }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.car_make') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->car_make }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.car_model') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->car_model }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.car_type') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->car_type }}</span>
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
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.cleaning_options') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->cleaning_options }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.carpet_area') }}
                                                :</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->carpet_area }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.curtains') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ $ancillaryservice->curtains }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('ancillaryservice::ancillary.blinds') }}:</label>
                                            <span class="form-text text-muted">{{ $ancillaryservice->blinds }}</span>
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
