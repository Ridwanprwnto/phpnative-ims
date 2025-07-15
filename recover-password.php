<?php
ob_start();
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php'; 

require 'includes/config/timezone.php';
require 'includes/function/func.php';
require 'includes/config/conn.php';

$page = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/recover-password.php";

if(isset($_POST["recoverpass"])) {

    $nik = mysqli_real_escape_string($conn, $_POST["user-nik"]);
    $result = mysqli_query($conn, "SELECT nik, email, full_name, id_office FROM users WHERE nik = '$nik'");
    
    if($data_users = mysqli_fetch_assoc($result)) {

      $emailto = $data_users["email"];
      $name = ucwords(strtolower($data_users["full_name"]));
      $office = $data_users["id_office"];
      $tgl = date("Y-m-d H:i:s");
      
      if (!filter_var($emailto, FILTER_VALIDATE_EMAIL)) {
        $alert = array("Gagal!", $emailto." Invalid email format", "error", "$page");
      }

      $sql_email = "SELECT * FROM email_server";
      $query_email = mysqli_query($conn, $sql_email);

      if($query_email) {
        $data_email = mysqli_fetch_assoc($query_email);

        $strplus = rplplus($data_email["password"]);
        $pass = decrypt($strplus);
        $app_pwd = $data_email["app_password"];
        $code = uniqid(true);
        $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/reset-password.php?code=$code";
        
        $mail = new PHPMailer(true);

        try {
          // Konfigurasi server
          $mail->isSMTP();
          // $mail->CharSet = "utf-8";//
          // $mail->SMTPDebug = 2;
          $mail->Host = $data_email['host'];
          $mail->SMTPAuth = true;
          $mail->Username = $data_email['email'];
          $mail->Password = $app_pwd;
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = $data_email['port'];

          // Penerima
          $mail->setFrom($data_email['email'], 'IMS - Reset Password');
          $mail->addAddress($emailto, $name);

          // Konten email
          $mail->isHTML(true);
          $mail->Subject = "Hi ".$name;
          $mail->Body = "
            Hi ".$name.", 
            <h4>Please click here to reset your password:</h4>
            <h3><a href='$url'>Click here</a></h3>
            <p><i>If you have not triggered this password reset yourself or if you do not wish to change your password, please ignore this mail.</i>.</p>
            <p>This link will expire in 30 minutes.</p>
            </br>
            <p>Kind regards,</p>
            <p>IMS Developer by Purwanto Ridwan</p>";
          $mail->AltBody = 'Hi, You requested to reset your password. Click the link to reset: '.$url;

          //Recipients
          $mail->addReplyTo($data_email['email'], 'no-reply');
          
          if (!$mail->send()) {
            $alert = array("Gagal!", "Mailer Error: {$mail->ErrorInfo}", "$page");
          }
          else {
            $alert = array("Success!", "Link recovery password berhasil kikirim Ke email yang terdaftar atas NIK ".$nik, "success", "$page");
          }
        } catch (Exception $e) {
          $alert = array("Gagal!", "Message could not be sent. Mailer Error: {$mail->ErrorInfo}", "error", "$page");
        }

        $sql_reset = "SELECT * FROM reset_pass WHERE nik_reset = '$nik' AND status_reset = 'N'";
        $query_reset = mysqli_query($conn, $sql_reset);

        if(mysqli_num_rows($query_reset) > 0) {
          mysqli_query($conn, "UPDATE reset_pass SET code_reset = '$code', tgl_reset = '$tgl', url_reset = '$url' WHERE nik_reset = '$nik'");
        }
        else {
          mysqli_query($conn, "INSERT INTO reset_pass (office_reset, tgl_reset, nik_reset, name_reset, email_reset, code_reset, url_reset, status_reset) VALUES ('$office', '$tgl', '$nik', '$name', '$emailto', '$code', '$url', 'N') ");
        }
        
      }
      else {
        $alert = array("Gagal!", "Email Server Not Found", "error", "$page");
      }
    }
    else {
        $alert = array("Gagal!", "NIK Tidak Ditemukan", "error", "$page");
    }
}

?>


<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<?php
    include ("includes/templates/meta.php");
  ?>
<title>Recovery Password - Inventory Management System</title>
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
                    <span>We will send you a link to reset password.</span>
                  </h6>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <form action="" method="POST">
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control form-control-lg input-lg" id="user-nik" name="user-nik"
                        placeholder="Enter Your Nik" required>
                        <div class="form-control-position">
                          <i class="ft-user"></i>
                        </div>
                      </fieldset>
                      <button type="submit" name="recoverpass" class="btn btn-outline-info btn-lg btn-block"><i class="ft-unlock"></i> Recover Password</button>
                    </form>
                  </div>
                </div>
                <div class="card-footer border-0">
                  <p class="float-sm-left text-center"><a href="login.php" class="card-link">Login</a></p>
                  <p class="float-sm-right text-center">Don't have an account ? <a href="register.php" class="card-link">Create Account</a></p>
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

