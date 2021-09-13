
    <a class="{{ (isset($source) && $source == 'job_detail') ? 'job_button btn btn-gradient-danger' : 'btn btn-gradient-danger ' }} move__job__decline" title="{{ trans('common.decline') }}"
       data-id="{{ $model->id }}" href="javascript(0)" data-url="{{route('move.decline_job_html', 'moveId')}}" data-source="__move_list"> {{ (isset($source) && $source == 'job_detail') ? 'Decline job' : 'Decline' }}</a>

