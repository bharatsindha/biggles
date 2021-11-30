@extends('layouts.master')

@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.scheduler')]) @stop

@section('pageHeader')
@include('layouts.modules.header', [
'moduleTitle' => trans('common.scheduler'),
'subTitle' => trans('common.list'),
'moduleLink' => route($moduleName.'.index')
])
@stop

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-calendar.css') }}">
@stop

@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content -->
    <div class="pending__job_calendar fc-view-harness fc-view-harness-active">
        <div class="row">
            <div class=" {{ count($pendingJobs) == 0 ? 'col-lg-12' : 'col-lg-9' }}">
                <div class="card">
                    <div class="card-datatable table-responsive card-body">
                        @if (isset($firstTimeVisitor) && $firstTimeVisitor == true)
                        <!-- begin:: Content Head -->
                        <div class="kt-subheader  kt-grid__item __hide__onboarding" id="kt_subheader">
                            <div class="onboard_contnet">
                                <div class="onboard_content_section">
                                    <h4>Welcome to scheduler <img src="{{ asset('assets/media/right.svg') }}">
                                    </h4>
                                    <p>You can easily manage and schedule your jobs.</p>
                                    <p>Simply drag and drop from pending jobs to the calendar</p>
                                </div>
                                <img src="{{ asset('assets/media/scheduler_screen.svg') }}" alt="">
                                <img class="close_icon __close__onboarding" src="{{ asset('assets/media/close.svg') }}"
                                    alt="">
                            </div>
                        </div>
                        <!-- end:: Content Head -->
                        @endif
                        <div class="kt-portlet container_space">
                            <div class="fc-view-harness fc-view-harness-active kt-portlet__body">
                                <div class="row">
                                    {{-- <div class="col-md-2">
                                        <!-- Sidebar -->
                                        <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column"
                                            id="app-calendar-sidebar">
                                            <div class="sidebar-wrapper">
                                                <div class="card-body d-flex justify-content-center">
                                                    <button class="btn btn-primary btn-toggle-sidebar w-100"
                                                        data-bs-toggle="modal" data-bs-target="#add-new-sidebar">
                                                        <span class="align-middle">Add Event</span>
                                                    </button>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <h5 class="section-label mb-1">
                                                        <span class="align-middle">Filter</span>
                                                    </h5>
                                                    <div class="form-check mb-1">
                                                        <input type="checkbox" class="form-check-input select-all"
                                                            id="select-all" checked />
                                                        <label class="form-check-label" for="select-all">View
                                                            All</label>
                                                    </div>
                                                    <div class="calendar-events-filter">
                                                        <div class="form-check form-check-danger mb-1">
                                                            <input type="checkbox" class="form-check-input input-filter"
                                                                id="personal" data-value="personal" checked />
                                                            <label class="form-check-label"
                                                                for="personal">Personal</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-auto">
                                                <img src="{{ asset('images/pages/calendar-illustration.png') }}"
                                                    alt="Calendar illustration" class="img-fluid" />
                                            </div>
                                        </div>
                                        <!-- /Sidebar -->
                                    </div> --}}
                                    <div class="col-md-12">
                                        <div id="calendar" class="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 {{ count($pendingJobs) == 0 ? 'hide' : '' }} job_section_modal">
                <div class="card">
                    <div class="card-datatable table-responsive pt-0 card-body">
                        <div class="kt-portlet">
                            <div class="kt-portlet__body ">
                                <div class="col-md-12">
                                    <div class="job_popup hide" id="__job_popup_model"></div>
                                    <div class="side-modal">
                                        <div id='external-events' class="dialog-content">
                                            <div class="sidebar_search_title pb10">
                                                <div class="row">
                                                    <h2>Pending Jobs</h2>
                                                </div>
                                            </div>
                                            <div class="sidebar_search_section pb10">
                                                <div class="row">
                                                    <ul id='external-events-listing'>
                                                        @include('move::schedule_job.filter_jobs', ['pendingJobs' =>
                                                        $pendingJobs])
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Primary modal -->
    <div class="modal fade" id="add_trip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ Lang::get('trip::trip.manage_trip') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-add-new">{{ Lang::get('common.update')
                        }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->
</div>
<!-- /page content -->
@stop

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js" type="application/javascript"></script>
<script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js" type="application/javascript"></script>
<script src="https://unpkg.com/@fullcalendar/timeline@4.4.0/main.min.js" type="application/javascript"></script>
<script src="https://unpkg.com/@fullcalendar/resource-common@4.4.0/main.min.js" type="application/javascript"></script>
<script src="https://unpkg.com/@fullcalendar/resource-timeline@4.4.0/main.min.js" type="application/javascript">
</script>
<link href="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css" rel="stylesheet" type="text/css">
<link href="https://unpkg.com/@fullcalendar/timeline@4.4.0/main.min.css" rel="stylesheet" type="text/css">
<link href="https://unpkg.com/@fullcalendar/resource-timeline@4.4.0/main.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>

<script>
    let token = "{{ Session::token() }}";
        let urlManageJob = "{{ route('move.schedule_job', 'moveId') }}";
        let urlJobHtml = "{{ route('move.get_html_scheduled_job', 'moveId') }}";


        document.addEventListener('DOMContentLoaded', function() {
            let Calendar = FullCalendar.Calendar;
            let Draggable = FullCalendarInteraction.Draggable;

            let containerEl = document.getElementById('external-events');
            let calendarEl = document.getElementById('calendar');
            let checkbox = document.getElementById('drop-remove');

            // initialize the external events
            // -----------------------------------------------------------------
            new Draggable(containerEl, {
                itemSelector: '.fc-event-new',
                eventData: function(eventEl) {
                    let htmlContent = generateEventHtml(eventEl);
                    return {
                        // title: eventEl.innerText,
                        title: htmlContent,
                        duration: '12:00'
                    };
                    $('.fc-event-new[data-event="' + id + '"]').remove();
                }
            });

            // initialize the calendar
            // -----------------------------------------------------------------
            let calendar = new Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                timeZone: 'Australia/Sydney',
                plugins: ['interaction', 'resourceTimeline', 'dayGrid', 'timeGrid', 'momentPlugin',
                    'formatDate'
                ],
                header: {
                    left: 'today,resourceTimelineDay,resourceTimelineWeek ',
                    center: 'title',
                    right: 'prev,next',
                },
                displayEventEnd: true,
                defaultView: 'resourceTimelineWeek',
                slotWidth: "100",
                slotLabelFormat: [{
                        weekday: 'short',
                        day: 'numeric',
                        month: 'long',
                    },
                    {
                        hour: 'numeric',
                        // minute: '2-digit',
                        // omitZeroMinute: true,
                        meridiem: 'short',
                    }
                ],
                slotDuration: '06:00',
                editable: true,
                selectable: true,
                selectHelper: true,
                droppable: true, // this allows things to be dropped onto the calendar
                // height: 'auto',
                resourceAreaWidth: "15%",
                contentHeight: 500,
                dragRevertDuration: 0,
                allDaySlot: false,
                timeGridEventMinHeight: 200,
                eventDurationEditable: false,
                eventReceive: function(event) {
                    showAcceptJobPopup(event);
                },
                eventDrop: function(event) {

                    jobManage(event, 'eventDrop');
                },
                drop: function(dropInfo) {
                    // dropInfo.draggedEl.innerHTML = '';
                },
                eventResize: function(event) {
                    jobManage(event, 'eventResize');
                },
                eventRender: function(info) {
                    console.log(info, "eventRender: function(info) ");
                    // info.el.innerHTML = info.event.title;
                },
                eventPositioned: function() {
                    // calendarRenderAll('calendar');
                    //setTimeout(function(){ calendarRenderAll('calendar'); }, 1000);
                },
                aspectRatio: 1.5,
                default: "10%",
                resourceLabelText: 'Trucks',
                resources: '{{ route('move.truck_resources') }}',
                events: '{{ route('move.scheduled_jobs') }}',
            });

            calendar.render();
        });

        $(function() {

            $(document).on('keyup', '#job__search', function(event) {
                event.preventDefault();
                let job_search = $('#job__search').val();
                let url = "{{ route('move.filter_jobs') }}/" + job_search;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#external-events-listing').html(data.html);
                        loadEventDraggable();
                    },
                    error: function(data) {
                        alert("{{ trans('common.something_went_wrong') }}");
                    }
                });
            });

            loadEventDraggable();
        });

        function reportWindowSize() {
            setTimeout(function() {
                // calendarRenderAll('calendar');
            }, 1000);
        }

        window.onresize = reportWindowSize;
</script>
@stop
