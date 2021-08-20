@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop

@section('pageHeader')
    {{-- @include('layouts.modules.header', ['moduleTitle' => 'View job', 'actionEdit' => route($moduleName.'.edit', $move->id) ]) --}}
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.jobs'),
        'subTitle' => trans('common.view_details'),
        'moduleLink' => route($moduleName.'.index')
    ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8 user_view">

                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right container_space">
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-5">
                                            <div class="kt-widget kt-widget--user-profile-1"
                                                 style="padding-bottom: 0px;">
                                                <div class="kt-widget__head">
                                                    <div class="kt-widget__media">
                                                        <span
                                                            class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bolder status_bg"
                                                            style="height: 70px;width: 70px;font-size: 2rem;">{{ $data['firstLetter'] }}</span>
                                                    </div>
                                                    <div class="kt-widget__content">
                                                        <div class="kt-widget__section">
                                                            <a href="{{ route('customer.show', $customer->id) }}"
                                                               class="kt-widget__username">{{ $customer->name }}<i
                                                                    class="flaticon2-correct kt-font-success status_bg"></i></a>
                                                            <span class="kt-widget__subtitle">customer</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__body">
                                                    <div class="kt-widget__content">
                                                        <div class="col-lg-12">
                                                            <div class="kt-widget__info"
                                                                 style="justify-content: normal;">
                                                                <span class="kt-widget__label">{{ trans('common.email') }}:</span>
                                                                <span
                                                                    class="kt-widget__data">{{ $customer->email }}</span>
                                                            </div>
                                                            <div class="kt-widget__info"
                                                                 style="justify-content: normal;">
                                                                <span class="kt-widget__label">{{ trans('common.phone') }}:</span>
                                                                <span
                                                                    class="kt-widget__data">{{ $customer->phone }}</span>
                                                            </div>
                                                            <div class="kt-widget__info"
                                                                 style="justify-content: normal;">
                                                                <span class="kt-widget__label">{{ trans('common.strip_id') }}:</span>
                                                                <span
                                                                    class="kt-widget__data">{{ $customer->stripe_id }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.company_name') }}:</label>
                                                    <span class="form-text text-muted">{{ $data['companyName'] }}</span>
                                                </div>

                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.stage') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->stage }}</span>
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.status') }}:</label><br>
                                                    @if($move->status == '' || $move->status == 'Pending')
                                                        <span class="form-text text-muted">{{ trans('move::move.waiting_for_approval') }}!</span>
                                                        @include('layouts.actions.accept', ['model' => $move])
                                                        @include('layouts.actions.decline', ['model' => $move, 'route' => 'move.decline'])
                                                    @elseif($move->status == 'Declined')
                                                        <span
                                                            class="kt-badge kt-badge--inline kt-badge--warning kt-font-bold">{{ $move->status }}</span>
                                                    @else
                                                        <span
                                                            class="kt-badge--inline kt-badge--success kt-font-bold status_bg">{{ $move->status }}</span>

                                                    @endif

                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.type') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->type }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Portlet-->
                    </div>

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <!--begin::Portlet-->
                                <div class="kt-portlet">
                                    <!--begin::Form-->
                                    <form class="kt-form kt-form--label-right container_space">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title"><i
                                                        class="flaticon2-map"></i> {{ trans('move::move.geo_start_address') }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">

                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label>{{ trans('move::move.start_address') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $move->start_addr }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.start_latitude') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->start_lat }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.start_longitude') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->start_lng }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.start_city') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->start_city }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.start_postal_code') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $move->start_postcode }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.start_access') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->start_access }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.start_date') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ !is_null($move->start_date) && !empty($move->start_date) ? Carbon\Carbon::parse($move->start_date)->format('d M Y - H:i') : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Portlet-->
                            </div>

                            <div class="col-lg-6">
                                <!--begin::Portlet-->
                                <div class="kt-portlet">
                                    <!--begin::Form-->
                                    <form class="kt-form kt-form--label-right container_space">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title"><i
                                                        class="flaticon2-map"></i> {{ trans('move::move.geo_end_address') }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">

                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label>{{ trans('move::move.end_address') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $move->end_addr }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.end_latitude') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $move->end_lat }}
                                                    </span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.end_longitude') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->end_lng }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.end_city') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->end_city }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.end_postal_code') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ $move->end_postcode }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <div class="col-lg-6">
                                                    <label class="">{{ trans('move::move.end_access') }}:</label>
                                                    <span class="form-text text-muted">{{ $move->end_access }}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>{{ trans('move::move.end_date') }}:</label>
                                                    <span class="form-text text-muted">
                                                        {{ !is_null($move->end_date) && !empty($move->end_date) ? Carbon\Carbon::parse($move->end_date)->format('d M Y - H:i') : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Portlet-->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--begin::Form-->
                            <form class="kt-form kt-form--label-right container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"><i
                                                class="flaticon-price-tag"></i> {{ trans('move::move.pricing') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.total_price') }}:</label>
                                            <span
                                                class="form-text text-muted">${{ sbNumberFormat($move->total_price) }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.deposit') }}:</label>
                                            <span
                                                class="form-text text-muted">${{ sbNumberFormat($move->deposit) }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.fee') }}:</label>
                                            <span class="form-text text-muted">${{ sbNumberFormat($move->fee) }}</span>
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
                            <form class="kt-form kt-form--label-right container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"><i
                                                class="flaticon2-menu-4"></i> {{ trans('move::move.move_specifics') }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">

                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.space') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ $move->space }}
                                            </span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.matches') }}:</label>
                                            <span class="form-text text-muted">{{ $move->matches }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.inventory') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ $move->inventory }}
                                            </span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.dwelling_type') }}:</label>
                                            <span class="form-text text-muted">{{ $move->dwelling_type }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.pickup_window_start') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ !is_null($move->pickup_window_start) && !empty($move->pickup_window_start) ? Carbon\Carbon::parse($move->pickup_window_start)->format('d M Y - H:i') : '' }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.pickup_window_end') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ !is_null($move->pickup_window_end) && !empty($move->pickup_window_end) ? Carbon\Carbon::parse($move->pickup_window_end)->format('d M Y - H:i') : '' }}
                                            </span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.delivery_window_start') }}:</label>
                                            <span
                                                class="form-text text-muted">{{ !is_null($move->delivery_window_start) && !empty($move->delivery_window_start) ? Carbon\Carbon::parse($move->delivery_window_start)->format('d M Y - H:i') : '' }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.delivery_window_end') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ !is_null($move->delivery_window_end) && !empty($move->delivery_window_end) ? Carbon\Carbon::parse($move->delivery_window_end)->format('d M Y - H:i') : '' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>{{ trans('move::move.dwelling_size') }}:</label>
                                            <span class="form-text text-muted">
                                                {{ $move->dwelling_size }}
                                            </span>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="">{{ trans('move::move.customer_analytics') }}:</label>
                                            <span class="form-text text-muted">{{ $move->customer_analytics }}</span>
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
                                        <h3 class="kt-portlet__head-title"><i
                                                class="fa fa-handshake"></i> {{ trans('move::move.deals') }}</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        <div class="kt-portlet__head-toolbar">
                                            <a href="{{ route('move.deal.create', $move->id) }}" type="button"
                                               class="btn btn-label-primary btn-bold btn-sm btn-icon-h kt-margin-l-10"
                                               title="Add New">
                                                <i class="flaticon2-plus"></i>{{ trans('common.add_deal') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body p-0">

                                    <table class="table table-striped" id="deal-table">
                                        <thead>
                                        <tr>
                                            <th width="18%">{{ trans('common.total_price') }}</th>
                                            <th>{{ trans('common.deposit') }}</th>
                                            <th width="18%">{{ trans('common.fee') }}</th>
                                            <th width="18%">{{ trans('common.created_by') }}</th>
                                            <th width="18%">{{ trans('common.created_at') }}</th>
                                            <th width="10%">{{ trans('common.actions') }}</th>
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
                                        <h3 class="kt-portlet__head-title"><i
                                                class="fab fa-servicestack"></i> {{ trans('move::move.ancillary_services') }}
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        <div class="kt-portlet__head-toolbar">
                                            <a href="{{ route('ancillaryservice.create') }}" type="button"
                                               class="btn btn-label-primary btn-bold btn-sm btn-icon-h kt-margin-l-10"
                                               title="Add New">
                                                <i class="flaticon2-plus"></i> {{ trans('move::move.add_ancillary') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body p-0">

                                    <table class="table table-striped" id="ancillaryservice-table">
                                        <thead>
                                        <tr>
                                            <th width="18%">{{ trans('common.type') }}</th>
                                            <th>{{ trans('common.premium') }}</th>
                                            <th width="18%">{{ trans('common.basis') }}</th>
                                            <th width="18%">{{ trans('common.created_by') }}</th>
                                            <th width="18%">{{ trans('common.created_at') }}</th>
                                            <th width="10%">{{ trans('common.actions') }}</th>
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
                                        <h3 class="kt-portlet__head-title"><i
                                                class="fa fa-dollar-sign"></i> {{ trans('move::move.payment') }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body p-0">

                                    <table class="table table-striped" id="payment-table">
                                        <thead>
                                        <tr>
                                            <th width="18%">{{ trans('common.method') }}</th>
                                            <th>{{ trans('common.response') }}</th>
                                            <th width="18%">{{ trans('common.type') }}</th>
                                            <th width="18%">{{ trans('common.amount') }}</th>
                                            <th width="18%">{{ trans('common.created_at') }}</th>
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
    <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
    <script>
        $(function () {
            $('#deal-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                paging: false,
                searching: false,
                bInfo: false,
                ajax: {
                    url: "{!! route('deal.index') !!}",
                    data: function (d) {
                        d.moveId = "{{ $move->id }}"
                    }
                },
                columns: [
                    {data: 'total_price', name: 'total_price'},
                    {data: 'deposit', name: 'deposit'},
                    {data: 'fee', name: 'fee'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'created_at', name: 'created_at'},
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

            $('#deal-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });


            $('#ancillaryservice-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                paging: false,
                searching: false,
                bInfo: false,
                ajax: {
                    url: "{!! route('ancillaryservice.index') !!}",
                    data: function (d) {
                        d.moveId = "{{ $move->id }}"
                    }
                },
                columns: [
                    {data: 'type', name: 'type'},
                    {data: 'premium', name: 'premium'},
                    {data: 'basis', name: 'basis'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'created_at', name: 'created_at'},
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

            $('#ancillaryservice-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });


            $('#payment-table').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: false,
                orderClasses: false,
                paging: false,
                searching: false,
                bInfo: false,
                ajax: {
                    url: "{!! route('move.payment_details') !!}",
                    data: function (d) {
                        d.moveId = "{{ $move->id }}"
                    }
                },
                columns: [
                    {data: 'method', name: 'method'},
                    {data: 'response', name: 'response'},
                    {data: 'type', name: 'type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at'},
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

            $('#payment-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });

        });
    </script>
@stop
