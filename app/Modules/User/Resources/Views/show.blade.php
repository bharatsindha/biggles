@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.staff')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', [
        'moduleTitle' => trans('common.user'),
        'subTitle' => trans('common.view_details'),
        'moduleLink' => route($moduleName.'.index')
    ])
@stop


@section('content')
    <!-- Page content -->

    <section id="profile-info">
        <div class="row">
            <!-- left profile info section -->
            <div class="col-lg-8 col-12 order-2 order-lg-1">
                <!-- about -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.name') }}</h5>
                                    <p class="card-text">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.email') }}:</h5>
                                    <p class="card-text">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{  trans('common.access_level') }}:</h5>
                                    <p class="card-text">{{ $accessLevels[$user->access_level] }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.role') }}:</h5>
                                    <p class="card-text"> @if(!is_null($user->role_id))
                                        {{ $user->role->name  }}
                                    @else
                                        N/A
                                    @endif</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">{{ trans('common.avatar') }}:</h5>
                                    <p class="card-text">@if(!is_null($user->avatar))
                                        <img src="{{  asset('storage/'.$user->avatar) }}" class="file-preview-image"
                                             title="avatar">
                                    @endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ about -->

                @include('layouts.modules.form-footer')

            </div>
        </div>
    </section>
    <!-- /page content -->
@stop
