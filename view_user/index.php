<?php
  /**
   * This is the html-view for normal users. They see the calendar with upcoming events. They also can participate to these events.
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

    require_once(PROJECT_ROOT . 'helper/dbconnect.php');

    // load all active news from database
    $db = new Db();
    $arr_News = $db->queryForNews();
?>

<?php if(!$_SESSION['adminFlag']) {include(PROJECT_ROOT . 'includes/headerSite.php'); }?>

    <header class="masthead bg-primary text-white text-center">
        <div class="container">
            <h2 class="text-uppercase mb-0">Kalender</h2>
            <div id='calendar'></div>
        </div>
    </header>

    <!-- Termine Grid Section -->
    <section class="portfolio" id="termine">
      <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Termine</h2>
        <br><br>
        <div class="row" id="tableEvents">
          <!-- placeholder for event tables -->
        </div>

        <!-- Modal new comment-->
        <div class="modal fade" id="newCommentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Neuen Kommentar erstellen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="newCommentBody" class="modal-body">
                      <textarea id="txtBoxComment" name="comment" cols="40" rows="3" placeholder="Kommentar"></textarea>
                      <p id="commentSuccess" style="display: none;">Kommentar erfolgreich angelegt!</p>
                      <p id="commentError" style="display: none;">Es ist ein Fehler aufgetreten!</p>
                    </div>
                    <div id="newCommentFooter" class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zur&uuml;ck</button>
                        <button type="button" class="btn btn-primary btn-createNewComment">Erstellen</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- About Section -->
    <section class="bg-primary text-white mb-0" id="news">
        <div class="container">
            <h2 class="text-center text-uppercase text-white">News</h2>
            <hr class="star-light mb-5">
            <div class="row">
                <?php require_once('../helper/news.php');
                echo(printNews($arr_News, 0));
                ?>
            </div>
        </div>
    </section>


<?php if(!$_SESSION['adminFlag']) {include(PROJECT_ROOT . 'includes/footerSite.php'); }?>
