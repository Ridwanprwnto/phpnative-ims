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
                    <h4 class="card-title" id="horz-layout-basic">Laporan Pengajuan Perbaikan Barang / Rekomendasi Pemusnahan</h4>
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
                            <form method="post" action="reporting/report-mon-service.php" target="_blank">
                                <div class="form-row">
                                    <input type="hidden" class="form-control" name="user-service" value="<?= $usernik;?>" required>
                                    <input type="hidden" class="form-control" name="office-service" value="<?= $idoffice;?>" required>
                                    <input type="hidden" class="form-control" name="dept-service" value="<?= $iddept;?>" required>
                                    <div class="col-md-6 mb-2">
                                        <label for="datefrom">Periode Awal</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" name="startdate-service" value="" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="datefrom">Periode Akhir</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="date" class="form-control" name="enddate-service" value="" required>
                                            <div class="form-control-position">
                                            <i class="ft-message-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Status SJ : </label>
                                        <select class="select2 form-control block" style="width: 100%" type="text" name="status-service" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <option value="A">All</option>
                                            <option value="N">Belum PTB</option>
                                            <option value="Y">Sudah PTB</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="printpdf" class="btn btn-primary mt-1"><i class="ft-printer"></i> Print Pdf </button>
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