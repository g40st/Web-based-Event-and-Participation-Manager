<?php
  /**
   * This is the register-html-view. New users have to provide some information about them and put these information in a formular.
   *
   * author: Christian Högerle
   */
    ob_start();
    session_start();
    require_once('../conf/path.php');
    // if session is set direct to index
    if(isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "index.php");
        exit;
    }

    require_once(PROJECT_ROOT . 'helper/dbconnect.php');
    require_once( PROJECT_ROOT . 'helper/crypto.php');

    if(isset($_POST['signup'])) {
        $uname = trim($_POST['uname']); // get posted data and remove whitespace
        $email = trim($_POST['sname']);
        $email = trim($_POST['email']);
        $upass = trim($_POST['pass']);
        $email = trim($_POST['pass2']);

        if($_POST['pass'] != $_POST['pass2']) {
            $errTyp = "danger";
            $errMSG = "Passwörter stimmen nicht überein!";

        } elseif(strlen($_POST['pass']) < 4) {
            $errTyp = "danger";
            $errMSG = "Passwort muss mindestens 4 Zeichen haben!";
        } else {
            $db = new Db();
            $dbemail = $db->queryForEmail($_POST['email']);

            if (!$dbemail) {    // if email is not found add user
                $hash_pwd = hashPassword($_POST['pass']);
                $newUser = $db->insertNewUser($_POST['uname'], $_POST['sname'], $_POST['email'], $hash_pwd);
                if($newUser) {
                        $errTyp = "success";
                        $errMSG = "User angelegt! Sie müssen auf die Freischaltung warten! <br> Sie werden per Email benachrichtigt!";
                        // Email an Admin
                        require_once( PROJECT_ROOT . 'helper/sendMail.php');
                        sendMailToAdmin($_POST['email']);
                } else {
                    $errTyp = "danger";
                    $errMSG = "Something went wrong, try again!";
                }

            } else {
                $errTyp = "warning";
                $errMSG = "Email is already used!";
            }
        }

    }
?>

<?php include(PROJECT_ROOT . 'includes/headerLogin.php'); ?>

<div class="container">
    <div id="login-form">
        <form method="post" autocomplete="off">
            <div class="col-md-12">
                <div class="form-group">
                    <h2 class="">Registrierung</h2>
                </div>
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
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="uname" class="form-control" placeholder="Vorname" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="sname" class="form-control" placeholder="Nachname" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="email" name="email" class="form-control" placeholder="Email Adresse" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="pass" class="form-control" placeholder="Passwort"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="pass2" class="form-control" placeholder="Password wiederholen"
                               required/>
                    </div>
                </div>
                <!--
                <div class="checkbox">
                    <label><input type="checkbox" id="TOS" value="This"><a href="#">I agree with
                            terms of service</a></label>
                </div>
                -->
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" name="signup" id="reg">Registrieren</button>
                </div>
                <div class="form-group">
                    <hr/>
                </div>
                <div class="form-group">
                    <a href="<?php echo(BASE_URL)?>view_login/login.php" type="button" class="btn btn-block btn-success" name="btn-login">Login</a>
                </div>
            </div>
        </form>
    </div>
</div>


<?php include(PROJECT_ROOT . 'includes/footerLogin.php'); ?>
