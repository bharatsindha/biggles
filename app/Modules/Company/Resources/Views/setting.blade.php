@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.settings')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Interstate settings' ])
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
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Pricing</h3>
                            </div>
                        </div>
                        <!--begin::Form-->
                        @if(isset($company))
                            {{ Form::model($company, ['route' => ['company.update', $company->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'company.store']) }}
                        @endif
                        @csrf
                        {!!  Form::hidden('inter_state_id', isset($company->interState->id) && $company->interState->id>0 ? $company->interState->id : 0, ['id' => 'inter_state_id','class' => 'form-control']) !!}
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.min_price') }}:</label>
                                    <div class="input-group">
                                        {!!  Form::number('min_price',isset($company->interState->min_price) ? $company->interState->min_price : old('min_price'),['id' => 'min_price','class' => 'form-control','placeholder' => 'Please enter min price','required' => 'required', 'step'=>"any"]) !!}
                                        @if($errors->has('min_price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('min_price') }}
                                            </div>
                                        @endif
                                        <span>Minimum total job price</span>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label class="">{{ trans('company::company.stairs') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::number('stairs', isset($company->interState->stairs) ? $company->interState->stairs : old('stairs'),['id' => 'stairs','class' => 'form-control','placeholder' => 'Please enter stairs','required' => 'required', 'step'=>"any"]) !!}
                                        @if($errors->has('stairs'))
                                            <div class="text text-danger">
                                                {{ $errors->first('stairs') }}
                                            </div>
                                        @endif
                                        <span>Stairs</span>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2">m<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 box_space">
                                    <label>{{ trans('company::company.elevator') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::number('elevator', isset($company->interState->elevator) ? $company->interState->elevator : old('elevator'),['id' => 'elevator','class' => 'form-control','placeholder' => 'Please enter elevator','required' => 'required', 'step'=>"any"]) !!}
                                        @if($errors->has('elevator'))
                                            <div class="text text-danger">
                                                {{ $errors->first('elevator') }}
                                            </div>
                                        @endif
                                        <span>Elevator</span>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2">m<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label class="">{{ trans('company::company.long_driveway') }}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::number('long_driveway', isset($company->interState->long_driveway) ? $company->interState->long_driveway : old('long_driveway'),['id' => 'long_driveway','class' => 'form-control','placeholder' => 'Please enter long driveway','required' => 'required', 'step'=>"any"]) !!}
                                        @if($errors->has('long_driveway'))
                                            <div class="text text-danger">
                                                {{ $errors->first('long_driveway') }}
                                            </div>
                                        @endif
                                        <span>Steep Driveway</span>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2">m<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.ferry_vehicle') }}:</label>
                                    {!!  Form::number('ferry_vehicle', isset($company->interState->ferry_vehicle) ? $company->interState->ferry_vehicle : old('ferry_vehicle'),['id' => 'ferry_vehicle','class' => 'form-control','placeholder' => 'Please enter ferry vehicle','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('ferry_vehicle'))
                                        <div class="text text-danger">
                                            {{ $errors->first('ferry_vehicle') }}
                                        </div>
                                    @endif
                                    <span>Ferry Vehicle</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.heavy_items') }}:</label>
                                    {!! Form::number('heavy_items', isset($company->interState->heavy_items) ? $company->interState->heavy_items : old('heavy_items'),['id' => 'heavy_items','class' => 'form-control','placeholder' => 'Please enter heavy items','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('heavy_items'))
                                        <div class="text text-danger">
                                            {{ $errors->first('heavy_items') }}
                                        </div>
                                    @endif
                                    <span>Heavy Items</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.extra_kms') }}:</label>
                                    {!!  Form::number('extra_kms', isset($company->interState->extra_kms) ? $company->interState->extra_kms : old('extra_kms'),['id' => 'extra_kms','class' => 'form-control','placeholder' => 'Please enter extra kms','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('extra_kms'))
                                        <div class="text text-danger">
                                            {{ $errors->first('extra_kms') }}
                                        </div>
                                    @endif
                                    <span>Extra kms</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.packaging') }}:</label>
                                    {!!  Form::number('packing', isset($company->interState->packing) ? $company->interState->packing : old('packing'),['id' => 'packing','class' => 'form-control','placeholder' => 'Please enter packing','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('packing'))
                                        <div class="text text-danger">
                                            {{ $errors->first('packing') }}
                                        </div>
                                    @endif
                                    <span>Packing</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.free_storage') }}</label>
                                    <span>{{ trans('company::company.free_storage') }}</span>
                                    <div class="col-3">
                                            <span class="switch">
                                                <label style="display: block;">
                                                    <input name="storage_toggle" id="storage_toggle" type="checkbox"
                                                           {{ $company->interState->storage_toggle == 1 ? 'checked' : '' }}>
                                                    <span></span>
                                                </label>
                                            </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 {{ $company->interState->storage_toggle != 1 ? 'd-none' : '' }} __toggle__free__storage__weeks">
                                    <label class="">{{ trans('company::company.how_many_weeks') }}:</label>
                                    {!!  Form::number('free_storage_weeks', isset($company->interState->free_storage_weeks) ? $company->interState->free_storage_weeks : old('free_storage_weeks'),['id' => 'free_storage_weeks','class' => 'form-control','placeholder' => 'Please enter weeks', 'step'=>"any"]) !!}
                                    @if($errors->has('free_storage_weeks'))
                                        <div class="text text-danger">
                                            {{ $errors->first('free_storage_weeks') }}
                                        </div>
                                    @endif
                                    <span>{{ trans('company::company.how_many_weeks') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.storage') }}:</label>
                                    {!!  Form::number('storage', isset($company->interState->storage) ? $company->interState->storage : old('storage'),['id' => 'storage','class' => 'form-control','placeholder' => 'Please enter storage','required' => 'required', 'step'=>"any"]) !!}
                                    @if($errors->has('storage'))
                                        <div class="text text-danger">
                                            {{ $errors->first('storage') }}
                                        </div>
                                    @endif
                                    <span>Storage</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.storage_cost') }}:</label>
                                    {!!  Form::number('storage_cost',isset($company->interState->storage_cost) ? $company->interState->storage_cost : old('storage_cost'),['id' => 'storage_cost','class' => 'form-control','placeholder' => trans('company::company.storage_cost'), 'step'=>"any"]) !!}
                                    @if($errors->has('storage_cost'))
                                        <div class="text text-danger">
                                            {{ $errors->first('storage_cost') }}
                                        </div>
                                    @endif
                                    <span>{{ trans('company::company.storage_cost') }}</span>
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
    <script type="application/javascript">
        $(document).ready(function () {
            $("#storage_toggle").change(function () {
                // showRecurring(this.checked);
                $(".__toggle__free__storage__weeks").addClass("hide");
                if (this.checked) {
                    $(".__toggle__free__storage__weeks").removeClass("hide");
                }
                $("#storage_toggle").prop('checked', this.checked);
            });

        });
    </script>
@stop
