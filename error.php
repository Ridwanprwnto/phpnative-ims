<?php

  require 'includes/config/timezone.php';
  require 'includes/function/func.php';

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<?php
  include ("includes/templates/meta.php");
?>
<title>Error - Inventory Information System</title>
<?php
  include ("includes/templates/css-error.php");
?>
</head>
<body class="vertical-layout vertical-menu-modern 1-column   menu-expanded blank-page blank-page"
data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <section class="flexbox-container">
          <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-10 p-0">
            <?php
              if(isset($_GET["alert"])) {
                  $url  = $_GET["alert"];
                  if($_GET["alert"] === $url) {
                    $strplus_id = rplplus($url);
                    $decid = decrypt($strplus_id);
                    if($decid == TRUE) {
                      if($decid === "no-get-data") { 
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">403</h2>
                        <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                      </div>
                      <?php
                      }
                      elseif($decid === "no-data") { 
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">403</h2>
                        <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                      </div>
                      <?php
                      }
                      elseif($decid === "no-data-user") { 
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">403</h2>
                        <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                      </div>
                      <?php   
                      }
                      elseif($decid === "connection-timeout") { 
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">500</h2>
                        <h3 class="text-uppercase text-center">Internal Server Error</h3>
                      </div>
                      <div class="card-content ">
                        <div class="row py-2">
                          <div class="col-12">
                            <a href="index.php" class="btn btn-primary btn-block"><i class="ft-refresh-cw"></i> Refresh</a>
                          </div>
                        </div>
                      </div>
                      <?php   
                      }
                      elseif($decid === "datanotfound") { 
                        ?>
                        <div class="card-header bg-transparent border-0">
                          <h2 class="error-code text-center mb-2">404</h2>
                          <h3 class="text-uppercase text-center">Data Not Found</h3>
                        </div>
                        <?php
                      }
                      elseif($decid === "print-error") { 
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">403</h2>
                        <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                      </div>
                      <?php
                      }
                      else {
                        ?>
                        <div class="card-header bg-transparent border-0">
                          <h2 class="error-code text-center mb-2">404</h2>
                          <h3 class="text-uppercase text-center"><?= $decid; ?></h3>
                        </div>
                        <div class="card-content ">
                          <div class="row py-2">
                            <div class="col-12">
                              <a href="index.php" class="btn btn-primary btn-block"><i class="ft-refresh-cw"></i> Refresh</a>
                            </div>
                          </div>
                        </div>
                        <?php
                      }
                    }
                    else {
                      ?>
                      <div class="card-header bg-transparent border-0">
                        <h2 class="error-code text-center mb-2">403</h2>
                        <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                      </div>
                      <?php
                    }
                  }   
                }
                else { 
                ?>
                  <div class="card-header bg-transparent border-0">
                    <h2 class="error-code text-center mb-2">403</h2>
                    <h3 class="text-uppercase text-center">Access Denied / Forbidden !</h3>
                  </div>
                <?php 
                }
              ?>
              <div class="card-footer bg-transparent">
                <div class="row">
                  <p class="text-muted text-center col-12 py-1">© 2019 <a href="#">Technikal Support Bogor 1 </a>Crafted with <i class="ft-heart pink"> </i>                    by <a href="#">Pur</a></p>
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
    include ("includes/templates/js-error.php");
  ?>
</body>
</html>