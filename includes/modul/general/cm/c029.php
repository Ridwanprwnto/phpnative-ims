<?php
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];

    $page_id = $_GET['page'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["insertdata"])){
        if(InsertSign($_POST) > 0 ){
            $alert = array("Success!", "Data Signature Berhasil Ditambah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["updatedata"])){
        if(UpdateSign($_POST)){
            $alert = array("Success!", "Data Signature Berhasil Dirubah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        if(DeleteSign($_POST)){
            $alert = array("Success!", "Data Signature Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Signature</h4>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrydatasign">Entry Data</button>
                                    <!-- Insert Modal -->
                                    <div class="modal fade text-left" id="entrydatasign" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Entry Data Signature</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="page-sign" value="<?= $encpid; ?>" readonly>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Office : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="office-sign" required>
                                                                    <option selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$office_id'");
                                                                    while($data_off = mysqli_fetch_assoc($query_off)) {
                                                                    ?>
                                                                    <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Department : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="dept-sign" required>
                                                                    <option selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id'");
                                                                    while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                                    ?>
                                                                    <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Deputy Name : </label>
                                                                <input type="text" name="deputy-name" placeholder="Nama Bagian Deputy Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Deputy Manager : </label>
                                                                <input type="text" name="deputy-sign" placeholder="Inisial Deputy Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Department Name : </label>
                                                                <input type="text" name="head-name" placeholder="Nama Bagian Dept Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Department Manager : </label>
                                                                <input type="text" name="head-sign" placeholder="Inisial Dept Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head VUM Name : </label>
                                                                <input type="text" name="vum-name" placeholder="Nama Bagian Head VUM Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head VUM Manager : </label>
                                                                <input type="text" name="vum-sign" placeholder="Inisial Head VUM Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head Department Name : </label>
                                                                <input type="text" name="area-name" placeholder="Nama Bagian Head Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head Department Manager : </label>
                                                                <input type="text" name="area-sign" placeholder="Inisial Head Dept Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Region Name : </label>
                                                                <input type="text" name="reg-name" placeholder="Nama Bagian Region Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Region Manager : </label>
                                                                <input type="text" name="reg-sign" placeholder="Inisial Region Manager 3 Digit" class="form-control" required>
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
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kantor Department</th>
                                    <th>Deputy Manager</th>
                                    <th>Department Manager</th>
                                    <th>Head VUM Manager</th>
                                    <th>Head Department Manager</th>
                                    <th>Region Manager</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $sql_sign = "SELECT signature.*, office.id_office, office.office_name, department.id_department, department.department_name FROM signature 
                                INNER JOIN office ON signature.office_sign = office.id_office
                                INNER JOIN department ON signature.dept_sign = department.id_department
                                WHERE signature.office_sign = '$office_id' AND signature.dept_sign = '$dept_id'";
                                $query_sign = mysqli_query($conn, $sql_sign);
                                while ($data_sign = mysqli_fetch_assoc($query_sign)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $data_sign["office_sign"]." - ".strtoupper($data_sign["office_name"])." ".strtoupper($data_sign["department_name"]); ?></td>
                                    <td><?= $data_sign["initial_deputy_sign"]; ?></td>
                                    <td><?= $data_sign["initial_dept_sign"]; ?></td>
                                    <td><?= $data_sign["initial_vum_sign"]; ?></td>
                                    <td><?= $data_sign["initial_head_sign"]; ?></td>
                                    <td><?= $data_sign["initial_reg_sign"]; ?></td>
                                    <td>
                                        <button title="Update Data" type="button" class="btn btn-icon btn-success" data-toggle="modal" data-target="#update<?= $data_sign["id_sign"];?>"><i class="ft-edit"></i></button>
                                        <button title="Delete Data" type="button" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data_sign["id_sign"];?>"><i class="ft-delete"></i></button>
                                    </td>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="update<?= $data_sign['id_sign']; ?>" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Edit Data Signature</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" name="page-sign" value="<?= $encpid; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="id-sign" value="<?= $data_sign["id_sign"];?>" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kantor : </label>
                                                                <input type="text" value="<?= $data_sign["office_sign"]." - ".strtoupper($data_sign["office_name"])." ".strtoupper($data_sign["department_name"]); ?>" class="form-control" disabled>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Deputy Name : </label>
                                                                <input type="text" name="deputy-name" value="<?= $data_sign['name_deputy_sign']; ?>" placeholder="Nama Bagian Deputy Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Deputy Manager : </label>
                                                                <input type="text" name="deputy-sign" value="<?= $data_sign['initial_deputy_sign']; ?>" placeholder="Inisial Deputy Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Department Name : </label>
                                                                <input type="text" name="head-name" value="<?= $data_sign['name_dept_sign']; ?>" placeholder="Nama Bagian Dept Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Department Manager : </label>
                                                                <input type="text" name="head-sign" value="<?= $data_sign['initial_dept_sign']; ?>" placeholder="Inisial Dept Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head VUM Name : </label>
                                                                <input type="text" name="vum-name" value="<?= $data_sign['name_vum_sign']; ?>" placeholder="Nama Bagian Head Dept Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head VUM Manager : </label>
                                                                <input type="text" name="vum-sign" value="<?= $data_sign['initial_vum_sign']; ?>" placeholder="Inisial Head Dept Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head Department Name : </label>
                                                                <input type="text" name="area-name" value="<?= $data_sign['name_head_sign']; ?>" placeholder="Nama Bagian Head Dept Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Head Department Manager : </label>
                                                                <input type="text" name="area-sign" value="<?= $data_sign['initial_head_sign']; ?>" placeholder="Inisial Head Dept Manager 3 Digit" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Region Name : </label>
                                                                <input type="text" name="reg-name" value="<?= $data_sign['name_reg_sign']; ?>" placeholder="Nama Bagian Region Manager" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Region Manager : </label>
                                                                <input type="text" name="reg-sign" value="<?= $data_sign['initial_reg_sign']; ?>" placeholder="Inisial Region Manager 3 Digit" class="form-control" required>
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
                                    <div class="modal fade text-left" id="delete<?= $data_sign['id_sign']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form action="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger white">
                                                        <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input class="form-control" type="hidden" name="id-sign" value="<?= $data_sign["id_sign"];?>">
                                                        <label>Apakah anda yakin ingin menghapus data id <?= $data_sign["id_sign"];?></label>
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
<!-- // Basic form layout section end -->

<?php
    include ("includes/templates/alert.php");
?>
