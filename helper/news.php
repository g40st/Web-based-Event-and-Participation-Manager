<?php
/**
 * This function prints the active news in the actual html page.
 * The function differentiate between admin and normal users.
 * The admin gets a "deactive"-button.
 *
 * author: Christian Högerle
 */

function printNews($arr_News, $flagAdmin) {
    $stringNews = "";
    if(sizeof($arr_News) == 0) {
        $stringNews .= '<div class="col-sm-12 col-sm-offset-3 text-center">';
            $stringNews .= '<h4>Aktuell liegen keine Nachrichten vor!</h4>';
        $stringNews .= '</div>';
    } else {
        for ($i = 0; $i < sizeof($arr_News); $i = $i + 4) {
            $stringNews .= '<div class="col-lg-12 ">';
            $stringNews .= '<div class="panel"><div class="panel-heading"><div class="text-center"><div class="row"><div class="col-sm-9">';
            $text = $arr_News[$i+2];

            if($flagAdmin) {
                $stringNews .= '<h3 class="pull-left">' . $arr_News[$i+1] .  ' <button id="newsDea_' . $arr_News[$i] . '" type="button" class="btn btn-warning deakNews">Deaktivieren</button></h3></div><div class="col-sm-3"><h4 class="pull-right">';
            } else {
                $stringNews .= '<h3 class="pull-left">' . $arr_News[$i+1] .  '</h3></div><div class="col-sm-3"><h4 class="pull-right">';
            }
            $stringNews .= '<small><em>' . date('d-m-Y', strtotime($arr_News[$i+3])) . '</em></small>';
            $stringNews .= '</h4></div></div></div></div>';
            $stringNews .= '<div class="panel-body">' . str_replace("\\r\\n","<br />",$text) . '</div></div></div>';
        }
    }
    return $stringNews;
}

?>
