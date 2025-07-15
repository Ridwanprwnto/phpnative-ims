<?php

ob_start();
if (session_status()!==PHP_SESSION_ACTIVE)session_start();

require 'includes/config/timezone.php';
require 'includes/function/func.php';
require 'includes/config/conn.php';

$url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/register.php";

if(isset($_POST["register"])) {

  if(registrasi($_POST) > 0 ) {

    header("location: login.php");

  } else {

    echo mysqli_error($conn);

}
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<?php
  include ("includes/templates/meta.php");
?>
<title>Register - Inventory Management System</title>
<?php
  include ("includes/templates/css-login.php");
?>
</head>
<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image menu-expanded blank-page blank-page"
data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <section class="flexbox-container">
          <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-10 box-shadow-2 p-0">
              <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                <div class="card-header border-0 pb-0">
                  <div class="card-title text-center">
                    <img src="app-assets/images/logo/login_logo.png" alt="branding logo">
                    <h2><strong>INVENTORY MANAGEMENT SYSTEM</strong></h2>
                  </div>
                  <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                    <span>Sign up and Join with Us</span>
                  </h6>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <form class="form-horizontal" action="" method="post">
                      <input type="hidden" class="form-control" name="page" value="<?= $url; ?>">
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" id="user-office" name="user-office" placeholder="Office Code" required>
                        <div class="form-control-position">
                          <i class="ft-home"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="number" class="form-control" id="user-nik" name="user-nik" placeholder="Nik">
                        <div class="form-control-position">
                          <i class="ft-user"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" id="user-name" name="user-name" placeholder="User Name">
                        <div class="form-control-position">
                          <i class="ft-user"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="email" class="form-control" id="user-email" name="user-email" placeholder="Your Email Address" required>
                        <div class="form-control-position">
                          <i class="ft-mail"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control" id="user-password" name="user-password" placeholder="Enter Password" required>
                        <div class="form-control-position">
                          <i class="la la-key"></i>
                        </div>
                      </fieldset>
                      <div class="form-group row">
                        <div class="col-md-6 col-12 text-center text-sm-left">
                          <!-- <fieldset>
                            <input type="checkbox" id="remember-me" class="chk-remember">
                            <label for="remember-me"> Remember Me</label>
                          </fieldset> -->
                        </div>
                        <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"><a href="recover-password.php" class="card-link">Forgot Password?</a></div>
                      </div>
                      <button type="submit" name="register" class="btn btn-outline-info btn-block"><i class="ft-user"></i> Register</button>
                    </form>
                  </div>
                  <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
                    <span>Already have an account?</span>
                  </p>
                  <div class="card-body">
                    <a href="login.php" class="btn btn-outline-danger btn-block"><i class="ft-unlock"></i> Login</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
<?php
  include ("includes/templates/js-login.php");
?>
</body>
</html>

<script>
$(document).ready(function(){
    <?php
        if (isset($alert)) {
    ?>
        swal({
		    title: "<?= $alert[0]; ?>",
		    text: "<?= $alert[1]; ?>",
		    icon: "<?= $alert[2]; ?>",
		    buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		})
		.then((isConfirm) => {
		    if (isConfirm) {
                window.location.href = "<?= $alert[3]; ?>";
		    } else {
                window.location.href = "<?= $alert[3]; ?>";
		    }
		});
    <?php
        }
    ?>
});
</script>

<?php
  mysqli_close($conn);
  ob_end_flush();
?>