@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Locals', 'actionAddNew' => route($moduleName.'.create') ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=> trans('common.'.$moduleName), 'subRoute'=>route($moduleName.'.create')])
            <table class="table table-striped" id="{{$moduleName}}-table">
                <div class="job_title d-flex __job_status_tabs justify-content-between">
                    <div class="local_price d-flex">
                        <p class="active">All prices (<span class="totalLocalRecords">1</span>)</p>
                        {{--<p>Archived prices (10)</p>--}}
                    </div>
                    <div>
                        {{--<a href="#" class="header_button">Add New Local Price</a>--}}
                    </div>
                </div>
                <thead>
                <tr>
                    {{--<th width="20%">{{ trans('depot::depot.location') }}</th>--}}
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
    @endcomponent
    <!-- /page content -->
    </div>
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script>
        $(function() {
            let table = $('#{{$moduleName}}-table').DataTable({
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
                dom: '<"datatable-header user_details d-flex"fl><"datatable-scroll-lg user_listing"t><"datatable-footer user_show d-flex justify-content-between"ip>',
                order: [[ 1, 'desc' ]],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                lengthMenu: [ 25, 50, 100 ],
                displayLength: 25
            });

            $('.dataTables_length select').select2({
                minimumResultsForSearch: "-1"
            });

            $('#{{$moduleName}}-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);
                    return;
                }
            });

        });
    </script>
@stop
