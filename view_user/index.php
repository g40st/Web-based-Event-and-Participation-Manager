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
    // load news from database
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
        <div class="row">
            <div class="col-md-8 col-lg-4">
                <table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>
                Übersicht
            </th>
            <th>
                [HEADER]
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>
                [HEADER]
            </th>
            <th>
                [HEADER]
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
        <tr>
            <td>
                [CONTENT]
            </td>
            <td>
                [CONTENT]
            </td>
        </tr>
    </tbody>
</table>
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
