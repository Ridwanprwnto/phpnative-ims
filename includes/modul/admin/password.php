<?php

    $page_id = $_GET['page'];
    $id = $_SESSION['user_nik'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    $users = mysqli_query($conn, "SELECT * FROM users
    INNER JOIN office ON users.id_office = office.id_office
    INNER JOIN department ON users.id_department = department.id_department
    INNER JOIN divisi ON users.id_divisi = divisi.id_divisi
    INNER JOIN groups ON users.id_group = groups.id_group
    WHERE nik = '$id' ");
    $datauser = mysqli_fetch_assoc($users);

    if(isset($_POST["update"])){
        if(updatepwd($_POST) > 0 ){
            $datapost = isset($_POST["nik"]) ? $_POST["nik"] : NULL;
            $alert = array("Success!", "Data Password NIK ".$datapost." berhasil dirubah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Change Password</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <form action="" method="post" class="form form-horizontal">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-edit-3"></i> Entry Password</h4>
                                <input type="hidden" class="form-control" name="page" value="<?= $encpid; ?>">
                                <input type="hidden" class="form-control" name="nik" value="<?= $datauser['nik'];?>" required>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="oldpassword">Old Password</label>
                                    <div class="col-md-9">
                                        <input type="password" id="oldpassword" class="form-control"
                                            placeholder="Input Password" name="oldpassword" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="password">New Password</label>
                                    <div class="col-md-9">
                                        <input type="password" id="password" class="form-control"
                                            placeholder="Input Password" name="password" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="repassword">Confirmation Password</label>
                                    <div class="col-md-9">
                                        <input type="password" id="repassword" class="form-control"
                                            placeholder="Input Password" name="repassword" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <a href="index.php" class="btn btn-secondary mr-1">
                                    <i class="ft-x"></i> Back
                                </a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changepass">
                                    <i class="la la-check-square-o"></i> Save
                                </button>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="changepass" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel1">Changes Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Apakah anda yakin ingin merubah password baru?</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="update" class="btn btn-outline-primary">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->

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