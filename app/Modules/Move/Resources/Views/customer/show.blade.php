@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.customer')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View customer', 'actionEdit' => route($moduleName.'.edit', $customer->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_view" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">

                <div class="col-lg-8">
                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">
                        <!--begin::Form-->
                        <form class="kt-form kt-form--label-right">

                            <div class="kt-portlet__body">

                                <div class="kt-widget kt-widget--user-profile-1">
                                    <div class="kt-widget__head">
                                        <div class="kt-widget__media">
                                            <span
                                                class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bolder"
                                                style="height: 70px;width: 70px;font-size: 2rem;">{{ $data['firstLetter'] }}</span>
                                        </div>
                                        <div class="kt-widget__content">
                                            <div class="kt-widget__section">
                                                <a href="#" class="kt-widget__username">{{ $customer->name }}<i
                                                        class="flaticon2-correct kt-font-success"></i></a>
                                                <span class="kt-widget__subtitle">customer</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget__body">
                                        <div class="kt-widget__content">
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label>{{ trans('common.name') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                                    </span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('common.email') }}:</label>
                                                    <span class="form-text text-muted">{{ $customer->email }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('common.phone') }}:</label>
                                                    <span class="form-text text-muted">{{ $customer->phone }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>{{ trans('common.strip_id') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $customer->strip_id }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </form>
                        <!--end::Form-->
                        @include('layouts.modules.form-footer')
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
