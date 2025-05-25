<?php
    $idoffice = $_SESSION["office"];
    $iddept = $_SESSION["department"];
    $usernik = $_SESSION["user_nik"];
    $username = $_SESSION["user_name"];

    $page_id = $_GET['page'];

    $dec_page = decrypt(rplplus($page_id));

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["editdata"])){
        $datapost = isset($_POST["approve-idpresensi"]) ? $_POST["approve-idpresensi"] : NULL;
        if(ApproveEditKehadiran($_POST)){
            $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Approve!", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        $datapost = isset($_POST["delete-idpresensi"]) ? $_POST["delete-idpresensi"] : NULL;
        if(ApproveDeleteKehadiran($_POST)){
            $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Approve!", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["updatedata"])){
        $datapost = isset($_POST["update-idpresensi"]) ? $_POST["update-idpresensi"] : NULL;
        if(ApprovePerubahanJadwal($_POST)){
            $alert = array("Success!", "Pengajuan Perubahan Data Jadwal Docno ".$datapost." Berhasil di Approve!", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["rejectdata"])){
        $datapost = isset($_POST["reject-docnopresensi"]) ? $_POST["reject-docnopresensi"] : NULL;
        if(RejectDataKehadiran($_POST)){
            $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil di Reject!", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
?>

<!-- Auto Fill table -->
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Approval Data Kehadiran</h4>
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
                    <table class="table table-striped table-bordered zero-configuration row-grouping-presensi" id="table_approvepresensi">
                            <thead>
                                <tr>
                                    <th>DETAIL</th>
                                    <th>NO</th>
                                    <th>DOCNO</th>
                                    <th>TGL PENGAJUAN</th>
                                    <th>USER PENGAJUAN PERUBAHAN</th>
                                    <th>KETERANGAN</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql = "SELECT A.*, B.* FROM approval_presensi AS A
                            INNER JOIN data_presensi AS B ON A.no_aprv_presensi = B.no_data_presensi
                            WHERE A.office_aprv_presensi = '$idoffice' AND A.status_aprv_presensi = 'N' GROUP BY A.no_aprv_presensi ORDER BY RIGHT(A.no_aprv_presensi, 6) DESC";
                            $query = mysqli_query($conn, $sql);

                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td class="details-datapresensi" id="<?= $data['no_aprv_presensi']; ?>" onclick="changeIcon(this)">
                                        <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                    </td>
                                    <td><?= $no++; ?></td>
                                    <td><strong><?= $data['no_aprv_presensi']; ?></strong></td>
                                    <td><?= $data['date_aprv_presensi']; ?></td>
                                    <td><?= $data['user_aprv_presensi']; ?></td>
                                    <td><?= $data['ket_aprv_presensi']; ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['aksi_aprv_presensi'] == "E" ? "success" : ($data['aksi_aprv_presensi'] == "D" ? "danger" : "info"); ?> label-square">
                                            <i class="ft-info font-medium-2"></i>
                                            <span><strong><?= $data['aksi_aprv_presensi'] == "E" ? "EDIT ABSENSI" : ($data['aksi_aprv_presensi'] == "D" ? "DELETE ABSENSI" : "UPDATE JADWAL"); ?></strong></span>
                                        </div>
                                    </td>
                                    <td>
                                        <!-- Icon Button dropdowns -->
                                        <div class="btn-group mb-1">
                                            <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                            <div class="dropdown-menu">
                                            <a class="dropdown-item <?= $data['aksi_aprv_presensi'] == "E" ? "approve_editkehadiran" : ( $data['aksi_aprv_presensi'] === "D" ? "approve_deletekehadiran" : "approve_updatekehadiran"); ?>" href="#" title="Approve Perubahan <?= $data['aksi_aprv_presensi'] === "U" ? "Jadwal" : "Absensi"; ?> Docno <?= $data['no_aprv_presensi']; ?>" name="<?= $data['aksi_aprv_presensi'] == "E" ? "approve_editkehadiran" : ( $data['aksi_aprv_presensi'] === "D" ? "approve_deletekehadiran" : "approve_updatekehadiran"); ?>" id="<?= $data["no_aprv_presensi"]; ?>" data-toggle="tooltip" data-placement="bottom">Approve</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item approve_rejectkehadiran" href="#" title="Reject Perubahan <?= $data['aksi_aprv_presensi'] === "U" ? "Jadwal" : "Absensi"; ?> Docno <?= $data['no_aprv_presensi']; ?>" name="approve_rejectkehadiran" id="<?= $data["no_aprv_presensi"]; ?>" data-toggle="tooltip" data-placement="bottom">Reject</a>
                                            </div>
                                        </div>
                                        <!-- /btn-group -->
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Edit -->
                            <div class="modal fade text-left" id="ModalApproveEditPresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="approve-labelpresensi"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" name="approve-pagepresensi" value="<?= $encpid; ?>" readonly>
                                                <input type="hidden" class="form-control" name="approve-userpresensi" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" id="approve-idpresensi" name="approve-idpresensi" readonly>
                                                <input type="hidden" class="form-control" id="approve-nikpresensi" name="approve-nikpresensi" readonly>
                                                <input type="hidden" class="form-control" id="approve-tglpresensi" name="approve-tglpresensi" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>OTP Code :</label>
                                                    <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="approve-otppresensi" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-success" name="editdata">Approve</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="ModalApproveDeletePresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="delete-labelpresensi"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" name="delete-pagepresensi" value="<?= $encpid; ?>" readonly>
                                                <input type="hidden" class="form-control" name="delete-userpresensi" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" id="delete-idpresensi" name="delete-idpresensi" readonly>
                                                <input type="hidden" class="form-control" id="delete-nikpresensi" name="delete-nikpresensi" readonly>
                                                <input type="hidden" class="form-control" id="delete-tglpresensi" name="delete-tglpresensi" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>OTP Code :</label>
                                                    <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="delete-otppresensi" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-danger" name="deletedata">Approve</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="ModalApproveUpdatePresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white" id="update-labelpresensi"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" name="update-pagepresensi" value="<?= $encpid; ?>" readonly>
                                                <input type="hidden" class="form-control" name="update-userpresensi" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" id="update-idpresensi" name="update-idpresensi" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>OTP Code :</label>
                                                    <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="update-otppresensi" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-info" name="updatedata">Approve</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Reject -->
                            <div class="modal fade text-left" id="ModalApproveRejectPresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning white">
                                            <h4 class="modal-title white" id="reject-labelpresensi"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" name="reject-pagepresensi" value="<?= $encpid; ?>" readonly>
                                                <input type="hidden" class="form-control" id="reject-docnopresensi" name="reject-docnopresensi" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>OTP Code :</label>
                                                    <input type="number" class="form-control" placeholder="6 Digit Kode OTP Notifikasi Telegram" name="reject-otppresensi" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-warning" name="rejectdata">Reject</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function () {

    var table = $('#table_approvepresensi').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#table_approvepresensi').on('click', 'td.details-datapresensi', function () {
        var docno = $(this).attr('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            createChild(row, docno);
            // format(row.child, noref_pp);
            tr.addClass('shown');
        }
    });

    function createChild (row, docno) {
        // This is the table we'll convert into a DataTable
        var table = $('<table class="display" width="100%"/>');
    
        // Display it the child row
        row.child( table ).show();

        $.ajax({
            url:'action/datarequest.php',
            method:"POST",  
            data:{ACTIONDETAILPRESENSI:docno},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'NIK - USERNAME', data: 'users_presensi' },
                    { title: 'DIVISI', data: 'div_presensi' },
                    { title: 'TANGGAL', data: 'tgl_presensi' },
                    { title: 'DATA SEBELUMNYA', data: 'cekold_data_presensi',
                        render : function(data, type, row) {
                            if (data != null) {
                                return '<div class="badge badge-warning">'+data+'</div>'
                            }
                            else {
                                return '-'
                            }
                        }
                    },
                    { title: 'PERUBAHAN DATA', data: 'ceknew_data_presensi',
                        render : function(data, type, row) {
                            if (data != null) {
                                return '<div class="badge badge-info">'+data+'</div>'
                            }
                            else {
                                return '-'
                            }
                        } 
                    },
                    { title: 'PERUBAHAN JADWAL', data: 'jam_data_presensi' },
                    { title: 'KETERANGAN', data: 'ket_data_presensi' },
                ],
                order: [[1, 'asc']]
            } );
        })
    }
});

$(document).ready(function(){
    $(document).on('click', '.approve_editkehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#approve-idpresensi').val(data.no_aprv_presensi);
                $('#approve-nikpresensi').val(data.nik_presensi);
                $('#approve-tglpresensi').val(data.tgl_presensi);

                $('#approve-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveEditPresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_updatekehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#update-idpresensi').val(data.no_aprv_presensi);
                $('#update-nikpresensi').val(data.nik_presensi);
                $('#update-tglpresensi').val(data.tgl_presensi);

                $('#update-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveUpdatePresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_deletekehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#delete-idpresensi').val(data.no_aprv_presensi);
                $('#delete-nikpresensi').val(data.nik_presensi);
                $('#delete-tglpresensi').val(data.tgl_presensi);

                $('#delete-labelpresensi').html("Approve Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveDeletePresensi').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_rejectkehadiran', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#reject-docnopresensi').val(data.no_aprv_presensi);

                $('#reject-labelpresensi').html("Reject Confirmation Docno : "+data.no_aprv_presensi);

                $('#ModalApproveRejectPresensi').modal('show');
            }  
        });
    });
});

function changeIcon(anchor) {
    var icon = anchor.querySelector("i");
    var button = anchor.querySelector('button');

    icon.classList.toggle('la-plus');
    icon.classList.toggle('la-minus');

    button.classList.toggle('success');
    button.classList.toggle('danger');
}

</script>

<?php
    include ("includes/templates/alert.php");
?>