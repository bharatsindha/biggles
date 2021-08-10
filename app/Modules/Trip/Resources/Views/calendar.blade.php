@extends('layouts.master')
@section('pageTitle') @include('layouts.modules.title', ['moduleTitle' => trans('common.trip')]) @stop
@section('pageHeader')
    @include('layouts.modules.header', ['moduleTitle' => 'Trip Calendar' ])
@stop

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="calendar" class="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary modal -->
        <div class="modal fade" id="add_trip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-primary" id="submit-add-new">{{ Lang::get('common.update') }}</button>
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
    <script src="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css" type="text/css"></script>
    <script src="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js" type="application/javascript"></script>
    <script src="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.css" type="text/css"></script>
    <script src="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js" type="application/javascript"></script>
    <script src="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.css" type="text/css"></script>
    <script src="https://unpkg.com/@fullcalendar/list@4.4.0/main.min.js" type="application/javascript"></script>
    <script src="https://unpkg.com/@fullcalendar/list@4.4.0/main.min.css" type="text/css"></script>
    <script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js" type="application/javascript"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>
    <script>
        var token = "{{ Session::token() }}";
        var urlAddTrip = "{{route('trip.new-trip')}}";
        var urlGetTrip = "{{route('trip.get-trips')}}";
        var somethingWentWrong = "{{ trans('common.something_went_wrong') }}";

        document.addEventListener('DOMContentLoaded', function() {
            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendarInteraction.Draggable;


            var calendarEl = document.getElementById('calendar');
            var checkbox = document.getElementById('drop-remove');

            // initialize the calendar
            // -----------------------------------------------------------------

            var calendar = new Calendar(calendarEl, {
                timeZone: 'Australia/Sydney',
                //slotDuration: '24:00',
                //defaultTimedEventDuration:'02:00',
                allDayDefault:true,
                plugins: [ 'interaction', 'dayGrid', 'timeGrid','momentPlugin','formatDate' ],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    //right: 'timeGridWeek,timeGridDay',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay',
                },
                defaultView: 'dayGridWeek',
                //editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                events: '{{ route('trip.get-trips') }}',

            });
            calendar.render();



        });


    </script>

@stop
