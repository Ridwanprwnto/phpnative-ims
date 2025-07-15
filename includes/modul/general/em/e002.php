<?php

$_SESSION['PRINTLHSONA'] = $_POST; 

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
    if(ResetLHSONA($_POST) > 0){
        $datapost = $_POST["reset-noso"];
        $alert = array("Success!", "Data SO ".$datapost." berhasil direset", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["adjustdataso"])){
    if(AdjustSONA($_POST) > 0){
        header("location: index.php?page=$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["uploaddataso"])){
    if(UploadSONA($_POST) > 0){
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
                                            <form action="reporting/report-data-so-nonaktiva.php" method="post" target="_blank">
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
                                                    <h4 class="modal-title white" id="myModalLabel">Upload Data Stock Opname</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="page-so" value="<?= $redirect; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="office-so" value="<?= $idoffice; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="dept-so" value="<?= $iddept; ?>" readonly>
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
                            <table class="table table-striped table-bordered zero-configuration text-center" id="tableProsesSONA">
                                <thead>
                                    <tr>
                                        <th scope="col">NO</th>
                                        <th scope="col">PLU BARANG</th>
                                        <th scope="col">DESC</th>
                                        <th scope="col">SALDO AWAL</th>
                                        <th scope="col">FISIK</th>
                                        <th scope="col">SELISIH</th>
                                        <th scope="col">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $nol = 0;
                                    $no = 1;
                                    $sql = "SELECT A.*, B.*, C.NamaBarang, D.NamaJenis FROM detail_stock_opname AS A
                                    INNER JOIN head_stock_opname AS B ON A.no_so_head = B.no_so 
                                    INNER JOIN mastercategory AS C ON LEFT(A.pluid_so, 6) = C.IDBarang
                                    INNER JOIN masterjenis AS D ON RIGHT(A.pluid_so, 4) = D.IDJenis
                                    WHERE B.office_so = '$idoffice' AND B.dept_so = '$iddept' AND B.no_so = '$dec_act' AND B.jenis_so = 0 AND A.status_so_detail = 'N'";
                                    $query = mysqli_query($conn, $sql);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                    $fisik = $data['fisik_so'] == NULL ? 0 : $data['fisik_so'];
                                ?>
                                    <tr id="<?= $data["no_so_detail"]; ?>" class="edit_tr">
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["pluid_so"];?></td>
                                        <td><?= $data["NamaBarang"].' '.$data["NamaJenis"];?></td>
                                        <td><?= $saldo = $data["saldo_so"];?></td>

                                        <td class="edit_td">
                                            <span id="fisikso_<?= $data["no_so_detail"]; ?>" class="text"><?= $fisik; ?></span>
                                            <input type="number" value="<?= $fisik; ?>" class="form-control editbox" id="fisikso_input_<?= $data["no_so_detail"]; ?>">
                                        </td>
                                        
                                        <td><?= $fisik-$saldo;?></td>

                                        <td class="edit_td">
                                            <span id="ketso_<?= $data["no_so_detail"]; ?>" class="text"><?= $data["keterangan_so"]; ?></span>
                                            <textarea type="text" value="<?= $data["keterangan_so"];?>" class="form-control editbox" id="ketso_input_<?= $data["no_so_detail"];?>" placeholder="Input Keterangan"><?= $data["keterangan_so"]; ?></textarea>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Modal Proses -->
                                <div class="modal fade text-left" id="prosesdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="myModalLabel1">Adjust Data SO Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="page-noso" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" name="pagesuccess-noso" value="<?= $redirect_scs; ?>" readonly>
                                                <input class="form-control" type="hidden" name="page" value="<?= $redirect; ?>" readonly>
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
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="reset-noso" value="<?= $dec_act; ?>" readonly>
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
                                $query_all = mysqli_query($conn, "SELECT COUNT(no_so_detail) AS data_total FROM detail_stock_opname WHERE no_so_head = '$dec_act'");
                                $result_all = mysqli_fetch_assoc($query_all);

                                $query_so = mysqli_query($conn, "SELECT COUNT(no_so_detail) AS data_so FROM detail_stock_opname WHERE no_so_head = '$dec_act' AND fisik_so IS NOT NULL");
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
                        <a href="reporting/report-lhso-nonaktiva.php?nomor=<?= encrypt($dec_act);?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" class="btn btn-info mr-1 mt-1 mb-2 pull-right">
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

<script>

$(document).ready(function() {
    $(".edit_tr").click(function() {
    var ID = $(this).attr('id');
    $("#ketso_"+ID).hide();
    $("#fisikso_"+ID).hide();
    $("#ketso_input_"+ID).show();
    $("#fisikso_input_"+ID).show();
    }).change(function() {
        var ID = $(this).attr('id');
        var ket_so = $("#ketso_input_"+ID).val();
        var fisik_so = $("#fisikso_input_"+ID).val();
        var dataString = 'IDSO='+ID+'&FISIKSO='+fisik_so+'&KETSO='+ket_so;
        if(fisik_so >= 0) {
            if(ket_so.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "action/datarequest.php",
                    data: dataString,
                    cache: false,
                    success: function(html) {
                        $("#ketso_"+ID).html(ket_so);
                        $("#fisikso_"+ID).html(fisik_so);
                        toastr.success('Data ID '+ ID +' berhasil di update!', 'Stock Opname Non Aktiva');
                    }
                });
            }
            else {
                alert('Keterangan wajib di input!');
            }
        }   
        else {
            alert('Fisik tidak boleh kurang dari nol');
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