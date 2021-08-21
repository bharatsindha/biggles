@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.company')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
    'moduleTitle' => trans('common.company'),
    'subTitle' => trans('common.view_details'),
    'moduleLink' => route('company.edit', $company->id) ])
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
                                    <h5 class="mb-75">{{ trans('company::company.company_name') }}:</h5>
                                    <p class="card-text">{{ $company->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.email') }}:</h5>
                                    <p class="card-text">{{ $company->email }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.address') }}:</h5>
                                    <p class="card-text">{{ $company->website }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.website') }}:</h5>
                                    <p class="card-text">{{ $company->website }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.phone') }}:</h5>
                                    <p class="card-text">{{ $company->phone }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.hosted_phone') }}:</h5>
                                    <p class="card-text">{{ $company->hosted_phone }}</p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.about_us') }}:</h5>
                                    <p class="card-text">{{ $company->about_us }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.abn') }}:</h5>
                                    <p class="card-text">{{ $company->abn }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.bank_number') }}:</h5>
                                    <p class="card-text">{{ $company->bank_number }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.bank_bsb') }}:</h5>
                                    <p class="card-text">{{ $company->bank_bsb }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    <i class="fa fa-route"></i> {{ trans('company::company.inter_state_setting') }}</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.min_price') }}:</h5>
                                    <p class="card-text">{{ $company->interState->min_price }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.stairs') }}:</h5>
                                    <p class="card-text">{{ $company->interState->stairs }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.elevator') }}:</h5>
                                    <p class="card-text">{{ $company->interState->elevator }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.long_driveway') }}:</h5>
                                    <p class="card-text">{{ $company->interState->long_driveway }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.ferry_vehicle') }}:</h5>
                                    <p class="card-text">{{ $company->interState->ferry_vehicle }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.heavy_items') }}:</h5>
                                    <p class="card-text">{{ $company->interState->heavy_items }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.extra_kms') }}:</h5>
                                    <p class="card-text">{{ $company->interState->extra_kms }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.packaging') }}:</h5>
                                    <p class="card-text">{{ $company->interState->packing }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('company::company.storage') }}:</h5>
                                    <p class="card-text">{{ $company->interState->storage }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ about -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="card-datatable table-responsive pt-0">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">{{ trans('common.lane_details') }}</h3>
                                </div>
                            </div>
                            <table class="user-list-table table" id="lane-table">
                                <thead class="table-light">
                                <tr>
                                    <th width="15%">{{ trans('common.start') }}</th>
                                    <th width="15%">{{ trans('common.end') }}</th>
                                    <th width="10%">{{ trans('common.transport') }}</th>
                                    <th width="10%">{{ trans('common.price') }}</th>
                                    <th>{{ trans('common.timing') }}</th>
                                    <th  width="10%">{{ trans('common.available_space') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- list section end -->
                </section>
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('common.trip_details') }}</h3>
                                    </div>
                                </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table class="user-list-table table" id="trip-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="15%">{{ trans('common.start') }}</th>
                                        <th width="15%">{{ trans('common.end') }}</th>
                                        <th width="10%">{{ trans('common.transport') }}</th>
                                        <th width="10%">{{ trans('common.price') }}</th>
                                        <th>{{ trans('common.timing') }}</th>
                                        <th  width="10%">{{ trans('common.available_space') }}</th>
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
            $('#lane-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                paging: false,
                searching: false,
                bInfo: false,
                ajax: {
                    url: "{!! route('lane.index') !!}",
                    data: function (d) {
                        d.companyId = "{{ $company->id }}"
                    }
                },
                columns: [
                    {data: 'start_city', name: 'start_city'},
                    {data: 'end_city', name: 'end_city'},
                    {data: 'transport', name: 'transport'},
                    {data: 'price_per', name: 'price_per'},
                    {data: 'transit_time', name: 'transit_time'},
                    {data: 'capacity', name: 'capacity'},
                ],
                columnDefs: [
                    { className: "_mv_text_align_left", targets: "_all" },
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
                    $('lane-table_filter input').removeClass('form-control-sm');
                    $('lane-table_wrapper').find("select[name=lane-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });

            $('#trip-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                paging: false,
                searching: false,
                bInfo: false,
                ajax: {
                    url: "{!! route('trip.index') !!}",
                    data: function (d) {
                        d.companyId = "{{ $company->id }}"
                    }
                },
                columns: [
                    {data: 'start_city', name: 'start_city'},
                    {data: 'end_city', name: 'end_city'},
                    {data: 'transport', name: 'transport'},
                    {data: 'price_per', name: 'price_per'},
                    {data: 'transit_time', name: 'transit_time'},
                    {data: 'capacity', name: 'capacity'},
                ],
                columnDefs: [
                    { className: "_mv_text_align_left", targets: "_all" },
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
                    $('lane-table_filter input').removeClass('form-control-sm');
                    $('lane-table_wrapper').find("select[name=lane-table_length']").removeClass('form-select-sm');
                    feather.replace();
                }
            });
        });
    </script>
@stop

