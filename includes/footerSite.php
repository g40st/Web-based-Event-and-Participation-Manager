<?php
    ob_start();
    if(!isset($_SESSION['user'])) { // if session is set direct to index
        header("HTTP/1.0 404 Not Found");
        exit;
    }
?>

    <div class="copyright py-4 text-center text-white">
      <div class="container">
        <small>Copyright &copy; Christian Högerle 2018</small>
      </div>
    </div>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-to-top d-lg-none position-fixed ">
      <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
        <i class="fa fa-chevron-up"></i>
      </a>
    </div>

    </body>
</html>