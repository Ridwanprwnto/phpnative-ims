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

    $idoffice = $datauser['id_office'];
    $iddept = $datauser['id_department'];
    $iddiv = $datauser['id_divisi'];
    $idlvl = $datauser['id_level'];

    $sql_leader = mysqli_query($conn, "SELECT nik_lead_user, name_lead_user FROM leader_users WHERE nik_lead_user = '$nik'");
    $data_leader = mysqli_fetch_assoc($sql_leader);
    
    if(isset($_POST["update"])){
        if(updateprofile($_POST) > 0 ){
            $datapost = isset($_POST["nik"]) ? $_POST["nik"] : NULL;
            $alert = array("Success!", "Data Profile NIK ".$datapost." berhasil dirubah", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Profile Info</h4>
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
                        <form action="" method="post" enctype="multipart/form-data" role="form" class="form form-horizontal form-bordered">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-user"></i> Employee Info</h4>
                                <input type="hidden" class="form-control" name="page" value="<?= $encpid; ?>" readonly>
                                <input type="hidden" class="form-control" name="nik" value="<?= $datauser['nik']; ?>" readonly>
                                <input type="hidden" class="form-control" name="office" value="<?= $idoffice; ?>" readonly>
                                <input type="hidden" class="form-control" name="department" value="<?= $iddept; ?>" readonly>
                                <input type="hidden" class="form-control" name="divisi" value="<?= $iddiv; ?>" readonly>
                                <input type="hidden" class="form-control" name="level" value="<?= $idlvl; ?>" readonly>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="usernik">NIK</label>
                                    <div class="col-md-9">
                                        <input type="text" id="usernik" class="form-control" name="usernik" value="<?= $datauser['nik'];?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="office">Office</label>
                                    <div class="col-md-9">
                                        <input type="text" id="office" class="form-control" name="office" value="<?= $datauser["id_office"]." - ".$datauser['office_name'];?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="department">Department</label>
                                    <div class="col-md-9">
                                        <input type="text" id="department" class="form-control" name="department" value="<?= $datauser['department_name'];?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="divisi">Divisi</label>
                                    <div class="col-md-9">
                                        <input type="text" id="divisi" class="form-control" name="divisi" value="<?= $datauser['divisi_name'];?>" disabled>
                                    </div>
                                </div>
                                <?php
                                if ($idlvl == $arrlvl[3]) { ?>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="leader">Leader</label>
                                    <div class="col-md-9">
                                        <input type="text" id="leader" class="form-control" name="leader" value="<?= isset($data_leader["name_lead_user"]) ? $data_leader["name_lead_user"] : '-'; ?>" disabled>
                                    </div>
                                    <label class="col-md-3 label-control" for="leader"></label>
                                    <div class="col-md-9">
                                        <select name="leader[]" class="select2 form-control" multiple="multiple" style="width: 100%" data-placeholder="Please Select" type="text">
                                            <option value="DELETE">DELETE</option>
                                            <?php 
                                                $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_department = '$iddept' AND id_divisi = '$iddiv' AND id_level = '$arrlvl[2]' AND id_group NOT LIKE 'GP01' AND nik NOT LIKE '$id' ORDER BY nik ASC");
                                                while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                <option value="<?= $data_user['nik']." - ".strtoupper($data_user['username']); ?>"><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="fullname">Full Name</label>
                                    <div class="col-md-9">
                                        <input type="text" id="fullname" class="form-control"
                                            placeholder="Full Name" name="fullname" value="<?= $datauser['full_name']; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="email">E-mail</label>
                                    <div class="col-md-9">
                                        <input type="text" id="email" class="form-control" placeholder="E-mail" name="email" value="<?= $datauser['email']; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                <label class="col-md-3 label-control" for="birthday">Birthday</label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                    <input type="date" id="birthday" class="form-control" name="birthday" value="<?= $datauser['tgl_lahir']; ?>">
                                    <div class="form-control-position">
                                        <i class="ft-message-square"></i>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 label-control" for="gender">Gender</label>
                                    <div class="col-md-9">
                                        <select id="gender" name="gender" class="select2 form-control block" style="width: 100%" data-placeholder="Please Select" type="text">
                                        <option value="" selected disabled>Please Select</option>
                                        <?php
                                            $gender = array('L', 'P');
                                            foreach ($gender as $g) {
                                        ?>
                                            <option value="<?= $g; ?>" <?= $datauser['gender'] == $g ? 'selected' : ''; ?>><?= $g == "L" ? 'Laki-Laki' : 'Perempuan'; ?></option>
                                        <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <input class="form-control" type="hidden" id="oldfoto" name="oldfoto" value="<?= $datauser['foto']; ?>">
                                <div class="form-group row">
                                    <label class="col-md-3 label-control">Photo</label>
                                    <div class="col-md-9">
                                        <label id="file" class="file center-block">
                                            <input type="file" name="foto" id="file">
                                            <span class="file-custom"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateprofile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel1">Changes Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah data profile anda sudah sesuai untuk dilakukan perubahan?
                                            </p>
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
                            <div class="form-actions">
                                <a href="index.php" class="btn btn-secondary mr-1">
                                    <i class="ft-x"></i> Back
                                </a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateprofile">
                                    <i class="la la-check-square-o"></i> Save
                                </button>
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