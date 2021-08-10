<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        {{--<div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    {{ $subTitle  }}
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="{{ URL::previous() }}" class="btn btn-clean btn-icon-sm" title="Back"><i class="icon-arrow-left52 position-left"></i></a> <i class="la la-long-arrow-left"></i> {{ trans('common.back')  }}</h4>
                    &nbsp;
                    <div class="dropdown dropdown-inline">
                        <a href="{{ $subRoute }}" type="button" class="btn btn-brand btn-icon-sm" title="{{ trans('common.add_new') }}">
                            <i class="flaticon2-plus"></i> {{ trans('common.add_new')  }}
                        </a>
                    </div>
                </div>
            </div>
        </div>--}}
        {{ $slot }}
    </div>
</div>
