@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.deal')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.deal'),
        'subTitle' => isset($deal) ? trans('common.edit'). ' '. trans('common.deal') : trans('common.add').' '. trans('common.deal') ,
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

    @section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
@stop

@section('content')
    <!-- Page content -->
    <section class="app-user-edit">
        <!--begin::Form-->
        @if(isset($deal))
            {{ Form::model($deal, [
            'route' => [$moduleName.'.update', $deal->id],
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
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="totap_price">{{ trans('move::deal.total_price') }}:
                                    </label>
                                    {!!  Form::text('total_price', old('total_price'),[
                                        'id' => 'total_price',
                                        'class' => 'form-control '. (($errors->has('total_price')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Total Price'
                                        ]) !!}
                                    @if($errors->has('total_price'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('total_price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="deposit">
                                       {{ trans('move::deal.deposit') }}:
                                    </label>
                                    {!!  Form::text('deposit', old('deposit'),[
                                        'id' => 'deposit',
                                        'class' => 'form-control '. (($errors->has('deposit')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter truck deposit'
                                        ]) !!}
                                    @if($errors->has('deposit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deposit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="totap_price">{{ trans('move::deal.fee') }}:
                                    </label>
                                    {!!  Form::text('fee', old('fee'),[
                                        'id' => 'fee',
                                        'class' => 'form-control '. (($errors->has('fee')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Total Price'
                                        ]) !!}
                                    @if($errors->has('fee'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('fee') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
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

    <style>
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
    </style>
@stop


