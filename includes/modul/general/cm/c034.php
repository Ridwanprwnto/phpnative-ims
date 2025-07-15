<?php

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertroletelegram"])){
    if(InsertRoleTransaksi($_POST) > 0 ){
        $datapost = isset($_POST["name-insroletelebot"]) ? $_POST["name-insroletelebot"] : NULL;
        $alert = array("Success!", "Role Transaksi ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updateroletelegram"])){
    if(UpdateRoleTransaksi($_POST) > 0 ){
        $datapost = isset($_POST["name-updroletelebot"]) ? $_POST["name-updroletelebot"] : NULL;
        $alert = array("Success!", "Role Transaksi ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deleteroletelegram"])){
    if(DeleteRoleTransaksi($_POST) > 0 ){
        $datapost = isset($_POST["name-delroletelebot"]) ? $_POST["name-delroletelebot"] : NULL;
        $alert = array("Success!", "Role Transaksi ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Entry Data Role Transaksi</h4>
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
                        <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#entryroletelegram">Entry Role Transaksi</button>
                        <div class="modal fade text-left" id="entryroletelegram" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel">Entry Data Role Transaksi</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-insroletelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Inisial Role Transaksi : </label>
                                                    <input type="text" name="initial-insroletelebot" placeholder="Inisial role transaksi 3 digit huruf" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nama Role Transaksi : </label>
                                                    <input type="text" name="name-insroletelebot" placeholder="Nama role transaksi" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertroletelegram" class="btn btn-outline-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>ID Role</th>
                                    <th>Inisial Role</th>
                                    <th>Name Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql_role = "SELECT * FROM role_transaksi";
                                $query_role = mysqli_query($conn, $sql_role);
                                while($data_role = mysqli_fetch_assoc($query_role)) {
                            ?>
                                <tr>
                                    <td><?= $data_role['no_role_trans']; ?></td>
                                    <td><?= $data_role['inisial_role_trans']; ?></td>
                                    <td><?= $data_role['name_role_trans']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success update_roletelebot" title="Update Role ID <?= $data_role["no_role_trans"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_roletelebot" id="<?= $data_role["id_role_trans"]; ?>"><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger delete_roletelebot" title="Delete Role ID <?= $data_role["no_role_trans"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_roletelebot" id="<?= $data_role["id_role_trans"]; ?>" <?= $id_group == $admin ? "" : "disabled"; ?>><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalRoleTelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="label-updroletelebot"></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="page-updroletelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <input type="hidden" id="id-updroletelebot" name="id-updroletelebot" class="form-control" readonly>
                                                <div class="form-group">
                                                    <label>Inisial Role Transaksi : </label>
                                                    <input type="text" id="initial-updroletelebot" name="initial-updroletelebot" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Role Transaksi : </label>
                                                    <input type="text" id="name-updroletelebot" name="name-updroletelebot" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="updateroletelegram" class="btn btn-outline-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalRoleTelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-delroletelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                            <input type="hidden" id="id-delroletelebot" name="id-delroletelebot" class="form-control" readonly>
                                            <input type="hidden" id="name-delroletelebot" name="name-delroletelebot" class="form-control" readonly>
                                            <label id="label-delroletelebot"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deleteroletelegram" class="btn btn-outline-danger">Yes</button>
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
    $(document).on('click', '.update_roletelebot', function(){  
        var IDrole = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIROLETRANSAKSI:IDrole},  
            dataType:"json",  
            success:function(data){
                $('#id-updroletelebot').val(data.id_role_trans);
                $('#initial-updroletelebot').val(data.inisial_role_trans);
                $('#name-updroletelebot').val(data.name_role_trans);
                
                $('#label-updroletelebot').html("Edit Data Role Transaksi "+data.no_role_trans);

                $('#updateModalRoleTelebot').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_roletelebot', function(){  
        var IDrole = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIROLETRANSAKSI:IDrole},  
            dataType:"json",  
            success:function(data){
                $('#id-delroletelebot').val(data.id_role_trans);
                $('#name-delroletelebot').val(data.name_role_trans);
                
                $('#label-delroletelebot').html("Delete Data Role Transaksi "+data.no_role_trans);

                $('#deleteModalRoleTelebot').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>