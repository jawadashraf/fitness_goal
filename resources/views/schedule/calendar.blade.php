@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
{{--    <script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>--}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var Draggable = FullCalendar.Draggable;
            var containerEl = document.getElementById('external-events');
            // initialize the external events
            // -----------------------------------------------------------------

            new Draggable(containerEl, {
                itemSelector: '.fc-event',
                eventData: function(eventEl) {
                    return {
                        id: -1,
                        title: eventEl.innerText,
                        workout_id: eventEl.getAttribute('data-id'),
                    };
                }
            });

            let customElementClicked = false;
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    start: 'listWeek,dayGridMonth,timeGridWeek,timeGridDay',
                    center: 'title',
                    // end: 'prevYear,prev,next,nextYear',
                },
                // initialView: 'dayGridMonth',
                initialView: 'timeGridWeek',
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                events: '/events',
                // Deleting The Event
                eventContent: function(info) {

                    var eventTitle = info.event.title;
                    var eventElement = document.createElement('div');
                    eventElement.innerHTML = '<span class="custom-icon" style="cursor: pointer;">❌</span> ' + eventTitle;

                    eventElement.querySelector('.custom-icon').addEventListener('click', function(event) {
                        customElementClicked = true; // Set the flag
                        event.preventDefault();
                        event.stopPropagation();
                        if (confirm("Are you sure you want to delete this event?")) {
                            var eventId = info.event.id;
                            $.ajax({
                                method: 'get',
                                url: '/schedule/delete/' + eventId,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    console.log('Event deleted successfully.');
                                    calendar.refetchEvents(); // Refresh events after deletion
                                    showToast('Event deleted.');
                                },
                                error: function(error) {
                                    console.error('Error deleting event:', error);
                                }
                            });
                        }

                    });
                    return {
                        domNodes: [eventElement]
                    };
                },

                // events: [
                //     {
                //         title: 'Cardio Workout',
                //         start: '2024-03-12T14:30:00',
                //         extendedProps: {
                //             status: 'done'
                //         }
                //     },
                //     {
                //         title: 'Strength WO',
                //         start: '2024-03-13T07:00:00',
                //         // backgroundColor: 'green',
                //         // borderColor: 'green'
                //     }
                // ],
                // eventDidMount: function(info) {
                //     if (info.event.extendedProps.status === 'done') {
                //
                //         // Change background color of row
                //         info.el.style.backgroundColor = '#ff00ff';
                //
                //         // Change color of dot marker
                //         var dotEl = info.el.getElementsByClassName('fc-event-dot')[0];
                //         if (dotEl) {
                //             dotEl.style.backgroundColor = 'red';
                //         }
                //     }
                // },
                eventDidMount: function(info) {

                    info.el.setAttribute('title', info.event.extendedProps.goals);
                    // Use Bootstrap's tooltip
                    var tooltip = new bootstrap.Tooltip(info.el, {
                        container: 'body', // Ensures tooltip is appended to the body and not within the event element
                        placement: 'top', // Specifies the tooltip position
                        trigger: 'hover', // Specifies that the tooltip is triggered on hover
                    });

                },
                drop: function(info) {
                    console.log(info);
                    var workoutId = info.draggedEl.getAttribute('data-id');
                    var title = info.draggedEl.innerText;
                    // Extract the date where the element was dropped
                    var dropDate = info.dateStr;
                    var allDay = info.allDay;

                    createWorkoutSchedule(workoutId, title, dropDate, allDay);
                },
                eventClick: function(event) {
                    if (!customElementClicked) {
                        // Proceed with your usual eventClick logic
                        if (event.url) {
                            window.open(event.url, "_blank");
                            return false;
                        }
                    } else {
                        // Reset the flag after checking
                        customElementClicked = false;
                    }
                },
                eventDrop: function(info) {
                    var eventId = info.event.id;
                    var newStartDate = info.event.start;
                    var newEndDate = info.event.end || newStartDate;
                    var newStartDateUTC = newStartDate.toISOString().slice(0, 16);
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 16);
                    console.log(newStartDateUTC, newEndDateUTC, eventId);
                    $.ajax({
                        method: 'post',
                        url: `/schedule/${eventId}/update_event_on_drop`,
                        data: {
                            '_token': "{{ csrf_token() }}",
                            start_date: newStartDateUTC,
                            end_date: newEndDateUTC,
                        },
                        success: function() {
                            console.log('Event moved successfully.');
                            showToast('Event updated successfully.');
                        },
                        error: function(error) {
                            console.error('Error moving event:', error);
                        }
                    });
                },
                // Event Resizing
                eventResize: function(info) {
                    var eventId = info.event.id;
                    var newEndDate = info.event.end;
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 16);

                    $.ajax({
                        method: 'post',
                        url: `/schedule/${eventId}/resize`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            end_date: newEndDateUTC
                        },
                        success: function() {
                            console.log('Event resized successfully.');
                            showToast('Event resized successfully.');
                        },
                        error: function(error) {
                            console.error('Error resizing event:', error);
                            showToast('Error resizing event:', error);
                        }
                    });
                },
                navLinks: true,
                // navLinkDayClick: function(date, jsEvent) {
                //     console.log('day', date.toISOString());
                //     console.log('coords', jsEvent.pageX, jsEvent.pageY);
                // }
            });
            calendar.render();

            function showToast(message){
                var toastLiveExample = document.getElementById('liveToast');
                var toastBody = document.getElementById('toast-body');

                // Set the dynamic message from the session
                toastBody.textContent = message;
                var toast = new bootstrap.Toast(toastLiveExample);

                toast.show();
            }
            // Function to send AJAX request to create a WorkoutSchedule
            function createWorkoutSchedule(workoutId, title, dropDate, allDay) {
                fetch('/schedule/create-from-drop', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        workoutId: workoutId,
                        date: dropDate,
                        title: title,
                        // Any other data you need to send
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('WorkoutSchedule created:', data);

                        // Remove the dropped element if needed
                        var tempEvent = calendar.getEventById(-1);

                        if (tempEvent) {
                            console.log('tempEvent:',tempEvent);
                            tempEvent.remove();
                        }
                        //
                        // // This step depends on your UI requirements
                        //
                        // // Add the new event to the calendar
                        // calendar.addEvent({
                        //     id: data.workoutSchedule.id, // Use the actual property name from your response
                        //     title: data.workoutSchedule.title,
                        //     start: data.workoutSchedule.start,
                        //     end: data.workoutSchedule.end, // Assuming your server response includes the end time
                        //     allDay: allDay
                        // });


                        {{--Swal.fire({--}}
                        {{--    icon: 'success',--}}
                        {{--    title: "{{ __('message.created') }}",--}}
                        {{--    text: '{{ Session::get("success") }}',--}}
                        {{--    confirmButtonColor: "var(--bs-primary)",--}}
                        {{--    timer: 1000,--}}
                        {{--});--}}

                        showToast('Event created successfully.');

                        calendar.refetchEvents();
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        // Handle errors here
                    });
            }

        });




    </script>
@endpush

<x-app-layout :assets="$assets ?? []">
    <style>
        #external-events {
            /*position: fixed;*/
            /*z-index: 2;*/
            /*top: 20px;*/
            /*left: 20px;*/
            /*width: 150px;*/
            /*padding: 0 10px;*/
            border: 1px solid #ccc;
            background: #eee;
        }

        #external-events .fc-event {
            cursor: move;
            margin: 3px 0;
        }

        /*.popper,*/
        /*.tooltip {*/
        /*    position: absolute;*/
        /*    z-index: 9999;*/
        /*    background: #FFC107;*/
        /*    color: black;*/
        /*    width: 150px;*/
        /*    border-radius: 3px;*/
        /*    box-shadow: 0 0 2px rgba(0,0,0,0.5);*/
        /*    padding: 10px;*/
        /*    text-align: center;*/
        /*}*/
        /*.style5 .tooltip {*/
        /*    background: #1E252B;*/
        /*    color: #FFFFFF;*/
        /*    max-width: 200px;*/
        /*    width: auto;*/
        /*    font-size: .8rem;*/
        /*    padding: .5em 1em;*/
        /*}*/
        /*.popper .popper__arrow,*/
        /*.tooltip .tooltip-arrow {*/
        /*    width: 0;*/
        /*    height: 0;*/
        /*    border-style: solid;*/
        /*    position: absolute;*/
        /*    margin: 5px;*/
        /*}*/

        /*.tooltip .tooltip-arrow,*/
        /*.popper .popper__arrow {*/
        /*    border-color: #FFC107;*/
        /*}*/
        /*.style5 .tooltip .tooltip-arrow {*/
        /*    border-color: #1E252B;*/
        /*}*/
        /*.popper[x-placement^="top"],*/
        /*.tooltip[x-placement^="top"] {*/
        /*    margin-bottom: 5px;*/
        /*}*/
        /*.popper[x-placement^="top"] .popper__arrow,*/
        /*.tooltip[x-placement^="top"] .tooltip-arrow {*/
        /*    border-width: 5px 5px 0 5px;*/
        /*    border-left-color: transparent;*/
        /*    border-right-color: transparent;*/
        /*    border-bottom-color: transparent;*/
        /*    bottom: -5px;*/
        /*    left: calc(50% - 5px);*/
        /*    margin-top: 0;*/
        /*    margin-bottom: 0;*/
        /*}*/
        /*.popper[x-placement^="bottom"],*/
        /*.tooltip[x-placement^="bottom"] {*/
        /*    margin-top: 5px;*/
        /*}*/
        /*.tooltip[x-placement^="bottom"] .tooltip-arrow,*/
        /*.popper[x-placement^="bottom"] .popper__arrow {*/
        /*    border-width: 0 5px 5px 5px;*/
        /*    border-left-color: transparent;*/
        /*    border-right-color: transparent;*/
        /*    border-top-color: transparent;*/
        /*    top: -5px;*/
        /*    left: calc(50% - 5px);*/
        /*    margin-top: 0;*/
        /*    margin-bottom: 0;*/
        /*}*/
        /*.tooltip[x-placement^="right"],*/
        /*.popper[x-placement^="right"] {*/
        /*    margin-left: 5px;*/
        /*}*/
        /*.popper[x-placement^="right"] .popper__arrow,*/
        /*.tooltip[x-placement^="right"] .tooltip-arrow {*/
        /*    border-width: 5px 5px 5px 0;*/
        /*    border-left-color: transparent;*/
        /*    border-top-color: transparent;*/
        /*    border-bottom-color: transparent;*/
        /*    left: -5px;*/
        /*    top: calc(50% - 5px);*/
        /*    margin-left: 0;*/
        /*    margin-right: 0;*/
        /*}*/
        /*.popper[x-placement^="left"],*/
        /*.tooltip[x-placement^="left"] {*/
        /*    margin-right: 5px;*/
        /*}*/
        /*.popper[x-placement^="left"] .popper__arrow,*/
        /*.tooltip[x-placement^="left"] .tooltip-arrow {*/
        /*    border-width: 5px 0 5px 5px;*/
        /*    border-top-color: transparent;*/
        /*    border-right-color: transparent;*/
        /*    border-bottom-color: transparent;*/
        /*    right: -5px;*/
        /*    top: calc(50% - 5px);*/
        /*    margin-left: 0;*/
        /*    margin-right: 0;*/
        /*}*/

    </style>
    <div class="row">
        <div class="col-md-3">
            <div id='external-events'>
                <p>
                    <strong>My Workouts</strong>
                </p>
                @foreach($workouts as $wo)
                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'
                         data-id="{{ $wo->id }}">
                        <div class='fc-event-main'>{{ $wo->title }}</div>
                    </div>
                @endforeach

            </div>
        </div>
        <div class="col-md-9">
            <div id='calendar'></div>
        </div>
    </div>

</x-app-layout>
