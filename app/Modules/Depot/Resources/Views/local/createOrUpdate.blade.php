@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.local'),
        'subTitle' => isset($local) ? trans('common.edit'). ' '. trans('common.local') : trans('common.add').' '. trans('common.local') ,
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

    @section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <style>

.select2-container .select2-selection--single {
            height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 18px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px;
        }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: none;
            border-width: 5px 4px 0 4px;
            height: 9px;
            left: 50%;
            margin-left: -15px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
        
            .form-control{
        border-color: #AAAAAA;
        }

        </style>
@stop

@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->
                        @if(isset($local))
                        {{ Form::model($local, [
                            'route' => [$moduleName.'.update', $local->id],
                            'method' => 'patch',
                            'class' => 'form-validate'
                            ]) }}
                        @else
                        {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
                        @endif
                        @csrf
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <div class="card">
                    <div class="card-body">
                       <div class="row">
                            @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level @endphp
                            @if($userAccess != 1)
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="company_id">
                                            {{ trans('common.company') }}<span class="required"> * </span>
                                        </label>
                                        {!!  Form::select('company_id', $data['companyOptions'] , old('company_id'),[
                                            'id' => 'company_id',
                                            'class' => 'form-select select2'.
                                            (($errors->has('company_id')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Company',
                                            'required' => 'required'
                                            ]) !!}
                                        @if($errors->has('company_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                                 <div class="col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="depot_id">
                                        {{ trans('depot::depot.name') }}:<span class="required"> * </span>
                                        </label>
                                        {!!  Form::select('depot_id', $depotOptions, old('depot_id'),[
                                            'id' => 'depot_id',
                                            'class' => 'form-select select2'.
                                            (($errors->has('depot_id')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Depot',
                                            'required' => 'required'
                                            ]) !!}
                                        @if($errors->has('depot_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('depot_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="price_per">
                                        {{ trans('common.price_for_two_movers') }}:
                                    </label>
                                    <div class="input-group mb-1">
                                    <div class="input-group-prepend bg-light-primary">
                                        <span class="input-group-text bg-light-primary" id="basic-addon1">$</span>
                                    </div>
                                    {!!  Form::text('price_per', old('price_per'),[
                                        'id' => 'price_per',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('price_per')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Price', 'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('price_per'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('price_per') }}
                                        </div>
                                    @endif
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                    <label class="form-label" for="radius">
                                        {{ trans('common.travel_radius') }}:
                                    </label>
                                    <div class="input-group mb-1">
                                    {!!  Form::text('radius', old('radius'),[
                                        'id' => 'radius',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('radius')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Radius',
                                        ]) !!}
                                    @if($errors->has('radius'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('radius') }}
                                        </div>
                                    @endif
                                    <div class="input-group-prepend bg-light-primary">
                                        <span class="input-group-text bg-light-primary" id="basic-addon1">m</span>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-6">  
                                    <label class="form-label" for="extra_person_price">
                                        {{ trans('common.extra_person_price') }}:
                                    </label>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend bg-light-primary">
                                            <span class="input-group-text bg-light-primary" id="basic-addon1">$</span>
                                        </div>
                                    {!!  Form::text('extra_person_price', old('extra_person_price'),[
                                        'id' => 'extra_person_price',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('extra_person_price')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Price', 'onchange'=>'javascript:formatPrice(this);'
                                        ]) !!}
                                    @if($errors->has('extra_person_price'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('extra_person_price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="days_main_section">
                            <h4 class="mb-0 mt-1">Apply on</h4>
                            <div class="day_section d-flex demo-inline-spacing">
                                @foreach($weekDaysArr as $weekDay)
                                <div class="day d-flex align-items-center form-check form-check-inline">
                                    <span class="local_circle{{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'active' : '' }}">
                                        <input type="checkbox" class="form-check-input" name="weekdays[]" value="{{ $weekDay }}" id="{{ $weekDay }}" {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'checked' : '' }}>
                                    </span>
                                    <label for="{{ $weekDay }}">{{ ucfirst($weekDay) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.forms.actions')
        {{ Form::close() }}
    </section>     
    <!-- /page content -->
@stop

@section('scripts')
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-select2.min.js') }}"></script>

    <script>
        function formatPrice(obj) {
            // $(obj).formatCurrency();
        }

        $(function () {
            // $('.number-format').formatCurrency();
        })
    </script>
@stop