@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.depot')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($move) ? 'Edit depot' : 'Add depot' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">

                        <!--begin::Form-->
                        @if(isset($depot))
                            {{ Form::model($depot, ['route' => ['depot.update', $depot->id], 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
                            @method('PATCH')
                        @else
                            {{ Form::open(['route' => 'depot.store', 'enctype' => "multipart/form-data"]) }}
                        @endif
                        @csrf
                        <div class="kt-portlet__body kt-move__body">
                            {!! Form::hidden('lat', old('lat'),['id' => 'lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude','required' => 'required']) !!}
                            {!!  Form::hidden('lng', old('lng'),['id' => 'lng','class' => 'form-control','placeholder' => 'Please enter Longitude','required' => 'required']) !!}
                            <div class="form-group row">
                                @if(\App\Facades\General::isSuperAdmin())
                                    <div class="col-lg-6">
                                        <label>{{ trans('depot::depot.company') }}:</label>
                                        {!!  Form::select('company_id', $companies,old('company_id'), ['id' => 'company_id','class' => 'form-control' ]) !!}
                                        @if($errors->has('company_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                        <span>Company</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('depot::depot.name') }}:</label>
                                    {!! Form::text('name', old('name'), ['class'=>'form-control','id' => 'name', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Depot Name','required' => 'required']) !!}
                                    @if($errors->has('name'))
                                        <div class="text text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                    <span>Depot Name</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('depot::depot.address') }}:</label>
                                    <div id="geocoder_addr"></div>
                                    {!! Form::hidden('addr', old('addr'), ['class'=>'form-control','id' => 'addr', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Address','required' => 'required']) !!}
                                    @if($errors->has('addr'))
                                        <div class="text text-danger">
                                            {{ $errors->first('addr') }}
                                        </div>
                                    @endif
                                    <span>Address</span>
                                </div>
                            </div>
                             <div class="form-group row">
                                <div class="col-lg-6">
                                    {!! Form::hidden('city', old('city'),['id' => 'city','class' => 'form-control', 'placeholder' => 'City','required' => 'required']) !!}
                                </div>
                                <div class="col-lg-6">
                                    {!!  Form::hidden('postcode', old('postcode'),['id' => 'postcode','class' => 'form-control','Postcode']) !!}
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
                    <div class="col-lg-12 job_view_map" style="height:320px">
                        <div class="kt-portlet">
                            <div class="job_map">
                                @if(isset($depot))
                                <img class="depot_map_main" alt='static Mapbox map of the San Francisco bay area'
                                     src="https://api.mapbox.com/styles/v1/mapbox/light-v10/static/pin-s-o+FF5C00({{ $depot->lng }},{{ $depot->lat }})/{{ $depot->lng }},{{ $depot->lat }},12,0.00,0.00/165x170@2x?access_token={{ env('MAPBOX_ACCESS_TOKEN') }}">@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')

    <script src="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet"/>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
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
            //types: 'country,region,place,postcode,locality,neighborhood'
        });
        geocoder.addTo('#geocoder_addr');

        geocoder.on('result', function (results) {
            var event = new Event('change');
            document.getElementById("addr").value = results.result.place_name;
            document.getElementById("lng").value = results.result.center[0];
            document.getElementById("lat").value = results.result.center[1];
            var resultContext = results.result.context;

            if (Array.isArray(resultContext)) {
                resultContext.forEach(function (entry) {
                    if (typeof entry.id !== "undefined") {
                        if (entry.id.indexOf("postcode") >= 0) {
                            document.getElementById("postcode").value = entry.text;
                        }

                        if (entry.id.indexOf("place") >= 0) {
                            document.getElementById("city").value = entry.text;
                        }
                    }
                });

                // If city does not return from the geo coder context
                let endCity = document.getElementById("city").value;
                if((endCity == null || endCity == '') && (results.result.text != '')){
                    document.getElementById("city").value = results.result.text;
                }
            }
        });

        document.querySelector('#geocoder_addr .mapboxgl-ctrl-geocoder--input').value = $(document).find('#addr').val();

        $(document).ready(function () {
            $("#form-btn-save").click(function () {
                let returnFlag = true;
                /*if ($.trim($("#postcode").val()) == '') {
                    $("#postcode").prop('type', 'text');
                    $('label[for="postcode"]').removeClass('hide');
                    returnFlag = false;
                }
                if ($.trim($("#city").val()) == '') {
                    $("#city").prop('type', 'text');
                    $('label[for="city"]').removeClass('hide');
                    returnFlag = false;
                }*/

                return returnFlag;
            });
        });

    </script>
@stop
