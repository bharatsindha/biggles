@extends('layouts.master')
@php
    use Illuminate\Support\Facades\Auth;$user = Auth::user()
@endphp

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.my_account')]) @stop

@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => trans('common.my_account') ])
@stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor user_edit" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-8">
                    {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'post']) }}
                    @method('PATCH')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Login email</h3>
                            </div>
                        </div>
                        <div
                            class="kt-portlet__body account_page_body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="login_section d-flex align-items-center">
                                <img src="{{ asset('assets/media/login_mail.svg') }}">
                                <span>You can change your login email here</span>
                            </div>
                            <a href="javascript:void(0)" class="header_button __change_email_button">Change Email</a>
                            <div class="w-100 change_email_pass __change_email_input">
                                <input type="text" class="w-100 change_email" name="email" value="{{ $user->email }}">
                                <button type="submit" class="header_button">Change email</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}

                    {{ Form::open(['route' => 'user.password', 'method' => "POST"]) }}
                    @csrf
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Password</h3>
                            </div>
                        </div>
                        <div
                            class="kt-portlet__body account_page_body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="login_section d-flex align-items-center">
                                <img src="{{ asset('assets/media/password_icon.svg') }}">
                                <span>Need to update your password?</span>
                            </div>
                            <a href="javascript:void(0);" class="header_button __change_password_button">Update
                                Password</a>
                            <div class="w-100 change_email_pass __change_password_input"
                                 style=" {{ isset($errors) && count($errors)>0 ? 'display: block' : '' }};">
                                <input type="password" class="w-100 change_email" placeholder="Current password"
                                       name="current_password" value="{{ old('current_password') }}">
                                <input type="password" class="w-100 change_email" placeholder="New password"
                                       name="new_password" value="{{ old('new_password') }}">
                                <input type="password" class="w-100 change_email" placeholder="New confirm password"
                                       name="new_confirm_password" value="{{ old('new_confirm_password') }}">
                                <button class="header_button">Change password</button>
                            </div>

                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Attachments</h3>
                            </div>
                        </div>
                        <div
                            class="kt-portlet__body account_page_body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="login_section d-flex align-items-center">
                                <img src="{{ asset('assets/media/attechment.svg') }}">
                                <span>Add attachments to your document</span>
                            </div>
                            {{--<a href="#" class="header_button change">Add attachments</a>--}}


                            <div class="form-group row w-100 drag_drop_design">
                                <label class="col-form-label col-lg-3 col-sm-12 text-lg-right">Multiple File
                                    Upload</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                    <form action="" method="post"
                                          class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2"
                                          enctype="multipart/form-data">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                            <span class="dropzone-msg-desc">Upload up to 10 files (pdf, doc)</span>
                                        </div>
                                        @csrf
                                    </form>
                                </div>

                                <div class="downoad_delete_button d-flex justify-content-end w-100 flex-wrap">
                                    <h4 class="m-0">Attached files</h4>

                                    @foreach($user->attachment as $attach)
                                        <div class="attach_file_list d-flex align-items-center w-100">
                                            <p class="m-0">{{ $attach->original_name }}</p>
                                            <a href="javascript:void(0);" class="__delete_attached_file" title="Delete" data-id="{{ $attach->id }}"><i class="la la-trash"></i></a>
                                            <a href="{{ asset('storage/'.$attach->file_name) }}" title="Download" target="_blank"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                        </div>
                    </div>
                    <!--begin::Portlet-->

                    <!--end::Form-->
                </div>

                <!--end::Portlet-->
            </div>
            <div class="col-lg-4">
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script>

        "use strict";
        var KTDropzoneDemo = {
            init: function () {
                $("#kt_dropzone_2").dropzone({
                    url: "{{ route('user.file.upload') }}",
                    paramName: "file",
                    maxFiles: 10,
                    maxFilesize: 10,
                    addRemoveLinks: !0,
                    acceptedFiles: "application/pdf,.doc",
                    removedfile: function (file) {
                        var name = file.upload.filename;

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            type: 'GET',
                            url: '{{ route("user.file.delete") }}',
                            data: {filename: name},
                            success: function (data) {
                                console.log("File has been successfully removed!!");
                            },
                            error: function (e) {
                                console.log(e);
                            }
                        });
                        var fileRef;
                        return (fileRef = file.previewElement) != null ?
                            fileRef.parentNode.removeChild(file.previewElement) : void 0;
                    },

                    success: function (file, response) {
                        console.log(response);
                    },
                    error: function (file, response) {
                        return false;
                    }
                })
            }
        };
        KTUtil.ready(function () {
            KTDropzoneDemo.init()
        });

        // Add edit sections
        $(document).on('click', ".__delete_attached_file", function (event) {
            event.preventDefault();
            if(confirm("Are you sure you want to delete attachment?")){
                let _refSection = $(this).data("id");
                let url = "{{ route('user.attach.delete', 'fileId') }}";
                url = url.replace("fileId", _refSection);

                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'get',
                    success: function (data) {
                        location.reload();
                    },
                    error: function (jqXHR, exception) {
                        if (jqXHR.status === 422) {

                        }
                    }
                });
            }
        });
    </script>

@stop
