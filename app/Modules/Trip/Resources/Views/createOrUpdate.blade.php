@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.trip')]) @stop
@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.trip'),
    'subTitle' => isset($trip) ? trans('common.edit'). ' '. trans('common.trip') : trans('common.add').' '. trans('common.trip') ,
    'moduleLink' => route($moduleName.'.index')
])
@stop

@section('css')
<link href="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">

<link rel="stylesheet"
      href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
      type="text/css"/>

      <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <style>
            /* --bs-font-sans-serif: 'Montserrat',Helvetica,Arial,serif; */
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
    <section class="app-user-edit user_edit" id="kt_content">
        <!-- begin:: Content -->

        <!--begin::Form-->
        @if(isset($tripCreatedFromLane) && $tripCreatedFromLane)
            {{ Form::model($trip, ['route' => ['trip.store'], 'id' =>'trip-form','method' => 'post', 'enctype' => "multipart/form-data"]) }}
        @elseif(isset($trip))
            {{ Form::model($trip, [
                'route' => [$moduleName.'.update', $trip->id],
                'method' => 'patch',
                'class' => 'form-validate',
                'id' =>'trip-form',
                'enctype' => "multipart/form-data"
                ]) }}
        @else
            {{ Form::open(['route' => $moduleName.'.store',  'id' =>'trip-form',   'enctype' => "multipart/form-data" , 'class' => 'form-validate']) }}
        @endif
        @csrf
        {!! Form::hidden('start_lat', old('start_lat'),['id' => 'start_lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude','required' => 'required']) !!}
        {!!  Form::hidden('start_lng', old('start_lng'),['id' => 'start_lng','class' => 'form-control','placeholder' => 'Please enter Longitude','required' => 'required']) !!}
        {!!  Form::hidden('start_city', old('start_city'),['id' => 'start_city','class' => 'form-control','placeholder' => 'Please enter start city','required' => 'required']) !!}
        {!!  Form::hidden('start_postcode', old('start_postcode'),['id' => 'start_postcode','class' => 'form-control','placeholder' => 'Please enter start postcode']) !!}
        {!! Form::hidden('end_lat', old('end_lat'),['id' => 'end_lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude','required' => 'required']) !!}
        {!!  Form::hidden('end_lng', old('end_lng'),['id' => 'end_lng','class' => 'form-control','placeholder' => 'Please enter Longitude','required' => 'required']) !!}
        {!!  Form::hidden('end_city', old('end_city'),['id' => 'end_city','class' => 'form-control','placeholder' => 'Please enter end city','required' => 'required']) !!}
        {!!  Form::hidden('end_postcode', old('end_postcode'),['id' => 'end_postcode','class' => 'form-control','placeholder' => 'Please enter end postcode']) !!}
        {!! Form::hidden('route', old('route'),['id' => 'route','class' => 'form-control']) !!}
        <div class="row lane_page_content">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-1 order-lg-1">
                <div class="card">
                    <div class="card-header pb-50">
                    <h4 class="card-title">Transport and Space</h4>
                    </div>
                    <div class="card-body">
                        <div class="border-bottom">          
                            {{-- It will be used after                      --}}
                        {{-- <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap mt-0">
                            <div class="lane_checkbox d-flex w-100 transport_box">
                                <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport
                                {{ isset($lane->transport) && $lane->transport == 1 ? 'active' : '' }}">
                                    <input class="form-check-input" type="radio" name="transport" id="truckRadio" value="1"
                                           {{ isset($lane->transport) && $lane->transport == 1 ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="truckRadio">Truck</label>
                                </div>
                                <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport
                                {{ isset($lane->transport) && $lane->transport == 2 ? 'active' : '' }}">
                                    <input class="form-check-input" type="radio" name="transport" id="railRadio" value="2"
                                           {{ isset($lane->transport) && $lane->transport == 2 ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="railRadio">Rail</label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                        @if(\App\Facades\General::isSuperAdmin())
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1 mt-1">
                                    <label class="form-label" for="company_id">
                                        {{ trans('truck::truck.company') }}<span class="required"> * </span>
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
                                    <label class="form-label" for="maxspace">
                                        {{ trans('lane::lane.maximum_space') }}
                                    </label>
                                    {!!  Form::text('capacity', old('capacity'),[
                                        'id' => 'capacity',
                                        'class' => 'form-control '. (($errors->has('capacity')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter truck Capacity'
                                        ]) !!}
                                    @if($errors->has('capacity'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('capacity') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 __toggle__truck_option">
                                <div class="mb-1">
                                    <label class="form-label" for="truck_id">
                                        {{ trans('move::move.truck') }}<span class="required"> * </span>
                                    </label>
                                    {!!  Form::select('truck_id', $data['truckOptions'], old('truck_id'),[
                                        'id' => 'truck_id',
                                        'class' => 'form-control select2'. (($errors->has('truck_id')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please select Truck',
                                        'required' => 'required'
                                        ]) !!}
                                    @if($errors->has('truck_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('truck_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="card">
                <div class="card-header pb-50">
                    <h4 class="card-title">Location</h4>
                </div>
                <div class="card-body">
                    <div class="border-bottom mb-1">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="mb-1">
                            <label class="form-label" for="start_address">
                                {{ trans('lane::lane.start_address') }}:
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
                            @endif
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="mb-1">
                            <label class="form-label" for="end_address">
                                {{ trans('lane::lane.end_address') }}
                            </label>
                            <div id="geocoder_end_addr"></div>
                            {!!  Form::hidden('end_addr', old('end_addr'),[
                                'id' => 'end_addr',
                                'class' => 'form-control '. (($errors->has('end_addr')) ? 'is-invalid' : ''),
                                'placeholder' => 'Please enter end Address'
                                ]) !!}
                            @if($errors->has('end_addr'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('end_addr') }}
                                </div>
                            @endif
                        </div>
                        </div>
                    </div> 
                    <div class="form row {{ isset($trip) && !is_null($trip->waypoint) ? 'show' : 'd-none' }}"
                            id="_toggle_waypoint_section">
                            <div class="col-lg-12">
                                <label class="form-label">{{ trans('trip::trip.add_waypoint') }}</label>
                                <div id="geocoder_waypoint"></div>
                                <input type="hidden">
                            </div>
                            <div class="col-lg-12 mt-50">
                                <p class="form-label">Waypoints:</p>
                                <ul id='external-events-listing' class="p-0">
                                    @if(isset($trip))
                                        @php $waypointsArr = json_decode($trip->waypoint) @endphp
                                        @if(isset($waypointsArr) && !is_null($waypointsArr) && !empty($waypointsArr))
                                            @foreach($waypointsArr as $waypoint)
                                                @php $waypoint = json_decode($waypoint, true) @endphp
                                                <li id="0"
                                                    class="fc-event d-flex w-100 justify-content-between mt-05 p-1 common-box-shadow"
                                                    data-lat="{{ $waypoint['lat'] }}"
                                                    data-lng="{{ $waypoint['lng'] }}"
                                                    data-place="{{ $waypoint['place'] }}">{{ $waypoint['place'] }}
                                                    <i data-feather='trash' class="" type="button"
                                                    onclick="removeWaypoint(this)"></i>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-50">
                    <h4 class="card-title">Customer Pricing</h4>
                    </div>
                    <div class="card-body">
                        <div class="border-bottom">
                        </div>
                    <div class="demo-inline-spacing">
                        <div class="lane_checkbox d-flex w-100 transport_box">
                            {{--checked="{{ (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'true' : 'false' }}"--}}
                            <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport
                                    {{ (isset($trip->laneTieredPrice[0]) && $trip->laneTieredPrice[0]->price_type == 'single') ? 'active' : '' }} lane_checkbox_content_pricing __lane_pricing_muval">
                                        <input class="form-check-input" type="radio" name="price_type" id="inlineRadio2" value="single"
                                        {{ (isset($trip->laneTieredPrice[0]) && $trip->laneTieredPrice[0]->price_type == 'single') ? 'checked' : '' }} required />
                                        <label class="form-check-label" for="inlineRadio2">Single price</label>
                                    </div>
                            {{--checked="{{ (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'true' : 'false' }}"--}}
                            <div class="form-check form-check-inline lane_checkbox_content  lane_checkbox_content_transport {{ (isset($trip->laneTieredPrice[0]) && $trip->laneTieredPrice[0]->price_type == 'tiered') ? 'active' : '' }} lane_checkbox_content_pricing d-flex __lane_pricing_muval">
                                <input type="radio" value="tiered" class="form-check-input" name="price_type" {{ (isset($trip->laneTieredPrice[0]) && $trip->laneTieredPrice[0]->price_type == 'tiered') ? 'checked' : '' }} required>
                                <label class="form-check-label mx-50">Tiered price</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <blockquote class="border-start-3 price_section d-flex align-items-center border-start-primary bg-light-primary">
                            <span><i data-feather='alert-circle' class="m-1"></i>Did you know that the average price for this lane is <strong>$1500</strong></span>
                        </blockquote>
                    </div>
                            <div class="form-group row mb-0 __lane_single_pricing {{ (isset($trip->laneTieredPrice) && $trip->laneTieredPrice[0]->price_type == 'single') ? 'show' : 'd-none' }}">
                                <div class="col-lg-6 box_space">
                                    <label class="form-label" for="min_price">Price shown to customer</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary" id="basic-addon1">$</span>
                                        </div>
                                        {!! Form::text('min_price', (isset($trip->laneTieredPrice) && $trip->laneTieredPrice[0]->price_type == 'single' && isset($trip->laneTieredPrice[0]->price)) ? $trip->laneTieredPrice[0]->price : old('min_price'),['id' => 'min_price','class' => 'form-control', 'placeholder' => 'Please enter min price']) !!}
                                        @if($errors->has('min_price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('min_price') }}
                                            </div>
                                        @endif
                                        @if(isset($trip) && count($trip->laneTieredPrice) > 0 && $trip->laneTieredPrice[0]->price_type == 'single')
                                        {!! Form::hidden('single_price_id', $trip->laneTieredPrice[0]->id,['class' => 'form-control']) !!}
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="__lane_tiered_pricing {{ (isset($trip->laneTieredPrice) && $trip->laneTieredPrice[0]->price_type == 'tiered') ? 'show' : 'd-none' }}">
                                @if(isset($trip) && isset($trip->laneTieredPrice) && count($trip->laneTieredPrice) > 0 && $trip->laneTieredPrice[0]->price_type == 'tiered')
                                    @foreach($trip->laneTieredPrice as $key => $tiredPrice)
                                        <div class="form-group row mb-0 mt-25">
                                            <div class="col-sm">
                                                <label class="form-label" for="price_per">From</label>
                                                <div class="input-group mb-1">
                                                    {!! Form::text('tiered_price['.$key.'][space_start_range]', $tiredPrice->space_start_range,['class' => 'form-control range_start hide_show_range', 'placeholder' => 'Please enter space start range','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                    @if($errors->has('space_start_range'))
                                                    <div class="text text-danger">
                                                        {{ $errors->first('space_start_range') }}
                                                    </div>
                                                @endif
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light-primary">m<sup>3</sup>
                                                    </span>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <label class="form-label" for="price_per">To</label>
                                                <div class="input-group mb-1">
                                                    {!! Form::text('tiered_price['.$key.'][space_end_range]', $tiredPrice->space_end_range,['id' => 'space_end_range','class' => 'form-control range_end hide_show_range', 'placeholder' => 'Please enter space end range','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                    @if($errors->has('space_start_range'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('space_start_range') }}
                                                        </div>
                                                    @endif
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light-primary">m<sup>3</sup></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm box_space">
                                                <label class="form-label" for="price">Minimum Price</label>
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light-primary">$</span>
                                                    </div>
                                                    {!! Form::text('tiered_price['.$key.'][price]', $tiredPrice->price,['id' => 'price','class' => 'form-control tiered_price_class hide_show_range', 'placeholder' => 'Please enter min price','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                    @if($errors->has('price'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('price') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            {!! Form::hidden('tiered_price['.$key.'][id]', $tiredPrice->id,['class' => 'form-control', 'data-tiered-index' => $key]) !!}
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group row mb-0 mt-25">
                                                <div class="col-sm">
                                                    <label class="form-label" for="price_per">From</label>
                                                    <div class="input-group mb-1">
                                                    {!! Form::text('tiered_price[0][space_start_range]', old('space_start_range'),['class' => 'form-control  ', 'placeholder' => 'Please enter space start range', 'data-tiered-index' => '0']) !!}
                                                    @if($errors->has('space_start_range'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('space_start_range') }}
                                                        </div>
                                                    @endif
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light-primary">m<sup>3</sup>
                                                        </span>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <label class="form-label" for="price_per">To</label>
                                                    <div class="input-group mb-1">
                                                    {!! Form::text('tiered_price[0][space_end_range]', old('space_end_range'),['id' => 'space_end_range','class' => 'form-control  ', 'placeholder' => 'Please enter space end range', 'data-tiered-index' => '0']) !!}
                                                    
                                                    @if($errors->has('space_start_range'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('space_end_range') }}
                                                        </div>
                                                    @endif
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light-primary">m<sup>3</sup></span>
                                                    </div>
                                                    </div>
                                                </div>
                                        <div class="col-sm box_space">
                                            <label class="form-label" for="price">Minimum Price</label>
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light-primary">$</span>
                                                </div>
                                                {!! Form::text('tiered_price[0][price]', old('price'),['id' => 'price','class' => 'form-control tiered_price_class hide_show_range', 'placeholder' => 'Please enter min price', 'data-tiered-index' => '0']) !!}
                                                @if($errors->has('price'))
                                                    <div class="text text-danger">
                                                        {{ $errors->first('price') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <input type="hidden" data-tiered-index="0" value="" name="tiered_price[0][id]"/>
                                    </div>
                                @endif
                                <div class="form-group row mb-0 mt-25 d-none" id="tieredTemplate">
                                    <div class="col-sm">
                                        <label class="form-label" for="price_per">From</label>
                                        <div class="input-group mb-1">
                                        {!! Form::text('space_start_range', '',['class' => 'form-control  ', 'placeholder' => 'Please enter space start range', 'data-tiered-index' => '0']) !!}
                                        @if($errors->has('space_start_range'))
                                            <div class="text text-danger">
                                                {{ $errors->first('space_start_range') }}
                                            </div>
                                        @endif
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary">m<sup>3</sup>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-label" for="price_per">To</label>
                                        <div class="input-group mb-1">
                                        {!! Form::text('space_end_range', '',['id' => 'space_end_range','class' => 'form-control  ', 'placeholder' => 'Please enter space end range', 'data-tiered-index' => '0']) !!}
                                        
                                        @if($errors->has('space_start_range'))
                                            <div class="text text-danger">
                                                {{ $errors->first('space_end_range') }}
                                            </div>
                                        @endif
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary">m<sup>3</sup></span>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm box_space">
                                        <label class="form-label" for="price">Minimum Price</label>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light-primary">$</span>
                                            </div>
                                            {!! Form::text('price', '',['id' => 'price','class' => 'form-control tiered_price_class', 'placeholder' => 'Please enter min price', 'data-tiered-index' => '0']) !!}
                                            @if($errors->has('price'))
                                                <div class="text text-danger">
                                                    {{ $errors->first('price') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <a href="#" class="range_section d-flex justify-content-center align-content-center mt-50 addTieredButton d-none">Add another range</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-50">
                        <h4 class="card-title">Timing</h4>
                        </div>
                        <div class="card-body">
                            <div class="border-bottom mb-1"></div>
                        <div class="row">
                         <div class="col-lg-6 box_space">
                            <label class="form-label" for="departure_date">{{ trans('trip::trip.departure_date') }}</label>
                            <div class="input-group mb-2">
                                {!! Form::text('start_date', old('start_date'),[ 'autocomplete'=>"off",'id' => 'start_date','class' => 'form-control flatpickr-basic flatpickr-date flatpickr-input active'. (($errors->has('start_date')) ? 'is-invalid' : ''), 'placeholder' => 'Enter Departure date']) !!}
                                @if($errors->has('start_date'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('start_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 box_space">
                            <label class="form-label" for="delivery_within">{{ trans('trip::trip.delivery_within') }}</label>
                            <div class="input-group mb-2">
                                {!! Form::text('delivery_within', old('delivery_within'),['id' => 'delivery_within','class' => 'form-control '. (($errors->has('delivery_within')) ? 'is-invalid' : ''), 'placeholder' => 'Enter Delivery days']) !!}
                                @if($errors->has('delivery_within'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('delivery_within') }}
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3 d-flex">
                                <label>{{ trans('trip::trip.recurring') }}</label>
                                <div class="col-3 mx-50">
                                    <span class="switch">
                                        <label class="form-check form-switch">
                                            <input name="recurring_trip" id="recurring-trip" type="checkbox" class="form-check-input" name="recurring">
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-9" id="trip-details">
                                <div class="row">
                                    <div class="lane_checkbox d-flex transport_box col-lg-5">
                                        <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport">
                                            <input class="form-check-input" type="radio" name="frequency" id="inlineRadio3" value="weekly"/>
                                            <label class="form-check-label" for="inlineRadio3">{{ trans('trip::trip.weekly') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport">
                                            <input class="form-check-input" type="radio" name="frequency" id="inlineRadio4" value="monthly"/>
                                            <label class="form-check-label" for="inlineRadio4">{{ trans('trip::trip.monthly') }}</label>
                                        </div>
                                    </div>
                                     <div class="col-lg-7 box_space">
                                        <label class="form-label" for="recurrence_expiry">{{ trans('trip::trip.recurrence_expiry') }}:</label>
                                        <div class="input-group mb-1">
                                            {!! Form::text('expiry_date', old('expiry_date'),[ 'autocomplete'=>"off",'id' => 'expiry_date','class' => 'form-control flatpickr-basic flatpickr-date flatpickr-input active'. (($errors->has('expiry_date')) ? 'is-invalid' : ''), 'placeholder' => 'Please select Recurrence Expiry']) !!}
                                            @if($errors->has('expiry_date'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('expiry_date') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($trip))
                        @php $waypointsArr = json_decode($trip->waypoint) @endphp
                        @if(isset($waypointsArr) && !is_null($waypointsArr))
                            @foreach($waypointsArr as $key => $waypoint)
                                <input type="hidden" class="waypoint" name="waypoint[{{ $key }}]" value="{{ $waypoint }}">
                            @endforeach
                        @endif
                    @endif
                        <div class="form-group row mb-0">
                            <div class="col-lg-6 mt-1">
                                <label class="form-label"><span class="required"> * </span>{{ trans('trip::trip.transit_time') }}
                                    </label>
                                <div class="input-group">
                                    {!!  Form::text('transit_time', old('transit_time'),['id' => 'transit_time','class' => 'form-control number-format __transit_time_within', 'placeholder' => 'Enter transit time within','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                    @if($errors->has('transit_time'))
                                        <div class="text text-danger">
                                            {{ $errors->first('transit_time') }}
                                        </div>
                                    @endif
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light-primary">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="delivery_pickup row">
                            <div class="pickup_section delivery_section col-lg-6 mt-1">
                                <a href="javascript:void(0)" class="delevery_section __delivery_next_day trip_delivery">Delivery next day</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-2">
                <div class="card">
                    <div class="col-lg-12 job_view_map" style="height:320px">
                        <div class="kt-portlet">
                            <div class="job_map" id="map"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header pb-50 d-flex justify-content-start">
                                <h4 class="card-title">Pricing calculator</h4>
                                <i data-feather='help-circle' class="mx-50" title="Before calculation, single/tiered price must be added."></i>
                            </div>
                            <div class=" card-body">
                                <div class="border-bottom mb-1"></div>
                                <div class="space_content d-flex justify-content-between align-items-center">
                                    <span>Space</span>
                                    <div class="input-group w-50">
                                        <input class="__input_pricing_cal form-control" type="number">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary">m <sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="muval_fee d-flex justify-content-between p-50 mt-50 common-box-shadow">
                                    <span>calculate at 20%</span>
                                    <span id="__muvalFee" onchange="formatPriceNew(this);">$0</span>
                                </div>
                                <div class="muval_fee d-flex justify-content-between p-50 mt-50 common-box-shadow">
                                    <span>CC processing fee</span>
                                    <span id="__cc_processing_fee">$0</span>
                                </div>
                                <div class="muval_fee d-flex justify-content-between p-50 mt-50 common-box-shadow">
                                    <span>You earn</span>
                                    <span id="__total_profit">$0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        @if(isset($trip))
            @include('layouts.forms.actions', ['buttonTitle' => 'Save and close'])
        @else
            @include('layouts.forms.actions', ['buttonTitle' => 'Save and close', 'buttonSaveAdd' => 'Save and add another trip'])
        @endif
    {{ Form::close() }}
    <!--end::Form-->
    </section>
    <!-- /page content -->
@stop

@section('scripts')
<!-- Promise polyfill script required to use Mapbox GL Geocoder in IE 11 -->
<script src="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
<script src="https://npmcdn.com/@turf/turf/turf.min.js"></script>
<link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
<script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('js/scripts/forms/form-select2.min.js') }}"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>

<script type="application/javascript">

        

        let mapBoxAccessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        let wayPointsCoordinates = [];
        let wayPoints = [];
        let startLat;
        let startLng;
        let endLat;
        let endLng;
        let centerLatLng = [151.202855, -33.864601];

        $(document).ready(function () {

            $("#start_lat, #start_lng, #end_lat, #end_lng").change(function () {
                console.log("before works");
                plotMap();
                console.log("after too ");
            });

            @if(isset($trip) && isset($trip->frequency))
            $("#recurring-trip").prop("checked", true);
            @if($trip->frequency == 'weekly')
            $("input[type='radio'][value='weekly']").prop('checked', true);
            @else
            $("input[type='radio'][value='monthly']").prop('checked', true);
            @endif
            @endif
            $("#recurring-trip").change(function () {
                showRecurring(this.checked);
            });
            showRecurring($("#recurring-trip").prop('checked'));
           
        });

        // Mapbox location search API
        mapboxgl.accessToken = mapBoxAccessToken;
        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            // limit results to Australia
            countries: 'au',
            mapboxgl: mapboxgl,
        });
        geocoder.addTo('#geocoder_start_addr');
        

        var geocoderEnd = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            // limit results to Australia
            countries: 'au',
            mapboxgl: mapboxgl,
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
                    console.log("Start ORIGINAL address selected" ,  startLat , start_addr);
                });

                        // If city does not return from the geo coder context
                        let startCity = document.getElementById("start_city").value;
                if ((startCity == null || startCity == '') && (results.result.text != '')) {
                    document.getElementById("start_city").value = results.result.text;

                    console.log("start address selected");
                }
               
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
                    console.log("End ORIGINAL address selected" , endLat);
                });
                // If city does not return from the geo coder context
                let endCity = document.getElementById("end_city").value;
                if ((endCity == null || endCity == '') && (results.result.text != '')) {
                    document.getElementById("end_city").value = results.result.text;
                
                console.log("End address selected" , endCity);
                }
            }
            var mapContainer = document.getElementById("_toggle_waypoint_section");
            mapContainer.classList.remove("d-none");
            document.getElementById("end_lat").dispatchEvent(event);
        });

        document.querySelector('#geocoder_start_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#start_addr').val();
        document.querySelector('#geocoder_end_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#end_addr').val();

        startLat = document.getElementById("start_lat").value;
        startLng = document.getElementById("start_lng").value;
        endLat = document.getElementById("end_lat").value;
        endLng = document.getElementById("end_lng").value;


        if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
            wayPointsCoordinates.push([startLng, startLat]);
            wayPointsCoordinates.push([endLng, endLat]);
            centerLatLng = [startLng, startLat];
        
        console.log("Push Occured" , wayPointsCoordinates , startLat , startLng);

        }

        if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
            getCoordinates(startLng, startLat, endLng, endLat, wayPointsCoordinates);
        
            console.log(" Different Push Occured" , wayPointsCoordinates , startLat , startLng , endLat , endLng);
        }

        let geocoderSearch = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            countries: 'au',
            mapboxgl: mapboxgl,
        });

        geocoderSearch.addTo('#geocoder_waypoint');
        geocoderSearch.on('result', function (results) {
            var ul = document.getElementById("external-events-listing");
            var li = document.createElement("li");
            document.getElementById("end_addr").value = results.result.place_name;
            var children = ul.children.length;
            li.setAttribute("id", children);
            li.setAttribute("class", 'common-box-shadow d-flex w-100 justify-content-between mt-50 p-1');
            li.setAttribute("data-lng", results.result.center[0]);
            li.setAttribute("data-lat", results.result.center[1]);
            li.setAttribute("data-place", results.result.place_name);
            var button = document.createElement('i');
            button.setAttribute("class", 'm-05');
            button.setAttribute("data-feather", 'trash')
            // button.setAttribute("type", 'button');
            button.setAttribute("onclick", 'removeWaypoint(this)');
            // button.innerHTML = ;
            li.appendChild(document.createTextNode(results.result.place_name));
            li.appendChild(button);
            ul.appendChild(li);
            document.querySelector('#geocoder_waypoint .mapboxgl-ctrl-geocoder--input').value = '';
            var waypointObj = {
                lng: results.result.center[0],
                lat: results.result.center[1],
                place: results.result.place_name
            };
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("class", "waypoint");
            input.setAttribute("name", "waypoint[" + ul.children.length + "]");
            input.setAttribute("value", JSON.stringify(waypointObj));
            document.getElementById("trip-form").appendChild(input);

            console.log("working at waypoint START" , waypointObj);
            plotMap();
            console.log("working at waypoint END" , waypointObj);
            feather.replace();
        });

        $(document).ready(function () {
            $("#form-btn-save").click(function () {
                let returnFlag = true;
                return returnFlag;
            });

            let tieredIndex = {{ isset($trip) && count($trip->laneTieredPrice) > 0 ? count($trip->laneTieredPrice) : 0 }};
            $('.addTieredButton').click(function (e) {
                tieredIndex++;
                let $template = $('#tieredTemplate'),
                    $clone = $template
                        .clone()
                        .removeClass('d-none')
                        .removeAttr('id')
                        .attr('data-tiered-index', tieredIndex)
                        .insertBefore($template);

                // Update the name attributes
                $clone.find('[name="space_start_range"]').attr('name', 'tiered_price[' + tieredIndex + '][space_start_range]').end();
                $clone.find('[name="space_end_range"]').attr('name', 'tiered_price[' + tieredIndex + '][space_end_range]').end();
                $clone.find('[name="price"]').attr('name', 'tiered_price[' + tieredIndex + '][price]').end();
                e.preventDefault();
                return false;
            });

        });

        function formatPrice(obj) {
            // $(obj).formatCurrency();
        }

        function formatPriceNew(obj) {
            $(obj).formatCurrency();
        }

        $(document).ready(function () {
            $(".__input_pricing_cal").change(function () {

                let totalTieredRange = parseInt($('input[name^="tiered_price"]').length / 3);
                let totalSpace = $(this).val();
                let matchedPrice = 0;

                for (let i = 0; i < totalTieredRange; i++) {
                    let spaceStartRange = parseInt($('input[name="tiered_price[' + i + '][space_start_range]"]')[0].value);
                    let spaceEndRange = parseInt($('input[name="tiered_price[' + i + '][space_end_range]"]')[0].value);
                    let spacePrice = parseFloat($('input[name="tiered_price[' + i + '][price]"]')[0].value);

                    if (totalSpace >= spaceStartRange && totalSpace <= spaceEndRange) {
                        matchedPrice = spacePrice;
                    }
                }

                if (matchedPrice == 0) {
                    let minPrice = parseFloat($('input[name="min_price"]').val());
                    matchedPrice = (minPrice > 0) ? minPrice : 0;
                }

                let totalPrice = parseFloat(matchedPrice * totalSpace);
                let muvalFee = parseFloat(totalPrice * 20 / 100).toFixed(2);
                let ccProcessingFee = parseFloat(totalPrice * 1.5 / 100).toFixed(2);
                let totalProfit = parseFloat(totalPrice - muvalFee - ccProcessingFee).toFixed(2);

                $("#__muvalFee").html('$' + muvalFee);
                $("#__cc_processing_fee").html('$' + ccProcessingFee);
                $("#__total_profit").html('$' + totalProfit);
            });
        });
    </script>
@stop
