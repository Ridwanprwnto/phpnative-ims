<?php
    $idoffice = $_SESSION["office"];
    $iddept = $_SESSION["department"];
    $usernik = $_SESSION["user_nik"];
    $username = $_SESSION["user_name"];

    $page_id = $_GET['page'];

    $dec_page = decrypt(rplplus($page_id));

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["postingdata"])){
        if(PostingKehadiran($_POST) > 0 ){
            $alert = array("Success!", "Data absensi berhasil di posting", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["editdataposting"])){
        if(EditKehadiran($_POST)){
            $datapost1 = implode(", ", $_POST["edit_user_hadir"]);
            $datapost2 = implode(", ", $_POST["edit_tgl_hadir"]);
            $alert = array("Success!", "Data absensi NIK ".$datapost1." Tgl ".$datapost2." berhasil pengajuan edit, hubungi atasan untuk persetujuan perubahan data", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedataposting"])){
        if(DeleteKehadiran($_POST)){
            $datapost1 = implode(", ", $_POST["edit_user_hadir"]);
            $datapost2 = implode(", ", $_POST["edit_tgl_hadir"]);
            $alert = array("Success!", "Data absensi NIK ".$datapost1." Tgl ".$datapost2." berhasil pengajuan hapus, hubungi atasan untuk persetujuan perubahan data", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Entry Data Absensi</h4>
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
                        <?php
                        $data_sheet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT link_sheet, linkid_sheet FROM sheet"));
                        ?>
                        <a href="<?= $data_sheet["link_sheet"].$data_sheet["linkid_sheet"]; ?>" target="_blank" class="btn btn-info mr-1 mb-1">Link Google Sheet Presensi</a>
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="entry-kehadiran" data-toggle="tab" href="#entrykehadiran" aria-expanded="true">Input Absensi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="update-kehadiran" data-toggle="tab" href="#updatekehadiran" aria-expanded="false">Update Absensi</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="entrykehadiran" aria-expanded="true" aria-labelledby="entry-kehadiran">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <form action="" method="post">
                                            <!-- Modal -->
                                            <div class="modal fade text-left" id="proses-kehadiran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success white">
                                                            <h4 class="modal-title white">Posting Confirmation</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="page-posting" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="trans-posting" value="<?= $arrmodifref[10]; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="office-posting" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept-posting" value="<?= $iddept; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="user-posting" value="<?= $usernik." - ".strtoupper($username); ?>" class="form-control" readonly>
                                                            <label>Pastikan data kehadiran yang anda input sudah sesuai!</label>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">No</button>
                                                        <button type="submit" name="postingdata" class="btn btn-outline-success">Yes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                            <div class="table-responsive">
                                                <table class="table text-center" id="table_user_hadir">
                                                    <thead>
                                                        <tr>
                                                            <th>NIK - USERNAME</th>
                                                            <th>DIVISI / BAGIAN</th>
                                                            <th>TANGGAL</th>
                                                            <th>ALASAN PERUBAHAN ABSENSI</th>
                                                            <th>KETERANGAN</th>
                                                            <th><button type="button" name="add_users" class="btn btn-success btn-xs add_users"><i class="ft-plus"></i></button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-inputkehadiran">
                                                    </tbody>
                                                </table>
                                            </div>
                                            </form>
                                            <button type="button" class="btn btn-success btn-min-width pull-right mb-1" data-toggle="modal" data-target="#proses-kehadiran">Posting</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="updatekehadiran" aria-labelledby="update-kehadiran">
                                <div class="row mt-2">
                                    <input type="hidden" name="office-src" id="office-src" value="<?= $idoffice; ?>" class="form-control" readonly>
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
                                        <label>Tanggal</label>
                                        <input type="date" name="tgl-src" id="tgl-src" max="<?= date("Y-m-d"); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 mb-0">
                                        <form method="post" action="">
                                            <div class="table-responsive">
                                                <table class="table text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>NIK - USERNAME</th>
                                                            <th>DIVISI / BAGIAN</th>
                                                            <th>TANGGAL</th>
                                                            <th>ALASAN PERUBAHAN ABSENSI</th>
                                                            <th>KETERANGAN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-editkehadiran">
                                                    </tbody>        
                                                    <!-- Modal Edit -->
                                                    <div class="modal fade text-left" id="editdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success white">
                                                                    <h4 class="modal-title white" id="myModalLabel1">Edit Confirmation</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <input class="form-control" type="hidden" name="page-edit" value="<?= $encpid; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="trans-posting" value="<?= $arrmodifref[10]; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="office-edit" value="<?= $idoffice; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="user-edit" value="<?= $usernik." - ".strtoupper($username); ?>" readonly>
                                                                        <div class="col-md-12 mb-2">
                                                                            <label>Keterangan :</label>
                                                                            <textarea class="form-control" name= "ket-edit" type="text" placeholder="Keterangan alasan rubah jadwal kehadiran"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="editdataposting" class="btn btn-outline-success">Yes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->
                                                    <!-- Modal Delete -->
                                                    <div class="modal fade text-left" id="deletedata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger white">
                                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <input class="form-control" type="hidden" name="page-delete" value="<?= $encpid; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="trans-posting" value="<?= $arrmodifref[10]; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="office-delete" value="<?= $idoffice; ?>" readonly>
                                                                        <input class="form-control" type="hidden" name="user-delete" value="<?= $usernik." - ".strtoupper($username); ?>" readonly>
                                                                        <div class="col-md-12 mb-2">
                                                                            <label>Keterangan :</label>
                                                                            <textarea class="form-control" name= "ket-delete" type="text" placeholder="Keterangan alasan hapus jadwal kehadiran"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="deletedataposting" class="btn btn-outline-danger">Yes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->
                                                </table>
                                            </div>
                                        </form>
                                        <button type="submit" class="btn btn-success mb-2 pull-right" data-toggle="modal" data-target="#editdata">Update Data</button>
                                        <button type="submit" class="btn btn-danger mr-1 mb-2 pull-right" data-toggle="modal" data-target="#deletedata">Delete Data</button>
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
    
    var count = 0;

    $(document).on('click', '.add_users', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="user_hadir[]" class="select2 form-control block user_hadir" style="width: 100%" data-user_hadir_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_users($idoffice.$iddept); ?></select></td>';
        html += '<td><input type="text" name="bagian_hadir[]" class="form-control bagian_hadir" id="bagian_hadir_id'+count+'" readonly/></td>';
        html += '<td><input type="date" name="tgl_hadir[]" value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>" class="form-control tgl_hadir" required/></td>';
        html += '<td><select type="text" name="cek_hadir[]" data-cek_hadir_id="'+count+'" class="select2 form-control block cek_hadir" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="Cuti">Cuti</option><option value="Cuti Mendadak">Cuti Mendadak</option><option value="Sakit">Sakit</option><option value="Alpa">Alpa</option><option value="Libur Pengganti">Libur Pengganti</option></select></td>';
        html += '<td><textarea class="form-control ket_hadir" type="text" name="ket_hadir[]" placeholder="Input keterangan (Optional)"></textarea></td>';
        html += '<td><button type="button" name="remove_users" class="btn btn-danger btn-xs remove_users"><i class="ft-minus"></i></button></td>';
        
        $('#table-inputkehadiran').append(html);

        $(".select2").select2();

        var input = document.getElementById("reqDate");
        var today = new Date();
        var day = today.getDate();

        // Set month to string to add leading 0
        var mon = new String(today.getMonth()+1); //January is 0!
        var yr = today.getFullYear();

        if(mon.length < 2) { mon = "0" + mon; }
        if(day.length < 2) { dayn = "0" + day; }

        var date = new String( yr + '-' + mon + '-' + day );

        input.disabled = false; 
        input.setAttribute('max', date);

    });

    $(document).on('click', '.remove_users', function(){
        $(this).closest('tr').remove();
    });

});

$(document).ready(function(){    
    $(document).on('change', '.user_hadir', function(){
        var user_hadir = $(this).val();
        var user_hadir_id = $(this).data('user_hadir_id');
        if (user_hadir) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {
                        KEHADIRANUSERS: user_hadir
                },
                dataType: "JSON",
                success: function (data) {
                    if (data) {
                        $('#bagian_hadir_id'+user_hadir_id).val((data.divisi_name));
                    } else {
                        $('#bagian_hadir_id'+user_hadir_id).val('');
                    }
                }
            });
        }
    });

    // $(document).on('change', '.cek_hadir', function() {
    //     var cek_hadir = $(this).val();
    //     var cek_hadir_id = $(this).data('cek_hadir_id');
    //     if (cek_hadir == 'Rubah Shift') {
    //         $('#jam_hadir_id'+cek_hadir_id).val("");
    //         $('#jam_hadir_id'+cek_hadir_id).removeAttr('readonly');
    //         $('#jam_hadir_id'+cek_hadir_id).prop('required', cek_hadir);
    //         document.getElementById('jam_hadir_id'+cek_hadir_id).type = "time";
    //     }
    //     else if (cek_hadir == 'Tukar Off') {
    //         $('#jam_hadir_id'+cek_hadir_id).val("");
    //         $('#jam_hadir_id'+cek_hadir_id).removeAttr('readonly');
    //         $('#jam_hadir_id'+cek_hadir_id).prop('required', cek_hadir);
    //         document.getElementById('jam_hadir_id'+cek_hadir_id).type = "date";
    //     }
    //     else {
    //         $('#jam_hadir_id'+cek_hadir_id).val("");
    //         $('#jam_hadir_id'+cek_hadir_id).prop('readonly', cek_hadir);
    //         document.getElementById('jam_hadir_id'+cek_hadir_id).type = "text";
    //     }
    // });
});

// $(document).ready(function(){    
//     var input = document.getElementById("reqDate");
//     var today = new Date();
//     var day = today.getDate();

//     // Set month to string to add leading 0
//     var mon = new String(today.getMonth()+1); //January is 0!
//     var yr = today.getFullYear();

//     if(mon.length < 2) { mon = "0" + mon; }
//     if(day.length < 2) { dayn = "0" + day; }

//     var date = new String( yr + '-' + mon + '-' + day );

//     input.disabled = false; 
//     input.setAttribute('max', date);
// });

$(document).ready(function(){
    function load_data(office_presensi, nik_presensi, tgl_presensi) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: { OFFPRESENSI: office_presensi, NIKPRESENSI: nik_presensi, TGLPRESENSI: tgl_presensi },
            beforeSend: function() {
                // $("#loadpresensi-spinner").show();
                hideSpinner();
                showSpinner();
            },
            // complete: function() {
            //     // $('#loadpresensi-spinner').hide();
            //     hideSpinner();
            // },
            success: function(hasil) {
                $('.table-editkehadiran').html(hasil);
            },
            error: function(hasil) {
                $('.table-editkehadiran').html(hasil);
            }
        });
    }
    $('#nik-src').change(function(){
        var office_presensi = $("#office-src").val();
        var nik_presensi = $("#nik-src").val();
        var tgl_presensi = $("#tgl-src").val();
        load_data(office_presensi, nik_presensi, tgl_presensi);
    });
    $('#tgl-src').change(function(){
        var office_presensi = $("#office-src").val();
        var nik_presensi = $("#nik-src").val();
        var tgl_presensi = $("#tgl-src").val();
        load_data(office_presensi, nik_presensi, tgl_presensi);
    });
    function hideSpinner() {
        $('.table-editkehadiran').html("");
        if($(document).find('#loadpresensi-spinner').length > 0) {
            $(document).find('#loadpresensi-spinner').remove();
        }
    }
    function showSpinner() {
        $('.table-editkehadiran').append('<tr><td colspan="6"><i id="loadpresensi-spinner" class="la la-spinner spinner"></i></td></tr>');
    }
});

// $(document).ready(function(){
//     $(document).on('change', '#edit_cek_hadir', function() {
//         var cek_hadir = $(this).val();
//         // $('#edit_jam_hadir').val("");
//         // $('#edit_jam_hadir').prop('readonly', cek_hadir);

//         if (cek_hadir == 'RUBAH SHIFT') {
//             $('#edit_jam_hadir').val("");
//             $('#edit_jam_hadir').removeAttr('readonly');
//             $('#edit_jam_hadir').prop('required', cek_hadir);
//             document.getElementById('edit_jam_hadir').type = "time";
//         }
//         else if (cek_hadir == 'TUKAR OFF') {
//             $('#edit_jam_hadir').val("");
//             $('#edit_jam_hadir').removeAttr('readonly');
//             $('#edit_jam_hadir').prop('required', cek_hadir);
//             document.getElementById('edit_jam_hadir').type = "date";
//         }
//         else {
//             $('#edit_jam_hadir').val("");
//             $('#edit_jam_hadir').removeAttr('required');
//             $('#edit_jam_hadir').prop('readonly', cek_hadir);
//             document.getElementById('edit_jam_hadir').type = "text";
//         }
//     });
// });

</script>

<?php
    include ("includes/templates/alert.php");
?>