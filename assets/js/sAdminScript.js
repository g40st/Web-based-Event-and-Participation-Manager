$(document).ready(function() {
    // the admin url is known, therefore we can split on view_sadmin | URL: http://127.0.0.1/Login/view_sadmin/index.php
    actURL = window.location.href.split("view_sadmin");
    ajaxURL = actURL[0] + 'ajax/sAdminCallback.inc.php';

    $(".actUser").click(function(event) {
        $.ajax({
            type: "POST",
            url:  ajaxURL,
            contentType: "application/x-www-form-urlencoded;charset=utf-8",
            data: "req_type=activateUser&id="+event.target.id+"&email="+$(this).attr('data-email'),
            success: function(data) {
                json = JSON.parse(data);
                if(json['data']) {
                    location.reload();
                } else {
                    console.log(json['data']);
                    alert("Es ist ein Fehler aufgetreten!");
                }
            }
        });
    });
});
