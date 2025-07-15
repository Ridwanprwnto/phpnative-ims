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
                    <h4 class="card-title" id="horz-layout-basic">Report Saldo Budget Tahunan</h4>
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
                            <form method="post" action="reporting/report-stock-budget.php" target="_blank">
                                <div class="form-row">   
                                    <div class="col-md-6 mb-2">
                                        <input type="hidden" class="form-control" name="user-cetak" value="<?= $user; ?>">
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
                                    <div class="col-md-6 mb-2">
                                        <label>Tahun : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="tahun-cetak" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                            $query_tahun = mysqli_query($conn, "SELECT * FROM statusbudget WHERE status_budget = 'Y'");
                                            while($data_tahun = mysqli_fetch_assoc($query_tahun)) {
                                            ?>
                                            <option value="<?= $data_tahun["tahun_periode"];?>"><?= $data_tahun["tahun_periode"];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nama Barang : </label>
                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="barangcetak[]" required>
                                            <option value="ALL" >ALL</option>
                                            <?php 
                                                $query_barang = mysqli_query($conn, "SELECT A.IDBarang, A.NamaBarang, B.IDJenis, B.NamaJenis FROM mastercategory AS A
                                                INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang ORDER BY A.NamaBarang");
                                                while($data_barang = mysqli_fetch_assoc($query_barang)) { 
                                                    $data = "'".$data_barang['IDBarang'].$data_barang['IDJenis']."'"; ?>
                                                    <option value="<?= $data;?>">
                                                    <?= $data_barang['IDBarang'].$data_barang['IDJenis']." - ".$data_barang['NamaBarang']." ".$data_barang['NamaJenis'];?>
                                                    </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="printpdf" class="btn btn-primary mt-1"><i class="ft-printer"></i> Print Pdf </button>
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