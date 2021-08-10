@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.deal')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View deal', 'actionEdit' => route($moduleName.'.edit', $deal->id) ])
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
                        <!--begin::Form-->
                        <form class="kt-form kt-form--label-right">
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('move::deal.company_name') }}:</label>
                                        <span class="form-text text-muted">{{ $data['companyName'] }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('move::deal.total_price') }}:</label>
                                        <span
                                            class="form-text text-muted">${{ sbNumberFormat($deal->total_price) }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('move::deal.deposit') }}:</label>
                                        <span class="form-text text-muted">${{ sbNumberFormat($deal->deposit) }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('move::deal.fee') }}:</label>
                                        <span class="form-text text-muted">${{ sbNumberFormat($deal->fee) }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.created_by') }}:</label>
                                        <span class="form-text text-muted">{{ $data['createdBy'] }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.created_at') }}:</label>
                                        <span
                                            class="form-text text-muted">{{ !is_null($deal->created_at) && !empty($deal->created_at) ? Carbon\Carbon::parse($deal->created_at)->format('d M Y - H:i') : '' }}</span>
                                    </div>
                                </div>
                            </div>

                            @include('layouts.modules.form-footer')

                        </form>
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
