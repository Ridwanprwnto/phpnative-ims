<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$_SESSION['USERMANUAL'] = $_POST;

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertmasterapplication"])){
    if(InsertMasterApplication($_POST) > 0 ){
        $alert = array("Success!", "Data Master Aplikasi Berhasil Disimpan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatemasterapplication"])){
    if(UpdateMasterApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-upd-mstrapp"]) ? $_POST["name-upd-mstrapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletemasterapplication"])){
    if(DeleteMasterApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-del-mstrapp"]) ? $_POST["name-del-mstrapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertversiapplication"])){
    if(InsertVersiApplication($_POST) > 0 ){
        $alert = array("Success!", "Data Versi Aplikasi Berhasil Disimpan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updateversiapplication"])){
    if(UpdateVersiApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-upd-verapp"]) ? $_POST["name-upd-verapp"] : NULL;
        $datapost2 = isset($_POST["code-upd-verapp"]) ? $_POST["code-upd-verapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Versi ".$datapost2." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deleteversiapplication"])){
    if(DeleteVersiApplication($_POST) > 0 ){
        $datapost = isset($_POST["name-del-verapp"]) ? $_POST["name-del-verapp"] : NULL;
        $datapost2 = isset($_POST["code-del-verapp"]) ? $_POST["code-del-verapp"] : NULL;
        $alert = array("Success!", "Data Master Aplikasi ".$datapost." Versi ".$datapost2." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Management Application</h4>
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
                                <a class="nav-link active" id="master-application" data-toggle="tab" href="#masterapplication" aria-expanded="true">Master Application</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="master-version" data-toggle="tab" href="#masterversion" aria-expanded="false">Master Version</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="masterapplication" aria-expanded="true" aria-labelledby="master-application">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entrymasterapp">Entry Master Application</button>
                                            <!-- Insert Modal -->
                                            <div class="modal fade text-left" id="entrymasterapp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Master Application</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="page-mstr-app" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <input type="hidden" name="office-mstr-app" value="<?= $idoffice;?>" class="form-control" readonly>
                                                                    <input type="hidden" name="dept-mstr-app" value="<?= $iddept;?>" class="form-control" readonly>
                                                                    <table class="table table-striped text-center">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Jenis Aplikasi</th>
                                                                                <th scope="col">Developer</th>
                                                                                <th scope="col">Basis</th>
                                                                                <th scope="col">Nama Aplikasi</th>
                                                                                <th scope="col">Fungsi</th>
                                                                                <th scope="col">Peruntukan</th>
                                                                                <th><button type="button" name="add_master_app" class="btn btn-success btn-xs add_master_app"><i class="ft-plus"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="table-master-app">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertmasterapplication" class="btn btn-outline-primary">Save</button>
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
                                            <th>ID</th>
                                            <th>Jenis Aplikasi</th>
                                            <th>Basis</th>
                                            <th>Developer</th>
                                            <th>Nama Aplikasi</th>
                                            <th>Fungsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $nop = 1;
                                        $sql_app = "SELECT A.*, B.office_name, C.department_name FROM master_app AS A
                                        INNER JOIN office AS B ON A.office_app = B.id_office
                                        INNER JOIN department AS C ON A.dept_app = C.id_department
                                        WHERE A.office_app = '$idoffice' AND A.dept_app = '$iddept'";
                                        $query_app = mysqli_query($conn, $sql_app);
                                        while($data_app = mysqli_fetch_assoc($query_app)) {
                                    ?>
                                        <tr>
                                            <td><?= $nop++; ?></td>
                                            <td><?= $data_app['code_app']; ?></td>
                                            <td><?= $data_app['jenis_app']; ?></td>
                                            <td><?= $data_app['basis_app']; ?></td>
                                            <td><?= $data_app['develop_app']; ?></td>
                                            <td><?= $data_app['name_app']; ?></td>
                                            <td><?= $data_app['func_app'] == "" ? "-" : $data_app['func_app']; ?></td>
                                            <td>
                                                <!-- Icon Button dropdowns -->
                                                <div class="btn-group mb-1">
                                                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item update_master_app" href="#" title="Edit Data Master Aplikasi ID <?= $data_app['code_app']; ?>" name="update_master_app" id="<?= $data_app["id_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Data</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_master_app" href="#" title="Hapus Data Master Aplikasi ID <?= $data_app['code_app']; ?>" name="delete_master_app" id="<?= $data_app["id_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Data</a>
                                                    </div>
                                                </div>
                                                <!-- /btn-group -->
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalMasterApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-upd-mstrapp"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-upd-mstrapp" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="office-upd-mstrapp" value="<?= $idoffice;?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept-upd-mstrapp" value="<?= $iddept;?>" class="form-control" readonly>
                                                            <input type="hidden" name="id-upd-mstrapp" id="id-upd-mstrapp" class="form-control" readonly>
                                                            <input type="hidden" name="code-upd-mstrapp" id="code-upd-mstrapp" class="form-control" readonly>
                                                            <input type="hidden" name="nameold-upd-mstrapp" id="nameold-upd-mstrapp" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nama Aplikasi : </label>
                                                                <input type="text" name="name-upd-mstrapp" id="name-upd-mstrapp" placeholder="Input Nama Aplikasi" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Peruntukan :</label>
                                                                <textarea type="text" class="form-control" name="for-upd-mstrapp" id="for-upd-mstrapp" readonly></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Edit Peruntukan : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="useupdmstrapp[]">
                                                                <?php
                                                                    $sql_upd_mstrapp = "SELECT A.*, B.* FROM divisi AS A
                                                                    INNER JOIN sub_divisi AS B ON A.id_divisi = B.id_divisi ORDER BY A.id_divisi ASC";
                                                                    $query_upd_mstrapp = mysqli_query($conn, $sql_upd_mstrapp);
                                                                    while($data_ups_mstrapp = mysqli_fetch_assoc($query_upd_mstrapp)) {
                                                                ?>
                                                                    <option value="<?= $data_ups_mstrapp["id_sub_divisi"]."-".$data_ups_mstrapp["divisi_name"].' > '.$data_ups_mstrapp["sub_divisi_name"]; ?>"><?= $data_ups_mstrapp["divisi_name"].' > '.$data_ups_mstrapp["sub_divisi_name"]; ?></option>
                                                                <?php
                                                                    }
                                                                ?>    
                                                            </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Fungsi :</label>
                                                                <textarea type="text" class="form-control" name="func-upd-mstrapp" id="func-upd-mstrapp" placeholder="Input Fungsi Aplikasi (Optional)"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatemasterapplication" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalMasterApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" name="page-del-mstrapp" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-del-mstrapp" name="id-del-mstrapp" class="form-control" readonly>
                                                    <input type="hidden" id="code-del-mstrapp" name="code-del-mstrapp" class="form-control" readonly>
                                                    <input type="hidden" id="name-del-mstrapp" name="name-del-mstrapp" class="form-control" readonly>
                                                    <label id="label-del-mstrapp"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletemasterapplication" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="masterversion" aria-labelledby="master-version">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryversiapp">Entry Version Application</button>
                                            <!-- Insert Modal -->
                                            <div class="modal fade text-left" id="entryversiapp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post" enctype="multipart/form-data" role="form">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Version Application</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page-insversi-app" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <input type="hidden" name="user-insversi-app" value="<?= $usernik;?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Aplikasi : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="name-insversi-app" id="name-insversi-app" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php
                                                                                $query_ins_verapp = mysqli_query($conn, "SELECT code_app, name_app, basis_app FROM master_app WHERE office_app = '$idoffice' AND dept_app = '$iddept' ORDER BY name_app ASC");
                                                                                while($data_ins_verapp = mysqli_fetch_assoc($query_ins_verapp)) {
                                                                            ?>
                                                                                <option value="<?= $data_ins_verapp["code_app"].$data_ins_verapp["basis_app"];?>"><?= $data_ins_verapp["name_app"];?></option>
                                                                            <?php
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Tanggal Rilis : </label>
                                                                        <input type="date" name="rilis-insversi-app" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Versi : </label>
                                                                        <input type="text" name="versi-insversi-app" placeholder="Input Versi Aplikasi" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Fitur Update :</label>
                                                                        <textarea type="text" class="form-control" name="fitur-insversi-app" placeholder="Input Update Fitur (Optional)"></textarea>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <label>Informasi : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="info-insversi-app" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <option value="EMAIL">EMAIL</option>
                                                                            <option value="WA GROUP">WA GROUP</option>
                                                                            <option value="TELE GROUP">TELE GROUP</option>
                                                                            <option value="LAIN-LAIN">LAIN-LAIN</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <label>Penggunaan : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="use-insversi-app" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <option value="Y">VERSI EXIST</option>
                                                                            <option value="N">VERSI OLD</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2" id="elmweb-insversi-app" style="display:none;">
                                                                        <label>Website Aplikasi : </label>
                                                                        <input type="text" name="web-insversi-app" id="web-insversi-app" placeholder="Input Alamat Website Aplikasi" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2" id="elmnonweb-insversi-app" style="display:none;">
                                                                        <label>Master Aplikasi (zip file) : </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="nonweb-insversi-app" id="nonweb-insversi-app" required>
                                                                            <label class="custom-file-label">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>User Manual (pdf or pptx file) : </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="manual-insversi-app">
                                                                            <label class="custom-file-label">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertversiapplication" class="btn btn-outline-primary">Save</button>
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
                                            <th>Nama Aplikai</th>
                                            <th>Rilis</th>
                                            <th>Version</th>
                                            <th>Fitur Update</th>
                                            <th>Informasi</th>
                                            <th>Penggunaan</th>
                                            <th>Master</th>
                                            <th>User Manual</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $nov = 1;
                                        $sql_ver = "SELECT A.*, B.name_app, B.basis_app, C.username FROM version_app AS A
                                        INNER JOIN master_app AS B ON A.id_code_app = B.code_app
                                        LEFT JOIN users AS C ON A.user_ver_app = C.nik
                                        WHERE B.office_app = '$idoffice' AND B.dept_app = '$iddept'";
                                        $query_ver = mysqli_query($conn, $sql_ver);
                                        while($data_ver = mysqli_fetch_assoc($query_ver)) {
                                    ?>
                                        <tr>
                                            <td><?= $nov++; ?></td>
                                            <td><?= $data_ver['name_app']; ?></td>
                                            <td><?= $data_ver['rilis_ver_app']; ?></td>
                                            <td><?= $data_ver['version_ver_app']; ?></td>
                                            <td><?= $data_ver['fitur_ver_app'] == "" ? "-" : $data_ver['fitur_ver_app']; ?></td>
                                            <td><?= $data_ver['info_ver_app']; ?></td>
                                            <td><?= $data_ver['use_ver_app'] == "Y" ? "VERSI EXIST" : "VERSI OLD"; ?></td>
                                            <?php
                                                if ($data_ver['basis_app'] == "WEB") {
                                            ?>
                                            <td>
                                                <a title="Link Website Aplikasi " href="<?= $data_ver["source_ver_app"]; ?>" target="_blank" class="btn btn-float btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-external-link"></i>
                                                    <span>Link</span>
                                                </a>
                                            </td>
                                            <?php
                                                }
                                                else { 
                                            ?>
                                            <td>
                                                <a title="Source Master Aplikasi " href="files/source/index.php?master=<?= encrypt($data_ver['source_ver_app']);?>" target="_blank" class="btn btn-float btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-file"></i>
                                                    <span>File</span>
                                                </a>
                                            </td>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if ($data_ver['manual_ver_app'] === "") {
                                            ?>
                                            <td>-</td>
                                            <?php
                                                }
                                                else { 
                                            ?>
                                            <td>
                                                <a title="User Manual Aplikasi" onclick="document.location.href='<?= $encpid;?>'" target="_blank" data-toggle="tooltip" data-placement="bottom" href="<?= $data_ver['manual_ver_app'] !== "" ? "files/manual/index.php?id=".encrypt($data_ver['manual_ver_app']) : '#'; ?>" class="<?= $data_ver['manual_ver_app'] !== "" ? 'btn btn-float btn-warning' : ''; ?>"><i class="<?= $data_ver['manual_ver_app'] !== "" ? 'ft-file-text' : ''; ?>"></i>
                                                    <?= $data_ver['manual_ver_app'] !== "" ? '<span>File</span>' : ''; ?>
                                                </a>
                                            </td>
                                            <?php
                                                }
                                            ?>
                                            <td>
                                                <!-- Icon Button dropdowns -->
                                                <div class="btn-group mb-1">
                                                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item update_versi_app" href="#" title="Edit Data Master Aplikasi <?= $data_ver['name_app']; ?> Versi <?= $data_ver['version_ver_app']; ?>" name="update_versi_app" id="<?= $data_ver["id_ver_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Data</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_versi_app" href="#" title="Hapus Data Master Aplikasi <?= $data_ver['name_app']; ?> Versi <?= $data_ver['version_ver_app']; ?>" name="delete_versi_app" id="<?= $data_ver["id_ver_app"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Data</a>
                                                    </div>
                                                </div>
                                                <!-- /btn-group -->
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalVersionApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-upd-verapp"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-upd-verapp" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="user-upd-verapp" value="<?= $usernik;?>" class="form-control" readonly>
                                                            <input type="hidden" name="id-upd-verapp" id="id-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="code-upd-verapp" id="code-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="apl-upd-verapp" id="apl-upd-verapp" class="form-control" readonly>
                                                            <input type="hidden" name="mnl-upd-verapp" id="mnl-upd-verapp" class="form-control" readonly>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Nama Aplikasi : </label>
                                                                <input type="text" name="name-upd-verapp" id="name-upd-verapp" class="form-control" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Versi : </label>
                                                                <input type="text" name="versi-upd-verapp" id="versi-upd-verapp" placeholder="Input Versi Aplikasi" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tanggal Rilis : </label>
                                                                <input type="date" name="rilis-upd-verapp" id="rilis-upd-verapp" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Fitur Update :</label>
                                                                <textarea type="text" class="form-control" name="fitur-upd-verapp" id="fitur-upd-verapp" placeholder="Input Update Fitur (Optional)"></textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Informasi : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="info-upd-verapp" id="info-upd-verapp" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="EMAIL">EMAIL</option>
                                                                    <option value="WA GROUP">WA GROUP</option>
                                                                    <option value="TELE GROUP">TELE GROUP</option>
                                                                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Penggunaan : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="use-upd-verapp" id="use-upd-verapp" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="Y">VERSI EXIST</option>
                                                                    <option value="N">VERSI OLD</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2" id="elmweb-updversi-app" style="display:none;">
                                                                <label>Website Aplikasi : </label>
                                                                <input type="text" name="web-upd-verapp" id="web-upd-verapp" placeholder="Input Alamat Website Aplikasi" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2" id="elmnonweb-updversi-app" style="display:none;">
                                                                <label>Master Aplikasi (zip file) : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="nonweb-insversi-app" id="nonweb-insversi-app">
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>User Manual (pdf or pptx file) : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="manual-insversi-app">
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updateversiapplication" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalVersionApp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" name="page-del-verapp" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="id-del-verapp" name="id-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="code-del-verapp" name="code-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="name-del-verapp" name="name-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="source-del-verapp" name="source-del-verapp" class="form-control" readonly>
                                                    <input type="hidden" id="manual-del-verapp" name="manual-del-verapp" class="form-control" readonly>
                                                    <label id="label-del-verapp"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deleteversiapplication" class="btn btn-outline-danger">Yes</button>
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

    let count = 0;

    $(document).on('click', '.add_master_app', function(){
        count++;
        
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="insmstr_jenis_app[]" class="select2 form-control block insmstr_jenis_app" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="INTERNAL">INTERNAL</option><option value="EKSTERNAL">EKSTERNAL</option></select></td>';
        html += '<td><select type="text" name="insmstr_develop_app[]" class="select2 form-control block insmstr_develop_app" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="SD3">SD3 (DC)</option><option value="SD5">SD5 (GA)</option><option value="LAIN-LAIN">LAIN-LAIN</option></select></td>';
        html += '<td><select type="text" name="insmstr_basis_app[]" class="select2 form-control insmstr_basis_app" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="DESKTOP">DESKTOP</option><option value="WEB">WEB</option><option value="MOBILE">MOBILE</option></select></td>';
        html += '<td><input type="text" name="insmstr_name_app[]" class="form-control insmstr_name_app" placeholder="Input Nama Aplikasi" required/></td>';
        html += '<td><textarea type="text" name="insmstr_func_app[]" class="form-control insmstr_func_app" placeholder="Input Fungsi Aplikasi (Optional)"></textarea></td>';
        html += '<td><select type="text" name="insmstr_peruntukan_app['+String(count)+'][]" class="select2 form-control block insmstr_peruntukan_app" style="width: 100%" data-placeholder="Please Select" multiple="multiple" required><?= fill_select_subdivisi(); ?></select></td>';
        html += '<td><button type="button" name="remove_master_app" class="btn btn-danger btn-xs remove_master_app"><i class="ft-minus"></i></button></td>';
        
        $('#table-master-app').append(html);
        $(".select2").select2();
    });

    $(document).on('click', '.remove_master_app', function(){
        $(this).closest('tr').remove();
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_master_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMASTERAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-upd-mstrapp').val(data.id_app);
                $('#code-upd-mstrapp').val(data.code_app);
                $('#name-upd-mstrapp').val(data.name_app);
                $('#nameold-upd-mstrapp').val(data.name_app);
                $('#func-upd-mstrapp').val(data.func_app);
                $('#for-upd-mstrapp').val(data.peruntukan_app);

                $('#label-upd-mstrapp').html("Update Master Aplikasi "+data.code_app+". "+data.name_app);
                $('#updateModalMasterApp').modal('show');
            }  
        });
    });
});    

$(document).ready(function(){
    $(document).on('click', '.delete_master_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMASTERAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-del-mstrapp').val(data.id_app);
                $('#code-del-mstrapp').val(data.code_app);
                $('#name-del-mstrapp').val(data.name_app);
                
                $('#label-del-mstrapp').html("Delete Master Aplikasi "+data.name_app);
                $('#deleteModalMasterApp').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $("#name-insversi-app").change(function() {
        var jenis_appname = $(this).val();
        if (jenis_appname.substring(8) == "WEB") {
            $("#elmnonweb-insversi-app").hide();

            $("#web-insversi-app").removeAttr('disabled');
            $("#web-insversi-app").prop('required', jenis_appname);

            $("#nonweb-insversi-app").removeAttr('required');
            $("#nonweb-insversi-app").prop('disabled', jenis_appname);

            $("#elmweb-insversi-app").show();
        }
        else if (jenis_appname.substring(8) == "DESKTOP" || jenis_appname.substring(8) == "MOBILE") {
            $("#elmweb-insversi-app").hide();
            $("#web-insversi-app").val('');
            
            $("#web-insversi-app").removeAttr('required');
            $("#web-insversi-app").prop('disabled', jenis_appname);
            
            $("#nonweb-insversi-app").removeAttr('disabled');
            $("#nonweb-insversi-app").prop('required', jenis_appname);

            $("#elmnonweb-insversi-app").show();
        }
        else {
            $("#elmnonweb-insversi-app").hide();
            $("#elmweb-insversi-app").hide();
        }
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_versi_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONVERSIAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-upd-verapp').val(data.id_ver_app);
                $('#code-upd-verapp').val(data.id_code_app);
                $('#name-upd-verapp').val(data.name_app);
                $('#apl-upd-verapp').val(data.source_ver_app);
                $('#mnl-upd-verapp').val(data.manual_ver_app);
                $('#rilis-upd-verapp').val(data.rilis_ver_app);
                $('#versi-upd-verapp').val(data.version_ver_app);
                $('#fitur-upd-verapp').val(data.fitur_ver_app);
                $('#manual-upd-verapp').val(data.manual_ver_app);

                $('#info-upd-verapp').find('option[value="'+data.info_ver_app+'"]').remove();
                $('#info-upd-verapp').append($('<option></option>').html(data.info_ver_app).attr('value', data.info_ver_app).prop('selected', true));
                               
                $('#use-upd-verapp').find('option[value="'+data.use_ver_app+'"]').remove();
                $('#use-upd-verapp').append($('<option></option>').html(data.use_ver_app == 'Y' ? 'VERSI EXIST' : 'VERSI OLD').attr('value', data.use_ver_app).prop('selected', true));
                
                if (data.basis_app == "WEB") {
                    $("#elmnonweb-updversi-app").hide();
                    $('#web-upd-verapp').val(data.source_ver_app);

                    $("#web-upd-verapp").removeAttr('disabled');
                    $("#web-upd-verapp").prop('required', data.basis_app);
                    
                    $("#nonweb-insversi-app").prop('disabled', data.basis_app);

                    $("#elmweb-updversi-app").show();
                }
                else if (data.basis_app == "DESKTOP" || data.basis_app == "MOBILE") {
                    $("#elmweb-updversi-app").hide();
                    
                    $("#web-upd-verapp").removeAttr('required');
                    $("#web-upd-verapp").prop('disabled', data.basis_app);
                    
                    $("#nonweb-insversi-app").removeAttr('disabled');

                    $("#elmnonweb-updversi-app").show();
                }

                $('#label-upd-verapp').html("Update Master Aplikasi "+data.name_app+" Versi "+data.version_ver_app);
                $('#updateModalVersionApp').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_versi_app', function(){  
        var nomor_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONVERSIAPP:nomor_id},  
            dataType:"json",  
            success:function(data){
                $('#id-del-verapp').val(data.id_ver_app);
                $('#code-del-verapp').val(data.id_code_app);
                $('#name-del-verapp').val(data.name_app);
                $('#versi-del-verapp').val(data.version_ver_app);
                $('#source-del-verapp').val(data.source_ver_app);
                $('#manual-del-verapp').val(data.manual_ver_app);
                
                $('#label-del-verapp').html("Delete Master Aplikasi "+data.name_app+" Versi "+data.version_ver_app);
                $('#deleteModalVersionApp').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>