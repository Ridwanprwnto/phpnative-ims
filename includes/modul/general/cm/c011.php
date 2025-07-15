<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insertsubdivisi($_POST) > 0 ){
        $datapost = $_POST["subdivisiname"];
        $alert = array("Success!", "Data Sub Divisi ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deletesubdivisi($_POST)){
        $datapost = $_POST["idsubdiv"];
        $alert = array("Success!", "Data Sub Divisi ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Tabel Master Sub Divisi</h4>
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
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1"
                                        data-toggle="modal" data-target="#entrysubdivisi">Entry Sub Divisi</button>
                                    <div class="modal fade text-left" id="entrysubdivisi" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data Sub Divisi</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label for="pmid">Divisi Name</label>
                                                                <select type="text" name="divisiid" class="select2 form-control block" style="width: 100%">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_dv = mysqli_query($conn, "SELECT * FROM divisi");
                                                                  while($data_dv = mysqli_fetch_assoc($sql_dv)) {
                                                                ?>
                                                                <option value="<?= $data_dv['id_divisi'];?>" ><?= $data_dv['id_divisi'].' - '.$data_dv['divisi_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Sub Divisi Name : </label>
                                                                <input type="text" name="subdivisiname" placeholder="Sub Divisi Name" class="form-control" required>
                                                            </div>
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
                                    <th>ID Divisi</th>
                                    <th>Divisi Name</th>
                                    <th>Sub Divisi Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $query = mysqli_query($conn, "SELECT sub_divisi.*, divisi.* FROM sub_divisi
                            INNER JOIN divisi ON divisi.id_divisi = sub_divisi.id_divisi");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['id_divisi']; ?></td>
                                    <td><?= $data['divisi_name']; ?></td>
                                    <td><?= $data['sub_divisi_name']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger mr-1"><i class="ft-delete"
                                                data-toggle="modal"
                                                data-target="#delete<?= $data['id_sub_divisi']; ?>"></i></button>
                                    </td>
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="delete<?= $data['id_sub_divisi']; ?>" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="idsubdiv" value="<?= $data['id_sub_divisi']; ?>">
                                                    <p>Are you sure to delete ID Sub Divisi : <?= $data['id_sub_divisi']; ?>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
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
    <?php
        if (isset($alert)) {
    ?>
        swal({
		    title: "<?= $alert[0]; ?>",
		    text: "<?= $alert[1]; ?>",
		    icon: "<?= $alert[2]; ?>",
		    buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		})
		.then((isConfirm) => {
		    if (isConfirm) {
                window.location.href = "<?= $alert[3]; ?>";
		    } else {
                window.location.href = "<?= $alert[3]; ?>";
		    }
		});
    <?php
        }
    ?>
});
</script>