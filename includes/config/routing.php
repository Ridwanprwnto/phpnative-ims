<?php
  if(isset($_GET["page"]) && isset($_GET["ext"])){

    $page  = $_GET["page"];
    $strplus_id = rplplus($page);
    $decid = mysqli_real_escape_string($conn, decrypt($strplus_page));

    $id  = $_GET["ext"];
    $strplus_act = rplplus($id);
    $decact = mysqli_real_escape_string($conn, decrypt($strplus_act));

    $query_emp = mysqli_query($conn, "SELECT extendmenu.*, parentmenu.parentmenu_akses, akses_parentmenu.id_group, akses_parentmenu.parentmenu_status FROM extendmenu 
    INNER JOIN parentmenu ON extendmenu.id_ref_menu = parentmenu.id_parentmenu 
    INNER JOIN akses_parentmenu ON extendmenu.id_ref_menu = akses_parentmenu.id_parentmenu 
    WHERE extendmenu.id_ref_menu = '$decid' AND extendmenu.kode_extend_menu = '$decact' AND akses_parentmenu.id_group = '$id_group' AND akses_parentmenu.parentmenu_status = 'Y'");

    $query_emc = mysqli_query($conn, "SELECT extendmenu.*, childmenu.childmenu_akses, akses_childmenu.id_group, akses_childmenu.childmenu_status FROM extendmenu 
    INNER JOIN childmenu ON extendmenu.id_ref_menu = childmenu.id_childmenu 
    INNER JOIN akses_childmenu ON extendmenu.id_ref_menu = akses_childmenu.id_childmenu 
    WHERE extendmenu.id_ref_menu = '$decid' AND extendmenu.kode_extend_menu = '$decact' AND akses_childmenu.id_group = '$id_group' AND akses_childmenu.childmenu_status = 'Y'");

    $query_emg = mysqli_query($conn, "SELECT extendmenu.*, grandchildmenu.grandchildmenu_akses, akses_grandchildmenu.id_group, akses_grandchildmenu.grandchildmenu_status FROM extendmenu 
    INNER JOIN grandchildmenu ON extendmenu.id_ref_menu = grandchildmenu.id_grandchildmenu 
    INNER JOIN akses_grandchildmenu ON extendmenu.id_ref_menu = akses_grandchildmenu.id_grandchildmenu 
    WHERE extendmenu.id_ref_menu = '$decid' AND extendmenu.kode_extend_menu = '$decact' AND akses_grandchildmenu.id_group = '$id_group' AND akses_grandchildmenu.grandchildmenu_status = 'Y'");

    if ($_GET["page"] == $page && $_GET["ext"] == $id) {
      if($decpage == true && $decact == true) {
        if ($id_group == $admin) {
          if ($data_emp = mysqli_fetch_assoc($query_emp)) {
            if($data_emp["id_ref_menu"] === $decpage && $data_emp["kode_extend_menu"] === $decact) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emp["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emp["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-403.php");
            }
          }
          elseif ($data_emc = mysqli_fetch_assoc($query_emc)) {
            if($data_emc["id_ref_menu"] === $decpage && $data_emc["kode_extend_menu"] === $decact) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emc["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emc["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-403.php");
            }
          }
          elseif ($data_emg = mysqli_fetch_assoc($query_emg)) {
            if($data_emg["id_ref_menu"] === $decpage && $data_emg["kode_extend_menu"] === $decact) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emg["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emg["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-403.php");
            }
          }
          else {
              include ("includes/templates/error-403.php");
          }
        }
        else {
          if ($data_emp = mysqli_fetch_assoc($query_emp)) {
            if($data_emp["id_ref_menu"] === $decpage && $data_emp["kode_extend_menu"] === $decact && $data_emg["parentmenu_akses"] == 1) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emp["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emp["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-503.php");
            }
          }
          elseif ($data_emc = mysqli_fetch_assoc($query_emc)) {
            if($data_emc["id_ref_menu"] === $decpage && $data_emc["kode_extend_menu"] === $decact && $data_emc["childmenu_akses"] == 1) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emc["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emc["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-503.php");
            }
          }
          elseif ($data_emg = mysqli_fetch_assoc($query_emg)) {
            if($data_emg["id_ref_menu"] === $decpage && $data_emg["kode_extend_menu"] === $decact && $data_emg["grandchildmenu_akses"] == 1) {
              if(file_exists("includes/modul/general/em/".strtolower($data_emg["kode_extend_menu"]).".php")) {
                include ("includes/modul/general/em/".strtolower($data_emg["kode_extend_menu"]).".php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            else {
                include ("includes/templates/error-503.php");
            }
          }
          else {
              include ("includes/templates/error-403.php");
          }
        }
      }
      else {
          include ("includes/templates/error-404.php");
      }
    }
  }
  else {
    if(isset($_GET["page"])) {

      $page  = $_GET["page"];
      $strplus_id = rplplus($page);
      $decid = decrypt($strplus_page);

      $query_pm = mysqli_query($conn, "SELECT parentmenu.parentmenu_akses, akses_parentmenu.id_parentmenu FROM parentmenu 
      INNER JOIN akses_parentmenu ON parentmenu.id_parentmenu = akses_parentmenu.id_parentmenu 
      WHERE akses_parentmenu.id_group = '$id_group' AND akses_parentmenu.id_parentmenu = '$decid' AND akses_parentmenu.parentmenu_status = 'Y'");
      
      $query_cm = mysqli_query($conn, "SELECT childmenu.childmenu_akses, akses_childmenu.id_childmenu FROM childmenu
      INNER JOIN akses_childmenu ON childmenu.id_childmenu = akses_childmenu.id_childmenu 
      WHERE akses_childmenu.id_group = '$id_group' AND akses_childmenu.id_childmenu = '$decid' AND akses_childmenu.childmenu_status = 'Y'");
      
      $query_gm = mysqli_query($conn, "SELECT grandchildmenu.grandchildmenu_akses, akses_grandchildmenu.id_grandchildmenu FROM grandchildmenu 
      INNER JOIN akses_grandchildmenu ON grandchildmenu.id_grandchildmenu = akses_grandchildmenu.id_grandchildmenu 
      WHERE akses_grandchildmenu.id_group = '$id_group' AND akses_grandchildmenu.id_grandchildmenu = '$decid' AND akses_grandchildmenu.grandchildmenu_status = 'Y'");

      if($_GET["page"] === $page) {
        if($decid == true) {
          if ($id_group == $admin) {
            if($decid === "profile") {
              if(file_exists("includes/modul/admin/profile.php")) {
                include ("includes/modul/admin/profile.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "username") {
              if(file_exists("includes/modul/admin/username.php")) {
                include ("includes/modul/admin/username.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "password") {
              if(file_exists("includes/modul/admin/password.php")) {
                include ("includes/modul/admin/password.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "company") {
              if(file_exists("includes/modul/admin/company.php")) {
                include ("includes/modul/admin/company.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "office") {
              if(file_exists("includes/modul/admin/office.php")) {
                include ("includes/modul/admin/office.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "department") {
              if(file_exists("includes/modul/admin/department.php")) {
                include ("includes/modul/admin/department.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "divisi") {
              if(file_exists("includes/modul/admin/divisi.php")) {
                include ("includes/modul/admin/divisi.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "group") {
              if(file_exists("includes/modul/admin/group.php")) {
                include ("includes/modul/admin/group.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "level") {
              if(file_exists("includes/modul/admin/level.php")) {
                include ("includes/modul/admin/level.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "parentmenu") {
              if(file_exists("includes/modul/admin/parentmenu.php")) {
                include ("includes/modul/admin/parentmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "childmenu") {
              if(file_exists("includes/modul/admin/childmenu.php")) {
                include ("includes/modul/admin/childmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "grandchildmenu") {
              if(file_exists("includes/modul/admin/grandchildmenu.php")) {
                include ("includes/modul/admin/grandchildmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "extendmenu") {
              if(file_exists("includes/modul/admin/extendmenu.php")) {
                include ("includes/modul/admin/extendmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "accesspm") {
              if(file_exists("includes/modul/admin/accesspm.php")) {
                include ("includes/modul/admin/accesspm.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "accesscm") {
              if(file_exists("includes/modul/admin/accesscm.php")) {
                include ("includes/modul/admin/accesscm.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "accessgm") {
              if(file_exists("includes/modul/admin/accessgm.php")) {
                include ("includes/modul/admin/accessgm.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "emailserver") {
              if(file_exists("includes/modul/admin/emailserver.php")) {
                include ("includes/modul/admin/emailserver.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "category") {
              if(file_exists("includes/modul/admin/category.php")) {
                include ("includes/modul/admin/category.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "satuan") {
              if(file_exists("includes/modul/admin/satuan.php")) {
                include ("includes/modul/admin/satuan.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "kondisi") {
              if(file_exists("includes/modul/admin/kondisi.php")) {
                include ("includes/modul/admin/kondisi.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "spp") {
              if(file_exists("includes/modul/admin/spp.php")) {
                include ("includes/modul/admin/spp.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "crud") {
              if(file_exists("includes/modul/admin/crud.php")) {
                include ("includes/modul/admin/crud.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "statusp3at") {
              if(file_exists("includes/modul/admin/statusp3at.php")) {
                include ("includes/modul/admin/statusp3at.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "searchmenu") {
              if(file_exists("includes/modul/admin/searchmenu.php")) {
                include ("includes/modul/admin/searchmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "mastertelebot") {
              if(file_exists("includes/modul/admin/mastertelebot.php")) {
                include ("includes/modul/admin/mastertelebot.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "apiservices") {
              if(file_exists("includes/modul/admin/apiservices.php")) {
                include ("includes/modul/admin/apiservices.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "simulasi") {
              if(file_exists("includes/modul/admin/simulasi.php")) {
                include ("includes/modul/admin/simulasi.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "logout") {
              header("location: logout.php");
            }
            else {
              // Parent menu akses
              if ($data_pm = mysqli_fetch_assoc($query_pm)) {
                $id_pm = $data_pm["id_parentmenu"];
                $id_akses_pm = $data_pm["parentmenu_akses"];
                if($decid === $id_pm) {
                  if(file_exists("includes/modul/general/pm/".strtolower($id_pm).".php")) {
                    include ("includes/modul/general/pm/".strtolower($id_pm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-403.php");
                }
              }
              elseif($data_cm = mysqli_fetch_assoc($query_cm)) {
                // Child menu akses
                $id_cm = $data_cm["id_childmenu"];
                $id_akses_cm = $data_cm["childmenu_akses"];
                if($decid === $id_cm) {
                  if(file_exists("includes/modul/general/cm/".strtolower($id_cm).".php")) {
                    include ("includes/modul/general/cm/".strtolower($id_cm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-403.php");
                }
              }
              // Grandchild menu akses
              elseif ($data_gm = mysqli_fetch_assoc($query_gm)) {
                $id_gm = $data_gm["id_grandchildmenu"];
                $id_akses_gm = $data_gm["grandchildmenu_akses"];
                if($decid === $id_gm) {
                  if(file_exists("includes/modul/general/gm/".strtolower($id_gm).".php")) {
                    include ("includes/modul/general/gm/".strtolower($id_gm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-403.php");
                }
              }
              else {
                  include ("includes/templates/error-403.php");
              }
            }
          }
          else {
            if($decid === "profile") {
              if(file_exists("includes/modul/admin/profile.php")) {
                include ("includes/modul/admin/profile.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "username") {
              if(file_exists("includes/modul/admin/username.php")) {
                include ("includes/modul/admin/username.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "password") {
              if(file_exists("includes/modul/admin/password.php")) {
                include ("includes/modul/admin/password.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "searchmenu") {
              if(file_exists("includes/modul/admin/searchmenu.php")) {
                include ("includes/modul/admin/searchmenu.php");
              }
              else {
                  include ("includes/templates/error-404.php");
              }
            }
            elseif($decid === "logout") {
              header("location: logout.php");
            }
            else {
              // Parent menu akses
              if($data_pm = mysqli_fetch_assoc($query_pm)) {
                $id_pm = $data_pm["id_parentmenu"];
                $id_akses_pm = $data_pm["parentmenu_akses"];
                if($decid === $id_pm  && $id_akses_pm == 1) {
                  if(file_exists("includes/modul/general/pm/".strtolower($id_pm).".php")) {
                    include ("includes/modul/general/pm/".strtolower($id_pm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-503.php");
                }
              }
              // Child menu akses
              elseif($data_cm = mysqli_fetch_assoc($query_cm)) {
                $id_cm = $data_cm["id_childmenu"];
                $id_akses_cm = $data_cm["childmenu_akses"];
                if($decid === $id_cm && $id_akses_cm == 1) {
                  if(file_exists("includes/modul/general/cm/".strtolower($id_cm).".php")) {
                    include ("includes/modul/general/cm/".strtolower($id_cm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-503.php");
                }
              }
              // Grandchild menu akses
              elseif ($data_gm = mysqli_fetch_assoc($query_gm)) {
                $id_gm = $data_gm["id_grandchildmenu"];
                $id_akses_gm = $data_gm["grandchildmenu_akses"];
                if($decid === $id_gm && $id_akses_gm == 1) {
                  if(file_exists("includes/modul/general/gm/".strtolower($id_gm).".php")) {
                    include ("includes/modul/general/gm/".strtolower($id_gm).".php");
                  }
                  else {
                      include ("includes/templates/error-404.php");
                  }
                }
                else {
                    include ("includes/templates/error-503.php");
                }
              }
              else {
                  include ("includes/templates/error-403.php");
              }
            }
          }
        }
        else {
          include ("includes/templates/error-404.php");
        }
      }
    }
    else {
      if(file_exists("includes/modul/admin/dashboard.php")) {
          include ("includes/modul/admin/dashboard.php");
      }
      else {
          include ("includes/templates/error-404.php");
      }
    }
  }

?>