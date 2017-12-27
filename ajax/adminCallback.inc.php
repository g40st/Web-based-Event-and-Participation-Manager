<?php
  /**
   * This is the callback for the adminScript.js
   *
   * author: Christian Högerle
   */

    if(!isset($_SESSION)) {
        session_start();
    }
    require_once('../conf/path.php');

    if (!isset($_SESSION['user'])) {
        header("HTTP/1.0 403 Forbidden");
        exit;
    } elseif($_SESSION['adminFlag'] == 0) {
        header("HTTP/1.0 403 Forbidden");
        exit;
    }

    require_once(PROJECT_ROOT . 'helper/dbconnect.php');

    $db = new Db();

    if($_POST['req_type'] == "deactivateNews") {
        $id = explode("newsDea_", $_POST['id']);
        $ret = $db->deactivateNews($id[1]);
        if($ret) {
            echo(json_encode(array('data' => true)));
        } else {
            echo(json_encode(array('data' => false)));
        }
    }

?>
