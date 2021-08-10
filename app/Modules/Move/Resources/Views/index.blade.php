@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop

{{--

'actionAddNew' => route($moduleName.'.create')
--}}
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

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        @component('layouts.modules.grid',['subTitle'=> trans('common.'.$moduleName), 'subRoute'=>route($moduleName.'.create')])
            <table class="table table-striped" id="{{$moduleName}}-table">
                <div class="job_title d-flex __job_status_tabs">
                    @foreach($data['jobTitles'] as  $jobTitle)
                        <p class="{{ $jobTitle['active'] ? 'active' : '' }}"
                           data-status="{{ $jobTitle['id'] }}">{{ $jobTitle['title'] }} ({{ $jobTitle['count'] }})</p>
                    @endforeach
                </div>
                <thead>
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
                    <th width="5%" id="__switch__action__type">{{ trans('common.accept') }}</th>
                </tr>
                </thead>
            </table>
        @endcomponent
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"> </script>



    <script>
        $(function () {

            var jobPendingList = $('#{{$moduleName}}-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                "bSort": true,
                ajax: {
                    url: '{!! route($moduleName.'.index') !!}',
                    data: function (d) {
                        d.status = $(".__job_status_tabs p.active").data('status');
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

            $('#{{$moduleName}}-table tbody').on('click', 'tr', function (evt) {
                let href = $(this).find("a#view").attr('href');
                let $cell = $(evt.target).closest('td');
                if ($cell.index() != 7 && $cell.index() != 8 && href) {
                    $(location).attr('href', href);
                }
            });

            // Draw table again click on search button
            $('.__job_status_tabs p').on('click', function (e) {
                $(".__job_status_tabs p").removeClass('active');
                $(this).addClass('active');

                let status = $(".__job_status_tabs p.active").data('status');
                let columnName = (status == 1) ? "{{ trans('common.accept') }}" : "{{ trans('common.actions') }}";
                $("#__switch__action__type").html(columnName);

                if(status == 2){
                    jobPendingList.column(4).visible(true);
                }else{
                    jobPendingList.column(4).visible(false);
                }
                jobPendingList.ajax.reload();
                e.preventDefault();
            });
        });


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
