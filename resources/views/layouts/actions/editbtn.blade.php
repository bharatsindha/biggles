{{-- <a class="me-1" title="{{ trans('common.edit') }} btn btn-outline-primary waves-effect" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit"
   aria-label="Edit" href="{{  route($route, $model->id) }}" id="edit">
   
</a> --}}


<a class="{{ (isset($source) && $source == 'job_detail') ? ' btn btn-outline-primary waves-effect' : 'btn btn-outline-primary waves-effect' }} schedule_job" title="{{ trans('common.edit') }}"
data-id="{{ $model->id }}" href="{{  route($route, $model->id) }}" data-url="{{route('move.schedule_job', 'moveId')}}" data-source="__move_list"> {{ (isset($source) && $source == 'job_detail') ? 'Edit job' : 'Edit' }}</a>

