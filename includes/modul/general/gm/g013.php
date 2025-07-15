<?php

    $page_id = $_GET['page'];
    $user = $_SESSION["user_name"];
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);
?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Laporan Rekapitulasi STHH</h4>
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
                            <form method="post" action="reporting/report-sthh.php" target="_blank">
                                <div class="form-row">
                                    <div class="col-md-6 mb-2">
                                        <input type="hidden" class="form-control" name="user" value="<?= $user; ?>">
                                        <input type="hidden" class="form-control" name="office" value="<?= $office_id; ?>">
                                        <input type="hidden" class="form-control" name="dept" value="<?= $dept_id; ?>">
                                        <label for="datefrom">Start Date</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" max="<?=date('Y-m-d')?>" name="from" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="dateto">End Date</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" max="<?=date('Y-m-d')?>" name="end" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Status Serah Terima : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="status" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="A">All</option>
                                            <option value="Y">Sudah Kembali</option>
                                            <option value="N">Belum Kembali</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="printpdf" class="btn btn-primary mt-1"> <i class="ft-printer"></i> Print Pdf </button>
                                <button type="submit" name="printexcell" class="btn btn-primary mt-1"> <i class="ft-printer"></i> Print Excell </button>
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
    var input = document.getElementById("reqDate");
    var today = new Date();
    var day = today.getDate();

    // Set month to string to add leading 0
    var mon = new String(today.getMonth()+1); //January is 0!
    var yr = today.getFullYear();

    if(mon.length < 2) { mon = "0" + mon; }
    if(day.length < 2) { dayn = "0" + day; }

    var date = new String( yr + '-' + mon + '-' + day );

    input.disabled = false; 
    input.setAttribute('max', date);
</script>