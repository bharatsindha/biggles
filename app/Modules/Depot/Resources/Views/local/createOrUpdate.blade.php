@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($local) ? 'Edit local price' : 'Add local price' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet">

                        <!--begin::Form-->
                        @if(isset($local))
                            {{ Form::model($local, ['route' => ['local.update', $local->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'local.store']) }}
                        @endif
                        @csrf

                        {{--{!!  Form::hidden('depot_id', isset($local->depot_id) && $local->depot_id>0 ? $local->depot_id : $depot->id, ['id' => 'depot_id','class' => 'form-control']) !!}--}}
                        {{--{!!  Form::hidden('company_id', isset($local->company_id) && $local->company_id>0 ? $local->company_id : $depot->company_id, ['id' => 'company_id','class' => 'form-control']) !!}--}}
                        <div class="kt-portlet__body">
                            @if(\App\Facades\General::isSuperAdmin())
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('common.company') }}
                                            :</label>
                                        {!!  Form::select('company_id', $data['companyOptions'], old('company_id'),['id' => 'company_id','class' => 'form-control', 'placeholder' => 'Please select Company','required' => 'required']) !!}
                                        @if($errors->has('company_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('common.company') }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label><span class="required"> * </span>{{ trans('depot::depot.name') }}:</label>
                                    {!!  Form::select('depot_id', $depotOptions, old('depot_id'),['id' => 'depot_id','class' => 'form-control', 'placeholder' => 'Please select Depot','required' => 'required']) !!}
                                    @if($errors->has('depot_id'))
                                        <div class="text text-danger">
                                            {{ $errors->first('depot_id') }}
                                        </div>
                                    @endif
                                    <span>Depot</span>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label>{{ trans('depot::depot.price_per') }}({{ trans('depot::depot.per_hour') }}):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('price_per', old('price_per'),['id' => 'price_per','class' => 'form-control number-format', 'placeholder' => 'Please enter Price', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('price_per'))
                                            <div class="text text-danger">
                                                {{ $errors->first('price_per') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('common.price_for_two_movers') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('depot::depot.radius') }}:</label>
                                    <div class="input-group">
                                        {!!  Form::text('radius', old('radius'),['id' => 'radius','class' => 'form-control','placeholder' => 'Please enter radius']) !!}
                                        @if($errors->has('radius'))
                                            <div class="text text-danger">
                                                {{ $errors->first('radius') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('common.travel_radius') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label class="">{{ trans('depot::depot.extra_person_price') }}({{ trans('depot::depot.per_hour') }}):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('extra_person_price', old('extra_person_price'),['id' => 'extra_person_price','class' => 'form-control number-format','placeholder' => 'Please enter Extra Person Price', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('extra_person_price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('extra_person_price') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('common.extra_person_price') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="days_main_section">
                                <h4>Apply on</h4>
                                <div class="day_section d-flex">
                                    @foreach($weekDaysArr as $weekDay)
                                    <div class="day d-flex align-items-center">
                                        <span class="local_circle {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'active' : '' }}"><input type="checkbox" name="weekdays[]" value="{{ $weekDay }}" {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'checked' : '' }}> <img src="{{ asset('assets/media/right_arrow.svg') }}"></span> <span>{{ ucfirst($weekDay) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @include('layouts.forms.actions')

                    {{ Form::close() }}

                    <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>
    <script>
        function formatPrice(obj) {
            // $(obj).formatCurrency();
        }

        $(function () {
            // $('.number-format').formatCurrency();
        })
    </script>
@stop
