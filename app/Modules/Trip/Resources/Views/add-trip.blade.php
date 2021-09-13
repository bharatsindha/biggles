{{ Form::open(['route' => 'trip.store',  'method' => 'post', 'enctype' => "multipart/form-data"]) }}
@csrf
<div class="kt-portlet__body">

    <div class="form-group row">
        <div class="col-lg-4">
            <label>{{ trans('common.company_name') }}:</label>
            <span class="form-text text-muted">
                @if($lane->company)
                    {{ $lane->company->name }}
                @endif
            </span>
        </div>

        <div class="col-lg-4">
            <label class="">{{ trans('lane::lane.start_address') }}:</label>
            <span class="form-text text-muted">
                {{ $lane->start_addr }}, {{ $lane->start_city }},{{ $lane->start_postcode }}
            </span>
        </div>

        <div class="col-lg-4">
            <label class="">{{ trans('lane::lane.end_address') }}:</label>
            <span class="form-text text-muted">
                {{ $lane->start_addr }},{{ $lane->end_city }},{{ $lane->end_postcode }}
            </span>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-lg-12">
            <table class="tt_modal_addclass table table-striped" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        @php
                            $startDate = date('Y-m-d');
                            $endDate   = date('Y-m-d',strtotime($startDate. ' +3 months'));
                            $iCnt = 1
                        @endphp

                    </td>
                </tr>

            </table>
        </div>
    </div>

</div>
{{ Form::close() }}
