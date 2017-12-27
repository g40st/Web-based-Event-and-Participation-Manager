$(document).ready(function() {

     $('#calendar').fullCalendar({

        fixedWeekCount: false,
        weekNumbers: true,
        events: "ajax/events.inc.php?view=1",
        displayEventEnd: true,
        aspectRatio: 2.5,
        defaultView: 'week',
          views: {
              week: {
                  type: 'basic',
                  duration: { weeks: 3}
              }
          },

        dayClick: function(date, jsEvent, view) {
          callbackClick(date, jsEvent, view, $(this));
        },
        dayRender: function (date, cell) {
          cell.attr("id", date);
        },
        eventClick: function(calEvent, jsEvent, view) {
          callbackClick(calEvent.start, jsEvent, view, $(this));
        }

      });

      // query the actual day
      date = new Date();
      var tmpElement = $('td').find("td.fc-today");
      id = tmpElement.attr('id');
      callbackClick(moment(date), null, null, $('#' + id));

      // init the datetimepickers for event creation
      jQuery('#startDatepicker').datetimepicker();
      jQuery('#endDatepicker').datetimepicker();
});


function callbackClick(date, jsEvent, view, element) {
    date.second(0);
    date.minute(0);
    date.hour(0);
    console.log('Clicked on: ' + date.format());

    $.ajax({
        type: "POST",
        url:  "ajax/events.inc.php",
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
        data: "req_type=getDay&day="+date.format(),
        success: function(json) {
            // remove all items
            $('#tableEvents').empty();
            if(json.length > 0) {
              $.each(json, function (index, value) {
                countParticipants = json[index]['names'].split(";");

                eventTable = '<div class="table-responsive col-md-6">';
                eventTable +=  '<table class="table table-striped">';
                eventTable +=  '<tr><td>Titel</td><td>' + json[index]['title']  + '</td></td>';
                eventTable +=  '<tr><td>Datum</td><td>' + moment(json[index]['start']).format('DD-MM-YYYY')  + '</td></td>';
                eventTable +=  '<tr><td>Start</td><td>' + moment(json[index]['start']).format('HH:mm')  + ' Uhr</td></td>';
                eventTable +=  '<tr><td>Ende</td><td>' + moment(json[index]['end']).format('HH:mm')  + ' Uhr</td></td>';
                eventTable +=  '<tr><td>Teilnehmerzahl</td><td>' + (countParticipants.length - 1) + '/' + json[index]['participants']  + '</td></td>';
                eventTable +=  '<tr><td>Teilnehmer</td><td>' + json[index]['names']  + '</td></td>';
                if(json[index]['signed_in']) {
                  eventTable +=  '<tr><td></td><td><button type="button" id=' + json[index]['id'] + ' class="btn btn-warning btn-participate">Austragen</button></td></td>';
                } else {
                  eventTable +=  '<tr><td></td><td><button type="button" id=' + json[index]['id'] + ' class="btn btn-primary btn-participate">Teilnehmen</button></td></td>';
                }
                eventTable +=  '<tr><td>Beschreibung</td><td>' + json[index]['description']  + '</td></td>';
                eventTable += '</table></div>';

                $('#tableEvents').append(eventTable);
              });

              // button for participate
              $(".btn-participate").click(function(event) {
                  tmpButton = $(this)
                  $.ajax({
                      type: "POST",
                      url:  "ajax/events.inc.php",
                      contentType: "application/x-www-form-urlencoded;charset=utf-8",
                      data: "req_type=setParticipant&event_id="+event.target.id+"&type="+$(this).text(),
                      success: function(data) {
                        if(data == 1) {
                          // callback the day click with the actual date
                          var tmpElement = $('td').find("td.fc-day[data-date='" +  date + "']");
                          id = tmpElement.attr('id');
                          callbackClick(moment(date), null, null, $('#' + id));
                        } else {
                          alert("Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später nochmals!");
                        }
                      }
                    });

              });
            } else {
                eventTable = '<div class="col-md-12">';
                eventTable += '<h4>Keine Termine an diesem Tag [' + date.format('DD-MM-YYYY') + '].</h4>';
                eventTable += '</div>';
                $('#tableEvents').append(eventTable);
            }
        }
    });
}
