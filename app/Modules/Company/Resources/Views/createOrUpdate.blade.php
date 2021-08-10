@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.company')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($company) ? 'Edit company' : 'Add company' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">

                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Company profile</h3>
                            </div>
                        </div>

                        <!--begin::Form-->
                        @if(isset($company))
                            {{ Form::model($company, ['route' => ['company.update', $company->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'company.store']) }}
                        @endif
                            @csrf
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('company::company.company_name') }}:</label>
                                        {!!  Form::text('name', old('name'),['id' => 'name','class' => 'form-control', 'placeholder' => 'Please enter Name','required' => 'required']) !!}
                                        @if($errors->has('name'))
                                            <div class="text text-danger">
                                                {{ $errors->first('name') }}
                                            </div>
                                        @endif
                                        <span>Name</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('company::company.email') }}:</label>
                                        {!!  Form::text('email', old('email'),['id' => 'email','class' => 'form-control','placeholder' => 'Please enter Email','required' => 'required']) !!}
                                        @if($errors->has('email'))
                                            <div class="text text-danger">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                        <span>Email</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('company::company.address') }}:</label>
                                        <div class="kt-input-icon">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-map-marker"></i></span></span>
                                        </div>
                                        {!!  Form::text('address', old('address'),['id' => 'address', 'class' => 'form-control','placeholder' => 'Please enter Address','required' => 'required']) !!}
                                        @if($errors->has('address'))
                                            <div class="text text-danger">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                        <span>Address</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('company::company.website') }}:</label>
                                        {!!  Form::text('website', old('website'),['id' => 'website', 'class' => 'form-control','placeholder' => 'Please enter website','required' => 'required']) !!}
                                        @if($errors->has('website'))
                                            <div class="text text-danger">
                                                {{ $errors->first('website') }}
                                            </div>
                                        @endif
                                        <span>Website</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('company::company.phone') }}:</label>
                                        {!!  Form::text('phone', old('phone'),['id' => 'phone','class' => 'form-control','placeholder' => 'Please enter phone','required' => 'required']) !!}
                                        @if($errors->has('phone'))
                                            <div class="text text-danger">
                                                {{ $errors->first('phone') }}
                                            </div>
                                        @endif
                                        <span>Phone</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('company::company.hosted_phone') }}:</label>
                                        {!!  Form::text('hosted_phone', old('hosted_phone'),['id' => 'hosted_phone','class' => 'form-control','placeholder' => 'Please enter hosted phone','required' => 'required']) !!}
                                        @if($errors->has('hosted_phone'))
                                            <div class="text text-danger">
                                                {{ $errors->first('hosted_phone') }}
                                            </div>
                                        @endif
                                        <span>Hosted Phone</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('company::company.abn') }}:</label>
                                        {!! Form::text('abn', old('abn'),['id' => 'abn','class' => 'form-control','placeholder' => 'Please enter abn']) !!}
                                        @if($errors->has('abn'))
                                            <div class="text text-danger">
                                                {{ $errors->first('abn') }}
                                            </div>
                                        @endif
                                        <span>Abn</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>{{ trans('company::company.about_us') }}:</label>
                                        {!! Form::textarea('about_us', old('about_us'), ['class'=>'form-control','id' => 'about_us', 'rows' => '3', 'cols' => '5', 'placeholder' =>'About US']) !!}
                                        @if($errors->has('about_us'))
                                            <div class="text text-danger">
                                                {{ $errors->first('about_us') }}
                                            </div>
                                        @endif
                                        <span>About company</span>
                                    </div>
                                </div>

                            </div>
                        <!--end::Form-->
                    </div>
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Company setting</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.bank_number') }}:</label>
                                    {!!  Form::text('bank_number', old('bank_number'),['id' => 'bank_number','class' => 'form-control','placeholder' => 'Please enter bank number','required' => 'required']) !!}
                                    @if($errors->has('bank_number'))
                                        <div class="text text-danger">
                                            {{ $errors->first('bank_number') }}
                                        </div>
                                    @endif
                                    <span>Bank Number</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.bank_bsb') }}:</label>
                                    {!!  Form::text('bank_bsb', old('bank_bsb'),['id' => 'bank_bsb','class' => 'form-control','placeholder' => 'Please enter bank number','required' => 'required']) !!}
                                    @if($errors->has('bank_bsb'))
                                        <div class="text text-danger">
                                            {{ $errors->first('bank_bsb') }}
                                        </div>
                                    @endif
                                    <span>Bank Number</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.stripe_id') }}:</label>
                                    {!! Form::text('stripe_id', old('stripe_id'),['id' => 'stripe_id','class' => 'form-control','placeholder' => 'Please enter stripe']) !!}
                                    @if($errors->has('stripe_id'))
                                        <div class="text text-danger">
                                            {{ $errors->first('stripe_id') }}
                                        </div>
                                    @endif
                                    <span>Stripe</span>
                                </div>
                            </div>
                        </div>
                    <!--end::Form-->
                    </div>
                    <!--end::Portlet-->
                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Interstate setting</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                {!!  Form::hidden('inter_state_id', isset($company->interState->id) && $company->interState->id>0 ? $company->interState->id : 0, ['id' => 'inter_state_id','class' => 'form-control']) !!}
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.min_price') }}:</label>
                                    {!!  Form::number('min_price',isset($company->interState->min_price) ? $company->interState->min_price : old('min_price'),['id' => 'min_price','class' => 'form-control','placeholder' => 'Please enter min price','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('min_price'))
                                        <div class="text text-danger">
                                            {{ $errors->first('min_price') }}
                                        </div>
                                    @endif
                                    <span>Min Price</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.stairs') }}:</label>
                                    {!!  Form::number('stairs', isset($company->interState->stairs) ? $company->interState->stairs : old('stairs'),['id' => 'stairs','class' => 'form-control','placeholder' => 'Please enter stairs','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('stairs'))
                                        <div class="text text-danger">
                                            {{ $errors->first('stairs') }}
                                        </div>
                                    @endif
                                    <span>Stairs</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.elevator') }}:</label>
                                    {!!  Form::number('elevator', isset($company->interState->elevator) ? $company->interState->elevator : old('elevator'),['id' => 'elevator','class' => 'form-control','placeholder' => 'Please enter elevator','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('elevator'))
                                        <div class="text text-danger">
                                            {{ $errors->first('elevator') }}
                                        </div>
                                    @endif
                                    <span>Elevator</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.long_driveway') }}:</label>
                                    {!!  Form::number('long_driveway', isset($company->interState->long_driveway) ? $company->interState->long_driveway : old('long_driveway'),['id' => 'long_driveway','class' => 'form-control','placeholder' => 'Please enter long driveway','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('long_driveway'))
                                        <div class="text text-danger">
                                            {{ $errors->first('long_driveway') }}
                                        </div>
                                    @endif
                                    <span>Long Driveway</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.ferry_vehicle') }}:</label>
                                    {!!  Form::number('ferry_vehicle', isset($company->interState->ferry_vehicle) ? $company->interState->ferry_vehicle : old('ferry_vehicle'),['id' => 'ferry_vehicle','class' => 'form-control','placeholder' => 'Please enter ferry vehicle','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('ferry_vehicle'))
                                        <div class="text text-danger">
                                            {{ $errors->first('ferry_vehicle') }}
                                        </div>
                                    @endif
                                    <span>Ferry Vehicle</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.heavy_items') }}:</label>
                                    {!! Form::number('heavy_items', isset($company->interState->heavy_items) ? $company->interState->heavy_items : old('heavy_items'),['id' => 'heavy_items','class' => 'form-control','placeholder' => 'Please enter heavy items','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('heavy_items'))
                                        <div class="text text-danger">
                                            {{ $errors->first('heavy_items') }}
                                        </div>
                                    @endif
                                    <span>Heavy Items</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.extra_kms') }}:</label>
                                    {!!  Form::number('extra_kms', isset($company->interState->extra_kms) ? $company->interState->extra_kms : old('extra_kms'),['id' => 'extra_kms','class' => 'form-control','placeholder' => 'Please enter extra kms','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('extra_kms'))
                                        <div class="text text-danger">
                                            {{ $errors->first('extra_kms') }}
                                        </div>
                                    @endif
                                    <span>Extra kms</span>
                                </div>
                                <div class="col-lg-3">
                                    <label class="">{{ trans('company::company.packaging') }}:</label>
                                    {!!  Form::number('packing', isset($company->interState->packing) ? $company->interState->packing : old('packing'),['id' => 'packing','class' => 'form-control','placeholder' => 'Please enter packing','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('packing'))
                                        <div class="text text-danger">
                                            {{ $errors->first('packing') }}
                                        </div>
                                    @endif
                                    <span>Packing</span>
                                </div>
                                <div class="col-lg-3">
                                    <label class="">{{ trans('company::company.storage') }}:</label>
                                    {!!  Form::number('storage', isset($company->interState->storage) ? $company->interState->storage : old('storage'),['id' => 'storage','class' => 'form-control','placeholder' => 'Please enter storage','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('storage'))
                                        <div class="text text-danger">
                                            {{ $errors->first('storage') }}
                                        </div>
                                    @endif
                                    <span>Storage</span>
                                </div>
                            </div>
                        </div>

                    @include('layouts.forms.actions')
                        <!--end::Form-->
                    </div>
                    <!--end::Portlet-->

                </div>
                {{ Form::close() }}
                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop
@section('scripts')
<style>
    #kt_content .kt-portlet__head + .kt-portlet__body {
        padding: 25px;
    }
</style>
@stop
