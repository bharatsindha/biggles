@if(!isset($message))
    @php
        $message = trans('common.delete_alert');
    @endphp
@endif

{!! Form::open(['name'=>'delete','method'=> 'delete','class'=> '' , 'route' => [$route, $model->id] ]) !!}
<a href="javascript:void(0);" class="me-1"
   onclick="if (confirm('{{ $message }}')) { $(this).closest('form').submit(); }"
   title="{{ trans('common.delete') }}"><i class="ficon" data-feather="trash"></i></a>
{!! Form::close() !!}

