@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.local'),
    'subTitle' => trans('common.view_details'),
    'moduleLink' => route($moduleName.'.index')
])
@stop

@section('content')
    <!-- Page content -->
    <section id="profile-info">
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <!-- about -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('depot::depot.name') }}:</h5>
                                    <p class="card-text">{{ $local->depot->name  }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.price_for_two_movers_show') }}:</h5>
                                    <p class="card-text">${{ sbNumberFormat($local->price_per) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.travel_radius_show') }}:</h5>
                                    <p class="card-text">{{ $local->radius }} m</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.extra_person_price_show') }}:</h5>
                                    <p class="card-text">${{ sbNumberFormat($local->extra_person_price) }}</p>
                                </div>
                            </div>
                               <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.created_by') }}:</h5>
                                    <p class="card-text">{{ $data['createdBy'] }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.created_at') }}:</h5>
                                    <p class="card-text">{{ !is_null($local->created_at) && !empty($local->created_at) ? Carbon\Carbon::parse($local->created_at)->format('d M Y - H:i') : '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="days_main_section mt-1">
                            <h4>Apply on</h4>
                            <div class="day_section d-flex demo-inline-spacing">
                                @foreach($weekDaysArr as $weekDay)
                                    <div class="day d-flex align-items-center form-check mt-0">
                                        <span class="local_circle {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'active' : '' }}"><input type="checkbox" class="form-check-input" name="weekdays[]" value="{{ $weekDay }}" {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'checked' : '' }} disabled></span> <span>{{ ucfirst($weekDay) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.modules.form-footer')
            </div>
        </div>
    </section>
    <!-- /page content -->
@stop
