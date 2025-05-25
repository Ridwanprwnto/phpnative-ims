<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insertgrandchildmenu($_POST) > 0 ){
        $alert_success_insert = "<strong>Success!</strong> Data menu group berhasil di tambahkan!";
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(updategrandchildmenu($_POST) > 0 ){
        $alert_success_update = "<strong>Success!</strong> Data menu group berhasil di rubah!";
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deletegrandchildmenu($_POST)){
        $alert_success_delete = "<strong>Success!</strong> Data menu group berhasil di hapus!";
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
                    <h4 class="card-title">Grand Child Menu</h4>
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
                                        data-toggle="modal" data-target="#entrypm">Entry Grand Child Menu</button>
                                    <div class="modal fade text-left" id="entrypm" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data Menu</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="gmid" value="<?= autoid('1', '3', 'id_grandchildmenu', 'grandchildmenu'); ?>">
                                                            <div class="col-md-12 mb-2">
                                                                <label for="cmid">Parent Menu - Child Menu</label>
                                                                <select id="cmid" name="cmid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_pm = mysqli_query($conn, "SELECT parentmenu.*, childmenu.* FROM childmenu
                                                                    INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu");
                                                                    while($data_pm = mysqli_fetch_assoc($sql_pm)) {
                                                                ?>
                                                                    <option value="<?= $data_pm['id_childmenu'];?>" ><?= $data_pm['parentmenu_name']." - ".$data_pm['childmenu_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Grand Child Menu Name : </label>
                                                                <input type="text" name="gmname" placeholder="Grand Child Menu Name" class="form-control" required>
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
                            <?php
                            if (isset($ins_gmname)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $ins_gmname; ?>
                                    </div>
                                    <?php
                            }
                            elseif (isset($error_delete)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $error_delete; ?>
                                    </div>
                                <?php
                            }
                            elseif (isset($alert_success_insert)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_insert; ?>
                                    </div>
                                <?php
                            }
                            elseif (isset($alert_success_delete)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_delete; ?>
                                    </div>
                                <?php
                            }
                            elseif (isset($alert_success_update)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_update; ?>
                                    </div>
                                <?php
                            }
                            ?>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>Parent Menu Name</th>
                                    <th>Child Menu Name</th>
                                    <th>ID Grand Child Menu</th>
                                    <th>Grand Child Menu Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $query = mysqli_query($conn, "SELECT childmenu.*, parentmenu.*, grandchildmenu.* FROM childmenu
                            INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu
                            INNER JOIN grandchildmenu ON childmenu.id_childmenu = grandchildmenu.id_childmenu");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['parentmenu_name']; ?></td>
                                    <td><?= $data['childmenu_name']; ?></td>   
                                    <td><?= $data['id_grandchildmenu']; ?></td>
                                    <td><?= $data['grandchildmenu_name']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success" data-toggle="modal" data-target="#update<?= $data['id_grandchildmenu']; ?>"><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data['id_grandchildmenu']; ?>"><i class="ft-delete"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id_grandchildmenu']; ?>"
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
                                                    <input class="form-control" type="hidden" name="gmid" value="<?= $data['id_grandchildmenu']; ?>">
                                                        <label>Parent Menu - Child Menu Name : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="cmname" class="form-control" value="<?= $data['parentmenu_name']." - ".$data['childmenu_name']; ?>" disabled>
                                                        </div>
                                                        <label>Grand Child Menu Name : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="gmname" class="form-control" value="<?= $data['grandchildmenu_name']; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedata"
                                                            class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="delete<?= $data['id_grandchildmenu']; ?>" role="dialog"
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
                                                    <input type="hidden" name="gmid" value="<?= $data['id_grandchildmenu']; ?>">
                                                    <p>Are you sure to delete ID : <?= $data['id_grandchildmenu']; ?>
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