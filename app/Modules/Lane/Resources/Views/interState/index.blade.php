@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.interstate')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Interstate',
 'calendarView'   => route('trip.trip-calendar') ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        @if(isset($isAccess['lane']))
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet head_remove">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('common.lane_details') }}
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        <div class="kt-portlet__head-toolbar">
                                            <a href="{{ route('lane.create') }}" class="header_button" title="{{ trans('common.add_new_lane') }}"> <i class="flaticon2-plus"></i> {{ trans('common.add_new_lane') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">

                                    <!-- begin:: Content -->
                                    @component('layouts.modules.grid',['subTitle'=>trans('common.lane'), 'subRoute'=>route('lane.create')])
                                        <table class="table table-striped" id="lane-table">
                                            <thead>
                                            <tr>
                                                <th width="2%">{{ trans('common.id') }}</th>
                                                <th width="10%">{{ trans('common.company') }}</th>
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
            </div>
        @endif

        @if(isset($isAccess['trip']))
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet head_remove">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"> {{ trans('common.trip_details') }}
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        <div class="kt-portlet__head-toolbar">
                                            <a href="{{ route('trip.create') }}" class="header_button" title="{{ trans('common.add_new_trip') }}"> <i class="flaticon2-plus"></i> {{ trans('common.add_new_trip') }}</a>
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
                                                <th width="10%">{{ trans('common.company') }}</th>
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
            </div>
        @endif

    </div>
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script>
        $(function () {
            $('#lane-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route('lane.index') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'company_name', name: 'company_name'},
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

            $('#lane-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });


            $('#trip-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route('trip.index') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'company_name', name: 'company_name'},
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
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });

        });
    </script>
@stop
