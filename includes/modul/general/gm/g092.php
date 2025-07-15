<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(CreateFormEquipmentChecking($_POST) > 0 ){
        $alert_in = "Form Data Pengecekan Barang Berhasil Dibuat";
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
                    <h4 class="card-title">Form Pengecekan Barang Tidak Terdaftar Aktiva</h4>
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
                        if (isset($alert_in)) {
                    ?>
                        <div class="card-text">
                            <div class="alert alert-icon-right alert-info alert-dismissible mb-2" role="alert">
                                <span class="alert-icon"><i class="la la-info"></i></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                                <strong><?= $alert_in; ?></strong>
                            </div>
                        </div>
                    <?php
                        }
                        elseif (isset($alert_err_lenght)) {
                    ?>
                        <div class="card-text">
                            <div class="alert alert-icon-right alert-danger alert-dismissible mb-2" role="alert">
                                <span class="alert-icon"><i class="la la-info"></i></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                                <strong><?= $alert_err_lenght; ?></strong>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    <form class="form" id="form_cekbarang" action="" method="post">
                      <div class="form-body">
                        <h4 class="form-section"><i class="la la-dropbox"></i> Data Barang</h4>
                        <div class="row">
                          <div class="form-group col-md-12 mb-2">
                            <input type="hidden" name="no-cek" value="<?= autonum(6, "no_equip_check", "equipment_checking"); ?>" class="form-control" readonly>
                            <input type="hidden" name="office-cek" value="<?= $idoffice; ?>" class="form-control" readonly>
                            <input type="hidden" name="dept-cek" value="<?= $iddept; ?>" class="form-control" readonly>
                            <label>PLU - Nama Barang</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="plu-cek" required>
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_plu = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM masterstock AS A
                                    INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang 
                                    INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis 
                                    WHERE ms_id_office = '$idoffice' AND ms_id_department = '$iddept' ORDER BY B.NamaBarang ASC");
                                    while($data_plu = mysqli_fetch_assoc($query_plu)) { ?>
                                <option value="<?= $data_plu['pluid']; ?>"><?= $data_plu['pluid']." - ".$data_plu['NamaBarang']." ".$data_plu['NamaJenis'];?>
                                </option>
                                <?php 
                                    }
                                ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>Tgl Pengecekan</label>
                            <input type="date" name="tgl-cek" class="form-control" value="<?= date("Y-m-d") ?>" required>
                          </div>
                        </div>
                        <h4 class="form-section"><i class="la la-users"></i> Data PIC</h4>
                        <div class="row">
                          <div class="form-group col-6 mb-2">
                            <label>Diterima Dari</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="user-cek" required>
                            <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_users = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_group NOT LIKE '$arrgroup[0]' ORDER BY username ASC");
                                    while($data_users = mysqli_fetch_assoc($query_users)) { ?>
                                        <option value="<?= $data_users['nik'];?>"><?= $data_users['nik'].' - '.strtoupper($data_users['username']);?>
                                        </option>
                                        <?php 
                                    }
                                ?>
                            </select>
                          </div>
                          <div class="form-group col-6 mb-2">
                            <label>PIC</label>
                            <select class="select2 form-control block" style="width: 100%" type="text" name="pic-cek" required>
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
                          <div class="form-group col-md-12 mb-2">
                            <label>Kondisi Barang</label>
                            <select class="select2 form-control" style="width: 100%" type="text" name="kondisi-cek" required>
                            <option value="" selected disabled>Please Select</option>
                            <?php
                                $query_cond = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi = '$arrcond[0]' OR id_kondisi = '$arrcond[2]'");
                                while($data_cond = mysqli_fetch_assoc($query_cond)) { ?>
                                <option value="<?= $data_cond['kondisi_name']; ?>"><?= $data_cond['kondisi_name'];?></option>
                            <?php 
                                }
                            ?>
                            </select>
                        </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-12 mb-2">
                            <label>Keterangan</label>
                            <textarea class="form-control" rows="3" type="text" name="ket-cek" placeholder="Input Keterangan Seperti Lokasi Penempatan Asal, Hasil Pengecekan dll (panjang huruf tidak boleh lebih dari 72 karakter)" required></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="form-actions right">
                        <button type="button" class="btn btn-warning mr-1" onclick="ResetForm()">
                          <i class="ft-x"></i> Reset
                        </button>
                        <button type="submit" name="insertdata" class="btn btn-primary">
                          <i class="la la-check-square-o"></i> Save
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
    function ResetForm() {
        var element = document.getElementById("form_cekbarang");
        element.reset()
    }
</script>