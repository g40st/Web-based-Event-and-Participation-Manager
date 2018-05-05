<?php
  /**
   * This file contains the admin-html-view.
   * In this view, the admin can create new events or news. They also can deactivate news.
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
    } elseif($_SESSION['adminFlag'] == 0) { // User-Seite laden
        header("Location: " . BASE_URL . "view_user/index.php");
        exit;
    }
    require_once(PROJECT_ROOT . 'helper/dbconnect.php');

    // create a new event
    if(isset($_POST['createEvent'])) {
        $titel = trim($_POST['titel']);
        $text = trim($_POST['description']); // get posted data and remove whitespace
        $amountP = trim($_POST['amountP']);

        if(date($_POST['startDate']) > date($_POST['endDate'])) {
          $errTyp = "danger";
          $errMSGTaetigkeit = "Bitte Start- und End-Datum überprüfen!";
        } else if(strlen($titel) > 0 && $amountP > 0) {
            $db = new Db();
            $ret = $db->insertNewEvent($titel, $amountP, date($_POST['startDate']), date($_POST['endDate']), $text);

            if($ret) {
                $errTyp = "success";
                $errMSGTaetigkeit = "Tätigkeit erfolgreich angelegt!";
            } else {
                $errTyp = "danger";
                $errMSGTaetigkeit = "Es ist ein Fehler aufgetreten!";
            }
        } else {
          $errTyp = "danger";
          $errMSGTaetigkeit = "Fehler im Titel oder der Teilnehmeranzahl!";
        }
    }

    // handle new "News" entry
    if(isset($_POST['createNews'])) {
        $text = trim($_POST['text']); // get posted data and remove whitespace
        $titel = trim($_POST['titel']);
        if(strlen($text) > 10 && strlen($titel) > 5) {
            $db = new Db();
            $ret = $db->insertNewNews($titel, $text);
            if($ret) {
                $errTyp = "success";
                $errMSG = "Nachricht erfoglreich angelegt!";
            } else {
                $errTyp = "danger";
                $errMSG = "Es ist ein Fehler aufgetreten!";
            }
        }
    }
?>

<?php include(PROJECT_ROOT . 'includes/headerSite.php'); ?>
<?php include(PROJECT_ROOT . 'view_user/index.php'); ?>

<section class="portfolio" id="admin">
    <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Admin</h2>
        <div class="row">
            <div class="col-sm-12">
                <br><h3>T&auml;tigkeiten</h3><br>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
              <hr/>
              </div>
              <?php
                if (isset($errMSGTaetigkeit)) {
              ?>
                  <div class="form-group">
                      <div class="alert alert-<?php echo ($errTyp == "success") ? "success" : $errTyp; ?>">
                          <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSGTaetigkeit; ?>
                      </div>
                  </div>
              <?php
                }
              ?>
              <form method="post">
                  <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                          <input type="text" name="titel" class="form-control" placeholder="Titel" required/>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-users" aria-hidden="true"></i></span>
                          <input type="number" name="amountP" class="form-control" placeholder="Teilnehmerzahl" required/>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          <input type="text" name="startDate" id="startDatepicker" placeholder="Start" required>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          <input type="text" name="endDate" id="endDatepicker" placeholder="Ende" required>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                          <textarea name="description" cols="80" rows="5" placeholder="Beschreibung"></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-block btn-primary" name="createEvent">Tätigkeit erstellen</button>
                  </div>
              </form>
            </div>
        </div>
        <br><br>
        <hr><hr><hr>
        <br><br>
        <div class="row">
            <div class="col-sm-12" id="newsAdmin">
                <h3>News</h3><br>
            </div>
                <?php require_once('../helper/news.php');
                      echo(printNews($arr_News, 1));
                ?>
            <div class="col-sm-12">
                <br><br>
                <div class="form-group">
                <hr/>
                </div>
                <?php
                  if (isset($errMSG)) {
                ?>
                    <div class="form-group">
                        <div class="alert alert-<?php echo ($errTyp == "success") ? "success" : $errTyp; ?>">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                        </div>
                    </div>
                <?php
                  }
                ?>
                <div class="form-group">
                    <h4 class="">Neue Nachricht</h4>
                </div>
                <form method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                            <input type="text" name="titel" class="form-control" placeholder="Titel" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                            <textarea name="text" cols="80" rows="5" placeholder="Nachricht"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" name="createNews" id="reg">Erstellen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include(PROJECT_ROOT . 'includes/footerSite.php'); ?>
