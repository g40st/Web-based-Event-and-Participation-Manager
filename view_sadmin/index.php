<?php
/**
 * This file contains the super-admin-html-view.
 * The super admin can activate users in this view.
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
  } elseif($_SESSION['adminFlag'] == 0 || $_SESSION['user'] != 1) {
      header("HTTP/1.0 403 Forbidden");
      exit;
  }

  require_once(PROJECT_ROOT . 'helper/dbconnect.php');

  // load users (not actived yet) from database
  $db = new Db();
  $arr_users = $db->queryForNonActUsers();

  if(isset($_POST['btn-reset'])) {
    if(strlen($_POST['email']) > 0 && strlen($_POST['pass']) > 0) {

      // hash password
      require_once( PROJECT_ROOT . 'helper/crypto.php');
      $hash_pwd = hashPassword($_POST['pass']);

      $db = new Db();
      $ret = $db->resetPassword($_POST['email'], $hash_pwd);

      if($ret) {
        $errMSG = "Passwort erfolgreich geändert! Bitte den Zugang prüfen!";
      } else {
        $errMSG = "Es ist ein Fehler aufgetreten!";
      }
    }
  }
?>
<html>
  <head>
    <title>Super Admin Page</title>
    <base href="<?php echo(BASE_URL); ?>" />
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/sAdminScript.js"></script>
  </head>
  <body>
    <h1>Super Admin Page</h1>
    <h3>Nicht aktive Benutzer:</h3>
    <table>
        <tr>
          <th>Vorname</th>
          <th>Nachname</th>
          <th>E-mail</th>
          <th></th>
        </tr>
        <tr>
          <?php
            if(sizeof($arr_users) == 0) {
              echo('<td>Aktuell liegen keine Benutzer vor!</td>');
              echo('<td></td><td></td>');

            } else {
              for ($i = 0; $i < sizeof($arr_users); $i = $i + 4) {
                echo('<tr>');
                echo('<td>'.$arr_users[$i+1].'</td>');
                echo('<td>'.$arr_users[$i+2].'</td>');
                echo('<td>'.$arr_users[$i+3].'</td>');
                echo('<td><button class="actUser" data-email="'.$arr_users[$i+3].'" id="'.$arr_users[$i].'" type="button">Activate</button></td>');
                echo('</tr>');
              }
            }
           ?>
        </tr>
    </table>

    <h3>Passwort-Reset:</h3>
    <form method="post" autocomplete="off">
      <div class="form-group"><hr/></div>
        <?php
          if (isset($errMSG)) {
        ?>
          <div class="form-group">
              <div class="alert alert-danger">
                  <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
              </div>
          </div>
        <?php
        }
        ?>
      <div class="form-group">
          <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
              <input type="email" name="email" class="form-control" placeholder="Email" required/>
          </div>
      </div>
      <div class="form-group">
          <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
              <input type="password" name="pass" class="form-control" placeholder="Password" required/>
          </div>
      </div>

      <div class="form-group"><hr/></div>

      <div class="form-group">
          <button type="submit" class="btn btn-block btn-primary" name="btn-reset">Reset</button>
      </div>
    </from>
    <br><br>
    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?php echo(BASE_URL)?>view_login/logout.php?logout">Logout</a>
  </body>
</html>
