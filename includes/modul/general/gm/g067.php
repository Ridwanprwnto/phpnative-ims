<?php

    $page_id = $_GET['page'];
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Reprint Bukti Penerimaan Hasil Pemeriksaan Perbaikan Barang</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">              
                                <form method="post" action="reporting/report-reprint-hppb.php" target="_blank">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-2">
                                            <label>Nomor SJ : </label>
                                            <select class="select2 form-control block" style="width: 100%" type="text" name="idsj-perbaikan" id="idsj-perbaikan" required>
                                                <option value="" selected disabled>Please Select</option>
                                                <?php
                                                    $query_sj = mysqli_query($conn, "SELECT no_sj FROM surat_jalan 
                                                    WHERE LEFT(no_sj, 1) = 'R' AND LEFT(asal_sj, 4) = '$office_id' AND LEFT(asal_sj, 4) = '$office_id' AND RIGHT(asal_sj, 4) = '$dept_id' ORDER BY no_sj DESC");
                                                    while($data_sj = mysqli_fetch_assoc($query_sj)) { ?>
                                                    <option value="<?= $data_sj['no_sj'];?>"><?= substr($data_sj['no_sj'], 1, 5);?></option>
                                                <?php 
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Nama Barang : </label>
                                            <select class="select2 form-control block" style="width: 100%" type="text" name="idbr-perbaikan" id="idbr-perbaikan" required>
                                                <option value="">Please Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Serial Number : </label>
                                            <select class="select2 form-control block" style="width: 100%" type="text" name="idsn-perbaikan" id="idsn-perbaikan" required>
                                                <option value="">Please Select</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Nomor DAT : </label>
                                            <input type="text" name="idat-perbaikan" id="idat-perbaikan" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Hasil Perbaikan : </label>
                                            <input type="text" name="idkd-perbaikan" id="idkd-perbaikan" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary mt-1">
                                        <i class="ft-printer"></i> Report Data
                                    </button>
                                </form>
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
$(document).ready(function() {
    $("#idsj-perbaikan").on('change', function() {
        var idsjp = $('#idsj-perbaikan').val();
        var data = "IDSJP="+idsjp;
        if(idsjp){
            $.ajax ({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success : function(htmlresponse) {
                    $('#idbr-perbaikan').html(htmlresponse);
                }
            });
        }
        else {
            $('#idsj-perbaikan').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function(){
    $("select[name=idsj-perbaikan],select[name=idbr-perbaikan]").on('change', function(){
        var IDsjp = $('#idsj-perbaikan').val();
        var IDbrp = $('#idbr-perbaikan').val();
        if(IDsjp && IDbrp) {
            $.ajax({
                type :'POST',
                url :'action/datarequest.php',
                data : {SJPID:IDsjp, IDBRP:IDbrp},
                success : function(htmlresponse){
                    $('#idsn-perbaikan').html(htmlresponse);
                }
            });
        }
        else {
            $('#idsn-perbaikan').val('');
        }
    });
});

$(document).ready(function(){
    $("select[name=idsj-perbaikan],select[name=idbr-perbaikan],select[name=idsn-perbaikan]").on('change', function(){
        var IDsjp = $('#idsj-perbaikan').val();
        var IDbrp = $('#idbr-perbaikan').val();
        var IDsnp = $('#idsn-perbaikan').val();
        if(IDsjp && IDbrp && IDsnp) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {SJP:IDsjp, BRP:IDbrp, SNP:IDsnp},
                dataType:"JSON",
                success:function(data){
                    if (data.length > 0) {
                        $('#idat-perbaikan').val((data[0].at_sj));
                        $('#idkd-perbaikan').val((data[0].kondisi_name));
                    }
                    else {
                        $('#idat-perbaikan').val('');
                        $('#idkd-perbaikan').val('');
                    }
                }
            });
        }
        else {
            $('#idat-perbaikan').val('');
            $('#idkd-perbaikan').val('');
        }
    });
});
</script>