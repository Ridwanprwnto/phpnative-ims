<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdataindikator"])){
    if(InsertMasterIndikatorAssessment($_POST) > 0 ){
        $datapost = isset($_POST["name-indikator"]) ? $_POST["name-indikator"] : NULL;
        $alert = array("Success!", "Data Indikator Penilaian ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedataindikator"])){
    if(UpdateMasterIndikatorAssessment($_POST)){
        $datapost = isset($_POST["name-updindpenilaian"]) ? $_POST["name-updindpenilaian"] : NULL;
        $alert = array("Success!", "Data Indikator Penilaian ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedataindikator"])){
    if(DeleteMasterIndikatorAssessment($_POST)){
        $datapost = isset($_POST["name-delindpenilaian"]) ? $_POST["name-delindpenilaian"] : NULL;
        $alert = array("Success!", "Data Indikator Penilaian ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdatainstrumen"])){
    if(InsertMasterInstrumentAssessment($_POST) > 0 ){
        $datapost = isset($_POST["name-instrumen"]) ? $_POST["name-instrumen"] : NULL;
        $alert = array("Success!", "Data Instrumen Penilaian ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatainstrumen"])){
    if(UpdateMasterInstrumentAssessment($_POST)){
        $datapost = isset($_POST["id-updateinspenilaian"]) ? $_POST["id-updateinspenilaian"] : NULL;
        $alert = array("Success!", "Data Instrumen Penilaian ID ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatainstrumen"])){
    if(DeleteMasterInstrumentAssessment($_POST)){
        $datapost = isset($_POST["id-delinspenilaian"]) ? $_POST["id-delinspenilaian"] : NULL;
        $alert = array("Success!", "Data Instrumen Penilaian ID ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Instrument Assesment Tahunan</h4>
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
                                <a class="nav-link active" id="indikator-penilaian" data-toggle="tab" href="#indikatorpenilaian" aria-expanded="true">Indikator Penilaian</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="instrumen-penilaian" data-toggle="tab" href="#instrumenpenilaian" aria-expanded="false">Instrumen Penilaian</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="indikatorpenilaian" aria-expanded="true" aria-labelledby="indikator-penilaian">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryindikator">Tambah Indikator</button>
                                            <!-- Create Modal -->
                                            <div class="modal fade text-left" id="entryindikator" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Tambah Data Indikator Penilaian</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page-indikator" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <input type="hidden" name="office-indikator" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Number : </label>
                                                                        <input type="number" name="numb-indikator" placeholder="Input Urutan Nomor Penilaian" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Indikator : </label>
                                                                        <textarea type="text" name="name-indikator" placeholder="Input Indikator Penilaian" class="form-control" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdataindikator" class="btn btn-outline-primary">Save</button>
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
                                            <th>Sequence</th>
                                            <th>Indikator</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $result = "SELECT * FROM indicator_assessment WHERE office_ind_assest = '$idoffice'";
                                    $query = mysqli_query($conn, $result);
                                    while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data['num_ind_assest']; ?></td>
                                            <td><?= $data['name_ind_assest']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_indpenilaian" title="Update Indikator Penilaian <?= $data["name_ind_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_indpenilaian" id="<?= $data['id_ind_assest']; ?>"><i class="ft-edit"></i></button>
                                                <?php if ($id_group == $arrgroup[0] || $id_group == $arrgroup[3]) { ?>
                                                <button type="button" class="btn btn-icon btn-danger delete_indpenilaian" title="Delete Indikator Penilaian <?= $data["name_ind_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_indpenilaian" id="<?= $data['id_ind_assest']; ?>"><i class="ft-delete"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalIndPenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-updindpenilaian"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-updindpenilaian" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" id="id-updindpenilaian" name="id-updindpenilaian" class="form-control" readonly>
                                                            <input type="hidden" id="numbold-updindpenilaian" name="numbold-updindpenilaian" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Sequence : </label>
                                                                <input type="text" id="numb-updindpenilaian" name="numb-updindpenilaian" placeholder="Input Urutan Nomor Penilaian" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Indikator Penilaian : </label>
                                                                <input type="text" id="name-updindpenilaian" name="name-updindpenilaian" placeholder="Input Indikator Penilaian" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedataindikator" class="btn btn-outline-success">Edit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalIndPenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" class="form-control" id="page-delindpenilaian" name="page-delindpenilaian" value="<?= $encpid; ?>" readonly>
                                                    <input type="hidden" class="form-control" id="id-delindpenilaian" name="id-delindpenilaian" readonly>
                                                    <input type="hidden" class="form-control" id="name-delindpenilaian" name="name-delindpenilaian" readonly>
                                                    <label id="label-delindpenilaian"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedataindikator" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="instrumenpenilaian" aria-labelledby="instrumen-penilaian">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryinstrumen">Tambah Instrumen</button>
                                            <div class="modal fade text-left" id="entryinstrumen" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Tambah Data Instrumen Penilaian</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Indikator Penilaian : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="id-indikator" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php
                                                                            $query_ins = mysqli_query($conn, "SELECT * FROM indicator_assessment");
                                                                            while($data_ins = mysqli_fetch_assoc($query_ins)) {
                                                                            ?>
                                                                            <option value="<?= $data_ins["id_ind_assest"];?>"><?= $data_ins["num_ind_assest"].". ".$data_ins["name_ind_assest"];?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Instrumen Penilaian :</label>
                                                                        <textarea class="form-control" type="text" name="name-instrumen" placeholder="Input Instrumen Penilaian" required></textarea>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Poin : </label>
                                                                        <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="poininstrumen[]" required>
                                                                            <?php
                                                                            for ($i = 100; $i >= 5; $i -= 5) { ?>
                                                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatainstrumen" class="btn btn-outline-primary">Save</button>
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
                                            <th>Indikator Penilaian</th>
                                            <th>Instrumen Penilaian</th>
                                            <th>Poin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $result = "SELECT A.*, B.* FROM instrument_assessment AS A
                                    INNER JOIN indicator_assessment AS B ON A.id_head_ind_assest  = B.id_ind_assest
                                    WHERE B.office_ind_assest = '$idoffice'";
                                    $query = mysqli_query($conn, $result);
                                    while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data['num_ind_assest'].". ".$data['name_ind_assest']; ?></td>
                                            <td><?= $data['name_ins_assest']; ?></td>
                                            <td><?= $data['poin_ins_assest']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_inspenilaian" title="Update Instrumen Penilaian <?= $data["id_ins_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_inspenilaian" id="<?= $data['id_ins_assest']; ?>"><i class="ft-edit"></i></button>
                                                <?php if ($id_group == $arrgroup[0] || $id_group == $arrgroup[3]) { ?>
                                                <button type="button" class="btn btn-icon btn-danger delete_inspenilaian" title="Delete Instrumen Penilaian <?= $data["id_ins_assest"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_inspenilaian" id="<?= $data['id_ins_assest']; ?>"><i class="ft-delete"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalInsPenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-updateinspenilaian"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" id="id-updateinspenilaian" name="id-updateinspenilaian" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Indikator Penilaian : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" id="idn-updateinspenilaian" name="idn-updateinspenilaian" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_indikator = mysqli_query($conn, "SELECT * FROM indicator_assessment");
                                                                    while($data_indikator = mysqli_fetch_assoc($query_indikator)) {
                                                                    ?>
                                                                    <option value="<?= $data_indikator["id_ind_assest"]; ?>" ><?= $data_indikator["num_ind_assest"].". ".$data_indikator["name_ind_assest"];?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Instrumen Penilaian : </label>
                                                                <textarea class="form-control" type="text" id="name-updateinspenilaian" name="name-updateinspenilaian" placeholder="Input Instrumen Penilaian" required></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Poin : </label>
                                                                <input type="text" id="poin-updateinspenilaian" name="poin-updateinspenilaian" class="form-control" disabled>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Poin Update : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="poinupdateinspenilaian[]" required>
                                                                    <?php
                                                                    for ($i = 100; $i >= 5; $i -= 5) { ?>
                                                                        <option value="<?= $i; ?>"><?= $i; ?></option>
                                                                    <?php 
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedatainstrumen" class="btn btn-outline-success">Edit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalInsPenilaian" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" class="form-control" id="id-delinspenilaian" name="id-delinspenilaian" readonly>
                                                    <input type="hidden" class="form-control" id="name-delinspenilaian" name="name-delinspenilaian" readonly>
                                                    <label id="label-delinspenilaian"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatainstrumen" class="btn btn-outline-danger">Yes</button>
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
    $(document).on('click', '.update_indpenilaian', function(){  
        var ID_indikator = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEMASTERPENILAIAN:ID_indikator},  
            dataType:"json",  
            success:function(data){
                $('#id-updindpenilaian').val(data.id_ind_assest);
                $('#numbold-updindpenilaian').val(data.num_ind_assest);
                $('#numb-updindpenilaian').val(data.num_ind_assest);
                $('#name-updindpenilaian').val(data.name_ind_assest);
                $('#label-updindpenilaian').html("Edit Indikator Penilaian Nomor ID "+data.id_ind_assest);

                $('#updateModalIndPenilaian').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_indpenilaian', function(){  
        var ID_indikator = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",
            data:{UPDATEMASTERPENILAIAN:ID_indikator},  
            dataType:"json",  
            success:function(data){
                $('#id-delindpenilaian').val(data.id_ind_assest);
                $('#name-delindpenilaian').val(data.name_ind_assest);
                $('#label-delindpenilaian').html("Delete Indikator Penilaian "+data.name_ind_assest);

                $('#deleteModalIndPenilaian').modal('show');
            }  
        });
    });
});


$(document).ready(function(){
    $(document).on('click', '.update_inspenilaian', function(){  
        var ID_instrumen = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONINSTRUMENPENILAIAN:ID_instrumen},  
            dataType:"json",  
            success:function(data){
                $('#id-updateinspenilaian').val(data.code_ins_assest);
                $('#poin-updateinspenilaian').val(data.poin_ins_assest);

                $('#idn-updateinspenilaian').find('option[value="'+data.id_head_ind_assest+'"]').remove();
                $('#idn-updateinspenilaian').append($('<option></option>').html(data.num_ind_assest+". "+data.name_ind_assest).attr('value', data.id_head_ind_assest).prop('selected', true));

                $('#name-updateinspenilaian').val(data.name_ins_assest);
                $('#label-updateinspenilaian').html("Edit Instrumen Penilaian Nomor ID "+data.code_ins_assest);

                $('#updateModalInsPenilaian').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_inspenilaian', function(){  
        var ID_instrumen = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONINSTRUMENPENILAIAN:ID_instrumen},  
            dataType:"json",  
            success:function(data){
                $('#id-delinspenilaian').val(data.code_ins_assest);
                $('#name-delinspenilaian').val(data.name_ins_assest);
                $('#label-delinspenilaian').html("Delete Instrumen Penilaian "+data.id_ins_assest);

                $('#deleteModalInsPenilaian').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>