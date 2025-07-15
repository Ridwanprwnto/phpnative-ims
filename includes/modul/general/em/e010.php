<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];
$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$ext_id = $_GET['ext'];
$dec_ext = decrypt(rplplus($ext_id));
$enceid = encrypt($dec_ext);

$action_id = isset($_GET['id']) ? $_GET['id'] : NULL;
$dec_act = decrypt(rplplus($action_id));
$encaid = encrypt($dec_act);

$redirect = "index.php?page=$encpid&ext=$enceid&id=$encaid";

if(isset($_POST["prosesmusnah"])) {
    if(ProsesMusnahBarang($_POST) > 0 ){
        $datapost = isset($_POST["id-sn"]) ? $_POST["id-sn"] : NULL;
        $alert = array("Success!", "Data Barang SN ".$datapost." Berhasil Proses Pemusnahan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["prosescheckmusnah"])) {
    if(ProsesMusnahBarangCheck($_POST) > 0 ){
        $alert = array("Success!", "Data Barang Inventaris Berhasil Proses Pemusnahan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<!-- Basic form layout section start -->
<section id="basic-select2">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Proses Pemusnahan Barang Inventaris Nomor <?= $dec_act; ?></h4>
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
                            <form action="" method="post">
                            <table class="table table-striped table-bordered text-center" id="table_proses_musnah">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO P3AT</th>
                                        <th>NAMA BARANG</th>
                                        <th>SN</th>
                                        <th>NO AKTIVA</th>
                                        <th>ACTION</th>
                                        <th class="icheck1">
                                            <input type="checkbox" id="checkall-musnah" class="checkall-musnah">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                $nol = 0;
                                $no = 1;
                                $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.* FROM detail_p3at AS A
                                INNER JOIN p3at AS B ON A.id_head_p3at = B.id_p3at
                                INNER JOIN office AS C ON B.office_p3at = C.id_office
                                INNER JOIN department AS D ON B.dept_p3at = D.id_department
                                INNER JOIN mastercategory AS E ON LEFT(A.pluid_p3at, 6) = E.IDBarang
                                INNER JOIN masterjenis AS F ON RIGHT(A.pluid_p3at, 4) = F.IDJenis
                                WHERE B.office_p3at = '$idoffice' AND B.dept_p3at = '$iddept' AND B.id_p3at = '$dec_act' AND A.nomor_musnah IS NULL AND A.tgl_approve IS NULL";
                                $query = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["id_head_p3at"];?></td>
                                        <td><?= $data["pluid_p3at"].' - '.$data["NamaBarang"].' '.$data["NamaJenis"].' '.$data["merk_p3at"].' '.$data["tipe_p3at"];?>
                                        </td>
                                        <td><?= $data["sn_p3at"];?></td>
                                        <td><?= $data["at_p3at"];?></td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-success update_prosesmusnah" title="Update Proses Pemusnahan SN : <?= $data['sn_p3at']; ?>" data-toggle="tooltip" data-placement="bottom" name="update_prosesmusnah" id="<?= $data["id_detail_p3at"];?>"><i class="ft-edit"></i></button>
                                        </td>
                                        <td class="icheck1">
                                            <input type="checkbox" name="checkidmusnah[]" id="checkidmusnah" class="checkidmusnah" value="<?= $data['id_detail_p3at']; ?>">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <!-- Modal Update -->
                                <div class="modal fade text-left" id="modalUpdateProsesMusnah" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success white">
                                                    <h4 class="modal-title white" id="label-prosmusnah"></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="page-prosmusnah" value="<?= $redirect; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="user-prosmusnah" value="<?= $usernik; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="id-kondisi" value="<?= $arrcond[5];?>" readonly>
                                                        <input class="form-control" type="hidden" name="status-p3at" value="<?= $arrsp3at[1];?>" readonly>
                                                        <input class="form-control" type="hidden" name="ref-p3at" value="<?= $arrmodifref[11];?>" readonly>
                                                        <input class="form-control" type="hidden" id="offdep-prosmusnah" name="offdep-p3at" readonly>
                                                        <input class="form-control" type="hidden" id="id-prosmusnah" name="id-p3at" readonly>
                                                        <input class="form-control" type="hidden" id="docno-prosmusnah" name="nomor-p3at" readonly>
                                                        <input class="form-control" type="hidden" id="brg-prosmusnah" name="id-plu" readonly>
                                                        <input class="form-control" type="hidden" id="sn-prosmusnah" name="id-sn" readonly>
                                                        <input class="form-control" type="hidden" id="at-prosmusnah" name="id-at" readonly>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Nomor Bukti Pemusnahan : </label>
                                                            <input class="form-control" type="text" name="id-pemusnahan" placeholder="Entry nomor pemusnahan aktiva">
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Tgl Dimusnahkan : </label>
                                                            <input class="form-control" type="date" name="tgl-pemusnahan">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="prosesmusnah" class="btn btn-outline-success">Proses</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Update Check-->
                                <div class="modal fade text-left" id="modalUpdateCheckProsesMusnah" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white">Proses Pemusnahan Barang Inventaris</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" name="kon-chkprosmusnah" value="<?= $arrcond[5];?>" readonly>
                                                    <input class="form-control" type="hidden" name="sts-chkprosmusnah" value="<?= $arrsp3at[1];?>" readonly>
                                                    <input class="form-control" type="hidden" name="user-chkprosmusnah" value="<?= $usernik; ?>" readonly>
                                                    <input class="form-control" type="hidden" name="ref-chkprosmusnah" value="<?= $arrmodifref[11];?>" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Nomor Bukti Pemusnahan : </label>
                                                        <input class="form-control" type="text" name="bukti-chkprosmusnah" placeholder="Entry nomor pemusnahan aktiva">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Tgl Dimusnahkan : </label>
                                                        <input class="form-control" type="date" name="tgl-chkprosmusnah">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosescheckmusnah" class="btn btn-outline-success" onclick="return validateForm();">Proses</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </table>
                            </form>
                            <button type="button" class="btn btn-success btn-min-width mt-1 mr-1 mb-1 pull-right" data-toggle="modal" data-target="#modalUpdateCheckProsesMusnah">Proses By Checkbox</button>
                            <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary btn-min-width mt-1 mr-1 mb-1 pull-right">
                                <i class="ft-chevrons-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>

$(document).ready(function(){
    $('#table_proses_musnah').DataTable({
        info: true,
        ordering: true,
        paging: false,
        autoWidth: true
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_prosesmusnah', function(){  
        var id_p3at = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPROSESMUSNAH:id_p3at},
            dataType:"json",  
            success:function(data){
                $('#id-prosmusnah').val(data.id_detail_p3at);
                $('#docno-prosmusnah').val(data.id_head_p3at);
                $('#brg-prosmusnah').val(data.pluid_p3at);
                $('#sn-prosmusnah').val(data.sn_p3at);
                $('#at-prosmusnah').val(data.at_p3at);
                $('#offdep-prosmusnah').val(data.offdep_p3at);
                
                $('#label-prosmusnah').html("Proses Pemusnahan Barang SN : "+data.sn_p3at);
                $('#modalUpdateProsesMusnah').modal('show');
            }  
        });
    });
});

$(document).ready(function() {
    // check / uncheck all
    var checkAll = $('input#checkall-musnah');
    var checkboxes = $('input[name="checkidmusnah[]"]');

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } 
        else {
            checkAll.prop('checked', "");
        }
        checkAll.iCheck('update');
    });
});

function validateForm() {
    var count_checked = $('input[name="checkidmusnah[]"]:checked').length;
    if (count_checked == 0) {
        alert("Please check at least one checkbox");
        return false;
    } else {
        return true;
    }
}
</script>

<?php
    include ("includes/templates/alert.php");
?>