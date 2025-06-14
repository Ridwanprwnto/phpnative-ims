<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertPeriodePenilaianTahunan($_POST) > 0 ){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Assesment Penilaian Tahun ".$datapost." Berhasil Dibuka", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["finishdata"])){
    if(FinalPeriodePenilaianTahunan($_POST)){
        $datapost = isset($_POST["tahun-fnsbuatperiodeassest"]) ? $_POST["tahun-fnsbuatperiodeassest"] : NULL;
        $alert = array("Success!", "Laporan Penilaian Periode Tahun ".$datapost." Berhasil Ditutup", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdatePeriodePenilaianTahunan($_POST)){
        $datapost = isset($_POST["tahun-updbuatperiodeassest"]) ? $_POST["tahun-updbuatperiodeassest"] : NULL;
        $alert = array("Success!", "Data Leader Yang Terdaftar Sudah Selesai Diupdate Pada Penilaian Tahun ".$datapost, "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeletePeriodePenilaianTahunan($_POST)){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Assesment Penilaian Tahun ".$datapost." Berhasil Dibatalkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["resetdata"])){
    if(DeleteLaporanPenilaianTahunan($_POST)){
        $datapost = isset($_POST["junior-dellapassest"]) ? $_POST["junior-dellapassest"] : NULL;
        $alert = array("Success!", "Assesment Penilaian NIK ".$datapost." Berhasil Reset", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["resetdataleader"])){
    if(DeleteLaporanPenilaianTahunanLeader($_POST)){
        $datapost = isset($_POST["senior-dellapassestlead"]) ? $_POST["senior-dellapassestlead"] : NULL;
        $alert = array("Success!", "Assesment Penilaian Leader NIK ".$datapost." Berhasil Reset", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>
<!-- Auto Fill table -->
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Assesment Tahunan</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="periode-penilaian" data-toggle="tab" href="#periodepenilaian" aria-expanded="true">Periode Assessment Tahunan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="monitoring-penilaian" data-toggle="tab" href="#monitoringpenilaian" aria-expanded="false">Monitoring Assesment Tahunan</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="periodepenilaian" aria-expanded="true" aria-labelledby="periode-penilaian">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryassessment">Entry Periode</button>
                                            <div class="modal fade text-left" id="entryassessment" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Periode Penilaian</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Tahun Periode : </label>
                                                                        <select id="tahun" name="tahun" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_thn = mysqli_query($conn, "SELECT * FROM periodebudget");
                                                                                while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                                                            ?>
                                                                                <option value="<?= $data_thn['tahun_periode'];?>"><?= $data_thn['tahun_periode']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Kantor : </label>
                                                                        <select id="office" name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$office_id'");
                                                                                while($data_off = mysqli_fetch_assoc($query_off)) {
                                                                            ?>
                                                                                <option value="<?= $data_off['id_office'];?>"><?= $data_off['id_office']." - ".strtoupper($data_off['office_name']); ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Department : </label>
                                                                        <select id="department" name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_dept = mysqli_query($conn, "SELECT id_department, department_name FROM department WHERE id_department = '$dept_id'");
                                                                                while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                                            ?>
                                                                                <option value="<?= $data_dept['id_department'];?>"><?= strtoupper($data_dept['department_name']); ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Divisi : </label>
                                                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="divisi[]">
                                                                            <?php 
                                                                                $sql_divisi = mysqli_query($conn, "SELECT id_divisi, divisi_name FROM divisi");
                                                                                while($data_divisi = mysqli_fetch_assoc($sql_divisi)) {
                                                                            ?>
                                                                                <option value="<?= $data_divisi['id_divisi']." - ".$data_divisi['divisi_name']; ?>"><?= $data_divisi['divisi_name'];?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>User : </label>
                                                                        <select id="pic" name="pic" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                                <option value="<?=$nik;?>" ><?= strtoupper($username);?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdata" class="btn btn-outline-primary">Create</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                        <tr>
                                            <th>TAHUN PERIODE</th>
                                            <th>KANTOR</th>
                                            <th>DIVISI</th>
                                            <th>JUMLAH DRAFT</th>
                                            <th>PROGRESS DRAFT</th>
                                            <th>STATUS LAPOR</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $query = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name, COUNT(E.head_code_sts_assest) AS jumlah_assest, COUNT(IF(E.status_leader_assest = 'Y', 1, NULL)) AS jumlah_selesai, SUM(IF(E.status_leader_assest = 'N', 1, 0)) AS count_status FROM statusassessment AS A
                                        INNER JOIN office AS B ON A.office_sts_assest = B.id_office
                                        INNER JOIN department AS C ON A.dept_sts_assest = C.id_department
                                        INNER JOIN divisi_assessment AS D ON A.code_sts_assest = D.head_code_sts_assest
                                        INNER JOIN leader_assessment AS E ON A.code_sts_assest = E.head_code_sts_assest
                                        WHERE A.office_sts_assest = '$office_id' AND A.dept_sts_assest = '$dept_id' AND A.flag_sts_assest = 'N' AND D.head_id_divisi = '$div_id' GROUP BY A.code_sts_assest ORDER BY A.code_sts_assest ASC");
                                        if(mysqli_num_rows($query) > 0 ) {
                                            while($data = mysqli_fetch_assoc($query)){
                                                $jumlah = $data['jumlah_assest'];
                                                $selesai = $data['jumlah_selesai'];
                                                $persentasi = number_format($selesai / $jumlah * 100);
                                                
                                                $id_asst = $data['id_sts_assest'];
                                                $query_lapor = mysqli_query($conn, "SELECT COUNT(head_id_sts_assest) AS jumlah_data, COUNT(IF(status_data_assest = 'Y', 1, NULL)) AS jumlah_lapor, SUM(IF(status_data_assest = 'N', 1, 0)) AS jumlah_draft FROM data_assessment WHERE head_id_sts_assest = '$id_asst'");
                                                $data_lapor = mysqli_fetch_assoc($query_lapor);

                                                $jumlah_data = $data_lapor['jumlah_data'];
                                                $jumlah_lapor = $data_lapor['jumlah_lapor'];
                                                $jumlah_draft = $data_lapor['jumlah_draft'];
                                                
                                                if ($jumlah_data > 0) {
                                                    $persentasi_lapor = number_format($jumlah_draft / $jumlah_data * 100);
                                                }
                                            ?>
                                            <tr>
                                                <td><?= $tahun = $data['tahun_sts_assest']; ?></td>
                                                <td><?= $data['office_sts_assest']." - ".strtoupper($data['office_name'])." ".strtoupper($data['department_name']); ?></td>
                                                <td><?= $data['divisi_sts_assest']; ?></td>
                                                <td><?= $selesai." OF ".$jumlah; ?></td>
                                                <td>
                                                    <div class="text-center" id="example-caption-2"><?= $persentasi; ?>%</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $persentasi; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $persentasi; ?>%" aria-describedby="example-caption-2"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="badge badge-<?= $jumlah_data == 0 ? "danger" : ($jumlah_data == $jumlah_lapor ? "info" : "warning"); ?> "><?= $jumlah_data == 0 ? "PROSES" : ($jumlah_data == $jumlah_lapor ? "FINAL" : "PROSES"); ?></div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-primary finish_buatperiodepenilaian" title="Finalisasi Penilaian Tahun <?= $data['tahun_sts_assest']; ?>" name="finish_buatperiodepenilaian" id="<?= $data["code_sts_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" <?= $jumlah_data == $jumlah_lapor ? '' : 'disabled'; ?>><i class="ft-check-square"></i></button>
                                                    <button type="button" class="btn btn-icon btn-success update_buatperiodepenilaian" title="Update Data Leader Untuk Penilaian Tahun <?= $data['tahun_sts_assest']; ?>" name="update_buatperiodepenilaian" id="<?= $data["code_sts_assest"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-refresh-cw"></i></button>
                                                    <button type="button" class="btn btn-icon btn-danger delete_buatperiodepenilaian" title="Cancel Periode Penilaian Tahun <?= $data['tahun_sts_assest']; ?>" name="delete_buatperiodepenilaian" id="<?= $data["id_sts_assest"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                                </td>
                                            </tr>
                                            <?php   
                                            }
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Finish -->
                                    <div class="modal fade text-left" id="finishModalBuatPeriodePenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Finalisasi Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page-fnsbuatperiodeassest" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-fnsbuatperiodeassest" name="id-fnsbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="code-fnsbuatperiodeassest" name="code-fnsbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="tahun-fnsbuatperiodeassest" name="tahun-fnsbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="office-fnsbuatperiodeassest" name="office-fnsbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="dept-fnsbuatperiodeassest" name="dept-fnsbuatperiodeassest" class="form-control" readonly>
                                                    <label id="label-fnsbuatperiodeassest"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="finishdata" class="btn btn-outline-primary">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalBuatPeriodePenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Update Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page-updbuatperiodeassest" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-updbuatperiodeassest" name="id-updbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="code-updbuatperiodeassest" name="code-updbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="tahun-updbuatperiodeassest" name="tahun-updbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="office-updbuatperiodeassest" name="office-updbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="dept-updbuatperiodeassest" name="dept-updbuatperiodeassest" class="form-control" readonly>
                                                    <label id="label-updbuatperiodeassest"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="updatedata" class="btn btn-outline-success">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalBuatPeriodePenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page-delbuatperiodeassest" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-delbuatperiodeassest" name="id-delbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="tahun-delbuatperiodeassest" name="tahun-delbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="office-delbuatperiodeassest" name="office-delbuatperiodeassest" class="form-control" readonly>
                                                    <input type="hidden" id="dept-delbuatperiodeassest" name="dept-delbuatperiodeassest" class="form-control" readonly>
                                                    <label id="label-delbuatperiodeassest"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="monitoringpenilaian" aria-labelledby="monitoring-penilaian">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <table class="table table-striped table-bordered zero-configuration row-grouping-monitor" id="monitor_assessment">
                                                <thead>
                                                    <tr>
                                                        <th>DETAIL</th>
                                                        <th>NO</th>
                                                        <th>USER</th>
                                                        <th>TAHUN</th>
                                                        <th>DEPARTMENT</th>
                                                        <th>DIVISI</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $nom = 1;
                                                    $query_mon = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name, D.*, E.divisi_name, F.username FROM statusassessment AS A
                                                    INNER JOIN office AS B ON A.office_sts_assest = B.id_office
                                                    INNER JOIN department AS C ON A.dept_sts_assest = C.id_department
                                                    INNER JOIN data_assessment AS D ON A.id_sts_assest = D.head_id_sts_assest
                                                    INNER JOIN divisi AS E ON D.div_data_assest = E.id_divisi
                                                    INNER JOIN users AS F ON D.junior_data_assest = F.nik
                                                    INNER JOIN divisi_assessment AS G ON A.code_sts_assest = G.head_code_sts_assest
                                                    WHERE A.office_sts_assest = '$office_id' AND A.dept_sts_assest = '$dept_id' AND G.head_id_divisi = '$div_id' GROUP BY D.th_data_assest, D.junior_data_assest ORDER BY A.tahun_sts_assest DESC");
                                                    while($data_mon = mysqli_fetch_assoc($query_mon)) {
                                                    ?>
                                                    <tr>
                                                        <td class="details-monitorasst" id="<?= $data_mon["junior_data_assest"].$data_mon['id_sts_assest']; ?>" onclick="changeIcon(this)">
                                                            <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                                        </td>
                                                        <td><?= $nom++; ?></td>
                                                        <td><?= $data_mon['junior_data_assest']." - ".strtoupper($data_mon['username']); ?></td>
                                                        <td>
                                                            <h6 class="mb-0">
                                                                <span class="text-bold-600"><?= $data_mon['tahun_sts_assest']; ?></span> on
                                                                <em><?= date( "d M y", strtotime($data_mon['date_sts_assest'])); ?></em>
                                                            </h6>
                                                        </td>
                                                        <td><?= $data_mon['dept_sts_assest']." - ".strtoupper($data_mon['department_name']); ?></td>
                                                        <td><?= $data_mon['div_data_assest']." - ".strtoupper($data_mon['divisi_name']); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-icon btn-danger delete_datapenilaian" title="Reset Penilaian Periode Tahun <?= $data_mon['tahun_sts_assest']; ?> NIK <?= $data_mon['junior_data_assest']; ?>" name="delete_datapenilaian" id="<?= $data_mon["junior_data_assest"].$data_mon["code_sts_assest"].$data_mon["id_sts_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" <?= $data_mon["flag_sts_assest"] == "N" ? "" : "disabled"; ?>><i class="ft-delete"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    }
                                                ?>
                                                </tbody>
                                                <!-- Modal Delete -->
                                                <div class="modal fade text-left" id="deleteModalMonAssessment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form message="" method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger white">
                                                                <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="page-dellapassest" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                <input type="hidden" id="id-dellapassest" name="id-dellapassest" class="form-control" readonly>
                                                                <input type="hidden" id="code-dellapassest" name="code-dellapassest" class="form-control" readonly>
                                                                <input type="hidden" id="junior-dellapassest" name="junior-dellapassest" class="form-control" readonly>
                                                                <input type="hidden" id="senior-dellapassest" name="senior-dellapassest" class="form-control" readonly>
                                                                <label id="label-dellapassest"></label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="resetdata" class="btn btn-outline-danger">Yes</button>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->
                                                 <!-- Modal Delete -->
                                                <div class="modal fade text-left" id="deleteModalMonAssessmentLeader" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form message="" method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger white">
                                                                <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="page-dellapassestlead" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                <input type="hidden" id="id-dellapassestlead" name="id-dellapassestlead" class="form-control" readonly>
                                                                <input type="hidden" id="code-dellapassestlead" name="code-dellapassestlead" class="form-control" readonly>
                                                                <input type="hidden" id="junior-dellapassestlead" name="junior-dellapassestlead" class="form-control" readonly>
                                                                <input type="hidden" id="senior-dellapassestlead" name="senior-dellapassestlead" class="form-control" readonly>
                                                                <label id="label-dellapassestlead"></label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="resetdataleader" class="btn btn-outline-danger">Yes</button>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script type="text/javascript">

$(document).ready(function(){
    $(document).on('click', '.finish_buatperiodepenilaian', function(){  
        var periode_nilai = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{PERIODEASSESSMENT:periode_nilai},  
            dataType:"json",  
            success:function(data){
                $('#id-fnsbuatperiodeassest').val(data.id_sts_assest);
                $('#code-fnsbuatperiodeassest').val(data.code_sts_assest);
                $('#tahun-fnsbuatperiodeassest').val(data.tahun_sts_assest);
                $('#office-fnsbuatperiodeassest').val(data.office_sts_assest);
                $('#dept-fnsbuatperiodeassest').val(data.dept_sts_assest);
                
                $('#label-fnsbuatperiodeassest').html("Proses ini akan mengunci dan menutup semua laporan penilaian tahun " + data.tahun_sts_assest + " yang sudah selesai dilakukan pengisian oleh seluruh leader yang terdaftar oleh user");
                $('#finishModalBuatPeriodePenilaian').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_buatperiodepenilaian', function(){  
        var periode_nilai = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{PERIODEASSESSMENT:periode_nilai},  
            dataType:"json",  
            success:function(data){
                $('#id-updbuatperiodeassest').val(data.id_sts_assest);
                $('#code-updbuatperiodeassest').val(data.code_sts_assest);
                $('#tahun-updbuatperiodeassest').val(data.tahun_sts_assest);
                $('#office-updbuatperiodeassest').val(data.office_sts_assest);
                $('#dept-updbuatperiodeassest').val(data.dept_sts_assest);
                
                $('#label-updbuatperiodeassest').html("Proses ini akan mengupdate semua data leader yang sudah diupdate oleh setiap user untuk didaftarkan pada akses penilaian");
                $('#updateModalBuatPeriodePenilaian').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_buatperiodepenilaian', function(){  
        var periode_nilai = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{PERIODEASSESSMENT:periode_nilai},  
            dataType:"json",  
            success:function(data){
                $('#id-delbuatperiodeassest').val(data.code_sts_assest);
                $('#tahun-delbuatperiodeassest').val(data.tahun_sts_assest);
                $('#office-delbuatperiodeassest').val(data.office_sts_assest);
                $('#dept-delbuatperiodeassest').val(data.dept_sts_assest);
                
                $('#label-delbuatperiodeassest').html("Proses ini akan menghapus atau membatalkan semua laporan penilaian tahun " + data.tahun_sts_assest);
                $('#deleteModalBuatPeriodePenilaian').modal('show');
            }  
        });
    });
});

$(document).ready(function() {
    
    $('.row-grouping-monitor').DataTable({
        responsive: false,
        autoWidth: false,
        rowReorder: false,
        scrollX: false,
        columnDefs: [
            { "visible": false, "targets": 3 },
        ],
        displayLength: 10,
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(3, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $('.row-grouping-monitor tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 3 && currentOrder[1] === 'asc' ) {
                table.order( [ 3, 'desc' ] ).draw();
            }
            else {
                table.order( [ 3, 'asc' ] ).draw();
            }
        }
    });

});

$(document).ready(function () {

    var table = $('#monitor_assessment').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#monitor_assessment').on('click', 'td.details-monitorasst', function () {
        var no_id = $(this).attr('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            createChild(row, no_id);
            // format(row.child, noref_pp);
            tr.addClass('shown');
        }
    });

    function createChild (row, no_id) {
        // This is the table we'll convert into a DataTable
        var table = $('<table class="display" width="100%"/>');
    
        // Display it the child row
        row.child( table ).show();

        $.ajax({
            url:'action/datarequest.php',
            method:"POST",  
            data:{ACTIONDETAILMONITORASSESSMENT:no_id},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'TANGGAL', data: 'DATE_ASSESST' },
                    { title: 'DOCNO', data: 'DOCNO_ASSESST' },
                    { title: 'LEADER', data: 'LEADER_ASSESST', render: function(data) { 
                        return data.toUpperCase();
                    } },
                    { title: 'POIN', data: 'POIN_ASSESST' },
                    { title: 'GRADE', data: 'GRADE_ASSESST' },
                    { 
                        title: 'STATUS',
                        data: null,
                        render: function(data, type, row) {
                            if (row.STATUS_ASSESST == "Y") {
                                return '<div class="badge badge-info">FINAL</div>'
                            }
                            else if (row.STATUS_ASSESST == "N") {
                                return '<div class="badge badge-warning">DRAFT</div>'
                            }
                        }
                    },
                    { 
                        title: 'ACTION',
                        data: null,
                        render: function(data, type, row) {
                            if (row.STATUS_ASSESST == "Y") {
                                var btnFlagStatus = "disabled";
                            }
                            else if (row.STATUS_ASSESST == "N") {
                                var btnFlagStatus = "";
                            }
                            return '<button type="button" class="btn btn-icon btn-danger delete_nikpenilaian" title="Reset Laporan Penilaian Leader ' + row.LEADER_ASSESST.toUpperCase() + ' Periode Tahun ' + row.THN_ASSESST + '" name="delete_nikpenilaian" id="' + row.DOCNO_ASSESST + '" data-toggle="tooltip" data-placement="bottom" ' + btnFlagStatus + '><i class="ft-delete"></i></button> <a title="Cetak Laporan Hasil Evaluasi Penilaian Leader '+ row.LEADER_ASSESST.toUpperCase() + ' Periode Tahun ' + row.THN_ASSESST + '" href="reporting/report-form-evaluasi-penilaian.php?docno=' + row.ENCRYPT_ASSESST + '" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="bottom" onclick="return postSESSION();" target="_blank"><i class="ft-printer"></i></a>';
                        }
                    }
                ],
                order: [[1, 'asc']]
            } );
        })
    }
});

function postSESSION() {
    var dataToSend = {
            PRINTASSESSMENT: 'PRINT-ASSESSMENT'
    };
    $.ajax({
        url: 'action/datarequest.php',
        method: 'POST',
        data: dataToSend,
        dataType:"json",  
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

$(document).ready(function(){
    $(document).on('click', '.delete_datapenilaian', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMONITORINGASSESSMENT:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-dellapassest').val(data.head_id_sts_assest);
                $('#code-dellapassest').val(data.code_sts_assest);
                $('#junior-dellapassest').val(data.junior_data_assest);
                $('#senior-dellapassest').val(data.leader_data_assest);
                
                $('#label-dellapassest').html("Reset Laporan Penilaian NIK : "+data.junior_data_assest);
                $('#deleteModalMonAssessment').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_nikpenilaian', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMONITORINGASSESSMENTNIK:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-dellapassestlead').val(data.head_id_sts_assest);
                $('#code-dellapassestlead').val(data.docno_data_assest);
                $('#junior-dellapassestlead').val(data.junior_data_assest);
                $('#senior-dellapassestlead').val(data.leader_data_assest);
                
                $('#label-dellapassestlead').html("Reset Penilaian Yang Sudah Dilaporakan Leader : "+data.leader_data_assest);
                $('#deleteModalMonAssessmentLeader').modal('show');
            }  
        });
    });
});

function changeIcon(anchor) {
    var icon = anchor.querySelector("i");
    var button = anchor.querySelector('button');

    icon.classList.toggle('la-plus');
    icon.classList.toggle('la-minus');

    button.classList.toggle('success');
    button.classList.toggle('danger');
}
</script>

<?php
    include ("includes/templates/alert.php");
?>