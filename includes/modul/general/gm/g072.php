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
                    <h4 class="card-title" id="horz-layout-basic">Report Data IP Address</h4>
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
                            <form method="post" action="reporting/report-ip-address.php" target="_blank">
                                <div class="form-row">
                                    <input type="hidden" name="user-ip" value="<?= $user; ?>" class="form-control" readonly>
                                    <div class="col-md-6 mb-2">
                                        <label>Office : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-ip" required>
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
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="dept-ip" required>
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
                                        <label>Segment : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="segment-ip" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="ALL" >ALL</option>
                                            <?php 
                                                $query_seg = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name FROM ip_segment AS A
                                                INNER JOIN office AS B ON A.office_iseg = B.id_office
                                                INNER JOIN department AS C ON A.dept_iseg = C.id_department
                                                WHERE A.office_iseg = '$office_id' AND A.dept_iseg = '$dept_id' ORDER BY A.id_iseg ASC
                                                ");
                                                while($data_seg = mysqli_fetch_assoc($query_seg)) { ?>
                                                <option value="<?= $data_seg['id_iseg'];?>">
                                                    <?= $data_seg['name_iseg'];?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Status : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="status-ip" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="ALL" >All</option>
                                            <option value="Y" >Active</option>
                                            <option value="N" >Non Active</option>
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