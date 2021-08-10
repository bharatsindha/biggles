@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.truck')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.trucks'),
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
                                    <h5 class="mb-75">{{ trans('truck::truck.company_name') }}:</h5>
                                    <p class="card-text">{{ $truck->company->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('truck::truck.truck_name') }}:</h5>
                                    <p class="card-text">{{ $truck->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('truck::truck.description') }}:</h5>
                                    <p class="card-text">{{ $truck->description }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('truck::truck.capacity') }}:</h5>
                                    <p class="card-text">{{ $truck->capacity }}</p>
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
