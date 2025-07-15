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
                    <h4 class="card-title" id="horz-layout-basic">Report Data Master Application</h4>
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
                            <form method="post" action="reporting/report-data-master-app.php" target="_blank">
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
                                        <label>Base : </label>
                                        <select class="select2 form-control block" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="basecetak[]" required>
                                            <option value="ALL">ALL</option>
                                            <option value="'DESKTOP'">DESKTOP</option>
                                            <option value="'WEB'">WEB</option>
                                            <option value="'MOBILE'">MOBILE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Developer : </label>
                                        <select class="select2 form-control block" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="devcetak[]" required>
                                            <option value="ALL">ALL</option>
                                            <option value="'SD3'">SD3</option>
                                            <option value="'SD5'">SD5</option>
                                            <option value="'LAIN-LAIN'">LAIN-LAIN</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Penggunaan : </label>
                                        <select class="select2 form-control block" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="usecetak[]" required>
                                            <option value="ALL">ALL</option>
                                            <option value="'Y'">VERSI EXIST</option>
                                            <option value="'N'">VERSI OLD</option>
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