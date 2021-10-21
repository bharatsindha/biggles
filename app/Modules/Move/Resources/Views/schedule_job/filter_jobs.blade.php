@if(count($pendingJobs) > 0)
    @php $inc = 1 @endphp
    @foreach($pendingJobs as $pendingJob)
        @php
            $colorClass = ($inc % 3 == 1) ? 'red' : (($inc % 3 == 2) ? 'orange' : (($inc % 3 == 0) ? 'green' : 'red'));
            $inc++;

            $pendingJob->start_addr = \App\Facades\General::removeAusWord($pendingJob->start_addr);
            $pendingJob->end_addr = \App\Facades\General::removeAusWord($pendingJob->end_addr)
        @endphp
        <div class="job_content fc-event-new mt-25 __job_event_bg mb-2" data-event="{{ $pendingJob->id }}"
             data-duration="10:00:00" data-customer_name="{{ $pendingJob->customer_name }}"
             data-start_addr="{{ $pendingJob->start_addr }}" data-start_city="{{ $pendingJob->start_city }}"
             data-end_addr="{{ $pendingJob->end_addr }}" data-end_city="{{ $pendingJob->end_city }}"
             data-total_price="{{ $pendingJob->total_price }}">
            <div class="pending_job_section purple">
                <div class="pending_top d-flex align-items-center justify-content-between">
                    <span class="status_bg">Pending</span>
                    <span class="price"><sup>$</sup>{{ $pendingJob->total_price }}</span>
                </div>
                <div class="name">
                    <p>{{ $pendingJob->customer_name }}</p>
                </div>
                <div class="job_description">
                    <p>Job {{ $pendingJob->id }} <span class="light_color">(5 hours)</span></p>
                    <div class="address_content">
                        <p class="active">{{ $pendingJob->start_addr }}, {{ $pendingJob->start_city }}</p>
                        <p>{{ $pendingJob->end_addr }}, {{ $pendingJob->end_city }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
