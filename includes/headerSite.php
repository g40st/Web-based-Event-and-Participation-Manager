<?php
  /**
   * This file contains the header of the html page. All css- and javaScript-files will be loaded.
   *
   * author: Christian Högerle
   */

    ob_start();
    if(!isset($_SESSION['user'])) { // if session is set direct to index
        header("HTTP/1.0 404 Not Found");
        exit;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="content-language" content="de" />
    <meta name="robots" content="index, follow" />

    <title>Testseite</title>

    <base href="<?php echo(BASE_URL); ?>" />

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="assets/vendor/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <!-- Plugin CSS -->
    <link href="assets/vendor/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="assets/css/freelancer.min.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/fullcalendar/js/moment.min.js"></script>
    <script src="assets/vendor/jquery/jquery-ui.min.js"></script>
    <script src="assets/vendor/fullcalendar/js/fullcalendar.min.js"></script>
    <script src='assets/vendor/fullcalendar/locale/de.js'></script>

    <!-- Plugin JavaScript -->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <script src="assets/js/calendar.js"></script>

    <?php if($_SESSION['adminFlag'] == 1) { ?>
        <script src="assets/js/adminScript.js"></script>
    <?php } ?>


</head>
<body id="page-top">
    <noscript><h2>JavaScript muss aktiviert sein um eine Reservierung vorzunehmen!</h2></noscript>

   <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top text-uppercase" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Testseite</a>
        <button class="navbar-toggler navbar-toggler-right text-uppercase bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#termine">Termine</a>
            </li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#news">News</a>
            </li>
            <?php if($_SESSION['adminFlag'] == 1) { ?>
                <li class="nav-item mx-0 mx-lg-1">
                  <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#admin">ADMIN</a>
                </li>
            <?php } ?>


            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?php echo(BASE_URL)?>view_login/logout.php?logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
