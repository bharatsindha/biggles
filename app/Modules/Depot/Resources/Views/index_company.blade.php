@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.depot')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Depot', 'actionAddNew' => route($moduleName.'.create') ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor depots_content_section"
         id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=> trans('common.'.$moduleName), 'subRoute'=>route($moduleName.'.create')])
            <div class="row depots_main">
                @foreach($depots as $depot)
                    <div class="col-lg-3 depot_content_main">

                        <img class="depot_map_main" alt='static Mapbox map of the San Francisco bay area'
                             src="https://api.mapbox.com/styles/v1/mapbox/light-v10/static/pin-s-o+FF5C00({{ $depot->lng }},{{ $depot->lat }})/{{ $depot->lng }},{{ $depot->lat }},12,0.00,0.00/250x170@2x?access_token={{ env('MAPBOX_ACCESS_TOKEN') }}">
                        {{--<div id="map_{{ $depot->id }}" style="height: 50vh;width: 100%;"></div>--}}
                        <div class="depot_content">
                            <h4>{{ $depot->name }}</h4>
                            <p>{{ $depot->addr }}, {{ $depot->city }}, {{ $depot->postcode }}</p>
                            <a href="{{ route('depot.edit', $depot->id) }}">Edit depot</a>
                        </div>
                    </div>
                @endforeach
            </div>
    @endcomponent
    <!-- /page content -->
    </div>
@stop

@section('scripts')
@stop
