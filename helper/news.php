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
            $text = $arr_News[$i+2];
            if($flagAdmin) {
                $stringNews .= '<p class="lead">' . $arr_News[$i+1] . " [" . date('d-m-Y', strtotime($arr_News[$i+3])) . "]" . ': <button id="newsDea_' . $arr_News[$i] . '" type="button" class="btn btn-warning deakNews">Deaktivieren</button>';
                $stringNews .= '<br> <br>'. str_replace("\\r\\n","<br />",$text) . '</p>';
            } else {
                $stringNews .= '<p class="lead">' . $arr_News[$i+1] . " [" . date('d-m-Y', strtotime($arr_News[$i+3])) . "]" . ': <br> '. str_replace("\\r\\n","<br />",$text) . '</p>';
            }
            $stringNews .= '</div>';
        }
    }
    return $stringNews;
}

?>
