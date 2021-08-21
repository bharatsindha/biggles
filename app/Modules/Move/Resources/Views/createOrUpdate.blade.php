@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
    'moduleTitle' => trans('common.jobs'),
    'subTitle' => isset($move) ? trans('common.edit'). ' '. trans('common.jobs') : trans('common.add').' '. trans('common.lane') ,
    'moduleLink' => route($moduleName.'.index')
])
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <style>
            /* --bs-font-sans-serif: 'Montserrat',Helvetica,Arial,serif; */
        .mapboxgl-ctrl-geocoder--input {
            width: 100% !important;
            border: 1px solid #D8D6DE !important;
            border-radius: 4px;
            background-color: transparent !important;
            margin: 0 !important;
            height: inherit !important;
            color: inherit !important;
            color: inherit !important;
            padding: 6px 45px !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            font-family: --bs-font-sans-serif;
        }

        .mapboxgl-ctrl-geocoder, .mapboxgl-ctrl-geocoder .suggestions{
            box-shadow: none !important;
        }

        @media screen and (min-width: 640px){
            .mapboxgl-ctrl-geocoder--input {
                height: 38px !important;
                padding: 6px 35px !important;
            }

            .mapboxgl-ctrl-geocoder {
                width: 100% !important;
                font-size: 15px !important;
                line-height: 20px !important;
                max-width: 100% !important;
            }
        }
        .form-control[readonly]{
        background-color: #fff;
        border-color: #AAAAAA;
        }

        .form-control{
        border-color: #AAAAAA;
        }

    </style>
@stop

@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->
      @if(isset($move))
            {{ Form::model($move, [
            'route' => [$moduleName.'.update', $move->id],
            'method' => 'patch',
            'class' => 'form-validate'
            ]) }}
            @else
            {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
            @endif
            @csrf
        <div class="row">
            {!!  Form::hidden('start_lat', old('start_lat'),['id' => 'start_lat','class' => 'form-control','placeholder' => 'Please enter Start Latitude','step'=>"any"]) !!}
                            {!!  Form::hidden('start_lng', old('start_lng'),['id' => 'start_lng','class' => 'form-control','placeholder' => 'Please enter Start Longitude','step'=>"any"]) !!}
                            {!!  Form::hidden('end_lat', old('end_lat'),['id' => 'end_lat','class' => 'form-control','placeholder' => 'Please enter End Latitude', 'step'=>"any"]) !!}
                            {!!  Form::hidden('end_lng', old('end_lng'),['id' => 'end_lng','class' => 'form-control','placeholder' => 'Please enter End Longitude', 'step'=>"any"]) !!}
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level @endphp
                            @if($userAccess != 1)
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="company_id">
                                            {{ trans('move::move.company') }}<span class="required"> * </span>
                                        </label>
                                        {!!  Form::select('company_id', $data['companyOptions'] , old('company_id'),[
                                            'id' => 'company_id',
                                            'class' => 'form-select select2'.
                                            (($errors->has('company_id')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Company',
                                            'required' => 'required'
                                            ]) !!}
                                        @if($errors->has('company_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="customer_id">
                                        {{ trans('move::move.customer') }}
                                    </label>
                                     {!!  Form::select('customer_id',  $data['customerId'] , old('customer_id'),[
                                            'id' => 'customer_id',
                                            'class' => 'form-select select2'.
                                            (($errors->has('customer_id')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Customer',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('customer_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('customer_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="stage">
                                        {{ trans('move::move.stage') }}
                                    </label>
                                      {!!  Form::select('stage',  $data['stageOptions'] , old('stage'),[
                                            'id' => 'stage',
                                            'class' => 'form-select select2'.
                                            (($errors->has('stage')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Stage',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('stage'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('stage') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="type">
                                        {{ trans('move::move.type') }}
                                    </label>
                                     {!!  Form::select('type',  $data['typeOptions'] , old('type'),[
                                            'id' => 'type',
                                            'class' => 'form-select select2'.
                                            (($errors->has('type')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Type',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('type') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="status">
                                        {{ trans('move::move.status') }}
                                    </label>
                                      {!!  Form::select('status',  $data['statusOptions'] , old('status'),[
                                            'id' => 'status',
                                            'class' => 'form-select select2'.
                                            (($errors->has('status')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Status',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="start_address">
                                        {{ trans('move::move.start_address') }}:
                                    </label>
                                    <div id="geocoder_start_addr"></div>
                                    {!!  Form::hidden('start_addr', old('start_addr'),[
                                        'id' => 'start_addr',
                                        'class' => 'form-control '. (($errors->has('start_addr')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Start Address'
                                        ]) !!}
                                    @if($errors->has('start_addr'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('start_addr') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="start_city">
                                        {{ trans('move::move.start_city') }}
                                    </label>
                                    {!!  Form::text('start_city', old('start_city'),[
                                        'id' => 'start_city',
                                        'class' => 'form-control '. (($errors->has('start_city')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Start City'
                                        ]) !!}
                                    @if($errors->has('start_city'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('start_city') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="start_postcode">
                                        {{ trans('lane::lane.start_postcode') }}:
                                    </label>
                                    {!!  Form::text('start_postcode', old('start_postcode'),[
                                        'id' => 'start_postcode',
                                        'class' => 'form-control '. (($errors->has('start_postcode')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Start Postcode'
                                        ]) !!}
                                    @if($errors->has('start_postcode'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('start_postcode') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="start_access">
                                        {{ trans('move::move.start_access') }}:
                                    </label>
                                    {!!  Form::text('start_access', old('start_access'),[
                                        'id' => 'start_access',
                                        'class' => 'form-control '. (($errors->has('start_access')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Start Access'
                                        ]) !!}
                                    @if($errors->has('start_access'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('start_access') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="start_date">
                                        {{ trans('common.start_date') }}:
                                    </label>
                                    {!!  Form::text('start_date', old('start_date'),[
                                        'id' => 'start_date',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input'. (($errors->has('start_date')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Start Date'
                                        ]) !!}
                                    @if($errors->has('start_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('start_date') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_addr">
                                        {{ trans('move::move.end_address') }}:
                                    </label>
                                    <div id="geocoder_end_addr"></div>
                                    {!!  Form::hidden('end_addr', old('end_addr'),[
                                        'id' => 'end_addr',
                                        'class' => 'form-control '. (($errors->has('end_addr')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter End Address'
                                        ]) !!}
                                    @if($errors->has('end_addr'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('end_addr') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_city">
                                        {{ trans('lane::lane.end_city') }}:
                                    </label>
                                    {!!  Form::text('end_city', old('end_city'),[
                                        'id' => 'end_city',
                                        'class' => 'form-control'. (($errors->has('end_city')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter End City'
                                        ]) !!}
                                    @if($errors->has('end_city'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('end_city') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_addr">
                                        {{ trans('lane::lane.end_postcode') }}:
                                    </label>
                                
                                    {!!  Form::text('end_postcode', old('end_postcode'),[
                                        'id' => 'end_postcode',
                                        'class' => 'form-control '. (($errors->has('end_postcode')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter End Postcode'
                                        ]) !!}
                                    @if($errors->has('end_postcode'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('end_postcode') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_access">
                                       {{ trans('move::move.end_access') }}:
                                    </label>
                                    {!!  Form::text('end_access', old('end_access'),[
                                        'id' => 'end_access',
                                        'class' => 'form-control '. (($errors->has('end_access')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter End Access'
                                        ]) !!}
                                    @if($errors->has('end_access'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('end_access') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_date">
                                       {{ trans('move::move.end_date') }}:
                                    </label>
                                    {!!  Form::text('end_date', old('end_date'),[
                                        'id' => 'end_date',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input'. (($errors->has('end_date')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter End Date'
                                        ]) !!}
                                    @if($errors->has('end_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('end_date') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="customer_analytics">
                                     {{ trans('move::move.customer_analytics') }}:
                                    </label>
                                    {!!  Form::textarea('customer_analytics', old('customer_analytics'),[
                                        'id' => 'customer_analytics',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('customer_analytics')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Customer analytics'
                                        ]) !!}
                                    @if($errors->has('customer_analytics'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('customer_analytics') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_addr"><span class="required"> * </span>
                                      {{ trans('move::move.total_price') }}
                                    </label>
                                    
                                    {!!  Form::text('total_price', old('total_price'),[
                                        'id' => 'total_price',
                                        'class' => 'form-control '. (($errors->has('total_price')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Total Price',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('total_price'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('total_price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="amount_due">
                                       {{ trans('move::move.amount_due') }}:
                                    </label>
                                    {!!  Form::text('amount_due', old('amount_due'),[
                                        'id' => 'amount_due',
                                        'class' => 'form-control '. (($errors->has('amount_due')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Amount Due',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('amount_due'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('amount_due') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="end_addr"><span class="required"> * </span>
                                      {{ trans('move::move.deposit') }}:
                                    </label>
                                    {!!  Form::text('deposit', old('deposit'),[
                                        'id' => 'deposit',
                                        'class' => 'form-control '. (($errors->has('deposit')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Deposit',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('deposit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deposit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="fee">
                                       {{ trans('move::move.fee') }}:
                                    </label>
                                    {!!  Form::text('fee', old('fee'),[
                                        'id' => 'fee',
                                        'class' => 'form-control number-format'. (($errors->has('fee')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Total Price',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('fee'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('fee') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="space"><span class="required"> * </span>
                                     {{ trans('move::move.space') }}:
                                    </label>
                                    {!!  Form::number('space', old('space'),[
                                        'id' => 'space',
                                        'class' => 'form-control '. (($errors->has('space')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Space',
                                        'required' => 'required',
                                        ]) !!}
                                    @if($errors->has('space'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('space') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="matches">
                                     {{ trans('move::move.matches') }}:
                                    </label>
                                    {!!  Form::textarea('matches', old('matches'),[
                                        'id' => 'matches',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('matches')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Matches'
                                        ]) !!}
                                    @if($errors->has('matches'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('matches') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="inventory">
                                  {{ trans('move::move.inventory') }}:
                                    </label>
                                    {!!  Form::textarea('inventory', old('inventory'),[
                                        'id' => 'inventory',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('inventory')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Inventory'
                                        ]) !!}
                                    @if($errors->has('inventory'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('inventory') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1"><span
                                            class="required"> * </span>
                                    <label class="form-label" for="dwelling_type">
                                    {{ trans('move::move.dwelling_type') }}:
                                    </label>
                                     {!!  Form::select('dwelling_type',  $data['dwellingTypeOptions'], old('dwelling_type'),[
                                            'id' => 'dwelling_type',
                                            'class' => 'form-select select2'.
                                            (($errors->has('dwelling_type')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please enter dwelling type',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('dwelling_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('dwelling_type') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="dwelling_size"><span
                                            class="required"> * </span>
                                        {{ trans('move::move.dwelling_size') }}
                                    </label>
                                      {!!  Form::select('dwelling_size',  $data['dwellingSizeOptions'], old('dwelling_size'),[
                                            'id' => 'dwelling_size',
                                            'class' => 'form-select select2'.
                                            (($errors->has('dwelling_size')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please dwelling size',
                                            'required' => 'required'
                                            ]) !!}
                                    @if($errors->has('dwelling_size'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('dwelling_size') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="pickup_window_start">
                                {{ trans('move::move.pickup_window_start') }}:
                                    </label>
                                    {!!  Form::text('pickup_window_start', old('pickup_window_start'),[
                                        'id' => 'pickup_window_start',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input'. (($errors->has('pickup_window_start')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter pickup window start date',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('pickup_window_start'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pickup_window_start') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="pickup_window_end">
                                       {{ trans('move::move.pickup_window_end') }}:
                                    </label>
                                    {!!  Form::text('pickup_window_end', old('pickup_window_end'),[
                                        'id' => 'pickup_window_end',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input'. (($errors->has('pickup_window_end')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter pickup window end date',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('pickup_window_end'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pickup_window_end') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="delivery_window_start">
                           {{ trans('move::move.delivery_window_start') }}:
                                    </label>
                                    {!!  Form::text('delivery_window_start', old('delivery_window_start'),[
                                        'id' => 'delivery_window_start',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input '. (($errors->has('delivery_window_start')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter delivery window start date',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('delivery_window_start'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_window_start') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="delivery_window_end">
                           {{ trans('move::move.delivery_window_end') }}:
                                    </label>
                                    {!!  Form::text('delivery_window_end', old('delivery_window_end'),[
                                        'id' => 'delivery_window_end',
                                        'class' => 'form-control flatpickr-date-time flatpickr-input'. (($errors->has('delivery_window_end')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter delivery window end date',
                                        'required' => 'required', 
                                        'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('delivery_window_end'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_window_end') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1  demo-inline-spacing">
                                     <label>Ancillary Services:</label> <br/>
                                    @if(isset($data['ancillaryServices']))
                                        @foreach($data['ancillaryServices'] as $ancillary)
                                            @php
                                                $selected =false
                                            @endphp
                                                @if (isset($move) && isset($move->ancillaryServices) && $move->ancillaryServices->contains($ancillary))
                                                    @php
                                                        $selected = true
                                                    @endphp
                                                @endif
                                                {!!  Form::checkbox('ancillaryServices[]', $ancillary->id, $selected, ['id' => 'ancillaryServices_'.$ancillary->id,'class' => 'form-control  form-check-input' ])
                                                 !!} {{ $ancillary->type_val  }}
                                            <br/>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
           @include('layouts.forms.actions')
        {{ Form::close() }}
    </section>
    <!-- /page content -->
@stop

@section('scripts')
    <script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
    <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet"
          href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
          type="text/css"/>
    <!-- Promise polyfill script required to use Mapbox GL Geocoder in IE 11 -->
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
    <script src="https://npmcdn.com/@turf/turf/turf.min.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-select2.min.js') }}"></script>
    <script type="application/javascript">
        var mapBoxAccessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        var wayPointsCoordinates = [];
        var wayPoints = [];
        var startLat;
        var startLng;
        var endLat;
        var endLng;
        var centerLatLng = [151.202855, -33.864601];

        // Mapbox location search API
        mapboxgl.accessToken = mapBoxAccessToken;
        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            // limit results to Australia
            countries: 'au',
            mapboxgl: mapboxgl,
            class: 'form-control'
        });
        geocoder.addTo('#geocoder_start_addr');

        var geocoderEnd = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            // limit results to Australia
            countries: 'au',
            mapboxgl: mapboxgl,
            class: 'form-control'
        });
        geocoderEnd.addTo('#geocoder_end_addr');

        geocoder.on('result', function (results) {
            var event = new Event('change');
            document.getElementById("start_addr").value = results.result.place_name;
            document.getElementById("start_lng").value = results.result.center[0];
            document.getElementById("start_lat").value = results.result.center[1];
            var resultContext = results.result.context;

            if (Array.isArray(resultContext)) {
                resultContext.forEach(function (entry) {
                    if (typeof entry.id !== "undefined") {
                        if (entry.id.indexOf("postcode") >= 0) {
                            document.getElementById("start_postcode").value = entry.text;
                        }

                        if (entry.id.indexOf("place") >= 0) {
                            document.getElementById("start_city").value = entry.text;
                        }
                    }
                });
            }
        });

        geocoderEnd.on('result', function (results) {
            var event = new Event('change');
            document.getElementById("end_addr").value = results.result.place_name;
            document.getElementById("end_lng").value = results.result.center[0];
            document.getElementById("end_lat").value = results.result.center[1];

            var resultContext = results.result.context;

            if (Array.isArray(resultContext)) {
                resultContext.forEach(function (entry) {
                    if (typeof entry.id !== "undefined") {
                        if (entry.id.indexOf("postcode") >= 0) {
                            document.getElementById("end_postcode").value = entry.text;
                        }

                        if (entry.id.indexOf("place") >= 0) {
                            document.getElementById("end_city").value = entry.text;
                        }
                    }
                });
            }
        });

        document.querySelector('#geocoder_start_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#start_addr').val();
        document.querySelector('#geocoder_end_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#end_addr').val();
    </script>
    <script>
        function formatPrice(obj) {
        }
        $(function () {
        });
        $(document).ready(function () {
            $("#form-btn-save").click(function () {
                let returnFlag = true;
                if ($.trim($("#start_postcode").val()) == '') {
                    $("#start_postcode").prop('type', 'text');
                    $('label[for="start_postcode"]').removeClass('hide');
                    returnFlag = false;
                }
                if ($.trim($("#start_city").val()) == '') {
                    $("#start_city").prop('type', 'text');
                    $('label[for="start_city"]').removeClass('hide');
                    returnFlag = false;
                }
                if ($.trim($("#end_postcode").val()) == '') {
                    $("#end_postcode").prop('type', 'text');
                    $('label[for="end_postcode"]').removeClass('hide');
                    returnFlag = false;
                }
                if ($.trim($("#end_city").val()) == '') {
                    $("#end_city").prop('type', 'text');
                    $('label[for="end_city"]').removeClass('hide');
                    returnFlag = false;
                }

                return returnFlag;
            });
        });

    </script>
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 18px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #D8D6DE transparent transparent transparent;
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


