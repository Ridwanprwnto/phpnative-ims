<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];
$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$ext_id = $_GET['ext'];
$dec_ext = decrypt(rplplus($ext_id));
$enceid = encrypt($dec_ext);

$action_id = isset($_GET['id']) ? $_GET['id'] : NULL;
$dec_act = decrypt(rplplus($action_id));
$encaid = encrypt($dec_act);

$redirect = "index.php?page=$encpid&ext=$enceid&id=$encaid";

if(isset($_POST["insertdata"])){
    if(InsertTaskProject($_POST) > 0 ){
        $datapost = $_POST["urut-task"];
        $alert = array("Success!", "Task Project ".$datapost." berhasil di tambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateTaskProject($_POST) > 0 ){
        $datapost = $_POST["urut-task"];
        $alert = array("Success!", "Task Project ".$datapost." berhasil di update", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteTaskProject($_POST) > 0 ){
        $datapost = $_POST["urut-task"];
        $alert = array("Success!", "Task Project ".$datapost." berhasil di hapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Project Nomor : <?= $dec_act; ?></h4>
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
                    <button type="button" class="btn btn-primary btn-min-width" data-toggle="modal" data-target="#insert-task">Add Task</button>
                    <!-- Modal Add Item -->
                    <div class="modal fade text-left" id="insert-task">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white">Entry Data Task Project</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <input class="form-control" type="hidden" name="page-task" value="<?= $redirect; ?>" readonly>
                                            <input class="form-control" type="hidden" name="docno-task" value="<?= $dec_act; ?>" readonly>
                                            <input class="form-control" type="hidden" name="user-task" value="<?= $nik; ?>" readonly>
                                            <div class="col-md-4 mb-2">
                                                <label>Urutan Pengerjaan</label>
                                                <input type="number" name="urut-task" class="form-control" placeholder="Input nomor urutan pengerjaan">
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label>PIC / Pelaksana</label>
                                                <input type="text" name="pic-task" class="form-control" placeholder="Input tim bagian pelaksana">
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Pengerjaan</label>
                                                <textarea class="form-control" type="text" name="ket-task" placeholder="Input keterangan pengerjaan proyek"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Jumlah</label>
                                                <input type="number" name="jumlah-task" class="form-control" placeholder="Jumlah pengerjaan" required>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Kesulitan</label>
                                                <select type="text" name="kesulitan-task" class="select2 form-control block" style="width: 100%" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <option value="Low">Low</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="High">High</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="insertdata" class="btn btn-outline-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End -->
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center" id="tableUpdateTaskProject">
                            <thead>
                                <tr>
                                    <th scope="col">TAHAPAN</th>
                                    <th scope="col">PIC / PELAKSANA</th>
                                    <th scope="col">PENGERJAAN</th>
                                    <th scope="col">JUMLAH</th>
                                    <th scope="col">KESULITAN</th>
                                    <th scope="col">TGL PENGERJAAN</th>
                                    <th scope="col">KETERANGAN</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $sql = "SELECT A.*, B.username FROM project_task AS A
                                LEFT JOIN users AS B ON A.user_project_task = B.nik
                                WHERE A.ref_project_task = '$dec_act' ORDER BY A.urutan_project_task ASC";

                                $query = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr id="<?= $data["id_project_task"]; ?>" class="edit_tr">
                                    <td class="edit_td">
                                        <span id="uruttask_<?= $data["id_project_task"]; ?>" class="text"><?= $data["urutan_project_task"]; ?></span>
                                        <input type="number" value="<?= $data["urutan_project_task"]; ?>" class="form-control editbox" id="uruttask_input_<?= $data["id_project_task"]; ?>">
                                    </td>
                                    <td class="edit_td">
                                        <span id="pictask_<?= $data["id_project_task"]; ?>" class="text"><?= $data["pic_project_task"]; ?></span>
                                        <input type="text" value="<?= $data["pic_project_task"]; ?>" class="form-control editbox" id="pictask_input_<?= $data["id_project_task"]; ?>">
                                    </td>
                                    <td class="edit_td">
                                        <span id="kettask_<?= $data["id_project_task"]; ?>" class="text"><?= $data["pengerjaan_project_task"]; ?></span>
                                        <textarea type="text" value="<?= $data["pengerjaan_project_task"];?>" class="form-control editbox" id="kettask_input_<?= $data["id_project_task"];?>" placeholder="Input keterangan pengerjaan proyek"><?= $data["pengerjaan_project_task"]; ?></textarea>
                                    </td>
                                    <td class="edit_td">
                                        <span id="jumlahtask_<?= $data["id_project_task"]; ?>" class="text"><?= $data["jumlah_project_task"]; ?></span>
                                        <input type="number" value="<?= $data["jumlah_project_task"]; ?>" class="form-control editbox" id="jumlahtask_input_<?= $data["id_project_task"]; ?>">
                                    </td>
                                    <td class="edit_td">
                                        <div class="text" id="sulittask_<?= $data["id_project_task"]; ?>"><?= strtoupper($data['priority_project_task']);?></div>
                                        <select type="text" class="form-control editbox" id="sulittask_input_<?= $data["id_project_task"];?>">
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="Low" <?= $data['priority_project_task'] == 'Low' ? 'selected' : ''; ?>>LOW</option>
                                            <option value="Medium" <?= $data['priority_project_task'] == 'Medium' ? 'selected' : ''; ?>>MEDIUM</option>
                                            <option value="High" <?= $data['priority_project_task'] == 'High' ? 'selected' : ''; ?>>HIGH</option>
                                        </select>
                                </td>
                                    <td><?= isset($data["efektif_project_task"]) ? $data["efektif_project_task"] : '-';?></td>
                                    <td><?= isset($data["ket_project_task"]) ? $data["ket_project_task"] : '-';?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['status_project_task'] == 'Y' ? 'success' : 'warning'; ?> "><?= $data['status_project_task'] == 'Y' ? 'SELESAI' : 'PROSES'; ?></div>
                                    </td>
                                    <td>
                                        <button type="button" id="<?= $data['id_project_task']; ?>" name="update_task_project" title="Update Task : <?= $data['urutan_project_task']; ?>" class="btn btn-icon btn-success update_task_project" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        <button type="button" id="<?= $data['id_project_task']; ?>" name="delete_task_project" title="Delete Task : <?= $data['urutan_project_task']; ?>" class="btn btn-icon btn-danger delete_task_project" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="modalUpdateTaskProject" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="upd-labeltask"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="user-task" value="<?= $usernik;?>" readonly>
                                                <input class="form-control" type="hidden" id="upd-idtask" name="id-task" readonly>
                                                <input class="form-control" type="hidden" id="upd-notask" name="no-task" readonly>
                                                <input class="form-control" type="hidden" id="upd-uruttask" name="urut-task" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tgl Pengerjaan : </label>
                                                    <input class="form-control" type="date" id="upd-tgltask" name="tgl-task" max="<?=date('Y-m-d')?>" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" id="upd-kettask" name= "ket-task" type="text" placeholder="Keterangan pengerjaan proyek" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn grey btn-outline-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="modalDeleteTaskProject" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input class="form-control" type="hidden" name="page-task" value="<?= $redirect; ?>" readonly>
                                            <input class="form-control" type="hidden" id="del-idtask" name="id-task" readonly>
                                            <input class="form-control" type="hidden" id="del-notask" name="no-task" readonly>
                                            <input class="form-control" type="hidden" id="del-uruttask" name="urut-task" readonly>
                                            <label id="del-labeltask"></label>
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
                    <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary ml-2 mt-1 mb-2">
                        <i class="ft-chevrons-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>

$(document).ready(function() {
    $(".edit_tr").click(function() {
    var ID = $(this).attr('id');
    $("#uruttask_"+ID).hide();
    $("#uruttask_input_"+ID).show();
    $("#pictask_"+ID).hide();
    $("#pictask_input_"+ID).show();
    $("#kettask_"+ID).hide();
    $("#kettask_input_"+ID).show();
    $("#jumlahtask_"+ID).hide();
    $("#jumlahtask_input_"+ID).show();
    $("#sulittask_"+ID).hide();
    $("#sulittask_input_"+ID).show();
    }).change(function() {
        var ID = $(this).attr('id');
        var urut_task = $("#uruttask_input_"+ID).val();
        var pic_task = $("#pictask_input_"+ID).val();
        var ket_task = $("#kettask_input_"+ID).val();
        var jumlah_task = $("#jumlahtask_input_"+ID).val();
        var sulit_task = $("#sulittask_input_"+ID).val();
        if(urut_task > 0 || pic_task.lenght > 0 || ket_task.lenght > 0 || jumlah_task > 0 || sulit_task > 0) {
            $.ajax({
                type: "POST",
                url: "action/datarequest.php",
                data: {IDTASK:ID, URUTTASK:urut_task, PICTASK:pic_task, KETTASK:ket_task, JUMLAHTASK:jumlah_task, SULITTASK:sulit_task},
                cache: false,
                success: function(html) {
                    $("#uruttask_"+ID).html(urut_task);
                    $("#pictask_"+ID).html(pic_task);
                    $("#kettask_"+ID).html(ket_task);
                    $("#jumlahtask_"+ID).html(jumlah_task);
                    $("#sulittask_"+ID).html(sulit_task);
                    toastr.success('Data ID Task '+ ID +' berhasil di update!', 'Task Project');
                }
            });
        }   
        // else {
        //     alert('Data tidak boleh kosong!');
        // }
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

$(document).ready(function(){
    $(document).on('click', '.update_task_project', function(){
        var id_project = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONUPDATEPROJECT:id_project},  
            dataType:"json",  
            success:function(data){
                $('#upd-idtask').val(data.id_project_task);
                $('#upd-notask').val(data.ref_project_task);
                $('#upd-uruttask').val(data.urutan_project_task);
                $('#upd-tgltask').val(data.efektif_project_task);
                $('#upd-kettask').val(data.ket_project_task);

                $('#upd-labeltask').html("Update Task Project : "+data.urutan_project_task);
                $('#modalUpdateTaskProject').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_task_project', function(){
        var id_project = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONUPDATEPROJECT:id_project},  
            dataType:"json",  
            success:function(data){
                $('#del-idtask').val(data.id_project_task);
                $('#del-notask').val(data.ref_project_task);
                $('#del-uruttask').val(data.urutan_project_task);

                $('#del-labeltask').html("Delete Task Project : "+data.urutan_project_task);
                $('#modalDeleteTaskProject').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
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
});
</script>

<?php
    include ("includes/templates/alert.php");
?>