<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);
$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["repostabsensi"])){
    if(RepostingKehadiran($_POST)){
        $alert = array("Success!", "Berhasil Repost Data Absensi", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monitoring Gagal Posting Data Absensi</h4>
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
                        <div class="form-row">
                            <input type="hidden" name="office-src" id="office-src" value="<?= $idoffice; ?>" class="form-control" readonly>
                            <div class="col-md-6 mb-2">
                                <p>Periode Awal</p>
                                <input type="date" name="tglawal-src" id="tglawal-src" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <p>Periode Akhir</p>
                                <input type="date" name="tglakhir-src" id="tglakhir-src" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>TGL WAKTU INPUT</th>
                                            <th>POSTING</th>
                                            <th>NIK - NAMA</th>
                                            <th>BAGIAN</th>
                                            <th>TGL ABSENSI</th>
                                            <th>ALASAN</th>
                                            <th>KETERANGAN</th>
                                            <th>CHECKLIST</th>
                                        </tr>
                                    </thead>
                                    <tbody class="datatable-srcabsensi">
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success btn-min-width pull-right mb-1" onclick="return validateFormGagalPostAbsensi();">Repost Data</button>
                            <!-- Modal -->
                            <div class="modal fade text-left" id="repost-kehadiran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="myModalLabel">Repost Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input class="form-control" type="hidden" name="page-repost" value="<?= $encpid; ?>" readonly>
                                            <label>Are you sure you want to repost the checked data?</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="repostabsensi" class="btn btn-outline-success">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
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
    load_data();
    function load_data(offabsensi, tglawalabsensi, tglakhirabsensi) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: {OFFABSENSISRC: offabsensi, TGLAWLABSENSISRC: tglawalabsensi, TGLAKRABSENSISRC: tglakhirabsensi},
            beforeSend: function() {
                hideSpinner();
                showSpinner();
            },
            success: function(hasil) {
                $('.datatable-srcabsensi').html(hasil);
            },
            complete: function() {
                $('.icheck1 input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });
            },
            error: function(hasil) {
                $('.datatable-srcabsensi').html(hasil);
            }
        });
    }
    $('#tglawal-src').change(function(){
        var offabsensi = $("#office-src").val();
        var tglawalabsensi = $("#tglawal-src").val();
        var tglakhirabsensi = $("#tglakhir-src").val();
        load_data(offabsensi, tglawalabsensi, tglakhirabsensi);
    });
    $('#tglakhir-src').change(function(){
        var offabsensi = $("#office-src").val();
        var tglawalabsensi = $("#tglawal-src").val();
        var tglakhirabsensi = $("#tglakhir-src").val();
        load_data(offabsensi, tglawalabsensi, tglakhirabsensi);
    });
    $('#dept-src').change(function(){
        var offabsensi = $("#office-src").val();
        var tglawalabsensi = $("#tglawal-src").val();
        var tglakhirabsensi = $("#tglakhir-src").val();
        load_data(offabsensi, tglawalabsensi, tglakhirabsensi);
    });
    $('#office-src').change(function(){
        var offabsensi = $("#office-src").val();
        var tglawalabsensi = $("#tglawal-src").val();
        var tglakhirabsensi = $("#tglakhir-src").val();
        load_data(offabsensi, tglawalabsensi, tglakhirabsensi);
    });
    function hideSpinner() {
        $('.datatable-srcabsensi').html("");
        if($(document).find('#loadpresensi-spinner').length > 0) {
            $(document).find('#loadpresensi-spinner').remove();
        }
    }
    function showSpinner() {
        $('.datatable-srcabsensi').append('<tr><td colspan="9"><i id="loadpresensi-spinner" class="la la-spinner spinner"></i></td></tr>');
    }
});

function validateFormGagalPostAbsensi() {
    var count_checked = $('input[name="check_id_hadir[]"]:checked');
    if (count_checked.length == 0) {
        alert("Data belum ada yang dicheklist!");
        return false;
    }
    else {
        $('#repost-kehadiran').modal('show');
    }
}
</script>

<?php
    include ("includes/templates/alert.php");
?>