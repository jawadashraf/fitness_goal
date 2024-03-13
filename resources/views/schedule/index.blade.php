@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
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

                    if(info.event.id == -1){
                        console.log('eventContent', info);
                    }
                    var eventTitle = info.event.title;
                    var eventElement = document.createElement('div');
                    eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

                    eventElement.querySelector('span').addEventListener('click', function() {
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: "{{ __('message.deleted') }}",
                                        text: '{{ Session::get("success") }}',
                                        confirmButtonColor: "var(--bs-primary)",
                                        timer: 1000,
                                    });
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
                drop: function(info) {
                    console.log(info);
                    var workoutId = info.draggedEl.getAttribute('data-id');
                    var title = info.draggedEl.innerText;
                    // Extract the date where the element was dropped
                    var dropDate = info.dateStr;
                    var allDay = info.allDay;



                    // Immediately add the event to the calendar
                    // var newEvent = calendar.addEvent({
                    //     title: title,
                    //     start: dropDate,
                    //     allDay: allDay,
                    //     // Temporarily use the workoutId or another placeholder for id
                    //     id: -1,
                    // });

                    createWorkoutSchedule(workoutId, title, dropDate, allDay);
                },
                eventDrop: function(info) {
                    // console.log('eventDrop ',info.event);
                    // var eventId = info.event.id;
                    // var newStartDate = info.event.start;
                    // var newEndDate = info.event.end || newStartDate;
                    // var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                    // var newEndDateUTC = newEndDate.toISOString().slice(0, 10);
                },
                navLinks: true,
                navLinkDayClick: function(date, jsEvent) {
                    console.log('day', date.toISOString());
                    console.log('coords', jsEvent.pageX, jsEvent.pageY);
                }
            });
            calendar.render();


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


                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.created') }}",
                            text: '{{ Session::get("success") }}',
                            confirmButtonColor: "var(--bs-primary)",
                            timer: 1000,
                        });

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
