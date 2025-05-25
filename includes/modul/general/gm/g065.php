<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertIPSegment($_POST) > 0 ){
        $datapost = $_POST["name_iseg"];
        $alert = array("Success!", "Data Segment IP ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteIPSegment($_POST)){
        $datapost = $_POST["name_iseg"];
        $alert = array("Success!", "Data Segment IP ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Tabel Master IP Segment</h4>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrysubdivisi">Entry IP Segment</button>
                                    <div class="modal fade text-left" id="entrysubdivisi" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data IP Segment</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Office : </label>
                                                                <select type="text" name="office_iseg" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_off = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$idoffice'");
                                                                        while($data_off = mysqli_fetch_assoc($query_off)) {
                                                                    ?>
                                                                        <option value="<?= $data_off['id_office'];?>"><?= $data_off['id_office'].' - '.strtoupper($data_off['office_name']); ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Department : </label>
                                                                <select type="text" name="dept_iseg" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_dep = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$iddept'");
                                                                        while($data_dep = mysqli_fetch_assoc($query_dep)) {
                                                                    ?>
                                                                        <option value="<?= $data_dep['id_department'];?>"><?= strtoupper($data_dep['department_name']); ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Segment Name : </label>
                                                                <input type="text" name="name_iseg" placeholder="Input Nama Segment" class="form-control" required>
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
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Segment Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT A.*, B.office_name, C.department_name FROM ip_segment AS A
                            INNER JOIN office AS B ON A.office_iseg = B.id_office
                            INNER JOIN department AS C ON A.dept_iseg = C.id_department                           
                            WHERE A.office_iseg = '$idoffice' AND A.dept_iseg = '$iddept'";
                            $query = mysqli_query($conn, $sql);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['office_iseg']." - ".strtoupper($data['office_name']); ?></td>
                                    <td><?= strtoupper($data['department_name']); ?></td>
                                    <td><?= $data['name_iseg']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data['id_iseg']; ?>"><i class="ft-delete"></i></button>
                                    </td>
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="delete<?= $data['id_iseg']; ?>" role="dialog"
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
                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="id_iseg" value="<?= $data['id_iseg']; ?>">
                                                    <input type="hidden" name="name_iseg" value="<?= $data['name_iseg']; ?>">
                                                    <label>Are you sure to delete this data <?= $data['name_iseg']; ?></label>
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

<?php
    include ("includes/templates/alert.php");
?>