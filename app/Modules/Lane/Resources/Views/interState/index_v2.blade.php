@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.interstate')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Interstate', 'interstateSettings'   => route('company.interstate') ])
    {{--, 'calendarView'   => route('trip.trip-calendar')--}}
@stop

@section('content')
    <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor depots_content_section"
         id="kt_content">
        @if(isset($isAccess['lane']) && isset($lanes))
        <!-- begin:: Content -->
            @component('layouts.modules.grid',['subTitle'=>trans('common.lane'), 'subRoute'=>route('lane.create')])
                <div class="row depots_main">
                    <div class="col-lg-12">
                        <div class="lane_title d-flex justify-content-between align-items-center col-lg-12">
                            <h4>{{ trans('lane::lane.lanes') }} ({{ count($lanes['records']) }}) </h4>
                            <a class="header_button" href="{{ route('lane.create') }}">Add new lane</a>
                        </div>
                        <div class="dashboard_main notification_open depot_interstate">
                            <div class="notification_title">
                                <div class="nofication_content">
                                    <div class="notification_title d-flex">
                                        @foreach($lanes['cities'] as $key => $city)
                                            <p class="city_{{ $key }} {{ ($key == 0) ? 'show' : '' }}">From {{ $city }}</p>
                                        @endforeach
                                    </div>
                                    @foreach($lanes['cities'] as $key => $city)
                                        <div class="notification_content_section common_notification city_{{ $key }} {{ ($key == 0) ? 'show' : '' }}">
                                            <div class="row depots_main">
                                                @foreach($lanes['records'] as $cityDetails)

                                                    @if($cityDetails->start_city == $city)
                                                        <div class="col-sm-3 depot_content_main">
                                                            <img class="depot_map_main"
                                                                    alt='static Mapbox map of the San Francisco bay area'
                                                                    src="https://api.mapbox.com/styles/v1/mapbox/light-v10/static/pin-s-o+FF5C00({{ $cityDetails->start_lng }},{{ $cityDetails->start_lat }})/{{ $cityDetails->start_lng }},{{ $cityDetails->start_lat }},12,0.00,0.00/250x170@2x?access_token={{ env('MAPBOX_ACCESS_TOKEN') }}">
                                                            <div class="depot_content">
                                                                <div class="sidebar_search_section pb10">
                                                                    <div class="row">
                                                                        <div class="job_content">
                                                                            <div class="pending_job_section">
                                                                                <div class="pending_top d-flex align-items-center justify-content-between"> <span class="status_bg">{{ $cityDetails->transport == 2 ? 'Rail' : 'Truck' }}</span>
                                                                                    <span
                                                                                        class="price"><sup>$</sup>{{ $cityDetails->min_price }}</span></div>
                                                                                <div class="job_description"> <div class="address_content"> <p class="active"><span></span> {{ $cityDetails->start_city }}</p> <p>{{ $cityDetails->end_city }}</p> </div> </div>
                                                                                <a class="create_trip" href="{{ route('trip.trip_create_byLane', $cityDetails->id) }}">Create trip</a>
                                                                                <a class="create_trip" href="{{ route('lane.edit', $cityDetails->id) }}">Edit Lane</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet lane_trip_content">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('common.trip_details') }}
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        <div class="kt-portlet__head-toolbar">
                                            <a href="{{ route('trip.create') }}" class="header_button" title="{{ trans('common.add_new_trip') }}"> {{ trans('common.add_new_trip') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!-- begin:: Content -->
                                    @component('layouts.modules.grid',['subTitle'=>trans('common.trip'), 'subRoute'=>route('lane.create')])
                                        <table class="table table-striped" id="trip-table">
                                            <thead>
                                            <tr>
                                                <th width="2%">{{ trans('common.id') }}</th>
                                                <th width="10%">{{ trans('common.start') }}</th>
                                                <th width="10%">{{ trans('common.end') }}</th>
                                                <th width="5%">{{ trans('common.transport') }}</th>
                                                <th width="5%">{{ trans('common.type') }}</th>
                                                <th width="10%">{{ trans('common.price') }}</th>
                                                <th>{{ trans('common.timing') }}</th>
                                                <th  width="10%">{{ trans('common.available_space') }}</th>
                                                <th width="10%">{{ trans('common.actions') }}</th>
                                            </tr>
                                            </thead>
                                        </table>
                                @endcomponent
                                <!-- /page content -->

                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
            @endcomponent
        <!-- end:: content -->
        @endif

    </div>
@stop

@section('scripts')

    <script>

        $(document).ready(function () {
            for (let i = 0; i < {{ count($lanes['cities']) }}; i++) {
                $(".dashboard_main .notification_title .notification_title .city_" + i).click(function () {
                    $('.dashboard_main .notification_title .notification_title p').removeClass('show');
                    $(this).addClass('show');

                    $('.dashboard_main .notification_title .nofication_content .notification_content_section.common_notification').removeClass('show');
                    $('.dashboard_main .notification_title .nofication_content .notification_content_section.city_' + i).addClass('show');
                });
            }
        });
    </script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script>
        $(function () {
            $('#trip-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route('trip.index') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'start_city', name: 'start_city'},
                    {data: 'end_city', name: 'end_city'},
                    {data: 'transport', name: 'transport'},
                    {data: 'type', name: 'type'},
                    {data: 'price_per', name: 'price_per'},
                    {data: 'transit_time', name: 'transit_time'},
                    {data: 'capacity', name: 'capacity'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [
                    { className: "_mv_text_align_left", targets: "_all" },
                ],
                dom: '<"datatable-header user_details d-flex"fl><"datatable-scroll-lg user_listing"t><"datatable-footer user_show d-flex justify-content-between"ip>',
                order: [[1, 'desc']],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25
            });

            $('.dataTables_length select').select2({
                minimumResultsForSearch: "-1"
            });

            $('#trip-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#edit").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });

        });
    </script>
@stop
