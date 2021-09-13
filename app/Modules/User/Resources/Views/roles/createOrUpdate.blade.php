@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.roles')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => isset($role) ? 'Edit role' : 'Add role' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">

                    <!--begin::Portlet-->
                    <div class="kt-portlet container_space">

                        <!--begin::Form-->
                        @if(isset($role))
                            {{ Form::model($role, ['route' => ['role.update', $role->id], 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
                            @method('PATCH')
                        @else
                            {{ Form::open(['route' => 'role.store', 'enctype' => "multipart/form-data"]) }}
                        @endif
                        @csrf
                        <div class="kt-portlet__body">
                            <div class="form-group row ">
                                <div class="col-lg-6">
                                    <label>{{ trans('common.role_name') }}:</label>
                                    {!!  Form::text('name', old('name'),['id' => 'name','class' => 'form-control', 'placeholder' => 'Please enter Name','required' => 'required']) !!}
                                    @if($errors->has('name'))
                                        <div class="text text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                    <span>Enter Name</span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="">{{ trans('common.description') }}:</label>
                                    {!! Form::textarea('description', old('description'), ['class'=>'form-control','id' => 'description', 'rows' => '3', 'cols' => '5', 'placeholder' =>'Description']) !!}
                                    @if($errors->has('description'))
                                        <div class="text text-danger">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                    <span>Description</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6 permission_label">
                                    <label>{{ trans('common.permissions') }}:</label> <br/>
                                    @if(isset($permissions))
                                        @foreach($permissions as $permission)
                                            @php
                                                $selected =false
                                            @endphp
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                @if (isset($role) && isset($role->permissions) && $role->permissions->contains($permission))
                                                    @php
                                                        $selected = true
                                                    @endphp
                                                @endif
                                                {!!  Form::checkbox('permissions[]', $permission->id, $selected, ['id' => 'permission_'.$permission->id,'class' => 'form-control']) !!} {{ $permission->name  }}
                                                <span></span>
                                            </label>
                                            <br/>
                                        @endforeach
                                    @endif
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

@stop
