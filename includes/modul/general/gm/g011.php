<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$offdep = $idoffice.$iddept;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["prosesdatapp"])){
    if(ProsesBarangPP($_POST) > 0 ){
        $alert = array("Success!", "PP berhasil dibuat", "success", "$redirect");
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
                    <h4 class="card-title">Entry Pengajuan Pembelian</h4>
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
                        <button type="button" class="btn btn-success square btn-min-width" data-toggle="modal" data-target="#proses-ppnb">Proses PP</button>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="table-responsive">
                                <table class="table text-center" id="table_barangpp">
                                    <thead>
                                        <tr>
                                            <th scope="col">KODE - NAMA BARANG</th>
                                            <th scope="col">UNIT COST</th>
                                            <th scope="col">SATUAN</th>
                                            <th scope="col">MERK</th>
                                            <th scope="col">TIPE</th>
                                            <th scope="col">QTY</th>
                                            <th scope="col">KETERANGAN</th>
                                            <th><button type="button" name="add_barangpp" class="btn btn-success btn-xs add_barangpp"><i class="ft-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="modal fade text-left" id="proses-ppnb">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white">Tujuan Pengajuan Pembelian</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" name="page-ppnb" value="<?= $redirect; ?>" readonly>
                                                    <input class="form-control" type="hidden" name="status-ppnb" value="<?= $arrsp[0] ;?>" readonly>
                                                    <input class="form-control" type="hidden" name="user-ppnb" value="<?= $usernik;?>" readonly>
                                                    <input class="form-control" type="hidden" name="office-ppnb" value="<?= $idoffice;?>" readonly>
                                                    <input class="form-control" type="hidden" name="dept-ppnb" value="<?= $iddept;?>" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Office : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-to" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            $query_off = mysqli_query($conn, "SELECT * FROM office");
                                                            while($data_off = mysqli_fetch_assoc($query_off)) { ?>
                                                            <option value="<?= $data_off['id_office'];?>" ><?= $data_off['id_office'].' - '.strtoupper($data_off['office_name']);?></option>
                                                        <?php 
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Department : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="dept-to" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_dept = mysqli_query($conn, "SELECT * FROM department");
                                                            while($data_dept = mysqli_fetch_assoc($query_dept)) { ?>
                                                            <option value="<?= $datadept = $data_dept['id_department'];?>" ><?= $data_dept['id_department'].' - '.strtoupper($data_dept['department_name']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Tanggal Pengajuan : </label>
                                                        <input type="date" class="form-control" name="tgl-to" max="<?=date('Y-m-d')?>" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keperluan :</label>
                                                        <textarea class="form-control" type="text" name="keperluan-ppnb"  placeholder="Keperluan PP" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosesdatapp" class="btn btn-outline-success">Proses</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
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

    var count = 0;

    $(document).on('click', '.add_barangpp', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="kode_barang[]" class="select2 form-control block kode_barang" style="width: 100%" data-kode_barang_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_pp(); ?></select></td>';
        html += '<td><input type="text" name="cost_barang[]" class="form-control cost_barang" id="cost_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="satuan_barang[]" class="form-control satuan_barang" id="satuan_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="merk_barang[]" class="form-control merk_barang"/></td>';
        html += '<td><input type="text" name="tipe_barang[]" class="form-control tipe_barang"/></td>';
        html += '<td><input type="number" name="qty_barang[]" class="form-control qty_barang" required/></td>';
        html += '<td><textarea class="form-control ket_barang" type="text" name="ket_barang[]" placeholder="Input keterangan peruntukan barang" required></textarea></td>';
        html += '<td><button type="button" name="remove_barangpp" class="btn btn-danger btn-xs remove_barangpp"><i class="ft-minus"></i></button></td>';
        $('tbody').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_barangpp', function(){
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.kode_barang', function(){
        var kd_barang = $(this).val();
        var kd_barang_id = $(this).data('kode_barang_id');
        if (kd_barang) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {
                    IDSATUAN: kd_barang
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#cost_barang_id'+kd_barang_id).val((data[0].HargaJenis));
                        $('#satuan_barang_id'+kd_barang_id).val((data[0].nama_satuan));
                    } else {
                        $('#cost_barang_id'+kd_barang_id).val('');
                        $('#satuan_barang_id'+kd_barang_id).val('');
                    }
                }
            });
        }
        $('#cost_barang_id'+kd_barang_id).val('');
        $('#satuan_barang_id'+kd_barang_id).val('');
    });
});

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