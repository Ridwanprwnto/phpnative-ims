<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$_SESSION['WATCHPLG'] = $dec_page;

$encpid = encrypt($dec_page);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History Data Pelanggaran CCTV</h4>
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
                            <input type="hidden" name="dept-src" id="dept-src" value="<?= $iddept; ?>" class="form-control" readonly>
                            <div class="col-md-6 mb-2">
                                <p>Periode Awal</p>
                                <input type="date" name="tglawal-src" id="tglawal-src" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <p>Periode Akhir</p>
                                <input type="date" name="tglakhir-src" id="tglakhir-src" class="form-control">
                            </div>
                            <div class="col-md-12 mb-2">
                                <p>Search</p>
                                <input type="text" name="keyword-src" id="keyword-src" class="form-control" placeholder="Keyword">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pelanggaran</th>
                                        <th>Tgl Waktu Kejadian</th>
                                        <th>Shift</th>
                                        <th>Divisi / Bagian</th>
                                        <th>Kategori Pelanggaran</th>
                                        <th>Area Lokasi CCTV</th>
                                        <th>Status Follow Up</th>
                                        <th>Rekaman</th>
                                    </tr>
                                </thead>
                                <tbody class="datatable-srcplg">
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Read -->
                        <div class="modal fade text-left" id="dataModalReadHplg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header bg-info white">
                                        <h4 class="modal-title white"
                                            id="myModalLabel">Detail Data Pelanggaran CCTV</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row" id="modal_readdatahplg">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
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
    function load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: {OFFPLGCCTVSRC: offplg, DEPTPLGCCTVSRC: deptplg, TGLAWLPLGCCTVSRC: tglawalplg, TGLAKRPLGCCTVSRC: tglakhirplg, KEYPLGCCTVSRC:keyword},
            success:function(hasil) {
                $('.datatable-srcplg').html(hasil);
            }
        });
    }
    $('#keyword-src').keyup(function(){
        var offplg = $("#office-src").val();
        var deptplg = $("#dept-src").val();
        var tglawalplg = $("#tglawal-src").val();
        var tglakhirplg = $("#tglakhir-src").val();
        var keyword = $("#keyword-src").val();
        load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword);
    });
    $('#tglawal-src').change(function(){
        $("#keyword-src").val('');
        var offplg = $("#office-src").val();
        var deptplg = $("#dept-src").val();
        var tglawalplg = $("#tglawal-src").val();
        var tglakhirplg = $("#tglakhir-src").val();
        var keyword = $("#keyword-src").val();
        load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword);
    });
    $('#tglakhir-src').change(function(){
        $("#keyword-src").val('');
        var offplg = $("#office-src").val();
        var deptplg = $("#dept-src").val();
        var tglawalplg = $("#tglawal-src").val();
        var tglakhirplg = $("#tglakhir-src").val();
        var keyword = $("#keyword-src").val();
        load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword);
    });
    $('#dept-src').change(function(){
        $("#keyword-src").val('');
        var offplg = $("#office-src").val();
        var deptplg = $("#dept-src").val();
        var tglawalplg = $("#tglawal-src").val();
        var tglakhirplg = $("#tglakhir-src").val();
        var keyword = $("#keyword-src").val();
        load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword);
    });
    $('#office-src').change(function(){
        $("#keyword-src").val('');
        var offplg = $("#office-src").val();
        var deptplg = $("#dept-src").val();
        var tglawalplg = $("#tglawal-src").val();
        var tglakhirplg = $("#tglakhir-src").val();
        var keyword = $("#keyword-src").val();
        load_data(offplg, deptplg, tglawalplg, tglakhirplg, keyword);
    });
});


$(document).ready(function(){
    $(document).on('click', '.read_datahplg', function(){  
        var nomor_id = $(this).attr("id");  
        if(nomor_id != '') {  
            $.ajax({
                url:"action/datarequest.php",
                method:"POST",  
                data:{RMHISTORYPLGCCTV: nomor_id},  
                success:function(data){  
                    $('#modal_readdatahplg').html(data);
                    $('#dataModalReadHplg').modal('show');
                }  
            });
        }
    });
});
</script>