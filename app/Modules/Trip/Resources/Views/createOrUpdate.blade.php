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
                'class' => 'form-validate'
                ]) }}
        @else
            {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
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
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body kt-portlet__head transport_head_main">
                        <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap ">
                            <h3 class="kt-portlet__head-title mb-1">Transport and space</h3>
                            <div class="lane_checkbox d-flex w-100 transport_box mb-1">                        
                                <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport 
                                {{ isset($trip->transport) && $trip->transport == 1 ? 'active' : '' }}">
                                    <input class="form-check-input" type="radio" name="transport" id="inlineRadio1" value="1" 
                                    {{ isset($trip->transport) && $trip->transport == 1 ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="inlineRadio1">Truck</label>
                                </div>
                                <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport 
                                {{ isset($trip->transport) && $trip->transport == 2 ? 'active' : '' }}">
                                    <input class="form-check-input" type="radio" name="transport" id="inlineRadio111" value="2" 
                                    {{ isset($trip->transport) && $trip->transport == 2 ? 'checked' : '' }} required />
                                    <label class="form-check-label" for="inlineRadio111">Rail</label>
                                </div>
                            </div>
                        </div>

                        @if(\App\Facades\General::isSuperAdmin())
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-1">
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
                                    <div class="input-group mb-3">
                                        {!! Form::text('capacity', old('capacity'),['id' => 'capacity','class' => 'form-control '. (($errors->has('heavy_items')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter truck capacity']) !!}
                                        @if($errors->has('capacity'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('capacity') }}
                                            </div>
                                        @endif
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary" id="basic-addon1">m<sup>3</sup></span>
                                        </div>
                                    </div>
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
                    <div class="card-body">
                        <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap">
                            <h3 class="kt-portlet__head-title mb-1">Location</h3>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-1">
                                <label class="form-label" for="address">
                                    {{ trans('lane::lane.start_address') }}
                                </label>
                                {!!  Form::text('start_addr', old('start_addr'),[
                                    'id' => 'geocoder_start_addr',
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
                                <label class="form-label" for="address">
                                    {{ trans('lane::lane.end_address') }}
                                </label>
                                {!!  Form::text('end_addr', old('end_addr'),[
                                    'id' => 'geocoder_end_addr',
                                    'class' => 'form-control '. (($errors->has('end_addr')) ? 'is-invalid' : ''),
                                    'placeholder' => 'Please enter Start Address'
                                    ]) !!}
                                @if($errors->has('end_addr'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('end_addr') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
  
                <div class="card">
                    <div class="card-body">
                            <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap ">
                                <h3 class="kt-portlet__head-title mb-1">Customer Pricing</h3>
                                <div class="lane_checkbox d-flex w-100 transport_box">                        
                                    <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport 
                                    {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'active' : '' }}">
                                        <input class="form-check-input" type="radio" name="price_type" id="inlineRadio2" value="single" 
                                        {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'checked' : '' }} required />
                                        <label class="form-check-label" for="inlineRadio1">Single price</label>
                                    </div>
                                    <div class="form-check form-check-inline lane_checkbox_content lane_checkbox_content_transport 
                                    {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'active' : '' }}">
                                        <input class="form-check-input" type="radio" name="price_type" id="inlineRadio2" value="tired" 
                                        {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'checked' : '' }} required />
                                        <label class="form-check-label" for="inlineRadio1">Tiered price</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2">
                                <blockquote class="border-start-3 price_section d-flex align-items-center border-start-primary bg-light-primary">
                                    <span><i data-feather='alert-circle' class="m-1"></i>Did you know that the average price for this lane is <strong>$1500</strong></span>
                                </blockquote>
                            </div>
                        <div class="row">
                            <div class="form-group row mb-0 __lane_single_pricing {{ (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'show' : 'hide' }}">

                                <div class="col-lg-6 box_space">
                                    <label class="" for="min_price">{{ trans('lane::lane.min_price') }}:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light-primary" id="basic-addon1">$</span>
                                        </div>
                                        {!! Form::text('min_price', (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'single' && isset($lane->laneTieredPrice[0]->price)) ? $lane->laneTieredPrice[0]->price : old('min_price'),['id' => 'min_price','class' => 'form-control '. (($errors->has('heavy_items')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter min price']) !!}
                                        @if($errors->has('min_price'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('min_price') }}
                                            </div>
                                        @endif
                                        @if(isset($lane) && count($lane->laneTieredPrice) > 0 && $lane->laneTieredPrice[0]->price_type == 'single')
                                        {!! Form::hidden('single_price_id', $lane->laneTieredPrice[0]->id,['class' => 'form-control']) !!}
                                            @endif
                                    </div>
                                </div>
                            </div>
              
                        </div>
                        {{-- Another section of input is left yet --}}
                    </div>
                </div>
       
           
                <div class="card">
                    <div class="card-body">
                            <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap ">
                                <h3 class="kt-portlet__head-title mb-1">Timing</h3>
                            </div>
                        <div class="row">
                         <div class="col-lg-6 box_space">
                            <label class="" for="departure_date">{{ trans('trip::trip.departure_date') }}:</label>
                            {{-- It has some problems --}}
                            <div class="input-group mb-3">
                                {!! Form::text('start_date', old('start_date'),[ 'autocomplete'=>"off",'id' => 'start_date','class' => 'form-control flatpicker-basic flatpicker-input active'. (($errors->has('start_date')) ? 'is-invalid' : ''), 'placeholder' => 'Enter Departure date']) !!}
                                @if($errors->has('start_date'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('start_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 box_space">
                            <label class="" for="delivery_within">{{ trans('trip::trip.delivery_within') }}:</label>
                            <div class="input-group mb-3">
                                {!! Form::text('delivery_within', old('delivery_within'),['id' => 'delivery_within','class' => 'form-control '. (($errors->has('delivery_within')) ? 'is-invalid' : ''), 'placeholder' => 'Enter Delivery date']) !!}
                                @if($errors->has('delivery_within'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('delivery_within') }}
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>{{ trans('trip::trip.recurring') }}</label>
                                <div class="col-3">
                                    <span class="switch">
                                        <label style="display: block;">
                                            <input name="recurring_trip" id="recurring-trip" type="checkbox"
                                                  class="form-check-input" name="recurring">
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
                                        <label class="" for="recurrence_expiry">{{ trans('trip::trip.recurrence_expiry') }}:</label>
                                        {{-- It has some problems --}}
                                        <div class="input-group mb-3">
                                            {!! Form::text('expiry_date', old('expiry_date'),[ 'autocomplete'=>"off",'id' => 'expiry_date','class' => 'form-control flatpicker-basic flatpicker-input active'. (($errors->has('expiry_date')) ? 'is-invalid' : ''), 'placeholder' => 'Please select Recurrence Expiry']) !!}
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

                        <div class="form-group row mb-0">
                            {{--<div class="col-lg-6">
                                <label><span class="required"> * </span>{{ trans('move::move.amount_due') }}
                                    :</label>
                                {!!  Form::text('pickup_notice', old('pickup_notice'),['id' => 'pickup_notice','class' => 'form-control', 'placeholder' => 'Enter pickup within','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                @if($errors->has('pickup_notice'))
                                    <div class="text text-danger">
                                        {{ $errors->first('pickup_notice') }}
                                    </div>
                                @endif
                                <span>Pickup within</span>
                                <span class="input_last">Days</span>
                            </div>--}}
                            <div class="col-lg-6 mt-2">
                                <label><span class="required"> * </span>{{ trans('move::move.amount_due') }}
                                    :</label>
                                <div class="input-group">
                                    {!!  Form::text('transit_time', old('transit_time'),['id' => 'transit_time','class' => 'form-control number-format __transit_time_within', 'placeholder' => 'Enter transit time within','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                    @if($errors->has('transit_time'))
                                        <div class="text text-danger">
                                            {{ $errors->first('transit_time') }}
                                        </div>
                                    @endif
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light-primary" id="basic-addon1">Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="delivery_pickup row">
                            {{--<div class="pickup_section col-lg-6">
                                <div class="days_main_section">
                                    <h4>Pickup days</h4>
                                    <div class="day_section row">
                                        @foreach($data['pickupDaysArr'] as $pickupDay)
                                            <div class="day d-flex align-items-center col-lg-6">
                                                <span
                                                    class="local_circle {{ isset($trip) && !is_null($trip->pickup_days) && in_array($pickupDay, $trip->pickup_days) ? 'active' : '' }}"><input
                                                        type="checkbox" name="pickup_days[]"
                                                        value="{{ $pickupDay }}" {{ isset($trip) && !is_null($trip->pickup_days) && in_array($pickupDay, $trip->pickup_days) ? 'checked' : '' }}> <img
                                                        src="{{ asset('assets/media/right_arrow.svg') }}"></span>
                                                <span>{{ ucfirst($pickupDay) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>--}}
                            <div class="pickup_section delivery_section col-lg-6 mt-1">
                                <a href="javascript:void(0)" class="delevery_section __delivery_next_day trip_delivery">Delivery next day</a>
                                {{--<div class="days_main_section">
                                    <h4>Delivery days</h4>
                                    <div class="day_section row">
                                        @foreach($data['pickupDaysArr'] as $pickupDay)
                                            <div class="day d-flex align-items-center col-lg-6">
                                                <span
                                                    class="local_circle {{ isset($trip) && !is_null($trip->delivery_days) && in_array($pickupDay, $trip->delivery_days) ? 'active' : '' }}"><input
                                                        type="checkbox" name="delivery_days[]"
                                                        value="{{ $pickupDay }}" {{ isset($trip) && !is_null($trip->delivery_days) && in_array($pickupDay, $trip->delivery_days) ? 'checked' : '' }}> <img
                                                        src="{{ asset('assets/media/right_arrow.svg') }}"></span>
                                                <span>{{ ucfirst($pickupDay) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>--}}
                            </div>
                        </div>

                    </div>
                </div>
                @if(isset($lane))
                @php $waypointsArr = json_decode($lane->waypoint) @endphp
                @if(isset($waypointsArr) && !is_null($waypointsArr))
                    @foreach($waypointsArr as $key => $waypoint)
                        <input type="hidden" class="waypoint" name="waypoint[{{ $key }}]"
                               value="{{ $waypoint }}">
                    @endforeach
                @endif
                @endif

                @if(isset($lane))
                    @include('layouts.forms.actions', ['buttonTitle' => 'Save and close'])
                @else
                    @include('layouts.forms.actions', ['buttonTitle' => 'Save and close', 'buttonSaveAdd' => 'Save and add another lane'])
                @endif

              
            
            </div>
            <div class="col-lg-4 order-lg-2">
                <div class="card">
                <div class="col-lg-12 job_view_map" style="height:320px">
                    <div class="kt-portlet">
                        <div class="job_map" id="map"></div>
                    </div>
                </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                         <!--begin::Portlet-->
                        <div class=" card-body kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title mb-1">Pricing calculator
                                        <i class="fa fa-question-circle" title="Before calculation, single/tiered price must be added." aria-hidden="true"></i></h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="space_content d-flex justify-content-between align-items-center">
                                    <span>Space</span>
                                    <div class="space_right">
                                        <span><input class="__input_pricing_cal" type="number"></span>
                                        <span>m3</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>calculate at 20%</span>
                                    <span id="" onchange="formatPriceNew(this);">$0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>CC processing fee</span>
                                    <span id="">$0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>You earn</span>
                                    <span id="">$0</span>
                                </div>
                            </div>
                        </div><!--end::Portlet-->
                    </div>
                </div>
              </div>
        </div>

    {{ Form::close() }}
    <!--end::Form-->
    </section>
    <!-- /page content -->
@stop

@section('scripts')




<script src="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.js"></script>

<script
    src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    
    
<!-- Promise polyfill script required to use Mapbox GL Geocoder in IE 11 -->
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
<script src="https://npmcdn.com/@turf/turf/turf.min.js"></script>
<link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
<script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>

<script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.min.js') }}"></script>


    <script type="application/javascript">
        var mapBoxAccessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        var wayPointsCoordinates = [];
        var wayPoints = [];
        var startLat;
        var startLng;
        var endLat;
        var endLng;
        var centerLatLng = [151.202855, -33.864601];
        $(document).ready(function () {
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
            $("#start_lat, #start_lng, #end_lat, #end_lng").change(function () {
                plotMap();
            });
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
                });

                // If city does not return from the geo coder context
                let startCity = document.getElementById("start_city").value;
                if ((startCity == null || startCity == '') && (results.result.text != '')) {
                    document.getElementById("start_city").value = results.result.text;
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
                });

                // If city does not return from the geo coder context
                let endCity = document.getElementById("end_city").value;
                if ((endCity == null || endCity == '') && (results.result.text != '')) {
                    document.getElementById("end_city").value = results.result.text;
                }
            }
            var mapContainer = document.getElementById("_toggle_waypoint_section");
            mapContainer.classList.remove("hide");
            document.getElementById("end_lat").dispatchEvent(event);
        });
        document.querySelector('#geocoder_start_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#start_addr').val();
        document.querySelector('#geocoder_end_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#end_addr').val();
        var startLat = document.getElementById("start_lat").value;
        var startLng = document.getElementById("start_lng").value;
        var endLat = document.getElementById("end_lat").value;
        var endLng = document.getElementById("end_lng").value;
        if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
            wayPointsCoordinates.push([startLng, startLat]);
            wayPointsCoordinates.push([endLng, endLat]);
            centerLatLng = [startLng, startLat];
        }
        if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
            getCoordinates(startLng, startLat, endLng, endLat, wayPointsCoordinates);
        }
        var geocoderSearch = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            countries: 'au',
            mapboxgl: mapboxgl,
        });
        // geocoderSearch.addTo('#geocoder_waypoint');
        geocoderSearch.on('result', function (results) {
            var ul = document.getElementById("external-events-listing");
            var li = document.createElement("li");
            document.getElementById("end_addr").value = results.result.place_name;
            var children = ul.children.length;
            li.setAttribute("id", children);
            li.setAttribute("class", '_waypoint_listing_li d-flex w-100 justify-content-between mt-05');
            li.setAttribute("data-lng", results.result.center[0]);
            li.setAttribute("data-lat", results.result.center[1]);
            li.setAttribute("data-place", results.result.place_name);
            var button = document.createElement('button');
            button.setAttribute("class", 'btn btn-default d-flex');
            button.setAttribute("type", 'button');
            button.setAttribute("onclick", 'removeWaypoint(this)');
            button.innerHTML = 'Remove';
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
            plotMap();
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
                        .removeClass('hide')
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
