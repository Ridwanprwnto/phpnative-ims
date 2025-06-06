<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insertcm_akses($_POST) > 0 ){
        $msg = encrypt("insertdata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(updatecm_akses($_POST) > 0 ){
        $msg = encrypt("updatedata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deletecm_akses($_POST)){
        $msg = encrypt("deletedata");
        header("location: index.php?page=$encpid&alert=$msg");
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
                    <h4 class="card-title">Access Child Menu</h4>
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
                                        data-toggle="modal" data-target="#entryaccpm">Entry Access Group</button>
                                    <div class="modal fade text-left" id="entryaccpm" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Select Access Group Menu</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label for="groupid">Group Name</label>
                                                                <select id="groupid" name="groupid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_group = mysqli_query($conn, "SELECT * FROM groups ");
                                                                    while($data_group = mysqli_fetch_assoc($sql_group)) {
                                                                ?>
                                                                    <option value="<?= $data_group['id_group'];?>" ><?= $data_group['group_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label >Parent Menu Name</label>
                                                                <select name="pmid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_pm = mysqli_query($conn, "SELECT * FROM parentmenu ");
                                                                    while($data_pm = mysqli_fetch_assoc($sql_pm)) {
                                                                ?>
                                                                    <option value="<?= $data_pm['id_parentmenu'];?>" ><?= $data_pm['parentmenu_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label for="pmid">Child Menu Name</label>
                                                                <select id="pmid" name="cmid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_cm = mysqli_query($conn, "SELECT childmenu.*, parentmenu.* FROM childmenu 
                                                                    INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu");
                                                                    while($data_cm = mysqli_fetch_assoc($sql_cm)) {
                                                                ?>
                                                                    <option value="<?= $data_cm['id_childmenu'];?>" ><?= $data_cm['parentmenu_name']." - ".$data_cm['childmenu_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
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
                            if(isset($_GET["page"]) && isset($_GET["alert"])) {
                                $url_page  = $_GET["page"];
                                $url_alert = $_GET["alert"];
                                if($_GET["page"] === $url_page && $_GET["alert"] === $url_alert) {
                                    $strplus = rplplus($url_alert);
                                    $decmsg = decrypt($strplus);
                                    if($decmsg == true) {
                                        if($decmsg === "insertdata") {
                                            ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right"
                                        role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil ditambahkan
                                    </div>
                                    <?php
                                        }
                                        elseif($decmsg === "updatedata") {
                                            ?>
                                    <div class="alert alert-info alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil diupdate
                                    </div>
                                    <?php   
                                        }
                                        elseif($decmsg === "deletedata") {
                                            ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil dihapus
                                    </div>
                                    <?php
                                        }
                                    }
                                }
                            }
                            ?>
                                    <?php
                            if (isset($ins_acmname)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $ins_acmname; ?>
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
                            ?>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>Group</th>
                                    <th>Parent Menu</th>
                                    <th>Child Menu</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $query = mysqli_query($conn, "SELECT parentmenu.*, childmenu.*, groups.*, akses_childmenu.* FROM akses_childmenu 
                            INNER JOIN parentmenu ON akses_childmenu.id_parentmenu = parentmenu.id_parentmenu
                            INNER JOIN childmenu ON akses_childmenu.id_childmenu = childmenu.id_childmenu
                            INNER JOIN groups ON akses_childmenu.id_group = groups.id_group");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['group_name']; ?></td>
                                    <td><?= $data['parentmenu_name']; ?></td>
                                    <td><?= $data['childmenu_name']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success"><i class="ft-edit"
                                                data-toggle="modal"
                                                data-target="#update<?= $data['id_akses_cm']; ?>"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger"><i class="ft-delete"
                                                data-toggle="modal"
                                                data-target="#delete<?= $data['id_akses_cm']; ?>"></i></button>
                                    </td>
                                    <td>
                                        <div class="badge badge-<?= $data['childmenu_status'] == 'Y' ? 'info' : 'danger'; ?> "><?= $data['childmenu_status'] == 'Y' ? 'Active' : 'Non Active'; ?></div>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id_akses_cm']; ?>"
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
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="idacccm" value="<?= $data['id_akses_cm']; ?>">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Group Name</label>
                                                                <select name="groupid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <?php 
                                                                    $sql_group = mysqli_query($conn, "SELECT * FROM groups ");
                                                                    while($data_group = mysqli_fetch_assoc($sql_group)) {
                                                                ?>
                                                                    <option value="<?= $data_group['id_group']; ?>" <?= $data_group['id_group'] == $data['id_group'] ? 'selected' : '';?> disabled><?= $data_group['group_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Menu Name</label>
                                                                <select name="cmid" class="select2 form-control block" style="width: 100%" type="text">
                                                                <?php 
                                                                    $sql_cm = mysqli_query($conn, "SELECT parentmenu.*, childmenu.* FROM childmenu 
                                                                    INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu");
                                                                    while($data_cm = mysqli_fetch_assoc($sql_cm)) {
                                                                ?>
                                                                    <option value="<?= $data_cm['id_childmenu']; ?>" <?= $data_cm['id_childmenu'] == $data['id_childmenu'] ? 'selected' : '';?> disabled><?= $data_cm['parentmenu_name']." - ".$data_cm['childmenu_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Status Menu</label>
                                                                <select name="status" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="none" disabled>Please Select</option>
                                                                <?php
                                                                    $status = array('Y', 'N');
                                                                    foreach ($status as $s) {
                                                                ?>
                                                                    <option value="<?= $s; ?>" <?= $data['childmenu_status'] == $s ? 'selected' : ''; ?>><?= $s == 'Y' ? 'Active' : 'Non Active'; ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </select>
                                                            </div>
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
                                    <div class="modal fade text-left" id="delete<?= $data['id_akses_cm']; ?>" role="dialog"
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
                                                    <input type="hidden" name="acccmid" value="<?= $data['id_akses_cm']; ?>">
                                                    <input type="hidden" name="groupid" value="<?= $data['id_group']; ?>">
                                                    <input type="hidden" name="cmid" value="<?= $data['id_childmenu']; ?>">
                                                    <p>Are you sure to delete ID : <?= $data['id_akses_cm']; ?>
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