<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

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
                    <h4 class="card-title" id="horz-layout-basic">Reprint Bukti Mutasi Barang</h4>
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
                            <form method="post" action="reporting/report-reprint-bukti-mutasi-barang.php" target="_blank">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label>Nomor Dokumen : </label>
                                        <select class="select2 form-control block" style="width: 100%"
                                            type="text" name="docno" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php
                                                $query = mysqli_query($conn, "SELECT no_btb_dpd FROM btb_dpd WHERE office_btb_dpd = '$office_id' AND dept_btb_dpd = '$dept_id' ORDER BY RIGHT(no_btb_dpd, 5) DESC");
                                                while($data = mysqli_fetch_assoc($query)) { ?>
                                                <option value="<?= $data['no_btb_dpd'];?>"><?= substr($data['no_btb_dpd'], 1);?></option>
                                            <?php 
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary mt-1">
                                    <i class="ft-printer"></i> Reprint
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