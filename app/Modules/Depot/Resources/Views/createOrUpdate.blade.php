@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.depot')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.depot'),
        'subTitle' => isset($depot) ? trans('common.edit'). ' '. trans('common.depot') : trans('common.add').' '. trans('common.depot') ,
        'moduleLink' => route($moduleName.'.index')
    ])
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">

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
        @if(isset($truck))
            {{ Form::model($depot, [
            'route' => [$moduleName.'.update', $depot->id],
            'method' => 'patch',
            'class' => 'form-validate'
            ]) }}
        @else
            {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
        @endif
        @csrf
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                        {!! Form::hidden('lat', old('lat'),['id' => 'lat','class' => 'form-control', 'placeholder' => 'Please enter Latitude','required' => 'required']) !!}
                            {!!  Form::hidden('lng', old('lng'),['id' => 'lng','class' => 'form-control','placeholder' => 'Please enter Longitude','required' => 'required']) !!}
                        <div class="row">
                            @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level @endphp
                            @if($userAccess != 1)
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="company_id">
                                            {{ trans('depot::depot.company') }}<span class="required"> * </span>
                                        </label>
                                        {!!  Form::select('company_id',  $companies, old('company_id'),[
                                            'id' => 'company_id',
                                            'class' => 'form-select select2 '.
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
                                    <label class="form-label" for="name">
                                        {{ trans('depot::depot.name') }}
                                    </label>
                                    {!!  Form::text('name', old('name'),[
                                        'id' => 'name',
                                        'class' => 'form-control '. (($errors->has('name')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Depot Name'
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
                                    <label class="form-label" for="address">
                                        {{ trans('depot::depot.address') }}
                                    </label>
                                    <div id="geocoder_addr"></div>
                                    {!!  Form::hidden('addr', old('addr'),[
                                        'id' => 'addr',
                                        'class' => 'form-control '. (($errors->has('addr')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Address'
                                        ]) !!}
                                    @if($errors->has('addr'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('addr') }}
                                        </div>
                                    @endif
                                </div>
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
                </div>
            </div>
        </div>

    </section>
    @include('layouts.forms.actions')
    {{ Form::close() }}
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
            //countries: 'au',
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


