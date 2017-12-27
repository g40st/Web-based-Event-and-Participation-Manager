<?php
  /**
   * This is the login-html-view.
   *
   * author: Christian Högerle
   */
   
    ob_start();
    session_start();
    require_once('../conf/path.php');

    // if session is set direct to index
    if (isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "index.php");
        exit;
    }
    require_once(PROJECT_ROOT . 'helper/dbconnect.php');
    require_once( PROJECT_ROOT . 'helper/crypto.php');

    if(isset($_POST['btn-login'])) {
        $email = $_POST['email'];
        $upass = $_POST['pass'];

        $db = new Db();
        $arr_User = $db->queryForIDPassEmail($_POST['email']);

        if($arr_User['status'] && !$arr_User['active']) {
            $errMSG = "Benutzer noch nicht aktiviert!";
        } elseif($arr_User['status'] && checkPasswordHash($_POST['pass'], $arr_User['passwordHash'])) {
            $_SESSION['user'] = $arr_User['id'];
            $_SESSION['adminFlag'] = $arr_User['flagAdmin'];
            header("Location: " . BASE_URL . "index.php");
        } else $errMSG = "User or Password wrong!";
    }
?>

<?php include(PROJECT_ROOT . 'includes/headerLogin.php'); ?>

<div class="container">
    <div id="login-form">
        <form method="post" autocomplete="off">
            <div class="col-md-12">
                <div class="form-group">
                    <h2 class="">Login:</h2>
                </div>
                <div class="form-group">
                    <hr/>
                </div>
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

                <div class="form-group">
                    <hr/>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" name="btn-login">Login</button>
                </div>
                <div class="form-group">
                    <hr/>
                </div>
                <div class="form-group">
                    <a href="<?php echo(BASE_URL)?>view_login/register.php" type="button" class="btn btn-block btn-danger"
                       name="btn-login">Register</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include(PROJECT_ROOT . 'includes/footerLogin.php'); ?>