<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

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
$redirect_post = "index.php?page=$encpid";

if(isset($_POST["updatedatapp"])){
    if(UpdateRevisiPP($_POST) > 0){
        $datapost = $_POST["pluid"];
        $alert = array("Success!", "Item Barang ".$datapost." berhasil direvisi", "success", "$redirect");
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
                    <h4 class="card-title">Form Revisi Pengajuan Pembelian : <?= $dec_act; ?></h4>
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
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Pluid</th>
                                    <th scope="col">Desc</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Unit Cost</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $nol = 0;
                                $no = 1;

                                if (substr($dec_act, 0, 3) == "PPG" || substr($dec_act, 0, 3) == "PPM") {
                                    $resultpp = "SELECT detail_pembelian.*, pembelian.*, office.id_office, office.office_name, department.id_department, department.department_name, mastercategory.*, masterjenis.*, satuan.nama_satuan FROM pembelian 
                                    INNER JOIN detail_pembelian ON pembelian.noref = detail_pembelian.noref
                                    INNER JOIN office ON pembelian.id_office = office.id_office
                                    INNER JOIN department ON pembelian.id_department = department.id_department
                                    INNER JOIN mastercategory ON LEFT(detail_pembelian.plu_id, 6) = mastercategory.IDBarang
                                    INNER JOIN masterjenis ON RIGHT(detail_pembelian.plu_id, 4) = masterjenis.IDJenis
                                    INNER JOIN satuan ON mastercategory.id_satuan = satuan.id_satuan
                                    WHERE detail_pembelian.proses = 'Y' AND pembelian.id_office = '$idoffice' AND pembelian.id_department = '$iddept' AND ppid = '$dec_act'";
                                }

                                $querypp = mysqli_query($conn, $resultpp);
                                while ($datapp = mysqli_fetch_assoc($querypp)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $datapp["plu_id"];?></td>
                                    <td><?= $datapp["NamaBarang"].' '.$datapp["NamaJenis"].' '.$datapp["merk"].' '.$datapp["tipe"];?></td>
                                    <td><?= $datapp["nama_satuan"];?></td>
                                    <td><?= $qty = $datapp["qty"];?></td>
                                    <td><?= 'Rp. '.number_format($cost = $datapp["HargaJenis"],2);?></td>
                                    <td><?= 'Rp. '.number_format($subtotal = $cost*$qty,2);?></td>
                                    <?php $total = $nol+=$subtotal; ?>
                                    <td>
                                        <button type="button" id="<?= $datapp['id_dpp']; ?>" name="revisi_pp" title="Revisi Item : <?= $datapp['plu_id']; ?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-success revisi_pp"><i class="ft-edit"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (isset($total)) { ?>
                                <tr>
                                    <th colspan="6">Total :</th>
                                    <th colspan="1"><?='Rp. ' .number_format($total,2); ?></th>
                                    <th>
                                    </th>
                                </tr>
                            <?php }?>
                            </tbody>
                            <!-- Modal Update PP -->
                            <div class="modal fade text-left" id="modalRevisiPP" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="rev-labelpp"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="page" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" id="rev-idhpp" name="idpp" readonly>
                                                <input class="form-control" type="hidden" id="rev-iddpp" name="iddpp" readonly>
                                                <input class="form-control" type="hidden" id="rev-idofdp" name="idoffdep" readonly>
                                                <input class="form-control" type="hidden" id="rev-norefpp" name="noref" readonly>
                                                <input class="form-control" type="hidden" name="jenispp" value="<?= substr($dec_act, 0, 3); ?>" readonly>
                                                <input class="form-control" type="hidden" id="rev-hargapp" name="hargajenis" readonly>
                                                <input class="form-control" type="hidden" id="rev-plupp" name="pluid" readonly>
                                                <input class="form-control" type="hidden" id="rev-tglpp" name="tglpp" readonly>
                                                <input class="form-control" type="hidden" id="rev-qtyoldpp" name="qtyold" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nama Barang : </label>
                                                    <input class="form-control" type="text" id="rev-itempp" name="namabarang" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Merk : </label>
                                                    <input class="form-control" id="rev-merkpp" type="text" name="merk">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Tipe : </label>
                                                    <input class="form-control" id="rev-tipepp" type="text" name="tipe">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Qty : </label>
                                                    <input class="form-control" id="rev-qtypp" type="number" name="qty" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedatapp" class="btn grey btn-outline-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                    </div>
                    <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary ml-2 mt-1 mb-2">
                        <i class="ft-chevrons-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>

$(document).ready(function(){
    $(document).on('click', '.revisi_pp', function(){  
        var barang_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONUPDATEPP:barang_pp},  
            dataType:"json",  
            success:function(data){
                $('#rev-iddpp').val(data.id_dpp);
                $('#rev-idhpp').val(data.ppid);
                $('#rev-norefpp').val(data.noref);
                $('#rev-idofdp').val(data.id_offdep);
                $('#rev-plupp').val(data.plu_id);
                $('#rev-tglpp').val(data.tgl_detail_pp);
                $('#rev-hargapp').val(data.HargaJenis);
                $('#rev-itempp').val(data.NamaBarang+" "+data.NamaJenis);
                $('#rev-merkpp').val(data.merk);
                $('#rev-tipepp').val(data.tipe);
                $('#rev-qtypp').val(data.qty);
                $('#rev-qtyoldpp').val(data.qty);
                
                $('#rev-labelpp').html("Revisi PP Barang : "+data.plu_id);
                $('#modalRevisiPP').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>