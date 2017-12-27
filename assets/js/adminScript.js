$(document).ready(function() {
    // the admin url is known, therefore we can split on view_admin | URL: http://127.0.0.1/Login/view_admin/index.php
    actURL = window.location.href.split("view_admin");
    ajaxURL = actURL[0] + 'ajax/adminCallback.inc.php';

    $(".deakNews").click(function(event) {
        $.ajax({
            type: "POST",
            url:  ajaxURL,
            contentType: "application/x-www-form-urlencoded;charset=utf-8",
            data: "req_type=deactivateNews&id="+event.target.id,
            success: function(data) {
                json = JSON.parse(data);
                if(json['data']) {
                    var varAppend = "#newsAdmin";
                    window.location.href = document.URL.split("#")[0] + varAppend;
                    location.reload();
                } else {
                    console.log(json['data']);
                    alert("Es ist ein Fehler aufgetreten!");
                }
            }
        });
    });
});
