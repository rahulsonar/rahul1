<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Meeting Calendar</h2>
    <div id="calendar" style="background:#fff;padding:10px;margin-top:10px;"></div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: moment().format('YYYY-MM-DD'),
            defaultView: 'month',
            editable: false,
            eventRender: function (event, element) {
                element.qtip({
                    content: {
                        title: {text: event.title},
                        text: '<img src="https://www.google.co.in/images/branding/googlelogo/2x/googlelogo_color_120x44dp.png" style="width:150px;"><br><span class="title">Start: </span>' + (moment(event.start).format('MM/DD/YYYY hh:mm')) + '<br><br><span class="title">Title: </span>' + event.content
                    },
                    show: {solo: true},
                    //hide: { when: 'inactive', delay: 3000 }, 
                    style: {
                        width: 200,
                        padding: 5,
                        color: 'black',
                        textAlign: 'left',
                        border: {
                            width: 1,
                            radius: 3
                        },
                        tip: 'topLeft',
                        classes: 'qtip-tipsy'
                    }
                });
            },
            eventClick: function (calEvent, jsEvent, view) {
                //replace calEvent.url
                window.location.href = 'http://www.google.com';
            },
            events: [
                {
                    title: 'All Day Event',
                    content: 'This is content of event',
                    start: '2016-12-01'
                },
                {
                    title: 'Long Event',
                    content: 'This is content of event',
                    start: '2014-06-07',
                    end: '2016-12-10'
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    content: 'This is content of event',
                    start: '2016-12-09T16:00:00'
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    content: 'This is content of event',
                    start: '2016-12-16T16:00:00'
                },
                {
                    title: 'Meeting',
                    content: 'This is content of event',
                    start: '2016-12-12T10:30:00',
                    end: '2016-12-12T12:30:00'
                },
                {
                    title: 'Lunch',
                    content: 'This is content of event',
                    start: '2016-12-12T12:00:00'
                },
                {
                    title: 'Birthday Party',
                    content: 'This is content of event',
                    start: '2016-12-13T07:00:00'
                },
                {
                    title: 'Click for Google',
                    content: 'This is content of event',
                    url: 'http://google.com/',
                    start: '2016-12-28'
                }
            ],
            eventColor: '#23282d'
        });
    });
</script>