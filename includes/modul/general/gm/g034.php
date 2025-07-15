<?php

    $page_id = $_GET['page'];
    $idoffice = $_SESSION["office"];
    $iddept = $_SESSION["department"];
    $usernik = $_SESSION["user_nik"];
    $username = $_SESSION["user_name"];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Laporan Tablok Barang</h4>
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
                            <form method="post" action="reporting/report-tablok-barang.php" target="_blank">
                                <div class="form-row">
                                    <input type="hidden" class="form-control" name="user-tablok" value="<?= $usernik;?>" required>
                                    <input type="hidden" class="form-control" name="office-tablok" value="<?= $idoffice;?>" required>
                                    <input type="hidden" class="form-control" name="dept-tablok" value="<?= $iddept;?>" required>
                                    <div class="col-md-12 mb-2">
                                        <label for="datefrom">Start Date</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" name="startdate-tablok" value="" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="datefrom">End Date</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" name="enddate-tablok" value="" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
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