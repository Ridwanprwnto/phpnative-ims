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
                    <h4 class="card-title" id="horz-layout-basic">Laporan Pengeluaran Stock Budget</h4>
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
                                <form method="post" action="reporting/report-pengeluaran-stock-budget.php" target="_blank">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-2">
                                            <input type="hidden" id="user" class="form-control" name="user" value="<?= $user; ?>">
                                            <label for="office">Office</label>
                                                <select class="select2 form-control block" style="width: 100%" name="office" required>
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
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="department">Department</label>
                                                <select class="select2 form-control block" style="width: 100%" name="department" required>
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
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="year">Tahun</label>
                                                <select class="select2 form-control block" style="width: 100%" name="year" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <?php
                                                $result_thn = "SELECT * FROM periodebudget";
                                                $query_thn = mysqli_query($conn, $result_thn);
                                                while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                            ?>
                                                    <option value="<?= $data_thn['tahun_periode'];?>">
                                                        <?= $data_thn['tahun_periode'];?></option>
                                                    <?php 
                                                } 
                                            ?>
                                                </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="month">Barang</label>
                                                <select name="pluid" class="select2 form-control block" style="width: 100%" type="text" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <!-- <option value="ALL">All Barang</option> -->
                                                    <?php
                                                $result_brg = "SELECT mastercategory.IDBarang, mastercategory.NamaBarang, masterjenis.IDJenis, masterjenis.NamaJenis FROM masterjenis
                                                    INNER JOIN mastercategory ON masterjenis.IDBarang = mastercategory.IDBarang ORDER BY masterjenis.IDBarang ASC";
                                                $query_brg = mysqli_query($conn, $result_brg);
                                                while($data_brg = mysqli_fetch_assoc($query_brg)) {
                                            ?>
                                                    <option value="<?= $data_brg['IDBarang'].$data_brg['IDJenis'];?>">
                                                        <?= strtoupper($data_brg['IDBarang'].$data_brg['IDJenis']." - ".$data_brg['NamaBarang']." ".$data_brg['NamaJenis']);?>
                                                    </option>
                                                    <?php 
                                                } 
                                            ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary mt-1 ml-1">
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