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

if(isset($_POST["prosesdatatablok"])){
    if(ProsesBarangTablok($_POST)){
        $alert = array("Success!", "Data item yang dibuat berhasil diajukan penablokan", "success", "$redirect");
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
                    <h4 class="card-title">Form Pengajuan Tablok Barang</h4>
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
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="entry-tablok" data-toggle="tab" href="#entrytablok" aria-expanded="true">Entry Pengajuan Tablok</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list-tablok" data-toggle="tab" href="#listtablok" aria-expanded="false">Daftar Pengajuan Tablok</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="entrytablok" aria-expanded="true" aria-labelledby="entry-tablok">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="modal fade text-left" id="proses-tablok">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-success white">
                                                                <h4 class="modal-title white">Proses Pengajuan Tablok</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <input type="hidden" name="office-tablok" value="<?= $idoffice; ?>">
                                                                        <input type="hidden" name="dept-tablok" value="<?= $iddept; ?>">
                                                                        <label>User Yang Mengajukan : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="user-tablok" required>
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
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Keterangan : </label>
                                                                        <textarea class="form-control" type="text" name="ket-tablok" placeholder="Input Keterangan (Optional!)"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="prosesdatatablok" class="btn btn-outline-success">Proses</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <form action="" method="post">
                                        <table class="table text-center" id="list_tablok_plano">
                                            <thead>
                                                <tr>
                                                    <th>PLU/KODE ITEM</th>
                                                    <th>DESKRIPSI BARANG</th>
                                                    <th>TIPE ITEM</th>
                                                    <th colspan="4">LINE + RAK + SHELF + CELL</th>
                                                    <th>KEL CTN</th>
                                                    <th>TYPE RAK</th>
                                                    <th>ZONA</th>
                                                    <th>STATION</th>
                                                    <th>IP DPD</th>
                                                    <th>ID DPD</th>
                                                    <th><button type="button" name="add_tablok_rows" class="btn btn-success btn-xs add_tablok_rows"><i class="ft-plus"></i></button></th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-item-tablok">
                                            </tbody>
                                        </table>
                                    </form>
                                    <button type="button" class="btn btn-success btn-min-width pull-right mb-1" data-toggle="modal" data-target="#proses-tablok">Proses</button>
                                </div>
                            </div>
                            <div class="tab-pane" id="listtablok" aria-labelledby="list-tablok">
                            </div>
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
    $(document).ready(function(){
    
    var count = 0;

    $(document).on('click', '.add_tablok_rows', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><input type="text" name="entry_tablok_plu[]" class="form-control entry_tablok_plu" placeholder="Entry PLU" required/></td>';
        html += '<td><input type="text" name="entry_tablok_desc[]" class="form-control entry_tablok_desc" placeholder="Deskripsi Barang" required/></td>';
        html += '<td><select type="text" name="entry_tablok_tipeitem[]" class="select2 form-control block entry_tablok_tipeitem" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="F">FOOD</option><option value="NF">NON FOOD</option></select></td>';
        html += '<td><input type="text" name="entry_tablok_line[]" class="form-control entry_tablok_line" required/></td>';
        html += '<td><input type="text" name="entry_tablok_rak[]" class="form-control entry_tablok_rak" required/></td>';
        html += '<td><input type="text" name="entry_tablok_shelf[]" class="form-control entry_tablok_shelf" required/></td>';
        html += '<td><input type="text" name="entry_tablok_cell[]" class="form-control entry_tablok_cell" required/></td>';
        html += '<td><input type="number" name="entry_tablok_karton[]" class="form-control entry_tablok_karton" required/></td>';
        html += '<td><select type="text" name="entry_tablok_tiperak[]" class="select2 form-control block entry_tablok_tiperak" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="FRACTION">FRACTION</option><option value="BULKY">BULKY</option><option value="BULKY FRACTION">BULKY FRACTION</option></select></td>';
        html += '<td><select type="text" name="entry_tablok_zona[]" class="select2 form-control block entry_tablok_zona" style="width: 100%" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="entry_tablok_station[]" class="select2 form-control block entry_tablok_station" style="width: 100%" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="entry_tablok_ip[]" class="select2 form-control block entry_tablok_ip" style="width: 100%" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="entry_tablok_id[]" class="select2 form-control block entry_tablok_id" style="width: 100%" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><button type="button" name="remove_tablok_rows" class="btn btn-danger btn-xs remove_tablok_rows"><i class="ft-minus"></i></button></td>';
        $('#table-item-tablok').append(html);
        $(".select2").select2();
    });
    $(document).on('click', '.remove_tablok_rows', function(){
        $(this).closest('tr').remove();
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>