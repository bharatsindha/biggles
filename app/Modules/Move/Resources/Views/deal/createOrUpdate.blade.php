@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.deal')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($deal) ? 'Edit deal' : 'Add deal' ])
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
                        @if(isset($deal))
                            {{ Form::model($deal, ['route' => ['deal.update', $deal->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'deal.store']) }}
                        @endif
                        @csrf

                        {!!  Form::hidden('move_id', isset($deal->move_id) && $deal->move_id>0 ? $deal->move_id : $move->id, ['id' => 'move_id','class' => 'form-control']) !!}
                        {{--{!!  Form::hidden('company_id', isset($deal->company_id) && $deal->company_id>0 ? $deal->company_id : $move->company_id, ['id' => 'company_id','class' => 'form-control']) !!}--}}
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('move::deal.total_price') }}:</label>
                                    {!!  Form::text('total_price', old('total_price'),['id' => 'total_price','class' => 'form-control number-format', 'placeholder' => 'Please enter Total Price', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                    @if($errors->has('total_price'))
                                        <div class="text text-danger">
                                            {{ $errors->first('total_price') }}
                                        </div>
                                    @endif
                                    <span>Total Price</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::deal.deposit') }}:</label>
                                    {!!  Form::text('deposit', old('deposit'),['id' => 'deposit','class' => 'form-control number-format','placeholder' => 'Please enter Deposit', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                    @if($errors->has('deposit'))
                                        <div class="text text-danger">
                                            {{ $errors->first('deposit') }}
                                        </div>
                                    @endif
                                    <span>Deposit</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('move::deal.fee') }}:</label>
                                    {!!  Form::text('fee', old('fee'),['id' => 'fee','class' => 'form-control number-format','placeholder' => 'Please enter Fee', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                    @if($errors->has('fee'))
                                        <div class="text text-danger">
                                            {{ $errors->first('fee') }}
                                        </div>
                                    @endif
                                    <span>Fee</span>
                                </div>

                                @if(\App\Facades\General::isSuperAdmin())
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
                                @endif
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
            $(obj).formatCurrency();
        }

        $(function () {
            $('.number-format').formatCurrency();
        })
    </script>
@stop
