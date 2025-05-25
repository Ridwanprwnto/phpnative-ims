<?php

$_SESSION['PRINTLHSO'] = $_POST;
$_SESSION['PRINTBASO'] = $_POST;

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

$page_id = $_GET['page'];
$ext_id = $_GET['ext'];
$action_id = isset($_GET['id']) ? $_GET['id'] : NULL;

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$dec_ext = decrypt(rplplus($ext_id));
$encext = encrypt($dec_ext);

$dec_act = decrypt(rplplus($action_id));
$encaid = encrypt($dec_act);

$redirect_scs = "index.php?page=".$encpid;
$redirect = "index.php?page=".$encpid."&ext=".$encext."&id=".$encaid;

if(isset($_POST["resetdataso"])){
    if(ResetLHSO($_POST) > 0){
        $datapost = $_POST["reset-noso"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil direset", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["adjustdataso"])){
    if(AdjustLHSO($_POST) > 0){
        header("location: index.php?page=$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["uploaddataso"])){
    if(UploadSO($_POST) > 0){
        $datapost = $_POST["noref-so"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil diupload", "success", "$redirect");
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
                    <h4 class="card-title">Rekam Draft REF SO : <?= $dec_act; ?></h4>
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
                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary square btn-min-width mr-1 mb-1" data-toggle="modal" data-target="#downloadso">Download File SO</button>
                                <button type="button" class="btn btn-primary square btn-min-width mr-1 mb-1" data-toggle="modal" data-target="#uploadso">Upload File SO</button>
                                <button type="button" class="btn btn-info mr-1 mb-1" onclick="document.location.href='<?= $redirect;?>'"><i class="ft-rotate-ccw"></i> Reload Page</button>
                                <!-- Modal Download-->    
                                <div class="modal fade text-left" id="downloadso" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="reporting/report-data-so.php" method="post" target="_blank">
                                                <div class="modal-header bg-primary white">
                                                    <h4 class="modal-title white"
                                                        id="myModalLabel">Download Data Stock Opname</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="office-so" value="<?= $idoffice?>">
                                                        <input class="form-control" type="hidden" name="dept-so" value="<?= $iddept?>">
                                                        <div class="col-md-12 mb-2">
                                                            <label>Noref SO : </label>
                                                            <select name="noref-so" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_noref = mysqli_query($conn, "SELECT no_so FROM head_stock_opname WHERE no_so = '$dec_act'");
                                                                    while($data_noref = mysqli_fetch_assoc($query_noref)) {
                                                                ?>
                                                                    <option value="<?= $data_noref['no_so'];?>" ><?= $data_noref['no_so'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="downloaddataso" onclick="return downloadFileSO();" class="btn btn-outline-primary">Download</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Upload-->    
                                <div class="modal fade text-left" id="uploadso" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="" method="post" enctype="multipart/form-data" role="form">
                                                <div class="modal-header bg-primary white">
                                                    <h4 class="modal-title white"
                                                        id="myModalLabel">Upload Data Stock Opname</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="page-so" value="<?= $redirect; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="offdep-so" value="<?= $idoffice.$iddept; ?>" readonly>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Noref SO : </label>
                                                            <select name="noref-so" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_noref = mysqli_query($conn, "SELECT no_so FROM head_stock_opname WHERE no_so = '$dec_act'");
                                                                    while($data_noref = mysqli_fetch_assoc($query_noref)) {
                                                                ?>
                                                                    <option value="<?= $data_noref['no_so'];?>" ><?= $data_noref['no_so'];?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>File CSV : </label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="filecsv-so" required>
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="uploaddataso" class="btn btn-outline-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration text-center" id="tableProsesSO">
                                <thead>
                                    <tr>
                                        <th scope="col">NO</th>
                                        <th scope="col">KODE BARANG</th>
                                        <th scope="col">NAMA BARANG</th>
                                        <th scope="col">NO AKTIVA</th>
                                        <th scope="col">SERIAL NUMBER</th>
                                        <th scope="col">LOKASI</th>
                                        <th scope="col">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $nol = 0;
                                    $no = 1;
                                    $sql = "SELECT A.*, B.*, asset_stock_opname.*, D.*, E.*, F.ba_merk, F.ba_tipe FROM detail_stock_opname AS A
                                    INNER JOIN head_stock_opname AS B ON A.no_so_head = B.no_so 
                                    INNER JOIN asset_stock_opname ON A.pluid_so = asset_stock_opname.pluid_so_asset
                                    INNER JOIN mastercategory AS D ON LEFT(A.pluid_so, 6) = D.IDBarang
                                    INNER JOIN masterjenis AS E ON RIGHT(A.pluid_so, 4) = E.IDJenis
                                    INNER JOIN barang_assets AS F ON asset_stock_opname.sn_so_asset = F.sn_barang
                                    WHERE B.office_so = '$idoffice' AND B.dept_so = '$iddept' AND B.no_so = '$dec_act' AND B.jenis_so = 1 AND LEFT(asset_stock_opname.offdep_so_asset, 4) = '$idoffice' AND RIGHT(asset_stock_opname.offdep_so_asset, 4) = '$iddept' GROUP BY asset_stock_opname.id_so_asset";
                                    $query = mysqli_query($conn, $sql);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr id="<?= $data["id_so_asset"]; ?>" class="edit_tr">
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["pluid_so_asset"];?></td>
                                        <td><?= $data["NamaBarang"].' '.$data["NamaJenis"]." ".$data["ba_merk"]." ".$data["ba_tipe"];?></td>
                                        <td><?= $data["noat_so_asset"];?></td>
                                        <td><?= $data["sn_so_asset"];?></td>
                                        <td class="edit_td">
                                            <span id="lokasiso_<?= $data["id_so_asset"]; ?>" class="text"><?= $data["lokasi_so_asset"]; ?></span>
                                            <textarea type="text" value="<?= $data["lokasi_so_asset"];?>" class="form-control editbox" id="lokasiso_input_<?= $data["id_so_asset"];?>" placeholder="Input Lokasi"><?= $data["lokasi_so_asset"]; ?></textarea>
                                        </td>
                                        <td class="edit_td">
                                            <?php
                                            if ($data["kondisi_so_asset"] == "$arrcond[0]") {
                                                $color = "success";
                                                $kondisi = "01 - BAIK";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[1]") {
                                                $color = "primary";
                                                $kondisi = "02 - CADANGAN";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[2]") {
                                                $color = "warning";
                                                $kondisi = "03 - RUSAK";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[3]") {
                                                $color = "info";
                                                $kondisi = "04 - PERBAIKAN";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[4]") {
                                                $color = "secondary";
                                                $kondisi = "05 - P3AT";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[6]") {
                                                $color = "danger";
                                                $kondisi = "07 - HILANG";
                                            }
                                            elseif ($data["kondisi_so_asset"] == "$arrcond[7]") {
                                                $color = "dark";
                                                $kondisi = "08 - MUTASI";
                                            }
                                            else {
                                                $color = "";
                                                $kondisi = "";
                                            }
                                            ?>
                                            <div class="text" id="statusso_<?= $data["id_so_asset"]; ?>"><?= $kondisi;?></div>
                                            <select type="text" class="form-control editbox" id="statusso_input_<?= $data["id_so_asset"];?>">
                                            <option value="" selected disabled>Pilih Status</option>
                                            <?php
                                                $query_cond = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi != '$arrcond[5]'");
                                                while($data_cond = mysqli_fetch_assoc($query_cond)) { ?>
                                                <option value="<?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name']; ?>" <?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name'] == $kondisi ? 'selected' : ''; ?> ><?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name'];?></option>
                                            <?php 
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Modal Proses -->
                                <div class="modal fade text-left" id="prosesdata" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="myModalLabel1">Adjust Data SO Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="page-noso" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" name="pagesuccess-noso" value="<?= $redirect_scs; ?>" readonly>
                                                <input class="form-control" type="hidden" name="user-noso" value="<?= $nik." - ".strtoupper($username);?>" readonly>
                                                <input class="form-control" type="hidden" name="modifref-noso" value="<?= $arrmodifref[9]; ?>" readonly>
                                                <input class="form-control" type="hidden" name="adjust-noso" value="<?= $dec_act;?>" readonly>
                                                <label>Data Stock Opname Nomor <?= $dec_act; ?></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="adjustdataso" class="btn btn-outline-success">Yes</button>
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
                                                <h4 class="modal-title white" id="myModalLabel1">Reset SO : <?= $dec_act;?></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="reset-noso" value="<?= $dec_act;?>" readonly>
                                                <label>Are you sure to reset this data stock opname?</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
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
                                $query_all = mysqli_query($conn, "SELECT COUNT(id_so_asset) AS data_total FROM asset_stock_opname WHERE noref_so_asset = '$dec_act'");
                                $result_all = mysqli_fetch_assoc($query_all);

                                $query_so = mysqli_query($conn, "SELECT COUNT(id_so_asset) AS data_so FROM asset_stock_opname WHERE noref_so_asset = '$dec_act' AND kondisi_so_asset IS NOT NULL AND lokasi_so_asset IS NOT NULL");
                                $result_so = mysqli_fetch_assoc($query_so);

                                $data_all = $result_all["data_total"];
                                $data_so = $result_so["data_so"];

                                if ($data_all > 0 && $data_so > 0) {
                                    $persentasi = number_format($data_so / $data_all * 100);
                                }

                                ?>
                                <div class="progress-bar" role="progressbar" style="width:<?= isset($persentasi) ? $persentasi : 0; ?>%"><?= isset($persentasi) ? $persentasi : 0; ?> %</div>
                            </div>
                        </div>
                        <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary ml-2 mr-1 mt-1 mb-2">
                            <i class="ft-chevrons-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success mr-2 mt-1 mb-2 pull-right" data-toggle="modal" data-target="#prosesdata"><i class="ft-repeat"></i> Adjust SO</button>
                        <a href="reporting/report-baso.php?baso=<?= encrypt($dec_act);?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" class="btn btn-secondary mr-1 mt-1 mb-2 pull-right">
                            <i class="ft-printer"></i> Print BASO
                        </a>
                        <a href="reporting/report-lhso.php?lhso=<?= encrypt($dec_act);?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" class="btn btn-info mr-1 mt-1 mb-2 pull-right">
                            <i class="ft-printer"></i> Print LHSO
                        </a>
                        <button type="submit" class="btn btn-warning mr-1 mt-1 mb-2 pull-right" data-toggle="modal" data-target="#resetdata"><i class="ft-rotate-ccw"></i> Reset SO</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script type="text/javascript">

$(document).ready(function() {
    $(".edit_tr").click(function() {
    var ID = $(this).attr('id');
    $("#lokasiso_"+ID).hide();
    $("#statusso_"+ID).hide();
    $("#lokasiso_input_"+ID).show();
    $("#statusso_input_"+ID).show();
    }).change(function() {
        var ID = $(this).attr('id');
        var lokasi_so =$("#lokasiso_input_"+ID).val();
        var status_so =$("#statusso_input_"+ID).val();
        var dataString = 'IDSO='+ ID +'&LOKASISO='+lokasi_so+'&STATUSSO='+status_so;
        if(lokasi_so.length > 0 && status_so.length > 0) {
            $.ajax({
                type: "POST",
                url: "action/datarequest.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#lokasiso_"+ID).html(lokasi_so);
                    $("#statusso_"+ID).html(status_so);
                    toastr.success('Data ID '+ ID +' berhasil di update!', 'Stock Opname Aktiva');
                }
            });
        }   
        else {
            alert('Lokasi dan Status Barang Belum di Input!');
        }
    });

    // Edit input box click action
    $(".editbox").mouseup(function() {
        return false
    });

    // Outside click action
    $(document).mouseup(function() {
        $(".editbox").hide();
        $(".text").show();
    });
});

function downloadFileSO() {
    $('#downloadso').modal('hide');
}
</script>

<?php
    include ("includes/templates/alert.php");
?>