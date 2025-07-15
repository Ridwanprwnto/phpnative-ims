<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdataout"])){
    if(InsertBarangOut($_POST) > 0 ){
        $datapost = isset($_POST["plu-btb"]) ? $_POST["plu-btb"] : NULL;
        $alert = array("Success!", "Data Stock Barang ".$datapost." Berhasil dikeluarkan", "success", "$redirect");
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
                    <h4 class="card-title">Entry Data Mutasi Barang Non Aktiva (Pemakaian / Pengeluaran)</h4>
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
                    <form class="form" id="form_entrymutasi" action="" method="post">
                      <div class="form-body">
                        <h4 class="form-section"><i class="la la-dropbox"></i> Data Barang</h4>
                        <div class="row">
                          <div class="form-group col-md-6 mb-2">
                            <input type="hidden" name="page-btb" value="<?= $redirect; ?>" class="form-control" readonly>
                            <input type="hidden" name="office-btb" value="<?= $idoffice; ?>" class="form-control" readonly>
                            <input type="hidden" name="dept-btb" value="<?= $iddept; ?>" class="form-control" readonly>
                            <label>PLU - Nama Barang</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="plu-btb" required>
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_plu = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM masterstock AS A
                                    INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang 
                                    INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis 
                                    WHERE ms_id_office = '$idoffice' AND ms_id_department = '$iddept' ORDER BY B.NamaBarang ASC");
                                    while($data_plu = mysqli_fetch_assoc($query_plu)) { ?>
                                <option value="<?= $data_plu['IDBarang'].$data_plu['IDJenis'];?>"><?= $data_plu['IDBarang'].$data_plu['IDJenis']." - ".$data_plu['NamaBarang']." ".$data_plu['NamaJenis'];?>
                                </option>
                                <?php 
                                    }
                                ?>
                            </select>
                          </div>
                          <div class="form-group col-md-6 mb-2">
                            <label>Qty Barang</label>
                            <input type="number" name="qty-btb" class="form-control" placeholder="Qty" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>Tgl Keluar / Pemakaian</label>
                            <input type="date" name="tgl-btb" class="form-control" max="<?= date("Y-m-d"); ?>" value="<?= date("Y-m-d") ?>" required>
                          </div>
                        </div>
                        <h4 class="form-section"><i class="la la-users"></i> Data PIC</h4>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>PIC</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="pic-btb" required>
                            <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_users = mysqli_query($conn, "SELECT nik, username FROM users WHERE nik = '$usernik'");
                                    while($data_users = mysqli_fetch_assoc($query_users)) { ?>
                                        <option value="<?= $data_users['nik'];?>"><?= $data_users['nik'].' - '.strtoupper($data_users['username']);?>
                                        </option>
                                        <?php 
                                    }
                                ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>Penerima Barang</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="user-btb" required>
                            <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_users = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_department = '$iddept' AND id_group NOT LIKE '$arrgroup[0]' ORDER BY username ASC");
                                    while($data_users = mysqli_fetch_assoc($query_users)) { ?>
                                        <option value="<?= $data_users['nik'];?>"><?= $data_users['nik'].' - '.strtoupper($data_users['username']);?>
                                        </option>
                                        <?php 
                                    }
                                ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>Keterangan</label>
                            <textarea class="form-control" rows="5" type="text" name="ket-btb" placeholder="Input Keterangan Pengeluaran" required></textarea>
                          </div>
                        </div>
                      </div>
                      <!-- Modal Delete -->
                      <div class="modal fade text-left" id="prosesbarngmutasi" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <form action="" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-primary white">
                                    <h4 class="modal-title white">Proses Confirmation</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label>Are you sure to proccess this data?</label>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="insertdataout" class="btn btn-outline-primary">Yes</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <!-- End Modal -->
                      <div class="form-actions right">
                        <button type="button"  class="btn btn-warning mr-1" onclick="ResetForm()">
                          <i class="la la-retweet"></i> Reset
                        </button>
                        <button type="submit" data-toggle="modal" data-target="#prosesbarngmutasi" class="btn btn-primary">
                          <i class="la la-send-o"></i> Proses
                        </button>
                      </div>
                    </form>
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
    var input = document.getElementById("reqDate");
    var today = new Date();
    var day = today.getDate();

    // Set month to string to add leading 0
    var mon = new String(today.getMonth()+1); //January is 0!
    var yr = today.getFullYear();

    if(mon.length < 2) { mon = "0" + mon; }
    if(day.length < 2) { dayn = "0" + day; }

    var date = new String( yr + '-' + mon + '-' + day );

    input.disabled = false; 
    input.setAttribute('max', date);
});

function ResetForm() {
    var element = document.getElementById("form_entrymutasi");
    element.reset()
    $("#form_entrymutasi").find('select').select2().val('').trigger('change');
}
</script>

<?php
    include ("includes/templates/alert.php");
?>