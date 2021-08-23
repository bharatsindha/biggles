@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop

@section('pageHeader')
    @if(\App\Facades\General::isSuperAdmin())
        @include('layouts.modules.header', [
            'moduleTitle' => trans('common.jobs'),
            'subTitle' => trans('common.list'),
            'moduleLink' => route($moduleName.'.index')
        ])
    @else
        @include('layouts.modules.header', [
            'moduleTitle' => trans('common.jobs'),
            'subTitle' => trans('common.list'),
            'moduleLink' => route($moduleName.'.index')
        ])
    @endif
@stop

@section('css')
<style>
    .status_bg {
        padding: 5px 10px !important;
        background-color: rgba(253, 185, 64, .1) !important;
        font-weight: 600 !important;
        color: #FDB940 !important;
        white-space: nowrap;
    }
</style>
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
                        @foreach($data['jobTitles'] as  $jobTitle)
                            <li class="nav-item">
                                <a
                                    class="nav-link {{ $jobTitle['active'] ? 'active' : '' }} pb-2"
                                    data-bs-toggle="tab"
                                    aria-controls="{{ $jobTitle['title'] }}"
                                    role="tab"
                                    aria-selected="true"
                                    data-status="{{ $jobTitle['id'] }}"
                                >{{ $jobTitle['title'] }} ({{ $jobTitle['count'] }})</a
                                >
                            </li>
                        @endforeach
                    </ul>
                    <!-- Navigation Tabs starts -->
                    <a class="dt-button b-add-new btn btn-primary" href="{{ route('ancillaryservice.index') }}">
                        <span> Ancillaries </span>
                    </a>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="{{$moduleName}}-table">
                    <thead class="table-light">
                    <tr>
                        <th width="2%">{{ trans('common.id') }}</th>
                        <th width="10%">{{ trans('common.customer') }}</th>
                        <th width="">{{ trans('common.pickup') }}</th>
                        <th width="">{{ trans('common.delivery') }}</th>
                        <th width="">{{ trans('common.pickup_date') }}</th>
                        <th width="5%">{{ trans('common.status') }}</th>
                        <th width="5%">{{ trans('common.space') }}</th>
                        <th width="5%">{{ trans('common.price') }}</th>
                        <th width="5%">{{ trans('common.is_complete') }}</th>
                        <th width="10%" id="__switch__action__type">{{ trans('common.accept') }}</th>
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

            let jobPendingList = $('#{{$moduleName}}-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                "bSort": true,
                ajax: {
                    url: '{!! route($moduleName.'.index') !!}',
                    data: function (d) {
                        d.status = $(".__job_status_tabs ul li a.active").data('status');
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'start_address', name: 'start_address'},
                    {data: 'end_address', name: 'end_address'},
                    {data: 'pickup_date', name: 'pickup_date'},
                    {data: 'status', name: 'status'},
                    {data: 'space', name: 'space'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'is_complete', name: 'is_complete'},
                    {data: 'actions', name: 'actions'},
                ],
                columnDefs: [
                    {className: "_mv_text_align_left", targets: "_all"},
                    { "visible": false, "targets": 4 },
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
                    reloadFeather();
                },
                "drawCallback": function( settings ) {
                    reloadFeather();
                }
            });

            $('#{{$moduleName}}-table tbody').on('click', 'tr', function (evt) {
                let href = $(this).find("a#view").attr('href');
                let $cell = $(evt.target).closest('td');

                let jobStatus =  $(".__job_status_tabs ul li a.active").data('status');

                if(jobStatus == 1){
                    if ($cell.index() !== 4 && $cell.index() !== 1 && $cell.index() !== 7 && $cell.index() !== 8 && href) {
                        $(location).attr('href', href);
                    }
                } else if(jobStatus == 2){
                    if ($cell.index() !== 5 && $cell.index() !== 1 && $cell.index() !== 8 && $cell.index() !== 9 && href) {
                        $(location).attr('href', href);
                    }
                } else if(jobStatus == 3){
                    if ($cell.index() !== 4 && $cell.index() !== 1 && $cell.index() !== 7 && $cell.index() !== 8 && href) {
                        $(location).attr('href', href);
                    }
                }
            });

            // Draw table again click on search button
            $('.__job_status_tabs ul li a').on('click', function (e) {
                $(".__job_status_tabs ul li a").removeClass('active');
                $(this).addClass('active');

                let status = $(".__job_status_tabs ul li a.active").data('status');
                let columnName = (status === 1) ? "{{ trans('common.accept') }}" : "{{ trans('common.actions') }}";
                $("#__switch__action__type").html(columnName);

                if(status === 2){
                    jobPendingList.column(4).visible(true);
                }else{
                    jobPendingList.column(4).visible(false);
                }
                jobPendingList.ajax.reload();

                e.preventDefault();
            });
        });

        /**
        * Reload the feather icon
        **/
        function reloadFeather(){
            feather.replace();

            let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

        }
    </script>

<script type="text/javascript">
    function changeToComplete(elementId) {
        if (confirm("{{ trans('common.complete_alert') }}")) {
            let url = '{{ route('move.completed', 'elementId') }}';
            url = url.replace("elementId", elementId);
            // isComplete = (isComplete == 1) ? 0 : 1;

            $.ajax({
                url: url,
                type: 'GET',
                /*data: {
                    is_complete: isComplete,
                },*/
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    alert("Something Went Wrong!");
                }
            });
        }
    }
</script>
@stop
