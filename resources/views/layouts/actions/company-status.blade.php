<a class="kt-badge kt-badge--pill kt-badge--inline kt-font-bold  {{ ($model->flag == 1) ? 'kt-badge--success' : 'kt-badge--danger' }} move__company__active__inactive status_bg" title="" data-flag="{{ $model->flag }}" data-id="{{ $model->id }}" data-url="{{route($route, 'companyId')}}" data-source="__company_list"> @if(isset($action) && $action == true) Active @else {{ ($model->flag == 1) ? 'Active' : 'Inactive' }} @endif </a>
