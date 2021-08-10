@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View local', 'actionEdit' => route($moduleName.'.edit', $local->id) ])
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
                                        <label class="">{{ trans('depot::depot.name') }}:</label>
                                        <span class="form-text text-muted">{{ $local->depot->name  }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{ trans('common.price_for_two_movers_show') }}:</label>
                                        <span class="form-text text-muted">${{ sbNumberFormat($local->price_per) }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('common.travel_radius_show') }}:</label>
                                        <span class="form-text text-muted">{{ $local->radius }} m</span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.extra_person_price_show') }}:</label>
                                        <span class="form-text text-muted">${{ sbNumberFormat($local->extra_person_price) }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.created_by') }}:</label>
                                        <span class="form-text text-muted">{{ $data['createdBy'] }}</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.created_at') }}:</label>
                                        <span class="form-text text-muted">{{ !is_null($local->created_at) && !empty($local->created_at) ? Carbon\Carbon::parse($local->created_at)->format('d M Y - H:i') : '' }}</span>
                                    </div>
                                </div>

                                <div class="days_main_section">
                                    <h4>Apply on</h4>
                                    <div class="day_section d-flex">
                                        @foreach($weekDaysArr as $weekDay)
                                            <div class="day d-flex align-items-center">
                                                <span class="local_circle {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'active' : '' }}"><input type="checkbox" name="weekdays[]" value="{{ $weekDay }}" {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'checked' : '' }} disabled> <img src="{{ asset('assets/media/right_arrow.svg') }}"></span> <span>{{ ucfirst($weekDay) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            @include('layouts.modules.form-footer')

                        </form>
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
