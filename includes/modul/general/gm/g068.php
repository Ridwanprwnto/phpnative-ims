<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insert_layapar($_POST) > 0 ){
        $datapost = isset($_POST["lokasiname"]) ? $_POST["lokasiname"] : NULL;
        $alert = array("Success!", "Data Lokasi Apar ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(delete_layapar($_POST)){
        $datapost = isset($_POST["arealayout"]) ? $_POST["arealayout"] : NULL;
        $alert = array("Success!", "Data Lokasi Apar ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Layout Apar</h4>
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
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrylayapar">Entry Lokasi Apar</button>
                                    <!-- Modal -->
                                    <div class="modal fade text-left" id="entrylayapar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Entry Data</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="kodelayout" value="<?= autoid('2', '3', 'id_layout', 'layout_apar'); ?>">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Gudang Lokasi : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="officename" id="officename" required>
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
                                                                <label>Lokasi Penempatan : </label>
                                                                <input type="text" name="lokasiname" placeholder="Input Lokasi Penempatan" class="form-control" required>
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
                                    <th>Kode Layout</th>
                                    <th>Lokasi Penempatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result = "SELECT A.*, B.office_name FROM layout_apar AS A 
                            INNER JOIN office AS B ON A.id_office_layout = B.id_office
                            WHERE A.id_office_layout = '$idoffice'";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['id_layout']; ?></td>
                                    <td><?= $data['layout_name']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger delete_arealayapar"  name="delete_arealayapar" id="<?= $data["id_layout"]; ?>" title="Delete Area Layout Apar <?= $data['layout_name']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalLayApar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                            <input type="hidden" class="form-control" id="id-dellayapar" name="idlayout" readonly>
                                            <input type="hidden" class="form-control" id="area-dellayapar" name="arealayout" readonly>
                                            <label id="label-dellayapar"></label>
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
    $(document).on('click', '.delete_arealayapar', function(){  
        var id_apar = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEAREAAPAR:id_apar},  
            dataType:"json",  
            success:function(data){
                $('#id-dellayapar').val(data.id_layout);
                $('#area-dellayapar').val(data.layout_name);
                
                $('#label-dellayapar').html("Delete Area Layout Apar Lokasi "+data.id_layout);
                $('#deleteModalLayApar').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>