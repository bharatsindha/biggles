@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.jobs')]) @stop
@php
    use Illuminate\Support\Facades\Auth;$accessLevel = Auth::user()->access_level
@endphp

@section('pageHeader')

    @if($accessLevel == 0)
        @include('layouts.modules.header', [
            'moduleTitle' => trans('common.jobs'),
            'subTitle' => trans('common.list'),
            'moduleLink' => route($moduleName.'.index')
        ])
    @else
        @include('layouts.modules.header', ['moduleTitle' => 'Job '.$move->id])
    @endif
@stop

@section('css')
<style>
    body {
        margin: 0;
        padding: 0;
    }
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
    }
    .job_edit_icon{
        color: #796DF0;
        width: 1rem;
        size: 1rem;
    }
    .form-control[readonly]{
        background-color: #fff;
    }
</style>
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row user_view job_detail_page">
                <div class="col-lg-8">
                    @if($move->status == '' || $move->status == 0 || $move->status == 12)
                        <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!--begin::Form-->
                                            <form class="form  container_space">
                                                <div class="kt-portlet__head job_action">
                                                    <div class="w-100 d-flex align-items-center justify-content-between">
                                                        <h3>Action</h3>
                                                        <div class="job_details_button">
                                                            @include('layouts.actions.editbtn', ['model' => $move, 'route' => 'move.edit'])
                                                            @include('layouts.actions.decline', ['model' => $move, 'route' => 'move.decline', 'source' => 'job_detail'])
                                                            @include('layouts.actions.accept', ['model' => $move, 'source' => 'job_detail'])
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        <!--end::Form-->
                                    </div>
                                </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                            <!--begin::Form-->
                            <form class="form container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label border-bottom">
                                        <h3 class="kt-portlet__head-title mb-1">{{ trans('move::move.customer_information') }}</h3>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <div class="row mb-0">
                                        <div class="col-lg-2 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('common.status') }}</h5>
                                            <p class="card-text">{{ $move->statusVal }}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('common.name') }}</h5>
                                            <p class="card-text">{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? ''}}</p>
                                        </div>
                                        <div class="col-lg-3 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('common.phone') }}</h5>
                                            <p class="card-text">{{ $customer->phone ?? '' }}</p>
                                        </div>
                                        <div class="col-lg-4 d-flex flex-column">
                                            <h5 class="mb-75">{{ trans('common.email') }}</h5>
                                            <p class="card-text">{{ $customer->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </form><!--end::Form-->
                        </div>
                    </div>
                </div>
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                            <div class="card">
                                <div class="card-body">
                            <!--begin::Form-->
                            <form class="form container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label d-flex justify-content-between w-100 border-bottom">
                                        <h3 class="kt-portlet__head-title">Pricing</h3>
                                        <div class="price_right_content">
                                            {{--<span><a href="#">Payment history</a></span>--}}
                                            <span class="desktop_show m-1"><a href="#">How to invoice</a></span>
                                            <span class=""><a href="#">Download invoice</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="price_content mt-1">
                                        <p class="d-flex justify-content-between">
                                            <span>Your fee</span><span>${{ sbNumberFormat($move->total_price*0.8) }}</span></p>
                                        <p class="d-flex justify-content-between">
                                            <span>Muval fee <span="mobile_show"></span>
                                            </span>
                                            <span> ${{ sbNumberFormat($move->total_price*0.2) }}</span>
                                        </p>
                                        <p class="d-flex justify-content-between fw-bolder fs-5">
                                            <span>Total price</span><span>${{ sbNumberFormat($move->total_price) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </form><!--end::Form-->
                        </div></div>
                    </div>
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                            <div class="card">
                                <div class="card-body">
                            <!--begin::Form-->
                            <form class="form container_space">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label mb-1 border-bottom">
                                        <h3 class="kt-portlet__head-title">{{ trans('move::move.inventory') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    @if(isset($move->inventory) && $move->inventory !== null && $move->inventory !== '')
                                        @foreach(json_decode($move->inventory) as $inventory)
                                            <div class="job_bedroom_content mt-2">
                                                <h5 class="mb-75" class="align-items-center">
                                                    <i data-feather='shopping-bag'></i>
                                                    {{ $inventory->title }}
                                                    <span>({{ count($inventory->items) }} items)</span>
                                                </h5>
                                                <div class="job_bedroom_details">
                                                    <div class="job_bedroom_title">
                                                        <div class="row">
                                                            @foreach($inventory->items as $item)
                                                                <div class="col-lg-6">
                                                                    <div class="row">
                                                                        <p class="col-lg-6">{{ replaceUnderscoreWithSpace($item->item) }}:</p>
                                                                        <p class="col-lg-6">{{ $item->value }}m3</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                </div>
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--begin::Form-->
                            <div class="card">
                                <div class="card-body">
                            <form class="form">
                                <div class="kt-portlet__head d-flex justify-content-between  border-bottom mb-1">
                                    <div class="kt-portlet__head-label ">
                                        <h3 class="kt-portlet__head-title">{{ trans('move::move.deals') }}</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar ">
                                        @if($accessLevel == 0)
                                            <div class="kt-portlet__head-toolbar">
                                                <a href="{{ route('move.deal.create', $move->id) }}"
                                                   class="header_button" title="{{ trans('common.add_deal') }}">
                                                   <i class="flaticon2-plus"></i> {{ trans('common.add_deal') }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="user-list-table table" id="deal-table">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="20%">{{ trans('common.total_price') }}</th>
                                            <th>{{ trans('common.deposit') }}</th>
                                            <th width="20%">{{ trans('common.fee') }}</th>
                                            <th width="20%">{{ trans('common.actions') }}</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                </div>
            </div>
                    <div class="col-lg-12">
                            <!--begin::Form-->
                            <div class="card">
                                <div class="card-body">
                            <form class="form">
                                <div class="kt-portlet__head d-flex justify-content-between  border-bottom mb-1">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ trans('move::move.ancillary_services') }}
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        @if($accessLevel == 0)
                                            <div class="kt-portlet__head-toolbar">
                                                <a href="{{ route('ancillaryservice.create') }}" class="header_button"
                                                   title="{{ trans('common.add_deal') }}"><i
                                                        class="flaticon2-plus"></i> {{ trans('move::move.add_ancillary') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-datatable table-responsive pt-0">
                                    <table class="user-list-table table" id="ancillaryservice-table">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="">{{ trans('common.type') }}</th>
                                            <th>{{ trans('common.price') }}</th>
                                            <th width="">{{ trans('common.actions') }}</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                    </div>
                </div>
                    @if($accessLevel == 0)
                        <div class="col-lg-12">
                                <!--begin::Form-->
                                <div class="card">
                                    <div class="card-body">
                                <form class="form">
                                    <div class="kt-portlet__head  border-bottom mb-1">
                                        <div class="kt-portlet__head-label ">
                                            <h3 class="kt-portlet__head-title"> {{ trans('move::move.payment') }}
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-datatable table-responsive pt-0">
                                        <table class="user-list-table table" id="payment-table">
                                            <thead class="table-light">
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

                                </form>
                                <!--end::Form-->
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="col-lg-12 job_view_map" style="height:320px">
                        <div class="kt-portlet">
                            <div class="job_map" id="map"></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12 transfer_job_section" style="margin-top: 15px;">
                            <div class="row">
                                @php
                                    $checkNoParking = false;
                                    if( isset($move->start_access) && $move->start_access !== null && $move->start_access !== ''){
                                        $startAccess = json_decode($move->start_access);
                                        foreach ($startAccess as $key => $val) {
                                            if ($val->item === 'No parking') {
                                                $checkNoParking = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                            @if($checkNoParking)
                                <p class="price_section d-flex align-items-center mb-20"><img
                                        src="{{ asset('assets/media/price_error.svg') }}">
                                    Restricted access</p>
                            @endif
                            <div class="col-lg-6">
                                <h5 class="mb-75">{{ trans('common.pick_up_from') }}</h5>
                                <p class="card-text">{{ $move->start_addr }} {{ isset($move->start_city) ? ','.$move->start_city : '' }} {{ isset($move->start_postcode) ? ','.$move->start_postcode : '' }}</p>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">{{ trans('common.delivery_to') }}</h5>
                                <p class="card-text">{{ $move->end_addr }}, {{ $move->end_city }}, {{ $move->end_postcode }}</p>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">{{ trans('common.pick_up_date') }}</h5>
                                <p class="card-text">{{ Carbon\Carbon::parse($move->start_date)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">{{ trans('common.delivery_date') }}</h5>
                                <p class="card-text">{{ Carbon\Carbon::parse($move->end_date)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">Start access</h5>
                                @if(isset($move->start_access) && $move->start_access !== null && $move->start_access !== '')
                                    @foreach(json_decode($move->start_access) as $startAccess)
                                        <p class="card-text"> {{ $startAccess->item }}</p>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">End access</h5>
                                @if(isset($move->end_access) && $move->end_access !== null && $move->end_access !== '')
                                    @foreach(json_decode($move->end_access) as $endAccess)
                                        <p> {{ $endAccess->item }}</p>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">Space required</h5>
                                <p>{{ $move->space }}m3</p>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-75">Staff member</h5>
                                <p>1</p>
                            </div>
                                @if($move->status != '' && $move->status != 0 && $move->status != 12)
                                    <div class="col-lg-12 text-right job_details_edit d-flex flex-row-reverse">
                                            <a class="move__job__accept" data-id="{{ $move->id }}" data-url="{{route('move.accept_job_html',    'moveId')}}" data-update="1"><i class="job_edit_icon"
                                        data-toggle="modal" data-target="#jobEditModal" data-feather='edit'></i></a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')

    <script src="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css" rel="stylesheet"/>
    <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script>
        // TO MAKE THE MAP APPEAR YOU MUST
        var centerLatLng = [{{ $move->start_lng }}, {{ $move->start_lat }}];

        // ADD YOUR ACCESS TOKEN FROM
        mapBoxAccessToken = mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        var wayPointsCoordinates = [];
        wayPointsCoordinates[0] = [{{ $move->start_lng }}, {{ $move->start_lat }}];
        wayPointsCoordinates[1] = [{{ $move->end_lng }}, {{ $move->end_lat }}];
        getCoordinates({{ $move->start_lng }}, {{ $move->start_lat }},{{ $move->end_lng }}, {{ $move->end_lat }}, wayPointsCoordinates);

    </script>

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
                    /*{data: 'created_by', name: 'created_by'},
                    {data: 'created_at', name: 'created_at'},*/
                    {data: 'action', name: 'action'},
                ],
                order: [[1, 'desc']],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    feather.replace();
                }
            });

            $('#deal-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });

            /**
             * Ancillary datatable
             * */
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
                    {data: 'price', name: 'price'},
                    {data: 'action', name: 'action'},
                ],
                order: [[1, 'desc']],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    feather.replace();
                }
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
            
                order: [[1, 'desc']],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                lengthMenu: [25, 50, 100],
                displayLength: 25,
                initComplete: function () {
                    feather.replace();
                }
            });

            $('#payment-table tbody').on('click', 'tr', function () {
                var href = $(this).find("a#view").attr('href');
                if (href) {
                    $(location).attr('href', href);

                }
            });

        });

        $('.sidebar_job_checklist .day .local_circle').click(function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).find('input[name="checklists[]"]').prop('checked', false);
            } else {
                $(this).addClass('active');
                $(this).find('input[name="checklists[]"]').prop('checked', true);
            }
            let checklists = [];
            $('input[type=checkbox][class=_job_checklist_click]:checked').each(function () {
                let checklistVal = (this.checked ? $(this).val() : "");
                if (checklistVal != '')
                    checklists.push(checklistVal);
            });

            console.log(checklists);

            let url = '{{ route('move.update_checklist', $move->id) }}';

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    checklists: checklists,
                },
                success: function (data) {
                },
                error: function (data) {
                    alert("Something Went Wrong!");
                }
            });
        });
    </script>
@stop
