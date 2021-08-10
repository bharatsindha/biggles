@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.user_actions')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'User actions' ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=> trans('common.'.$moduleName), 'subRoute'=>route($moduleName.'.create')])
            <table class="table table-striped" id="{{$moduleName}}-table">
                <thead>
                <tr>
                    <th width="15%">{{ trans('common.action_by') }}</th>
                    <th width="20%">{{ trans('common.date') }}</th>
                    <th width="20%">{{ trans('common.activity') }}</th>
                    <th>{{ trans('common.message') }}</th>
                    <th width="10%">{{ trans('common.remote_ip') }}</th>
                </tr>
                </thead>
            </table>
        @endcomponent
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
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

            $('#{{$moduleName}}-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });
        });
    </script>
@stop
