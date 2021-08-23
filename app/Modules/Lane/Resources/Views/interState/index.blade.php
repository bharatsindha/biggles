@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.interstate')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.interstate'),
        'subTitle' => trans('common.list'),
        'moduleLink' => route('trip.trip-calendar')
    ])
@stop

@section('css')
@stop

@section('content')
    @if(isset($isAccess['lane']))
    <!-- Basic table start-->
    <section class="app-user-list">
        <!-- list section start -->
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">Lanes</h4>
                <a class="dt-button add-new btn btn-primary mt-50" href="{{ route('lane.create') }}">
                    <i class="ficon b-plus-icon" data-feather="plus"></i>
                    <span>{{ trans('common.add') . ' '. trans('common.lane') }}</span>
                </a>
            </div>

            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="lane-table">
                    <thead class="table-light">
                    <tr>
                        <th width="2%">{{ trans('common.id') }}</th>
                        <th width="15%">{{ trans('common.company') }}</th>
                        <th width="15%">{{ trans('common.start') }}</th>
                        <th width="15%">{{ trans('common.end') }}</th>
                        <th width="5%">{{ trans('common.transport') }}</th>
                        <th width="5%">{{ trans('common.type') }}</th>
                        <th width="10%">{{ trans('common.price') }}</th>
                        <th>{{ trans('common.timing') }}</th>
                        <th  width="10%">{{ trans('common.available_space') }}</th>
                        <th width="10%">{{ trans('common.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- list section end -->
    </section>
    <!-- Basic table end -->
    @endif
    @if(isset($isAccess['trip']))
    <!-- Basic table start-->
    <section class="app-user-list">
        <!-- list section start -->
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">Trips</h4>
                <a class="dt-button add-new btn btn-primary mt-50" href="{{ route('trip.create') }}">
                    <i class="ficon b-plus-icon" data-feather="plus"></i>
                    <span>{{ trans('common.add') . ' '. trans('common.trip') }}</span>
                </a>
            </div>

            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="trip-table">
                    <thead class="table-light">
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
            </div>
        </div>
        <!-- list section end -->
    </section>
    <!-- Basic table end -->
    @endif
@stop

@section('scripts')
<script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>

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
                dom: '<"d-flex justify-content-between align-items-center mx-1 row"' +
                    '<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex ' +
                    'justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                order: [[1, 'desc']],
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    $('#lane-table_filter input').removeClass('form-control-sm');
                    $('#lane-table_wrapper').find("select[name='lane-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });

            $('#lane-table tbody').on('click', 'tr', function (evt) {
                var href = $(this).find("a#view").attr('href');
                var $cell = $(evt.target).closest('td');
                if ($cell.index() !== 4 && href) {
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
                dom: '<"d-flex justify-content-between align-items-center mx-1 row"' +
                    '<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex ' +
                    'justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                order: [[1, 'desc']],
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    $('#trip-table_filter input').removeClass('form-control-sm');
                    $('#trip-table_wrapper').find("select[name='trip-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });

            $('#trip-table tbody').on('click', 'tr', function (evt) {
                let href = $(this).find("a#view").attr('href');
                let $cell = $(evt.target).closest('td');
                if ($cell.index() !== 4 && href) {
                    $(location).attr('href', href);
                }
            });
        });
    </script>
@stop
