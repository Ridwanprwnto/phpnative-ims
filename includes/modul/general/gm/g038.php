<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

if (isset($_SESSION['ALERT'])) {
    $alert = $_SESSION["ALERT"];
    unset($_SESSION['ALERT']);
}

$_SESSION['KKSOAPAR'] = $_POST;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdata"])){
    if(InsertKKSO_Apar($_POST) > 0 ){
        $datapost = $_POST["no-so"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil di draft", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteKKSO_Apar($_POST) > 0 ){
        $datapost = $_POST["del-noso"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil di batalkan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<!-- Basic form layout section start -->
<section id="basic-select2">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Stock Opname Apar</h4>
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
                        <button type="button" class="btn btn-primary square btn-min-width ml-1 mb-2" data-toggle="modal" data-target="#insert-kkso">Draft SO</button>
                        <!-- Modal Insert -->
                        <div class="modal fade text-left" id="insert-kkso" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="" method="post" enctype="multipart/form-data" role="form">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white">Entry Data Draft SO</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-so" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <?php $id = autonum(6, "id_head_so_apar", "head_so_apar"); ?>
                                                    <label>NO SO : </label>
                                                    <input type="text" name="no-so" value="<?= $id; ?>" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Petugas : </label>
                                                    <input type="text" name="petugas-so" value="<?= $nik." - ".strtoupper($username);?>"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Office : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="office-so" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$idoffice'");
                                                        while($data_off = mysqli_fetch_assoc($query_off)) {
                                                        ?>
                                                        <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Department : </label>
                                                    <select class="select2 form-control block" style="width: 100%"
                                                        type="text" name="dept-so" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$iddept'");
                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                        ?>
                                                        <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertdata" class="btn btn-outline-primary">Draft SO</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered zero-configuration text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO REF SO</th>
                                        <th>TANGGAL</th>
                                        <th>PETUGAS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no = 1;
                                    $result = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.username FROM head_so_apar AS A 
                                    INNER JOIN office AS B ON A.office_head_so_apar = B.id_office
                                    INNER JOIN department AS C ON A.dept_head_so_apar = C.id_department
                                    INNER JOIN users AS D ON A.user_head_so_apar = D.nik
                                    WHERE A.office_head_so_apar = '$idoffice' AND A.dept_head_so_apar = '$iddept' AND A.status_head_so_apar = 'N' GROUP BY A.id_head_so_apar";
                                    $query = mysqli_query($conn, $result);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $data["id_head_so_apar"];?></td>
                                        <td><?= $data["date_head_so_apar"];?></td>
                                        <td><?= $data["user_head_so_apar"]." - ".strtoupper($data["username"]);?></td>
                                        <td>
                                        <span class="dropdown">
                                            <button id="action<?= $data['id_head_so_apar']; ?>" type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="action<?= $data['id_head_so_apar']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                            <a href="index.php?page=<?= $encpid;?>&ext=<?= encrypt($arrextmenu[7]);?>&id=<?= encrypt($data["id_head_so_apar"]); ?>" title="Input Data SO Nomor <?= $data["id_head_so_apar"]; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-edit"></i>Input SO</a>
                                            <a href="reporting/report-kkso-apar.php?id=<?= encrypt($data['id_head_so_apar']);?>" title="Print KKSO Nomor <?= $data["id_head_so_apar"]; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print KKSO</a>
                                            <a href="#" class="dropdown-item delete_draftso" title="Delete Data SO Nomor <?= $data["id_head_so_apar"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_draftso" id="<?= $data['id_head_so_apar']; ?>"><i class="ft-delete"></i>Batalkan SO</a>
                                            </span>
                                        </span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <!-- Modal Delete -->
                                <div class="modal fade text-left" id="modalDeleteSOApar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" id="del-nosoapar" name="del-noso" readonly>
                                                    <label id="del-labelsoapar"></label>
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
        <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function(){
    $(document).on('click', '.delete_draftso', function(){  
        var nomor_so = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEDATASOAPAR:nomor_so},  
            dataType:"json",  
            success:function(data){
                $('#del-nosoapar').val(data.id_head_so_apar);
                $('#del-labelsoapar').html("Nomor SO : "+data.id_head_so_apar);
                $('#modalDeleteSOApar').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>