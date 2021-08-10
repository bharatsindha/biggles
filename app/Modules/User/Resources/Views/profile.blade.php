@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.profile')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Profile Details' ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-12">

                    <!--begin::Portlet-->
                    <div class="kt-portlet">

                        <!--begin::Form-->
                        {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'patch', 'enctype' => "multipart/form-data"]) }}
                            @csrf
                            {!!  Form::hidden('remove_avatar', 0,['id' => 'remove_avatar']) !!}
                            <div class="kt-portlet__body">

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('common.name') }}:</label>
                                        {!!  Form::text('name', old('name'),['id' => 'name','class' => 'form-control', 'placeholder' => 'Please enter Name','required' => 'required']) !!}

                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.email') }}:</label>
                                        <span class="form-text text-muted">{{ $user->email }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>{{ trans('common.new_password') }}:</label>
                                        {!!  Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder' => 'Please enter Password']) !!}
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">{{ trans('common.confirm_new_password') }}:</label>
                                        {!!  Form::password('confirm_password', ['id' => 'confirm_password','class' => 'form-control','placeholder' => 'Please enter Confirm Password']) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6 new_user">
                                        {!!  Form::file('avatar',['class' => 'file-input']) !!}
                                        @push("scripts")
                                            <?php
                                            $options = [];
                                            if(isset($user) && !empty($user->avatar)) {
                                                $options["initialPreview"][] = asset('storage/'.$user->avatar);
                                            }
                                            ?>
                                            {!!  \App\Facades\General::filejs($options) !!}
                                        @endpush
                                    </div>
                                </div>

                            </div>
                            @include('layouts.forms.actions')

                        {{ Form::close() }}

                        <!--end::Form-->
                    </div>

                    <!--end::Portlet-->
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/plugins/uploaders/fileinput.min.js') }}"></script>
    <script>
        $(function () {
            $('.file-input').fileinput({
                removeLabel: '',
                uploadLabel: '',
                uploadClass: 'd-none',
                removeClass: 'btn btn-danger btn-icon',
                removeIcon: '<i class="la la-remove"></i> ',
                allowedFileTypes: ['image'],
                @if(isset($user->avatar) && !empty($user->avatar))
                initialPreview: '<img src="{{  asset('storage/'.$user->avatar) }}" class="file-preview-image" title="avatar">',
                @endif
                layoutTemplates: {
                    caption: '<div tabindex="-1" class="form-control file-caption {class}">\n' + '<span class="icon-file-plus kv-caption-icon"></span><div class="file-caption-name"></div>\n' + '</div>'
                },
                initialCaption: "No file selected"
            });

            $('.fileinput-remove').click(function(event){

                event.preventDefault(event);
                $('#remove_avatar').val(1);
            });

        });
    </script>
@stop
