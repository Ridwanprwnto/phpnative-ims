<?php
$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertheaddivisi"])){
    if(InsertHeadDivisi($_POST) > 0 ){
        $alert = array("Success!", "Data Head Divisi Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updateheaddivisi"])){
    if(UpdateHeadDivisi($_POST) > 0 ){
        $alert = array("Success!", "Data Head Divisi Berhasil Dirubah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deleteheaddivisi"])){
    if(DeleteHeadDivisi($_POST) > 0 ){
        $alert = array("Success!", "Data Head Divisi Berhasil Dihapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdata"])){
    if(insertdivisi($_POST) > 0 ){
        $alert = array("Success!", "Data Divisi Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(updatedivisi($_POST) > 0 ){
        $alert = array("Success!", "Data Divisi Berhasil Dirubah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deletedivisi($_POST)){
        $alert = array("Success!", "Data Divisi Berhasil Dihapus", "success", "$redirect");
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
                    <h4 class="card-title">Struktur Divisi</h4>
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
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="master-headiv" data-toggle="tab" href="#masterheadiv" aria-expanded="true">Head Divisi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="master-div" data-toggle="tab" href="#masterdiv" aria-expanded="false">Divisi</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="masterheadiv" aria-expanded="true" aria-labelledby="master-headiv">
                            <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryheaddivisi">Entry Head Divisi</button>
                                            <div class="modal fade text-left" id="entryheaddivisi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Head Divisi</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="ins-headivpage" value="<?= $redirect; ?>" class="form-control" readonly>
                                                                <label>Head Divisi Name : </label>
                                                                <div class="form-group">
                                                                    <input type="text" name="ins-headivname" placeholder="Head Divisi Name" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertheaddivisi" class="btn btn-outline-primary">Save</button>
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
                                            <th>ID Head Divisi</th>
                                            <th>Head Divisi Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query_headiv = mysqli_query($conn, "SELECT * FROM head_divisi");
                                    while($data_headiv = mysqli_fetch_assoc($query_headiv)) {
                                    ?>
                                        <tr>
                                            <td><?= $data_headiv['id_head_div']; ?></td>
                                            <td><?= $data_headiv['name_head_div']; ?></td>
                                            <td>
                                                <!-- Icon Button dropdowns -->
                                                <div class="btn-group mb-1">
                                                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item update_headiv" href="#" title="Edit Head Divisi <?= $data_headiv['id_head_div']; ?>" name="update_headiv" id="<?= $data_headiv["id_head_div"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Head Divisi</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_headiv" href="#" title="Hapus Head Divisi <?= $data_headiv['id_head_div']; ?>" name="delete_headiv" id="<?= $data_headiv["id_head_div"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Head Divisi</a>
                                                    </div>
                                                </div>
                                                <!-- /btn-group -->
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalHeadiv" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="upd-headivlabel"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input class="form-control" type="hidden" name="upd-headivpage" value="<?= $redirect; ?>" readonly>
                                                        <input class="form-control" type="hidden" id="upd-headivid" name="upd-headivid" readonly>
                                                        <label>Head Divisi Name : </label>
                                                        <div class="form-group">
                                                            <input type="text" id="upd-headivname" name="upd-headivname" placeholder="Head Divisi Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updateheaddivisi" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalHeadiv" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" name="del-headivpage" value="<?= $redirect; ?>" readonly>
                                                    <input class="form-control" type="hidden" id="del-headivid" name="del-headivid" readonly>
                                                    <label id="del-headivlabel"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deleteheaddivisi" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="masterdiv" aria-labelledby="master-div">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1"
                                                data-toggle="modal" data-target="#entrydivisi">Entry Divisi</button>
                                            <div class="modal fade text-left" id="entrydivisi" role="dialog"
                                                aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Divisi</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="divisipage" value="<?= $redirect; ?>" class="form-control" readonly>
                                                                <div class="form-group">
                                                                    <label>Head Divisi : </label>
                                                                    <select name="idheaddivisi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_ins_headiv = mysqli_query($conn, "SELECT * FROM head_divisi");
                                                                        while($data_ins_headiv = mysqli_fetch_assoc($query_ins_headiv)) {
                                                                    ?>
                                                                        <option value="<?= $data_ins_headiv['id_head_div']; ?>"><?= $data_ins_headiv['name_head_div']; ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                    </select>
                                                                </div>
                                                                <label>Divisi Name : </label>
                                                                <div class="form-group">
                                                                    <input type="text" name="divisiname" placeholder="Divisi Name" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdata"
                                                                    class="btn btn-outline-primary">Save</button>
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
                                            <th>Head Divisi</th>
                                            <th>ID - Divisi Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT A.*, B.* FROM divisi AS A
                                            INNER JOIN head_divisi AS B ON A.id_head_divisi = B.id_head_div ORDER BY B.name_head_div ASC");
                                            while($data = mysqli_fetch_assoc($query)) {
                                            ?>
                                        <tr>
                                            <td><?= $data['name_head_div']; ?></td>
                                            <td><?= $data['id_divisi']." - ".$data['divisi_name']; ?></td>
                                            <td>
                                                <!-- Icon Button dropdowns -->
                                                <div class="btn-group mb-1">
                                                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item update_divisi" href="#" title="Edit Divisi <?= $data['id_divisi']; ?>" name="update_divisi" id="<?= $data["id_divisi"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Divisi</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_divisi" href="#" title="Hapus Divisi <?= $data['id_divisi']; ?>" name="delete_divisi" id="<?= $data["id_divisi"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Divisi</a>
                                                    </div>
                                                </div>
                                                <!-- /btn-group -->
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalDivisi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="upd-labeldiv"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input class="form-control" type="hidden" name="upd-divisipage" value="<?= $redirect; ?>" readonly>
                                                        <input class="form-control" type="hidden" id="upd-iddiv" name="iddivold" readonly>
                                                        <label>Divisi Name : </label>
                                                        <div class="form-group">
                                                            <input type="text" id="upd-namediv" name="divisiname" placeholder="Divisi Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedata" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalDivisi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" name="del-divisipage" value="<?= $redirect; ?>" readonly>
                                                    <input class="form-control" type="hidden" id="del-iddiv" name="iddiv" readonly>
                                                    <label id="del-labeldiv"></label>
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
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.update_headiv', function(){  
        var id_div = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONHEADIV:id_div},  
            dataType:"json",  
            success:function(data){
                $('#upd-headivid').val(data.id_head_div);
                $('#upd-headivname').val(data.name_head_div);
                
                $('#upd-headivlabel').html("Edit Data Head Divisi "+data.id_head_div+" - "+data.name_head_div);
                $('#updateModalHeadiv').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_headiv', function(){  
        var id_div = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONHEADIV:id_div},  
            dataType:"json",  
            success:function(data){
                $('#del-headivid').val(data.id_head_div);
                
                $('#del-headivlabel').html("Head Divisi "+data.id_head_div+" - "+data.name_head_div);
                $('#deleteModalHeadiv').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_divisi', function(){  
        var id_div = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONDIV:id_div},  
            dataType:"json",  
            success:function(data){
                $('#upd-iddiv').val(data.id_divisi);
                $('#upd-namediv').val(data.divisi_name);
                
                $('#upd-labeldiv').html("Edit Data Divisi "+data.id_divisi+" - "+data.divisi_name);
                $('#updateModalDivisi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_divisi', function(){  
        var id_div = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONDIV:id_div},  
            dataType:"json",  
            success:function(data){
                $('#del-iddiv').val(data.id_divisi);
                
                $('#del-labeldiv').html("Divisi "+data.id_divisi+" - "+data.divisi_name);
                $('#deleteModalDivisi').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>