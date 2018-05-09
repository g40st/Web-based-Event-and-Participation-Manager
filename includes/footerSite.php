<?php
    ob_start();
    if(!isset($_SESSION['user'])) { // if session is set direct to index
        header("HTTP/1.0 404 Not Found");
        exit;
    }
?>

    <div class="copyright py-4 text-center text-white">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <span class="small">Copyright © 2018 - Christian H&ouml;gerle</span>
          </div>
          <div class="col-md-3">
              <span class="small"><a href="./disclaimer/imprint.html">Impressum</a></span> | <span class="small"><a href="./disclaimer/dataProtection.html">Datenschutz</a></span>
          </div>
        </div>
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
