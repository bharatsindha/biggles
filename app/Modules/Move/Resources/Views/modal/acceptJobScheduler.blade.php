<!-- start Modal-->
{{ Form::model($move, ['route' => ['move.accept_job', $move->id], 'method' => 'patch']) }}
@csrf

{!!  Form::hidden('pickup_window_start', $request->eventStartDate,['required' => 'required']) !!}
{!!  Form::hidden('delivery_window_end', $request->eventEndDate,['required' => 'required']) !!}
{!!  Form::hidden('truck_id', $request->truckId,['required' => 'required']) !!}

<span class="full_screen_section"></span>
<div class="job_popup_content">
    <img class="close_icon" src="{{asset('assets/media/close.svg') }}">
    <h4>Accept job</h4>
    <p>By scheduling the job, you will be accepting the job for
        <b>{{ Carbon\Carbon::parse($request->eventStartDate)->format('H:i') }}</b> on
        <b>{{ Carbon\Carbon::parse($request->eventStartDate)->format('d M Y') }}</b> to <b>{{ $truckName }}</b></p>
    <div class="job_content chart_job_content d-flex align-items-center justify-content-between">
        <div class="pending_job_section">
            <div class="pending_top d-flex align-items-center justify-content-between">
                <span>{{ $customerName }}</span>
                <p>Job {{ $move->id }} <span class="light_color">(5 hours)</span></p>
            </div>
            <div class="job_description">
                <div class="address_content">
                    <p class="active">{{ $move->start_addr }}, {{ $move->start_city }}</p>
                    <p>{{ $move->end_addr }}, {{ $move->end_city }}</p>
                </div>
            </div>
        </div>
        <div class="chart_job_price">
            <span class="price"><sup>$</sup>{{ $move->total_price }}</span>
        </div>
    </div>
    <div class="job_accordine">
        <p class="already_slide">Job details <img src="{{asset('assets/media/expand.svg') }}"></p>
        <div class="job_description">

            <div class="row">
                <div class="col-lg-6">
                    <label>Pick up date</label>
                    <p>{{ Carbon\Carbon::parse($move->pickup_window_start)->format('d/m/Y') }}</p>
                </div>
                <div class="col-lg-6">
                    <label>Delivery date</label>
                    <p>{{ Carbon\Carbon::parse($move->delivery_window_end)->format('d/m/Y') }}</p>
                </div>
                <div class="col-lg-6">
                    <label>Start access</label>
                    @if(isset($move->start_access) && $move->start_access !== null && $move->start_access !== '')
                        @foreach(json_decode($move->start_access) as $startAccess)
                            <p> {{ $startAccess->item }}</p>
                        @endforeach
                    @else
                        <p>Not selected start access.</p>
                    @endif
                </div>
                <div class="col-lg-6">
                    <label>End access</label>
                    @if(isset($move->end_access) && $move->end_access !== null && $move->end_access !== '')
                        @foreach(json_decode($move->end_access) as $endAccess)
                            <p> {{ $endAccess->item }}</p>
                        @endforeach
                    @else
                        <p>Not selected end access.</p>
                    @endif
                </div>
                <div class="col-lg-6">
                    <label>Space required</label>
                    <p>{{ $move->space }}m3</p>
                </div>
            </div>

        </div>
    </div>
    <div class="job_accordine">
        <p>Customer notes <img src="{{asset('assets/media/expand.svg') }}"></p>
        <div class="job_description">
            {{--<p>{{ $move->customer_note }}</p>--}}
            <p>    Please look back at the design and re-do the whole calendar layout. Many styling issues. Lines cut off, padding issues, table header is incorrect, etc. </p>
        </div>
    </div>
    <div class="job_accordine">
        <p>Inventory <img src="{{asset('assets/media/expand.svg') }}"></p>
        <div class="job_description">
            <div class="col-lg-12">
            @if(isset($move->inventory) && $move->inventory !== null && $move->inventory !== '')
                @foreach(json_decode($move->inventory) as $inventory)
                    <label>{{ $inventory->title }}</label>
                    @foreach($inventory->items as $item)
                        <p>
                            <label>{{ $item->item }}</label>
                            <label>{{ $item->value }}</label>
                        </p>
                    @endforeach
                @endforeach
            @else
                <p>No inventory list found.</p>
            @endif
            </div>

        </div>
    </div>
    <div class="job_accordine">
        <p>Price breakdown <img src="{{asset('assets/media/expand.svg') }}"></p>
        <div class="job_description">
            <div class="col-lg-12">
                <label>Price</label>
                <p>
                    <label>Your fee</label>
                    <label>${{ round($move->total_price*0.8, 2) }}</label>
                </p>
                <p>
                    <label>Muval fee</label>
                    <label>${{ round($move->total_price*0.2, 2) }}</label>
                </p>

                <p>
                    <label>Total Price</label>
                    <label>${{ round($move->total_price, 2) }}</label>
                </p>

            </div>
        </div>
    </div>
    <div class="job_update d-flex justify-content-end">
        <button class="cancle __job_pending_cancel" type="button">Cancel</button>
        <button class="accept" type="submit">Accept job</button>
    </div>
</div>
{{ Form::close() }}
<!-- end Modal-->
