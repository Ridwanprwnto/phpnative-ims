<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$offdep = $idoffice.$iddept;

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

if(isset($_POST["prosesdatappp3at"])){
    if(ProsesPPP3AT($_POST) > 0 ){
        header("location: index.php?page=$encpid");
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
                    <h4 class="card-title">Pengajuan Pembelian Atas Pemusnahan Nomor : <?= $dec_act; ?></h4>
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
                        <button type="button" class="btn btn-primary btn-min-width mr-1" data-toggle="modal" data-target="#insert-ppp3at">Entry Barang</button>
                        <button type="button" class="btn btn-success btn-min-width mr-1" data-toggle="modal" data-target="#proses-ppp3at">Proses</button>
                        <!-- Modal Entry PP -->
                        <!-- Modal Insert -->
                        <div class="modal fade text-left" id="insert-ppp3at">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form id="userEntry">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white">Entry Data Barang</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <label>Nama Barang / Peralatan : </label>
                                                    <select class="select2 form-control block" style="width: 100%"
                                                        type="text" name="pluid-ppp3at" id="pluid-ppp3at" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_plu_p3at = mysqli_query($conn, "SELECT A.*, B.NamaBarang, C.NamaJenis FROM detail_p3at AS A
                                                        INNER JOIN mastercategory AS B ON LEFT(A.pluid_p3at, 6) = B.IDBarang
                                                        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_p3at, 4) = C.IDJenis 
                                                        WHERE A.id_head_p3at = '$dec_act' GROUP BY A.pluid_p3at ASC");
                                                        while($data_plu_p3at = mysqli_fetch_assoc($query_plu_p3at)) { ?>
                                                        <option value="<?= $data_plu_p3at['pluid_p3at']." - ".$data_plu_p3at['NamaBarang']." ".$data_plu_p3at['NamaJenis'];?>"><?= $data_plu_p3at['pluid_p3at']." - ".$data_plu_p3at['NamaBarang']." ".$data_plu_p3at['NamaJenis'];?>
                                                        </option>
                                                        <?php 
                                                        } 
                                                    ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-10 mb-2">
                                                    <label>Est Harga : </label>
                                                    <input type="text" name="harga-ppp3at" id="harga-ppp3at" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label>Satuan: </label>
                                                    <input type="text" name="satuan-ppp3at" id="satuan-ppp3at" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label>Merk Barang : </label>
                                                    <input type="text" name="merk-ppp3at" class="form-control" placeholder="Input Merk Barang / Peralatan">
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label>Tipe Barang : </label>
                                                    <input type="text" name="tipe-ppp3at" class="form-control" placeholder="Input Tipe Barang / Peralatan">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label>Qty Barang : </label>
                                                    <input type="number" name="qty-ppp3at" id="qty-ppp3at" class="form-control" placeholder="Input Qty" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" name="keterangan-ppp3at" placeholder="Keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertdatappp3at" class="btn btn-outline-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End -->
                    </div>
                    <div class="card-body">
                    <form action="" method="post">
                        <div class="table-responsive">
                            <table class="table text-center" id="userList">
                                <thead>
                                    <tr>
                                        <th scope="col">Desc</th>
                                        <th scope="col">Unit Cost</th>
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Merk</th>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <div class="modal fade text-left" id="proses-ppp3at">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white">Tujuan PP Atas Pemusnahan</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" name="page_success" value="<?= $redirect_scs; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="page" value="<?= $redirect; ?>" class="form-control" readonly>
                                                    <input class="form-control" type="hidden" name="status-ppnb" value="<?= $arrsp[0] ;?>" readonly>
                                                    <input class="form-control" type="hidden" name="user-ppnb" value="<?= $usernik;?>" readonly>
                                                    <input class="form-control" type="hidden" name="office-ppnb" value="<?= $idoffice;?>" readonly>
                                                    <input class="form-control" type="hidden" name="dept-ppnb" value="<?= $iddept;?>" readonly>
                                                    <input class="form-control" type="hidden" name="noref-p3at" value="<?= $dec_act;?>" readonly>
                                                    <input class="form-control" type="hidden" name="status-p3at" value="<?= $arrsp3at[2];?>" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Office : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" type="text" name="office-to" required>
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
                                                        <select class="select2 form-control block" style="width: 100%" type="text" type="text" name="dept-to" required>
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
                                                        <input type="date" class="form-control" name="tgl-to" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keperluan :</label>
                                                        <textarea class="form-control" type="text" name="keperluan-ppnb"  placeholder="Keperluan PP" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosesdatappp3at" class="btn btn-outline-success">Proses PP</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    </div>
                    <a href="index.php?page=<?= $encpid;?>" class="btn btn-secondary btn-min-width ml-2 mb-2">
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
    $("select[name=pluid-ppp3at]").on('change', function(){
        var IDpp3at = $('#pluid-ppp3at').val();
        if(IDpp3at) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {IDSATUAN:IDpp3at},
                dataType:"JSON",
                success:function(data){
                    if (data.length > 0) {
                        $('#satuan-ppp3at').val((data[0].nama_satuan));
                        $('#harga-ppp3at').val((data[0].HargaJenis));
                    }
                    else {
                        $('#satuan-ppp3at').val('');
                        $('#harga-ppp3at').val('');
                    }
                }
            });
        }
        else {
            $('#satuan-ppp3at').val('');
            $('#harga-ppp3at').val('');
        }
    });
});


$(document).ready(function() {

    let counter = 0;

    $('#userEntry').on('submit', function(e) {
    e.preventDefault();

        const rows = [];
        $.each($(this).serializeArray(), function(i, field) {
            if (i > 0 && field.name === rows[rows.length - 1].name) {
            rows[rows.length - 1].value += ';' + field.value;
            } else {
            rows.push(field);
            }
        });

        let list = '<tr>';

        $.each(rows, function(i, field) {
            list += '<td>' + field.value + '<input type="hidden" class="form-control" name="barangentry[' + String(counter) + '][' + field.name + ']" value="' + field.value + '" readonly/>' + '</td>';
        });

        list += '<td><button class="btn btn-icon btn-danger" title="Delete Item" onclick="return this.parentNode.parentNode.remove();"><i class="ft-delete"></i></button></tr>';

        $('#userList tbody').append(list);

        $('#insert-ppp3at').modal('hide');

        counter++;

        $(this)[0].reset();

        toastr.success('Barang berhasil di tambah kedalam tabel!', 'SJ Barang Keluar');
    });

});
</script>

<?php
    include ("includes/templates/alert.php");
?>