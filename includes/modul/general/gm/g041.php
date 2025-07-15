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
                    <h4 class="card-title" id="horz-layout-basic">Data Label Nomor Lambung Barang Inventaris</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">                
                            <form method="post" action="reporting/report-barcode-lnl.php" target="_blank">
                                <div class="form-row">
                                    <div class="col-md-6 mb-2">
                                        <label>Office : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-lnl" required>
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
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="dept-lnl" required>
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
                                        <label>Nama Barang : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" id="barang-lnl" name="barang-lnl" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_plu_lnl = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis 
                                                WHERE LEFT(A.dat_asset, 4) = '$office_id' AND RIGHT(A.dat_asset, 4) = '$dept_id' GROUP BY C.IDJenis ASC
                                                ");
                                                while($data_plu_lnl = mysqli_fetch_assoc($query_plu_lnl)) { ?>
                                                <option value="<?= $office_id.$dept_id.$data_plu_lnl['pluid'];?>">
                                                    <?= $data_plu_lnl['pluid']." - ".$data_plu_lnl['NamaBarang']." ".$data_plu_lnl['NamaJenis']; ?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Nomor Lambung : </label>
                                        <select class="select2 form-control block" multiple="multiple" data-placeholder="Please Select" style="width: 100%" type="text" id="lambung-lnl" name="lambunglnl[]" required>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary mt-1">
                                    <i class="ft-printer"></i> Report Data
                                </button>
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

<script>
    $(document).ready(function () {
        $("#barang-lnl").on('change', function () {
            var barangID = $('#barang-lnl').val();
            var data = "BarcodeNL=" + barangID;
            if (barangID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#lambung-lnl').html(htmlresponse);
                    }
                });
            } else {
                $('#lambung-lnl').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });
</script>