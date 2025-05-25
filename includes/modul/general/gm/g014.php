<?php
$page_id = $_GET['page'];

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$usernik = $_SESSION["user_nik"];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);


if(isset($_POST["proses-bkse"])){
    if(ProsesBKSE($_POST) > 0 ){
        $alert_success = "BA Kerusakan Sarana Elektrikal Berhasil Dibuat";
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
                    <h4 class="card-title">Form Berita Acara Kerusakan Sarana Elektrikal</h4>
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
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-bkse" required>
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
                                            type="text" name="dept-bkse" required>
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
                                            type="text" name="div-bkse" required>
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
                                        <input type="date" name="tgl-bkse" value="<?= date("Y-m-d");?>" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nama Barang : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="barang-bkse" id="barang-bkse" required>
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
                                            type="text" name="sn-bkse" id="sn-bkse" required>
                                            <option value="" selected disabled>Please Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Merk Barang : </label>
                                        <input type="text" name="merk-bkse" id="merk-bkse" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Tipe Barang : </label>
                                        <input type="text" name="tipe-bkse" id="tipe-bkse" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nomor Aktiva : </label>
                                        <input type="text" name="aktiva-bkse" id="aktiva-bkse" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nomor Lambung : </label>
                                        <input type="text" name="nomor-bkse" id="nomor-bkse" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Pemakai / Penanggung Jawab : </label>
                                        <select name="pemakai-bkse" class="select2 form-control block" style="width: 100%" type="text" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$office_id' AND id_group NOT LIKE 'GP01'");
                                                while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                <option value="<?= $data_user['username'];?>" ><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Penempatan : </label>
                                        <input type="text" name="penempatan-bkse" id="penempatan-bkse" placeholder="Lokasi Penempatan Barang" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Kerusakan :</label>
                                        <textarea class="form-control" type="text" name="kerusakan-bkse" placeholder="Jelaskan Kerusakan Barang" required></textarea>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Posisi Saat ini :</label>
                                        <textarea class="form-control" type="text" name="posisi-bkse" placeholder="Posisi Penempatan Barang Saat Ini" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary square btn-min-width ml-2 mr-2 mb-2 pull-right" data-toggle="modal" data-target="#modal-bkse">Proses</button>
                    <!-- Modal Entry PP -->
                    <div class="modal fade text-left" id="modal-bkse">
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
                                                <input type="hidden" name="modifref-bkse" value="<?= $arrmodifref[4]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-bkse" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="kondisi-bkse" value="<?= $arrcond[2]; ?>" class="form-control" readonly>
                                            <div class="col-md-12 mb-2">
                                                <label>Nomor BA : </label>
                                                <input type="text" name="id-bkse" value="<?= autonum(6, 'nomor_bkse', 'bkse'); ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" name="proses-bkse" class="btn btn-outline-primary">Proses</button>
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
    $("#barang-bkse").on('change', function () {
        var IDBarang = $('#barang-bkse').val();
        var data = "BKSEIDBARANG=" + IDBarang;
        if (IDBarang) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success: function (htmlresponse) {
                    $('#sn-bkse').html(htmlresponse);
                }
            });
        } else {
            $('#sn-bkse').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function () {
    $("select[name=barang-bkse],select[name=sn-bkse]").on('change', function () {
        var BarangID = $('#barang-bkse').val();
        var SNid = $('#sn-bkse').val();
        if (BarangID && SNid) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {
                    BKSEBARANG: BarangID,
                    BKSESN: SNid
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#merk-bkse').val((data[0].ba_merk));
                        $('#tipe-bkse').val((data[0].ba_tipe));
                        $('#aktiva-bkse').val((data[0].no_at));
                        $('#nomor-bkse').val((data[0].no_lambung));
                        $('#penempatan-bkse').val((data[0].posisi));
                    } else {
                        $('#merk-bkse').val('');
                        $('#tipe-bkse').val('');
                        $('#aktiva-bkse').val('');
                        $('#nomor-bkse').val('');
                        $('#penempatan-bkse').val('');
                    }
                }
            });
        } else {
            $('#merk-bkse').val('');
            $('#tipe-bkse').val('');
            $('#aktiva-bkse').val('');
            $('#nomor-bkse').val('');
            $('#penempatan-bkse').val('');
        }
    });

});
</script>
