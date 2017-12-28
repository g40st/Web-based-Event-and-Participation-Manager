<?php
  /**
   * This is the callback for the events handle
   *
   * author: Christian Högerle
   */

  if(!isset($_SESSION))
  {
      session_start();
  }
  require_once('../conf/path.php');

  if (!isset($_SESSION['user'])) {    // Keine Session vorhanden -> umleiten auf Loginseite
      header("Location: " . BASE_URL . "view_login/login.php");
      exit;
  }

  require_once('../conf/path.php');
  require_once(PROJECT_ROOT . 'helper/dbconnect.php');

  // view all events from start-date to end-date
  if(isset($_GET['view'])) {
    header('Content-Type: application/json');
    $start = trim($_GET["start"]);
    $end = trim($_GET["end"]);

    $db = new Db();
    $json_events = $db->queryForEvents($start, $end);

    echo($json_events);
    exit;
  }

  // get events on a day and further information about these events
  if($_POST['req_type'] == "getDay") {
      header('Content-Type: application/json');
      $day = trim($_POST["day"]);

      $db = new Db();
      // get all events on this date
      $json_events = $db->queryForEventsOnDay($day);

      // get all participants to the events on this date
      if(sizeof($json_events) > 0) {
        for ($i = 0; $i < count($json_events); $i++) {
          $arr_result = $db->queryForParticipantsOnEvent($json_events[$i]['id']);

          // put the string of the participants in the json($arr_result[0])
          $p['names'] = $arr_result[0];
          $json_events[$i] += $p;

          // check if a users is already participant
          $signed_in = false;
          for ($k = 0; $k < count($arr_result[1]); $k++) {
            if($arr_result[1][$k] === $_SESSION['user']) {
              $signed_in = true;
              break;
            }
          }

          $sign['signed_in'] = $signed_in;
          $json_events[$i] += $sign;
        }
      }

      echo(json_encode($json_events));
      exit;
  }

  // insert or delete participant
  if($_POST['req_type'] === "setParticipant") {
      if($_POST['type'] === "Austragen") {
        $id = trim($_POST['event_id']);

        $db = new Db();
        $result = $db->deleteEntryFromUsersEvents($_SESSION['user'], $id);

        echo($result);
      } else {
        $id = trim($_POST['event_id']);

        $db = new Db();
        $result = $db->insertNewParticipant($_SESSION['user'], $id);

        echo($result);
      }
      exit;
  }

?>
