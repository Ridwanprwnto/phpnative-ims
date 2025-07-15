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
                    <h4 class="card-title" id="horz-layout-basic">Report Data Peralatan Inventaris</h4>
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
                            <form method="post" action="reporting/report-data-barang.php" target="_blank">
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
                                        <label>Data : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="data-cetak" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="DAT">Kepemilikan</option>
                                            <option value="LOK">Lokasi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nama Barang : </label>
                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="barangcetak[]" required>
                                            <option value="ALL" >ALL</option>
                                            <?php 
                                                $query_plu_service = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis 
                                                WHERE A.ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY C.IDJenis ASC
                                                ");
                                                while($data_plu_service = mysqli_fetch_assoc($query_plu_service)) { 
                                                    $dataplu = "'".$data_plu_service['pluid']."'"; ?>
                                                    <option value="<?= $dataplu;?>">
                                                    <?= $data_plu_service['pluid']." - ".$data_plu_service['NamaBarang']." ".$data_plu_service['NamaJenis'];?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Kondisi Barang : </label>
                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="kondisicetak[]" required>
                                            <option value="ALL" >ALL</option>
                                            <?php
                                                $query_cond = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi != '$arrcond[5]'");
                                                while($data_cond = mysqli_fetch_assoc($query_cond)) { ?>
                                                <option value="<?= $data_cond['id_kondisi']; ?>"><?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name'];?></option>
                                            <?php 
                                                }
                                            ?>
                                            </select>
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