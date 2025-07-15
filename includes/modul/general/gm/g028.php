<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertusertelegram"])){
    if(InsertTelebot($_POST) > 0 ){
        $datapost = isset($_POST["nik-insusertelebot"]) ? $_POST["nik-insusertelebot"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Penerima Notifikasi Telegram Bot Berhasil Didaftarkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updateusertelegram"])){
    if(UpdateTelebot($_POST) > 0 ){
        $datapost = isset($_POST["nik-updusertelebot"]) ? $_POST["nik-updusertelebot"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Penerima Notifikasi Telegram Bot Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deleteusertelegram"])){
    if(DeleteTelebot($_POST) > 0 ){
        $datapost = isset($_POST["nik-delusertelebot"]) ? $_POST["nik-delusertelebot"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Penerima Notifikasi Telegram Bot Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Entry Data Users Telegram</h4>
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
                        <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#entryusertelegram">Entry User Telegram</button>
                        <div class="modal fade text-left" id="entryusertelegram" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel">Entry Data User Telegram</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-insusertelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-insusertelebot" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Role Transaksi : </label>
                                                    <select name="role-insusertelebot" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_role_trans = mysqli_query($conn, "SELECT no_role_trans, inisial_role_trans, name_role_trans FROM role_transaksi");
                                                            while($data_role_trans = mysqli_fetch_assoc($query_role_trans)) { ?>
                                                            <option value="<?= $data_role_trans['inisial_role_trans']; ?>" ><?= $data_role_trans['no_role_trans']." - ".strtoupper($data_role_trans['name_role_trans']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Head Divisi : </label>
                                                    <select name="div-insusertelebot" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_div = mysqli_query($conn, "SELECT * FROM head_divisi");
                                                            while($data_div = mysqli_fetch_assoc($query_div)) { ?>
                                                            <option value="<?= $data_div['id_head_div']; ?>" ><?= $data_div['id_head_div']." - ".$data_div['name_head_div'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>ID Telegram : </label>
                                                    <input type="text" name="id-insusertelebot" placeholder="Input ID Telegram" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>NIK - Username : </label>
                                                    <select name="nik-insusertelebot" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_user_p = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_department = '$iddept' AND id_group NOT LIKE 'GP01'");
                                                            while($data_user_p = mysqli_fetch_assoc($query_user_p)) { ?>
                                                            <option value="<?= $data_user_p['nik']; ?>" ><?= $data_user_p['nik']." - ".strtoupper($data_user_p['username']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Status : </label>
                                                    <select name="status-insusertelebot" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="Y" >Aktif</option>
                                                        <option value="N" >Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertusertelegram" class="btn btn-outline-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Role Transaksi</th>
                                    <th>ID Telegram</th>
                                    <th>Head Divisi</th>
                                    <th>NIK - Username</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $sql_usertele = "SELECT A.*, B.no_role_trans, B.inisial_role_trans, B.name_role_trans, C.username, D.* FROM user_telebot AS A
                                INNER JOIN role_transaksi AS B ON A.role_user_tele = B.inisial_role_trans
                                INNER JOIN users AS C ON A.nik_user_tele = C.nik
                                INNER JOIN head_divisi AS D ON A.div_user_tele = D.id_head_div
                                WHERE A.office_user_tele = '$idoffice' ORDER BY A.nik_user_tele ASC";
                                $query_usertele = mysqli_query($conn, $sql_usertele);
                                while($data_usertele = mysqli_fetch_assoc($query_usertele)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data_usertele['no_role_trans']." - ".$data_usertele['name_role_trans']; ?></td>
                                    <td><?= $data_usertele['no_user_tele']; ?></td>
                                    <td><?= $data_usertele['name_head_div']; ?></td>
                                    <td><?= $data_usertele['nik_user_tele']." - ".strtoupper($data_usertele['username']); ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data_usertele['status_user_tele'] == "N" ? "danger" : "info"; ?> "><?= $data_usertele['status_user_tele'] == "N" ? "Nonaktif" : "Aktif"; ?></div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success update_usertelebot" title="Update User ID <?= $data_usertele["no_user_tele"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_usertelebot" id="<?= $data_usertele["id_user_tele"]; ?>"><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger delete_usertelebot" title="Delete User ID <?= $data_usertele["no_user_tele"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_usertelebot" id="<?= $data_usertele["id_user_tele"]; ?>"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalUserTelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white">Update Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-delusertelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <input type="hidden" id="id-updusertelebot" name="id-updusertelebot" class="form-control" readonly>
                                                <input type="hidden" id="nik-updusertelebot" name="nik-updusertelebot" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Status : </label>
                                                    <select id="status-updusertelebot" name="status-updusertelebot" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="Y" >Aktif</option>
                                                        <option value="N" >Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updateusertelegram" class="btn btn-outline-success">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalUserTelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                            <input type="hidden" name="page-delusertelebot" value="<?= $encpid; ?>" class="form-control" readonly>
                                            <input type="hidden" id="id-delusertelebot" name="id-delusertelebot" class="form-control" readonly>
                                            <input type="hidden" id="nik-delusertelebot" name="nik-delusertelebot" class="form-control" readonly>
                                            <label id="label-delusertelebot"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deleteusertelegram" class="btn btn-outline-danger">Yes</button>
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
    $(document).on('click', '.update_usertelebot', function(){  
        var idusertele = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIUSERTELEBOT:idusertele},  
            dataType:"json",  
            success:function(data){
                $('#id-updusertelebot').val(data.id_user_tele);
                $('#nik-updusertelebot').val(data.nik_user_tele);
                $('#status-updusertelebot').find('option[value="'+data.status_user_tele+'"]').remove();
                
                if (data.status_user_tele == "Y") {
                    var $name_sts = "Aktif";
                }
                else {
                    var $name_sts = "Nonaktif";
                }
                
                $('#status-updusertelebot').append($('<option></option>').html($name_sts).attr('value', data.status_user_tele).prop('selected', true));

                $('#updateModalUserTelebot').modal('show');
            }  
        });
    });
});
$(document).ready(function(){
    $(document).on('click', '.delete_usertelebot', function(){  
        var idusertele = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIUSERTELEBOT:idusertele},  
            dataType:"json",  
            success:function(data){
                $('#id-delusertelebot').val(data.id_user_tele);
                $('#nik-delusertelebot').val(data.nik_user_tele);
                
                $('#label-delusertelebot').html("Delete Data User ID "+data.no_user_tele);

                $('#deleteModalUserTelebot').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>