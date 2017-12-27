<?php
  /**
   * This file redirects the requests bassed on the permissions.
   *
   * author: Christian Högerle
   */

    ob_start();
    session_start();
    require_once('conf/path.php');

    if (!isset($_SESSION['user'])) {    // Keine Session vorhanden -> umleiten auf Loginseite
        header("Location: " . BASE_URL . "view_login/login.php");
        exit;
    } elseif($_SESSION['adminFlag'] == 0) { // User-Seite laden
        header("Location: " . BASE_URL . "view_user/index.php");
        exit;
    } elseif($_SESSION['adminFlag'] == 1) { // Admin-Seite laden
        header("Location: " . BASE_URL . "view_admin/index.php");
        exit;
    }
?>
