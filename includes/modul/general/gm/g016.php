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
                    <h4 class="card-title" id="horz-layout-basic">Report Laporan Pembelian</h4>
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
                            <form method="post" action="reporting/report-pembelian.php" target="_blank">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="office" value="<?= $office_id; ?>">
                                    <input type="hidden" class="form-control" name="dept" value="<?= $dept_id; ?>">
                                    <input type="hidden" class="form-control" name="user" value="<?= $user; ?>">
                                    <label for="datefrom">Start Date</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="date" id="datefrom" class="form-control" name="from" value="" required>
                                        <div class="form-control-position">
                                        <i class="ft-message-square"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateto">End Date</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="date" id="dateto" class="form-control" name="end" value="" required>
                                        <div class="form-control-position">
                                        <i class="ft-message-square"></i>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary mt-1">
                                    <i class="ft-printer"></i> Print Report
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