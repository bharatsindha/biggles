@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.company')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'View company', 'actionEdit' => route('company.edit', $company->id) ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-company__view" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view">
                <div class="col-lg-8">
                    <div class="col-lg-12">

                        <!--begin::Portlet-->
                        <div class="kt-portlet">

                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right container_space">
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.company_name') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ $company->name }}
                                            </span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.email') }}:</label>
                                            <span class="form-text text-muted">{{ $company->email }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.address') }}:</label>
                                            <div class="kt-input-icon">
                                                <span class="kt-input-icon__icon kt-input-icon__icon--right"></span>
                                            </div>
                                            <span class="form-text text-muted">{{ $company->address }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.website') }}:</label>
                                            <span class="form-text text-muted">{{ $company->website }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.phone') }}:</label>
                                            <span class="form-text text-muted">{{ $company->phone }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.hosted_phone') }}:</label>
                                            <span class="form-text text-muted">{{ $company->hosted_phone }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label>{{ trans('company::company.about_us') }}:</label>
                                            <span class="form-text text-muted">{{ $company->about_us }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.abn') }}:</label>
                                            <span class="form-text text-muted">{{ $company->abn }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.bank_number') }}:</label>
                                            <span class="form-text text-muted">{{ $company->bank_number }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.bank_bsb') }}:</label>
                                            <span class="form-text text-muted">{{ $company->bank_bsb }}</span>
                                        </div>
                                        {{--<div class="col-lg-6">
                                            <label class="">{{ trans('company::company.stripe_id') }}:</label>
                                            <span class="form-text text-muted">{{ $company->stripe_id }}</span>
                                        </div>--}}
                                    </div>

                                </div>

                            </form>

                            <!--end::Form-->
                        </div>

                        <!--end::Portlet-->
                    </div>
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"><i
                                                class="fa fa-route"></i> {{ trans('company::company.inter_state_setting') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.min_price') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->min_price }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.stairs') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->stairs }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.elevator') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->elevator }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.long_driveway') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->long_driveway }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.ferry_vehicle') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->ferry_vehicle }}</span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">{{ trans('company::company.heavy_items') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->heavy_items }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>{{ trans('company::company.extra_kms') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->extra_kms }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('company::company.packaging') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->packing }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('company::company.storage') }}:</label>
                                            <span class="form-text text-muted">{{ $company->interState->storage }}</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                        <h3 class="kt-portlet__head-title">{{ trans('common.lane_details') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body company_view_space">
                                    <table class="table table-striped" id="lane-table">
                                        <thead>
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
                            </form>
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
                                        <h3 class="kt-portlet__head-title">{{ trans('common.trip_details') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body company_view_space">
                                    <table class="table table-striped" id="trip-table">
                                        <thead>
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

        });
    </script>
@stop

