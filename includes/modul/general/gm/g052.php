<?php
    $idoffice = $_SESSION["office"];
    $iddept = $_SESSION["department"];
    $usernik = $_SESSION["user_nik"];
    $username = $_SESSION["user_name"];

    $page_id = $_GET['page'];

    $dec_page = decrypt(rplplus($page_id));
    $encpid = encrypt($dec_page);

    $redirect = "index.php?page=".$encpid;

    if(isset($_POST["inputjadwalcheckdata"])){
        if(InputPerubahanJadwal($_POST) > 0 ){
            $alert = array("Success!", "Pengajuan perubahan jadwal berhasil di kirim, hubungi atasan untuk persetujuan perubahan data", "success", "$redirect");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletejadwalcheckdata"])){
        if(DeleteKehadiran($_POST)){
            $alert = array("Success!", "Pengajuan perubahan hapus jadwal berhasil di kirim, hubungi atasan untuk persetujuan perubahan data", "success", "$redirect");
       }
        else {
            echo mysqli_error($conn);
        }
    }
?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Entry Data Jadwal</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="update-jadwal" data-toggle="tab" href="#updatejadwal" aria-expanded="true">Input Perubahan Jadwal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="update-kehadiran" data-toggle="tab" href="#updatekehadiran" aria-expanded="false">Update Perubahan Jadwal</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="updatejadwal" aria-expanded="true" aria-labelledby="update-jadwal">
                                <div class="row mt-2">
                                    <input type="hidden" name="offdep-src" id="offdep-src" value="<?= $idoffice.$iddept; ?>" class="form-control" readonly>
                                    <div class="form-group col-md-12 mb-2">
                                        <label>NIK - Username</label>
                                        <select id="nik-src" name="nik-src" class="select2 form-control block" style="width: 100%" type="text" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_user_p = mysqli_query($conn, "SELECT nik, username, full_name FROM users WHERE id_office = '$idoffice' AND id_department = '$iddept' AND id_group NOT LIKE 'GP01' AND full_name IS NOT NULL ORDER BY nik ASC");
                                                while($data_user_p = mysqli_fetch_assoc($query_user_p)) { ?>
                                                <option value="<?= $data_user_p['nik']; ?>" ><?= $data_user_p['nik']." - ".strtoupper($data_user_p['full_name']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 mb-2">
                                        <label>Bulan</label>
                                        <input type="month" name="month-src" id="month-src" max="<?= date("Y-m"); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 mb-0">
                                        <form method="post" action="">
                                        <div class="table-responsive">
                                            <table class="table table-hover text-center" id="table_user_hadir">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>DAYS</th>
                                                        <th>TANGGAL</th>
                                                        <th>JADWAL</th>
                                                        <th>CHECKLIST</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-inputjadwal">
                                                </tbody>
                                            </table>
                                        </div>
                                        </form>
                                        <button type="submit" class="btn btn-success mb-2 pull-right" onclick="return validateFormJadwal();">Pilih Jadwal</button>
                                        <!-- Modal Update By Check -->
                                        <div class="modal fade text-left" id="inputJadwalCheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Daftar Data Pemilihan Perubahan Jadwal</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" name="page-posting" value="<?= $redirect; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="trans-posting" value="<?= $arrmodifref[10]; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="office-posting" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="dept-posting" value="<?= $iddept; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="user-posting" value="<?= $usernik." - ".strtoupper($username); ?>" class="form-control" readonly>
                                                        <div class="form-row" id="table-inputjadwal-check">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="inputjadwalcheckdata" class="btn btn-outline-success">Posting</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="updatekehadiran" aria-labelledby="update-kehadiran">
                                <div class="row mt-2">
                                    <input type="hidden" name="offdep-src-edit" id="offdep-src-edit" value="<?= $idoffice.$iddept; ?>" class="form-control" readonly>
                                    <div class="form-group col-md-12 mb-2">
                                        <label>NIK - Username</label>
                                        <select id="edit-nik-src" name="edit-nik-src" class="select2 form-control block" style="width: 100%" type="text" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_user_edit = mysqli_query($conn, "SELECT nik, username, full_name FROM users WHERE id_office = '$idoffice' AND id_department = '$iddept' AND id_group NOT LIKE 'GP01' AND full_name IS NOT NULL ORDER BY nik ASC");
                                                while($data_user_edit = mysqli_fetch_assoc($query_user_edit)) { ?>
                                                <option value="<?= $data_user_edit['nik']; ?>" ><?= $data_user_edit['nik']." - ".strtoupper($data_user_edit['full_name']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 mb-2">
                                        <label>Tanggal</label>
                                        <input type="date" name="tgl-src-edit" id="tgl-src-edit" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 mb-0">
                                        <form method="post" action="">
                                        <div class="table-responsive">
                                            <table class="table text-center" id="table_user_hadir">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>TANGGAL</th>
                                                        <th>PERUBAHAN JADWAL</th>
                                                        <th>KETERANGAN</th>
                                                        <th>CHEKLIST</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-rubahjadwal">
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Modal Delete By Check -->
                                        <div class="modal fade text-left" id="deleteJadwalCheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger white">
                                                        <h4 class="modal-title white">Konfirmasi Hapus Penginputan Jadwal</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="page-delete" value="<?= $encpid; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="trans-posting" value="<?= $arrmodifref[10]; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="office-delete" value="<?= $idoffice; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="dept-edit" value="<?= $iddept; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="user-delete" value="<?= $usernik." - ".strtoupper($username); ?>" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" name= "ket-delete" type="text" placeholder="Keterangan alasan pengajuan hapus perubahan jadwal"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="deletejadwalcheckdata" class="btn btn-outline-danger">Posting</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                        </form>
                                        <button type="submit" class="btn btn-danger mb-2 pull-right" onclick="return validateFormEditJadwal();">Delete Jadwal</button>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function(){
    function load_data(offdep_presensi, nik_presensi, bulan_presensi) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: { OFFDEPABSENSI: offdep_presensi, NIKABSENSI: nik_presensi, BULANABSENSI: bulan_presensi },
            beforeSend: function() {
                hideSpinner();
                showSpinner();
            },
            success: function(hasil) {
                $('.table-inputjadwal').html(hasil);
            },
            complete: function() {
                $('.icheck1 input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });
            },
            error: function(hasil) {
                $('.table-inputjadwal').html(hasil);
            }
        });
    }
    $('#nik-src').change(function(){
        var offdep_presensi = $("#offdep-src").val();
        var nik_presensi = $("#nik-src").val();
        var bulan_presensi = $("#month-src").val();
        load_data(offdep_presensi, nik_presensi, bulan_presensi);
    });
    $('#month-src').change(function(){
        var offdep_presensi = $("#offdep-src").val();
        var nik_presensi = $("#nik-src").val();
        var bulan_presensi = $("#month-src").val();
        load_data(offdep_presensi, nik_presensi, bulan_presensi);
    });
    function hideSpinner() {
        $('.table-inputjadwal').html("");
        if($(document).find('#loadpresensi-spinner').length > 0) {
            $(document).find('#loadpresensi-spinner').remove();
        }
    }
    function showSpinner() {
        $('.table-inputjadwal').append('<tr><td colspan="6"><i id="loadpresensi-spinner" class="la la-spinner spinner"></i></td></tr>');
    }
});

function validateFormJadwal() {
    var count_checked = $('input[name="checkjadwalinput[]"]:checked');
    if (count_checked.length == 0) {
        alert("Jadwal belum ada yang dicheklist!");
        return false;
    }
    else {
        var array = []
        for (var i = 0; i < count_checked.length; i++) {
            array.push(count_checked[i].value)
        }
        $.ajax({
            type:'POST',
            url:'action/datarequest.php',
            data: {EDITJADWALCHECKBOX:array},
            success:function(data){
                $('#table-inputjadwal-check').html(data);
                $('#inputJadwalCheck').modal('show');
            },
            complete: function() {
                $(".select2").select2();
            }
        });
    }
}

$(document).ready(function(){
    function load_data_edit(offdep_editpresensi, nik_editpresensi, tgl_editpresensi) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: { OFFDEPEDITABSENSI: offdep_editpresensi, NIKEDITABSENSI: nik_editpresensi, TANGGALEDITABSENSI: tgl_editpresensi },
            beforeSend: function() {
                hideSpinneredit();
                showSpinneredit();
            },
            success: function(hasil) {
                $('.table-rubahjadwal').html(hasil);
            },
            complete: function() {
                $('.icheck1 input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });
                $(".select2").select2();
            },
            error: function(hasil) {
                $('.table-rubahjadwal').html(hasil);
            }
        });
    }
    $('#edit-nik-src').change(function(){
        var offdep_editpresensi = $("#offdep-src-edit").val();
        var nik_editpresensi = $("#edit-nik-src").val();
        var tgl_editpresensi = $("#tgl-src-edit").val();
        load_data_edit(offdep_editpresensi, nik_editpresensi, tgl_editpresensi);
    });
    $('#tgl-src-edit').change(function(){
        var offdep_editpresensi = $("#offdep-src-edit").val();
        var nik_editpresensi = $("#edit-nik-src").val();
        var tgl_editpresensi = $("#tgl-src-edit").val();
        load_data_edit(offdep_editpresensi, nik_editpresensi, tgl_editpresensi);
    });
    function hideSpinneredit() {
        $('.table-rubahjadwal').html("");
        if($(document).find('#loadpresensi-spinner').length > 0) {
            $(document).find('#loadpresensi-spinner').remove();
        }
    }
    function showSpinneredit() {
        $('.table-rubahjadwal').append('<tr><td colspan="6"><i id="loadpresensi-spinner" class="la la-spinner spinner"></i></td></tr>');
    }
});

function validateFormEditJadwal() {
    var count_checked = $('input[name="edit_id_hadir[]"]:checked');
    if (count_checked.length == 0) {
        alert("Perubahan jadwal belum ada yang dicheklist!");
        return false;
    }
    else {
        $('#deleteJadwalCheck').modal('show');
    }
}
</script>

<?php
    include ("includes/templates/alert.php");
?>