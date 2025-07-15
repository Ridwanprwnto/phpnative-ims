<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["prosesdatamutasi"])) {
    if(ProsesBarangMutasi($_POST) > 0 ){
        $alert = array("Success!", "Mutasi DAT Barang Berhasil Diproses", "success", "$encpid");
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
                    <h4 class="card-title">Mutasi Barang Data Aktiva Tetap</h4>
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
                        <form action="" method="post">
                        <div class="table-responsive">
                            <table class="table text-center" id="table_mutasibarang">
                                <thead>
                                    <tr>
                                        <th>PLU - NAMA BARANG</th>
                                        <th>NOMOR AKTIVA</th>
                                        <th>SERIAL NUMBER</th>
                                        <th>MERK BARANG</th>
                                        <th>TIPE BARANG</th>
                                        <th><button type="button" name="add_mutasibarang" class="btn btn-success btn-xs add_mutasibarang"><i class="ft-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Proses -->
                        <div class="modal fade text-left" id="proses-mutasi">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success white">
                                        <h4 class="modal-title white">Tujuan Mutasi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <input type="hidden" name="page-mutasi" value="<?= $encpid;?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-mutasi" value="<?= $arrmodifref[8]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-mutasi" value="<?= $usernik;?>" class="form-control" readonly>
                                                <input type="hidden" name="ofdep-from-mutasi" value="<?= $idoffice.$iddept;?>" class="form-control" readonly>
                                                <input type="hidden" name="kondisi-mutasi" value="<?= $arrcond[7];?>" class="form-control" readonly>
                                                <label>Office : </label>
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="office-to-mutasi" required>
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
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="dept-to-mutasi" required>
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
                                                <textarea class="form-control" name="ket-mutasi" type="text" placeholder="Input keterangan (Optional)"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="prosesdatamutasi" class="btn btn-outline-success">Proses</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        </form>
                        <button type="button" class="btn btn-success btn-min-width pull-right mb-2" data-toggle="modal" data-target="#proses-mutasi">Proses Mutasi</button>
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

    $(document).on('click', '.add_mutasibarang', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="kode_barang[]" class="select2 form-control block kode_barang" style="width: 100%" data-kode_barang_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_dat($idoffice.$iddept.$arrcond[5]); ?></select></td>';
        html += '<td><select type="text" name="no_aktiva[]" class="select2 form-control block no_aktiva" style="width: 100%" data-no_aktiva_id="'+count+'" id="no_aktiva_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="sn_barang[]" class="select2 form-control block sn_barang" style="width: 100%" data-sn_barang_id="'+count+'" id="sn_barang_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><input type="text" name="merk_barang[]" class="form-control merk_barang" id="merk_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="tipe_barang[]" class="form-control tipe_barang" id="tipe_barang_id'+count+'" readonly/></td>';
        html += '<td><button type="button" name="remove_mutasibarang" class="btn btn-danger btn-xs remove_mutasibarang"><i class="ft-minus"></i></button></td>';
        $('tbody').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_mutasibarang', function(){
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.kode_barang', function(){
        var kode_barang = $(this).val();
        var kode_barang_id = $(this).data('kode_barang_id');
        if (kode_barang) {
            $.ajax({
            url:"action/datarequest.php",
            method:"POST",
            data:{BARANGMUTASI:kode_barang},
                success:function(data){
                    var html = '';
                    html += data;
                    $('#no_aktiva_id'+kode_barang_id).html(html);

                    var html2 = '<option value="" selected disabled>Please Select</option>';
                    $('#sn_barang_id'+kode_barang_id).html(html2);

                    $('#merk_barang_id'+kode_barang_id).val('');
                    $('#tipe_barang_id'+kode_barang_id).val('');
                }
            })
        } else {
            $('#no_aktiva_id'+kode_barang_id).val('');
        }
    });

    $(document).on('change', '.no_aktiva', function(){
        var at_barang = $(this).val();
        var at_barang_id = $(this).data('no_aktiva_id');
        if (at_barang) {
            $.ajax({
            url:"action/datarequest.php",
            method:"POST",
            data:{ATMUTASI:at_barang},
                success:function(data){
                    var html = '';
                    html += data;
                    $('#sn_barang_id'+at_barang_id).html(html);

                    $('#merk_barang_id'+at_barang_id).val('');
                    $('#tipe_barang_id'+at_barang_id).val('');
                }
            })
        } else {
            $('#sn_barang_id'+kode_barang_id).val('');
        }
    });

    $(document).on('change', '.sn_barang', function(){
        var sn_barang = $(this).val();
        var sn_barang_id = $(this).data('sn_barang_id');
        if (sn_barang) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: { SNMUTASI: sn_barang },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#merk_barang_id'+sn_barang_id).val((data[0].ba_merk));
                        $('#tipe_barang_id'+sn_barang_id).val((data[0].ba_tipe));
                    } else {
                        $('#merk_barang_id'+sn_barang_id).val('');
                        $('#tipe_barang_id'+sn_barang_id).val('');
                    }
                }
            });
        }
    });
});    

</script>

<?php
    include ("includes/templates/alert.php");
?>