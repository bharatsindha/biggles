@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.lane')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($lane) ? 'Edit Lane' : 'Add Lane' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">

        <!--begin::Form-->
        @if(isset($lane))
            {{ Form::model($lane, ['route' => ['lane.update', $lane->id], 'id' =>'lane-form', 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
            @method('PATCH')
        @else
            {{ Form::open(['route' => 'lane.store', 'enctype' => "multipart/form-data",'id' =>'lane-form']) }}
        @endif
        @csrf
        {!!  Form::hidden('start_lat', old('start_lat'),['id' => 'start_lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude']) !!}
        {!!  Form::hidden('start_lng', old('start_lng'),['id' => 'start_lng','class' => 'form-control','placeholder' => 'Please enter Longitude']) !!}
        {!!  Form::hidden('start_city', old('start_city'),['id' => 'start_city','class' => 'form-control','placeholder' => 'Please enter start city']) !!}
        {!!  Form::hidden('start_postcode', old('start_postcode'),['id' => 'start_postcode','class' => 'form-control','placeholder' => 'Please enter start postcode']) !!}
        {!!  Form::hidden('end_lat', old('end_lat'),['id' => 'end_lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude']) !!}
        {!!  Form::hidden('end_lng', old('end_lng'),['id' => 'end_lng','class' => 'form-control','placeholder' => 'Please enter Longitude']) !!}
        {!!  Form::hidden('end_city', old('end_city'),['id' => 'end_city','class' => 'form-control','placeholder' => 'Please enter end city']) !!}
        {!!  Form::hidden('end_postcode', old('end_postcode'),['id' => 'end_postcode','class' => 'form-control','placeholder' => 'Please enter end postcode']) !!}
        {!!  Form::hidden('route', old('route'),['id' => 'route','class' => 'form-control']) !!}
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row lane_page_content">
                <div class="col-lg-8">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head transport_head_main">
                            <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap">
                                <h3 class="kt-portlet__head-title">Transport and space</h3>
                                <div class="lane_checkbox d-flex w-100 transport_box">
                                    <div class="lane_checkbox_content lane_checkbox_content_transport  {{ isset($lane->transport) && $lane->transport == 1 ? 'active' : '' }} d-flex">
                                        <input type="radio" name="transport" value="1" {{ isset($lane->transport) && $lane->transport == 1 ? 'checked' : '' }} required>
                                        <span></span>
                                        <p>Truck</p>
                                    </div>
                                    <div class="lane_checkbox_content lane_checkbox_content_transport {{ isset($lane->transport) && $lane->transport == 2 ? 'active' : '' }} d-flex">
                                        <input type="radio" name="transport" value="2" {{ isset($lane->transport) && $lane->transport == 2 ? 'checked' : '' }} required>
                                        <span></span>
                                        <p>Rail</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            @if(\App\Facades\General::isSuperAdmin())
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('common.company') }}
                                            :</label>
                                        {!!  Form::select('company_id', $data['companyOptions'], old('company_id'),['id' => 'company_id','class' => 'form-control', 'placeholder' => 'Please select Company','required' => 'required']) !!}
                                        @if($errors->has('company_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('common.company') }}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row mb-0">
                                <div class="col-lg-6">
                                    <label class="" for="capacity">{{ trans('lane::lane.maximum_space') }}:</label>
                                    <div class="input-group mb-3">
                                        {!! Form::text('capacity', old('capacity'),['id' => 'capacity','class' => 'form-control', 'placeholder' => 'Please enter maximum space','required' => 'required']) !!}
                                        @if($errors->has('capacity'))
                                            <div class="text text-danger">
                                                {{ $errors->first('capacity') }}
                                            </div>
                                        @endif
                                        <span>Maximum space</span>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 __toggle__truck_option">
                                    <label><span class="required"> * </span>{{ trans('move::move.truck') }}:</label>
                                    {!!  Form::select('truck_id', $data['truckOptions'], old('truck_id'),['id' => 'truck_id','class' => 'form-control', 'placeholder' => 'Select truck','required' => 'required']) !!}
                                    @if($errors->has('truck_id'))
                                        <div class="text text-danger">
                                            {{ $errors->first('truck_id') }}
                                        </div>
                                    @endif
                                    <span>Truck</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet">
                        <div class="kt-portlet__head transport_head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Locations</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('lane::lane.start_address') }}:</label>
                                    <div id="geocoder_start_addr"></div>
                                    {!!  Form::hidden('start_addr', old('start_addr'),['id' => 'start_addr','class' => 'form-control', 'placeholder' => 'Enter Start Address']) !!}
                                    @if($errors->has('start_addr'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_addr') }}
                                        </div>
                                    @endif
                                    <span>Start Address</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.end_address') }}:</label>
                                    <div id="geocoder_end_addr"></div>
                                    {!!  Form::hidden('end_addr', old('end_addr'),['id' => 'end_addr','class' => 'form-control', 'placeholder' => 'Please enter End Address']) !!}
                                    @if($errors->has('end_addr'))
                                        <div class="text text-danger">
                                            {{ $errors->first('end_addr') }}
                                        </div>
                                    @endif
                                    <span>End Address</span>
                                </div>
                            </div>

                            <div
                                class="form-group row {{ isset($lane) && !is_null($lane->waypoint) ? 'show' : 'hide' }}"
                                id="_toggle_waypoint_section">

                                <div class="col-lg-12">
                                    <label>{{ trans('trip::trip.add_waypoint') }}:</label>
                                    <div id="geocoder_waypoint"></div>
                                    <input type="hidden">
                                    <span>{{ trans('trip::trip.add_waypoint') }}</span>
                                </div>
                                <div class="col-lg-12 mt-25">
                                    <p>Waypoints:</p>
                                    <ul id='external-events-listing' class="_waypoint_listing">
                                        @if(isset($lane))
                                            @php $waypointsArr = json_decode($lane->waypoint) @endphp
                                            @if(isset($waypointsArr) && !is_null($waypointsArr) && !empty($waypointsArr))
                                                @foreach($waypointsArr as $waypoint)
                                                    @php $waypoint = json_decode($waypoint, true) @endphp
                                                    <li id="0"
                                                        class="fc-event _waypoint_listing_li d-flex w-100 justify-content-between mt-05"
                                                        data-lat="{{ $waypoint['lat'] }}"
                                                        data-lng="{{ $waypoint['lng'] }}"
                                                        data-place="{{ $waypoint['place'] }}">{{ $waypoint['place'] }}
                                                        <button class="btn btn-default d-flex" type="button"
                                                                onclick="removeWaypoint(this)">Remove
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet">
                        <div class="kt-portlet__head transport_head_main">
                            <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap">
                                <h3 class="kt-portlet__head-title">Pricing</h3>
                                <div class="lane_checkbox d-flex w-100">
                                    <div
                                        class="lane_checkbox_content lane_checkbox_content_pricing d-flex __lane_pricing_muval">
                                        <input type="radio" value="single price" class="">
                                        <span></span>
                                        <p>Single price</p>
                                    </div>
                                    <div
                                        class="lane_checkbox_content lane_checkbox_content_pricing d-flex __lane_pricing_muval">
                                        <input type="radio" value="tiered price" class="">
                                        <span></span>
                                        <p>Tiered price</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <p class="price_section d-flrange_sectionex align-items-center"><img
                                    src="{{ asset('assets/media/lane_error.svg') }}"> <span>Did you know that the average price for this lane is <strong>$1500</strong></span>
                            </p>
                            <div class="form-group row mb-0 __lane_single_pricing hide">
                                <div class="col-lg-6 box_space">
                                    <label class="" for="min_price">{{ trans('lane::lane.min_price') }}:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!! Form::text('min_price', old('min_price'),['id' => 'min_price','class' => 'form-control', 'placeholder' => 'Please enter min price','required' => 'required']) !!}
                                        @if($errors->has('min_price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('min_price') }}
                                            </div>
                                        @endif
                                        <span>Price shown to customer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="__lane_tiered_pricing hide">
                                @if(isset($lane) && count($lane->laneTieredPrice) > 0 )
                                    @foreach($lane->laneTieredPrice as $key => $tiredPrice)h
                                    <div class="form-group row mb-0 mt-25">
                                        <div class="col-lg-6">
                                            <label class="" for="price_per">{{ trans('lane::lane.space_range') }}
                                                :</label>
                                            <div class="input-group mb-3">
                                                <input>
                                                {!! Form::text('tiered_price['.$key.'][space_start_range]', $tiredPrice->space_start_range,['class' => 'form-control range_start', 'placeholder' => 'Please enter space start range','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                {!! Form::text('tiered_price['.$key.'][space_end_range]', $tiredPrice->space_end_range,['id' => 'space_end_range','class' => 'form-control range_end', 'placeholder' => 'Please enter space end range','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                @if($errors->has('space_start_range'))
                                                    <div class="text text-danger">
                                                        {{ $errors->first('space_start_range') }}
                                                    </div>
                                                @endif
                                                <span>Space range</span>
                                                <span class="space_between_range">-</span>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"
                                                          id="basic-addon1">m<sup>3</sup></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 box_space ">
                                            <label class="" for="min_price">{{ trans('lane::lane.min_price') }}
                                                :</label>
                                            <div class="input-group mb-3">
                                                {!! Form::text('tiered_price['.$key.'][price]', $tiredPrice->price,['id' => 'price','class' => 'form-control', 'placeholder' => 'Please enter min price','required' => 'required', 'data-tiered-index' => $key]) !!}
                                                @if($errors->has('price'))
                                                    <div class="text text-danger">
                                                        {{ $errors->first('price') }}
                                                    </div>
                                                @endif
                                                <span>Price shown to customer</span>
                                                <span class="fix_content">$</span>
                                            </div>
                                            {!! Form::hidden('tiered_price['.$key.'][id]', $tiredPrice->id,['class' => 'form-control', 'data-tiered-index' => $key]) !!}
                                        </div>
                                        @endforeach
                                        @else
                                            <div class="form-group row mb-0 mt-25">
                                                <div class="col-lg-6 box_space tiered_price_right">
                                                    <label class=""
                                                           for="price_per">{{ trans('lane::lane.space_range') }}
                                                        :</label>
                                                    <div class="input-group mb-3">
                                                        <input>
                                                        {!! Form::text('tiered_price[0][space_start_range]', old('space_start_range'),['class' => 'form-control range_start', 'placeholder' => 'Please enter space start range','required' => 'required', 'data-tiered-index' => '0']) !!}
                                                        <span>From</span>
                                                        {!! Form::text('tiered_price[0][space_end_range]', old('space_end_range'),['id' => 'space_end_range','class' => 'form-control range_end', 'placeholder' => 'Please enter space end range','required' => 'required', 'data-tiered-index' => '0']) !!}
                                                        <span>To</span>
                                                        @if($errors->has('space_start_range'))
                                                            <div class="text text-danger">
                                                                {{ $errors->first('space_start_range') }}
                                                            </div>
                                                        @endif
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="basic-addon1">m<sup>3</sup></span>
                                                        </div>
                                                        <span class="space_between_range">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 box_space">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">$</span>
                                                        </div>
                                                        <label class=""
                                                               for="min_price">{{ trans('lane::lane.min_price') }}
                                                            :</label>
                                                        {!! Form::text('tiered_price[0][price]', old('price'),['id' => 'price','class' => 'form-control', 'placeholder' => 'Please enter min price','required' => 'required', 'data-tiered-index' => '0']) !!}
                                                        @if($errors->has('price'))
                                                            <div class="text text-danger">
                                                                {{ $errors->first('price') }}
                                                            </div>
                                                        @endif
                                                        <span>Price shown to customer</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" data-tiered-index="0" value=""
                                                       name="tiered_price[0][id]"/>
                                            </div>
                                        @endif
                                        <div class="form-group row mb-0 mt-25 hide" id="tieredTemplate">
                                            <div class="col-lg-6 box_space tiered_price_right">
                                                <label class="" for="price_per">{{ trans('lane::lane.space_range') }}
                                                    :</label>
                                                <div class="input-group mb-3">
                                                    <input>
                                                    {!! Form::text('space_start_range', '',['class' => 'form-control range_start', 'placeholder' => 'Please enter space start range', 'data-tiered-index' => '0']) !!}
                                                    <span>From</span>
                                                    {!! Form::text('space_end_range', '',['id' => 'space_end_range','class' => 'form-control range_end', 'placeholder' => 'Please enter space end range', 'data-tiered-index' => '0']) !!}
                                                    @if($errors->has('space_start_range'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('space_start_range') }}
                                                        </div>
                                                    @endif
                                                    <span>To</span>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"
                                                              id="basic-addon1">m<sup>3</sup></span>
                                                    </div>
                                                    <span class="space_between_range">-</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 box_space">
                                                <label class="" for="price">{{ trans('lane::lane.min_price') }}:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">$</span>
                                                    </div>
                                                    {!! Form::text('price', old('price'),['class' => 'form-control', 'placeholder' => 'Please enter min price']) !!}
                                                    @if($errors->has('price'))
                                                        <div class="text text-danger">
                                                            {{ $errors->first('price') }}
                                                        </div>
                                                    @endif
                                                    <span>Price shown to customer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="range_section text-center addTieredButton hide">Add another
                                        range</a>
                            </div>
                        </div>
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">Timing</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="form-group row mb-0">
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('move::move.amount_due') }}
                                            :</label>
                                        <div class="input-group mb-3">
                                            {!!  Form::text('pickup_notice', old('pickup_notice'),['id' => 'pickup_notice','class' => 'form-control', 'placeholder' => 'Enter pickup within','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                            @if($errors->has('pickup_notice'))
                                                <div class="text text-danger">
                                                    {{ $errors->first('pickup_notice') }}
                                                </div>
                                            @endif
                                            <span>Pickup within</span>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('move::move.amount_due') }}
                                            :</label>
                                        <div class="input-group mb-3">
                                            {!!  Form::text('transit_time', old('transit_time'),['id' => 'transit_time','class' => 'form-control number-format __transit_time_within', 'placeholder' => 'Enter transit time','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                            @if($errors->has('transit_time'))
                                                <div class="text text-danger">
                                                    {{ $errors->first('transit_time') }}
                                                </div>
                                            @endif
                                            <span>Transit time within</span>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="delivery_pickup row">
                                    <div class="pickup_section col-lg-6">
                                        <div class="days_main_section">
                                            <h4>Pickup days</h4>
                                            <div class="day_section row">
                                                @foreach($data['pickupDaysArr'] as $pickupDay)
                                                    <div class="day d-flex align-items-center col-lg-6">
                                                    <span
                                                        class="local_circle {{ isset($lane) && !is_null($lane->pickup_days) && in_array($pickupDay, $lane->pickup_days) ? 'active' : '' }}"><input
                                                            type="checkbox" name="pickup_days[]"
                                                            value="{{ $pickupDay }}" {{ isset($lane) && !is_null($lane->pickup_days) && in_array($pickupDay, $lane->pickup_days) ? 'checked' : '' }}> <img
                                                            src="{{ asset('assets/media/right_arrow.svg') }}"></span>
                                                        <span>{{ ucfirst($pickupDay) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pickup_section delivery_section col-lg-6">
                                        <a href="javascript:void(0)" class="delevery_section __delivery_next_day">Delivery
                                            next day</a>
                                        <div class="days_main_section">
                                            <h4>Delivery days</h4>
                                            <div class="day_section row">
                                                @foreach($data['pickupDaysArr'] as $pickupDay)
                                                    <div class="day d-flex align-items-center col-lg-6">
                                                    <span
                                                        class="local_circle {{ isset($lane) && !is_null($lane->delivery_days) && in_array($pickupDay, $lane->delivery_days) ? 'active' : '' }}"><input
                                                            type="checkbox" name="delivery_days[]"
                                                            value="{{ $pickupDay }}" {{ isset($lane) && !is_null($lane->delivery_days) && in_array($pickupDay, $lane->delivery_days) ? 'checked' : '' }}> <img
                                                            src="{{ asset('assets/media/right_arrow.svg') }}"></span>
                                                        <span>{{ ucfirst($pickupDay) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
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
                    <div class="col-lg-4">
                        <div class="col-lg-12 job_view_map" style="height:320px">
                            <div class="kt-portlet">
                                <div class="job_map" id="map"></div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Pricing calculator
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
                                    <div class="muval_fee d-flex justify-content-between">
                                        <span>calculate at 20%</span>
                                        <span id="__muvalFee" onchange="formatPriceNew(this);">$0</span>
                                    </div>
                                    <div class="muval_fee d-flex justify-content-between">
                                        <span>CC processing fee</span>
                                        <span id="__cc_processing_fee">$0</span>
                                    </div>
                                    <div class="muval_fee d-flex justify-content-between">
                                        <span>You earn</span>
                                        <span id="__total_profit">$0</span>
                                    </div>
                                </div>
                            </div><!--end::Portlet-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin:: Content -->
        {{ Form::close() }}
        <!--end::Form-->
        </div>
        <!-- /page content -->
        @stop

        @section('scripts')

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
            <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
            <script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>
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

                            // If city does not return from the geo coder context
                            let startCity = document.getElementById("start_city").value;
                            if ((startCity == null || startCity == '') && (results.result.text != '')) {
                                document.getElementById("start_city").value = results.result.text;
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

                startLat = document.getElementById("start_lat").value;
                startLng = document.getElementById("start_lng").value;
                endLat = document.getElementById("end_lat").value;
                endLng = document.getElementById("end_lng").value;


                if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
                    wayPointsCoordinates.push([startLng, startLat]);
                    wayPointsCoordinates.push([endLng, endLat]);
                    centerLatLng = [startLng, startLat];
                }

                if (startLat !== null && startLng !== null && endLat !== null && endLng !== null && startLat !== "" && startLng !== "" && endLat !== "" && endLng !== "") {
                    getCoordinates(startLng, startLat, endLng, endLat, wayPointsCoordinates);
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
                    document.getElementById("lane-form").appendChild(input);
                    plotMap();
                });

                $(document).ready(function () {
                    $("#form-btn-save").click(function () {
                        let returnFlag = true;
                        return returnFlag;
                    });

                    let tieredIndex = {{ isset($lane) && count($lane->laneTieredPrice) > 0 ? count($lane->laneTieredPrice) : 0 }};
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

                            if (totalSpace >= spaceStartRange || totalSpace <= spaceEndRange) {
                                matchedPrice = spacePrice;
                            }
                        }

                        if (matchedPrice == 0) {
                            let minPrice = parseFloat($('input[name="min_price"]').attr('value'));
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
