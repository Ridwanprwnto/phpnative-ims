<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertPeriodeTahunan($_POST) > 0 ){
        $datapost = $_POST["tahun"];
        $alert = array("Success!", "Data Tahun ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdatePeriodeTahunan($_POST) > 0 ){
        $datapost = $_POST["tahun"];
        $alert = array("Success!", "Data Tahun ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeletePeriodeTahunan($_POST)){
        $datapost = $_POST["tahun"];
        $alert = array("Success!", "Data Tahun ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Entry Periode Tahunan</h4>
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
                                        data-toggle="modal" data-target="#entrytahun">Entry Tahun</button>
                                    <div class="modal fade text-left" id="entrytahun" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data Tahun</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                        <label>Tahun : </label>
                                                        <div class="form-group">
                                                            <input type="number" name="tahun" placeholder="Peruntukan Tahun Budget" class="form-control" required>
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
                                    <th>No</th>
                                    <th>Periode Tahun</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM periodebudget ");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['tahun_periode']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success"><i class="ft-edit" data-toggle="modal" data-target="#update<?= $data['id_tahun']; ?>"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger"><i class="ft-delete" data-toggle="modal" data-target="#delete<?= $data['id_tahun']; ?>"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id_tahun']; ?>"
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Changes Data</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input class="form-control" type="hidden" name="idtahun" value="<?= $data['id_tahun']; ?>">
                                                        <label>Tahun : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="tahun" placeholder="Peruntukan Tahun Budget" class="form-control" value="<?= $data['tahun_periode']; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedata"
                                                            class="btn btn-outline-success">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="delete<?= $data['id_tahun']; ?>" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" name="idtahun" value="<?= $data['id_tahun']; ?>">
                                                    <input type="hidden" name="tahun" value="<?= $data['tahun_periode']; ?>">
                                                    <p>Are you sure to delete tahun periode : <?= $data['tahun_periode']; ?>
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