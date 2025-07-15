<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insert_dept_budget($_POST) > 0 ){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Budget Perdepartment Periode ".$datapost." Berhasil Dibuat", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(delete_dept_budget($_POST)){
        $datapost = isset($_POST["tahun"]) ? $_POST["tahun"] : NULL;
        $alert = array("Success!", "Budget Perdepartment Periode ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Proses Budget Per Department</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrybudget">Entry Periode Budget</button>
                                    <div class="modal fade text-left" id="entrybudget" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data Periode Budget</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tahun Periode : </label>
                                                                <select id="tahun" name="tahun" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_thn = mysqli_query($conn, "SELECT * FROM periodebudget");
                                                                        while($data_thn = mysqli_fetch_assoc($query_thn)) {
                                                                    ?>
                                                                        <option value="<?= $data_thn['tahun_periode'];?>"><?= $data_thn['tahun_periode']; ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kantor : </label>
                                                                <select id="office" name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$office_id'");
                                                                        while($data_off = mysqli_fetch_assoc($query_off)) {
                                                                    ?>
                                                                        <option value="<?= $data_off['id_office'];?>"><?= $data_off['id_office']." - ".strtoupper($data_off['office_name']); ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Department : </label>
                                                                <select id="department" name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_dept = mysqli_query($conn, "SELECT id_department, department_name FROM department WHERE id_department = '$dept_id'");
                                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                                    ?>
                                                                        <option value="<?= $data_dept['id_department'];?>"><?= strtoupper($data_dept['department_name']); ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="insertdata" class="btn btn-outline-primary">Create</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>Tahun Periode</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($conn, "SELECT A.*, B.*, C.* FROM statusbudget AS A
                                INNER JOIN office AS B ON A.id_office = B.id_office
                                INNER JOIN department AS C ON A.id_department = C.id_department
                                WHERE A.id_office = '$office_id' AND A.id_department = '$dept_id'");
                                while($data = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr>
                                    <td><?= $tahun = $data['tahun_periode']; ?></td>
                                    <td><?= $data['id_office']." - ".strtoupper($data['office_name']); ?></td>
                                    <td><?= strtoupper($data['department_name']); ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['status_budget'] == 'Y' ? 'info' : 'danger'; ?> "><?= $data['status_budget'] == 'Y' ? 'Sudah Final' : 'Belum Final'; ?></div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger update_buatperiodebudget" title="Delete Periode Budget Tahun <?= $data['tahun_periode']; ?>" name="update_buatperiodebudget" id="<?= $data["id_sb"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalBuatPeriodeBudget" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                            <input type="hidden" id="id-delbuatbudget" name="idsb" class="form-control" readonly>
                                            <input type="hidden" id="tahun-delbuatbudget" name="tahun" class="form-control" readonly>
                                            <input type="hidden" id="office-delbuatbudget" name="office" class="form-control" readonly>
                                            <input type="hidden" id="dept-delbuatbudget" name="department" class="form-control" readonly>
                                            <label id="label-delbuatbudget"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.update_buatperiodebudget', function(){  
        var periode_budget = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{PERIODEBUDGETDEPT:periode_budget},  
            dataType:"json",  
            success:function(data){
                $('#id-delbuatbudget').val(data.id_sb);
                $('#tahun-delbuatbudget').val(data.tahun_periode);
                $('#office-delbuatbudget').val(data.id_office);
                $('#dept-delbuatbudget').val(data.id_department);
                
                $('#label-delbuatbudget').html("Delete Periode Budget Tahun : "+data.tahun_periode);
                $('#deleteModalBuatPeriodeBudget').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>