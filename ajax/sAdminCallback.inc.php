<?php
  /**
   * This is the callback for the sadminScript.js
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
    } elseif($_SESSION['adminFlag'] == 0 || $_SESSION['user'] != 1) {
        header("HTTP/1.0 403 Forbidden");
        exit;
    }

    require_once(PROJECT_ROOT . 'helper/dbconnect.php');

    // callback fuer activate-button in sadminScript (activate user)
    if($_POST['req_type'] == "activateUser") {
        $id = $_POST['id'];
        $db = new Db();
        $ret = $db->activateUser($id);

        if($ret) {
            // send mail to users and admin
            require_once( PROJECT_ROOT . 'helper/sendMail.php');
            sendMailToUser($_POST['email']);

            echo(json_encode(array('data' => true)));
        } else {
            echo(json_encode(array('data' => false)));
        }
    }

?>
