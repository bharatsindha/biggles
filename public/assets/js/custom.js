/**
 * Load Event Draggable
 */
function loadEventDraggable() {
    $(document).find('#external-events .fc-event-new').each(function () {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: false, // maintain when user navigates (see docs on the renderEvent method)
            //id:$(this).data('event'),
            class_id: $(this).data('event'),
            duration: $(this).data('duration'),
            backgroundColor: $(this).data('color'),
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 9999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });
}

/**
 * Custom block UI
 */
function blockUICustom() {
    $.blockUI({
        message: '<i class="icon-spinner4 spinner"></i>',
        baseZ: 2000,
        overlayCSS: {
            backgroundColor: '#1b2024',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            color: '#fff',
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
}

/**
 * Manage Trip
 * @param event
 */
function tripManage(info){

    let eventStartDate = moment(info.event.start);
    let eventEndDate = info.event.end;
    if(eventEndDate === null ){
        eventEndDate = moment(info.event.start).add(2,'hours');
    } else  {
        eventEndDate = moment(info.event.end);
    }

    let startDate = eventStartDate.format('YYYY-MM-DD');
    let startTime = eventStartDate.format("HH:mm:ss");
    let endTime = eventEndDate.format("HH:mm:ss");
    let eventDay = eventStartDate.format('dddd');
    let laneId = info.draggedEl.dataset.id;

    // Open Popup Here
    blockUICustom();
    $.ajax({
        url: urlAddTrip,
        type: "POST",
        data: {
            lane_id: laneId,
            date: startDate,
            start_time: startTime,
            end_time: endTime,
            event_day: eventDay,
            _token: token
        },
        dataType: 'json',
        success: function (response) {
            $.unblockUI();
            $(document).find('#add_trip .modal-body').html(response.html);
            $('#add_trip').modal();
        },
        error: function (response) {
            $.unblockUI();
            //$('.calendar').fullCalendar('removeEvents', info.event._id);
            alert(somethingWentWrong);
        },
    });
}

/**
 * show Hide Recurring section
 * @param event
 */
function showRecurring(isRecurring){
    if(isRecurring) {
        $('#trip-details').show();
        $("#expiry_date").prop('required',true);
        $("#delivery_within").prop('required',true);
        $("input[type='radio'][name='frequency']").prop('required',true);
    } else {
        $('#trip-details').hide();
        $("#expiry_date").prop('required',false);
        $("#delivery_within").prop('required',false);
        $("input[type='radio'][name='frequency']").prop('required',false);
    }
}

function getJobHtml(url) {

    let token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        success: function (data) {
            return data.html;
        },
        error: function () {
            alert("Something Went Wrong!");
        }
    });

}



/**
 * Get Trips
 * @param event
 */
function getTrips(info){

    let eventStartDate = moment(info.view.activeStart);
    let startDate = eventStartDate.format('YYYY-MM-DD');

    return true;
    // Open Popup Here
    blockUICustom();
    $.ajax({
        url: urlGetTrip,
        type: "GET",
        data: {
            date: startDate,
            _token: token
        },
        dataType: 'json',
        success: function (response) {
            $.unblockUI();
            //calendar( 'updateEvents', response.events );
            //calendar.updateEvents(response.events);
            calendar.addEvent( { // this object will be "parsed" into an Event Object
                   title: 'The Title', // a property!
                start: '2020-05-18', // a property!
                end: '2020-05-20' // a property! ** see important note below about 'end' **
            } );

        },
        error: function (response) {
            $.unblockUI();
            //$('.calendar').fullCalendar('removeEvents', info.event._id);
            alert(somethingWentWrong);
        },
    });
}


/**
 * Manage Job
 * @param event
 */
function jobManage(info, eventType) {

    let eventStartDate = '';
    let eventEndDate = '';
    let moveId = 0;
    let truckId = 0;
    if (eventType == 'eventReceive') {
        eventStartDate = moment(info.event._instance.range.start);
        eventEndDate = moment(info.event._instance.range.end);
        moveId = info.draggedEl.dataset.event;
        truckId = info.event._def.resourceIds[0];
    } else if (eventType == 'eventDrop' || eventType == 'eventResize') {
        eventStartDate = moment(info.event.start, 'Australia/Sydney');
        eventEndDate = moment(info.event.end,'Australia/Sydney');
        moveId = info.event.id;
        truckId = info.event._def.resourceIds[0];
    }

    let startDate = eventStartDate.format('YYYY-MM-DD HH:mm:ss');
    let endDate = eventEndDate.format('YYYY-MM-DD HH:mm:ss');

    urlManageJob = urlManageJob.replace("moveId", moveId);
    $(".mv__list__"+moveId).remove();

    blockUICustom();

    // Open Popup Here

    $.ajax({
        url: urlManageJob,
        type: "POST",
        data: {
            pickup_window_start: startDate,
            delivery_window_end: endDate,
            truck_id: truckId,
            _token: token
        },
        dataType: 'json',
        success: function (response) {
            $(".mv__list__"+moveId).remove();
            // location.load(0);

            $.unblockUI();
        },
        error: function (response) {
            $.unblockUI();
        },
    });
}

function showAcceptJobPopup(info) {
    let moveId = info.draggedEl.dataset.event;
    let data ={
        eventStartDate: moment(info.event._instance.range.start).format('YYYY-MM-DD HH:mm:ss'),
        eventEndDate: moment(info.event._instance.range.end).format('YYYY-MM-DD HH:mm:ss'),
        truckId: info.event._def.resourceIds[0],
    };

    let url = baseUrl+'/move/accept-job-scheduler-html/'+moveId;
    __callAcceptJobPopupScheduler(url, data);
}

function __callAcceptJobPopupScheduler(url, paramData) {
    let token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        data: paramData,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        success: function (data) {
            $('#__job_popup_model').html(data['data']);
            $('#__job_popup_model').removeClass('hide').addClass('show');
            /*$('#acceptJobModal').modal('show');
            $('.commonDatepicker').datepicker({format: 'yyyy-mm-dd'});*/
        },
        error: function () {
            alert("Something Went Wrong!");
        }
    });
}


function generateEventHtml(eventEl) {
    let id = eventEl.getAttribute("data-event");
    let customerName = eventEl.getAttribute("data-customer_name");
    let startAddr = eventEl.getAttribute("data-start_addr");
    let startCity = eventEl.getAttribute("data-start_city");
    let endAddr = eventEl.getAttribute("data-end_addr");
    let endCity = eventEl.getAttribute("data-end_city");
    let totalPrice = eventEl.getAttribute("data-total_price");

    return '<div class="job_content chart_job_content d-flex align-items-center justify-content-between"><div class="pending_job_section"><div class="pending_top d-flex align-items-center"> <span>' + customerName + '</span><p>Job ' + id + '<span class="light_color">(5 hours)</span></p></div><div class="job_description"><div class="address_content"><p class="active">' + startAddr + ', ' + startCity + '</p><p>' + endAddr + ', ' + endCity + '</p></div></div></div><div class="chart_job_price"> <span class="price"><sup>$</sup>' + totalPrice + '</span> <span>View details</span></div></div>';
}

function getDragJobHtml(info) {
    let moveId = info.draggedEl.dataset.event;
    urlJobHtml = urlJobHtml.replace("moveId", moveId);

    // Open Popup Here
    blockUICustom();
    $.ajax({
        url: urlJobHtml,
        type: "POST",
        data: {
            _token: token
        },
        dataType: 'json',
        success: function (response) {
            $.unblockUI();
            info.draggedEl.innerHTML = response['html'];
        },
        error: function (response) {
            $.unblockUI();
        },
    });
}

$(function () {

    /*$(document).on('click', ".move__job__decline", function (event) {
        if(confirm("Are you Sure!")){
            event.preventDefault();
            let moveId = $(this).data("id");
            let source = $(this).data("source");
            let url = $(this).data("url");
            url = url.replace("moveId", moveId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    if (source == '__move_calendar') {
                        $(".mv__list__" + moveId).remove();
                        loadEventDraggable();
                    } else {
                        location.reload();
                    }
                },
                error: function (data) {
                    alert("Something Went Wrong!");
                }
            });
        }
    });*/

    $(document).on('click', ".move__company__active__inactive", function (event) {
        let flag = $(this).data("flag");
        let status = (flag == 1) ? 'Inactive' : 'Active';
        if (confirm("Are you Sure want to " + status + "!")) {
            event.preventDefault();
            let companyId = $(this).data("id");
            let source = $(this).data("source");
            let url = $(this).data("url");

            url = url.replace("companyId", companyId);

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    flag: flag,
                },
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    alert("Something Went Wrong!");
                }
            });
        }
    });

    $(document).on('click', ".move__job__decline", function (event) {
        event.preventDefault();
        let token = $('meta[name="csrf-token"]').attr('content');
        let moveId = $(this).data("id");
        let url = $(this).data("url");
        url = url.replace("moveId", moveId);

        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            success: function (data) {
                $('.kt_model_common__decline_job').html(data['data']);
                $('#declineJobModal').modal('show');
            },
            error: function () {
                alert("Something Went Wrong!");
            }
        });

    });

    $(document).on('click', ".move__job__accept", function (event) {
        event.preventDefault();
        let moveId = $(this).data("id");
        let update = $(this).data("update");
        let url = $(this).data("url");
        url = url.replace("moveId", moveId);

        __call_accept_job_popup(url, update);
    });

    $(document).on('click', ".__job_pending_cancel", function (event) {
        $('#__job_popup_model').removeClass('show').addClass('hide');
        location.reload();
    });

});

function __call_accept_job_popup(url, update) {
    let token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        data: {
            update_status: update
        },
        success: function (data) {
            $('.kt_model_common__accept_job').html(data['data']);
            $('#acceptJobModal').modal('show');
            // $('.commonDatepicker').datetimepicker({format: 'yyyy-mm-dd HH:mm'});
            $(".commonDatepicker").datetimepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-right",
                format: "yyyy-mm-dd hh:ii"
            })
        },
        error: function () {
            alert("Something Went Wrong!");
        }
    });
}

function intializeMap() {

}

/**
 * Get Array from Object
 *
 * @param obj
 * @returns {*[]}
 */
function objectToArray(obj) {
    var keys = Object.keys(obj);
    var routeGeoJSON = keys.map(function(key) {
        return obj[key];
    });
    return routeGeoJSON;
}

/**
 * new Drop offs
 *
 * @param coords
 */
function newDropoff(coords) {
    // Store the clicked point as a new GeoJSON feature with
    // two properties: `orderTime` and `key`
    var pt = turf.point([coords.lng, coords.lat], {
        orderTime: Date.now(),
        key: Math.random()
    });
    dropoffs.features.push(pt);
    pointHopper[pt.properties.key] = pt;

    // Make a request to the Optimization API
    $.ajax({
        method: 'GET',
        url: assembleQueryURL()
    }).done(function(data) {
        // Create a GeoJSON feature collection
        var routeGeoJSON = turf.featureCollection([
            turf.feature(data.trips[0].geometry)
        ]);

        // If there is no route provided, reset
        if (!data.trips[0]) {
            routeGeoJSON = nothing;
        } else {
            // Update the `route` source by getting the route source
            // and setting the data equal to routeGeoJSON
            map.getSource('route').setData(routeGeoJSON);
        }

        //
        if (data.waypoints.length === 12) {
            window.alert(
                'Maximum number of points reached. Read more at docs.mapbox.com/api/navigation/#optimization.'
            );
        }
    });
}

/**
 * Update Map Dropoffs
 *
 * @param geojson
 */
function updateDropoffs(geojson) {
    map.getSource('dropoffs-symbol').setData(geojson);
}

// Here you'll specify all the parameters necessary for requesting a response from the Optimization API
function assembleQueryURL() {
    // Store the location of the truck in a variable called coordinates
    var coordinates = [truckLocation];
    var distributions = [];
    keepTrack = [truckLocation];

    // Create an array of GeoJSON feature collections for each point
    var restJobs = objectToArray(pointHopper);

    // If there are actually orders from this restaurant
    if (restJobs.length > 0) {
        // Check to see if the request was made after visiting the restaurant
        var needToPickUp =
            restJobs.filter(function(d, i) {
                return d.properties.orderTime > lastAtRestaurant;
            }).length > 0;

        // If the request was made after picking up from the restaurant,
        // Add the restaurant as an additional stop
        if (needToPickUp) {
            var restaurantIndex = coordinates.length;
            // Add the restaurant as a coordinate
            coordinates.push(warehouseLocation);
            // push the restaurant itself into the array
            keepTrack.push(pointHopper.warehouse);
        }

        restJobs.forEach(function(d, i) {
            // Add dropoff to list
            keepTrack.push(d);
            coordinates.push(d.geometry.coordinates);
            // if order not yet picked up, add a reroute
            if (needToPickUp && d.properties.orderTime > lastAtRestaurant) {
                distributions.push(
                    restaurantIndex + ',' + (coordinates.length - 1)
                );
            }
        });
    }

    // Set the profile to `driving`
    // Coordinates will include the current location of the truck,
    return (
        'https://api.mapbox.com/optimized-trips/v1/mapbox/driving/' +
        coordinates.join(';') +
        '?distributions=' +
        distributions.join(';') +
        '&overview=full&steps=true&geometries=geojson&source=first&access_token=' +
        mapboxgl.accessToken
    );
}



/**
 * create a function to make a directions request
 *
 * @param startLat
 * @param startLng
 * @param endLat
 * @param endLng
 */
function getCoordinates(startLng,startLat,endLng,endLat,wayPointsCoordinates) {

    var map = new mapboxgl.Map({
        container: 'map',
        //style: 'mapbox://styles/mapbox/streets-v11',
        //style: 'mapbox://styles/mapbox/light-v9',
        style: 'mapbox://styles/mapbox/light-v10',
        center: centerLatLng,
        zoom: 8
    });

    // make directions request using cycling profile
    var routeParams = wayPointsCoordinates.map(e => e.join(',')).join(';');
    var url =
        'https://api.mapbox.com/directions/v5/mapbox/driving/' +
        routeParams +
        '?steps=true&geometries=geojson&access_token=' +
        mapBoxAccessToken;

    /*var url =
        'https://api.mapbox.com/directions/v5/mapbox/driving/' +
        startLng +
        ',' +
        startLat +
        ';' +
        endLng +
        ',' +
        endLat +
        '?steps=true&geometries=geojson&access_token=' +
        mapBoxAccessToken;*/

    // make an XHR request https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
    var req = new XMLHttpRequest();
    req.open('GET', url, true);
    req.onload = function() {
        var json = JSON.parse(req.response);
        if(json.routes !== undefined){
            var data = json.routes[0];

            var rootElement =  document.getElementById('route');
            if (typeof(rootElement) != 'undefined' && rootElement != null)
            {
                document.getElementById("route").value = JSON.stringify(data.geometry.coordinates);
            }


            map.on('load', function() {
                if (!map.getSource('start')) {
                    map.addSource('start', {
                        type: 'geojson',
                        data: {
                            type: 'Point',
                            coordinates: [startLng, startLat]
                        }
                    });

                    map.addLayer({
                        'id': 'start',
                        'type': 'circle',
                        'source': 'start',
                        'layout': {},
                        'paint': {
                            'circle-radius': 10,
                            'circle-color': '#FF5C00',
                        }
                    });
                }

                map.addSource('end', {
                    type: 'geojson',
                    data: {
                        type: 'Point',
                        coordinates: [endLng, endLat]
                    }
                });

                map.addLayer({
                    'id': 'end',
                    'type': 'circle',
                    'source': 'end',
                    'layout': {},
                    'paint': {
                        'circle-radius': 10,
                        'circle-color': '#FF5C00',
                    }
                });

                map.addSource('route', {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': {
                            'type': 'LineString',
                            'coordinates': data.geometry.coordinates
                        }
                    }
                });
                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': '#FF5C00',
                        'line-width': 4
                    }
                });

                map.fitBounds([
                    [startLng, startLat],
                    [endLng, endLat]
                ]);
            });

            return data.geometry.coordinates;
        }
    };
    req.send();
}

/**
 * Draw a Map
 */
function plotMap(){
    var wayPointsCoordinates = [];
    startLat  = document.getElementById("start_lat").value;
    startLng  = document.getElementById("start_lng").value;
    endLat  = document.getElementById("end_lat").value;
    endLng  = document.getElementById("end_lng").value;

    if(startLat !== null && startLng !== null && endLat !== null  && endLng !== null && startLat !== "" && startLng !== "" && endLat !== ""  && endLng !== ""){

        wayPointsCoordinates[0] = [startLng,startLat];
        //wayPoints[0] = [startLat,startLng];

        $( "#external-events-listing li" ).each(function( index ) {

            // wayPointsCoordinates[wayPointsCoordinates.length] = [$(this).data("lng"),$(this).data("lat")];

            wayPointsCoordinates.push([$(this).data("lng"), $(this).data("lat")]);
        });

        wayPointsCoordinates[wayPointsCoordinates.length] = [endLng,endLat];
        //wayPoints[wayPointsCoordinates.length] = [endLat,endLng];

        // console.log(startLng, startLat, endLng, endLat, wayPointsCoordinates);
        getCoordinates(startLng, startLat, endLng, endLat, wayPointsCoordinates);
    }
}

/**
 * Remove Waypoint
 * @param element
 */
function removeWaypoint(element) {

    // remove li element
    element.parentElement.remove();

    // ES 6 call
    document.querySelectorAll('.waypoint').forEach(e => e.remove());

    // remove all input and regenrate li
    var ul = document.getElementById("external-events-listing");
    var items = ul.getElementsByTagName("li");
    for (var i = 0; i < items.length; ++i) {

        // do something with items[i], which is a <li> element
        items[i].id = i;
        var dataLat = items[i].getAttribute("data-lat");
        var dataLng = items[i].getAttribute("data-lng");
        var placeName = items[i].getAttribute("data-place");

        var waypointObj = {
            lat: dataLat,
            lng: dataLng,
            place: placeName };

        // add input element
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("class", "waypoint");
        input.setAttribute("name", "waypoint["+i+"]");
        input.setAttribute("value", JSON.stringify(waypointObj));

        // If it is trip
        if (document.getElementById("trip-form") !== null){
            document.getElementById("trip-form").appendChild(input);
        }
        // If it is lane
        if (document.getElementById("lane-form") !== null){
            document.getElementById("lane-form").appendChild(input);
        }
    }

    plotMap();
}

/**
 * @param CalendarId
 */
function calendarRenderAll(CalendarId){

    // ES 6 call

    document.querySelectorAll('.fc-time-area .fc-rows tr').forEach(function(trElement) {

        var childCountActualCount = 0;
        trElement.querySelectorAll('.fc-event-container > a').forEach(function(move) {
            countHeight = (childCountActualCount*100) + 100;
            move.style.marginTop = (countHeight - 100).toString() + 'px';
            move.parentElement.parentElement.style.height = countHeight.toString() + 'px';

            var containerDiv = move.parentElement.parentElement;
            if(typeof containerDiv.parentElement !== "undefined" && typeof containerDiv.parentElement.parentElement !== "undefined") {
                var divResourceId = containerDiv.parentElement.parentElement.getAttribute('data-resource-id');
                var resourceElement = document.querySelector(".fc-resource-area .fc-rows > table tr:nth-child("+( parseInt(divResourceId) )+") > td > div");

                if (resourceElement !== null) {
                    resourceElement.style.height = countHeight.toString() + 'px';
                }
            }

            childCountActualCount++;
        });

    });

}
