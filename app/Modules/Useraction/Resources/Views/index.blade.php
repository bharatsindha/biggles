@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.user_actions')]) @stop
@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.user_actions'),
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
                        <th width="15%">{{ trans('common.action_by') }}</th>
                    <th width="20%">{{ trans('common.date') }}</th>
                    <th width="20%">{{ trans('common.activity') }}</th>
                    <th>{{ trans('common.message') }}</th>
                    <th width="10%">{{ trans('common.remote_ip') }}</th>
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
        $(function () {
            $('#{{$moduleName}}-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                ajax: '{!! route($moduleName.'.index') !!}',
                columns: [
                    {data: 'action_by', name: 'action_by'},
                    {data: 'date', name: 'date'},
                    {data: 'activity', name: 'activity'},
                    {data: 'message', name: 'message'},
                    {data: 'remote_ip', name: 'remote_ip'}
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

            // click action to go to the module detail screen
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