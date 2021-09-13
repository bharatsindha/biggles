@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.locals'),
    'subTitle' => trans('common.list'),
    'moduleLink' => route($moduleName.'.index')
])
@stop

@section('content')
    <!-- Basic table start-->
    <section class="app-user-list">
        <!-- list section start -->
        <div class="card">
            <div class="border-bottom-light">
                <div class="job_title d-flex __job_status_tabs mt-1 justify-content-between align-items-baseline mx-75">
                    <!-- Navigation Tabs starts -->
                    <ul class="nav nav-tabs mb-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab"
                                    aria-selected="true">All prices (<span class="totalLocalRecords">1</span>)</a>
                            </li>
                    </ul>
                    <!-- Navigation Tabs starts -->
                    {{-- <a class="dt-button b-add-new btn btn-primary" href="{{ route($moduleName.'.create') }}">
                        <span><i class="ficon b-plus-icon" data-feather="plus"></i>&nbsp;  Add Local </span>
                    </a> --}}
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="user-list-table table" id="{{$moduleName}}-table">
                    <thead class="table-light">
                    <tr>
                        <th width="20%">{{ trans('common.company') }}</th>
                        <th width="20%">{{ trans('common.depot_name') }}</th>
                        <th width="15%">{{ trans('common.radius') }}</th>
                        <th width="15%">{{ trans('common.price_per') }}</th>
                        <th width="15%">{{ trans('common.min_price') }}</th>
                        <th width="15%">{{ trans('common.extra_person_price2') }}</th>
                        <th width="">{{ trans('common.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- list section end -->
    </section>
    <!-- Basic table end -->
@stop

@section('scripts')

    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script>
        $(function() {
            $('#{{$moduleName}}-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route($moduleName.'.index') !!}',
                columns: [
                    /*{data: 'location', name: 'location'},*/
                    {data: 'company_name', name: 'company_name'},
                    {data: 'depot_name', name: 'depot_name'},
                    {data: 'radius', name: 'radius'},
                    {data: 'price_per', name: 'price_per'},
                    {data: 'min_price', name: 'min_price'},
                    {data: 'extra_person_price', name: 'extra_person_price'},
                    {data: 'action', name: 'action'},
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    $(".totalLocalRecords").html(data.length);
                },
                columnDefs: [
                    { className: "_mv_text_align_left", targets: "_all" },
                ],
                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                    '<"col-sm-12 col-md-4 col-lg-6" l>' +
                    '<"col-sm-12 col-md-8 col-lg-6 ps-xl-75 ps-0"<"dt-action-buttons text-xl-end text-lg-start ' +
                    'text-md-end text-start d-flex align-items-center justify-content-md-end align-items-center ' +
                    'flex-sm-nowrap flex-wrap me-1"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
              // Buttons with Dropdown
              buttons: [
                    {
                        text: '{!! '<i class="ficon b-plus-icon" data-feather="plus"></i> &nbsp;'. trans('common.add'). ' '. trans('common.local') !!} ',
                        className: 'add-new btn btn-primary mt-50',
                        action: function (e, dt, node, config) {
                            window.location.href = "{{ route($moduleName.'.create') }}";
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
                order: [[ 1, 'desc' ]],
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                lengthMenu: [ 25, 50, 100 ],
                displayLength: 25,
                initComplete: function () {
                    $('#{{$moduleName}}-table_filter input').removeClass('form-control-sm');
                    $('#{{$moduleName}}-table_wrapper').find("select[name='{{$moduleName}}-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });

            $('#{{$moduleName}}-table tbody').on('click', 'tr', function (evt) {
                let href = $(this).find("a#view").attr('href');
                let $cell = $(evt.target).closest('td');
                if ($cell.index() !== 4 && href) {
                    $(location).attr('href', href);
                }
            });
        });
    </script>
@stop
