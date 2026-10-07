document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.querySelector('.calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth'
    });

    calendar.render();
});