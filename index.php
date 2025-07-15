<?php
  ob_start();
  
  require 'includes/config/sessecure.php';
  
  if (session_status()!==PHP_SESSION_ACTIVE)session_start();
  
  require 'includes/config/timezone.php';
  require 'includes/function/func.php';
  require 'includes/function/tag.php';
  include 'includes/config/conn.php';
  
  if(!isset($_SESSION['user_nik'])) {
    header("location: login.php");
    exit();
  }

  $nik = $_SESSION["user_nik"];
  $id_group = $_SESSION["group"];
  $username = $_SESSION["user_name"];

  $sql_idx = "SELECT id_office, foto, nik, username FROM users WHERE nik = '$nik'";
  $query_idx = mysqli_query($conn, $sql_idx);
  $data_idx = mysqli_fetch_assoc($query_idx);

  $admin = $arrgroup[0];
  $support = $arrgroup[1];
  $cctv = $arrgroup[7];
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- Header -->
<head>
<?php
  include ("includes/templates/meta.php");
?>
<title>Home - Inventory Management System</title>
<?php
  include ("includes/templates/css-index.php");
?>
</head>
<!-- End Header -->

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  <!-- fixed-top-->
  <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="index.php">
              <img class="brand-logo" alt="IMS Logo" src="app-assets/images/logo/logo.png">
              <h1 class="brand-text">IMS - <?= $data_idx["id_office"]; ?></h1>
            </a>
          </li>
          <li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
          <li class="nav-item d-md-none">
            <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
          </li>
        </ul>
      </div>
      <div class="navbar-container content">
        <div class="collapse navbar-collapse" id="navbar-mobile">
          <ul class="nav navbar-nav mr-auto float-left">
            <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
            <form action="index.php?page=<?= encrypt("searchmenu"); ?>" method="post">
            <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
              <div class="search-input">
                <input class="input" type="text" name="searchmenu" placeholder="Search Menu...">
                <input class="input" type="hidden" name="groupmenu" value="<?= $id_group; ?>">
              </div>
            </li>
            </form>
          </ul>
          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-user nav-item">
              <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                <span class="mr-1">Welcome, <?= "(".$data_idx["nik"].")"; ?>
                  <span class="user-name text-bold-700"><?= strtoupper($data_idx["username"]); ?></span>
                </span>
                <span class="avatar avatar-online">
                  <img src="<?php if($data_idx['foto'] != NULL || $data_idx['foto'] != '') { ?> files/img/<?= $data_idx['foto']; } else { ?> <?php echo 'files/img/user.png'; } ?>" alt="avatar"><i></i>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="index.php?page=<?= encrypt("profile"); ?>"><i class="ft-user"></i> Profile</a>
                <a class="dropdown-item" href="index.php?page=<?= encrypt("username"); ?>"><i class="ft-edit-2"></i> Change Username</a>
                <a class="dropdown-item" href="index.php?page=<?= encrypt("password"); ?>"><i class="ft-unlock"></i> Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logout"><i class="ft-log-out"></i> Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">   
        <!-- main menu content -->
        <?php
          include ("includes/config/menunavbar.php");
        ?>
        <!-- -->
      </ul>
    </div>
  </div>
  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new"> 
          <!-- Menu Subject -->      
          <?php
            include ("includes/config/menuheader.php");
          ?>
          <!--  -->
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
              <button class="btn btn-secondary round btn-glow px-2" id="sesstimer" type="button">00 : 60 : 00</button>
            </div>
        </div>
      </div>
      <div class="content-body">
      <!-- Content Body -->
        <?php
          include ("includes/config/routing.php");
        ?>
      <!--  -->
      </div>
      <!-- Modal Logout -->
      <div class="modal fade text-left" id="logout" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h4 class="modal-title white">Logout Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Are you sure to logout account?</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">No</button>
                    <a href="index.php?page=<?= encrypt("logout"); ?>" class="btn btn-outline-primary">Yes</a>
                </div>
            </div>
          </div>
      </div>
      <!-- End Modal -->
    </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <!-- Content Footer -->
  <?php
      include ("includes/templates/footer.php");
      include ("includes/templates/js-index.php");
  ?>
  <!--  -->
</body>
</html>

<script>
$(document).ready(function(){

  function check_session(){
    // var id = "<?= $nik; ?>";
    // var data = "SESSIONLOG=" + id;
    // $.ajax({
    //   type: 'POST',
    //   url: 'action/datarequest.php',
    //   data: data,
    //   success: function(htmlresponse){
    //     if(htmlresponse == "0"){
          
    //     }
    //   }
    // });
    swal({
      title: "Duration Ends",
      text: "Sesi telah berakhir, login kembali untuk melanjutkan.",
      icon: "warning",
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
          window.location.href = "logout.php";
        } else {
          window.location.href = "logout.php";
        }
    });
  }

  function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
      minutes = parseInt(timer / 60, 10)
      seconds = parseInt(timer % 60, 10);
      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;
      display.text("00 : " + minutes + " : " + seconds);

      if (--timer < 0) {
        check_session();
      }
    }, 1000);
  }

  jQuery(function ($) {
      var examtime = 60 * 60, display = $('#sesstimer');
      startTimer(examtime, display);
  });

});

</script>

<?php
  mysqli_close($conn);
  ob_end_flush();
?>