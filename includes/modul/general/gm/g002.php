<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];
$iddiv = $_SESSION['divisi'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insert_newuser($_POST) > 0 ){
        $datapost = isset($_POST["nikuser"]) ? $_POST["nikuser"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Berhasil Ditambahkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(usersaktivasi($_POST) > 0 ){
        $datapost = isset($_POST["nik"]) ? $_POST["nik"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deluser($_POST)){
        $datapost = isset($_POST["delnik"]) ? $_POST["delnik"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["editdata"])){
    if(EditDataUser($_POST) > 0 ){
        $datapost = isset($_POST["nik"]) ? $_POST["nik"] : NULL;
        $alert = array("Success!", "User NIK ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updateuserscheckdata"])){
    if(UpdateDataUserMultiple($_POST) > 0 ){
        $alert = array("Success!", "Data Users Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>
<!-- Auto Fill table -->
<section id="basic-select2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">List Users</h4>
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
                                <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entryuser" <?= $id_group == $admin ? "" : ($id_group == $support ? "" : "disabled"); ?>>Entry User</button>
                                    <div class="modal fade text-left" id="entryuser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data User</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-6 mb-2">
                                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <label>NIK : </label>
                                                                <input type="number" name="nikuser" placeholder="NIK" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>User Name : </label>
                                                                <input type="text" name="username" placeholder="Username" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Full Name : </label>
                                                                <input type="text" name="fullname" placeholder="Nama Lengkap" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Email : </label>
                                                                <input type="email" name="email" placeholder="Email Address" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Password : </label>
                                                                <input type="password" name="password" placeholder="Password" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>IP Address : </label>
                                                                <input type="text" name="ipaddress" placeholder="IP Address" class="form-control">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Office : </label>
                                                            <select id="office" name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php
                                                                if ($id_group == $admin) {
                                                                    $sql_office = mysqli_query($conn, "SELECT * FROM office");
                                                                    while($data_office = mysqli_fetch_assoc($sql_office)) {
                                                                ?>
                                                                    <option value="<?= $data_office['id_office']; ?>"><?= $data_office['id_office']." - ".strtoupper($data_office['office_name']);?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                else {
                                                                    $sql_office = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$idoffice'");
                                                                    while($data_office = mysqli_fetch_assoc($sql_office)) {
                                                                ?>
                                                                    <option value="<?= $data_office['id_office']; ?>"><?= $data_office['id_office']." - ".strtoupper($data_office['office_name']);?></option>
                                                                <?php 
                                                                    }
                                                                } 
                                                            ?>
                                                            </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Department : </label>
                                                            <select id="department" name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $sql_department = mysqli_query($conn, "SELECT * FROM department");
                                                                while($data_department = mysqli_fetch_assoc($sql_department)) {
                                                            ?>
                                                                <option value="<?= $data_department['id_department']; ?>"><?= strtoupper($data_department['department_name']);?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                            </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Divisi : </label>
                                                            <select id="divisi" name="divisi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $sql_divisi = mysqli_query($conn, "SELECT * FROM divisi ");
                                                                while($data_divisi = mysqli_fetch_assoc($sql_divisi)) {
                                                            ?>
                                                                <option value="<?= $data_divisi['id_divisi']; ?>"><?= $data_divisi['divisi_name'];?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                            </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Level : </label>
                                                            <select id="level" name="level" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php
                                                                $sql_level = mysqli_query($conn, "SELECT * FROM level WHERE id_level NOT LIKE 'LV01'");
                                                                while($data_level = mysqli_fetch_assoc($sql_level)) {
                                                            ?>
                                                                <option value="<?= $data_level['id_level']; ?>"><?= $data_level['level_name'];?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                            </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Group : </label>
                                                                <select id="group" name="group" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_group = mysqli_query($conn, "SELECT * FROM groups WHERE id_group NOT LIKE '$admin'");
                                                                    while($data_group = mysqli_fetch_assoc($sql_group)) {
                                                                ?>
                                                                    <option value="<?= $data_group['id_group']; ?>"><?= $data_group['group_name'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Akses Alamat IP : </label>
                                                                <select name="akses" class="select2 form-control block" style="width: 100%" type="number" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php
                                                                    $akses = array(0, 1);
                                                                    foreach ($akses as $a) {
                                                                ?>
                                                                    <option value="<?= $a; ?>"><?= $a == 1 ? 'Semua Alamat IP' : 'IP Terdaftar'; ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Akses Login : </label>
                                                                <select id="status" name="status" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php
                                                                    $status = array('Y', 'N');
                                                                    foreach ($status as $s) {
                                                                ?>
                                                                    <option value="<?= $s; ?>"><?= $s == 'Y' ? 'Active' : 'Non Active'; ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="insertdata" class="btn btn-outline-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <form action="" method="post">
                        <table class="table display nowrap table-striped table-bordered zero-configuration text-center" id="tabel-users">
                            <thead>
                                <tr>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>USERNAME</th>
                                <?php
                                if ($id_group != $admin && $id_group != $support) { ?>
                                <th>FULL NAME</th>
                                <?php
                                }
                                ?>
                                <?php
                                if ($id_group == $admin || $id_group == $support) { ?>
                                <th>OFFICE</th>
                                <?php
                                }
                                ?>
                                <th>GROUP</th>
                                <?php
                                if ($id_group == $admin || $id_group == $support) { ?>
                                <th>LAST LOGIN</th>
                                <?php
                                }
                                ?>
                                <th>ACTION</th>
                                <th>STATUS</th>
                                <th class="icheck1">
                                    <input type="checkbox" id="checkalluser" class="checkalluser">
                                </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                if ($id_group == $admin) {
                                    $select = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
                                    INNER JOIN office ON users.id_office = office.id_office
                                    LEFT JOIN department ON users.id_department = department.id_department
                                    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
                                    LEFT JOIN groups ON users.id_group = groups.id_group
                                    LEFT JOIN level ON users.id_level = level.id_level
                                    ORDER BY users.nik ASC";
                                }
                                elseif($id_group == $support) {
                                    $select = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
                                    INNER JOIN office ON users.id_office = office.id_office
                                    LEFT JOIN department ON users.id_department = department.id_department
                                    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
                                    LEFT JOIN groups ON users.id_group = groups.id_group
                                    LEFT JOIN level ON users.id_level = level.id_level
                                    WHERE users.id_office = '$idoffice'
                                    AND users.id_group NOT LIKE '$admin' OR users.id_group IS NULL
                                    ORDER BY users.nik ASC";
                                }
                                else {
                                    $select = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
                                    INNER JOIN office ON users.id_office = office.id_office
                                    LEFT JOIN department ON users.id_department = department.id_department
                                    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
                                    LEFT JOIN groups ON users.id_group = groups.id_group
                                    LEFT JOIN level ON users.id_level = level.id_level
                                    WHERE users.id_office = '$idoffice' AND users.id_department = '$iddept' AND users.id_divisi = '$iddiv'
                                    AND users.id_group NOT LIKE '$admin'
                                    ORDER BY users.nik ASC";
                                }
                                $query = mysqli_query($conn, $select);
                                while($user = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                <td><?= $no++; ?></td>
                                <td><a title="Detail User NIK : <?= $user['nik']; ?>" href="#" data-toggle="tooltip" data-placement="bottom" class="text-bold-600 read_useraktivasi" name="read_useraktivasi" id="<?= $user["nik"]; ?>"><?= $user['nik']; ?></a></td>
                                <td><?= strtoupper($user['username']); ?></td>
                                <?php
                                if ($id_group != $admin && $id_group != $support) { ?>
                                <td><?= strtoupper($user['full_name']); ?></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($id_group == $admin || $id_group == $support) { ?>
                                <td><?= $user['id_office']." - ".strtoupper($user['office_name']); ?></td>
                                <?php
                                }
                                ?>
                                <td><?= $user['group_name'] == NULL ? '-' : $user['group_name']; ?></td>
                                <?php
                                if ($id_group == $admin || $id_group == $support) { ?>
                                <td><?= $user['last_login'] == NULL ? '-' : $user['last_login']; ?></td>
                                <?php
                                }
                                ?>
                                <td>
                                <?php
                                if ($id_group == $admin || $id_group == $support) { ?>
                                    <button type="button" title="Update User NIK : <?= $user['nik']; ?>" class="btn btn-icon btn-success update_useraktivasi" name="update_useraktivasi" id="<?= $user["id"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                    <button type="button" title="Delete User NIK : <?= $user['nik']; ?>" class="btn btn-icon btn-danger delete_useraktivasi" name="delete_useraktivasi" id="<?= $user["nik"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                <?php
                                }
                                else { ?>
                                    <button type="button" title="Update User NIK : <?= $user['nik']; ?>" class="btn btn-icon btn-warning edit_useraktivasi" name="edit_useraktivasi" id="<?= $user["nik"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                <?php }
                                ?>
                                </td>
                                <td>
                                    <div class="badge badge-<?= $user['status'] == 'Y' ? 'info' : 'danger'; ?> "><?= $user['status'] == 'Y' ? 'Active' : 'Non Active'; ?></div>
                                </td>
                                <td class="icheck1">
                                    <input type="checkbox" name="checkidnik[]" id="checkidnik" value="<?= $user['id']; ?>">
                                </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Read -->
                            <div class="modal fade text-left" id="readModalUser" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-info white">
                                                <h4 class="modal-title white" id="myModalLabel">Detail Data User</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-12 mb-2">
                                                    <label>NIK : </label>
                                                        <input type="text" id="read_nik" name="read_nik" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>User Name : </label>
                                                        <input type="text" id="read_user" name="read_user" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Email : </label>
                                                        <input type="email" id="read_email" name="read_email" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>IP Address : </label>
                                                        <input type="text" id="read_ip" name="read_ip" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Office : </label>
                                                        <input type="text" id="read_office" name="read_office" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Department : </label>
                                                        <input type="text" id="read_dept" name="read_dept" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Divisi : </label>
                                                        <input type="text" id="read_div" name="read_div" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Group : </label>
                                                        <input type="text" id="read_group" name="read_group" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <label>Level : </label>
                                                        <input type="text" id="read_level" name="read_level" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal Read -->
                            <!-- Modal Edit -->
                            <div class="modal fade text-left" id="editModalUser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-warning white">
                                                <h4 class="modal-title white" id="myModalLabel">Edit Data User</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-2">
                                                        <label>NIK : </label>
                                                        <input type="text" class="form-control" id="edt_nik" name="nik" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Username : </label>
                                                        <input type="text" class="form-control" id="edt_username" name="username" readonly>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Fullname : </label>
                                                        <input type="text" class="form-control" placeholder="Nama Lengkap" id="edt_fullname" name="fullname" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Level :</label>
                                                        <select id="edt_userlevel" name="level" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            if ($id_group == $admin) {
                                                                $sql_level = mysqli_query($conn, "SELECT * FROM level");
                                                            }
                                                            else {
                                                                $sql_level = mysqli_query($conn, "SELECT * FROM level WHERE id_level NOT LIKE 'LV01'");
                                                            }
                                                            while($data_level = mysqli_fetch_assoc($sql_level)) {
                                                        ?>
                                                            <option value="<?= $data_level['id_level']; ?>"><?= $data_level['level_name'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="editdata" class="btn btn-outline-warning">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalUser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="myModalLabel">Edit Data User</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" class="form-control" id="id" name="id" readonly>
                                                    <input type="hidden" class="form-control" id="nikold" name="nikold" readonly>
                                                    <input type="hidden" class="form-control" name="page" value="<?= $encpid; ?>" readonly>
                                                    <div class="col-md-6 mb-2">
                                                        <label>NIK : </label>
                                                        <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Username : </label>
                                                        <input type="text" class="form-control" id="username" name="username" readonly>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Fullname : </label>
                                                        <input type="text" class="form-control" placeholder="Nama Lengkap" id="fullname" name="fullname" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Office : </label>
                                                        <select id="upd_useroffice" name="office" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php
                                                                if ($id_group == $admin) {
                                                                    $sql_office = mysqli_query($conn, "SELECT id_office, office_name FROM office");
                                                                }
                                                                else {
                                                                    $sql_office = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$idoffice'");
                                                                }
                                                                while($data_office = mysqli_fetch_assoc($sql_office)) { ?>
                                                                <option value="<?= $data_office['id_office']; ?>"><?= $data_office['id_office']." - ".strtoupper($data_office['office_name']);?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Department : </label>
                                                        <select id="upd_userdept" name="department" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                            $sql_department = mysqli_query($conn, "SELECT * FROM department");
                                                            while($data_department = mysqli_fetch_assoc($sql_department)) {
                                                            ?>
                                                                <option value="<?= $data_department['id_department']; ?>"><?= strtoupper($data_department['department_name']);?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Divisi :</label>
                                                        <select id="upd_userdivisi" name="divisi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $sql_divisi = mysqli_query($conn, "SELECT * FROM divisi ");
                                                            while($data_divisi = mysqli_fetch_assoc($sql_divisi)) {
                                                        ?>
                                                            <option value="<?= $data_divisi['id_divisi']; ?>"><?= $data_divisi['divisi_name'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Group :</label>
                                                        <select id="upd_usergroup" name="group" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            if ($id_group == $admin) {
                                                                $sql_group = mysqli_query($conn, "SELECT * FROM groups");
                                                            }
                                                            else {
                                                                $sql_group = mysqli_query($conn, "SELECT * FROM groups WHERE id_group NOT LIKE '$admin'");
                                                            }
                                                            while($data_group = mysqli_fetch_assoc($sql_group)) {
                                                        ?>
                                                            <option value="<?= $data_group['id_group']; ?>"><?= $data_group['group_name'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Level :</label>
                                                        <select id="upd_userlevel" name="level" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            if ($id_group == $admin) {
                                                                $sql_level = mysqli_query($conn, "SELECT * FROM level");
                                                            }
                                                            else {
                                                                $sql_level = mysqli_query($conn, "SELECT * FROM level WHERE id_level NOT LIKE 'LV01'");
                                                            }
                                                            while($data_level = mysqli_fetch_assoc($sql_level)) {
                                                        ?>
                                                            <option value="<?= $data_level['id_level']; ?>"><?= $data_level['level_name'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>IP Address : </label>
                                                        <input type="text" id="upd_userip" name="ipaddress" class="form-control" placeholder="Input IP Address" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Akses Alamat IP : </label>
                                                        <select id="upd_userakses" name="akses" class="select2 form-control block" style="width: 100%" type="number" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            $akses = array(0, 1);
                                                            foreach ($akses as $a) {
                                                        ?>
                                                            <option value="<?= $a; ?>"><?= $a == 1 ? 'Semua Alamat IP' : 'IP Terdaftar'; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Akses Login : </label>
                                                        <select id="upd_userstatus" name="status" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            $status = array('Y', 'N');
                                                            foreach ($status as $s) {
                                                        ?>
                                                            <option value="<?= $s; ?>"><?= $s == 'Y' ? 'Active' : 'Non Active'; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
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
                            <div class="modal fade text-left" id="deleteModalUser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Data User</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="delnik" name="delnik" readonly>
                                            <label id="del-labelnik"></label>
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
                            <!-- Modal Update By Check -->
                            <div class="modal fade text-left" id="updateuserscheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                    <!-- <form action="" method="post"> -->
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Update Data Users By Checkbox</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row" id="table-edtusers-check">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updateuserscheckdata" class="btn btn-outline-info">Update</button>
                                        </div>
                                    <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                        </form>
                        <!-- Button dropdowns with icons -->
                        <div class="btn-group mt-1 mb-2 mr-1 pull-right">
                            <button type="button" title="Action With Checkbox" class="btn btn-info btn-min-width dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Update By Checkbox</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" data-target="#updatecheckuser" onclick="return validateForm('EDIT');" href="#" title="Update Data With Checkbox">Update Data Users</a>
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

$(document).ready(function () {

    var table = $('#tabel-users').DataTable({
        info: true,
        searching: false,
        ordering: true,
        paging: false,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        scrollY: '50vh'
    });

});

$(document).ready(function(){
    $(document).on('click', '.read_useraktivasi', function(){  
        var nik_user = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{READUSERNIK:nik_user},  
            dataType:"json",  
            success:function(data){
                $('#read_nik').val(data.nik);
                $('#read_user').val(data.username.toUpperCase());
                $('#read_email').val(data.email.toUpperCase());
                $('#read_office').val(data.id_office+" - "+data.office_name.toUpperCase());

                if (data.ip_address == null || data.id_department == null || data.id_divisi == null || data.id_group == null || data.id_level == null) {
                    $('#read_ip').val("-");
                    $('#read_dept').val("-");
                    $('#read_div').val("-");
                    $('#read_group').val("-");
                    $('#read_level').val("-");
                }
                else {
                    $('#read_ip').val(data.ip_address);
                    $('#read_dept').val(data.id_department+" - "+data.department_name.toUpperCase());
                    $('#read_div').val(data.id_divisi+" - "+data.divisi_name);
                    $('#read_group').val(data.id_group+" - "+data.group_name);
                    $('#read_level').val(data.id_level+" - "+data.level_name);
                }

                $('#readModalUser').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_useraktivasi', function(){  
        var nik_user = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEUSERNIK:nik_user},  
            dataType:"json",  
            success:function(data){
                $('#id').val(data.id);
                $('#nikold').val(data.nik);
                $('#nik').val(data.nik);
                $('#username').val(data.username.toUpperCase());
                $('#fullname').val(data.full_name);

                $('#upd_useroffice').find('option[value="'+data.id_office+'"]').remove();
                $('#upd_userakses').find('option[value="'+data.akses_ip+'"]').remove();
                $('#upd_userstatus').find('option[value="'+data.status+'"]').remove();

                if (data.ip_address != null || data.ip_address != '') {
                    
                    $('#upd_userip').val(data.ip_address);

                }

                if (data.id_department != null) {

                    $('#upd_userdept').find('option[value="'+data.id_department+'"]').remove();

                    $('#upd_userdept').append($('<option></option>').html(data.department_name.toUpperCase()).attr('value', data.id_department).prop('selected', true));

                }

                if (data.id_divisi != null) {

                    $('#upd_userdivisi').find('option[value="'+data.id_divisi+'"]').remove();

                    $('#upd_userdivisi').append($('<option></option>').html(data.divisi_name).attr('value', data.id_divisi).prop('selected', true));

                }

                if (data.id_group != null) {

                    $('#upd_usergroup').find('option[value="'+data.id_group+'"]').remove();

                    $('#upd_usergroup').append($('<option></option>').html(data.group_name).attr('value', data.id_group).prop('selected', true));

                }

                if (data.id_level != null) {

                    $('#upd_userlevel').find('option[value="'+data.id_level+'"]').remove();

                    $('#upd_userlevel').append($('<option></option>').html(data.level_name).attr('value', data.id_level).prop('selected', true));

                }

                if (data.akses_ip == 1) {
                    var $name_aks = "Semua Alamat IP";
                }
                else {
                    var $name_aks = "IP Terdaftar";
                }

                if (data.status == "Y") {
                    var $name_sts = "Active";
                }
                else {
                    var $name_sts = "Non Active";
                }

                $('#upd_useroffice').append($('<option></option>').html(data.id_office+" - "+data.office_name.toUpperCase()).attr('value', data.id_office).prop('selected', true));
                $('#upd_userakses').append($('<option></option>').html($name_aks).attr('value', data.akses_ip).prop('selected', true));
                $('#upd_userstatus').append($('<option></option>').html($name_sts).attr('value', data.status).prop('selected', true));

                $('#updateModalUser').modal('show');
            }  
        });
    });
});


$(document).ready(function(){
    $(document).on('click', '.edit_useraktivasi', function(){  
        var nik_user = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{EDITUSERNIK:nik_user},  
            dataType:"json",  
            success:function(data){
                $('#edt_nik').val(data.nik);
                $('#edt_username').val(data.username.toUpperCase());
                $('#edt_fullname').val(data.full_name);

                if (data.id_level != null) {

                    $('#edt_userlevel').find('option[value="'+data.id_level+'"]').remove();

                    $('#edt_userlevel').append($('<option></option>').html(data.level_name).attr('value', data.id_level).prop('selected', true));

                }

                $('#editModalUser').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_useraktivasi', function(){  
        var nik_user = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEUSERNIK:nik_user},  
            dataType:"json",  
            success:function(data){
                $('#delnik').val(data.nik);
                
                $('#del-labelnik').html("Delete User NIK : "+data.nik);
                $('#deleteModalUser').modal('show');
            }  
        });
    });
});

$(document).ready(function() {
    // check / uncheck all
    var checkAll = $('input#checkalluser');
    var checkboxes = $('input[name="checkidnik[]"]');

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', true);
        } 
        else {
            checkAll.prop('checked', false);
        }
        checkAll.iCheck('update');
    });
});

function validateForm(aksi) {
    var count_checked = $('input[name="checkidnik[]"]:checked');
    if (count_checked.length == 0) {
        alert("Please check at least one checkbox");
        return false;
    }
    else {
        var groupid = "<?= $id_group; ?>"
        var array = []
        for (var i = 0; i < count_checked.length; i++) {
            array.push(count_checked[i].value)
        }
        $.ajax({
            type:'POST',
            url:'action/datarequest.php',
            data: {EDITUSERSCHECKBOX:array, EDITGROUPCHECKBOX:groupid},
            success:function(data){
                if (aksi == "EDIT") {
                    $('#table-edtusers-check').html(data);
                    $('#updateuserscheck').modal('show');
                }
            }
        });
    }
}
</script>

<?php
    include ("includes/templates/alert.php");
?>