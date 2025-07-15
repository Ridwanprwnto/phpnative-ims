<?php

    $page_id = $_GET['page'];
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
                    <h4 class="card-title" id="horz-layout-basic">Report Daftar Kategori Pelanggaran</h4>
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
                            <form method="post" action="reporting/report-daftar-pelanggaran.php" target="_blank">
                                <div class="form-row">
                                    <input class="form-control" type="hidden" name="user-pelanggaran" value="<?= $user;?>" readonly>
                                    <div class="col-md-12 mb-2">
                                        <label>Kategori Pelanggaran : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" id="cat-pelanggaran" name="cat-pelanggaran" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="ALL" >ALL</option>
                                            <?php 
                                                $query_cat_pel = mysqli_query($conn, "SELECT * FROM category_pelanggaran");
                                                while($data_cat_pel = mysqli_fetch_assoc($query_cat_pel)) { ?>
                                                <option value="<?= $data_cat_pel['id_ctg_plg']; ?>">
                                                    <?= $data_cat_pel['id_ctg_plg']." - ".$data_cat_pel['name_ctg_plg']; ?>
                                                </option>
                                                <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Jenis Pelanggaran : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" id="jenis-pelanggaran" name="jenis-pelanggaran" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="ALL" >ALL</option>
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
        $("#cat-pelanggaran").on('change', function () {
            var catID = $('#cat-pelanggaran').val();
            var data = "LISTCATPELANGGARAN=" + catID;
            if (catID) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#jenis-pelanggaran').html(htmlresponse);
                    }
                });
            }
            else {
                $('#jenis-pelanggaran').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });
</script>