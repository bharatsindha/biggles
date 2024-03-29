@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.customer')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
        'moduleTitle' => trans('common.customer'),
        'subTitle' => isset($customer) ? trans('common.edit'). ' '. trans('common.customer') : trans('common.add').' '. trans('common.customer') ,
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
        @if(isset($customer))
        {{ Form::model($customer, [
        'route' => [$moduleName.'.update', $customer->id],
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label"><span class="required"> * </span>{{ trans('move::customer.first_name') }}:</label>
                                    {!!  Form::text('first_name', old('first_name'),['id' => 'first_name','class' => 'form-control'. (($errors->has('name')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter first name']) !!}
                                    @if($errors->has('first_name'))
                                    <div class="invalid-feedback">
                                            {{ $errors->first('first_name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label"><span class="required"> * </span>{{ trans('move::customer.last_name') }}:</label>
                                    {!!  Form::text('last_name', old('last_name'),['id' => 'last_name','class' => 'form-control'. (($errors->has('name')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter last name']) !!}
                                    @if($errors->has('last_name'))
                                    <div class="invalid-feedback">
                                            {{ $errors->first('last_name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label"><span class="required"> * </span>{{ trans('move::customer.customer_email') }}:</label>
                                    {!!  Form::text('email', old('email'),['id' => 'email','class' => 'form-control'. (($errors->has('name')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter email']) !!}
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label"><span class="required"> * </span>{{ trans('move::customer.customer_phone') }}:</label>
                                    {!!  Form::text('phone', old('phone'),['id' => 'phone','class' => 'form-control'. (($errors->has('name')) ? 'is-invalid' : ''), 'placeholder' => 'Please enter phone']) !!}
                                    @if($errors->has('phone'))
                                    <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="demo-inline-spacing">
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label class="form-label"><span class="required"> * </span>{{ trans('move::customer.update_card_details') }}</label>
                                    {!!  Form::checkbox( 'customer', 1 ,['class' => 'form-check-primary form-check form-switch' ]) !!}
                                </div>
                            </div>          
                            <div id="card-details">
                                @if(isset($stripeIntent) && !is_null($stripeIntent))
                                    <div class="form-control row">
                                        <div class="col-lg-4">
                                            <input id="cardholder-name" type="text" value="Test Customer" />
                                        </div>
                                        <div class="col-lg-12">
                                            <form id="setup-form" data-secret="{{ $stripeIntent->client_secret }}">
                                                <div id="card-element"> {{$stripeIntent}} </div>
                                                <span id="card-errors"></span>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
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
    @if(isset($stripeIntent) && !is_null($stripeIntent))
        <script src="https://js.stripe.com/v3/"></script>
        <script type="application/javascript">

            var somethingWentWrong = "{{ trans('common.something_went_wrong') }}";
            var stripe = Stripe('{{ $stripePublicKey }}');
            var elements = stripe.elements();
            var cardElement = elements.create('card');
            cardElement.mount('#card-element');

            var cardholderName = document.getElementById('first_name') + ' ' + document.getElementById('last_name');
            var cardButton = document.getElementById('form-btn-save');
            var clientSecret = '{{ $stripeIntent->client_secret }}';
            cardButton.addEventListener('click', function (ev) {
                ev.preventDefault();

                if ($("#update-card").prop('checked') === true) {

                    stripe.confirmCardSetup(
                        clientSecret,
                        {
                            payment_method: {
                                card: cardElement,
                                billing_details: {
                                    name: cardholderName.value,
                                },
                            },
                        }
                    ).then(function (result) {
                        if (result.error) {
                            // Display error.message in your UI.
                            document.getElementById('card-errors').textContent = result.error.message;
                        } else {
                            // The setup has succeeded. Display a success message.
                            document.getElementById('customer-form').submit();
                            return true;
                        }
                    });
                    return false;
                } else {
                    document.getElementById('customer-form').submit();
                }

            });

            cardElement.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            $(document).ready(function () {

                $("#update-card").change(function () {
                    if (this.checked) {
                        $('#card-details').show();
                    } else {
                        $('#card-details').hide();
                    }
                });
            });

        </script>
    @endif
@stop