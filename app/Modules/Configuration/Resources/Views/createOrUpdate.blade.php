@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.configuration_details')]) @stop

@section('content')
    <!-- Page content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content Head -->
    @include('layouts.modules.header', ['moduleTitle' => trans('common.'.$moduleName.'_details'), 'actionList' => route($moduleName.'.index') ])
    <!-- end:: Content Head -->
        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Form-->
                {{ Form::model($data, ['route' => ['configuration.update'], 'method' => 'patch', 'id' => "configurationForm"]) }}
                @csrf
                @if(isset($data) && count($data) > 0 )
                    @foreach($data as $key => $option_type)
                        <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title"> {{ trans('configuration::configuration.header_'.$key) }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    @php($tmpIndex = 0)
                                    @if(isset($option_type) && count($option_type) > 0 )
                                        @foreach($option_type as $key_type => $option_value)
                                            <div class="form-group row config_row">
                                                <label class="control-label col-lg-2"><span
                                                        class="required"> * </span>{{ trans('configuration::configuration.label_'.$key) }}
                                                    :</label>
                                                <div class="col-lg-6">
                                                    {!!  Form::text($key.'['.$tmpIndex.'][value]', old($key.'['.$tmpIndex.'][value]', $option_value),['id' => $key.'_'.$tmpIndex,'class' => 'form-control ', 'placeholder' => 'Please enter '.trans('configuration::configuration.label_'.$key),'required' => 'required', 'data-category-index' => $tmpIndex ]) !!}

                                                    {!!  Form::hidden($key.'['.$tmpIndex.'][id]', old($key.'['.$tmpIndex.'][id]', $key_type), ['class' => 'form-control', 'data-category-index' => $tmpIndex]) !!}
                                                </div>
                                                <div class="col-lg-2 right-md">
                                                    <button type="button" name="add_button"
                                                            class="btn btn-default add_button_{{$key}}"
                                                            onclick="addConfig('{{$key}}')"><i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" name="remove_button"
                                                            class="btn btn-default removeConfiguration"><i
                                                            class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                            @php($tmpIndex++)
                                        @endforeach
                                    @else
                                        <div class="form-group row config_row" id="{{$key}}_{{$tmpIndex}}">
                                            <label class="control-label col-lg-2">
                                                <span
                                                    class="required"> * </span>{{ trans('configuration::configuration.label_'.$key) }}
                                                :</label>
                                            <div class="col-lg-6">
                                                {!!  Form::text($key.'['.$tmpIndex.'][value]', old($key.'['.$tmpIndex.'][value]'),['id' => $key.'_'.$tmpIndex,'class' => 'form-control ', 'placeholder' => 'Please enter '.trans('configuration::configuration.label_'.$key),'required' => 'required', 'data-category-index' => $tmpIndex ]) !!}
                                                @if($errors->has('customer_id'))
                                                    <div class="text text-danger">
                                                        {{ $errors->first('customer_id') }}
                                                    </div>
                                                @endif
                                                {!!  Form::hidden($key.'['.$tmpIndex.'][id]', old($key.'['.$tmpIndex.'][id]'), ['class' => 'form-control', 'data-category-index' => $tmpIndex]) !!}
                                            </div>
                                            <div class="col-lg-2 right-md">
                                                <button type="button" name="add_button"
                                                        class="btn btn-default add_button_{{$key}}"
                                                        onclick="addConfig('{{$key}}')"><i class="fa fa-plus"></i>
                                                </button>
                                                <button type="button" name="remove_button"
                                                        class="btn btn-default removeConfiguration"><i
                                                        class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group row config_row hide" id="configTemplate_{{$key}}">
                                        <label class="control-label col-lg-2"><span
                                                class="required"> * </span>{{ trans('configuration::configuration.label_'.$key) }}
                                            :</label>
                                        <div class="col-lg-6">
                                            {!!  Form::text('option_config', '', ['class' => 'form-control ', 'placeholder' => 'Please enter '.trans('configuration::configuration.label_'.$key)]) !!}
                                        </div>
                                        <div class="col-lg-2 right-md">
                                            <button type="button" name="add_button"
                                                    class="btn btn-default add_button_{{$key}}">
                                                <i class="fa fa-plus"></i></button>
                                            <button type="button" name="remove_button"
                                                    class="btn btn-default removeConfiguration"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    {!!  Form::hidden('temp_index', $tmpIndex, ['class' => 'form-control', 'id' => 'temp_index_'.$key]) !!}
                                </div>
                                <!--end::Form-->
                                @if(array_key_last($data) == $key)
                                    @include('layouts.forms.actions')
                                @endif
                            </div>
                            <!--end::Portlet-->
                    @endforeach
                @endif
                {{ Form::close() }}
                <!--end::Form-->
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
    </div>
    <!-- /page content -->
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#configurationForm').on('click', '.removeConfiguration', function () {
                var $row = $(this).parents('.config_row'), index = $row.attr('data-category-index');
                $row.remove();
            });
        });

        function addConfig(key) {
            let index = $("#temp_index_" + key).val();
            let pre_index = index;
            index++;

            var $template = $('#configTemplate_' + key),
                $clone = $template
                    .clone()
                    .removeClass('hide')
                    .removeAttr('id')
                    .attr('data-category-index', index)
                    .insertBefore($template);

            $clone.find('[name="add_button"]').attr('onclick', "addConfig('" + key + "')").show();
            $clone.find('[name="option_config"]').attr('name', key + '[' + index + '][value]').end();
            $("#temp_index_" + key).val(index);
        }
    </script>
@stop

