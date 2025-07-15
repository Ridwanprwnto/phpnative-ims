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
                    <h4 class="card-title" id="horz-layout-basic">Report Summary Data Pelanggaran CCTV Per User</h4>
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
                            <form method="post" action="reporting/report-sum-user-pelanggar.php" target="_blank">
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
                                    <div class="col-md-6 mb-2">
                                        <label>Start Date : </label>
                                        <input type="date" name="start-cetak" max="<?=date('Y-m-d')?>" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>End Date : </label>
                                        <input type="date" name="end-cetak" max="<?=date('Y-m-d')?>" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Status Follow Up : </label>
                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="statuscetak[]" required>
                                            <option value="'N'">SUDAH FUP ATASAN BELUM DI APPROVE</option>
                                            <option value="'Y'">SUDAH FUP DAN SUDAH DI APPROVE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>User Pelanggar : </label>
                                        <select name="pelanggarcetak" class="select2 form-control block" style="width: 100%" type="text" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <optgroup label="TIDAK TERIDENTIFIKASI">
                                            <?php
                                                $ident = array('IDENTITAS TIDAK DIKETAHUI', 'BAGIAN LAIN');
                                                foreach ($ident as $i) {
                                            ?>
                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                            <?php
                                                }
                                            ?>
                                            </optgroup>
                                            <optgroup label="TERIDENTIFIKASI">
                                                <option value="ALL">ALL</option>
                                            <?php 
                                                $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$office_id' AND id_group NOT LIKE 'GP01' ORDER BY username ASC");
                                                while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                <option value="<?= $data_user['nik']; ?>"><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                            </optgroup>
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
    var input = document.getElementById("reqDate");
    var today = new Date();
    var day = today.getDate();

    // Set month to string to add leading 0
    var mon = new String(today.getMonth()+1); //January is 0!
    var yr = today.getFullYear();

    if(mon.length < 2) { mon = "0" + mon; }
    if(day.length < 2) { dayn = "0" + day; }

    var date = new String( yr + '-' + mon + '-' + day );

    input.disabled = false; 
    input.setAttribute('max', date);
</script>