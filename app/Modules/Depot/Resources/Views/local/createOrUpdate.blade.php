@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.locals')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.local'),
        'subTitle' => isset($local) ? trans('common.edit'). ' '. trans('common.local') : trans('common.add').' '. trans('common.local') ,
        'moduleLink' => route($moduleName.'.index')
    ])
    @stop

{{-- Older Code starts --}}

{{-- Older Code Ends --}}


{{-- New Code Starts -----------------------------------------------------------------------------------------------------------------}}
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
                                        <label class="form-label" for="depot">
                                        {{ trans('depot::depot.name') }}:<span class="required"> * </span>
                                        </label>
                                        {!!  Form::select('depot', $depotOptions, old('depot'),[
                                            'id' => 'depot',
                                            'class' => 'form-select select2'.
                                            (($errors->has('depot')) ? 'is-invalid' : ''),
                                            'placeholder' => 'Please select Depot',
                                            'required' => 'required'
                                            ]) !!}
                                        @if($errors->has('depot'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('depot') }}
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
                                <div class="mb-1">
                                    <label class="form-label" for="radius">
                                        {{ trans('common.travel_radius') }}:
                                    </label>
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
                                </div>
                            </div>

                             <div class="col-md-6">
                                      
                                
                                    <label class="form-label" for="price_per">
                                        {{ trans('common.extra_person_price') }}:
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

                        <div class="days_main_section">
                            <h4>Apply on</h4>
                            <div class="day_section d-flex demo-inline-spacing">
                                @foreach($weekDaysArr as $weekDay)
                                <div class="day d-flex align-items-center form-check">
                                    <span class="local_circle{{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'active' : '' }}">
                                        <input type="checkbox" class="form-check-input" name="weekdays[]" value="{{ $weekDay }}" {{ isset($local) && !is_null($local->weekdays) && in_array($weekDay, $local->weekdays) ? 'checked' : '' }}>
                                    </span>
                                    <span>{{ ucfirst($weekDay) }}</span>
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
{{-- New Code Ends --}}



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
