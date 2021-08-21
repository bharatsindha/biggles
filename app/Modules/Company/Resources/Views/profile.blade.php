@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.profile')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Company profile' ])
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
                                <h3 class="kt-portlet__head-title">Profile</h3>
                            </div>
                        </div>
                        <!--begin::Form-->
                        @if(isset($company))
                            {{ Form::model($company, ['route' => ['company.update', $company->id], 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
                        @else
                            {{ Form::open(['route' => 'company.store', 'enctype' => "multipart/form-data"]) }}
                        @endif
                        @csrf
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.company_name') }}:</label>
                                    {!!  Form::text('name', old('name'),['id' => 'name','class' => 'form-control', 'placeholder' => 'Please enter Name','required' => 'required']) !!}
                                    @if($errors->has('name'))
                                        <div class="text text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                    <span>Company</span>
                                </div>
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.abn') }}:</label>
                                    {!! Form::text('abn', old('abn'),['id' => 'abn','class' => 'form-control','placeholder' => 'Please enter abn']) !!}
                                    @if($errors->has('abn'))
                                        <div class="text text-danger">
                                            {{ $errors->first('abn') }}
                                        </div>
                                    @endif
                                    <span>Abn</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.phone') }}:</label>
                                    {!!  Form::text('phone', old('phone'),['id' => 'phone','class' => 'form-control','placeholder' => 'Please enter phone','required' => 'required']) !!}
                                    @if($errors->has('phone'))
                                        <div class="text text-danger">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                    <span>Phone</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('company::company.email') }}:</label>
                                    {!!  Form::text('email', old('email'),['id' => 'email','class' => 'form-control','placeholder' => 'Please enter Email','required' => 'required']) !!}
                                    @if($errors->has('email'))
                                        <div class="text text-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                    <span>Email</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="">{{ trans('company::company.website') }}:</label>
                                    {!!  Form::text('website', old('website'),['id' => 'website', 'class' => 'form-control','placeholder' => 'Please enter website','required' => 'required']) !!}
                                    @if($errors->has('website'))
                                        <div class="text text-danger">
                                            {{ $errors->first('website') }}
                                        </div>
                                    @endif
                                    <span>Company website</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{ trans('company::company.address') }}:</label>
                                    <div class="kt-input-icon">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-map-marker"></i></span></span>
                                    </div>
                                    {!!  Form::text('address', old('address'),['id' => 'address', 'class' => 'form-control','placeholder' => 'Please enter Address','required' => 'required']) !!}
                                    @if($errors->has('address'))
                                        <div class="text text-danger">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                    <span>Address</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <p class="company_profile_title">Logo</p>
                                    <div class="change_logo d-flex align-items-center">
                                        <img src="{{asset('storage/'.$company->logo)}}">
                                        <span>Change logo</span>
                                        {!!  Form::file('logo', old('logo'),['id' => 'logo','placeholder' => 'Change logo']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12"><p class="company_profile_title">We use this information to display to the customers</p></div>
                                <div class="col-lg-12">
                                    <label>{{ trans('company::company.about_us') }}:</label>
                                    {!! Form::textarea('about_us', old('about_us'), ['class'=>'form-control','id' => 'about_us', 'rows' => '7', 'cols' => '5', 'placeholder' =>'About US']) !!}
                                    @if($errors->has('about_us'))
                                        <div class="text text-danger">
                                            {{ $errors->first('about_us') }}
                                        </div>
                                    @endif
                                    <span>Company bio</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12"><p>Promote your company(140 characters)</p></div>
                                <div class="col-lg-12">
                                    <label>{{ trans('company::company.summary') }}:</label>
                                    {!! Form::textarea('summary', old('summary'), ['class'=>'form-control','id' => 'summary', 'rows' => '3', 'cols' => '5', 'placeholder' =>'About US']) !!}
                                    @if($errors->has('summary'))
                                        <div class="text text-danger">
                                            {{ $errors->first('summary') }}
                                        </div>
                                    @endif
                                    <span>Summary</span>
                                </div>
                            </div>
                        </div>
                    @include('layouts.forms.actions')
                    {{ Form::close() }}
                    <!--end::Form-->
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4">
                <div class="profile_sidebar">
                        <div class="profile_sidebar_top d-flex align-items-center">
                            <img src="{{asset('storage/'.$company->logo)}}">
                            <div class="sidebar_top_content">
                                <h4>{{ $company->name }}</h4>
                                <div class="sidebar_top_section d-flex align-items-center">
                                    <img src="{{ asset('assets/media/star.svg') }}">
                                    <p>4.54 <span class="light_color">(123 reviews)</span></p>
                                </div>
                            </div>
                        </div>
                        <p>{{ $company->about_us }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

