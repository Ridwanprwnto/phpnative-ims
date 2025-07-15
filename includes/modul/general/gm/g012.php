<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$usernik = $_SESSION["user_nik"];

if (isset($_SESSION['ALERT'])) {
    $alert = $_SESSION["ALERT"];
    unset($_SESSION['ALERT']);
}

$_SESSION['PRINTPP'] = $_POST;
$_SESSION['PRINTBTB'] = $_POST;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["deletedatapp"])){
    if(cancelpp($_POST) > 0 ){
        $datapost = $_POST["ppid"];
        $alert = array("Success!", "PP Nomor ".$datapost." berhasil dibatalkan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["prosesdata"])){
    if(prosesterimapp($_POST) > 0 ){
        $datapost = $_POST["nopp"];
        $alert = array("Success!", "PP Nomor ".$datapost." berhasil realisasi penerimaan barang", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["approveall"])){
    if(approveall($_POST) > 0 ){
        $datapost = $_POST["nopp-approveall"];
        $alert = array("Success!", "PP Nomor ".$datapost." berhasil disetujui semua", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["approvehalf"])){
    if(approvehalf($_POST) > 0 ){
        $datapost = $_POST["nopp-approvehalf"];
        $alert = array("Success!", "PP Nomor ".$datapost." disetujui sebagian", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["prosesnosp"])){
    if(prosesnosp($_POST) > 0 ){
        $datapost = $_POST["nopp-inputspno"];
        $alert = array("Success!", "PP Nomor ".$datapost." berhasil proses pesanan pembelian", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["canceldata3"])){
    if(cancelpps3($_POST) > 0 ){
        $datapost = $_POST["nopp-cancel"];
        $alert = array("Success!", "PP Nomor ".$datapost." di reject dept tujuan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["approvedata"])){
    if(approvepp($_POST) > 0 ){
        $datapost = $_POST["nomorapprove"];
        $alert = array("Success!", "PP Nomor ".$datapost." berhasil di approve", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["canceldata1"])){
    if(cancelpps1($_POST) > 0 ){
        $datapost = $_POST["noppcancel"];
        $alert = array("Success!", "PP Nomor ".$datapost." di reject atasan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>
<!-- Auto Fill table -->
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Permohonan Pembelian</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <table class="table table-striped table-bordered zero-configuration row-grouping-pembelian" id="detail_barangpp">
                            <thead>
                                <tr>
                                    <th>DETAIL</th>
                                    <th>NO</th>
                                    <th>TGL PENGAJUAN</th>
                                    <th>NOMOR PP</th>
                                    <th>JUMLAH ITEM</th>
                                    <th>KEPERLUAN</th>
                                    <th>STATUS PP</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;

                                $query = mysqli_query($conn, "SELECT A.*, B.*, COUNT(B.noref) AS jumlah_item, C.*, D.*, E.*, F.*, G.id_office AS id_office_to, G.office_name AS id_office_name, H.id_department AS id_dept_to, H.department_name AS id_dept_name FROM pembelian AS A
                                INNER JOIN detail_pembelian AS B ON A.noref = B.noref
                                INNER JOIN office AS C ON A.id_office = C.id_office
                                INNER JOIN department AS D ON A.id_department = D.id_department
                                INNER JOIN users AS E ON A.user = E.nik
                                INNER JOIN status_pembelian AS F ON A.status_pp = F.id_spp
                                INNER JOIN office AS G ON A.office_to = G.id_office
                                INNER JOIN department AS H ON A.department_to = H.id_department
                                WHERE A.id_office = '$office_id' AND A.id_department = '$dept_id' AND B.proses = 'Y' GROUP BY A.id_pembelian ORDER BY A.proses_date DESC");

                                while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td class="details-datapp" id="<?= $data['noref']; ?>" onclick="changeIcon(this)">
                                        <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                    </td>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['tgl_pengajuan']; ?></td>
                                    <td><a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="detail_pp" title="Show Detail PP Nomor <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="text-bold-600 detail_pp"><?= $data['ppid']; ?></a></td>
                                    <td><?= $data['jumlah_item']; ?></td>
                                    <td>
                                    <h6 class="mb-0">
                                        <span class="text-bold-600"><?= $data['keperluan']; ?></span> on
                                        <em><?= date( "d/m/Y", strtotime($data['proses_date'])); ?></em>
                                    </h6>
                                    </td>
                                    <td>
                                        <div class="badge badge-<?= $data['status_warna']; ?> label-square">
                                            <i class="ft-info font-medium-2"></i>
                                            <span><?= strtoupper($data['status_name']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="dropdown">
                                            <button id="idaction<?= $data['id_pembelian']; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="idaction<?= $data['id_pembelian']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="detail_pp" title="Detail PP Nomor <?= $data['ppid']; ?>" class="dropdown-item detail_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-file-text"></i>Detail</a>
                                                <?php
                                                if ($data['status_pp'] != $arrsp[10]) { ?>
                                                <a href="reporting/report-form-pp.php?ppid=<?= encrypt($data['ppid']);?>" title="Print PP Nomor <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print PP</a>
                                                <?php } ?>
                                                <?php
                                                if ($data['status_pp'] == $arrsp[10]) { ?>
                                                <a href="reporting/report-btb.php?nomor=<?= encrypt($data['id_pembelian']);?>" title="Print BTB Nomor PP <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print BTB</a>
                                                <?php } ?>
                                                <?php
                                                if ($data['status_pp'] == $arrsp[4]) { ?>
                                                <a title="Revisi PP Nomor <?= $data['ppid']; ?>" href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[4]);?>&id=<?= encrypt($data['ppid']);?>" class="dropdown-item" data-toggle="tooltip" data-placement="bottom" ><i class="ft-edit"></i>Revisi PP</a>
                                                <?php } ?>
                                                <?php
                                                if ($data['status_pp'] == $arrsp[6]) {
                                                ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="realisasi_pp" title="Proses Realisasi PP Nomor <?= $data['ppid']; ?>" class="dropdown-item realisasi_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-package"></i>Terima Barang</a>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($data['status_pp'] == $arrsp[7] || $data['status_pp'] == $arrsp[8]) {
                                                ?>
                                                <a href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[5]);?>&id=<?= encrypt($data['ppid']);?>" title="Input Penerimaan Barang PP Nomor <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-feather"></i>Input Barang</a>
                                                <?php
                                                }
                                                if ($data['status_pp'] == $arrsp[9]) {
                                                ?>
                                                <a href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[6]);?>&id=<?= encrypt($data['ppid']);?>" title="Update Terima Barang Nomor PP <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-edit"></i>Update Barang</a>
                                                <a href="reporting/report-btb.php?nomor=<?= encrypt($data['id_pembelian']);?>" title="Print BTB Nomor PP <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print BTB</a>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($data['status_pp'] == $arrsp[0]) {
                                                    if (substr($data['ppid'], 0, 3) != "PPM") {        
                                                    ?>
                                                <a href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[3]);?>&id=<?= encrypt($data['ppid']);?>" title="Edit PP Nomor <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-edit-3"></i>Edit PP</a>
                                                    <?php } ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="approve_pp" title="Approve PP Nomor <?= $data['ppid']; ?>" class="dropdown-item approve_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-check-square"></i>Approve</a>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="reject_pp" title="Reject PP Nomor <?= $data['ppid']; ?>" class="dropdown-item reject_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i>Direject Atasan</a><?php
                                                }
                                                if($data['status_pp'] == $arrsp[2]) { ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="approvehalf_pp" title="Setujui Sebagian PP Nomor <?= $data['ppid']; ?>" class="dropdown-item approvehalf_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-feather"></i>Setujui Sebagian</a>
                                                <?php }
                                                if($data['status_pp'] == $arrsp[2]) { ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="approveall_pp" title="Setujui Semua PP Nomor  <?= $data['ppid']; ?>" class="dropdown-item approveall_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-check-square"></i>Setujui Semua</a>
                                                <?php }
                                                if($data['status_pp'] == $arrsp[4] || $data['status_pp'] == $arrsp[5] ) { ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="prosespesanan_pp" title="Proses Pesanan Pembelian <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom"  class="dropdown-item prosespesanan_pp"><i class="ft-shopping-cart"></i>Proses SP</a>
                                                <?php }
                                                if($data['status_pp'] == $arrsp[2] ) { ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="cancel_pp" title="Reject PP Nomor  <?= $data['ppid']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item cancel_pp"><i class="ft-x-square"></i>Reject PP</a>
                                                <?php }
                                                if ($data['status_pp'] == $arrsp[0] || $data['status_pp'] == $arrsp[1] || $data['status_pp'] == $arrsp[2] || $data['status_pp'] == $arrsp[3]) {
                                                ?>
                                                <a href="javascript:void(0);" id="<?= $data['id_pembelian']; ?>" name="batal_pp" title="Batalkan PP Nomor <?= $data['ppid']; ?>" class="dropdown-item batal_pp" data-toggle="tooltip" data-placement="bottom"><i class="ft-trash"></i>Batalkan</a>
                                                <?php
                                                }
                                                ?>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Detail -->
                            <div class="modal fade text-left" id="modalDetailPP" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white">DETAIL DATA PERMOHONAN PEMBELIAN</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="body_detailpp">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Batal PP -->
                            <div class="modal fade text-left" id="modalDeletePP" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="del-idpp" name="idpp" readonly>
                                            <input type="hidden" class="form-control" id="del-nomorpp" name="ppid" readonly>
                                            <input type="hidden" class="form-control" id="del-refpp" name="id-p3at" readonly>
                                            <input type="hidden" class="form-control" name="status-p3at" value="<?= $arrsp3at[1]; ?>" readonly>
                                            <input type="hidden" class="form-control" id="del-norefpp" name="noref" readonly>
                                            <label id="del-labelpp"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedatapp" class="btn btn-outline-danger">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Reject PP Dept Tujuan-->
                            <div class="modal fade text-left" id="modalCancelPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="cnl-labelpp"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" id="cnl-idpp" name="idpp-cancel" readonly>
                                                <input type="hidden" class="form-control" id="cnl-nopp" name="nopp-cancel" readonly>
                                                <input type="hidden" class="form-control" id="cnl-norefpp" name="noref-cancel" readonly>
                                                <input type="hidden" class="form-control" name="user-cancel" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" name="spid-cancel" value="<?= $arrsp[3]; ?>" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" name= "keterangan" type="text" placeholder="Berikan keterangan / catatan alasan reject pp" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="canceldata3" class="btn btn-outline-danger">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Reject PP Atasan -->
                            <div class="modal fade text-left" id="modalRejectPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="rjt-labelpp"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" id="rjt-idpp" name="idppcancel" readonly>
                                                <input type="hidden" class="form-control" id="rjt-nopp" name="noppcancel" readonly>
                                                <input type="hidden" class="form-control" name="spidcancel" value="<?= $arrsp[1]; ?>" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" name= "keterangan" type="text" placeholder="Berikan keterangan / catatan alasan reject PP" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="canceldata1" class="btn btn-outline-danger">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="modalApprovePP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white">Approve Pengajuan Pembelian</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="idppapprove" id="app-idpp" readonly>
                                            <input type="hidden" class="form-control" name="nomorapprove" id="app-nopp" readonly>
                                            <input type="hidden" class="form-control" name="norefapprove" id="app-norefpp" readonly>
                                            <input type="hidden" class="form-control" name="spidapprove" value="<?= $arrsp[2]; ?>" readonly>
                                            <input type="hidden" class="form-control" name="userapprove" value="<?= $usernik; ?>" readonly>
                                            <label id="app-labelpp"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="approvedata" class="btn btn-outline-info">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Approve Sebagian -->
                            <div class="modal fade text-left" id="modalApproveSebagianPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white" id="aps-labelpp"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" id="aps-idpp" name="idpp-approvehalf" readonly>
                                                <input type="hidden" class="form-control" id="aps-nopp" name="nopp-approvehalf" readonly>
                                                <input type="hidden" class="form-control" id="aps-norefpp" name="noref-approvehalf" readonly>
                                                <input type="hidden" class="form-control" name="user-approvehalf" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" name="spid-approvehalf" value="<?= $arrsp[4]; ?>" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" name= "keterangan" type="text" placeholder="Berikan keterangan / catatan alasan PP disetujui sebagian" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="approvehalf" class="btn btn-outline-info">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Approve All -->
                            <div class="modal fade text-left" id="modalApproveAllPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white">Approve Semua PP</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">                                            
                                            <input type="hidden" class="form-control" id="apl-idpp" name="idpp-approveall" readonly>
                                            <input type="hidden" class="form-control" id="apl-nopp" name="nopp-approveall" readonly>
                                            <input type="hidden" class="form-control" id="apl-norefpp" name="noref-approveall" readonly>
                                            <input type="hidden" class="form-control" name="user-approveall" value="<?= $usernik; ?>" readonly>
                                            <input type="hidden" class="form-control" name="spid-approveall" value="<?= $arrsp[5]; ?>" readonly>
                                            <label id="apl-labelpp"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="approveall" class="btn btn-outline-info">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Proses SP -->
                            <div class="modal fade text-left" id="modalProsesPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white">Proses Pesanan Pembelian</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" id="prs-idpp" name="idpp-inputspno" readonly>
                                                <input type="hidden" class="form-control" id="prs-nopp" name="nopp-inputspno" readonly>
                                                <input type="hidden" class="form-control" id="prs-norefpp" name="noref-inputspno" readonly>
                                                <input type="hidden" class="form-control" name="user-inputspno" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" name="idsp-inputspno" value="<?= $arrsp[6]; ?>" readonly>
                                                <label id="prs-labelpp"></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="prosesnosp" class="btn btn-outline-info">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Proses Terima Realisasi PP -->
                            <div class="modal fade text-left" id="modalRealisasiPP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark white">
                                            <h4 class="modal-title white">Proses Realisasi Penerimaan PP</h4>
                                            <button type="button" class="close" data-dismiss="modal" ria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" id="rls-idpp" name="idpp" readonly>
                                                <input type="hidden" class="form-control" id="rls-norefpp" name="idnoref" readonly>
                                                <input type="hidden" class="form-control" id="rls-nopp" name="nopp" readonly>
                                                <input type="hidden" class="form-control" id="rls-officepp" name="office" readonly>
                                                <input type="hidden" class="form-control" id="rls-deptpp" name="dept" readonly>
                                                <input type="hidden" class="form-control" name="user" value="<?= $usernik; ?>" readonly>
                                                <input type="hidden" class="form-control" name="idspp" value="<?= $arrsp[7]; ?>" readonly>
                                                <label id="rls-labelpp"></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="prosesdata" class="btn btn-outline-dark">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function() {
    
    $('.row-grouping-pembelian').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,
        columnDefs: [
            { "visible": false, "targets": 6 },
        ],
        displayLength: 10,
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(6, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $('.row-grouping-pembelian tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 6 && currentOrder[1] === 'asc' ) {
                table.order( [ 6, 'desc' ] ).draw();
            }
            else {
                table.order( [ 6, 'asc' ] ).draw();
            }
        }
    });

});

$(document).ready(function () {

    var table = $('#detail_barangpp').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#detail_barangpp').on('click', 'td.details-datapp', function () {
        var noref_pp = $(this).attr('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            createChild(row, noref_pp);
            // format(row.child, noref_pp);
            tr.addClass('shown');
        }
    });

    function createChild (row, noref_pp) {
        // This is the table we'll convert into a DataTable
        var table = $('<table class="display" width="100%"/>');
    
        // Display it the child row
        row.child( table ).show();

        $.ajax({
            url:'action/datarequest.php',
            method:"POST",  
            data:{ACTIONDETAILPP:noref_pp},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'KODE BARANG', data: 'KODE_BARANG' },
                    { title: 'NAMA BARANG', data: 'NAMA_BARANG' },
                    { title: 'QTY', data: 'QTY' },
                    { title: 'UNIT COST', data: 'UNIT_COST' },
                    { title: 'SUBTOTAL', data: 'SUB_TOTAL' },
                    { title: 'KETERANGAN', data: 'KETERANGAN' },
                    { title: 'STB', data: 'STATUS_RCV_PP' },
                ],
                order: [[6, 'asc']]
            } );
        })
    }
});

$(document).ready(function(){
    $(document).on('click', '.detail_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        if(nomor_pp != '') {  
            $.ajax({
                url:"action/datarequest.php",
                method:"POST",  
                data:{DETAILPP: nomor_pp},  
                success:function(data){  
                    $('#body_detailpp').html(data);
                    $('#modalDetailPP').modal('show');
                }  
            });
        }
    });
});

$(document).ready(function(){
    $(document).on('click', '.batal_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#del-idpp').val(data.id_pembelian);
                $('#del-nomorpp').val(data.ppid);
                $('#del-refpp').val(data.ref_musnah);
                $('#del-norefpp').val(data.noref);
                
                $('#del-labelpp').html("Batalkan PP Nomor : "+data.ppid);
                $('#modalDeletePP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.cancel_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#cnl-idpp').val(data.id_pembelian);
                $('#cnl-nopp').val(data.ppid);
                $('#cnl-norefpp').val(data.noref);
                
                $('#cnl-labelpp').html("Reject PP Nomor : "+data.ppid);
                $('#modalCancelPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.reject_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#rjt-idpp').val(data.id_pembelian);
                $('#rjt-nopp').val(data.ppid);
                
                $('#rjt-labelpp').html("Reject PP Nomor : "+data.ppid);
                $('#modalRejectPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approve_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#app-idpp').val(data.id_pembelian);
                $('#app-nopp').val(data.ppid);
                $('#app-norefpp').val(data.noref);
                
                $('#app-labelpp').html("Nomor PP : "+data.ppid);
                $('#modalApprovePP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approvehalf_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#aps-idpp').val(data.id_pembelian);
                $('#aps-nopp').val(data.ppid);
                $('#aps-norefpp').val(data.noref);
                
                $('#aps-labelpp').html("Setujui Sebgaian PP Nomor : "+data.ppid);
                $('#modalApproveSebagianPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.approveall_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#apl-idpp').val(data.id_pembelian);
                $('#apl-nopp').val(data.ppid);
                $('#apl-norefpp').val(data.noref);
                
                $('#apl-labelpp').html("Setujui Semua PP Nomor : "+data.ppid);
                $('#modalApproveAllPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.prosespesanan_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#prs-idpp').val(data.id_pembelian);
                $('#prs-nopp').val(data.ppid);
                $('#prs-norefpp').val(data.noref);
                
                $('#prs-labelpp').html("Proses Pesanan PP Nomor : "+data.ppid);
                $('#modalProsesPP').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.realisasi_pp', function(){  
        var nomor_pp = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPP:nomor_pp},  
            dataType:"json",  
            success:function(data){
                $('#rls-idpp').val(data.id_pembelian);
                $('#rls-nopp').val(data.ppid);
                $('#rls-norefpp').val(data.noref);
                $('#rls-officepp').val(data.id_office);
                $('#rls-deptpp').val(data.id_department);
                
                $('#rls-labelpp').html("Nomor PP : "+data.ppid);
                $('#modalRealisasiPP').modal('show');
            }  
        });
    });
});

function changeIcon(anchor) {
    var icon = anchor.querySelector("i");
    var button = anchor.querySelector('button');

    icon.classList.toggle('la-plus');
    icon.classList.toggle('la-minus');

    button.classList.toggle('success');
    button.classList.toggle('danger');
}

</script>

<?php
    include ("includes/templates/alert.php");
?>