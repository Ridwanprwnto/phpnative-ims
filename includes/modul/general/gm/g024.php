<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["prosesdatamusnah"])){
    if(ProsesBarangMusnah($_POST) > 0 ){
        $alert = array("Success!", "P3AT Berhasil Diproses", "success", "$encpid");
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
                    <h4 class="card-title">Proses Permohonan Pemusnahan Aktiva Tetap</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <button type="button" class="btn btn-success square btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#proses-musnah">Proses P3AT</button>
                        <form method="post" id="insert_formp3at">
                            <div class="table-responsive">
                                <table class="table text-center" id="table_brgp3at">
                                    <thead>
                                        <tr>
                                            <th>PLU - NAMA BARANG</th>
                                            <th>NOMOR AKTIVA</th>
                                            <th>SERIAL NUMBER</th>
                                            <th>MERK BARANG</th>
                                            <th>TIPE BARANG</th>
                                            <th>TAHUN PEROLEHAN</th>
                                            <th>NILAI AKTIVA</th>
                                            <th><button type="button" name="add_barangp3at" class="btn btn-success btn-xs add_barangp3at"><i class="ft-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal fade text-left" id="proses-musnah">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white">Proccess Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-musnah" value="<?= $encpid; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-musnah" value="<?= $arrmodifref[7]; ?>" class="form-control" readonly>
                                                <input type="hidden" value="<?= $usernik;?>" name="user-musnah" class="form-control" readonly>
                                                <input type="hidden" value="<?= $arrsp3at[0];?>" name="status-musnah" class="form-control" readonly>
                                                <input type="hidden" name="sign-musnah" id="sign-musnah" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>NO P3AT : </label>
                                                    <input type="text" value="<?= autonum(5, 'id_p3at', 'p3at');?>" name="no-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Office : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="office-musnah" id="office-musnah" required>
                                                        <option selected disabled>Please Select</option>
                                                        <?php
                                                        $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$idoffice'");
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
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="dept-musnah" id="dept-musnah" required>
                                                        <option selected disabled>Please Select</option>
                                                        <?php
                                                        $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$iddept'");
                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                        ?>
                                                        <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Deputy Manager : </label>
                                                    <input type="text" name="deputy-musnah" id="deputy-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Department Manager : </label>
                                                    <input type="text" name="head-musnah" id="head-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Head VUM Manager : </label>
                                                    <input type="text" name="vum-musnah" id="vum-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Head Department Manager : </label>
                                                    <input type="text" name="area-musnah" id="area-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Region Manager : </label>
                                                    <input type="text" name="reg-musnah" id="reg-musnah" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" name="ket-musnah" placeholder="Input keterangan / judul melakukan proses P3AT atas barang apa" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="prosesdatamusnah" class="btn btn-outline-success">Proses</button>
                                        </div>
                                    </div>
                                </div>
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

    $(document).on('click', '.add_barangp3at', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="kode_barang[]" class="select2 form-control block kode_barang" style="width: 100%" data-kode_barang_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_box($idoffice.$iddept."05"); ?></select></td>';
        html += '<td><select type="text" name="no_aktiva[]" class="select2 form-control block no_aktiva" style="width: 100%" data-no_aktiva_id="'+count+'" id="no_aktiva_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="sn_barang[]" class="select2 form-control block sn_barang" style="width: 100%" data-sn_barang_id="'+count+'" id="sn_barang_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><input type="text" name="merk_barang[]" class="form-control merk_barang" id="merk_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="tipe_barang[]" class="form-control tipe_barang" id="tipe_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="tahun_barang[]" class="form-control tahun_barang" placeholder="Tahun Perolehan" required/></td>';
        html += '<td><input type="text" name="nilai_barang[]" class="form-control nilai_barang" placeholder="Nilai Aktiva" required/></td>';
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

$(document).ready(function () {
    $("select[name=office-musnah],select[name=dept-musnah]").on('change', function () {
        var offmusnah = $('#office-musnah').val();
        var depmusnah = $('#dept-musnah').val();
        if (offmusnah && depmusnah) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {
                    OFFICEmusnah: offmusnah,
                    DEPTmusnah: depmusnah
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#sign-musnah').val((data[0].id_sign));
                        $('#deputy-musnah').val((data[0].initial_deputy_sign));
                        $('#head-musnah').val((data[0].initial_dept_sign));
                        $('#vum-musnah').val((data[0].initial_vum_sign));
                        $('#area-musnah').val((data[0].initial_head_sign));
                        $('#reg-musnah').val((data[0].initial_reg_sign));
                    } else {
                        $('#sign-musnah').val('');
                        $('#deputy-musnah').val('');
                        $('#head-musnah').val('');
                        $('#area-musnah').val('');
                        $('#reg-musnah').val('');
                    }
                }
            });
        } else {
            $('#sign-musnah').val('');
            $('#deputy-musnah').val('');
            $('#head-musnah').val('');
            $('#area-musnah').val('');
            $('#reg-musnah').val('');
        }
    });
});

$(document).ready(function(){
    <?php
        if (isset($alert)) {
    ?>
        swal({
		    title: "<?= $alert[0]; ?>",
		    text: "<?= $alert[1]; ?>",
		    icon: "<?= $alert[2]; ?>",
		    buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		})
		.then((isConfirm) => {
		    if (isConfirm) {
                window.location.href = "<?= $alert[3]; ?>";
		    } else {
                window.location.href = "<?= $alert[3]; ?>";
		    }
		});
    <?php
        }
    ?>
});
</script>