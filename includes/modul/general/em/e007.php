<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];
$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$ext_id = $_GET['ext'];
$dec_ext = decrypt(rplplus($ext_id));
$enceid = encrypt($dec_ext);

$action_id = isset($_GET['id']) ? $_GET['id'] : NULL;
$dec_act = decrypt(rplplus($action_id));
$encaid = encrypt($dec_act);

$redirect_scs = "index.php?page=".$encpid;
$redirect = "index.php?page=$encpid&ext=$enceid&id=$encaid";

if(isset($_POST["updatedata"])){
    if(UpdateBarangStockSecond($_POST) > 0 ){
        $datapost = $_POST["pluiddata"];
        $alert = array("Success!", "Item Barang ".$datapost." berhasil update penerimaan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["prosesreceive"])){
    if(ProsesBarangStock($_POST) > 0){
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
                    <h4 class="card-title">Entry Penerimaan Pembelian NO : <?= $dec_act; ?></h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">

                    <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary square btn-min-width mr-1"> <i class="ft-chevrons-left"></i> Back</a>
                    <button type="button" class="btn btn-info square btn-min-width mr-1" data-toggle="modal" data-target="#prosesinputbarang">Proses Stock Barang</button>

                    <!-- Modal Proses PP -->
                    <div class="modal fade text-left" id="prosesinputbarang">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <form action="" method="post">
                                <div class="modal-header bg-info white">
                                    <h4 class="modal-title white">Proses Update Stock Barang PP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <input class="form-control" type="hidden" name="pagesuccess" value="<?= $redirect_scs; ?>" readonly>
                                            <input class="form-control" type="hidden" name="userproses" value="<?= $usernik;?>">
                                            <input class="form-control" type="hidden" name="ppbid" value="<?= $dec_act;?>">
                                            <input class="form-control" type="hidden" name="spid-10" value="<?= $arrsp[9];?>">
                                            <input class="form-control" type="hidden" name="spid-11" value="<?= $arrsp[10];?>">
                                            <input class="form-control" type="hidden" name="sp3at" value="<?= $arrsp3at[3];?>">
                                            <div class="col-md-12 mb-2">
                                                <label>BTBNO : </label>
                                                <select class="form-control" type="text" name="ppid" required>
                                                <option value="" selected disabled>Please Select</option>
                                                <?php 
                                                    $query_ppid = mysqli_query($conn, "SELECT A.ppid, B.id_penerimaan, B.pp_id_pembelian FROM pembelian AS A
                                                    INNER JOIN penerimaan_pembelian AS B ON A.id_pembelian = B.pp_id_pembelian
                                                    INNER JOIN detail_penerimaan_pembelian AS C ON B.id_penerimaan = C.id_penerimaan_pp
                                                    WHERE A.ppid = '$dec_act' GROUP BY A.ppid");
                                                    while($data_ppid = mysqli_fetch_assoc($query_ppid)) { ?>
                                                    <option value="<?= $data_ppid['id_penerimaan'];?>" ><?= $data_ppid['id_penerimaan'];?></option>
                                                <?php 
                                                    } 
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Tgl Proses : </label>
                                                <input class="form-control" type="date" name="tgl-ppid" max="<?=date('Y-m-d')?>" required>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Keterangan : </label>
                                                <textarea class="form-control" type="text" name="ket-ppid" placeholder="Input Keterangan" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="prosesreceive" class="btn btn-outline-info">Proses</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Merk</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">Qty PP</th>
                                    <th scope="col">Qty Update</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $resultpp = "SELECT penerimaan_pembelian.*, pembelian.*, detail_penerimaan_pembelian.*, office.id_office, office.office_name, department.id_department, department.department_name, mastercategory.*, masterjenis.*, satuan.nama_satuan FROM penerimaan_pembelian
                                INNER JOIN pembelian ON penerimaan_pembelian.pp_id_pembelian = pembelian.id_pembelian
                                INNER JOIN detail_penerimaan_pembelian ON penerimaan_pembelian.id_penerimaan = detail_penerimaan_pembelian.id_penerimaan_pp
                                INNER JOIN office ON pembelian.id_office = office.id_office
                                INNER JOIN department ON pembelian.id_department = department.id_department
                                INNER JOIN mastercategory ON LEFT(detail_penerimaan_pembelian.pluid_penerimaan, 6) = mastercategory.IDBarang
                                INNER JOIN masterjenis ON RIGHT(detail_penerimaan_pembelian.pluid_penerimaan, 4) = masterjenis.IDJenis
                                INNER JOIN satuan ON mastercategory.id_satuan = satuan.id_satuan
                                WHERE pembelian.ppid = '$dec_act' AND pembelian.status_pp = '$arrsp[9]' AND detail_penerimaan_pembelian.status_penerimaan = 'N' GROUP BY detail_penerimaan_pembelian.id_penerimaan_detail ORDER BY detail_penerimaan_pembelian.id_penerimaan_detail ASC";
                                $querypp = mysqli_query($conn, $resultpp);
                                while ($datapp = mysqli_fetch_assoc($querypp)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $idb = $datapp["pluid_penerimaan"];?></td>
                                    <td><?= $namab = $datapp["NamaBarang"].' '.$datapp["NamaJenis"];?></td>
                                    <td><?= $datapp["nama_satuan"];?></td>
                                    <td><?= $datapp["merk_penerimaan"];?></td>
                                    <td><?= $datapp["tipe_penerimaan"];?></td>
                                    <td><?= $datapp["qty_pembelian"];?></td>
                                    <td><strong><?= $datapp["qty_penerimaan"];?></strong></td>
                                    <td>
                                        <button title="Input Barang" type="button" class="btn btn-icon btn-primary mr-1"><i class="ft-edit-3" data-toggle="modal" data-target="#update<?= $datapp["id_penerimaan_detail"];?>"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $datapp['id_penerimaan_detail']; ?>" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Update Penerimaan Barang : <?= $idb." - ".$namab; ?></h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input type="hidden" name="page" value="<?= $redirect; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="norefdata" value="<?= substr($dec_act, 4, 5); ?>" class="form-control" readonly>
                                                        <input type="hidden" name="idpdata" value="<?= $datapp["id_penerimaan_detail"]; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="idsppdata" value="<?= $arrsp[8]; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="ppiddata" value="<?= $datapp["id_penerimaan"]; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="qtypp" value="<?= $datapp["qty_pembelian"]; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="userdata" value="<?= $usernik; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="pluiddata" value="<?= $idb; ?>" class="form-control" readonly>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Tgl Terima : </label>
                                                            <input class="form-control" type="date" name="tgldata" value="<?= substr($datapp["tgl_penerimaan"], 0, 10); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Merk Barang : </label>
                                                            <input type="text" name="merkdata" value="<?= $datapp["merk_penerimaan"]; ?>" placeholder="Input Merk Barang" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Tipe Barang : </label>
                                                            <input type="text" name="tipedata" value="<?= $datapp["tipe_penerimaan"]; ?>" placeholder="Input tipe / model barang" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label>Nomor Ref SP / BTB : </label>
                                                            <input type="text" name="btbdata" value="<?= $datapp["no_btb"]; ?>" placeholder="Input Nomor Referensi (Optional)" class="form-control">
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Qty PP : </label>
                                                            <input type="number" value="<?= $datapp["qty_pembelian"]; ?>" class="form-control" readonly>
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Qty Terima : </label>
                                                            <input type="number" name="qtyterima" value="<?= $datapp["qty_penerimaan"]; ?>" placeholder="Qty Terima" class="form-control" readonly>
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Qty Update : </label>
                                                            <input type="number" name="qtyterimabaru" placeholder="Qty Update" class="form-control">
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Keterangan :</label>
                                                            <textarea class="form-control" type="text" name="katerangan" placeholder="Keterangan"><?= $datapp["keterangan_penerimaan"]; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="updatedata" class="btn btn-outline-primary">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </tr>
                                <?php 
                                } ?>
                            </tbody>
                        </table>
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

</script>

<?php
    include ("includes/templates/alert.php");
?>