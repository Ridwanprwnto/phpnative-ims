<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insert_laycctv($_POST) > 0 ){
        $datapost = isset($_POST["lokasi-cctv"]) ? $_POST["lokasi-cctv"] : NULL;
        $alert = array("Success!", "Data Layouting CCTV ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(update_laycctv($_POST)){
        $datapost = isset($_POST["lokasi-cctv"]) ? $_POST["lokasi-cctv"] : NULL;
        $alert = array("Success!", "Data Layouting CCTV ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(delete_laycctv($_POST)){
        $datapost = isset($_POST["id-cctv"]) ? $_POST["id-cctv"] : NULL;
        $alert = array("Success!", "Data Layouting CCTV ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Layouting CCTV</h4>
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
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrylayapar">Entry Data</button>
                                    <div class="modal fade text-left" id="entrylayapar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data CCTV</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Area IP DVR : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="area-cctv" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php
                                                                    $query_div = "SELECT A.id_area_cctv, A.ip_area_cctv, B.* FROM area_cctv AS A
                                                                    INNER JOIN divisi AS B ON A.divisi_area_cctv = B.id_divisi
                                                                    WHERE A.office_area_cctv = '$idoffice' AND A.dept_area_cctv = '$iddept'";
                                                                    $result_div = mysqli_query($conn, $query_div);
                                                                    while($data_div = mysqli_fetch_assoc($result_div)) {
                                                                ?>
                                                                    <option value="<?= $data_div['id_area_cctv'];?>"><?= $data_div['divisi_name']." - ".$data_div['ip_area_cctv']; ?></option>
                                                                <?php 
                                                                    } 
                                                                ?>    
                                                            </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Channel : </label>
                                                                <input type="number" name="channel-cctv" placeholder="Input Port / Channel Format 2 Digit Angka" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Titik Penempatan : </label>
                                                                <input type="text" name="lokasi-cctv" placeholder="Input Titik Lokasi Penempatan CCTV" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kode Bagian : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="bagian-cctv" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_bag_i = mysqli_query($conn, "SELECT * FROM bagian_cctv");
                                                                        while($data_bag_i = mysqli_fetch_assoc($query_bag_i)) { ?>
                                                                        <option value="<?= $data_bag_i['kode_bag_cctv']; ?>"><?= $data_bag_i['kode_bag_cctv']." - ".$data_bag_i['name_bag_cctv'];?></option>
                                                                    <?php 
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kode Running Number : </label>
                                                                <input type="number" name="number-cctv" placeholder="Input Running Number 2 Digit Angka Berdasarkan Bagian" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Jenis CCTV : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="jenis-cctv" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_jen_i = mysqli_query($conn, "SELECT * FROM jenis_cctv");
                                                                        while($data_jen_i = mysqli_fetch_assoc($query_jen_i)) { ?>
                                                                        <option value="<?= $data_jen_i['name_jns_cctv']; ?>"><?= $data_jen_i['name_jns_cctv'];?></option>
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
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Area - IP</th>
                                    <th>Port / Channel</th>
                                    <th>Penempatan</th>
                                    <th>Nomor Layout</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT A.*, B.*, C.*, D.* FROM layout_cctv AS A
                            INNER JOIN area_cctv AS B ON A.head_id_area_cctv = B.id_area_cctv
                            INNER JOIN divisi AS C ON B.divisi_area_cctv = C.id_divisi
                            INNER JOIN bagian_cctv AS D ON A.kode_head_bag_cctv = D.kode_bag_cctv
                            WHERE B.office_area_cctv = '$idoffice' AND B.dept_area_cctv = '$iddept'";
                            $result = mysqli_query($conn, $query);
                            while($data = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['divisi_name']." - ".$data['ip_area_cctv']; ?></td>
                                    <td><?= $data['channel_lay_cctv']; ?></td>
                                    <td><?= $data['penempatan_lay_cctv']; ?></td>
                                    <td><?= $data['kode_head_bag_cctv'].".".$data['no_lay_cctv']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success update_layoutingcctv" name="update_layoutingcctv" id="<?= $data["id_lay_cctv"]; ?>" title="Update Data CCTV Nomor : <?= $data['kode_head_bag_cctv'].".".$data['no_lay_cctv']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                    <?php if ($id_group == $admin || $id_group == $support || $id_group == $cctv) { ?>
                                        <button type="button" class="btn btn-icon btn-danger delete_layoutingcctv" name="delete_layoutingcctv" id="<?= $data["id_lay_cctv"]; ?>" title="Delete Data CCTV Nomor : <?= $data['kode_head_bag_cctv'].".".$data['no_lay_cctv']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalLayoutCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form message="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="label-updlaycctv"></h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" id="id-updlaycctv" name="id-cctv" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Area IP DVR : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" id="area-updlaycctv" name="area-cctv" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            $sql_area = "SELECT A.id_area_cctv, A.ip_area_cctv, B.divisi_name FROM area_cctv AS A
                                                            INNER JOIN divisi AS B ON A.divisi_area_cctv = B.id_divisi
                                                            WHERE A.office_area_cctv = '$idoffice' AND A.dept_area_cctv = '$iddept'";
                                                            $query_area = mysqli_query($conn, $sql_area);
                                                            while($data_area = mysqli_fetch_assoc($query_area)) {
                                                        ?>
                                                            <option value="<?= $data_area['id_area_cctv'];?>"><?= $data_area['divisi_name']." - ".$data_area['ip_area_cctv']; ?></option>
                                                        <?php 
                                                            } 
                                                        ?>    
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Channel : </label>
                                                        <input type="number" id="channel-updlaycctv" name="channel-cctv" placeholder="Input Port / Channel Format 2 Digit Angka" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Titik Penempatan : </label>
                                                        <input type="text" id="lokasi-updlaycctv" name="lokasi-cctv" placeholder="Input Titik Lokasi Penempatan CCTV" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Kode Bagian : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" id="bagian-updlaycctv" name="bagian-cctv" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $query_bag = mysqli_query($conn, "SELECT * FROM bagian_cctv");
                                                                while($data_bag = mysqli_fetch_assoc($query_bag)) { ?>
                                                                <option value="<?= $data_bag['kode_bag_cctv']; ?>"><?= $data_bag['kode_bag_cctv'].". ".$data_bag['name_bag_cctv'];?></option>
                                                            <?php 
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Kode Number Layout : </label>
                                                        <input type="number" id="number-updlaycctv" name="number-cctv" placeholder="Input Running Number 2 Digit Angka Berdasarkan Bagian" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Jenis CCTV : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" id="jenis-updlaycctv" name="jenis-cctv" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $query_jen = mysqli_query($conn, "SELECT * FROM jenis_cctv");
                                                                while($data_jen = mysqli_fetch_assoc($query_jen)) { ?>
                                                                <option value="<?= $data_jen['name_jns_cctv']; ?>"><?= $data_jen['name_jns_cctv'];?></option>
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
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalLayoutCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
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
                                            <input type="hidden" id="id-dellaycctv" name="id-cctv" readonly>
                                            <label id="label-dellaycctv"></label>
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
    $(document).on('click', '.update_layoutingcctv', function(){  
        var id_cctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATELAYOUTINGCCTV:id_cctv},  
            dataType:"json",  
            success:function(data){
                $('#id-updlaycctv').val(data.id_lay_cctv);
                $('#label-updlaycctv').html("Update Data CCTV Nomor "+data.kode_head_bag_cctv+"."+data.no_lay_cctv);
                $('#bagian-updlaycctv').find('option[value="'+data.kode_bag_cctv+'"]').remove();
                $('#bagian-updlaycctv').append($('<option></option>').html(data.kode_bag_cctv+" - "+data.name_bag_cctv).attr('value', data.kode_bag_cctv).prop('selected', true));
                $('#number-updlaycctv').val(data.no_lay_cctv);
                $('#lokasi-updlaycctv').val(data.penempatan_lay_cctv);
                $('#channel-updlaycctv').val(data.channel_lay_cctv);

                $('#area-updlaycctv').find('option[value="'+data.id_area_cctv+'"]').remove();
                $('#area-updlaycctv').append($('<option></option>').html(data.divisi_name+" - "+data.ip_area_cctv).attr('value', data.id_area_cctv).prop('selected', true));

                $('#jenis-updlaycctv').find('option[value="'+data.jenis_lay_cctv+'"]').remove();
                $('#jenis-updlaycctv').append($('<option></option>').html(data.jenis_lay_cctv).attr('value', data.jenis_lay_cctv).prop('selected', true));

                $('#updateModalLayoutCCTV').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_layoutingcctv', function(){  
        var id_cctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETELAYOUTINGCCTV:id_cctv},  
            dataType:"json",  
            success:function(data){
                $('#id-dellaycctv').val(data.id_lay_cctv);
                
                $('#label-dellaycctv').html("Delete Layouting CCTV Nomor "+data.kode_head_bag_cctv+"."+data.no_lay_cctv+" - "+data.penempatan_lay_cctv);
                $('#deleteModalLayoutCCTV').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>