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

if(isset($_POST["insertdatappnbnb"])){
    if(InsertBarangPPNB($_POST) > 0 ){
        $datapost = $_POST["pluid-ppnb"];
        $alert = array("Success!", "Item Barang ".$datapost." berhasil ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatapp"])){
    if(UpdateRevisiPP($_POST) > 0){
        $datapost = $_POST["pluid"];
        $alert = array("Success!", "Item Barang ".$datapost." berhasil diupdate", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatapp"])){
    if(DeleteBarangPPNB($_POST) > 0 ){
        $datapost = $_POST["pluid-dppnb"];
        $alert = array("Success!", "Item Barang ".$datapost." berhasil didelete", "success", "$redirect");
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
                    <h4 class="card-title">Form Edit Pengajuan Pembelian Nomor : <?= $dec_act; ?></h4>
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
                    <?php
                    if (substr($dec_act, 0, 3) == "PPG" || substr($dec_act, 0, 3) == "PPM") { ?>
                    <button type="button" class="btn btn-primary btn-min-width" data-toggle="modal" data-target="#insert-ppnb">Add Barang</button>
                    <?php } ?>    
                    <!-- Modal Add Item -->
                    <div class="modal fade text-left" id="insert-ppnb">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white">Entry Data Barang</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                        <input class="form-control" type="hidden" name="page-ppnb" value="<?= $redirect; ?>" readonly>
                                        <input class="form-control" type="hidden" name="noref-ppnb" value="<?= substr($dec_act, 4, 5); ?>" readonly>
                                        <input class="form-control" type="hidden" name="offdep-ppnb" value="<?= $idoffice.$iddept; ?>" readonly>
                                        <input class="form-control" type="hidden" name="user-ppnb" value="<?= $nik; ?>" readonly>
                                        <input class="form-control" type="hidden" name="harga-ppnb" id="harga-ppnb" readonly>
                                        <div class="col-md-10 mb-2">
                                            <label>Nama Barang </label>
                                                <select class="select2 form-control block" style="width: 100%" ype="text" name="pluid-ppnb" id="pluid-ppnb" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <?php
                                                    $query_plu_ppnb = mysqli_query($conn, "SELECT A.*, B.* FROM mastercategory AS A
                                                    INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang ORDER BY A.NamaBarang ASC");
                                                    while($data_plu_ppnb = mysqli_fetch_assoc($query_plu_ppnb)) { ?>
                                                    <option value="<?= $data_plu_ppnb['IDBarang'].$data_plu_ppnb['IDJenis'];?>"><?= $data_plu_ppnb['IDBarang'].$data_plu_ppnb['IDJenis']." - ".$data_plu_ppnb['NamaBarang']." ".$data_plu_ppnb['NamaJenis'];?>
                                                    </option>
                                                    <?php 
                                                    } 
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>Satuan: </label>
                                                <input type="text" name="satuan-ppnb" id="satuan-ppnb" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label>Merk Barang : </label>
                                                <input type="text" name="merk-ppnb" class="form-control" placeholder="Input Merk Barang / Peralatan">
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label>Tipe Barang : </label>
                                                <input type="text" name="tipe-ppnb" class="form-control" placeholder="Input Tipe Barang / Peralatan">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label>Qty Barang : </label>
                                                <input type="number" name="qty-ppnb" id="qty-ppnb" class="form-control" placeholder="Input Qty" required>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Keterangan :</label>
                                                <textarea class="form-control" type="text" name="keterangan-ppnb" placeholder="Keterangan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="insertdatappnbnb" class="btn btn-outline-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End -->
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
                                    <td><?= 'Rp. '.number_format(($datapp["harga_pp"] / $qty),2);?></td>
                                    <td><?= 'Rp. '.number_format($subtotal = $datapp["harga_pp"],2);?></td>
                                    <?php $total = $nol+=$subtotal; ?>
                                    <td>
                                        <button type="button" id="<?= $datapp['id_dpp']; ?>" name="update_itempp" title="Edit Item Barang : <?= $datapp['plu_id']; ?>" class="btn btn-icon btn-success update_itempp" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        <button type="button" id="<?= $datapp['id_dpp']; ?>" name="delete_itempp" title="Delete Item Barang : <?= $datapp['plu_id']; ?>" class="btn btn-icon btn-danger delete_itempp" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
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
                            <div class="modal fade text-left" id="modalEditItemPP" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="upd-labelpp"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="page" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" id="upd-idhpp" name="idpp" readonly>
                                                <input class="form-control" type="hidden" id="upd-iddpp" name="iddpp" readonly>
                                                <input class="form-control" type="hidden" id="upd-idofdp" name="idoffdep" readonly>
                                                <input class="form-control" type="hidden" id="upd-norefpp" name="noref" readonly>
                                                <input class="form-control" type="hidden" name="jenispp" value="<?= substr($dec_act, 0, 3); ?>" readonly>
                                                <input class="form-control" type="hidden" id="upd-hargapp" name="hargajenis" readonly>
                                                <input class="form-control" type="hidden" id="upd-plupp" name="pluid" readonly>
                                                <input class="form-control" type="hidden" id="upd-tglpp" name="tglpp" readonly>
                                                <input class="form-control" type="hidden" id="upd-qtyoldpp" name="qtyold" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nama Barang : </label>
                                                    <input class="form-control" type="text" id="upd-itempp" name="namabarang" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Merk : </label>
                                                    <input class="form-control" id="upd-merkpp" type="text" name="merk">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Tipe : </label>
                                                    <input class="form-control" id="upd-tipepp" type="text" name="tipe">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Qty : </label>
                                                    <input class="form-control" id="upd-qtypp" type="number" name="qty" required>
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
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="modalDeleteItemPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input class="form-control" type="hidden" name="page-dppnb" value="<?= $redirect; ?>" readonly>
                                            <input class="form-control" type="hidden" id="del-iddpp" name="id-dppnb" readonly>
                                            <input class="form-control" type="hidden" id="del-norefdpp" name="noref-dppnb" readonly>
                                            <input class="form-control" type="hidden" id="del-ofdpdpp" name="offdep-dppnb" readonly>
                                            <input class="form-control" type="hidden" id="del-datedpp" name="date-dppnb" readonly>
                                            <input class="form-control" type="hidden" id="del-brgdpp" name="pluid-dppnb" readonly>
                                            <input class="form-control" type="hidden" id="del-qtydpp" name="qtypp-dppnb" readonly>
                                            <label id="del-labeldpp"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedatapp" class="btn btn-outline-danger">Yes</button>
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
    $("select[name=pluid-ppnb]").on('change', function(){
        var IDppnb = $('#pluid-ppnb').val();
        if(IDppnb) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {IDSATUAN:IDppnb},
                dataType:"JSON",
                success:function(data){
                    if (data.length > 0) {
                        $('#satuan-ppnb').val((data[0].nama_satuan));
                        $('#harga-ppnb').val((data[0].HargaJenis));
                    }
                    else {
                        $('#satuan-ppnb').val('');
                        $('#harga-ppnb').val('');
                    }
                }
            });
        }
        else {
            $('#satuan-ppnb').val('');
            $('#harga-ppnb').val('');
        }
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_itempp', function(){  
        var barang_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONUPDATEPP:barang_pp},  
            dataType:"json",  
            success:function(data){
                $('#upd-iddpp').val(data.id_dpp);
                $('#upd-idhpp').val(data.ppid);
                $('#upd-norefpp').val(data.noref);
                $('#upd-idofdp').val(data.id_offdep);
                $('#upd-plupp').val(data.plu_id);
                $('#upd-tglpp').val(data.tgl_detail_pp);
                $('#upd-hargapp').val(data.HargaJenis);
                $('#upd-itempp').val(data.NamaBarang+" "+data.NamaJenis);
                $('#upd-merkpp').val(data.merk);
                $('#upd-tipepp').val(data.tipe);
                $('#upd-qtypp').val(data.qty);
                $('#upd-qtyoldpp').val(data.qty);
                
                $('#upd-labelpp').html("Edit PP Barang : "+data.plu_id);
                $('#modalEditItemPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_itempp', function(){  
        var id_dpp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONUPDATEPP:id_dpp},  
            dataType:"json",  
            success:function(data){
                $('#del-iddpp').val(data.id_dpp);
                $('#del-norefdpp').val(data.noref);
                $('#del-ofdpdpp').val(data.id_offdep);
                $('#del-datedpp').val(data.tgl_detail_pp);
                $('#del-brgdpp').val(data.plu_id);
                $('#del-qtydpp').val(data.qty);

                $('#del-labeldpp').html("Dlete Item Barang : "+data.plu_id);
                $('#modalDeleteItemPP').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>