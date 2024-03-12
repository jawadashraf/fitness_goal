@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>

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
                        title: eventEl.innerText
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
                drop: function(info) {
                    // is the "remove after drop" checkbox checked?
                    // if (checkbox.checked) {
                    //     // if so, remove the element from the "Draggable Events" list
                    //     info.draggedEl.parentNode.removeChild(info.draggedEl);
                    // }
                },
                // events: '/events',
                events: [
                    {
                        title: 'Cardio Workout',
                        start: '2024-03-12T14:30:00',
                        extendedProps: {
                            status: 'done'
                        }
                    },
                    {
                        title: 'Strength WO',
                        start: '2024-03-13T07:00:00',
                        // backgroundColor: 'green',
                        // borderColor: 'green'
                    }
                ],
                eventDidMount: function(info) {
                    if (info.event.extendedProps.status === 'done') {

                        // Change background color of row
                        info.el.style.backgroundColor = '#ff00ff';

                        // Change color of dot marker
                        var dotEl = info.el.getElementsByClassName('fc-event-dot')[0];
                        if (dotEl) {
                            dotEl.style.backgroundColor = 'red';
                        }
                    }
                },
                navLinks: true,
                navLinkDayClick: function(date, jsEvent) {
                    console.log('day', date.toISOString());
                    console.log('coords', jsEvent.pageX, jsEvent.pageY);
                }
            });
            calendar.render();
        });

    </script>
@endpush

<x-app-layout :assets="$assets ?? []">
{{--    <style>--}}
{{--        #external-events {--}}
{{--            position: fixed;--}}
{{--            z-index: 2;--}}
{{--            top: 20px;--}}
{{--            left: 20px;--}}
{{--            width: 150px;--}}
{{--            padding: 0 10px;--}}
{{--            border: 1px solid #ccc;--}}
{{--            background: #eee;--}}
{{--        }--}}

{{--        #external-events .fc-event {--}}
{{--            cursor: move;--}}
{{--            margin: 3px 0;--}}
{{--        }--}}
{{--    </style>--}}
    <div class="row">
        <div class="col-md-3">
            <div id='external-events'>
                <p>
                    <strong>My Workouts</strong>
                </p>

                <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'>
                    <div class='fc-event-main'>Workout 1</div>
                </div>
                <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'>
                    <div class='fc-event-main'>Workout 2</div>
                </div>
                <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'>
                    <div class='fc-event-main'>Workout 3</div>
                </div>
                <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'>
                    <div class='fc-event-main'>Workout 4</div>
                </div>
                <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event m-2 p-1'>
                    <div class='fc-event-main'>Workout 5</div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div id='calendar'></div>
        </div>
    </div>

</x-app-layout>
