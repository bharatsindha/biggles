<a class="{{ (isset($source) && $source == 'job_detail') ? ' btn btn-outline-primary waves-effect' : 'btn btn-outline-primary waves-effect' }} schedule_job" title="{{ trans('common.edit') }}"
data-id="{{ $model->id }}" href="{{  route($route, $model->id) }}" data-url="{{route('move.schedule_job', 'moveId')}}" data-source="__move_list"> {{ (isset($source) && $source == 'job_detail') ? 'Edit job' : 'Edit' }}</a>

