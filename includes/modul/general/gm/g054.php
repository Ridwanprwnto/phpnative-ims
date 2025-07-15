<?php

    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];

    $page_id = $_GET['page'];

    $dec_page = decrypt(rplplus($page_id));
    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["cetakbarcodebronjong"])){
        if(CetakBarcodeBronjong($_POST) > 0 ){
            $alert = array("Success!", "Data barcode bronjong berhasil dicetak", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Cetak Nomor Barcode Bulky</h4>
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

                    <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="print-bulky" data-toggle="tab" href="#printbulky" aria-expanded="true">Print Barcode</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="reprint-bulky" data-toggle="tab" href="#reprintbulky" aria-expanded="false">Reprint Barcode</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="printbulky" aria-expanded="true" aria-labelledby="print-bulky">
                                <div class="row">
                                    <div class="col-12">              
                                        <form method="post" action="">
                                            <div class="form-row">
                                                    <input class="form-control" type="hidden" name="office-barcode" value="<?= $office_id; ?>">
                                                    <input class="form-control" type="hidden" name="dept-barcode" value="<?= $dept_id; ?>">
                                                    <div class="col-md-6 mb-2">
                                                        <label>Nomor Barcode Bronjong : </label>
                                                        <input class="form-control" type="text" name="nomor-barcode" value="<?= $office_id."-".'04'."-".autokeynum(5, 'nomor_bb', $office_id, 'office_bb', $dept_id, 'dept_bb', 'barcode_bronjong'); ?>" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Jumlah Sticker : </label>
                                                        <input class="form-control" type="number" name="urut-barcode" value="" placeholder="Input jumlah cetak" required>
                                                    </div>
                                            </div>
                                            <button type="submit" name="cetakbarcodebronjong" class="btn btn-primary mt-1">
                                                <i class="ft-printer"></i> Print Pdf
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="reprintbulky" aria-labelledby="reprint-bulky">
                                <div class="row">
                                    <div class="col-12">
                                        <form method="post" action="reporting/report-reprint-barcodebulky.php" target="_blank">
                                            <div class="form-row">
                                                    <input class="form-control" type="hidden" name="office-rebarcode" value="<?= $office_id; ?>">
                                                    <input class="form-control" type="hidden" name="dept-rebarcode" value="<?= $dept_id; ?>">
                                                <div class="col-md-12 mb-2">
                                                    <label>Nomor Barcode Bronjong : </label>
                                                    <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="nomorbronjong[]">
                                                        <option value="ALL" >ALL</option>
                                                        <?php
                                                            $sql_bb = "SELECT * FROM barcode_bronjong WHERE office_bb = '$office_id' AND dept_bb = '$dept_id'";
                                                            $query_bb = mysqli_query($conn, $sql_bb);
                                                            while($data_bb = mysqli_fetch_assoc($query_bb)) {
                                                        ?>
                                                            <option value="<?= "'".$data_bb['nomor_bb']."'" ;?>"><?= $data_bb['nomor_bb']; ?></option>
                                                        <?php
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary mt-1">
                                                <i class="ft-printer"></i> Print Pdf
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->

<?php
    include ("includes/templates/alert.php");
?>