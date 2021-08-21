@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.ancillaryservice')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.ancillaryservice'),
        'subTitle' => isset($ancillaryservice) ? trans('common.edit'). ' '. trans('common.ancillaryservice') : trans('common.add').' '. trans('common.ancillaryservice') ,
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

    @section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent !important;
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

@section('content')
    <!-- Page content -->
    @if(isset($ancillaryservice))
    {{ Form::model($ancillaryservice, [
        'route' => [$moduleName.'.update', $ancillaryservice->id],
        'method' => 'patch',
        'class' => 'form-validate'
        ]) }}
    @else
    {{ Form::open(['route' => $moduleName.'.store', 'class' => 'form-validate']) }}
            @endif      
            @csrf
            <div class="row">
                <div class="col-lg-8 col-12">
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
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label class="form-label" for="type">{{ trans('ancillaryservice::ancillary.type') }}<span class="required"> * </span></label>
                                    {!!  Form::select('type', $data['ancillaryType'], old('type'),['id' => 'type','class' => 'form-select select2 form-control __ancillary__type__toggle'.
                                    (($errors->has('type')) ? 'is-invalid' : ''),
                                     'placeholder' => 'Please select type','required' => 'required']) !!}
                                    @if($errors->has('type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('type') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="row __toggle__insurance d-none __common_ancillaries">
                                <div class="col-lg-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="premium">{{ trans('ancillaryservice::ancillary.premium') }}:</label>
                                        {!!  Form::select('premium', $data['ancillaryPremium'], old('premium'),['id' => 'premium','class' => 'form-select select2 form-control'.
                                        (($errors->has('premium')) ? 'is-invalid' : ''),
                                         'placeholder' => 'Please select premium']) !!}
                                        @if($errors->has('premium'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('premium') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="basis">{{ trans('ancillaryservice::ancillary.basis') }}
                                            :</label>
                                        {!!  Form::select('basis', $data['ancillaryBasis'], old('basis'),['id' => 'basis','class' => 'form-select select2 form-control'.
                                        (($errors->has('basis')) ? 'is-invalid' : ''),
                                         'placeholder' => 'Please select basis']) !!}
                                        @if($errors->has('basis'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('basis') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row __toggle__insurance d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.add_ons') }}:</label>
                                    {!!  Form::text('add_ons', old('add_ons'),['id' => 'add_ons','class' => 'form-control'. (($errors->has('add_ons')) ? 'is-invalid' : ''),
                                     'placeholder' => 'Please enter Add Ons']) !!}
                                    @if($errors->has('add_ons'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('add_ons') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-1 box_space">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.valued_inventory') }}
                                        :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('valued_inventory', old('valued_inventory'),['id' => 'valued_inventory','class' => 'form-control number-format'. (($errors->has('valued_inventory')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter valued inventory','onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('valued_inventory'))
                                           <div class="invalid-feedback">
                                                {{ $errors->first('valued_inventory') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row __toggle__packaging d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.boxes') }}:</label>
                                    {!!  Form::number('boxes', old('boxes'),['id' => 'boxes','class' => 'form-control'. (($errors->has('boxes')) ? 'is-invalid' : ''),'placeholder' => 'Please enter boxes']) !!}
                                    @if($errors->has('boxes'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('boxes') }}
                                        </div>
                                     @endif                                   
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.large_boxes') }}:</label>
                                    {!!  Form::number('large_boxes', old('large_boxes'),['id' => 'large_boxes','class' => 'form-control'. (($errors->has('large_boxes')) ? 'is-invalid' : ''),'placeholder' => 'Please enter large boxes']) !!}
                                    @if($errors->has('large_boxes'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('large_boxes') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row __toggle__packaging d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.small_boxes') }}:</label>
                                    {!!  Form::number('small_boxes', old('small_boxes'),['id' => 'small_boxes','class' => 'form-control'. (($errors->has('small_boxes')) ? 'is-invalid' : ''),'placeholder' => 'Please enter small boxes']) !!}
                                    @if($errors->has('small_boxes'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('small_boxes') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.paper') }}:</label>
                                    {!!  Form::number('paper', old('paper'),['id' => 'paper','class' => 'form-control'. (($errors->has('paper')) ? 'is-invalid' : ''),'placeholder' => 'Please enter paper']) !!}
                                    @if($errors->has('paper'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('paper') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row __toggle__packaging d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.tape') }}:</label>
                                    {!!  Form::number('tape', old('tape'),['id' => 'tape','class' => 'form-control'. (($errors->has('tape')) ? 'is-invalid' : ''),'placeholder' => 'Please enter tape']) !!}
                                    @if($errors->has('tape'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('tape') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                                <div class="row __toggle__car__transport d-none __common_ancillaries">
                                    <div class="col-lg-6">
                                        <div class="mb-1">
                                            <label class="form-label" for="pickup_toggle">{{ trans('ancillaryservice::ancillary.pickup_toggle') }}
                                                :</label>
                                            {!!  Form::select('pickup_toggle', $data['ancillaryPickupToggle'], old('pickup_toggle'),['id' => 'pickup_toggle','class' => 'form-select select2 form-control'. (($errors->has('pickup_toggle')) ? 'is-invalid' : ''), 'placeholder' => 'Please select pickup toggle']) !!}
                                            @if($errors->has('pickup_toggle'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pickup_toggle') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-1">
                                            <label class="form-label" for="pickup_depot">{{ trans('ancillaryservice::ancillary.pickup_depot') }}
                                                :</label>
                                            {!!  Form::select('pickup_depot', $data['ancillaryPickupDepot'], old('pickup_depot'),['id' => 'pickup_depot','class' => 'form-select select2 form-control'. (($errors->has('pickup_depot')) ? 'is-invalid' : ''), 'placeholder' => 'Please select pickup depot']) !!}
                                            @if($errors->has('pickup_depot'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pickup_depot') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            <div class="row __toggle__car__transport d-none __common_ancillaries">
                                <div class="col-lg-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="delivery_toggle">{{ trans('ancillaryservice::ancillary.delivery_toggle') }}
                                            :</label>
                                        {!!  Form::select('delivery_toggle', $data['ancillaryDeliveryToggle'], old('delivery_toggle'),['id' => 'delivery_toggle','class' => 'form-select select2 form-control'. (($errors->has('delivery_toggle')) ? 'is-invalid' : ''), 'placeholder' => 'Please select delivery toggle']) !!}
                                        @if($errors->has('delivery_toggle'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_toggle') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="delivery_depot">{{ trans('ancillaryservice::ancillary.delivery_depot') }}
                                            :</label>
                                        {!!  Form::select('delivery_depot', $data['ancillaryDeliveryDepot'], old('delivery_depot'),['id' => 'delivery_depot','class' => 'form-select select2 form-control'. (($errors->has('delivery_depot')) ? 'is-invalid' : ''), 'placeholder' => 'Please select delivery depot']) !!}
                                        @if($errors->has('delivery_depot'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_depot') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>                                
                            </div>
                            <div class="row __toggle__car__transport d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.car_rego') }}:</label>
                                    {!!  Form::text('car_rego', old('car_rego'),['id' => 'car_rego','class' => 'form-control'. (($errors->has('car_rego')) ? 'is-invalid' : ''),'placeholder' => 'Please enter car rego']) !!}
                                    @if($errors->has('car_rego'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('car_rego') }}
                                        </div>
                                    @endif
                                    
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.car_make') }}:</label>
                                    {!!  Form::text('car_make', old('car_make'),['id' => 'car_make','class' => 'form-control'. (($errors->has('car_make')) ? 'is-invalid' : ''),'placeholder' => 'Please enter car make']) !!}
                                    @if($errors->has('car_make'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('car_make') }}
                                        </div>
                                    @endif
                                  
                                </div>
                            </div>
                            <div class="row __toggle__car__transport d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.car_model') }}:</label>
                                    {!!  Form::text('car_model', old('car_model'),['id' => 'car_model','class' => 'form-control'. (($errors->has('car_model')) ? 'is-invalid' : ''),'placeholder' => 'Please enter car model']) !!}
                                    @if($errors->has('car_model'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('car_model') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="car_type">{{ trans('ancillaryservice::ancillary.car_type') }}
                                            :</label>
                                        {!!  Form::select('car_type', $data['ancillaryCarType'], old('car_type'),['id' => 'car_type','class' => 'form-select select2 form-control'. (($errors->has('car_type')) ? 'is-invalid' : ''), 'placeholder' => 'Please select car type']) !!}
                                        @if($errors->has('car_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('car_type') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row __toggle__cleaning d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.cleaning_options') }}:</label>
                                    {!!  Form::text('cleaning_options', old('cleaning_options'),['id' => 'cleaning_options','class' => 'form-control'. (($errors->has('cleaning_options')) ? 'is-invalid' : ''),'placeholder' => 'Please enter cleaning options']) !!}
                                    @if($errors->has('cleaning_options'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('cleaning_options') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.carpet_area') }}:</label>
                                    {!!  Form::number('carpet_area', old('carpet_area'),['id' => 'carpet_area','class' => 'form-control'. (($errors->has('carpet_area')) ? 'is-invalid' : ''),'placeholder' => 'Please enter carpet area']) !!}
                                    @if($errors->has('carpet_area'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('carpet_area') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row __toggle__cleaning d-none __common_ancillaries">
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.curtains') }}:</label>
                                    {!!  Form::number('curtains', old('curtains'),['id' => 'curtains','class' => 'form-control'. (($errors->has('curtains')) ? 'is-invalid' : ''),'placeholder' => 'Please enter curtains']) !!}
                                    @if($errors->has('curtains'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('curtains') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.blinds') }}:</label>
                                    {!!  Form::number('blinds', old('blinds'),['id' => 'blinds','class' => 'form-control'. (($errors->has('blinds')) ? 'is-invalid' : ''),'placeholder' => 'Please enter blinds']) !!}
                                    @if($errors->has('blinds'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('blinds') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mt-1 box_space">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.total_price') }}:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend bg-light-primary">
                                            <span class="input-group-text bg-light-primary" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('price', old('price'),['id' => 'price','class' => 'form-control number-format'. (($errors->has('price')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter total price', 'required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('price'))
                                           <div class="invalid-feedback">
                                                {{ $errors->first('price') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-12">
                                    <label class="form-label">{{ trans('ancillaryservice::ancillary.about') }}:</label>
                                    {!! Form::textarea('about', old('about'), ['class'=>'form-control'. (($errors->has('about')) ? 'is-invalid' : ''),'id' => 'about', 'rows' => '3', 'cols' => '5', 'placeholder' =>'About US']) !!}
                                    @if($errors->has('about'))
                                       <div class="invalid-feedback">
                                            {{ $errors->first('about') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="mt-1 form-label"> Add questions to ask the customer when using this service.</p>
                            @if(isset($ancillaryservice->questions) && $ancillaryservice->questions!=null && count($ancillaryservice->questions)>0 )
                                @foreach($ancillaryservice->questions as $key => $question)
                                    <div class="row mb-1" >
                                        <div class="col-lg-12">
                                            {!!  Form::text('questions[]', $question,['class' => 'form-control', 'data-question-index' => $key,'placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                            <span>{{ trans('ancillaryservice::ancillary.question') }} {{ $key + 1 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-1" >
                                    <div class="col-lg-12">
                                        {!!  Form::text('questions[]', '',['class' => 'form-control', 'data-question-index' => '0','placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                        <span class="form-label">{{ trans('ancillaryservice::ancillary.question') }} 1</span>
                                    </div>
                                </div>
                            @endif
                            <div class="row d-none mb-1" id="ancillaryQuestions">
                                <div class="col-lg-12">
                                    {!!  Form::text('questions[]', '',['class' => 'form-control', 'data-question-index' => '0','placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                    <span>{{ trans('ancillaryservice::ancillary.question') }}</span>
                                </div>
                            </div>
                                <a href="#" class="d-flex justify-content-center align-content-center addAncillaryQuestion">Add another question</a>
                    <!--end::Form-->
                </div>
            </div>
        </div>
                <div class="col-lg-4"></div>
                </div>
            @include('layouts.forms.actions')
            {{ Form::close() }}
    <!-- /page content -->
@stop

@section('scripts')
    <script type="text/javascript" src="{!! asset('assets/js/jquery.formatCurrency-1.4.0.js') !!}"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-select2.min.js') }}"></script>
    <script>
        $(function () {
            $('.commonDatepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            ancillaryTypeManage();
        });

        function formatPrice(obj) {
            // $(obj).formatCurrency();
        }

        $(function () {
            // $('.number-format').formatCurrency();
        });

        $(document).ready(function () {
            let questionIndex = {{ isset($ancillaryservice->questions) && $ancillaryservice->questions!=null && count($ancillaryservice->questions)>0
? (count($ancillaryservice->questions) - 1) : 0 }};
            $('.addAncillaryQuestion').click(function (e) {
                questionIndex++;
                let $template = $('#ancillaryQuestions'),
                    $clone = $template
                        .clone()
                        .removeClass('d-none')
                        .removeAttr('id')
                        .attr('data-question-index', questionIndex)
                        .insertBefore($template);
                $clone.find('span').html("{{ trans('ancillaryservice::ancillary.question') }} "+ (questionIndex+1)).end();
                e.preventDefault();
                return false;
            });
        });
    </script>
@stop
