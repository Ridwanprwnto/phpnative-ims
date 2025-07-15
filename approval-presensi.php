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
else {
    $code = mysqli_real_escape_string($conn, $_GET["code"]);
    $decode = decrypt(rplplus($code));

    if($decode == FALSE) {
        $v = encrypt("no-get-data");
        header("location: error.php?alert=$v");
        exit();
    }
}

$sql = "SELECT A.*, B.*, C.office_name, D.full_name, E.divisi_name FROM data_presensi AS A 
INNER JOIN approval_presensi AS B ON A.no_data_presensi = B.no_aprv_presensi 
INNER JOIN office AS C ON B.office_aprv_presensi = C.id_office 
LEFT JOIN users AS D ON A.nik_data_presensi = D.nik 
LEFT JOIN divisi AS E ON D.id_divisi = E.id_divisi 
WHERE A.no_data_presensi = '$decode' AND B.status_aprv_presensi = 'N'";

$getdata = mysqli_query($conn, $sql);

$page = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/approval-presensi.php?code=".$code;

$row = mysqli_fetch_assoc($getdata);

if(isset($_POST["editdata"])){
    $datapost = isset($_POST["approve-idpresensi"]) ? $_POST["approve-idpresensi"] : NULL;
    if(ApproveEditKehadiran($_POST)){
        $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Approve!", "success", "$page");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    $datapost = isset($_POST["delete-idpresensi"]) ? $_POST["delete-idpresensi"] : NULL;
    if(ApproveDeleteKehadiran($_POST)){
        $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Approve!", "success", "$page");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    $datapost = isset($_POST["update-idpresensi"]) ? $_POST["update-idpresensi"] : NULL;
    if(ApprovePerubahanJadwal($_POST)){
        $alert = array("Success!", "Pengajuan Perubahan Data Jadwal Docno ".$datapost." Berhasil di Approve!", "success", "$page");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["rejectdata"])){
    $datapost = isset($_POST["reject-docnopresensi"]) ? $_POST["reject-docnopresensi"] : NULL;
    if(RejectDataKehadiran($_POST)){
        $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Reject!", "success", "$page");
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
<title>Approval Presensi - Inventory Information System</title>
<?php
    include ("includes/templates/css-error.php");
?>
</head>

<body class="vertical-layout vertical-menu-modern 1-column bg-lighten-2 menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
 <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
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
                <section class="col-sm-5 offset-sm-12 col-md-12 offset-md-12 col-lg-12 offset-lg-12 box-shadow-2">
                    <div class="card border-grey border-lighten-3 px-2 my-0 row">
                        <div class="card-header no-border pb-1">
                            <div class="card-body">
                                <!-- <h6 class="error-code text-center mb-2">APPROVAL PRESENSI</h3> -->
                                <h2 class="text-uppercase text-center">OTP Approval Presensi</h2>
                            </div>
                        </div>
                        <div class="card-content px-2">
                        <?php
                        if(mysqli_num_rows($getdata) > 0) { ?>
                            <form class="form" method="post" action="">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-user"></i> Data Pengajuan</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kantor</label>
                                            <input type="text" value="<?= isset($row["office_aprv_presensi"]) ? $row['office_aprv_presensi']." - ".strtoupper($row['office_name']) : NULL; ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bagian</label>
                                            <input type="text" value="<?= isset($row["divisi_name"]) ? $row['divisi_name'] : NULL; ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Aksi</label>
                                            <input type="text" value="<?=  $row["aksi_aprv_presensi"] == "E" ? "EDIT ABSENSI" : ($row["aksi_aprv_presensi"] == "D" ? "DELETE ABSENSI" : "UPDATE JADWAL"); ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Docno</label>
                                            <input type="text" value="<?= $row['aksi_aprv_presensi'] == "E" ? $row['no_aprv_presensi'] : $row['no_aprv_presensi']; ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="text" value="<?= isset($row["date_aprv_presensi"]) ? $row['date_aprv_presensi'] : NULL; ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User Yang Mengajukan</label>
                                            <input type="text" value="<?= isset($row["user_aprv_presensi"]) ? $row['user_aprv_presensi'] : NULL; ?>" class="form-control" placeholder="-" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea value="<?= isset($row["ket_aprv_presensi"]) ? $row['ket_aprv_presensi'] : NULL; ?>" rows="2" class="form-control" placeholder="-" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="form-section"><i class="ft-file-text"></i> Data Perubahan <?= $row["aksi_aprv_presensi"] == "U" ? "Jadwal" : "Absensi"; ?></h4>
                                <div class="form-group">
                                    <table class="table display nowrap table-striped table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>ID</th>
                                                <th>NIK - NAMA</th>
                                                <th>TANGGAL</th>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] != "U") { ?>
                                                <th>DATA SEBELUM</th>
                                                <?php }
                                                ?>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] == "E") { ?>
                                                <th>PERUBAHAN DATA</th>
                                                <?php }
                                                ?>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] == "U") { ?>
                                                <th>PERUBAHAN JADWAL</th>
                                                <?php }
                                                ?>
                                                <th>KETERANGAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $query_detail = mysqli_query($conn, $sql);

                                            while($data_detail = mysqli_fetch_assoc($query_detail)) {
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><strong><?= $data_detail['ref_data_presensi']; ?></strong></td>
                                                <td><?= $data_detail['nik_data_presensi']." - ".strtoupper($data_detail['full_name']); ?></td>
                                                <td><?= $data_detail['tgl_data_presensi']; ?></td>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] != "U") { ?>
                                               <td><?= isset($data_detail["cekold_data_presensi"]) ? $data_detail['cekold_data_presensi'] : "-"; ?></td>
                                                <?php }
                                                ?>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] == "E") { ?>
                                                <td><?= $data_detail['ceknew_data_presensi']; ?></td>
                                                <?php }
                                                ?>
                                                <?php
                                                if ($row['aksi_aprv_presensi'] == "U") { ?>
                                                <td><?= $data_detail['jam_data_presensi'] == "00:00:00" ? "" : $data_detail['jam_data_presensi']; ?></td>
                                                <?php }
                                                ?>
                                                <td><?= $data_detail['ket_data_presensi']; ?></td>
                                            </tr>
                                            <?php
                                            }
                                        ?>
                                        </tbody>
                                        <!-- Modal Edit -->
                                        <div class="modal fade text-left" id="ModalApproveEditPresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form message="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="approve-labelpresensi"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="approve-pagepresensi" value="<?= $page; ?>" readonly>
                                                            <input type="hidden" class="form-control" id="approve-idpresensi" name="approve-idpresensi" readonly>
                                                            <input type="hidden" class="form-control" id="approve-nikpresensi" name="approve-nikpresensi" readonly>
                                                            <input type="hidden" class="form-control" id="approve-tglpresensi" name="approve-tglpresensi" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>OTP Code :</label>
                                                                <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="approve-otppresensi" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-outline-primary" name="editdata">Approve</button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                        <!-- Modal Delete -->
                                        <div class="modal fade text-left" id="ModalApproveDeletePresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form message="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="delete-labelpresensi"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="delete-pagepresensi" value="<?= $page; ?>" readonly>
                                                            <input type="hidden" class="form-control" id="delete-idpresensi" name="delete-idpresensi" readonly>
                                                            <input type="hidden" class="form-control" id="delete-nikpresensi" name="delete-nikpresensi" readonly>
                                                            <input type="hidden" class="form-control" id="delete-tglpresensi" name="delete-tglpresensi" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>OTP Code :</label>
                                                                <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="delete-otppresensi" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-outline-primary" name="deletedata">Approve</button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                        <!-- Modal Update -->
                                        <div class="modal fade text-left" id="ModalApproveUpdatePresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form message="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="update-labelpresensi"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="update-pagepresensi" value="<?= $page; ?>" readonly>
                                                            <input type="hidden" class="form-control" id="update-idpresensi" name="update-idpresensi" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>OTP Code :</label>
                                                                <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="update-otppresensi" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-outline-primary" name="updatedata">Approve</button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                        <!-- Modal Reject -->
                                        <div class="modal fade text-left" id="ModalApproveRejectPresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form message="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger white">
                                                        <h4 class="modal-title white" id="reject-labelpresensi"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="reject-pagepresensi" value="<?= $page; ?>" readonly>
                                                            <input type="hidden" class="form-control" id="reject-docnopresensi" name="reject-docnopresensi" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>OTP Code :</label>
                                                                <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="reject-otppresensi" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-outline-danger" name="rejectdata">Reject</button>
                                                    </div>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </table>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-danger mr-1 approve_rejectkehadiran" name="approve_rejectkehadiran" id="<?= $row['no_aprv_presensi']; ?>"><i class="ft-x"></i> Reject</button>
                                <button type="button" class="btn btn-primary <?= $row['aksi_aprv_presensi'] === "E" ? "approve_editkehadiran" : ($row['aksi_aprv_presensi'] === "D" ? "approve_deletekehadiran" : "approve_updatekehadiran"); ?>" name="<?= $row['aksi_aprv_presensi'] == "E" ? "approve_editkehadiran" : ($row['aksi_aprv_presensi'] === "D" ? "approve_deletekehadiran" : "approve_updatekehadiran"); ?>" id="<?= $row['no_aprv_presensi']; ?>" data-toggle="modal" data-target="#<?= $row['aksi_aprv_presensi'] === "E" ? "edit-approvepresensi" : ($row['aksi_aprv_presensi'] === "D" ? "approve_deletekehadiran" : "approve_updatekehadiran"); ?>"><i class="ft-check"></i> Approve</button>
                            </div>
                        </form>
                        <?php
                            }
                        ?>
                        </div>
                        <div class="card-footer no-border pb-1">
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
    include ("includes/templates/js-error.php");
?>
</body>
</html>

<script>
$(document).ready(function(){
    $(document).on('click', '.approve_editkehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#approve-idpresensi').val(data.no_aprv_presensi);
                $('#approve-nikpresensi').val(data.nik_presensi);
                $('#approve-tglpresensi').val(data.tgl_presensi);

                $('#approve-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveEditPresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_deletekehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#delete-idpresensi').val(data.no_aprv_presensi);
                $('#delete-nikpresensi').val(data.nik_presensi);
                $('#delete-tglpresensi').val(data.tgl_presensi);

                $('#delete-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveDeletePresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_updatekehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#update-idpresensi').val(data.no_aprv_presensi);
                $('#update-nikpresensi').val(data.nik_presensi);
                $('#update-tglpresensi').val(data.tgl_presensi);

                $('#update-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveUpdatePresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_rejectkehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#reject-docnopresensi').val(data.no_aprv_presensi);

                $('#reject-labelpresensi').html("Reject Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveRejectPresensi').modal('show');
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