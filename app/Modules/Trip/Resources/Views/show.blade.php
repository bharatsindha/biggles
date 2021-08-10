@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.trip')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View trips', 'actionEdit' => route('trip.edit', $trip->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">
                    <div class="col-lg-12">

                        <!--begin::Portlet-->
                        <div class="kt-portlet">

                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.company_name') }}:</label>
                                        <span class="form-text text-muted">
                                        {{ $trip->company->name }}
                                    </span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.start_address') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_addr }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.start_latitude') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_lat }}</span>

                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.start_longitude') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_lng }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.start_city') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_city }}</span>

                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.start_postcode') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_postcode }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.start_city') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_city }}</span>

                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.start_postcode') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_postcode }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.end_address') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->end_addr }}</span>
                                    </div>

                                    <div class="col-lg-3">
                                        <label>{{ trans('trip::trip.end_latitude') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->end_lat }}</span>

                                    </div>
                                    <div class="col-lg-3">
                                        <label class="">{{ trans('trip::trip.end_lng') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->end_lng }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.end_city') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->end_city }}</span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.end_postcode') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->end_postcode }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.transit_time') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->transit_time }}</span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.pickup_notice') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->pickup_notice }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('trip::trip.price_per') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->price_per }}</span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="">{{ trans('trip::trip.min_price') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->min_price }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label>{{ trans('trip::trip.start_date') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->start_date }}</span>
                                    </div>

                                    <div class="col-lg-4">
                                        <label>{{ trans('trip::trip.capacity') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->capacity }}</span>
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="">{{ trans('trip::trip.transport') }}:</label>
                                        <span class="form-text text-muted">{{ $trip->transport }}</span>
                                    </div>
                                </div>


                                @if(!is_null($trip->frequency))
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>{{ trans('trip::trip.frequency') }}:</label>
                                            <span class="form-text text-muted">{{ $trip->frequency }}</span>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="">{{ trans('trip::trip.recurrence_expiry') }}:</label>
                                            <span class="form-text text-muted">{{ $trip->expiry_date }}</span>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="">{{ trans('trip::trip.delivery_within') }}:</label>
                                            <span class="form-text text-muted">{{ $trip->delivery_within }}</span>
                                        </div>

                                    </div>
                                @endif
                            </div>

                            <!--end::Form-->
                        </div>

                        <!--end::Portlet-->
                    </div>

                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"><i
                                                class="fab fa-servicestack"></i> {{ trans('common.trip') }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">

                                    <table class="table table-striped" id="trip-table">
                                        <thead>
                                        <tr>
                                            <th width="22%">{{ trans('common.company_name') }}</th>
                                            <th width="15%">{{ trans('common.from') }}</th>
                                            {{--<th width="10%">{{ trans('common.from_postcode') }}</th>--}}
                                            <th width="15%">{{ trans('common.to') }}</th>
                                            {{--<th width="10%">{{ trans('common.to_postcode') }}</th>--}}
                                            <th>{{ trans('common.min_price') }}</th>
                                            <th width="10%">{{ trans('common.actions') }}</th>
                                        </tr>
                                        </thead>
                                    </table>

                                </div>

                                @include('layouts.modules.form-footer')

                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>

                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
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
                ajax: '{!! route($moduleName.'.recurring-trips', $trip->id) !!}',
                columns: [
                    {data: 'company_name', name: 'company_name'},
                    {data: 'start_city', name: 'start_city'},
                    /*{ data: 'start_postcode', name: 'start_postcode' },*/
                    {data: 'end_city', name: 'end_city'},
                    /*{ data: 'end_postcode', name: 'end_postcode' },*/
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
