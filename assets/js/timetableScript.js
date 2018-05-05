$(document).ready(function() {
    // the admin url is known, therefore we can split on view_admin | URL: http://127.0.0.1/Login/view_admin/index.php
    actURL = window.location.href.split("view_admin");
    ajaxURL = actURL[0] + 'ajax/adminCallback.inc.php';

    $.ajax({
        type: "POST",
        url:  ajaxURL,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
        data: "req_type=getTimeAllUsers",
        success: function(data) {
            json = JSON.parse(data);
            if(json.length > 0) {
                var totalTime = 0;
                $.each(json, function (index, value) {
                  userTable =  '<tr><td>' + (index+1) + '</td><td>' + json[index]['firstname'] + " " + json[index]['lastname'] + '</td><td>' + (json[index]['time']/60) + ' h </td>' + '</tr>';
                  totalTime += json[index]['time'];
                  $('#bodyWorkingTime').append(userTable);
                });
                userTable =  '<tr><td>' + "" + '</td><td>' + "Gesamtzeit" + '</td><td>' + totalTime/60 + ' h </td>' + '</tr>';
                $('#bodyWorkingTime').append(userTable);
            } else {
                console.log(json['data']);
                alert("Es ist ein Fehler aufgetreten!");
            }
        }
    });
});
