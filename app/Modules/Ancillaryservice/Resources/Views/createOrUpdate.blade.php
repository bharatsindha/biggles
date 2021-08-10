@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.ancillaryservice')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($ancillaryservice) ? 'Edit ancillary' : 'Add ancillary' ])
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
                        @if(isset($ancillaryservice))
                            {{ Form::model($ancillaryservice, ['route' => ['ancillaryservice.update', $ancillaryservice->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'ancillaryservice.store']) }}
                        @endif
                        @csrf
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                @php use Illuminate\Support\Facades\Auth;$userAccess = Auth::user()->access_level @endphp
                                @if($userAccess != 1)
                                    <div class="col-lg-6">
                                        <label><span class="required"> * </span>{{ trans('move::move.company') }}
                                            :</label>
                                        {!!  Form::select('company_id', $data['companyOptions'], old('company_id'),['id' => 'company_id','class' => 'form-control', 'placeholder' => 'Please select Company','required' => 'required']) !!}
                                        @if($errors->has('company_id'))
                                            <div class="text text-danger">
                                                {{ $errors->first('company_id') }}
                                            </div>
                                        @endif
                                        <span>Company</span>
                                    </div>
                                @endif

                                <div class="col-lg-6">
                                    <label><span
                                            class="required"> * </span>{{ trans('ancillaryservice::ancillary.type') }}:</label>
                                    {!!  Form::select('type', $data['ancillaryType'], old('type'),['id' => 'type','class' => 'form-control __ancillary__type__toggle', 'placeholder' => 'Please select type','required' => 'required']) !!}
                                    @if($errors->has('type'))
                                        <div class="text text-danger">
                                            {{ $errors->first('type') }}
                                        </div>
                                    @endif
                                    <span>Type</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__insurance hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.premium') }}
                                        :</label>
                                    {!!  Form::select('premium', $data['ancillaryPremium'], old('premium'),['id' => 'premium','class' => 'form-control', 'placeholder' => 'Please select premium']) !!}
                                    @if($errors->has('premium'))
                                        <div class="text text-danger">
                                            {{ $errors->first('premium') }}
                                        </div>
                                    @endif
                                    <span>Premium</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.basis') }}:</label>
                                    {!!  Form::select('basis', $data['ancillaryBasis'], old('basis'),['id' => 'basis','class' => 'form-control', 'placeholder' => 'Please select basis']) !!}
                                    @if($errors->has('basis'))
                                        <div class="text text-danger">
                                            {{ $errors->first('basis') }}
                                        </div>
                                    @endif
                                    <span>Basis</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__insurance hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.add_ons') }}:</label>
                                    {!!  Form::text('add_ons', old('add_ons'),['id' => 'add_ons','class' => 'form-control', 'placeholder' => 'Please enter Add Ons']) !!}
                                    @if($errors->has('add_ons'))
                                        <div class="text text-danger">
                                            {{ $errors->first('add_ons') }}
                                        </div>
                                    @endif
                                    <span>Add Ons</span>
                                </div>
                                <div class="col-lg-6 box_space">
                                    <label>{{ trans('ancillaryservice::ancillary.valued_inventory') }}
                                        :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('valued_inventory', old('valued_inventory'),['id' => 'valued_inventory','class' => 'form-control number-format', 'placeholder' => 'Please enter valued inventory','onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('valued_inventory'))
                                            <div class="text text-danger">
                                                {{ $errors->first('valued_inventory') }}
                                            </div>
                                        @endif
                                        <span>Valued Inventory*</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row __toggle__packaging hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.boxes') }}:</label>
                                    {!!  Form::number('boxes', old('boxes'),['id' => 'boxes','class' => 'form-control','placeholder' => 'Please enter boxes']) !!}
                                    @if($errors->has('boxes'))
                                        <div class="text text-danger">
                                            {{ $errors->first('boxes') }}
                                        </div>
                                    @endif
                                    <span>Boxes</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.large_boxes') }}:</label>
                                    {!!  Form::number('large_boxes', old('large_boxes'),['id' => 'large_boxes','class' => 'form-control','placeholder' => 'Please enter large boxes']) !!}
                                    @if($errors->has('large_boxes'))
                                        <div class="text text-danger">
                                            {{ $errors->first('large_boxes') }}
                                        </div>
                                    @endif
                                    <span>Large boxes</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__packaging hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.small_boxes') }}:</label>
                                    {!!  Form::number('small_boxes', old('small_boxes'),['id' => 'small_boxes','class' => 'form-control','placeholder' => 'Please enter small boxes']) !!}
                                    @if($errors->has('small_boxes'))
                                        <div class="text text-danger">
                                            {{ $errors->first('small_boxes') }}
                                        </div>
                                    @endif
                                    <span>Small boxes</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.paper') }}:</label>
                                    {!!  Form::number('paper', old('paper'),['id' => 'paper','class' => 'form-control','placeholder' => 'Please enter paper']) !!}
                                    @if($errors->has('paper'))
                                        <div class="text text-danger">
                                            {{ $errors->first('paper') }}
                                        </div>
                                    @endif
                                    <span>Paper</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__packaging hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.tape') }}:</label>
                                    {!!  Form::number('tape', old('tape'),['id' => 'tape','class' => 'form-control','placeholder' => 'Please enter tape']) !!}
                                    @if($errors->has('tape'))
                                        <div class="text text-danger">
                                            {{ $errors->first('tape') }}
                                        </div>
                                    @endif
                                    <span>Tape</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__car__transport hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.pickup_toggle') }}
                                        :</label>
                                    {!!  Form::select('pickup_toggle', $data['ancillaryPickupToggle'], old('pickup_toggle'),['id' => 'pickup_toggle','class' => 'form-control', 'placeholder' => 'Please select pickup toggle']) !!}
                                    @if($errors->has('pickup_toggle'))
                                        <div class="text text-danger">
                                            {{ $errors->first('pickup_toggle') }}
                                        </div>
                                    @endif
                                    <span>Pickup toggle</span>
                                </div>

                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.pickup_depot') }}
                                        :</label>
                                    {!!  Form::select('pickup_depot', $data['ancillaryPickupDepot'], old('pickup_depot'),['id' => 'pickup_depot','class' => 'form-control', 'placeholder' => 'Please select pickup depot']) !!}
                                    @if($errors->has('pickup_depot'))
                                        <div class="text text-danger">
                                            {{ $errors->first('pickup_depot') }}
                                        </div>
                                    @endif
                                    <span>Pickup depot</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__car__transport hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.delivery_toggle') }}
                                        :</label>
                                    {!!  Form::select('delivery_toggle', $data['ancillaryDeliveryToggle'], old('delivery_toggle'),['id' => 'delivery_toggle','class' => 'form-control', 'placeholder' => 'Please select delivery toggle']) !!}
                                    @if($errors->has('delivery_toggle'))
                                        <div class="text text-danger">
                                            {{ $errors->first('delivery_toggle') }}
                                        </div>
                                    @endif
                                    <span>Delivery toggle</span>
                                </div>

                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.delivery_depot') }}
                                        :</label>
                                    {!!  Form::select('delivery_depot', $data['ancillaryDeliveryDepot'], old('delivery_depot'),['id' => 'delivery_depot','class' => 'form-control', 'placeholder' => 'Please select delivery depot']) !!}
                                    @if($errors->has('delivery_depot'))
                                        <div class="text text-danger">
                                            {{ $errors->first('delivery_depot') }}
                                        </div>
                                    @endif
                                    <span>Delivery depot</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__car__transport hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.car_rego') }}:</label>
                                    {!!  Form::text('car_rego', old('car_rego'),['id' => 'car_rego','class' => 'form-control','placeholder' => 'Please enter car rego']) !!}
                                    @if($errors->has('car_rego'))
                                        <div class="text text-danger">
                                            {{ $errors->first('car_rego') }}
                                        </div>
                                    @endif
                                    <span>Car rego</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.car_make') }}:</label>
                                    {!!  Form::text('car_make', old('car_make'),['id' => 'car_make','class' => 'form-control','placeholder' => 'Please enter car make']) !!}
                                    @if($errors->has('car_make'))
                                        <div class="text text-danger">
                                            {{ $errors->first('car_make') }}
                                        </div>
                                    @endif
                                    <span>Car make</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__car__transport hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.car_model') }}:</label>
                                    {!!  Form::text('car_model', old('car_model'),['id' => 'car_model','class' => 'form-control','placeholder' => 'Please enter car model']) !!}
                                    @if($errors->has('car_model'))
                                        <div class="text text-danger">
                                            {{ $errors->first('car_model') }}
                                        </div>
                                    @endif
                                    <span>Car model</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('ancillaryservice::ancillary.car_type') }}
                                        :</label>
                                    {!!  Form::select('car_type', $data['ancillaryCarType'], old('car_type'),['id' => 'car_type','class' => 'form-control', 'placeholder' => 'Please select car type']) !!}
                                    @if($errors->has('car_type'))
                                        <div class="text text-danger">
                                            {{ $errors->first('car_type') }}
                                        </div>
                                    @endif
                                    <span>Car type</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__cleaning hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.cleaning_options') }}:</label>
                                    {!!  Form::text('cleaning_options', old('cleaning_options'),['id' => 'cleaning_options','class' => 'form-control','placeholder' => 'Please enter cleaning options']) !!}
                                    @if($errors->has('cleaning_options'))
                                        <div class="text text-danger">
                                            {{ $errors->first('cleaning_options') }}
                                        </div>
                                    @endif
                                    <span>Cleaning options</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.carpet_area') }}:</label>
                                    {!!  Form::number('carpet_area', old('carpet_area'),['id' => 'carpet_area','class' => 'form-control','placeholder' => 'Please enter carpet area']) !!}
                                    @if($errors->has('carpet_area'))
                                        <div class="text text-danger">
                                            {{ $errors->first('carpet_area') }}
                                        </div>
                                    @endif
                                    <span>Carpet area</span>
                                </div>
                            </div>
                            <div class="form-group row __toggle__cleaning hide __common_ancillaries">
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.curtains') }}:</label>
                                    {!!  Form::number('curtains', old('curtains'),['id' => 'curtains','class' => 'form-control','placeholder' => 'Please enter curtains']) !!}
                                    @if($errors->has('curtains'))
                                        <div class="text text-danger">
                                            {{ $errors->first('curtains') }}
                                        </div>
                                    @endif
                                    <span>Curtains</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('ancillaryservice::ancillary.blinds') }}:</label>
                                    {!!  Form::number('blinds', old('blinds'),['id' => 'blinds','class' => 'form-control','placeholder' => 'Please enter blinds']) !!}
                                    @if($errors->has('blinds'))
                                        <div class="text text-danger">
                                            {{ $errors->first('blinds') }}
                                        </div>
                                    @endif
                                    <span>Blinds</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6 box_space">
                                    <label>{{ trans('ancillaryservice::ancillary.total_price') }}:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        {!!  Form::text('price', old('price'),['id' => 'price','class' => 'form-control number-format', 'placeholder' => 'Please enter total price', 'required' => 'required', 'onchange'=>'javascript:formatPrice(this);']) !!}
                                        @if($errors->has('price'))
                                            <div class="text text-danger">
                                                {{ $errors->first('price') }}
                                            </div>
                                        @endif
                                        <span>{{ trans('ancillaryservice::ancillary.total_price') }} *</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="col-lg-12">
                                    <label>{{ trans('ancillaryservice::ancillary.about') }}:</label>
                                    {!! Form::textarea('about', old('about'), ['class'=>'form-control','id' => 'about', 'rows' => '3', 'cols' => '5', 'placeholder' =>'About US']) !!}
                                    @if($errors->has('about'))
                                        <div class="text text-danger">
                                            {{ $errors->first('about') }}
                                        </div>
                                    @endif
                                    <span>{{ trans('ancillaryservice::ancillary.about') }}</span>
                                </div>
                            </div>

                            <p> Add questions to ask the customer when using this service.</p>
                            @if(isset($ancillaryservice->questions) && $ancillaryservice->questions!=null && count($ancillaryservice->questions)>0 )
                                @foreach($ancillaryservice->questions as $key => $question)
                                    <div class="form-group row" >
                                        <div class="col-lg-12">
                                            {!!  Form::text('questions[]', $question,['class' => 'form-control', 'data-question-index' => $key,'placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                            <span>{{ trans('ancillaryservice::ancillary.question') }} {{ $key + 1 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group row" >
                                    <div class="col-lg-12">
                                        {!!  Form::text('questions[]', '',['class' => 'form-control', 'data-question-index' => '0','placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                        <span>{{ trans('ancillaryservice::ancillary.question') }} 1</span>
                                    </div>
                                </div>
                            @endif


                            <div class="form-group row hide" id="ancillaryQuestions">
                                <div class="col-lg-12">
                                    {!!  Form::text('questions[]', '',['class' => 'form-control', 'data-question-index' => '0','placeholder' => trans('ancillaryservice::ancillary.question')]) !!}
                                    <span>{{ trans('ancillaryservice::ancillary.question') }}</span>
                                </div>
                            </div>

                            <a href="#" class="text-center addAncillaryQuestion">Add another question</a>

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
                        .removeClass('hide')
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


