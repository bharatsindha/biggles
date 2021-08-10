@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($move) ? 'Edit job' : 'Add job' ])
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

                        <!--begin::Form-->
                        @if(isset($move))
                            {{ Form::model($move, ['route' => ['move.update', $move->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'move.store']) }}
                        @endif
                        @csrf
                        <div class="kt-portlet__body kt-move__body">
                            {!!  Form::hidden('start_lat', old('start_lat'),['id' => 'start_lat','class' => 'form-control','placeholder' => 'Please enter Start Latitude','step'=>"any"]) !!}
                            {!!  Form::hidden('start_lng', old('start_lng'),['id' => 'start_lng','class' => 'form-control','placeholder' => 'Please enter Start Longitude','step'=>"any"]) !!}
                            {!!  Form::hidden('end_lat', old('end_lat'),['id' => 'end_lat','class' => 'form-control','placeholder' => 'Please enter End Latitude', 'step'=>"any"]) !!}
                            {!!  Form::hidden('end_lng', old('end_lng'),['id' => 'end_lng','class' => 'form-control','placeholder' => 'Please enter End Longitude', 'step'=>"any"]) !!}

                            @if(\App\Facades\General::isSuperAdmin())
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('move::move.company') }}
                                            :</label>
                                        {!!  Form::select('company_id', $data['companyOptions'], old('company_id'),['id' => 'company_id','class' => 'form-control', 'placeholder' => 'Please select Company','required' => 'required']) !!}
                                        @if($errors->has('company_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                        <span>Company</span>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label><span class="required"> * </span>{{ trans('move::move.customer') }}:</label>
                                    {!!  Form::select('customer_id', $data['customerId'], old('customer_id'),['id' => 'customer_id','class' => 'form-control', 'placeholder' => 'Please select Customer','required' => 'required']) !!}
                                    @if($errors->has('customer_id'))
                                        <div class="text text-danger">
                                            {{ $errors->first('customer_id') }}
                                        </div>
                                    @endif
                                    <span>Customer</span>
                                </div>
                                <div class="col-lg-6">
                                    <label><span class="required"> * </span>{{ trans('move::move.stage') }}:</label>
                                    {!!  Form::select('stage', $data['stageOptions'], old('stage'),['id' => 'stage','class' => 'form-control', 'placeholder' => 'Please select Stage','required' => 'required']) !!}
                                    @if($errors->has('stage'))
                                        <div class="text text-danger">
                                            {{ $errors->first('stage') }}
                                        </div>
                                    @endif
                                    <span>Stage</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class=""><span class="required"> * </span>{{ trans('move::move.type') }}
                                        :</label>
                                    {!!  Form::select('type', $data['typeOptions'], old('type'),['id' => 'type','class' => 'form-control','placeholder' => 'Please select Type','required' => 'required']) !!}
                                    @if($errors->has('type'))
                                        <div class="text text-danger">
                                            {{ $errors->first('type') }}
                                        </div>
                                    @endif
                                    <span>Type</span>
                                </div>
                                <div class="col-lg-6">
                                    <label><span class="required"> * </span>{{ trans('move::move.status') }}:</label>
                                    {!!  Form::select('status', $data['statusOptions'], old('status'),['id' => 'status','class' => 'form-control', 'placeholder' => 'Please select Status','required' => 'required']) !!}
                                    @if($errors->has('status'))
                                        <div class="text text-danger">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                    <span>Status</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.start_address') }}:</label>
                                    <div id="geocoder_start_addr"></div>
                                    {!!  Form::hidden('start_addr', old('start_addr'),['id' => 'start_addr','class' => 'form-control', 'placeholder' => 'Please enter Start Address']) !!}
                                    @if($errors->has('start_addr'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_addr') }}
                                        </div>
                                    @endif
                                    <span>Start Address</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="" for="start_city">{{ trans('lane::lane.start_city') }}:</label>
                                    {!! Form::text('start_city', old('start_city'),['id' => 'start_city','class' => 'form-control', 'placeholder' => 'Please enter Start City','required' => 'required']) !!}
                                    @if($errors->has('start_city'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_city') }}
                                        </div>
                                    @endif
                                    <span>Start City</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="" for="start_postcode">{{ trans('lane::lane.start_postcode') }}
                                        :</label>
                                    {!!  Form::text('start_postcode', old('start_postcode'),['id' => 'start_postcode','class' => 'form-control','placeholder' => 'Please enter Start Postcode','required' => 'required']) !!}
                                    @if($errors->has('start_postcode'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_postcode') }}
                                        </div>
                                    @endif
                                    <span>Start Postcode</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::move.start_access') }}:</label>
                                    {!!  Form::text('start_access', old('start_access'),['id' => 'start_access','class' => 'form-control','placeholder' => 'Please enter Start Access']) !!}
                                    @if($errors->has('start_access'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_access') }}
                                        </div>
                                    @endif
                                    <span>Start Access</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.start_date') }}:</label>
                                    {!!  Form::text('start_date', old('start_date'),['id' => 'start_date','class' => 'form-control commonDatepicker', 'placeholder' => 'Please enter Start Date']) !!}
                                    @if($errors->has('start_date'))
                                        <div class="text text-danger">
                                            {{ $errors->first('start_date') }}
                                        </div>
                                    @endif
                                    <span>Start Date</span>
                                </div>
                            </div>
                            <div class="form-group row">
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
                                <div class="col-lg-6">
                                    <label class="" for="end_city">{{ trans('lane::lane.end_city') }}:</label>
                                    {!! Form::text('end_city', old('end_city'),['id' => 'end_city','class' => 'form-control', 'placeholder' => 'Please enter End City','required' => 'required']) !!}
                                    @if($errors->has('end_city'))
                                        <div class="text text-danger">
                                            {{ $errors->first('end_city') }}
                                        </div>
                                    @endif
                                    <span>End City</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="" for="end_postcode">{{ trans('lane::lane.end_postcode') }}
                                        :</label>
                                    {!! Form::text('end_postcode', old('end_postcode'),['id' => 'end_postcode','class' => 'form-control','placeholder' => 'Please enter End Postcode','required' => 'required']) !!}
                                    @if($errors->has('end_postcode'))
                                        <div class="text text-danger">
                                            {{ $errors->first('end_postcode') }}
                                        </div>
                                    @endif
                                    <span>End Postcode</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::move.end_access') }}:</label>
                                    {!!  Form::text('end_access', old('end_access'),['id' => 'end_access','class' => 'form-control','placeholder' => 'Please enter End Access']) !!}
                                    @if($errors->has('end_access'))
                                        <div class="text text-danger">
                                            {{ $errors->first('end_access') }}
                                        </div>
                                    @endif
                                    <span>End Access</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.end_date') }}:</label>
                                    {!!  Form::text('end_date', old('end_date'),['id' => 'end_date','class' => 'form-control commonDatepicker', 'placeholder' => 'Please enter End Postal Code']) !!}
                                    @if($errors->has('end_date'))
                                        <div class="text text-danger">
                                            {{ $errors->first('end_date') }}
                                        </div>
                                    @endif
                                    <span>End date</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label>{{ trans('move::move.customer_analytics') }}:</label>
                                    {!! Form::textarea('customer_analytics', old('customer_analytics'), ['class'=>'form-control','id' => 'customer_analytics', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Customer Analytics']) !!}
                                    @if($errors->has('customer_analytics'))
                                        <div class="text text-danger">
                                            {{ $errors->first('customer_analytics') }}
                                        </div>
                                    @endif
                                    <span>Customer Analytics</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 box_space">
                                    <label><span class="required"> * </span>{{ trans('move::move.total_price') }}
                                        :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('total_price', old('total_price'),['id' => 'total_price','class' => 'form-control number-format', 'placeholder' => 'Please enter Total Price','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('total_price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('total_price') }}
                                            </div>
                                        @endif
                                        <span>Total Price</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label><span class="required"> * </span>{{ trans('move::move.amount_due') }}
                                        :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('amount_due', old('amount_due'),['id' => 'amount_due','class' => 'form-control number-format', 'placeholder' => 'Please enter Amount Due','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('amount_due'))
                                            <div class="text text-danger">
                                                {{ $errors->first('amount_due') }}
                                            </div>
                                        @endif
                                        <span>Amount Due</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 box_space">
                                    <label class=""><span class="required"> * </span>{{ trans('move::move.deposit') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('deposit', old('deposit'),['id' => 'deposit','class' => 'form-control number-format','placeholder' => 'Please enter Deposit','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('deposit'))
                                            <div class="text text-danger">
                                                {{ $errors->first('deposit') }}
                                            </div>
                                        @endif
                                        <span>Enter Deposit</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label><span class="required"> * </span>{{ trans('move::move.fee') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('fee', old('fee'),['id' => 'fee','class' => 'form-control number-format', 'placeholder' => 'Please enter Total Price','required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('fee'))
                                            <div class="text text-danger">
                                                {{ $errors->first('fee') }}
                                            </div>
                                        @endif
                                        <span>Total Price</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 box_space">
                                    <label><span class="required"> * </span>{{ trans('move::move.space') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::number('space', old('space'),['id' => 'space','class' => 'form-control _fix_input_prefix', 'placeholder' => 'Please enter Space','required' => 'required', 'step'=>"any"]) !!}
                                        @if($errors->has('space'))
                                            <div class="text text-danger">
                                                {{ $errors->first('space') }}
                                            </div>
                                        @endif
                                        <span>Space</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::move.matches') }}:</label>
                                    {!! Form::textarea('matches', old('matches'), ['class'=>'form-control','id' => 'matches', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Matches']) !!}
                                    @if($errors->has('matches'))
                                        <div class="text text-danger">
                                            {{ $errors->first('matches') }}
                                        </div>
                                    @endif
                                    <span>Matches</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.inventory') }}:</label>
                                    {!! Form::textarea('inventory', old('inventory'), ['class'=>'form-control','id' => 'inventory', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Inventory']) !!}
                                    @if($errors->has('inventory'))
                                        <div class="text text-danger">
                                            {{ $errors->first('inventory') }}
                                        </div>
                                    @endif
                                    <span>Inventory</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class=""><span
                                            class="required"> * </span>{{ trans('move::move.dwelling_type') }}:</label>
                                    {!!  Form::select('dwelling_type', $data['dwellingTypeOptions'], old('dwelling_type'),['id' => 'dwelling_type','class' => 'form-control','placeholder' => 'Please enter Dwelling Type','required' => 'required']) !!}
                                    @if($errors->has('dwelling_type'))
                                        <div class="text text-danger">
                                            {{ $errors->first('dwelling_type') }}
                                        </div>
                                    @endif
                                    <span>Dwelling Type</span>

                                </div>
                                <div class="col-lg-6">
                                    <label><span class="required"> * </span>{{ trans('move::move.dwelling_size') }}
                                        :</label>
                                    {!!  Form::select('dwelling_size', $data['dwellingSizeOptions'], old('dwelling_size'),['id' => 'dwelling_size','class' => 'form-control', 'placeholder' => 'Please enter Dwelling Size','required' => 'required']) !!}
                                    @if($errors->has('dwelling_size'))
                                        <div class="text text-danger">
                                            {{ $errors->first('dwelling_size') }}
                                        </div>
                                    @endif
                                    <span>Dwelling Size</span>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.pickup_window_start') }}:</label>
                                    {!!  Form::text('pickup_window_start', old('pickup_window_start'),['id' => 'pickup_window_start','class' => 'form-control commonDatepicker', 'placeholder' => 'Please enter Pickup Window Start Date']) !!}
                                    @if($errors->has('pickup_window_start'))
                                        <div class="text text-danger">
                                            {{ $errors->first('pickup_window_start') }}
                                        </div>
                                    @endif
                                    <span>Window Start Date</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::move.pickup_window_end') }}:</label>
                                    {!!  Form::text('pickup_window_end', old('pickup_window_end'),['id' => 'pickup_window_end','class' => 'form-control commonDatepicker','placeholder' => 'Please enter Pickup Window End Date']) !!}
                                    @if($errors->has('pickup_window_end'))
                                        <div class="text text-danger">
                                            {{ $errors->first('pickup_window_end') }}
                                        </div>
                                    @endif
                                    <span>Window End Date</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::move.delivery_window_start') }}:</label>
                                    {!!  Form::text('delivery_window_start', old('delivery_window_start'),['id' => 'delivery_window_start','class' => 'form-control commonDatepicker', 'placeholder' => 'Please enter Delivery Window Start Date']) !!}
                                    @if($errors->has('delivery_window_start'))
                                        <div class="text text-danger">
                                            {{ $errors->first('delivery_window_start') }}
                                        </div>
                                    @endif
                                    <span>Delivery Window Start Date</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::move.delivery_window_end') }}:</label>
                                    {!!  Form::text('delivery_window_end', old('delivery_window_end'),['id' => 'delivery_window_end','class' => 'form-control commonDatepicker','placeholder' => 'Please enter Delivery Window End Date']) !!}
                                    @if($errors->has('delivery_window_end'))
                                        <div class="text text-danger">
                                            {{ $errors->first('delivery_window_end') }}
                                        </div>
                                    @endif
                                    <span>Delivery Window End Date</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 permission_label">
                                    <label>Ancillary Services:</label> <br/>
                                    @if(isset($data['ancillaryServices']))
                                        @foreach($data['ancillaryServices'] as $ancillary)
                                            @php
                                                $selected =false
                                            @endphp
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                @if (isset($move) && isset($move->ancillaryServices) && $move->ancillaryServices->contains($ancillary))
                                                    @php
                                                        $selected = true
                                                    @endphp
                                                @endif
                                                {!!  Form::checkbox('ancillaryServices[]', $ancillary->id, $selected, ['id' => 'ancillaryServices_'.$ancillary->id,'class' => 'form-control']) !!} {{ $ancillary->type_val  }}
                                                <span></span>
                                            </label>
                                            <br/>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @include('layouts.forms.actions')

                    {{ Form::close() }}

                    <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
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
    </style>

@stop


