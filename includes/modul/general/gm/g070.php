<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insert_areacctv($_POST) > 0 ){
        $datapost = isset($_POST["dvr-area"]) ? $_POST["dvr-area"] : NULL;
        $alert = array("Success!", "Data Area CCTV ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(delete_areacctv($_POST)){
        $datapost = isset($_POST["ip-area"]) ? $_POST["ip-area"] : NULL;
        $alert = array("Success!", "Data Area CCTV ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Area Lokasi</h4>
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
                                        data-toggle="modal" data-target="#entrylayapar">Entry Data</button>
                                    <div class="modal fade text-left" id="entrylayapar" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Entry Data Area</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="office-area" value="<?= $idoffice; ?>">
                                                            <input class="form-control" type="hidden" name="dept-area" value="<?= $iddept; ?>">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Area Lokasi : </label>
                                                                <select type="text" name="divisi-area" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_div = mysqli_query($conn, "SELECT * FROM divisi");
                                                                        while($data_div = mysqli_fetch_assoc($query_div)) {
                                                                    ?>
                                                                        <option value="<?= $data_div['id_divisi'];?>"><?= $data_div['divisi_name']; ?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kode Area : </label>
                                                                <input type="text" name="kode-area" placeholder="Input 1 Digit Kode CCTV DVR Berdasarkan Area" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>IP Server DVR : </label>
                                                                <input type="text" name="dvr-area" placeholder="Input Server CCTV DVR Area" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Channel Server DVR : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="channel-area" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_ch = mysqli_query($conn, "SELECT * FROM dvr_channel");
                                                                        while($data_ch = mysqli_fetch_assoc($query_ch)) {
                                                                    ?>
                                                                        <option value="<?= $data_ch['id_dvr_ch'];?>"><?= $data_ch['jumlah_dvr_ch']; ?></option>
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
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Divisi Area</th>
                                    <th>IP DVR</th>
                                    <th>Channel DVR</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $result = "SELECT A.*, B.office_name, C.department_name, D.*, E.* FROM area_cctv AS A 
                            INNER JOIN office AS B ON A.office_area_cctv = B.id_office
                            INNER JOIN department AS C ON A.dept_area_cctv = C.id_department
                            INNER JOIN divisi AS D ON A.divisi_area_cctv = D.id_divisi
                            INNER JOIN dvr_channel AS E ON A.ch_area_cctv = E.id_dvr_ch
                            WHERE A.office_area_cctv = '$idoffice' AND A.dept_area_cctv = '$iddept'";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['kode_area_cctv']." - ".$data['divisi_name']; ?></td>
                                    <td><?= $data['ip_area_cctv']; ?></td>
                                    <td><?= $data['jumlah_dvr_ch']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger delete_areacctv"  name="delete_areacctv" id="<?= $data["id_area_cctv"]; ?>" title="Delete Area CCTV IP : <?= $data['ip_area_cctv']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalAreaCCTV" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                            <input type="hidden" class="form-control" id="id-delareacctv" name="id-area" readonly>
                                            <input type="hidden" class="form-control" id="ip-delareacctv" name="ip-area" readonly>
                                            <label id="label-delareacctv"></label>
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
    $(document).on('click', '.delete_areacctv', function(){  
        var id_cctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEAREACCTV:id_cctv},  
            dataType:"json",  
            success:function(data){
                $('#id-delareacctv').val(data.id_area_cctv);
                $('#ip-delareacctv').val(data.ip_area_cctv);
                
                $('#label-delareacctv').html("Delete Area CCTV IP "+data.ip_area_cctv);
                $('#deleteModalAreaCCTV').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>