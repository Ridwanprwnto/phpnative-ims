<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["extendpm"])){
    if(extendparentmenu($_POST) > 0 ){
        $alert_success_insert = "<strong>Success!</strong> Data extend parentmenu berhasil di tambahkan!";
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["extendcm"])){
    if(extendchildmenu($_POST) > 0 ){
        $alert_success_update = "<strong>Success!</strong> Data extend childmenu berhasil di tambahkan!";
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["extendgm"])){
    if(extendgrandchildmenu($_POST) > 0 ){
        $alert_success_extend = "<strong>Success!</strong> Data extend grandchildmenu berhasil di tambahkan!";
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
                    <h4 class="card-title">Extend Menu</h4>
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
                                    <!-- Button dropdowns with icons -->
                                    <div class="btn-group ml-1 mr-1 mb-1">
                                        <button type="button" class="btn btn-primary btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Entry Extend Menu</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#entryex-pm" href="#">Parent Menu</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#entryex-cm" href="#">Child Menu</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#entryex-gm" href="#">Grandchild Menu</a>
                                        </div>
                                    </div>
                                    <!-- /btn-group -->
                                    <!-- Modal Ref Grandchild Menu -->
                                    <div class="modal fade text-left" id="entryex-gm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data Extend Menu</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Grand Child Menu</label>
                                                                <select name="gmid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_gm = mysqli_query($conn, "SELECT parentmenu.*, childmenu.*, grandchildmenu.* FROM childmenu
                                                                    INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu
                                                                    INNER JOIN grandchildmenu ON childmenu.id_childmenu = grandchildmenu.id_childmenu");
                                                                    while($data_gm = mysqli_fetch_assoc($sql_gm)) {
                                                                ?>
                                                                    <option value="<?= $data_gm['id_grandchildmenu'];?>" ><?= $data_gm['id_grandchildmenu']." - ".$data_gm['parentmenu_name']." > ".$data_gm['childmenu_name']." > ".$data_gm['grandchildmenu_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Extend Menu Name : </label>
                                                                <input type="text" name="exname" placeholder="Extend Menu Name" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="extendgm" class="btn btn-outline-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                            <?php
                            if (isset($alert_success_insertpm)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_insertpm; ?>
                                    </div>
                                    <?php
                            }
                            elseif (isset($alert_success_insertcm)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_insertcm; ?>
                                    </div>
                                <?php
                            }
                            elseif (isset($alert_success_insertgm)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $alert_success_insertgm; ?>
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
                                    <th>No</th>
                                    <th>Ref Menu</th>
                                    <th>Extend Menu Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT extendmenu.*, parentmenu.*, childmenu.*, grandchildmenu.* FROM extendmenu
                            LEFT JOIN parentmenu ON extendmenu.id_ref_menu = parentmenu.id_parentmenu
                            LEFT JOIN childmenu ON extendmenu.id_ref_menu = childmenu.id_childmenu
                            LEFT JOIN grandchildmenu ON extendmenu.id_ref_menu = grandchildmenu.id_grandchildmenu");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['id_grandchildmenu']." - ".$data['grandchildmenu_name']; ?></td>
                                    <td><?= $data['name_extend_menu']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success" data-toggle="modal" data-target="#update<?= $data['id_extend_menu']; ?>"><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data['id_extend_menu']; ?>"><i class="ft-delete"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id_extend_menu']; ?>"
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
                                                    <input class="form-control" type="hidden" name="exid" value="<?= $data['id_extend_menu']; ?>">
                                                        <label>Ref Menu : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="cmname" class="form-control" value="<?= $data['parentmenu_name']." - ".$data['childmenu_name']; ?>" disabled>
                                                        </div>
                                                        <label>Extend Menu Name : </label>
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
                                    <div class="modal fade text-left" id="delete<?= $data['id_extend_menu']; ?>" role="dialog"
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
                                                    <input type="hidden" name="exid" value="<?= $data['id_extend_menu']; ?>">
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