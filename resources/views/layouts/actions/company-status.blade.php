<a class="me-1 {{ ($model->flag == 1) ? 'badge badge-light-primary' : 'badge badge-light-danger' }} move__company__active__inactive" title="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="List" data-flag="{{ $model->flag }}" data-id="{{ $model->id }}" data-url="{{route($route, 'companyId')}}" data-source="__company_list"
   aria-label="List" >
   @if(isset($action) && $action == true) Active @else {{ ($model->flag == 1) ? 'Active' : 'Inactive' }} @endif
</a>
