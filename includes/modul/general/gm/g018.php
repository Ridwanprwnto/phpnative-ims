<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["prosesdataservice"])){
    if(ProsesBarangService($_POST) > 0 ){
        $alert = array("Success!", "Pengajuan PBRP Berhasil Diproses", "success", "$encpid");
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
                    <h4 class="card-title">Form Pengajuan Perbaikan Barang / Rekomendasi Pemusnahan</h4>
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
                        <button type="button" class="btn btn-success square btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#proses-service">Proses</button>
                        <form action="" method="post">
                        <div class="modal fade text-left" id="proses-service">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success white">
                                        <h4 class="modal-title white">Tujuan PBRP</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <input type="hidden" name="page-service" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-service" value="<?= $arrmodifref[5]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-service" value="<?= $usernik;?>" class="form-control">
                                                <input type="hidden" name="ofdep-from-service" value="<?= $idoffice.$iddept;?>" class="form-control">
                                                <label>Office : </label>
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="office-to-service" required>
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
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="dept-to-service" required>
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
                                                <label>Keperluan : </label>
                                                <select class="select2 form-control block" style="width: 100%" type="text" name="keperluan-service" required>
                                                    <option value="" selected disabled>Please Select</option>
                                                    <option value="PS">Pengajuan Service / Perbaikan</option>
                                                    <option value="PM">Pengajuan Rekomendasi Pemusnahan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Keterangan :</label>
                                                <textarea class="form-control" type="text" name="ket-to-service" placeholder="Jelaskan keterangan dari semua barang tersebut"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="prosesdataservice" class="btn btn-outline-success">Proses</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <div class="table-responsive">
                            <table class="table text-center" id="table_brgpbrp">
                                <thead>
                                    <tr>
                                        <th>PLU - NAMA BARANG</th>
                                        <th>NOMOR AKTIVA</th>
                                        <th>SERIAL NUMBER</th>
                                        <th>MERK BARANG</th>
                                        <th>TIPE BARANG</th>
                                        <th>KETERANGAN</th>
                                        <th><button type="button" name="add_barangpbrp" class="btn btn-success btn-xs add_barangpbrp"><i class="ft-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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

    $(document).on('click', '.add_barangpbrp', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="kode_barang[]" class="select2 form-control block kode_barang" style="width: 100%" data-kode_barang_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_box($idoffice.$iddept."03"); ?></select></td>';
        html += '<td><select type="text" name="no_aktiva[]" class="select2 form-control block no_aktiva" style="width: 100%" data-no_aktiva_id="'+count+'" id="no_aktiva_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="sn_barang[]" class="select2 form-control block sn_barang" style="width: 100%" data-sn_barang_id="'+count+'" id="sn_barang_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><input type="text" name="merk_barang[]" class="form-control merk_barang" id="merk_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="tipe_barang[]" class="form-control tipe_barang" id="tipe_barang_id'+count+'" readonly/></td>';
        html += '<td><textarea class="form-control ket_barang" type="text" name="ket_barang[]" placeholder="Input keterangan atau kerusakan barang" required></textarea></td>';
        html += '<td><button type="button" name="remove_brg" class="btn btn-danger btn-xs remove_brg"><i class="ft-minus"></i></button></td>';
        $('tbody').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_brg', function(){
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.kode_barang', function(){
        var kode_barang = $(this).val();
        var kode_barang_id = $(this).data('kode_barang_id');
        if (kode_barang) {
            $.ajax({
            url:"action/datarequest.php",
            method:"POST",
            data:{PLUmusnah:kode_barang},
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
            data:{ATmusnah:at_barang},
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
                data: {
                        SNmusnah: sn_barang
                },
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