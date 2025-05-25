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
                    <h4 class="card-title" id="horz-layout-basic">Data Barcode Peralatan Inventaris</h4>
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
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="label-full" data-toggle="tab" href="#labelfull" aria-expanded="true">Label Standard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="label-small" data-toggle="tab" href="#labelsmall" aria-expanded="false">Label Kecil</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="labelfull" aria-expanded="true" aria-labelledby="label-full">
                                <div class="row">
                                    <div class="col-12">                
                                    <form method="post" action="reporting/report-data-barcode.php" target="_blank">
                                        <div class="form-row mt-2">
                                            <div class="col-md-6 mb-2">
                                                <label>Office : </label>
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="office-barcode" required>
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
                                                    type="text" name="dept-barcode" required>
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
                                                <label>Nama Barang : </label>
                                                <select class="select2 form-control block" style="width: 100%"
                                                    type="text" id="barang-barcode" name="barang-barcode" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <option value="ALL" >ALL</option>
                                                    <?php 
                                                        $query_plu_service = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                        INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                        INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis 
                                                        WHERE LEFT(A.dat_asset, 4) = '$office_id' AND RIGHT(A.dat_asset, 4) = '$dept_id' GROUP BY C.IDJenis ASC
                                                        ");
                                                        while($data_plu_service = mysqli_fetch_assoc($query_plu_service)) { ?>
                                                        <option value="<?= $office_id.$dept_id.$data_plu_service['pluid'];?>">
                                                            <?= $data_plu_service['pluid']." - ".$data_plu_service['NamaBarang']." ".$data_plu_service['NamaJenis'];?>
                                                        </option>
                                                        <?php 
                                                        } 
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Nomor Aktiva : </label>
                                                <select class="select2 form-control block" style="width: 100%"
                                                    type="text" id="aktiva-barcode" name="aktiva-barcode" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <option value="ALL" >ALL</option>
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
                            <div class="tab-pane" id="labelsmall" aria-labelledby="label-small">
                                <div class="row">
                                    <div class="col-12">                
                                    <form method="post" action="reporting/report-barcode-setlabel.php" target="_blank">
                                        <div class="form-row mt-2">
                                            <div class="col-md-6 mb-2">
                                                <label>Office : </label>
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="office-lat" required>
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
                                                    type="text" name="dept-lat" required>
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
                                                <label>Nama Barang : </label>
                                                <select class="select2 form-control block" style="width: 100%"
                                                    type="text" id="barang-lat" name="barang-lat" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <?php 
                                                        $query_plu_lat = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                        INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                        INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis 
                                                        WHERE LEFT(A.dat_asset, 4) = '$office_id' AND RIGHT(A.dat_asset, 4) = '$dept_id' GROUP BY C.IDJenis ASC
                                                        ");
                                                        while($data_plu_lat = mysqli_fetch_assoc($query_plu_lat)) { ?>
                                                        <option value="<?= $office_id.$dept_id.$data_plu_lat['pluid'];?>">
                                                            <?= $data_plu_lat['pluid']." - ".$data_plu_lat['NamaBarang']." ".$data_plu_lat['NamaJenis'];?>
                                                        </option>
                                                        <?php 
                                                        } 
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Nomor Aktiva : </label>
                                                <select class="select2 form-control block" style="width: 100%"
                                                    type="text" id="aktiva-lat" name="aktiva-lat" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <option value="ALL" >ALL</option>
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
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->

<script>
    $(document).ready(function () {
        $("#barang-barcode").on('change', function () {
            var barangID = $('#barang-barcode').val();
            var data = "BarcodeBarang=" + barangID;
            if (barangID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#aktiva-barcode').html(htmlresponse);
                    }
                });
            } else {
                $('#aktiva-barcode').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });

    $(document).ready(function () {
        $("#barang-lat").on('change', function () {
            var barangID = $('#barang-lat').val();
            var data = "BarcodeHandheld=" + barangID;
            if (barangID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#aktiva-lat').html(htmlresponse);
                    }
                });
            } else {
                $('#aktiva-lat').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });
</script>