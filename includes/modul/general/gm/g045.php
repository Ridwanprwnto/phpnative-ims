<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertsjkeluar"])){
    if(InsertBarangKeluar($_POST) > 0 ){
        $alert = array("Success!", "Data Barang Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertsjkeluarna"])){
    if(InsertBarangKeluarNA($_POST) > 0 ){
        $alert = array("Success!", "Data Barang Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletesjkeluar"])){
    if(DeleteBarangKeluar($_POST) > 0 ){
        $alert = array("Success!", "Data Barang Berhasil Dihapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["prosessjkeluar"])){
    if(ProsesBarangKeluar($_POST) > 0 ){
        $alert = array("Success!", "Surat jalan keluar barang berhasil diproses", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}

// Check null sj di database
$offdep = $idoffice.$iddept;
$result = mysqli_query($conn, "SELECT head_no_sj FROM detail_surat_jalan WHERE from_sj = '$offdep' AND head_no_sj IS NULL");

if(mysqli_fetch_assoc($result) == FALSE) {
    $disbtn = "disabled";
}
else {
    $actbtn = "success";
}
?>

<!-- Basic form layout section start -->
<section id="basic-select2">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Surat Jalan Keluar Barang</h4>
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

                        <div class="btn-group ml-1 mr-1 mb-2">
                            <button type="button" class="btn btn-primary square btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Entry Barang</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" data-target="#insert-dat" href="#">Terdaftar Aktiva</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#insert-nondat" href="#">Tidak Terdaftar Aktiva</a>
                            </div>
                        </div>

                        <button type="button" class="btn btn-<?= isset($actbtn) ? 'success' : 'secondary'; ?> square btn-min-width mr-1 mb-2" data-toggle="modal" data-target="#proses-brout" <?= isset($disbtn) ? $disbtn : ''; ?>>Proses</button>
                            <!-- Modal Insert Terdaftar Aktiva -->
                            <div class="modal fade text-left" id="insert-dat">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white">Entry Data Barang Terdaftar Aktiva</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-2">
                                                    <label>Nama Barang : </label>
                                                        <select class="select2 form-control block" style="width: 100%"
                                                            type="text" name="pluid-brout" id="pluid-brout" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                            $query_plu_service = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                                            INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                                            INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
                                                            WHERE A.ba_id_office = '$idoffice' AND A.ba_id_department = '$iddept' GROUP BY A.pluid ASC");
                                                            while($data_plu_service = mysqli_fetch_assoc($query_plu_service)) { ?>
                                                            <option value="<?= $data_plu_service['ba_id_office'].$data_plu_service['ba_id_department'].$data_plu_service['pluid'];?>"><?= $data_plu_service['pluid']." - ".$data_plu_service['NamaBarang']." ".$data_plu_service['NamaJenis'];?>
                                                            </option>
                                                            <?php 
                                                            } 
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Serial Number : </label>
                                                        <select class="select2 form-control block" style="width: 100%"
                                                            type="text" name="sn-brout" id="sn-brout" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Merk Barang : </label>
                                                        <input type="text" name="merk-brout" id="merk-brout"
                                                            class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Tipe Barang : </label>
                                                        <input type="text" name="tipe-brout" id="tipe-brout"
                                                            class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>No Aktiva : </label>
                                                        <input type="text" name="aktiva-brout" id="aktiva-brout"
                                                            class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keterangan :</label>
                                                        <textarea class="form-control" type="text"
                                                            name="keterangan-brout"
                                                            placeholder="Input keterangan pengiriman barang"></textarea>
                                                    </div>
                                                    <input type="hidden" name="user-brout" value="<?= $usernik;?>" class="form-control">
                                                    <input type="hidden" name="office-brout" value="<?= $idoffice;?>" class="form-control">
                                                    <input type="hidden" name="dept-brout" value="<?= $iddept;?>" class="form-control">
                                                    <input type="hidden" name="page-brout" value="<?= $redirect; ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" name="insertsjkeluar"
                                                    class="btn btn-outline-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->
                            <!-- Modal Insert Tidak Terdaftar Aktiva -->
                            <div class="modal fade text-left" id="insert-nondat">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white">Entry Data Barang Tidak Terdaftar Aktiva</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-2">
                                                        <label>Nama Barang : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="pluidna-brout" id="pluidna-brout" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_plu_na = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM masterstock AS A
                                                        INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang
                                                        INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
                                                        WHERE A.ms_id_office = '$idoffice' AND A.ms_id_department = '$iddept' ORDER BY B.NamaBarang ASC");
                                                            while($data_plu_na = mysqli_fetch_assoc($query_plu_na)) { ?>
                                                            <option value="<?= $data_plu_na['ms_id_office'].$data_plu_na['ms_id_department'].$data_plu_na['pluid'];?>"><?= $data_plu_na['pluid']." - ".$data_plu_na['NamaBarang']." ".$data_plu_na['NamaJenis'];?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label>Satuan Barang : </label>
                                                        <input type="text" name="satuan-brout" id="satuan-brout" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label>Saldo Barang : </label>
                                                        <input type="number" name="saldo-brout" id="saldo-brout" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Serial Number : </label>
                                                        <input type="text" name="sn-brout" placeholder="Input serial number barang (Optional)" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Qty Barang : </label>
                                                        <input type="number" name="qty-brout" placeholder="Input qty barang" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Merk Barang : </label>
                                                        <input type="text" name="merk-brout" placeholder="Input merk barang (Optional)" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Tipe Barang : </label>
                                                        <input type="text" name="tipe-brout" placeholder="Input tipe barang (Optional)" class="form-control">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keterangan :</label>
                                                        <textarea class="form-control" type="text" name="keterangan-brout" placeholder="Input keterangan pengiriman barang (Optional)"></textarea>
                                                    </div>
                                                    <input type="hidden" name="user-brout" value="<?= $usernik;?>" class="form-control">
                                                    <input type="hidden" name="office-brout" value="<?= $idoffice;?>" class="form-control">
                                                    <input type="hidden" name="dept-brout" value="<?= $iddept;?>" class="form-control">
                                                    <input type="hidden" name="page-brout" value="<?= $redirect;?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="insertsjkeluarna" class="btn btn-outline-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->
                            <div class="modal fade text-left" id="proses-brout">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white">Tujuan Pengiriman</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-12 mb-2">
                                                        <input type="hidden" name="user-brout" value="<?= $usernik;?>" class="form-control">
                                                        <input type="hidden" name="from-brout" value="<?= $idoffice.$iddept;?>" class="form-control">
                                                        <label>Office : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="office-brout" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php
                                                        $query_off = mysqli_query($conn, "SELECT * FROM office");
                                                        while($data_off = mysqli_fetch_assoc($query_off)) { ?>
                                                            <option value="<?= $data_off['id_office'];?>">
                                                                <?= $data_off['id_office'].' - '.strtoupper($data_off['office_name']);?>
                                                            </option>
                                                            <?php 
                                                        }
                                                    ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Department : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="dept-brout" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                        $query_dept = mysqli_query($conn, "SELECT * FROM department");
                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) { ?>
                                                            <option value="<?= $datadept = $data_dept['id_department'];?>">
                                                                <?= $data_dept['id_department'].' - '.strtoupper($data_dept['department_name']);?>
                                                            </option>
                                                            <?php 
                                                        } 
                                                    ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keterangan :</label>
                                                        <textarea class="form-control" type="text"
                                                            name="keterangan-brout"
                                                            placeholder="Input keterangan pengiriman barang"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosessjkeluar"
                                                    class="btn btn-outline-success">Proses</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered zero-configuration text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA BARANG</th>
                                        <th>SERIAL NUMBER</th>
                                        <th>NO AKTIVA</th>
                                        <th>QTY</th>
                                        <th>KETERANGAN</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nol = 0;
                                    $no = 1;
                                    $sql = "SELECT detail_surat_jalan.*, mastercategory.*, masterjenis.* FROM detail_surat_jalan
                                    INNER JOIN mastercategory ON LEFT(detail_surat_jalan.pluid_sj, 6) = mastercategory.IDBarang
                                    INNER JOIN masterjenis ON RIGHT(detail_surat_jalan.pluid_sj, 4) = masterjenis.IDJenis
                                    WHERE detail_surat_jalan.jenis_sj = 'M' AND detail_surat_jalan.from_sj = '$offdep' AND detail_surat_jalan.head_no_sj IS NULL";
                                    $query = mysqli_query($conn, $sql);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                     ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["pluid_sj"]." - ".$data["NamaBarang"].' '.$data["NamaJenis"].' '.$data["merk_sj"].' '.$data["tipe_sj"];?>
                                        </td>
                                        <td><?= $data["sn_sj"] == '' ? '-' : $data["sn_sj"];?></td>
                                        <td><?= $data["at_sj"] == '' ? '-' : $data["at_sj"];?></td>
                                        <td><?= $data["qty_sj"];?></td>
                                        <td><?= $data["keterangan_sj"] == '' ? '-' : $data["keterangan_sj"];?></td>
                                        <td>
                                            <button title="Delete Data" type="button" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data["detail_no_sj"];?>"><i class="ft-delete"></i></button>
                                        </td>
                                        <!-- Modal Delete -->
                                            <div class="modal fade text-left" id="delete<?= $data['detail_no_sj']; ?>"
                                                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="" method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger white">
                                                                <h4 class="modal-title white" id="myModalLabel1">Delete
                                                                    Confirmation</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control" type="hidden" name="del-idsj" value="<?= $data["detail_no_sj"];?>">
                                                                <label>Are you sure to delete this data?</label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="deletesjkeluar" class="btn btn-outline-danger">Yes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <!-- End Modal -->
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
    $(document).ready(function () {
        $("#pluid-brout").on('change', function () {
            var pluID = $('#pluid-brout').val();
            var data = "PLUBROUT=" + pluID;
            if (pluID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#sn-brout').html(htmlresponse);
                    }
                });
            } else {
                $('#sn-brout').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });

    $(document).ready(function () {
        $("select[name=pluid-brout],select[name=sn-brout]").on('change', function () {
            var pluID = $('#pluid-brout').val();
            var snID = $('#sn-brout').val();
            if (pluID && snID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: {
                        PLUIDBROUT: pluID,
                        SNBROUT: snID
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#office-brout').val((data[0].ba_id_office));
                            $('#dept-brout').val((data[0].ba_id_department));
                            $('#merk-brout').val((data[0].ba_merk));
                            $('#tipe-brout').val((data[0].ba_tipe));
                            $('#aktiva-brout').val((data[0].no_at));
                        } else {
                            $('#office-brout').val('');
                            $('#dept-brout').val('');
                            $('#merk-brout').val('');
                            $('#tipe-brout').val('');
                            $('#aktiva-brout').val('');
                        }
                    }
                });
            } else {
                $('#office-brout').val('');
                $('#dept-brout').val('');
                $('#merk-brout').val('');
                $('#tipe-brout').val('');
                $('#aktiva-brout').val('');
            }
        });

    });

    $(document).ready(function () {
        $("#pluidna-brout").on('change', function () {
            var pluID = $('#pluidna-brout').val();
            var data = "PLUBROUTNA=" + pluID;
            if (pluID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#satuan-brout').val((data[0].nama_satuan));
                            $('#saldo-brout').val((data[0].saldo_akhir));
                        } else {
                            $('#satuan-brout').val('');
                            $('#saldo-brout').val('');
                        }
                    }
                });
            } else {
                $('#satuan-brout').val('');
                $('#saldo-brout').val('');
            }
        });
    });
</script>

<?php
    include ("includes/templates/alert.php");
?>