@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.fleet')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => 'Fleets',
        'subTitle' => 'List',
        'moduleLink' => route($moduleName.'.index')
    ])
@stop

@section('css')
@stop

@section('content')
    <!-- Basic table -->
    <section class="app-user-list">
        <!-- list section start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="{{$moduleName}}-table">
                    <thead class="table-light">
                    <tr>
                        <th width="15%">{{ trans('common.company_name') }}</th>
                        <th width="20%">{{ trans('common.truck_name') }}</th>
                        <th>{{ trans('common.description') }}</th>
                        <th width="15%">{{ trans('common.truck_capacity') }}</th>
                        <th width="10%">{{ trans('common.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>

    <script>
        $(function () {
            $('#{{$moduleName}}-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route($moduleName.'.index') !!}',
                columns: [
                    {data: 'company_name', name: 'company_name'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'capacity', name: 'capacity'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [
                    {className: "_mv_text_align_left", targets: "_all"},
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
                        text: 'Add New Truck',
                        className: 'add-new btn btn-primary mt-50',
                        action: function (e, dt, node, config) {
                            window.location.href = "{{ route($moduleName.'.create') }}";
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
                order: [[1, 'desc']],
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    $('#{{$moduleName}}-table_filter input').removeClass('form-control-sm');
                    $('#{{$moduleName}}-table_wrapper').find("select[name='{{$moduleName}}-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });

            /*$('.dataTables_length select').select2({
                minimumResultsForSearch: "-1"
            });*/

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
