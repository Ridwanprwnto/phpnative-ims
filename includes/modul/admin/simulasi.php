<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

?>
<!-- Auto Fill table -->
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Simulasi Fitur</h4>
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
                            <li class="nav-item">
                                <a class="nav-link" id="laporan-penilaian" data-toggle="tab" href="#laporanpenilaian" aria-expanded="false">Laporan Assesment Tahunan</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="periodepenilaian" aria-expanded="true" aria-labelledby="periode-penilaian">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="monitoringpenilaian" aria-labelledby="monitoring-penilaian">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="laporanpenilaian" aria-labelledby="laporan-penilaian">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                        <form method="post" action="reporting/report-penilaian.php" target="_blank">
                                            <div class="form-row">   
                                                <input class="form-control" type="hidden" name="user-cetak" value="<?= $user;?>">
                                                <div class="col-md-6 mb-2">
                                                    <label>Office : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="office-cetak" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$office_id'");
                                                        while($data_off = mysqli_fetch_assoc($query_off)) {
                                                        ?>
                                                        <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Department : </label>
                                                    <select class="select2 form-control block" style="width: 100%"
                                                        type="text" name="dept-cetak" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id'");
                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                        ?>
                                                        <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tahun : </label>
                                                    <select class="select2 form-control block" style="width: 100%"
                                                        type="text" name="tahun-cetak" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_tahun = mysqli_query($conn, "SELECT * FROM periodebudget");
                                                        while($data_tahun = mysqli_fetch_assoc($query_tahun)) {
                                                        ?>
                                                        <option value="<?= $data_tahun["tahun_periode"];?>"><?= $data_tahun["tahun_periode"];?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Bagian : </label>
                                                    <select class="select2 form-control" multiple="multiple" style="width: 100%" type="text" data-placeholder="Please Select" name="divisicetak[]" required>
                                                        <option value="ALL" >ALL</option>
                                                        <?php 
                                                            $query_bag = mysqli_query($conn, "SELECT * FROM divisi");
                                                            while($data_bag = mysqli_fetch_assoc($query_bag)) { 
                                                                $databag = "'".$data_bag['id_divisi']."'"; ?>
                                                                <option value="<?= $databag;?>"><?= $data_bag['divisi_name'];?></option>
                                                            <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <button type="submit" name="printpdf" class="btn btn-primary mt-1"><i class="ft-printer"></i> Print Pdf </button> -->
                                            <button type="submit" name="printexcell" class="btn btn-primary mt-1"> <i class="ft-printer"></i> Print Excell </button>
                                        </form>
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

<script>

</script>

<?php
    include ("includes/templates/alert.php");
?>