@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.trip')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Trips', 'actionAddNew' => route($moduleName.'.create') ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=>trans('common.trip'), 'subRoute'=>route('trip.create')])
            <table class="table table-striped" id="trip-table">
                <thead>
                <tr>
                    <th width="22%">{{ trans('common.company_name') }}</th>
                    <th width="15%">{{ trans('common.from') }}</th>
                    <th width="10%">{{ trans('common.from_postcode') }}</th>
                    <th width="15%">{{ trans('common.to') }}</th>
                    <th width="10%">{{ trans('common.to_postcode') }}</th>
                    <th>{{ trans('common.min_price') }}</th>
                    <th width="10%">{{ trans('common.actions') }}</th>
                </tr>
                </thead>
            </table>
    @endcomponent
    <!-- /page content -->
    </div>
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
                    {data: 'company_name', name: 'company_name'},
                    {data: 'start_city', name: 'start_city'},
                    {data: 'start_postcode', name: 'start_postcode'},
                    {data: 'end_city', name: 'end_city'},
                    {data: 'end_postcode', name: 'end_postcode'},
                    {data: 'min_price', name: 'min_price'},
                    {data: 'action', name: 'action'},
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
