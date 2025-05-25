<?php
$page_id = $_GET['page'];

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$usernik = $_SESSION["user_nik"];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["proses-bkb"])){
    if(ProsesBKB($_POST) > 0 ){
        $alert_success = "Bukti Keluar Barang Berhasil Diproses";
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Proses Bukti Keluar Barang Sarana Elektrikal</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-row">
                <!-- Alert -->
                <?php
                if (isset($alert_success)) {
                    ?>
                        <div class="alert alert-primary alert-dismissible ml-1 mr-2 pull-right" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?= $alert_success; ?>
                        </div>
                    <?php
                }
                ?>
                </div>
                <div class="card-content collapse show">
                <form action="" method="post">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-md-6 mb-2">
                                        <label>Office : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-bkb" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                            $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$office_id'");
                                            while($data_off = mysqli_fetch_assoc($query_off)) {
                                            ?>
                                            <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Department : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="dept-bkb" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                            $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$dept_id'");
                                            while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                            ?>
                                            <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Divisi : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="div-bkb" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                            $query_div = mysqli_query($conn, "SELECT * FROM divisi");
                                            while($data_div = mysqli_fetch_assoc($query_div)) {
                                            ?>
                                            <option value="<?= $data_div["id_divisi"];?>"><?= strtoupper($data_div["divisi_name"]);?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Tanggal : </label>
                                        <input type="date" name="tgl-bkb" value="<?= date("Y-m-d");?>" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nama Barang : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="barang-bkb" id="barang-bkb" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_barang = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
                                                WHERE A.ba_id_office = '$office_id' AND A.ba_id_department = '$dept_id' GROUP BY A.pluid ASC");
                                                while($data_barang = mysqli_fetch_assoc($query_barang)) { ?>
                                                <option value="<?= $data_barang['ba_id_office'].$data_barang['ba_id_department'].$data_barang['pluid'];?>"><?= $data_barang['pluid']." - ".$data_barang['NamaBarang']." ".$data_barang['NamaJenis'];?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Serial Number : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="sn-bkb" id="sn-bkb" required>
                                            <option value="" selected disabled>Please Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Merk Barang : </label>
                                        <input type="text" name="merk-bkb" id="merk-bkb" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Tipe Barang : </label>
                                        <input type="text" name="tipe-bkb" id="tipe-bkb" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nomor Aktiva : </label>
                                        <input type="text" name="aktiva-bkb" id="aktiva-bkb" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nomor Lambung : </label>
                                        <input type="text" name="nomor-bkb" id="nomor-bkb" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Lokasi : </label>
                                        <input type="text" name="lokasi-bkb" class="form-control" placeholder="lokasi Pemakaian / Keluar Barang" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Keterangan :</label>
                                        <textarea class="form-control" type="text" name="ket-bkb" placeholder="Keterangan" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary square btn-min-width ml-2 mr-2 mb-2 pull-right" data-toggle="modal" data-target="#modal-bkb">Proses</button>
                    <!-- Modal Entry PP -->
                    <div class="modal fade text-left" id="modal-bkb">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary white">
                                    <h4 class="modal-title white">Proccess Confirmation</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                                <input type="hidden" name="modifref-bkb" value="<?= $arrmodifref[3]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-bkb" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="kondisi-bkb" value="<?= $arrcond[0]; ?>" class="form-control" readonly>
                                            <div class="col-md-12 mb-2">
                                                <label>Nomor BKB : </label>
                                                <input type="text" name="id-bkb" value="<?= autonum(6, 'nomor_bkb', 'bkb'); ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" name="proses-bkb" class="btn btn-outline-primary">Proses</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
$(document).ready(function () {
    $("#barang-bkb").on('change', function () {
        var IDBarang = $('#barang-bkb').val();
        var data = "BKBIDBARANG=" + IDBarang;
        if (IDBarang) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success: function (htmlresponse) {
                    $('#sn-bkb').html(htmlresponse);
                }
            });
        } else {
            $('#sn-bkb').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function () {
    $("select[name=barang-bkb],select[name=sn-bkb]").on('change', function () {
        var BarangID = $('#barang-bkb').val();
        var SNid = $('#sn-bkb').val();
        if (BarangID && SNid) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {
                    BKBBARANG: BarangID,
                    BKBSN: SNid
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#merk-bkb').val((data[0].ba_merk));
                        $('#tipe-bkb').val((data[0].ba_tipe));
                        $('#aktiva-bkb').val((data[0].no_at));
                        $('#nomor-bkb').val((data[0].no_lambung));
                    } else {
                        $('#merk-bkb').val('');
                        $('#tipe-bkb').val('');
                        $('#aktiva-bkb').val('');
                        $('#nomor-bkb').val('');
                    }
                }
            });
        } else {
            $('#merk-bkb').val('');
            $('#tipe-bkb').val('');
            $('#aktiva-bkb').val('');
            $('#nomor-bkb').val('');
        }
    });

});
</script>
