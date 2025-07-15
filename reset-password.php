<?php

ob_start();
if (session_status()!==PHP_SESSION_ACTIVE)session_start();

require 'includes/config/timezone.php';
require 'includes/function/func.php';
require 'includes/config/conn.php';

if(!isset($_GET["code"])) {
    $v = encrypt("no-get-data");
    header("location: error.php?alert=$v");
    exit();
}

$code = mysqli_real_escape_string($conn, $_GET["code"]);
$getnik = mysqli_query($conn, "SELECT id_reset_pass, nik_reset FROM reset_pass WHERE code_reset = '$code' AND status_reset = 'N'");

if(mysqli_num_rows($getnik) == 0) {
    $v = encrypt("no-data");
    header("location: error.php?alert=$v");
    exit();
}

$page = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/reset-password.php?code=".$code;
$row = mysqli_fetch_assoc($getnik);

if(isset($_POST["update-pass"])) {
    $pass = mysqli_real_escape_string($conn, $_POST["new-password"]);
    $repass = mysqli_real_escape_string($conn, $_POST["re-password"]);
    if(strlen($pass) < 6) {
        $alert = array("Gagal!", "Password Minimal 6 Karakter", "error", "$page");
    }
    else {
        // Validasi password
        if( $pass !== $repass ) {
            $alert = array("Gagal!", "Password Tidak Sama", "error", "$page");
        }
        else {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $id = $row["id_reset_pass"];
            $nik = $row["nik_reset"];
            $query = mysqli_query($conn, "SELECT nik FROM users WHERE nik = '$nik'");
            if($query) {
                mysqli_query($conn, "UPDATE users SET password = '$pass' WHERE nik = '$nik'");
                mysqli_query($conn, "UPDATE reset_pass SET status_reset = 'Y' WHERE id_reset_pass = '$id'");
                $_SESSION["ALERTREPASS"] = $_POST;
                header("location: login.php");
                exit();
            }
            else {
                $v = encrypt("no-data-user");
                header("location: error.php?alert=$v");
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<?php
    include ("includes/templates/meta.php");
  ?>
<title>Reset Password - Inventory Information System</title>
  <?php
    include ("includes/templates/css-recoverpass.php");
  ?>
</head>
<body class="vertical-layout vertical-menu-modern 1-column bg-full-screen-image menu-expanded blank-page blank-page"
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
              <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                <div class="card-header border-0 pb-0">
                  <div class="card-title text-center">
                    <img src="app-assets/images/logo/login_logo.png" alt="branding logo">
                    <h2><strong>INVENTORY MANAGEMENT SYSTEM</strong></h2>
                  </div>
                  <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                    <span>Input a new password for updated.</span>
                  </h6>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <form action="" method="post">
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control form-control-lg input-lg" value="NIK : <?= $row["nik_reset"]; ?>" readonly>
                        <div class="form-control-position">
                          <i class="ft-user"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" id="new-password" name="new-password"
                        placeholder="Password" required>
                        <div class="form-control-position">
                          <i class="la la-key"></i>
                        </div>
                      </fieldset>
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" id="re-password" name="re-password"
                        placeholder="Re Password" required>
                        <div class="form-control-position">
                          <i class="la la-key"></i>
                        </div>
                      </fieldset>
                      <button type="submit" name="update-pass" class="btn btn-outline-info btn-lg btn-block"><i class="ft-unlock"></i> Update Password</button>
                    </form>
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
      include ("includes/templates/js-recoverpass.php");
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