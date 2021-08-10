@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.fleet')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => 'Fleets',
        'subTitle' => isset($truck) ? 'Edit Fleet' : 'Add Fleet' ,
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
        @if(isset($truck))
            {{ Form::model($truck, [
            'route' => [$moduleName.'.update', $truck->id],
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
                                            {{ trans('truck::truck.company') }}<span class="required"> * </span>
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
                                    <label class="form-label" for="name">
                                        {{ trans('truck::truck.truck_name') }}
                                    </label>
                                    {!!  Form::text('name', old('name'),[
                                        'id' => 'name',
                                        'class' => 'form-control '. (($errors->has('name')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter Truck Name'
                                        ]) !!}
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label" for="capacity">
                                        {{ trans('truck::truck.capacity') }}
                                    </label>
                                    {!!  Form::text('capacity', old('capacity'),[
                                        'id' => 'capacity',
                                        'class' => 'form-control '. (($errors->has('capacity')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter truck Capacity'
                                        ]) !!}
                                    @if($errors->has('capacity'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('capacity') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="description">
                                        {{ trans('About truck') }}
                                    </label>
                                    {!!  Form::textarea('description', old('description'),[
                                        'id' => 'description',
                                        'rows' => '3', 'cols' => '5',
                                        'class' => 'form-control '. (($errors->has('description')) ? 'is-invalid' : ''),
                                        'placeholder' => 'Please enter truck description'
                                        ]) !!}
                                    @if($errors->has('description'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>
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


