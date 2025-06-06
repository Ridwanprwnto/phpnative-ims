<?php

    $page_id = $_GET['page'];
    $user = $_SESSION["user_name"];
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Laporan Mutasi Barang</h4>
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
                                <form method="post" action="reporting/report-laporan-mutasi-barang.php" target="_blank">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-2">
                                            <input type="hidden" id="user" class="form-control" name="user" value="<?= $user; ?>">
                                            <label for="office">Office</label>
                                            <div class="position-relative has-icon-left">
                                                <select name="office" class="form-control" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <?php 
                                                $query_office = mysqli_query($conn, "SELECT * FROM office
                                                WHERE id_office = '$office_id'");
                                                while($data_office = mysqli_fetch_assoc($query_office)) {
                                            ?>
                                                    <option value="<?= $data_office['id_office'];?>">
                                                        <?= $data_office['id_office'].' - '.strtoupper($data_office['office_name']);?>
                                                    </option>
                                                    <?php 
                                                } 
                                            ?>
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="ft-message-square"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="department">Department</label>
                                            <div class="position-relative has-icon-left">
                                                <select name="department" class="form-control" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <?php 
                                                $query_department = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id'");
                                                while($data_department = mysqli_fetch_assoc($query_department)) {
                                            ?>
                                                    <option value="<?= $data_department['id_department'];?>">
                                                        <?= $data_department['id_department'].' - '.strtoupper($data_department['department_name']);?>
                                                    </option>
                                                    <?php 
                                                } 
                                            ?>
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="ft-message-square"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Periode Awal : </label>
                                            <input type="date" name="tglawal" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Periode Akhir : </label>
                                            <input type="date" name="tglakhir" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Jenis Transaksi : </label>
                                            <select name="transaksi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                <option value="" selected disabled>Please Select</option>
                                                <option value="+">Penerimaan</option>
                                                <option value="-">Pengeluaran</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="month">Barang : </label>
                                            <select name="pluid" class="select2 form-control block" style="width: 100%" type="text" required>
                                                <option value="" selected disabled>Please Select</option>
                                                <option value="ALL">ALL</option>
                                                <!-- <option value="ALL">All Barang</option> -->
                                                <?php
                                                $result_brg = "SELECT mastercategory.IDBarang, mastercategory.NamaBarang, masterjenis.IDJenis, masterjenis.NamaJenis FROM masterjenis
                                                    INNER JOIN mastercategory ON masterjenis.IDBarang = mastercategory.IDBarang ORDER BY masterjenis.IDBarang ASC";
                                                $query_brg = mysqli_query($conn, $result_brg);
                                                while($data_brg = mysqli_fetch_assoc($query_brg)) {
                                                ?>
                                                <option value="<?= $data_brg['IDBarang'].$data_brg['IDJenis']." - ".$data_brg['NamaBarang']." ".$data_brg['NamaJenis'];?>">
                                                    <?= strtoupper($data_brg['IDBarang'].$data_brg['IDJenis']." - ".$data_brg['NamaBarang']." ".$data_brg['NamaJenis']);?>
                                                </option>
                                                <?php 
                                                    } 
                                                ?>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary ml-1 mt-1">
                                        <i class="ft-printer"></i> Print Report
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