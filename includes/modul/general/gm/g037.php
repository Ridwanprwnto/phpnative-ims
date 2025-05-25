<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdata"])){
    if(InsertMasterDAT($_POST) > 0 ){
        $datapost1 = isset($_POST["nomor-dat"]) ? $_POST["nomor-dat"] : NULL;
        $datapost2 = isset($_POST["barang-dat"]) ? $_POST["barang-dat"] : NULL;
        $alert = array("Success!", "Master DAT ".$datapost1." Barang ".$datapost2." Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateMasterDAT($_POST) > 0 ){
        $datapost = isset($_POST["nomor-updkepdat"]) ? $_POST["nomor-updkepdat"] : NULL;
        $alert = array("Success!", "Master DAT ".$datapost." Berhasil Diupdate", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteMasterDAT($_POST) > 0 ){
        $datapost = isset($_POST["nomor-delkepdat"]) ? $_POST["nomor-delkepdat"] : NULL;
        $alert = array("Success!", "Master DAT ".$datapost." Berhasil Didelete", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["uploaddata"])){
    if(UploadMasterDAT($_POST) > 0 ){
        $alert = array("Success!", "Master DAT Berhasil Diupload", "success", "$redirect");
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
                    <h4 class="card-title">Data Master Kepemilikan DAT</h4>
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
                                    <form method="post" action="reporting/report-file-masterdat.php" target="_blank">
                                        <input type="hidden" name="office-cetak" value="<?= $idoffice; ?>" class="form-control" readonly>
                                        <input type="hidden" name="dept-cetak" value="<?= $iddept; ?>" class="form-control" readonly>
                                        <button type="button" class="btn btn-primary btn-min-width ml-2 mr-1 mb-2" data-toggle="modal" data-target="#modalEntryDAT">Entry Master DAT</button>
                                        <button type="submit" class="btn btn-secondary btn-min-width mr-1 mb-2" name="exportdata" >Download Master DAT</button>
                                        <button type="button" class="btn btn-secondary btn-min-width mb-2 " data-toggle="modal" data-target="#modalUploadDAT">Upload Master DAT</button>
                                    </form>
                                    <div class="modal fade text-left" id="modalEntryDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Kepemilikan Data Aktiva Tetap</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-dat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="office-dat" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept-dat" value="<?= $iddept; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tanggal Perolehan : </label>
                                                                <input type="date" name="perolehan-dat" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor Aktiva : </label>
                                                                <input type="text" name="nomor-dat" placeholder="Input nomor aktiva" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Qty Aktiva : </label>
                                                                <input type="number" name="qty-dat" placeholder="Input qty aktiva" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Master Barang : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="barang-dat" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $sql = "SELECT A.*, B.* FROM masterjenis AS A INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang ORDER BY B.NamaBarang ASC";
                                                                        $query = mysqli_query($conn, $sql);
                                                                        while($data = mysqli_fetch_assoc($query)) { ?>
                                                                            <option value="<?= $data['IDBarang'].$data['IDJenis']; ?>">
                                                                                <?= $data['IDBarang'].$data['IDJenis']." - ".$data['NamaBarang']." ".$data['NamaJenis'];?>
                                                                            </option>
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
                                    <!-- Import Modal -->
                                    <div class="modal fade text-left" id="modalUploadDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-secondary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Upload Data Master DAT</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page" value="<?= $redirect; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="office" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept" value="<?= $iddept; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>File : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="file-import" required>
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="uploaddata" class="btn btn-outline-primary">Upload</button>
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
                                    <th>Nomor Aktiva</th>
                                    <th>Tanggal Perolehan</th>
                                    <th>Qty Aktiva</th>
                                    <th>Kode - Kategori Barang</th>
                                    <th>Status DAT</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $result = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.*, E.* FROM dat AS A
                                INNER JOIN office AS B ON A.office_dat = B.id_office
                                INNER JOIN department AS C ON A.dept_dat = C.id_department
                                INNER JOIN mastercategory AS D ON LEFT(A.pluid_dat, 6) = D.IDBarang
                                INNER JOIN masterjenis AS E ON RIGHT(A.pluid_dat, 4) = E.IDJenis
                                WHERE A.office_dat = '$idoffice' AND A.dept_dat = '$iddept'";
                                $query = mysqli_query($conn, $result);
                                while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['no_dat']; ?></td>
                                    <td><?= $data['perolehan_dat']; ?></td>
                                    <td><?= $data['qty_dat']; ?></td>
                                    <td><?= $data['pluid_dat']." - ".$data['NamaBarang']." ".$data['NamaJenis'];; ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['status_dat'] == "Y" ? "info" : "danger"; ?> label-square">
                                            <i class="ft-info font-medium-2"></i>
                                            <span><?= $data['status_dat'] == "Y" ? "AKTIF" : "NON AKTIF"; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <!-- Icon Button dropdowns -->
                                        <div class="btn-group mb-1">
                                            <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item update_kep_dat" href="#" title="Edit Kepemilikan DAT Nomor <?= $data['no_dat']; ?>" name="update_kep_dat" id="<?= $data["id_dat"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Kepemilikan DAT</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_kep_dat" href="#" title="Hapus Kepemilikan DAT Nomor <?= $data['no_dat']; ?>" name="delete_kep_dat" id="<?= $data["id_dat"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Kepemilikan DAT</a>
                                            </div>
                                        </div>
                                        <!-- /btn-group -->
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                        </table>
                        <!-- Modal Update Kepemilikan DAT -->
                        <div class="modal fade text-left" id="modalUpdateDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="label-updkepdat"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-updkepdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="id-updkepdat" id="id-updkepdat" class="form-control" readonly>
                                                <input type="hidden" name="office-updkepdat" id="office-updkepdat" class="form-control" readonly>
                                                <input type="hidden" name="dept-updkepdat" id="dept-updkepdat" class="form-control" readonly>
                                                <input type="hidden" name="oldnomor-updkepdat" id="oldnomor-updkepdat" class="form-control" readonly>
                                                <input type="hidden" name="oldstatus-updkepdat" id="oldstatus-updkepdat" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tanggal Perolehan : </label>
                                                    <input type="date" name="tgl-updkepdat" id="tgl-updkepdat" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nomor Aktiva : </label>
                                                    <input type="text" name="nomor-updkepdat" id="nomor-updkepdat" placeholder="Input nomor aktiva" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Qty Aktiva : </label>
                                                    <input type="number" name="qty-updkepdat" id="qty-updkepdat" placeholder="Input qty aktiva" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Master Barang : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="barang-updkepdat" id="barang-updkepdat" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $sql_upd_kdat = "SELECT A.*, B.* FROM masterjenis AS A INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang ORDER BY B.NamaBarang ASC";
                                                            $query_upd_kdat = mysqli_query($conn, $sql_upd_kdat);
                                                            while($dat_upd_kdat = mysqli_fetch_assoc($query_upd_kdat)) { ?>
                                                                <option value="<?= $dat_upd_kdat['IDBarang'].$dat_upd_kdat['IDJenis']; ?>">
                                                                    <?= $dat_upd_kdat['IDBarang'].$dat_upd_kdat['IDJenis']." - ".$dat_upd_kdat['NamaBarang']." ".$dat_upd_kdat['NamaJenis'];?>
                                                                </option>
                                                            <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                                if ($id_group == $admin) { ?>
                                                <div class="col-md-12 mb-2">
                                                    <label>Status DAT : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="status-updkepdat" id="status-updkepdat" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="Y">AKTIF</option>
                                                        <option value="N">TIDAK AKTIF</option>
                                                    </select>
                                                </div>
                                                <?php } ?>
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
                        <!-- Modal Delete Kepemilikan DAT -->
                        <div class="modal fade text-left" id="modalDeleteDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-delkepdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                            <input type="hidden" name="id-delkepdat" id="id-delkepdat" class="form-control" readonly>
                                            <input type="hidden" name="office-delkepdat" id="office-delkepdat" class="form-control" readonly>
                                            <input type="hidden" name="dept-delkepdat" id="dept-delkepdat" class="form-control" readonly>
                                            <input type="hidden" name="barang-delkepdat" id="barang-delkepdat" class="form-control" readonly>
                                            <input type="hidden" name="nomor-delkepdat" id="nomor-delkepdat" class="form-control" readonly>
                                            <label id="label-delkepdat"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedata" class="btn btn-outline-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.update_kep_dat', function(){  
        var nomor_dat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIKEPDAT:nomor_dat},  
            dataType:"json",  
            success:function(data){
                $('#id-updkepdat').val(data.id_dat);
                $('#office-updkepdat').val(data.office_dat);
                $('#dept-updkepdat').val(data.dept_dat);
                $('#tgl-updkepdat').val(data.perolehan_dat);
                $('#oldnomor-updkepdat').val(data.no_dat);
                $('#nomor-updkepdat').val(data.no_dat);
                $('#qty-updkepdat').val(data.qty_dat);
                $('#oldstatus-updkepdat').val(data.status_dat);

                $('#barang-updkepdat').find('option[value="'+data.pluid_dat+'"]').remove();
                $('#barang-updkepdat').append($('<option></option>').html(data.pluid_dat+" - "+data.desc_dat).attr('value', data.pluid_dat).prop('selected', true));
                
                $('#status-updkepdat').find('option[value="'+data.status_dat+'"]').remove();
                $('#status-updkepdat').append($('<option></option>').html(data.status_dat == "Y" ? "AKTIF" : "TIDAK AKTIF").attr('value', data.status_dat).prop('selected', true));

                $('#label-updkepdat').html("Update Kepemilikan DAT Nomor : "+data.no_dat);
                $('#modalUpdateDAT').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_kep_dat', function(){  
        var nomor_dat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIKEPDAT:nomor_dat},  
            dataType:"json",  
            success:function(data){
                $('#id-delkepdat').val(data.id_dat);
                $('#office-delkepdat').val(data.office_dat);
                $('#dept-delkepdat').val(data.dept_dat);
                $('#nomor-delkepdat').val(data.no_dat);
                $('#barang-delkepdat').val(data.pluid_dat);
                
                $('#label-delkepdat').html("Nomor Seri DAT : "+data.no_dat);
                $('#modalDeleteDAT').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>