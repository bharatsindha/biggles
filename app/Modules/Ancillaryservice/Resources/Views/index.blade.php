@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.ancillaryservice')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Ancillaries', 'actionAddNew' => route($moduleName.'.create') ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=> trans('common.'.$moduleName), 'subRoute'=>route($moduleName.'.create')])
            <table class="table table-striped" id="{{$moduleName}}-table">
                <thead>
                <tr>
                    <th width="15%">{{ trans('common.type') }}</th>
                    <th width="10%">{{ trans('common.price') }}</th>
                    <th width="10%">{{ trans('common.basis') }}</th>
                    <th width="18%">{{ trans('common.created_by') }}</th>
                    <th width="18%">{{ trans('common.created_at') }}</th>
                    <th width="10%">{{ trans('common.actions') }}</th>
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
                    {data: 'type', name: 'type'},
                    {data: 'price', name: 'price'},
                    {data: 'basis', name: 'basis'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
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
