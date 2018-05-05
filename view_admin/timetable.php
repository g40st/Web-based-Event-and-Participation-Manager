<?php
  /**
   * This file contains the timetable-html-view.
   * In this view, the admin gets an overview on the working hours
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
    } elseif($_SESSION['timeFlag'] == 0 && $_SESSION['adminFlag'] == 0) { // User-Seite laden
        header("Location: " . BASE_URL . "view_user/index.php");
        exit;
    } elseif($_SESSION['timeFlag'] == 0 && $_SESSION['adminFlag'] == 1) { // Admin-Seite laden
        header("Location: " . BASE_URL . "view_admin/index.php");
        exit;
    }
?>

<?php include(PROJECT_ROOT . 'includes/headerSite.php'); ?>

<?php if($_SESSION['adminFlag'] == 1) { ?>
    <script src="assets/js/timetableScript.js"></script>
<?php } ?>
<section class="portfolio" id="admin">
    <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0"><br><br>Übersicht</h2>
        <div class="row">
            <div class="col-sm-12">
              <br>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Arbeitszeit</th>
                  </tr>
                </thead>
                <tbody id="bodyWorkingTime">
                </tbody>
              </table>
            </div>
        </div>
    </div>
</section>

<?php include(PROJECT_ROOT . 'includes/footerSite.php'); ?>
