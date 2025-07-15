<?php

    $page_id = $_GET['page'];
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Report Data Titik Layout CCTV</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">                
                            <form method="post" action="reporting/report-data-cctv.php" target="_blank">
                                <div class="form-row">
                                    <input type="hidden" name="user-dvr" value="<?= $user; ?>" class="form-control" readonly>
                                    <div class="col-md-6 mb-2">
                                        <label>Office : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-dvr" required>
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
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="dept-dvr" required>
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
                                        <label>Server DVR : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="server-dvr" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="ALL" >ALL</option>
                                            <?php 
                                                $query_cctv = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name, D.divisi_name FROM area_cctv AS A
                                                INNER JOIN office AS B ON A.office_area_cctv = B.id_office
                                                INNER JOIN department AS C ON A.dept_area_cctv = C.id_department
                                                INNER JOIN divisi AS D ON A.divisi_area_cctv = D.id_divisi
                                                WHERE A.office_area_cctv = '$office_id' AND A.dept_area_cctv = '$dept_id' ORDER BY A.kode_area_cctv ASC
                                                ");
                                                while($data_cctv = mysqli_fetch_assoc($query_cctv)) { ?>
                                                <option value="<?= $data_cctv['id_area_cctv'];?>">
                                                    <?= $data_cctv['kode_area_cctv']." - ".$data_cctv['divisi_name']." - ".$data_cctv['ip_area_cctv'];?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="printpdf" class="btn btn-primary mt-1"><i class="ft-printer"></i> Print Pdf </button>
                                <button type="submit" name="printexcell" class="btn btn-primary mt-1"> <i class="ft-printer"></i> Print Excell </button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->