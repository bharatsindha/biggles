<a class="me-50 {{ isset($title) ? 'mt-25' : '' }}" label="" title="View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View"
   aria-label="View" href="{{  route($route, $model->id) }}" id="view">
    {!! isset($title) && !empty($title) ? $title : '<i class="ficon" data-feather="eye"></i>' !!}
</a>
