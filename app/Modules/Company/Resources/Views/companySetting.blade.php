@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.setting')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => trans('common.setting') ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Company setting</h3>
                            </div>
                        </div>

                        <!--begin::Form-->
                        @if(isset($company))
                            {{ Form::model($company, ['route' => ['company.update', $company->id], 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
                        @else
                            {{ Form::open(['route' => 'company.store', 'enctype' => "multipart/form-data"]) }}
                        @endif
                        @csrf

                        <div class="kt-portlet__body">

                            <div class="form-group row">

                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.stripe_id') }}:</label>
                                    {!! Form::text('stripe_id', old('stripe_id'),['id' => 'stripe_id','class' => 'form-control','placeholder' => 'Please enter stripe']) !!}
                                    @if($errors->has('stripe_id'))
                                        <div class="text text-danger">
                                            {{ $errors->first('stripe_id') }}
                                        </div>
                                    @endif
                                    <span>Stripe Id</span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="" style="display: block;">{{ trans('common.connect_disconnect') }}:</label>
                                    @if(empty($company->stripe_auth_credentials))
                                        <span class="form-text text-muted">
                                                @include('layouts.actions.stripe-connect', ['model' => $company, 'route' => $url])
                                            </span>
                                    @else
                                        <span class="form-text text-muted">
                                            @include('layouts.actions.stripe-disconnect', ['model' => $company,'route' => 'company.stripe-disconnect'])
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.bank_number') }}:</label>
                                    {!!  Form::text('bank_number', old('bank_number'),['id' => 'bank_number','class' => 'form-control','placeholder' => 'Please enter bank number','required' => 'required']) !!}
                                    @if($errors->has('bank_number'))
                                        <div class="text text-danger">
                                            {{ $errors->first('bank_number') }}
                                        </div>
                                    @endif
                                    <span>Bank number</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.bank_bsb') }}:</label>
                                    {!!  Form::text('bank_bsb', old('bank_bsb'),['id' => 'bank_bsb','class' => 'form-control','placeholder' => 'Please enter bank number','required' => 'required']) !!}
                                    @if($errors->has('bank_bsb'))
                                        <div class="text text-danger">
                                            {{ $errors->first('bank_bsb') }}
                                        </div>
                                    @endif
                                    <span>Bank BSB</span>
                                </div>
                            </div>


                        </div>
                    @include('layouts.forms.actions')

                    {{ Form::close() }}

                    <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4">
                </div>
            </div>
            <!-- begin:: Content -->
        </div>
        <!-- /page content -->
@stop

