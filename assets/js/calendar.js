$(document).ready(function() {
     $('#calendar').fullCalendar({
        fixedWeekCount: false,
        weekNumbers: true,
        dayClick: function(date, jsEvent, view) {
            console.log('Clicked on: ' + date.format());
            console.log('Current view: ' + view.name);
            $(this).css('background-color', 'red');

        }
    })

});