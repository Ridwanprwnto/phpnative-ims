<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

$page_id = $_GET['page'];
$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$ext_id = $_GET['ext'];
$dec_ext = decrypt(rplplus($ext_id));
$encext = encrypt($dec_ext);

$action_id = isset($_GET['id']) ? $_GET['id'] : NULL;
$dec_act = decrypt(rplplus($action_id));
$encaid = encrypt($dec_act);

$redirect_scs = "index.php?page=".$encpid;
$redirect = "index.php?page=".$encpid."&ext=".$encext."&id=".$encaid;

if(isset($_POST["updatedataso"])){
    if(UpdateSOApar($_POST) > 0){
        $datapost = $_POST["upd-noso"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil diupdate", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["resetdataso"])){
    if(ResetSOApar($_POST) > 0){
        $datapost = $_POST["reset-noso"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil direset", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["postingdataso"])){
    if(FinishSOApar($_POST) > 0){
        header("location: index.php?page=$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekam Draft SO No : <?= $dec_act; ?></h4>
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
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration text-center" id="tableProsesSOApar">
                                <thead>
                                    <tr>
                                        <th scope="col">NO</th>
                                        <th scope="col">LOKASI PENEMPATAN</th>
                                        <th scope="col">KAPASITAS</th>
                                        <th scope="col">JENIS ISI</th>
                                        <th scope="col">MASA EXP</th>
                                        <th scope="col">SISA EXP</th>
                                        <th scope="col">INDIKATOR JARUM</th>
                                        <th scope="col">BRACKET</th>
                                        <th scope="col">LABEL PETUNJUK</th>
                                        <th scope="col">CHECKING LIST</th>
                                        <th scope="col">KETERANGAN</th>
                                        <th scope="col">AKSI</th>
                                </thead>
                                <tbody>
                                <?php
                                    $no = 1;
                                    $sql = "SELECT A.*, B.*, C.* FROM so_apar AS A
                                    INNER JOIN layout_apar AS B ON A.posisi_so_apar = B.id_layout
                                    INNER JOIN head_so_apar AS C ON A.id_head_so_apar = C.id_head_so_apar
                                    WHERE A.id_head_so_apar = '$dec_act' AND C.status_head_so_apar = 'N'";
                                    $query = mysqli_query($conn, $sql);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                    $expdate = strtotime($data["expired_so_apar"]);
                                    $datenow = time();
                                    $diff  = $expdate - $datenow;
                                ?>
                                    <tr id="<?= $data["id_so_apar"]; ?>" class="edit_tr">
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["posisi_so_apar"]." - ".$data["layout_name"];?></td>
                                        <td><?= isset($data['berat_so_apar']) ? $data['berat_so_apar'] : '-';?></td>
                                        <td><?= isset($data['jenis_so_apar']) ? $data['jenis_so_apar'] : '-';?></td>
                                        <td><?= isset($data['expired_so_apar']) ? $data['expired_so_apar'] : '-'; ?></td>
                                        <td><?= isset($data['expired_so_apar']) ? floor($diff / (60 * 60 * 24)) . ' hari' : '-';?></td>
                                        <td><?= isset($data['indikator_so_apar']) ? $data['indikator_so_apar'] : '-'; ?></td>
                                        <td><?= isset($data['bracket_so_apar']) ? $data['bracket_so_apar'] : '-'; ?></td>
                                        <td><?= isset($data['label_so_apar']) ? $data['label_so_apar'] : '-'; ?></td>
                                        <td><?= isset($data['checklist_so_apar']) ? $data['checklist_so_apar'] : '-'; ?></td>
                                        <td><?= isset($data['ket_so_apar']) ? $data['ket_so_apar'] : '-'; ?></td>
                                        <td>
                                            <button type="button" title="Update Data SO : <?= $data['posisi_so_apar']; ?>" class="btn btn-icon btn-primary update_soapar" name="update_soapar" id="<?= $data["id_so_apar"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Modal Update -->
                                <div class="modal fade text-left" id="updateModalSOApar">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="" method="post">
                                                <div class="modal-header bg-primary white">
                                                    <h4 class="modal-title white" id="upd-headsoapar"></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input type="hidden" name="upd-idso" id="upd-idsoapar" class="form-control" readonly>
                                                        <input type="hidden" name="upd-noso" id="upd-nosoapar" class="form-control" readonly>
                                                        <div class="col-md-12 mb-2">
                                                        <label>Fisik Apar : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-fisiksoapar" id="upd-fisiksoapar" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="Y">Ada</option>
                                                                <option value="N">Tidak Ada</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-2" id="slc-kapsoapar" style="display:none;">
                                                        <label>Kapasitas : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-kapsoapar" id="upd-kapsoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="3">3 Kg</option>
                                                                <option value="5">5 Kg</option>
                                                                <option value="6">6 Kg</option>
                                                                <option value="9">9 Kg</option>
                                                                <option value="10">10 Kg</option>
                                                                <option value="12">12 Kg</option>
                                                                <option value="15">15 Kg</option>
                                                                <option value="20">20 Kg</option>
                                                                <option value="25">25 Kg</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-2" id="slc-isisoapar" style="display:none;">
                                                        <label>Jenis Isi : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-isisoapar" id="upd-isisoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="POWDER">Powder</option>
                                                                <option value="C02">C02</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2" id="slc-indikatorsoapar" style="display:none;">
                                                        <label>Indikator Tekanan : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-indikatorsoapar" id="upd-indikatorsoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="Y">Ada</option>
                                                                <option value="N">Tidak Ada</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2" id="slc-expsoapar" style="display:none;">
                                                            <label>Tanggal Expired : </label>
                                                            <input type="date" name="upd-expsoapar" id="upd-expsoapar" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 mb-2" id="slc-bracketsoapar" style="display:none;">
                                                        <label>Bracket : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-bracketsoapar" id="upd-bracketsoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="Y">Ada</option>
                                                                <option value="N">Tidak Ada</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-2" id="slc-labelsoapar" style="display:none;">
                                                        <label>Label Petunjuk : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-labelsoapar" id="upd-labelsoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="Y">Ada</option>
                                                                <option value="N">Tidak Ada</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2" id="slc-checksoapar" style="display:none;">
                                                        <label>Checking List : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="upd-checksoapar" id="upd-checksoapar">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="Y">Ada</option>
                                                                <option value="N">Tidak Ada</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2" id="slc-ketsoapar" style="display:none;">
                                                            <label>Keterangan :</label>
                                                            <textarea class="form-control" type="text" id="upd-ketsoapar" name="upd-ketsoapar" placeholder="Input Keterangan"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="updatedataso" class="btn btn-outline-primary">Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End -->
                                <!-- Modal Proses -->
                                <div class="modal fade text-left" id="prosesdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="myModalLabel1">Posting Data SO</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="page-noso" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" name="pagesuccess-noso" value="<?= $redirect_scs; ?>" readonly>
                                                <input class="form-control" type="hidden" name="finish-noso" value="<?= $dec_act;?>" readonly>
                                                <label>Data Stock Opname Nomor <?= $dec_act; ?></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" name="postingdataso" class="btn btn-outline-success">Yes</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Reset -->
                                <div class="modal fade text-left" id="resetdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning white">
                                                <h4 class="modal-title white" id="myModalLabel1">No SO : <?= $dec_act;?></h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="reset-noso" value="<?= $dec_act;?>" readonly>
                                                <label>Are you sure to reset this data stock opname?</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary"  data-dismiss="modal">Close</button>
                                                <button type="submit" name="resetdataso" class="btn btn-outline-warning">Yes</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <div class="progress">
                                <?php
                                    $query_all = mysqli_query($conn, "SELECT COUNT(id_so_apar) AS data_total FROM so_apar WHERE id_head_so_apar = '$dec_act'");
                                    $result_all = mysqli_fetch_assoc($query_all);

                                    $query_so = mysqli_query($conn, "SELECT COUNT(id_so_apar) AS data_so FROM so_apar WHERE id_head_so_apar = '$dec_act' AND bracket_so_apar IS NOT NULL AND label_so_apar IS NOT NULL AND checklist_so_apar IS NOT NULL");
                                    $result_so = mysqli_fetch_assoc($query_so);

                                    $data_all = $result_all["data_total"];
                                    $data_so = $result_so["data_so"];
                                    $persentasi = number_format($data_so / $data_all * 100);
                                ?>
                                <div class="progress-bar" role="progressbar" style="width:<?= $persentasi; ?>%"><?= $persentasi; ?> %</div>
                            </div>
                        </div>
                    </div>
                    <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary ml-2 mr-1 mb-2">
                        <i class="ft-chevrons-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success mr-2 mb-2 pull-right" data-toggle="modal" data-target="#prosesdata"><i class="ft-repeat"></i> Posting SO</button>
                    <button type="submit" class="btn btn-warning mr-1 mb-2 pull-right" data-toggle="modal" data-target="#resetdata"><i class="ft-rotate-ccw"></i> Reset SO</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function(){
    $(document).on('click', '.update_soapar', function(){  
        var id_so = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATESOAPAR:id_so},  
            dataType:"json",  
            success:function(data){
                $('#upd-idsoapar').val(data.id_so_apar);
                $('#upd-nosoapar').val(data.posisi_so_apar);
                $('#upd-expsoapar').val(data.expired_so_apar);

                if (data.berat_so_apar != null) {
                    $('#upd-kapsoapar').find('option[value="'+data.berat_so_apar+'"]').remove();
                    $('#upd-kapsoapar').append($('<option></option>').html(data.berat_so_apar+" Kg").attr('value', data.berat_so_apar).prop('selected', true));
                }
                
                if (data.jenis_so_apar != null) {
                    $('#upd-isisoapar').find('option[value="'+data.jenis_so_apar+'"]').remove();
                    $('#upd-isisoapar').append($('<option></option>').html(data.jenis_so_apar).attr('value', data.jenis_so_apar).prop('selected', true));
                }
                
                if (data.indikator_so_apar != null) {
                    if (data.indikator_so_apar == "Y") {
                        var name_indikator = "Ada";
                    }
                    else {
                        var name_indikator = "Tidak Ada";
                    }
                    $('#upd-indikatorsoapar').find('option[value="'+data.indikator_so_apar+'"]').remove();
                    $('#upd-indikatorsoapar').append($('<option></option>').html(name_indikator).attr('value', data.indikator_so_apar).prop('selected', true));
                }
                
                if (data.bracket_so_apar != null) {
                    if (data.bracket_so_apar == "Y") {
                        var name_bracket = "Ada";
                    }
                    else {
                        var name_bracket = "Tidak Ada";
                    }
                    $('#upd-bracketsoapar').find('option[value="'+data.bracket_so_apar+'"]').remove();
                    $('#upd-bracketsoapar').append($('<option></option>').html(name_bracket).attr('value', data.bracket_so_apar).prop('selected', true));
                }

                if (data.label_so_apar != null) {
                    if (data.label_so_apar == "Y") {
                        var name_label = "Ada";
                    }
                    else {
                        var name_label = "Tidak Ada";
                    }
                    $('#upd-labelsoapar').find('option[value="'+data.label_so_apar+'"]').remove();
                    $('#upd-labelsoapar').append($('<option></option>').html(name_label).attr('value', data.label_so_apar).prop('selected', true));
                }

                if (data.checklist_so_apar != null) {
                    if (data.checklist_so_apar == "Y") {
                        var name_check = "Ada";
                    }
                    else {
                        var name_check = "Tidak Ada";
                    }
                    $('#upd-checksoapar').find('option[value="'+data.checklist_so_apar+'"]').remove();
                    $('#upd-checksoapar').append($('<option></option>').html(name_check).attr('value', data.checklist_so_apar).prop('selected', true));
                }

                $('#upd-headsoapar').html("Update Data SO Lokasi : "+data.posisi_so_apar+" - "+data.layout_name);
                $('#updateModalSOApar').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $("#upd-fisiksoapar").change(function() { 
        if ($(this).val() == "Y") {
            $("#slc-kapsoapar").show();
            $("#slc-isisoapar").show();
            $("#slc-expsoapar").show();
            $("#slc-indikatorsoapar").show();
            $("#slc-bracketsoapar").show();
            $("#slc-labelsoapar").show();
            $("#slc-checksoapar").show();
            $("#slc-ketsoapar").show();
        }
        else if ($(this).val() == "N") {
            $("#slc-kapsoapar").hide();
            $("#slc-isisoapar").hide();
            $("#slc-expsoapar").hide();
            $("#slc-indikatorsoapar").hide();
            $("#slc-bracketsoapar").show();
            $("#slc-labelsoapar").show();
            $("#slc-checksoapar").show();
            $("#slc-ketsoapar").show();

        }
        else {
            $("#slc-kapsoapar").hide();
            $("#slc-isisoapar").hide();
            $("#slc-expsoapar").hide();
            $("#slc-indikatorsoapar").hide();
            $("#slc-ketsoapar").hide();
            $("#slc-bracketsoapar").hide();
            $("#slc-labelsoapar").hide();
            $("#slc-checksoapar").hide();
        }
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>