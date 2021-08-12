@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.trip')]) @stop
@section('pageHeader')
@include('layouts.modules.header', [
    'moduleTitle' => trans('common.trip'),
    'subTitle' => trans('common.view_details'),
    'moduleLink' => route($moduleName.'.index')
])
@stop
@section('content')
    <!-- Page content -->
<section id="profile-info">
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <!-- about -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.company_name') }}:</h5>
                                    <p class="card-text">{{ $trip->company->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_address') }}:</h5>
                                    <p class="card-text">{{ $trip->start_addr }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_latitude') }}:</h5>
                                    <p class="card-text">{{ $trip->start_lat }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_longitude') }}:</h5>
                                    <p class="card-text">{{ $trip->start_lng }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_city') }}:</h5>
                                    <p class="card-text">{{ $trip->start_city }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_postcode') }}:</h5>
                                    <p class="card-text">{{ $trip->start_postcode }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.end_address') }}:</h5>
                                    <p class="card-text">{{ $trip->start_addr }}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.end_latitude') }}:</h5>
                                    <p class="card-text">{{ $trip->end_lat }}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.end_lng') }}:</h5>
                                    <p class="card-text">{{ $trip->end_lng }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.end_city') }}:</h5>
                                    <p class="card-text">{{ $trip->end_city }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.end_postcode') }}:</h5>
                                    <p class="card-text">{{ $trip->end_postcode }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.transit_time') }}:</h5>
                                    <p class="card-text">{{ $trip->transit_time }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.pickup_notice') }}:</h5>
                                    <p class="card-text">{{ $trip->pickup_notice }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.price_per') }}:</h5>
                                    <p class="card-text">{{ $trip->price_per }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.min_price') }}:</h5>
                                    <p class="card-text">{{ $trip->min_price }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.start_date') }}:</h5>
                                    <p class="card-text">{{ $trip->start_date }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.capacity') }}:</h5>
                                    <p class="card-text">{{ $trip->capacity }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.transport') }}:</h5>
                                    <p class="card-text">{{ $trip->transport }}</p>
                                </div>
                            </div>
                            @if(!is_null($trip->frequency))
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.frequency') }}:</h5>
                                    <p class="card-text">{{ $trip->frequency }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.recurrence_expiry') }}:</h5>
                                    <p class="card-text">{{ $trip->recurrence_expiry }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('trip::trip.delivery_within') }}:</h5>
                                    <p class="card-text">{{ $trip->delivery_within }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/ about -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="user-list-table table" id="{{$moduleName}}-table">
                                <thead class="table-light">
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
                    </div>
                    <!-- list section end -->
                </section>
                @include('layouts.modules.form-footer')
            </div>
        </div>
    </section>
    <!-- /page content -->
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
