<div class="job_content chart_job_content d-flex align-items-center justify-content-between">
    <div class="pending_job_section">
        <div class="pending_top d-flex align-items-center"><span>{{ $result->customer_name }}</span>
            <p>Job {{ $result->id }} <span class="light_color">(5 hours)</span></p></div>
        <div class="job_description">
            <div class="address_content"><p class="active">{{ $result->start_addr }}, {{ $result->start_city }}</p>
                <p>{{ $result->end_addr }}, {{ $result->end_city }}</p></div>
        </div>
    </div>
    <div class="chart_job_price"><span class="price"><sup>$</sup>{{ $result->total_price }}</span>
        <a target="_blank" href="{{ route('move.job_details', $result->id) }}"><span>View details</span></a></div>
</div>
