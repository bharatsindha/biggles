@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.depot')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.depot'),
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
                                    <h5 class="mb-75">{{ trans('common.depot_name') }}:</h5>
                                    <p class="card-text">{{ $depot->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.address') }}:</h5>
                                    <p class="card-text">{{ $depot->addr }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.latitude') }}:</h5>
                                    <p class="card-text">{{ $depot->lat }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.longitude') }}:</h5>
                                    <p class="card-text">{{ $depot->lng }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('depot::depot.city') }}:</h5>
                                    <p class="card-text">{{ $depot->city }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('depot::depot.postcode') }}:</h5>
                                    <p class="card-text">{{ $depot->postcode }}</p>
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