@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.customer')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [ 
    'moduleTitle' => trans('common.customer'),
    'subTitle' => trans('common.view_details'),
    'moduleLink' => route($moduleName.'.index') ])
@stop

@section('content')
    <!-- Page content -->

{{-- <div class="kt-widget__head">
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
</div> --}}
                                   


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
                                    <h5 class="mb-75">{{ trans('common.name') }}:</h5>
                                    <p class="card-text">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.email') }}:</h5>
                                    <p class="card-text">{{ $customer->email }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.phone') }}:</h5>
                                    <p class="card-text">{{ $customer->phone }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.strip_id') }}:</h5>
                                    <p class="card-text"> {{ $customer->strip_id }}</p>
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