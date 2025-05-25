<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdatajenis"])){
    if(InsertJenisMobil($_POST) > 0 ){
        $datapost = $_POST["jenis-mobil"];
        $alert = array("Success!", "Data Jenis Kendaraan ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatajenis"])){
    if(DeleteJenisMobil($_POST) > 0 ){
        $datapost = $_POST["del-namejnsmobil"];
        $alert = array("Success!", "Data Jenis Kendaraan ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdata"])){
    if(InsertMobil($_POST) > 0 ){
        $datapost = $_POST["no-mobil"];
        $alert = array("Success!", "Data Master Kendaraan ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateMobil($_POST) > 0 ){
        $datapost = $_POST["nomor-mobil"];
        $alert = array("Success!", "Data Master Kendaraan ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteMobil($_POST)){
        $datapost = $_POST["no-mobil"];
        $alert = array("Success!", "Data Master Kendaraan ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Data Master Delivery Van</h4>
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
                                <a class="nav-link active" id="jenis-delivan" data-toggle="tab" href="#jenisdelivan" aria-expanded="true">Jenis Delivery Van</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="master-delivan" data-toggle="tab" href="#masterdelivan" aria-expanded="false">Master Delivery Van</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="jenisdelivan" aria-expanded="true" aria-labelledby="jenis-delivan">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                        <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryjenismobil" <?= $id_group == $admin ? "" : "disabled"; ?>>Entry Jenis Delivan</button>
                                            <div class="modal fade text-left" id="entryjenismobil" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Input Data Jenis Delivery Van</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jenis Mobil : </label>
                                                                        <input type="text" name="jenis-mobil" placeholder="Input jenis mobil" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatajenis" class="btn btn-outline-primary">Save</button>
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
                                            <th>Jenis Mobil</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $noj = 1;
                                        $sql_jenis = "SELECT * FROM jenis_mobil";
                                        $query_jenis = mysqli_query($conn, $sql_jenis);
                                        while($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                                    ?>
                                        <tr>
                                            <td><?= $noj++; ?></td>
                                            <td><?= $data_jenis['no_jns_mobil']." - ".$data_jenis['name_jns_mobil']; ?></td>
                                            <td>
                                                <button type="button" title="Delete Data Jenis Kendaraan <?= $data_jenis['name_jns_mobil']; ?>" class="btn btn-icon btn-danger delete_jenisdelivan" name="delete_jenisdelivan" id="<?= $data_jenis['id_jns_mobil']; ?>" <?= $id_group == $admin ? "" : "disabled"; ?>><i class="ft-delete"></i></button>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalJenisKendaraan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" class="form-control" id="del-idjnsmobil" name="del-idjnsmobil" readonly>
                                                    <input type="hidden" class="form-control" id="del-namejnsmobil" name="del-namejnsmobil" readonly>
                                                    <label id="del-labeljnsmobil"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatajenis" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="masterdelivan" aria-labelledby="master-delivan">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                        <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entrymobil">Entry Master Delivan</button>
                                            <div class="modal fade text-left" id="entrymobil" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Input Data Master Delivery Van</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page-mobil" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <input type="hidden" name="office-mobil" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jenis Mobil : </label>
                                                                        <select name="jenis-mobil" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_jenismbl = mysqli_query($conn, "SELECT * FROM jenis_mobil");
                                                                                while($data_jenismbl = mysqli_fetch_assoc($query_jenismbl)) {
                                                                            ?>
                                                                                <option value="<?= $data_jenismbl['no_jns_mobil'];?>"><?= $data_jenismbl['no_jns_mobil'].' - '.$data_jenismbl['name_jns_mobil']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Type Mobil : </label>
                                                                        <select name="type-mobil" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_type = mysqli_query($conn, "SELECT * FROM tipe_mobil");
                                                                                while($data_type = mysqli_fetch_assoc($query_type)) {
                                                                            ?>
                                                                                <option value="<?= $data_type['kode_type_mobil'];?>"><?= $data_type['kode_type_mobil'].' - '.$data_type['nama_type_mobil']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nomor Mobil : </label>
                                                                        <input type="text" name="no-mobil" placeholder="3 Digit Angka Nomor Mobil" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nomor Polisi : </label>
                                                                        <input type="text" name="no-polisi"
                                                                                placeholder="Input Nomor Polisi (Optional)" class="form-control">
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
                                            <th>Jenis Mobil</th>
                                            <th>Type Mobil</th>
                                            <th>Nomor Mobil</th>
                                            <th>Nomor Polisi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $nom = 1;
                                        $sql = "SELECT A.*, B.*, C.*, D.name_jns_mobil FROM mobil AS A 
                                        INNER JOIN office AS B ON A.office_mobil = B.id_office
                                        INNER JOIN tipe_mobil AS C ON A.type_kode_mobil = C.kode_type_mobil
                                        LEFT JOIN jenis_mobil AS D ON A.jenis_mobil = D.no_jns_mobil 
                                        WHERE A.office_mobil = '$idoffice'";
                                        $query = mysqli_query($conn, $sql);
                                        while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                            <tr>
                                                <td><?= $nom++; ?></td>
                                                <td><?= $data['jenis_mobil']." - ".$data['name_jns_mobil']; ?></td>
                                                <td><?= $data['type_kode_mobil']." - ".$data['nama_type_mobil']; ?></td>
                                                <td><?= $data['office_shortname']." - ".$data['no_mobil']; ?></td>
                                                <td><?= $data['nopol_mobil']; ?></td>
                                            <td>
                                                <button type="button" title="Edit Data Kendaraan Nomor <?= $data['office_shortname'].' - '.$data['no_mobil']; ?>" class="btn btn-icon btn-success update_delivan" name="update_delivan" id="<?= $data['id_mobil']; ?>"><i class="ft-edit"></i></button>
                                                <button type="button" title="Delete Data Kendaraan Nomor <?= $data['office_shortname'].' - '.$data['no_mobil']; ?>" class="btn btn-icon btn-danger delete_delivan" name="delete_delivan" id="<?= $data['id_mobil']; ?>"><i class="ft-delete"></i></button>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalKendaraan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <input type="hidden" name="page-mobil" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" id="office-mobil" name="office-mobil" class="form-control" readonly>
                                                            <input type="hidden" id="id-mobil" name="id-mobil" class="form-control" readonly>
                                                            <input type="hidden" id="nomor-mobilold" name="nomor-mobilold" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Jenis Mobil :</label>
                                                                <select id="id-jenis-mobil" name="jenis-mobil" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_jenismbl = mysqli_query($conn, "SELECT * FROM jenis_mobil");
                                                                    while($data_jenismbl = mysqli_fetch_assoc($query_jenismbl)) {
                                                                ?>
                                                                    <option value="<?= $data_jenismbl['no_jns_mobil']; ?>"><?= $data_jenismbl['no_jns_mobil']." - ".$data_jenismbl['name_jns_mobil'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Type Mobil :</label>
                                                                <select id="type-mobil" name="type-mobil" class="select2 form-control block" style="width: 100%" type="text">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $sql_typembl = mysqli_query($conn, "SELECT * FROM tipe_mobil ");
                                                                    while($data_typembl = mysqli_fetch_assoc($sql_typembl)) {
                                                                ?>
                                                                    <option value="<?= $data_typembl['kode_type_mobil']; ?>"><?= $data_typembl['kode_type_mobil']." - ".$data_typembl['nama_type_mobil'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor Mobil : </label>
                                                                <input type="text" id="nomor-mobil" name="nomor-mobil" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor Polisi : </label>
                                                                <input type="text" id="nopol-mobil" name="nopol-mobil" class="form-control">
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
                                    <div class="modal fade text-left" id="deleteModalKendaraan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" id="del-idmobil" name="id-mobil" readonly>
                                                    <input type="hidden" id="del-nomobil" name="no-mobil" readonly>
                                                    <label id="del-labelmobil"></label>
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
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.delete_jenisdelivan', function(){  
        var id_mobil = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONJENISDELIVAN:id_mobil},  
            dataType:"json",  
            success:function(data){
                $('#del-idjnsmobil').val(data.no_jns_mobil);
                $('#del-namejnsmobil').val(data.name_jns_mobil);
                
                $('#del-labeljnsmobil').html("Delete Jenis Kendaraan "+data.no_jns_mobil+" - "+data.name_jns_mobil);
                $('#deleteModalJenisKendaraan').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_delivan', function(){  
        var id_mobil = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEDELIVAN:id_mobil},  
            dataType:"json",  
            success:function(data){
                $('#id-mobil').val(data.id_mobil);
                $('#office-mobil').val(data.office_mobil);
                $('#nomor-mobilold').val(data.no_mobil);
                $('#nomor-mobil').val(data.no_mobil);
                $('#nopol-mobil').val(data.nopol_mobil);

                
                if (data.jenis_mobil != null) {

                    $('#id-jenis-mobil').find('option[value="'+data.jenis_mobil+'"]').remove();
                    $('#id-jenis-mobil').append($('<option></option>').html(data.jenis_mobil+" - "+data.name_jns_mobil).attr('value', data.jenis_mobil).prop('selected', true));

                }

                if (data.type_kode_mobil != null) {

                    $('#type-mobil').find('option[value="'+data.type_kode_mobil+'"]').remove();
                    $('#type-mobil').append($('<option></option>').html(data.type_kode_mobil+" - "+data.nama_type_mobil).attr('value', data.type_kode_mobil).prop('selected', true));

                }

                $('#updateModalKendaraan').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_delivan', function(){  
        var id_mobil = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEDELIVAN:id_mobil},  
            dataType:"json",  
            success:function(data){
                $('#del-idmobil').val(data.id_mobil);
                $('#del-nomobil').val(data.no_mobil);
                
                $('#del-labelmobil').html("Delete Kendaraan Nomor "+data.office_shortname+" - "+data.no_mobil);
                $('#deleteModalKendaraan').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>