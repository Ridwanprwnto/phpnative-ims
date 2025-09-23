<?php

ob_start();
if (session_status()!==PHP_SESSION_ACTIVE)session_start();

require 'includes/config/timezone.php';
require 'includes/function/func.php';
require 'includes/config/conn.php';

$_SESSION['USERMANUAL'] = $_POST;

$page = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/master-app";

if(isset($_POST["updateversiapplication"])){
    if(UpdateVersiApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-upd-verapp"]) ? $_POST["name-upd-verapp"] : NULL;
        $datapost2 = isset($_POST["code-upd-verapp"]) ? $_POST["code-upd-verapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Versi ".$datapost2." Berhasil Dirubah", "success", "$page");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deleteversiapplication"])){
    if(DeleteVersiApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-del-verapp"]) ? $_POST["name-del-verapp"] : NULL;
        $datapost2 = isset($_POST["code-del-verapp"]) ? $_POST["code-del-verapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Versi ".$datapost2." Berhasil Dihapus", "success", "$page");
    }
    else {
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
<title>Master Application - Inventory Information System</title>
<?php
    include ("includes/templates/css-index.php");
?>
</head>

<body class="vertical-layout vertical-menu-modern 1-column bg-lighten-2 menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
 <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item">
                        <a class="navbar-brand" href="<?= $page; ?>">
                        <img class="brand-logo" alt="IMS Logo" src="app-assets/images/logo/logo.png">
                        <h2 class="brand-text">Inventory Management System <?= isset($row["office_aprv_presensi"]) ? "- ".$row["office_aprv_presensi"] : NULL; ?></h2>
                        </a>
                    </li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container">
                <div class="collapse navbar-collapse justify-content-end" id="navbar-mobile">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link mr-2 nav-link-label" href="index.php"><i class="ficon ft-arrow-left"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="box-shadow-2">
                    <div class="card border-grey border-lighten-3 px-2 my-0 row">
                        <div class="card-content px-2">
                            <div class="form-header no-border pb-1">
                                <div class="card-body">
                                    <h2 class="text-uppercase text-center">Management Application</h2>
                                </div>
                            </div>
                            <div class="form-body">
                                <h4 class="form-section">List Master Application</h4>
                                <div class="row">
                                    <table class="table display nowrap table-striped table-bordered text-center" id="table_apps">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>OFFICE</th>
                                                <th>ID</th>
                                                <th>BASE</th>
                                                <th>APPLICATION NAME</th>
                                                <th>RELEASE</th>
                                                <th>VERSION</th>
                                                <th>INFO FEATURE</th>
                                                <th>SOURCE</th>
                                                <th>USER MANUAL</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $nov = 1;
                                            $sql_ver = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, C.office_shortname, D.department_initial FROM version_app AS A
                                            INNER JOIN master_app AS B ON A.id_code_app = B.code_app
                                            INNER JOIN office AS C ON B.office_app = C.id_office 
                                            INNER JOIN department AS D ON B.dept_app = D.id_department ORDER BY B.name_app ASC";
                                            $query_ver = mysqli_query($conn, $sql_ver);
                                            while($data_ver = mysqli_fetch_assoc($query_ver)) {
                                        ?>
                                            <tr>
                                                <td><?= $nov++; ?></td>
                                                <td><?= $data_ver['office_app']." - ".$data_ver['department_initial']." ".$data_ver['office_shortname']; ?></td>
                                                <td><?= $data_ver['code_app']; ?></td>
                                                <td><?= $data_ver['basis_app']; ?></td>
                                                <td><?= $data_ver['name_app']; ?></td>
                                                <td><?= $data_ver['rilis_ver_app']; ?></td>
                                                <td><?= $data_ver['version_ver_app']; ?></td>
                                                <td><?= $data_ver['fitur_ver_app'] == "" ? "-" : $data_ver['fitur_ver_app']; ?></td>
                                                <?php
                                                    if ($data_ver['basis_app'] == "WEB") {
                                                ?>
                                                <td>
                                                    <a title="Link Website Aplikasi " href="<?= $data_ver["source_ver_app"]; ?>" target="_blank" class="btn btn-float btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-external-link"></i>
                                                        <span>Link</span>
                                                    </a>
                                                </td>
                                                <?php
                                                    }
                                                    else { 
                                                ?>
                                                <td>
                                                    <a title="Source Master Aplikasi " href="files/source/index.php?master=<?= encrypt($data_ver['source_ver_app']);?>" target="_blank" class="btn btn-float btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-file"></i>
                                                        <span>File</span>
                                                    </a>
                                                </td>
                                                <?php
                                                    }
                                                ?>
                                                <?php
                                                    if ($data_ver['manual_ver_app'] === "") {
                                                ?>
                                                <td>-</td>
                                                <?php
                                                    }
                                                    else { 
                                                ?>
                                                <td>
                                                    <a title="User Manual Aplikasi" onclick="document.location.href='<?= $page;?>'" target="_blank" data-toggle="tooltip" data-placement="bottom" href="<?= $data_ver['manual_ver_app'] !== "" ? "files/manual/index.php?id=".encrypt($data_ver['manual_ver_app']) : '#'; ?>" class="<?= $data_ver['manual_ver_app'] !== "" ? 'btn btn-float btn-warning' : ''; ?>"><i class="<?= $data_ver['manual_ver_app'] !== "" ? 'ft-file-text' : ''; ?>"></i>
                                                        <?= $data_ver['manual_ver_app'] !== "" ? '<span>File</span>' : ''; ?>
                                                    </a>
                                                </td>
                                                <?php
                                                    }
                                                ?>
                                                <td>
                                                    <!-- Icon Button dropdowns -->
                                                    <div class="btn-group mb-1">
                                                        <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item update_versi_app" href="#" title="Edit Data Master Aplikasi <?= $data_ver['name_app']; ?> Versi <?= $data_ver['version_ver_app']; ?>" name="update_versi_app" id="<?= $data_ver["id_ver_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Data</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item delete_versi_app" href="#" title="Hapus Data Master Aplikasi <?= $data_ver['name_app']; ?> Versi <?= $data_ver['version_ver_app']; ?>" name="delete_versi_app" id="<?= $data_ver["id_ver_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Data</a>
                                                        </div>
                                                    </div>
                                                    <!-- /btn-group -->
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalVersionApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="label-upd-verapp"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-upd-verapp" value="<?= $page; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="user-upd-verapp" value="" class="form-control" readonly>
                                                            <input type="hidden" name="id-upd-verapp" id="id-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="code-upd-verapp" id="code-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="apl-upd-verapp" id="apl-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="mnl-upd-verapp" id="mnl-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="infohide-upd-verapp" id="infohide-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="usehide-upd-verapp" id="usehide-upd-verapp" class="form-control" readonly>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Nama Aplikasi : </label>
                                                                <input type="text" name="name-upd-verapp" id="name-upd-verapp" class="form-control" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Versi : </label>
                                                                <input type="text" name="versi-upd-verapp" id="versi-upd-verapp" placeholder="Input Versi Aplikasi" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tanggal Rilis : </label>
                                                                <input type="date" name="rilis-upd-verapp" id="rilis-upd-verapp" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Fitur Update :</label>
                                                                <textarea type="text" class="form-control" name="fitur-upd-verapp" id="fitur-upd-verapp" placeholder="Input Update Fitur (Optional)"></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2" id="elmweb-updversi-app" style="display:none;">
                                                                <label>Website Aplikasi : </label>
                                                                <input type="text" name="web-upd-verapp" id="web-upd-verapp" placeholder="Input Alamat Website Aplikasi" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2" id="elmnonweb-updversi-app" style="display:none;">
                                                                <label>Master Aplikasi (zip file) : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="nonweb-insversi-app" id="nonweb-insversi-app">
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>User Manual (pdf or pptx file) : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="manual-insversi-app">
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updateversiapplication" class="btn btn-outline-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalVersionApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page-del-verapp" value="<?= $page; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-del-verapp" name="id-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="code-del-verapp" name="code-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="name-del-verapp" name="name-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="source-del-verapp" name="source-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="manual-del-verapp" name="manual-del-verapp" class="form-control" readonly>
                                                    <label id="label-del-verapp"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deleteversiapplication" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer no-border pb-1 mt-2">
                            <div class="text-center">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
<?php
    include ("includes/templates/footer.php");
    include ("includes/templates/js-index.php");
?>
</body>
</html>

<script>
$(document).ready(function(){
    $('#table_apps').DataTable({
        info: true,
        searching: true,
        ordering: true,
        paging: false,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        scrollY: '50vh'
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_versi_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONVERSIAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-upd-verapp').val(data.id_ver_app);
                $('#code-upd-verapp').val(data.id_code_app);
                $('#name-upd-verapp').val(data.name_app);
                $('#apl-upd-verapp').val(data.source_ver_app);
                $('#mnl-upd-verapp').val(data.manual_ver_app);
                $('#rilis-upd-verapp').val(data.rilis_ver_app);
                $('#versi-upd-verapp').val(data.version_ver_app);
                $('#fitur-upd-verapp').val(data.fitur_ver_app);
                $('#manual-upd-verapp').val(data.manual_ver_app);
                $('#usehide-upd-verapp').val(data.use_ver_app);
                $('#infohide-upd-verapp').val(data.info_ver_app);

                if (data.basis_app == "WEB") {
                    $("#elmnonweb-updversi-app").hide();
                    $('#web-upd-verapp').val(data.source_ver_app);

                    $("#web-upd-verapp").removeAttr('disabled');
                    $("#web-upd-verapp").prop('required', data.basis_app);
                    
                    $("#nonweb-insversi-app").prop('disabled', data.basis_app);

                    $("#elmweb-updversi-app").show();
                }
                else if (data.basis_app == "DESKTOP" || data.basis_app == "MOBILE") {
                    $("#elmweb-updversi-app").hide();
                    
                    $("#web-upd-verapp").removeAttr('required');
                    $("#web-upd-verapp").prop('disabled', data.basis_app);
                    
                    $("#nonweb-insversi-app").removeAttr('disabled');

                    $("#elmnonweb-updversi-app").show();
                }

                $('#label-upd-verapp').html("Update Master Aplikasi "+data.name_app+" Versi "+data.version_ver_app);
                $('#updateModalVersionApp').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_versi_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONVERSIAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-del-verapp').val(data.id_ver_app);
                $('#code-del-verapp').val(data.id_code_app);
                $('#name-del-verapp').val(data.name_app);
                $('#versi-del-verapp').val(data.version_ver_app);
                $('#source-del-verapp').val(data.source_ver_app);
                $('#manual-del-verapp').val(data.manual_ver_app);
                
                $('#label-del-verapp').html("Delete Master Aplikasi "+data.name_app+" Versi "+data.version_ver_app);
                $('#deleteModalVersionApp').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>

<?php
  mysqli_close($conn);
  ob_end_flush();
?>