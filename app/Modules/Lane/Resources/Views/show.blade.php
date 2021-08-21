@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.lane')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.lane'),
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
                                    <h5 class="mb-75">{{ trans('lane::lane.company_name') }}:</h5>
                                    <p class="card-text">{{ $lane->company->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.start_address') }}:</h5>
                                    <p class="card-text">{{ $lane->start_addr }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.start_latitude') }}:</h5>
                                    <p class="card-text">{{ $lane->start_lat }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.start_longitude') }}:</h5>
                                    <p class="card-text">{{ $lane->start_lng }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.start_city') }}:</h5>
                                    <p class="card-text">{{ $lane->start_city }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.start_postcode') }}:</h5>
                                    <p class="card-text">{{ $lane->start_postcode }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.end_address') }}:</h5>
                                    <p class="card-text">{{ $lane->start_addr }}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.end_latitude') }}:</h5>
                                    <p class="card-text">{{ $lane->end_lat }}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.end_lng') }}:</h5>
                                    <p class="card-text">{{ $lane->end_lng }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.end_city') }}:</h5>
                                    <p class="card-text">{{ $lane->end_city }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.end_postcode') }}:</h5>
                                    <p class="card-text">{{ $lane->end_postcode }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.transit_time') }}:</h5>
                                    <p class="card-text">{{ $lane->transit_time }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.pickup_notice') }}:</h5>
                                    <p class="card-text">{{ $lane->pickup_notice }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.price_per') }}:</h5>
                                    <p class="card-text">{{ $lane->price_per }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.min_price') }}:</h5>
                                    <p class="card-text">{{ $lane->min_price }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.capacity') }}:</h5>
                                    <p class="card-text">{{ $lane->capacity }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('lane::lane.transport') }}:</h5>
                                    <p class="card-text">{{ $lane->transport }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ about -->
                @include('layouts.modules.form-footer')
            </div>
        </div>
    </section>
    <!-- /page content -->
@stop