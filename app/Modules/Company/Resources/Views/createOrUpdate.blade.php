@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.company')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.company'),
        'subTitle' => isset($company) ? trans('common.edit'). ' '. trans('common.company') : trans('common.add').' '. trans('common.company') ,
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
@stop

@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->
        @if(isset($company))
            {{ Form::model($company, [
            'route' => [$moduleName.'.update', $company->id],
            'method' => 'patch',
            'class' => 'form-validate'
            ]) }}
        @else
            {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
        @endif
        @csrf
        <section>
         <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Company profile</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="name">
                                        {{ trans('company::company.company_name') }}
                                    </label>
                                    {!!  Form::text('name', old('name'),[
                                        'id' => 'name',
                                        'class' => 'form-control '. (($errors->has('name')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter company Name'
                                        ]) !!}
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="email">
                                        {{ trans('company::company.email') }}
                                    </label>
                                    {!!  Form::text('email', old('email'),[
                                        'id' => 'email',
                                        'class' => 'form-control '. (($errors->has('email')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Email'
                                        ]) !!}
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="address">
                                        {{ trans('company::company.address') }}
                                    </label>
                                    {!!  Form::text('address', old('address'),[
                                        'id' => 'address',
                                        'class' => 'form-control '. (($errors->has('address')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter address'
                                        ]) !!}
                                    @if($errors->has('address'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="website">
                                        {{ trans('company::company.website') }}
                                    </label>
                                    {!!  Form::text('website', old('website'),[
                                        'id' => 'website',
                                        'class' => 'form-control '. (($errors->has('website')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter company website'
                                        ]) !!}
                                    @if($errors->has('website'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('website') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="address">
                                        {{ trans('company::company.phone') }}
                                    </label>
                                    {!!  Form::text('phone', old('phone'),[
                                        'id' => 'phone',
                                        'class' => 'form-control '. (($errors->has('phone')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter phone'
                                        ]) !!}
                                    @if($errors->has('phone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="hosted_phone">
                                        {{ trans('company::company.hosted_phone') }}
                                    </label>
                                    {!!  Form::text('hosted_phone', old('hosted_phone'),[
                                        'id' => 'hosted_phone',
                                        'class' => 'form-control '. (($errors->has('hosted_phone')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter company hosted_phone'
                                        ]) !!}
                                    @if($errors->has('hosted_phone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('hosted_phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="abn">
                                        {{ trans('company::company.abn') }}
                                    </label>
                                    {!!  Form::text('abn', old('abn'),[
                                        'id' => 'abn',
                                        'class' => 'form-control '. (($errors->has('abn')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter abn'
                                        ]) !!}
                                    @if($errors->has('abn'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('abn') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="aboutus">
                                            {{ trans('company::company.about_us') }}
                                        </label>
                                        {!!  Form::textarea('about_us', old('about_us'),[
                                            'id' => 'about_us',
                                            'rows' => '3', 'cols' => '5',
                                            'class' => 'form-control '. (($errors->has('about_us')) ? 'is-invalid' :    ''),
                                            'placeholder' => 'About US'
                                            ]) !!}
                                        @if($errors->has('about_us'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('about_us') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                             <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Company setting</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="bank_number">
                                        {{ trans('company::company.bank_number') }}
                                    </label>
                                    {!!  Form::text('bank_number', old('bank_number'),[
                                        'id' => 'bank_number',
                                        'class' => 'form-control '. (($errors->has('bank_number')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter bank number'
                                        ]) !!}
                                    @if($errors->has('bank_number'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('bank_number') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="bank_bsb">
                                        {{ trans('company::company.bank_bsb') }}
                                    </label>
                                    {!!  Form::text('bank_bsb', old('bank_bsb'),[
                                        'id' => 'bank_bsb',
                                        'class' => 'form-control '. (($errors->has('bank_bsb')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter bank bsb'
                                        ]) !!}
                                    @if($errors->has('bank_bsb'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('bank_bsb') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="stripe_id">
                                        {{ trans('company::company.stripe_id') }}
                                    </label>
                                    {!!  Form::text('stripe_id', old('stripe_id'),[
                                        'id' => 'stripe_id',
                                        'class' => 'form-control '. (($errors->has('stripe_id')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter stripe_id'
                                        ]) !!}
                                    @if($errors->has('stripe_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('stripe_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section>
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                          <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Interstate setting</h3>
                            </div>
                        </div>
                        <div class="row">
                                     {!!  Form::hidden('inter_state_id', isset($company->interState->id) && $company->interState->id>0 ? $company->interState->id : 0, ['id' => 'inter_state_id','class' => 'form-control']) !!}
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="min_price">
                                        {{ trans('company::company.min_price') }}
                                    </label>
                                    {!!  Form::number('min_price',isset($company->interState->min_price) ? $company->interState->min_price : old('min_price'),[
                                        'id' => 'min_price',
                                        'class' => 'form-control '. (($errors->has('min_price')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter minimum price',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('min_price'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('min_price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                              <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="stairs">
                                        {{ trans('company::company.stairs') }}
                                    </label>
                                    {!!  Form::number('stairs',isset($company->interState->stairs) ? $company->interState->stairs : old('stairs'),[
                                        'id' => 'stairs',
                                        'class' => 'form-control '. (($errors->has('stairs')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter stairs',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('stairs'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('stairs') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                         <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="elevator">
                                        {{ trans('company::company.elevator') }}
                                    </label>
                                    {!!  Form::number('elevator',isset($company->interState->elevator) ? $company->interState->elevator : old('elevator'),[
                                        'id' => 'elevator',
                                        'class' => 'form-control '. (($errors->has('elevator')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter elevator',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('elevator'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('elevator') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                           <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="long_driveway">
                                        {{ trans('company::company.long_driveway') }}
                                    </label>
                                    {!!  Form::number('long_driveway',isset($company->interState->long_driveway) ? $company->interState->long_driveway : old('long_driveway'),[
                                        'id' => 'long_driveway',
                                        'class' => 'form-control '. (($errors->has('long_driveway')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter long driveway',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('long_driveway'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('long_driveway') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="ferry_vehicle">
                                        {{ trans('company::company.ferry_vehicle') }}
                                    </label>
                                    {!!  Form::number('ferry_vehicle',isset($company->interState->ferry_vehicle) ? $company->interState->ferry_vehicle : old('ferry_vehicle'),[
                                        'id' => 'ferry_vehicle',
                                        'class' => 'form-control '. (($errors->has('ferry_vehicle')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter ferry_vehicle',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('ferry_vehicle'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('ferry_vehicle') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="heavy_items">
                                        {{ trans('company::company.heavy_items') }}
                                    </label>
                                    {!!  Form::number('heavy_items',isset($company->interState->heavy_items) ? $company->interState->heavy_items : old('heavy_items'),[
                                        'id' => 'heavy_items',
                                        'class' => 'form-control '. (($errors->has('heavy_items')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter heavy items',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('heavy_items'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('heavy_items') }}
                                        </div>
                                    @endif
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                   <div class="mb-1">
                                    <label class="form-label" for="extra_kms">
                                        {{ trans('company::company.extra_kms') }}
                                    </label>
                                    {!!  Form::number('extra_kms',isset($company->interState->extra_kms) ? $company->interState->extra_kms : old('extra_kms'),[
                                        'id' => 'extra_kms',
                                        'class' => 'form-control '. (($errors->has('extra_kms')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter extra kms',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('extra_kms'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('extra_kms') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                   <div class="mb-1">
                                    <label class="form-label" for="packaging">
                                        {{ trans('company::company.packaging') }}
                                    </label>
                                    {!!  Form::number('packaging',isset($company->interState->packaging) ? $company->interState->packaging : old('packaging'),[
                                        'id' => 'packaging',
                                        'class' => 'form-control '. (($errors->has('packaging')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter packaging',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('packaging'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('packaging') }}
                                        </div>
                                    @endif
                                </div>
                            </div>  
                                <div class="col-md-3">
                                   <div class="mb-1">
                                    <label class="form-label" for="storage">
                                        {{ trans('company::company.storage') }}
                                    </label>
                                    {!!  Form::number('storage',isset($company->interState->storage) ? $company->interState->storage : old('storage'),[
                                        'id' => 'storage',
                                        'class' => 'form-control '. (($errors->has('storage')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter storage',
                                        'required' => 'required', 
                                        'step'=>"any"
                                        ]) !!}
                                    @if($errors->has('storage'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('storage') }}
                                        </div>
                                    @endif
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
        @include('layouts.forms.actions')
        {{ Form::close() }}
    </section>
    <!-- /page content -->
@stop

@section('scripts')
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-select2.min.js') }}"></script>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: none;
            border-width: 5px 4px 0 4px;
            height: 9px;
            left: 50%;
            margin-left: -15px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
    </style>
@stop