@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.company')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.company'),
        'subTitle' => trans('common.list'),
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

@section('content')
<!-- Basic table start-->
<section class="app-user-list">
    <!-- list section start -->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="user-list-table table" id="{{$moduleName}}-table">
                <thead class="table-light">
                <tr>
                    <th width="10%">{{ trans('common.name') }}</th>
                        <th width="10%">{{ trans('common.email') }}</th>
                        <th width="10%">{{ trans('common.phone') }}</th>
                        <th>{{ trans('common.address') }}</th>
                        <th width="5%">{{ trans('common.status') }}</th>
                        <th width="5%">{{ trans('common.stripe') }}</th>
                        <th width="10%">{{ trans('common.actions') }}</th>
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
    <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
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
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address'},
                    { data: 'status', name: 'status'},
                    { data: 'is_connected', name: 'is_connected' },
                    { data: 'action', name: 'action' },
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
                        text: '{!! '<i class="ficon b-plus-icon" data-feather="plus"></i> &nbsp;'. trans('common.add'). ' '. trans('common.company') !!} ',
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
            $('#{{$moduleName}}-table tbody').on('click', 'tr', function (evt) {
                var href = $(this).find("a#view").attr('href');
                var $cell=$(evt.target).closest('td');
                if ($cell.index() != 4 && $cell.index() != 5 && $cell.index() != 6 && href) {
                    $(location).attr('href', href);
                    return;
                }
            });
        });
    </script>
@stop
