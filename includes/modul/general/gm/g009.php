<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["updatedata"])){
    if(ProsesBudget($_POST) > 0 ){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Draft Budget Tahun ".$datapost." Berhasil Diproses", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["importdata"])){
    if(ImportBudget($_POST) >= 0 ){
        $alert = array("Success!", "Data Draft Budget Berhasil Diupload", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["resetdata"])){
    if(ResetBudget($_POST) > 0 ){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Data Draft Budget Tahun ".$datapost." Berhasil Direset", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>
<!-- Auto Fill table -->
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Entry Budget Tahunan</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-secondary square btn-min-width ml-1 mb-1" data-toggle="modal" data-target="#importbudget">Import Budget</button>
                                    <button type="button" class="btn btn-secondary square btn-min-width ml-1 mb-1" data-toggle="modal" data-target="#printbudget">Export Budget</button>
                                    <button type="button" class="btn btn-warning square btn-min-width ml-1 mb-1" data-toggle="modal" data-target="#resetbudget">Reset Budget</button>
                                    <!-- Import Modal -->
                                    <div class="modal fade text-left" id="importbudget" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-secondary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Import Data Darft Budget</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tahun : </label>
                                                                <select name="tahun" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                        $result_thn = "SELECT periodebudget.* FROM periodebudget
                                                                        INNER JOIN statusbudget ON periodebudget.tahun_periode = statusbudget.tahun_periode
                                                                        WHERE statusbudget.id_office = '$office_id' AND statusbudget.id_department = '$dept_id' AND statusbudget.status_budget ='N'";
                                                                        $query_thn = mysqli_query($conn, $result_thn);
                                                                        while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                                                    ?>
                                                                        <option value="<?= $data_thn['tahun_periode'];?>" ><?= $data_thn['tahun_periode'];?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Office : </label>
                                                                <select name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_office = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$office_id' ");
                                                                        while($data_office = mysqli_fetch_assoc($query_office)) {
                                                                    ?>
                                                                        <option value="<?= $data_office['id_office'];?>" ><?= $data_office['id_office'].' - '.strtoupper($data_office['office_name']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Department : </label>
                                                                <select name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_department = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id' ");
                                                                        while($data_department = mysqli_fetch_assoc($query_department)) {
                                                                    ?>
                                                                        <option value="<?= $data_department['id_department'];?>" ><?= $data_department['id_department'].' - '.strtoupper($data_department['department_name']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>File : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="filecsv" id="importcsv">
                                                                    <label class="custom-file-label" for="importcsv">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="importdata" class="btn btn-outline-primary">Import</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Print Modal -->
                                    <div class="modal fade text-left" id="printbudget" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="reporting/report-budget.php" method="post" target="_blank">
                                                    <div class="modal-header bg-warning white">
                                                        <h4 class="modal-title white" id="myModalLabel">Export Data Draft Budget</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tahun : </label>
                                                                <select name="tahun" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                        $result_thn = "SELECT periodebudget.* FROM periodebudget
                                                                        INNER JOIN statusbudget ON periodebudget.tahun_periode = statusbudget.tahun_periode
                                                                        WHERE statusbudget.id_office = '$office_id' AND statusbudget.id_department = '$dept_id' AND statusbudget.status_budget ='N'";
                                                                        $query_thn = mysqli_query($conn, $result_thn);
                                                                        while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                                                    ?>
                                                                        <option value="<?= $data_thn['tahun_periode'];?>" ><?= $data_thn['tahun_periode'];?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Office : </label>
                                                                <select name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_office = mysqli_query($conn, "SELECT office.*, COUNT(statusbudget.id_sb) FROM office
                                                                        INNER JOIN statusbudget ON office.id_office = statusbudget.id_office
                                                                        WHERE office.id_office = '$office_id' AND statusbudget.status_budget = 'N'");
                                                                        while($data_office = mysqli_fetch_assoc($query_office)) {
                                                                    ?>
                                                                        <option value="<?= $data_office['id_office'];?>" ><?= $data_office['id_office'].' - '.strtoupper($data_office['office_name']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Department : </label>
                                                                <select name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_department = mysqli_query($conn, "SELECT department.*, COUNT(statusbudget.id_sb) FROM department 
                                                                        INNER JOIN statusbudget ON department.id_department = statusbudget.id_department
                                                                        WHERE department.id_department = '$dept_id' AND statusbudget.status_budget ='N'");
                                                                        while($data_department = mysqli_fetch_assoc($query_department)) {
                                                                    ?>
                                                                        <option value="<?= $data_department['id_department'];?>" ><?= $data_department['id_department'].' - '.strtoupper($data_department['department_name']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="printdata" onclick="return downloadDraftBudget();" class="btn btn-outline-warning">Print</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Reset Modal -->
                                    <div class="modal fade text-left" id="resetbudget" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-warning white">
                                                        <h4 class="modal-title white" id="myModalLabel">Reset Data Draft Budget</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="office" value="<?= $office_id; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept" value="<?= $dept_id; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tahun : </label>
                                                                <select name="tahun" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                        $result_thn = "SELECT periodebudget.* FROM periodebudget
                                                                        INNER JOIN statusbudget ON periodebudget.tahun_periode = statusbudget.tahun_periode
                                                                        WHERE statusbudget.id_office = '$office_id' AND statusbudget.id_department = '$dept_id' AND statusbudget.status_budget ='N'";
                                                                        $query_thn = mysqli_query($conn, $result_thn);
                                                                        while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                                                    ?>
                                                                        <option value="<?= $data_thn['tahun_periode'];?>" ><?= $data_thn['tahun_periode'];?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="resetdata" class="btn btn-outline-warning">Reset</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center" id="tableDraftBudget">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Qty Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT budget.*, mastercategory.*, masterjenis.*, statusbudget.*, office.*, department.* FROM budget
                                INNER JOIN mastercategory ON LEFT(budget.plu_id, 6) = mastercategory.IDBarang
                                INNER JOIN masterjenis ON RIGHT(budget.plu_id, 4) = masterjenis.IDJenis
                                INNER JOIN statusbudget ON budget.tahun_periode = statusbudget.tahun_periode
                                INNER JOIN office ON budget.id_office = office.id_office
                                INNER JOIN department ON budget.id_department = department.id_department
                                WHERE budget.id_office = '$office_id' AND budget.id_department = '$dept_id' AND statusbudget.id_office = '$office_id' AND statusbudget.id_department = '$dept_id' AND statusbudget.status_budget = 'N'");
                                while($data = mysqli_fetch_assoc($query)) {
                                    $saldo = $data['stock_budget'];
                            ?>
                                <tr id="<?= $data["id_budget"]; ?>" class="edit_tr">
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['tahun_periode']; ?></td>
                                    <td><?= $data['plu_id']; ?></td>
                                    <td><?= $data['NamaBarang']." ".$data['NamaJenis']; ?></td>
                                    <td class="edit_td">
                                        <span id="saldobudget_<?= $data["id_budget"]; ?>" class="text"><?= $saldo; ?></span>
                                        <input type="number" value="<?= $saldo; ?>" class="form-control editbox" id="saldobudget_input_<?= $data["id_budget"]; ?>">
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success square btn-min-width mr-1 mt-1 mb-2 pull-right" data-toggle="modal" data-target="#prosesbudget">Proses Budget Tahunan</button>
                        <!-- Modal Proses -->
                        <div class="modal fade text-left" id="prosesbudget"
                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="myModalLabel">Proses Data Draft Budget</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-2">
                                                    <label>Office : </label>
                                                    <select name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $queryoffice = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$office_id'");
                                                            while($dataoffice = mysqli_fetch_assoc($queryoffice)) {
                                                        ?>
                                                            <option value="<?= $dataoffice['id_office'];?>" ><?= $dataoffice['id_office']." - ".strtoupper($dataoffice['office_name']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Department : </label>
                                                    <select name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $querydept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id'");
                                                            while($datadept = mysqli_fetch_assoc($querydept)) {
                                                        ?>
                                                            <option value="<?= $datadept['id_department'];?>" ><?= $datadept['id_department']." - ".strtoupper($datadept['department_name']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tahun : </label>
                                                    <select name="tahun" class="select2 form-control block" style="width: 100%" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $querytahun = mysqli_query($conn, "SELECT * FROM statusbudget WHERE id_office = '$office_id' AND id_department = '$dept_id' AND status_budget ='N'");
                                                            while($datatahun = mysqli_fetch_assoc($querytahun)) {
                                                                $dataidoffice = $datatahun['id_office'];
                                                                $dataiddept = $datatahun['id_department'];
                                                        ?>
                                                            <option value="<?= $datatahun['tahun_periode'];?>" ><?= $datatahun['tahun_periode'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn btn-outline-success">Proses</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Proses -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function() {
    $(".edit_tr").click(function() {
    var ID = $(this).attr('id');
    $("#saldobudget_"+ID).hide();
    $("#saldobudget_input_"+ID).show();
    }).change(function() {
        var ID = $(this).attr('id');
        var saldo_bgt = $("#saldobudget_input_"+ID).val();
        var dataString = 'IDBUDGET='+ID+'&SALDOBUDGET='+saldo_bgt;
        if(saldo_bgt >= 0) {
            $.ajax({
                type: "POST",
                url: "action/datarequest.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#saldobudget_"+ID).html(saldo_bgt);
                    toastr.success('Data Saldo ID '+ ID +' berhasil di update!', 'Draft Budget');
                }
            });
        }   
        else {
            alert('Qty budget tidak boleh kurang dari nol');
        }
    });

    // Edit input box click action
    $(".editbox").mouseup(function() {
        return false
    });

    // Outside click action
    $(document).mouseup(function() {
        $(".editbox").hide();
        $(".text").show();
    });
});
    
function downloadDraftBudget() {
    $('#printbudget').modal('hide');
}
</script>

<?php
    include ("includes/templates/alert.php");
?>