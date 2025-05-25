<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertIPAddress($_POST) > 0 ){
        $datapost = $_POST["ip_ipad"];
        $alert = array("Success!", "Data IP ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateIPAddress($_POST)){
        $datapost = $_POST["updip_ipad"];
        $alert = array("Success!", "Data IP ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteIPAddress($_POST)){
        $datapost = $_POST["delip_ipad"];
        $alert = array("Success!", "Data IP ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Tabel Master IP Address</h4>
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
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#insert">Entry IP Address</button>
                                    <!-- Modal Insert -->
                                    <div class="modal fade text-left" id="insert" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data IP Address</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                <input type="hidden" name="office_ipad" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                                <input type="hidden" name="dept_ipad" value="<?= $iddept; ?>" class="form-control" readonly>
                                                                <label>Segment : </label>
                                                                <select type="text" name="seg_ipad" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_seg = mysqli_query($conn, "SELECT * FROM ip_segment WHERE office_iseg = '$idoffice' AND dept_iseg = '$iddept'");
                                                                        while($data_seg = mysqli_fetch_assoc($query_seg)) {
                                                                    ?>
                                                                        <option value="<?= $data_seg['id_iseg'];?>"><?= $data_seg['name_iseg']; ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>IP Address : </label>
                                                                <input type="text" name="ip_ipad" placeholder="Input IP Address" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Name / User : </label>
                                                                <input type="text" name="name_ipad" placeholder="Input Nama Atau User" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Status IP : </label>
                                                                <select type="text" name="status_ipad" class="select2 form-control block" style="width: 100%">
                                                                    <option value="none" selected disabled>Please Select</option>
                                                                    <option value="Y">Aktif</option>
                                                                    <option value="N">Non Aktif</option>
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
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Segment</th>
                                    <th>IP Address</th>
                                    <th>Names / Users</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql = "SELECT A.*, B.office_name, C.department_name, D.name_iseg FROM ip_address AS A
                            INNER JOIN office AS B ON A.office_ipad = B.id_office
                            INNER JOIN department AS C ON A.dept_ipad = C.id_department
                            INNER JOIN ip_segment AS D ON A.seg_ipad = D.id_iseg
                            WHERE A.office_ipad = '$idoffice' AND A.dept_ipad = '$iddept'";
                            $query = mysqli_query($conn, $sql);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['name_iseg']; ?></td>
                                    <td><?= $data['ip_ipad']; ?></td>
                                    <td><?= $data['name_ipad']; ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['status_ipad'] == 'Y' ? 'info' : 'danger'; ?> "><?= $data['status_ipad'] == 'Y' ? 'Active' : 'Non Active'; ?></div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success update_ipad" data-toggle="modal" name="update_ipad" id="<?= $data['id_ipad']; ?>"><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger delete_ipad" data-toggle="modal" name="delete_ipad" id="<?= $data['id_ipad']; ?>"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalIPAD" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="myModalLabel">Update Data IP Address</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-12 mb-2">
                                                        <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                        <input type="hidden" id="updid_ipad" name="updid_ipad" class="form-control" readonly>
                                                        <input type="hidden" id="updipold_ipad" name="updipold_ipad" class="form-control" readonly>
                                                        <label>Segment : </label>
                                                        <select type="text" id="updseg_ipad" name="updseg_ipad" class="select2 form-control block" style="width: 100%" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $query_segment = mysqli_query($conn, "SELECT * FROM ip_segment WHERE office_iseg = '$idoffice' AND dept_iseg = '$iddept'");
                                                                while($data_segment = mysqli_fetch_assoc($query_segment)) {
                                                            ?>
                                                                <option value="<?= $data_segment['id_iseg'];?>"><?= $data_segment['name_iseg']; ?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>IP Address : </label>
                                                        <input type="text" id="updip_ipad" name="updip_ipad" placeholder="Input IP Address" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Name / User : </label>
                                                        <input type="text" id="updname_ipad" name="updname_ipad" placeholder="Input Nama Atau User" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Status IP : </label>
                                                        <select type="text" id="updstatus_ipad" name="updstatus_ipad" class="select2 form-control block" style="width: 100%">
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
                            <div class="modal fade text-left" id="deleteModalIPAD" role="dialog"
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
                                            <input type="hidden" id="delid_ipad" name="delid_ipad" class="form-control" readonly>
                                            <input type="hidden" id="delip_ipad" name="delip_ipad" class="form-control" readonly>
                                            <label id="dellabel-ipad"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">No</button>
                                            <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
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
</section>
<!--/ Auto Fill table -->

<script>
    
$(document).ready(function(){
    $(document).on('click', '.update_ipad', function(){  
        var id_ipad = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEIPAD:id_ipad},  
            dataType:"json",  
            success:function(data){
                $('#updid_ipad').val(data.id_ipad);
                $('#updipold_ipad').val(data.ip_ipad);
                $('#updip_ipad').val(data.ip_ipad);
                $('#updname_ipad').val(data.name_ipad);
                
                $('#updseg_ipad').find('option[value="'+data.id_iseg+'"]').remove();
                $('#updstatus_ipad').find('option[value="'+data.status_ipad+'"]').remove();

                var $option_seg = $('<option></option>').html(data.name_iseg).attr('value', data.id_iseg).prop('selected', true);
                $('#updseg_ipad').append($option_seg);

                if (data.status_ipad == "Y") {
                    var $name_sts = "Active";
                }
                else {
                    var $name_sts = "Non Active";
                }

                var $option_sts = $('<option></option>').html($name_sts).attr('value', data.status_ipad).prop('selected', true);
                $('#updstatus_ipad').append($option_sts);

                $('#updateModalIPAD').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_ipad', function(){  
        var id_ipad = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEIPAD:id_ipad},  
            dataType:"json",  
            success:function(data){
                $('#delid_ipad').val(data.id_ipad);
                $('#delip_ipad').val(data.ip_ipad);
                
                $('#dellabel-ipad').html("Delete IP Address "+data.ip_ipad);
                $('#deleteModalIPAD').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>