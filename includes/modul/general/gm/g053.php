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
                    <h4 class="card-title" id="horz-layout-basic">Data Barcode Delivery Van</h4>
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
                            <form method="post" action="reporting/report-barcode-van.php" target="_blank">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label>Jenis Mobil : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" id="jenis-barcode" name="jenis-barcode" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="<?= $office_id; ?>ALL" >ALL</option>
                                            <?php 
                                                $query_jns_mbl = mysqli_query($conn, "SELECT * FROM jenis_mobil");
                                                while($data_jns_mbl = mysqli_fetch_assoc($query_jns_mbl)) { ?>
                                                <option value="<?= $office_id.$data_jns_mbl['no_jns_mobil'];?>">
                                                    <?= $data_jns_mbl['no_jns_mobil']." - ".$data_jns_mbl['name_jns_mobil'];?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Nomor Kendaraan : </label>
                                        <select class="select2 form-control block" multiple="multiple" data-placeholder="Please Select" style="width: 100%" type="text" id="mobil-barcode" name="mobilbarcode[]" required>
                                        </select>
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
    $(document).ready(function () {
        $("#jenis-barcode").on('change', function () {
            var jenis_id = $('#jenis-barcode').val();
            var data = "LISTDELIVAN=" + jenis_id;
            if (jenis_id) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#mobil-barcode').html(htmlresponse);
                    }
                });
            }
            else {
                $('#mobil-barcode').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });
</script>