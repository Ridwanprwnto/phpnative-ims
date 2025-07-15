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
                    <h4 class="card-title" id="horz-layout-basic">Reprint Surat Jalan Keluar Barang</h4>
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
                            <form method="post" action="reporting/report-reprint-barang-keluar.php" target="_blank">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label>Nomor SJ : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="sj-keluar" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                                $query_sj = mysqli_query($conn, "SELECT no_sj FROM surat_jalan 
                                                WHERE LEFT(no_sj, 1) = 'M' AND LEFT(asal_sj, 4) = '$office_id' AND LEFT(asal_sj, 4) = '$office_id' AND RIGHT(asal_sj, 4) = '$dept_id' ORDER BY no_sj DESC");
                                                while($data_sj = mysqli_fetch_assoc($query_sj)) { ?>
                                                <option value="<?= $data_sj['no_sj'];?>"><?= substr($data_sj['no_sj'], 1, 5);?></option>
                                            <?php 
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary mt-1">
                                    <i class="ft-printer"></i> Reprint Surat Jalan
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