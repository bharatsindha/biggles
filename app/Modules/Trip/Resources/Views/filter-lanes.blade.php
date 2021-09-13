@if(count($lanes) > 0)
    @foreach($lanes as $lane)
        <li class='fc-event'
            data-event='{{ $lane->id }}'
            data-duration='{{ $trip_time }}'>
            {{ $lane->company->name }} - {{ $lane->start_city }} to {{ $lane->end_city }}
        </li>
    @endforeach
@else
    {{ trans('common.no_records_found') }}
@endif
