@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.lane')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.lane'),
    'subTitle' => isset($lane) ? trans('common.edit'). ' '. trans('common.lane') : trans('common.add').' '. trans('common.lane') ,
    'moduleLink' => route($moduleName.'.index')
])
@stop




{{-- Below code is Newer One --}}
@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->
        @if(isset($lane))
        {{ Form::model($lane, [
        'route' => [$moduleName.'.update', $lane->id],
        'method' => 'patch',
        'class' => 'form-validate'
        ]) }}
        @else
        {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
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
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
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

                        <div class="row">
                            @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level @endphp
                            @if($userAccess != 1)
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

{{-- Location starts --}}

                <div class="card">
                    <div class="card-body">
                        <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap">
                            <h3 class="kt-portlet__head-title">Location</h3>
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
              
                

                            <div class="kt-portlet__head transport_head_main">
                                <div class="kt-portlet__head-label w-100 justify-content-between flex-wrap">
                                    <h3 class="kt-portlet__head-title">Customer Pricing</h3>
                                    <div class="lane_checkbox d-flex w-100">
                                        {{--checked="{{ (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'true' : 'false' }}"--}}
                                        <div class="lane_checkbox_content {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'active' : '' }} lane_checkbox_content_pricing d-flex __lane_pricing_muval">
                                            <input type="radio" value="single" class="" name="price_type" {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'single') ? 'checked' : '' }} required>
                                            <span></span>
                                            <p>Single price</p>
                                        </div>
                                        {{--checked="{{ (isset($lane->laneTieredPrice) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'true' : 'false' }}"--}}
                                        <div class="lane_checkbox_content {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'active' : '' }} lane_checkbox_content_pricing d-flex __lane_pricing_muval">
                                            <input type="radio" value="tiered" class="" name="price_type" {{ (isset($lane->laneTieredPrice[0]) && $lane->laneTieredPrice[0]->price_type == 'tiered') ? 'checked' : '' }} required><span></span>
                                            <p>Tiered price</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                  
              
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
