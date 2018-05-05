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

      // init the datetimepickers for event on user
      jQuery('#startDatepickerUser').datetimepicker();
      jQuery('#endDatepickerUser').datetimepicker();

      // listener for create comments
      $(".btn-createNewComment").click(function(event) {
        if($("#txtBoxComment").val().length > 4) {
          $.ajax({
              type: "POST",
              url:  "ajax/events.inc.php",
              contentType: "application/x-www-form-urlencoded;charset=utf-8",
              data: "req_type=createComment&eventID="+$(this).attr("data-event-id")+"&message="+$("#txtBoxComment").val(),
              success: function(json) {
                $("#txtBoxComment").hide();
                $(".btn-createNewComment").hide();
                $("#commentSuccess").show();
                setTimeout(function(){
                  location.reload();
                }, 2000);
              },
              error: function(json) {
                $("#txtBoxComment").hide();
                $(".btn-createNewComment").hide();
                $("#commentError").show();
              }
          });
        } else {
          alert("Bitte mindestens 5 Zeichen eingeben!");
        }
      });
});

function callbackClick(date, jsEvent, view, element) {
    date.second(0);
    date.minute(0);
    date.hour(0);
    //console.log('Clicked on: ' + date.format());

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
                  eventTable +=  '<tr><td>Startzeit:</td><td>' + '<input type="text" name="startDateUser" id="startDatepickerUser' + json[index]['id'] + '" placeholder="15:30">'+ '</td></td>';
                  eventTable +=  '<tr><td>Endzeit:</td><td>' + '<input type="text" name="endDateUser" id="endDatepickerUser' + json[index]['id'] + '" placeholder="17:00">' + ' <button type="button" id=buttonSaveWorkingUser_' + json[index]['id'] + ' class="btn btn-success btn-saveWorkingTime">Speichern</button></td></td>';
                } else {
                  eventTable +=  '<tr><td></td><td><button type="button" id=' + json[index]['id'] + ' class="btn btn-primary btn-participate">Teilnehmen</button></td></td>';
                }
                eventTable +=  '<tr><td>Beschreibung</td><td>' + json[index]['description'].replace(/\\r\\n/g, "<br>")  + '</td></td>';
                eventTable +=  '<tr><td>Kommentare <br><button type="button" data-event-id=' + json[index]['id'] + ' class="btn btn-success btn-comment" data-toggle="modal" data-target="#newCommentModal">Erstellen</button> </td><td>' + json[index]['comments'].replace(/\\r\\n/g, "<br>")  + '</td></td>';
                eventTable += '</table></div>';

                $('#tableEvents').append(eventTable);

                // fill the working time of a user if it exists
                if(json[index]['startWorking'] != "" && json[index]['endWorking'] != "") {
                  $('#startDatepickerUser' + json[index]['id']).val(json[index]['startWorking']);
                  $('#endDatepickerUser' + json[index]['id']).val(json[index]['endWorking']);
                }

              });

              // this will be called, when the modal is opened
              $('#newCommentModal').on('show.bs.modal', function(e) {
                  //get data-id attribute of the clicked element
                  var eventID = $(e.relatedTarget).data('event-id');
                  $('.btn-createNewComment').attr('data-event-id', eventID);
                  $(".btn-createNewComment").show();
                  $("#txtBoxComment").show();
                  $("#commentSuccess").hide();
                  $("#commentError").hide();
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

              // button save workingTime of user
              $(".btn-saveWorkingTime").click(function(event) {
                  event_id = event.target.id.split("_")[1];
                  console.log(event_id);
                  console.log($('#startDatepickerUser'+event_id).val());
                  // check the input of the user
                  var patt = new RegExp("[0-9]?[0-9]\:[0-9][0-9]");
                  console.log(patt.test($('#startDatepickerUser'+event_id).val()));
                  if(($('#startDatepickerUser'+event_id).val() == "") || ($('#endDatepickerUser'+event_id).val() == "")) {
                    alert("Bitte eine Start- und Endzeit eingeben.");
                  } else if(!patt.test($('#startDatepickerUser'+event_id).val())) {
                    alert("Feher bei der Startzeit. Bitte in der Form xx:xx angeben.");
                  } else if(!patt.test($('#endDatepickerUser'+event_id).val())) {
                    alert("Feher bei der Endzeit. Bitte in der Form xx:xx angeben.");
                  } else {
                    tmpButton = $(this)
                    $.ajax({
                      type: "POST",
                      url:  "ajax/events.inc.php",
                      contentType: "application/x-www-form-urlencoded;charset=utf-8",
                      data: "req_type=setWorkingTime&event_id="+event_id+"&start="+$('#startDatepickerUser'+event_id).val()+"&end="+$('#endDatepickerUser'+event_id).val(),
                      success: function(data) {
                        if(data == 1) {
                          tmpButton.html('gespeichert');
                          tmpButton.prop("disabled",true);
                        } else {
                          alert("Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später nochmals!");
                        }
                      }
                    });
                  }
              });

            } else {
                eventTable = '<div class="col-md-12">';
                eventTable += '<h4>Keine Termine an diesem Tag [' + date.format('DD-MM-YYYY') + '].</h4>';
                eventTable += '</div>';
                $('#tableEvents').append(eventTable);
            }
            if(jsEvent != null) {
              // automatic scroll to the section "termine"
              $('html, body').animate({scrollTop: $("#termine").offset().top-50}, 1000);
            }
        }
    });
}
