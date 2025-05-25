<?php
    $idoffice = $_SESSION["office"];
    $iddept = $_SESSION["department"];
    $usernik = $_SESSION["user_nik"];
    $username = $_SESSION["user_name"];

    $page_id = $_GET['page'];

    $dec_page = decrypt(rplplus($page_id));

    $encpid = "index.php?page=".encrypt($dec_page);
    
    if(isset($_POST["resenddata"])){
        if(ResendOTPAbsensi($_POST)){
            $datapost = isset($_POST["id-resendotp"]) ? $_POST["id-resendotp"] : NULL;
            $alert = array("Success!", "Pengajuan Perubahan Data Absensi Docno ".$datapost." Berhasil Resend OTP!", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["canceldata"])){
        $datapost = isset($_POST["reject-docnopresensi"]) ? $_POST["reject-docnopresensi"] : NULL;
        if(CancelDataKehadiran($_POST)){
            $alert = array("Success!", "Pengajuan perubahan data jadwal Docno ".$datapost." Berhasil di batalkan!", "success", "$encpid");
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
                    <h4 class="card-title">Daftar Pengajuan Perubahan Absensi Dan Jadwal</h4>
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
                    <form method="post" action="">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="table_listpresensi"">
                                <thead>
                                    <tr>
                                        <th>DETAIL</th>
                                        <th>NO</th>
                                        <th>DOCNO</th>
                                        <th>TANGGAL PENGAJUAN</th>
                                        <th>DIAJUKAN</th>
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
                                            <td class="details-listpresensi" id="<?= $data['no_aprv_presensi']; ?>" onclick="changeIcon(this)">
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
                                                    <a class="dropdown-item resend_otppresensi" href="#" title="Resend OTP Docno : <?= $data['no_aprv_presensi']; ?>" name="resend_otppresensi" id="<?= $data["no_aprv_presensi"]; ?>" data-toggle="tooltip" data-placement="bottom">Resend OTP</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item cancel_rubahpresensi" href="#" title="Cancel Pengajuan Perubahan Presensi Docno : <?= $data['no_aprv_presensi']; ?>" name="cancel_rubahpresensi" id="<?= $data["no_aprv_presensi"]; ?>" data-toggle="tooltip" data-placement="bottom">Cancel</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                </tbody>
                                <!-- Modal Resend OTP -->
                                <div class="modal fade text-left" id="modalResendOtp" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white" id="label-resendotp"></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" class="form-control" name="page-resendotp" value="<?= $encpid; ?>" readonly>
                                                <input type="hidden" class="form-control" name="trans-resendotp" value="<?= $arrmodifref[10]; ?>" readonly>
                                                <input type="hidden" class="form-control" id="id-resendotp" name="id-resendotp" readonly>
                                                <label>Proses ini akan mengirim ulang kode OTP ke akun penerima notifikasi Approval Telegram</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="resenddata" class="btn btn-outline-primary">Resend OTP</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Cancel -->
                                <div class="modal fade text-left" id="modalCancelPresensi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white" id="label-cancelpresensi"></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" class="form-control" id="reject-docnopresensi" name="reject-docnopresensi" readonly>
                                                <label>Proses ini akan membatalkan pengajuan perubahan data inputan absensi atau perubahan jadwal</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="canceldata" class="btn btn-outline-primary">Proses</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </table>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function () {

    var table = $('#table_listpresensi').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#table_listpresensi').on('click', 'td.details-listpresensi', function () {
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
                                return '<div class="badge badge-info"></div>'
                            }
                        }
                    },
                    { title: 'PERUBAHAN DATA', data: 'ceknew_data_presensi',
                        render : function(data, type, row) {
                            if (data != null) {
                                return '<div class="badge badge-info">'+data+'</div>'
                            }
                            else {
                                return '<div class="badge badge-info"></div>'
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

function changeIcon(anchor) {
    var icon = anchor.querySelector("i");
    var button = anchor.querySelector('button');

    icon.classList.toggle('la-plus');
    icon.classList.toggle('la-minus');

    button.classList.toggle('success');
    button.classList.toggle('danger');
}

$(document).ready(function(){
    $(document).on('click', '.resend_otppresensi', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#id-resendotp').val(data.no_aprv_presensi);

                $('#label-resendotp').html("Resend OTP Perubahan Presensi Docno : "+data.no_aprv_presensi);

                $('#modalResendOtp').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.cancel_rubahpresensi', function(){  
        var docno = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{APPROVEEDITKEHADIRAN:docno},  
            dataType:"json",  
            success:function(data){
                $('#reject-docnopresensi').val(data.no_aprv_presensi);

                $('#label-cancelpresensi').html("Cancel Pengajuan Perubahan Presensi Docno : "+data.no_aprv_presensi);

                $('#modalCancelPresensi').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>