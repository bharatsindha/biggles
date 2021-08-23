@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.deal')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.deal'),
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
                                    <h5 class="mb-75">{{ trans('move::deal.company_name') }}:</h5>
                                    <p class="card-text">{{ $data['companyName'] }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('move::deal.total_price') }}:</h5>
                                    <p class="card-text">${{ sbNumberFormat($deal->total_price) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('move::deal.deposit') }}:</h5>
                                    <p class="card-text">${{ sbNumberFormat($deal->deposit) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('move::deal.fee') }}:</h5>
                                    <p class="card-text">${{ sbNumberFormat($deal->fee) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.created_by') }}:</h5>
                                    <p class="card-text">{{ $data['createdBy'] }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.created_at') }}:</h5>
                                    <p class="card-text">{{ !is_null($deal->created_at) && !empty($deal->created_at) ? Carbon\Carbon::parse($deal->created_at)->format('d M Y - H:i') : '' }}</p>
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
