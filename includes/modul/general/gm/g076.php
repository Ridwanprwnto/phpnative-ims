<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$_SESSION['WATCHPLG'] = $dec_page;

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertPelanggaranCCTV($_POST) > 0 ){
        $alert = array("Success!", "Data Pelanggaan CCTV Berhasil Ditambahkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdatePelanggaranCCTV($_POST)){
        $datapost = isset($_POST["no-plgupdate"]) ? $_POST["no-plgupdate"] : NULL;
        $alert = array("Success!", "Data Pelanggaan CCTV Nomor ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeletePelanggaranCCTV($_POST)){
        $datapost = isset($_POST["no-plgdelete"]) ? $_POST["no-plgdelete"] : NULL;
        $alert = array("Success!", "Data Pelanggaan CCTV Nomor ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Data Pelanggaran CCTV <?= $id_group == $admin ? "All" : "Bulan ".date("F Y"); ?></h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#entrypelanggaran">Entry Data Pelanggaran</button>
                                    <div class="modal fade text-left" id="entrypelanggaran" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Entry Data Pelanggaran</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-plg" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="office-plg" value="<?= $idoffice; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="dept-plg" value="<?= $iddept; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tanggal Waktu Kejadian : </label>
                                                                <input type="datetime-local" name="tglwkt-plg" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Shift : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="shift-plg" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Bagian / Divisi : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="div-plg" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_div = mysqli_query($conn, "SELECT * FROM divisi");
                                                                    while($data_div = mysqli_fetch_assoc($query_div)) {
                                                                    ?>
                                                                    <option value="<?= $data_div["id_divisi"];?>"><?= $data_div["id_divisi"]." - ".$data_div["divisi_name"];?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Kategori Pelanggaran : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" id="ctg-plg" name="ctg-plg" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_ctg = mysqli_query($conn, "SELECT id_ctg_plg, name_ctg_plg FROM category_pelanggaran");
                                                                    while($data_ctg = mysqli_fetch_assoc($query_ctg)) {
                                                                    ?>
                                                                    <option value="<?= $data_ctg["id_ctg_plg"];?>"><?= $data_ctg["id_ctg_plg"]." - ".$data_ctg["name_ctg_plg"];?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Jenis Pelanggaran : </label>
                                                                <select id="jns-plg" name="jns-plg" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Server CCTV : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" id="server-plg" name="server-plg" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_dvr = mysqli_query($conn, "SELECT A.id_area_cctv, A.kode_area_cctv, A.ip_area_cctv, B.divisi_name FROM area_cctv AS A
                                                                    INNER JOIN divisi AS B ON A.divisi_area_cctv = B.id_divisi
                                                                    WHERE A.office_area_cctv = '$idoffice' AND A.dept_area_cctv = '$iddept' ORDER BY A.kode_area_cctv ASC");
                                                                    while($data_dvr = mysqli_fetch_assoc($query_dvr)) {
                                                                    ?>
                                                                    <option value="<?= $data_dvr["id_area_cctv"];?>"><?= $data_dvr["kode_area_cctv"]." - ".$data_dvr["divisi_name"]." - ".$data_dvr["ip_area_cctv"];?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Lokasi CCTV : </label>
                                                                <select id="cctv-plg" name="cctv-plg" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>User CCTV : </label>
                                                                <select name="user-plg" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                        <option value="<?=$nik;?>" ><?= $nik." - ".strtoupper($username);?></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Kejadian :</label>
                                                                <textarea class="form-control" type="text" name="kejadian-plg" placeholder="Input dan Jelaskan Kejadian Pelanggaran" required></textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" type="text" name="keterangan-plg" placeholder="Input Keterangan (Optional)"></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Follow Up : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="fup-plg" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <!-- <option value="1" >TEGURAN LISAN</option> -->
                                                                    <option value="2" >PEMANGGILAN DENGAN ATASAN YBS</option>
                                                                </select>
                                                            </div>
                                                            <!-- <div class="col-md-12 mb-2">
                                                                <label>Link Video Cloud (Optional) : </label>
                                                                <input type="text" name="link-plg" placeholder="Input link video atas nomor pelanggaran yang sama" class="form-control">
                                                            </div> -->
                                                            <div class="col-md-12 mb-2">
                                                                <label>Upload Video Pelanggaran Max 30Mb : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="record-plg" required>
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="insertdata"
                                                            class="btn btn-outline-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered row-grouping-entrypel">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pelanggaran</th>
                                    <th>Rekaman</th>
                                    <th>Tgl Waktu Kejadian</th>
                                    <th>Shift</th>
                                    <th>Divisi / Bagian</th>
                                    <th>Kategori Pelanggaran</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Area Lokasi CCTV</th>
                                    <th>User CCTV</th>
                                    <th>Status FUP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($id_group == $admin) {
                                $sqltxt = '';
                            }
                            else {
                                $curmounth = date("Y-m");
                                $sqltxt = "AND LEFT(tgl_plg_cctv, 7) = '$curmounth'";
                            }
                            $no = 1;
                            $result = "SELECT A.*, B.id_office, C.id_department, D.id_divisi, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.kode_head_bag_cctv, G.no_lay_cctv, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, K.name_fup_plg FROM pelanggaran_cctv AS A
                            INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
                            INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
                            INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
                            LEFT JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
                            LEFT JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
                            LEFT JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
                            LEFT JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
                            LEFT JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
                            LEFT JOIN users AS J ON A.user_plg_cctv = J.nik
                            LEFT JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
                            WHERE A.office_plg_cctv = '$idoffice' AND A.dept_plg_cctv = '$iddept' ".$sqltxt." ORDER BY A.no_plg_cctv DESC";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><a title="Show Detail Data Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" href="#" data-toggle="tooltip" data-placement="bottom" name="detail_plgcctv" id="<?= $data["no_plg_cctv"].$idoffice.$iddept; ?>" class="text-bold-600 detail_plgcctv"><?= $data['no_plg_cctv']; ?></a></td>
                                    <td>
                                        <a title="<?= $data['rekaman_plg_cctv'] != NULL ? 'Lihat Rekaman Pelanggaran Nomor : '.$data['no_plg_cctv'] : ''; ?>" data-toggle="tooltip" data-placement="bottom" onclick="window.open('', 'popupwindow', 'scrollbars=yes,resizable=yes,width=auto,height=auto');return true" target="popupwindow" href="<?= $data['rekaman_plg_cctv'] != NULL ? "files/record/index.php?id=".encrypt($data['rekaman_plg_cctv']) : '#'; ?>" class="<?= $data['rekaman_plg_cctv'] != NULL ? 'btn btn-icon btn-primary' : ''; ?>"><i class="<?= $data['rekaman_plg_cctv'] != NULL ? 'ft-film' : ''; ?>"></i></a>
                                    </td>
                                    <td><?= $data['tgl_plg_cctv']; ?></td>
                                    <td><?= $data['shift_plg_cctv']; ?></td>
                                    <td><?= $data['divisi_name']; ?></td>
                                    <td>
                                        <h5 class="mb-0">
                                        <span class="text-bold-600"><?= $data['ctg_plg_cctv']; ?></span>
                                        <em></em>
                                        </h5>
                                    </td>
                                    <td><?= $data['jns_plg_cctv']; ?></td>
                                    <td><?= $data['lokasi_plg_cctv']; ?></td>
                                    <td><?= $data['user_plg_cctv']." - ".strtoupper($data['username']); ?></td>
                                    <td>
                                        <?php
                                        if ($data['status_plg_cctv'] == 'S') { ?>
                                        <div class="badge badge-danger">BELUM FUP</div>
                                        <?php }
                                        elseif ($data['status_plg_cctv'] == 'N') { ?>
                                        <div class="badge badge-warning">SUDAH FUP, BELUM APPROVE</div>
                                        <?php }
                                        elseif ($data['status_plg_cctv'] == 'Y') { ?>
                                        <div class="badge badge-success">SUDAH FUP DAN SUDAH APPROVE</div>
                                        <?php }
                                        ?>
                                    </td>
                                    <td>
                                        <span class="dropdown">
                                            <button id="idaction<?= $data['no_plg_cctv']; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="idaction<?= $data['no_plg_cctv']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <?php
                                                if (empty($data['status_plg_cctv']) || $data['status_plg_cctv'] == NULL || $data['status_plg_cctv'] == 'S') { ?>
                                                    <a href="javascript:void(0);" id="<?= $data['id_plg_cctv']; ?>" name="update_plgcctv" title="Update Data Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" class="dropdown-item update_plgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i>Update</a>
                                                    <a href="javascript:void(0);" id="<?= $data['id_plg_cctv']; ?>" name="delete_plgcctv" title="Delete Data Pelanggaran Nomor <?= $data['no_plg_cctv']; ?>" class="dropdown-item delete_plgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-trash"></i>Delete</a>
                                                <?php }
                                                elseif ($data['status_plg_cctv'] == 'N') { ?>
                                                    <a href="javascript:void(0);" id="<?= $data['id_plg_cctv']; ?>" name="update_plgcctv" title="Update Data Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" class="dropdown-item update_plgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i>Update</a>
                                                <?php }
                                                elseif ($data['status_plg_cctv'] == 'Y') { ?>
                                                    <a href="#" id="<?= $data["no_plg_cctv"].$idoffice.$iddept; ?>" name="detail_plgcctv" title="Show Detail Data Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" class="dropdown-item detail_plgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-eye"></i>Detail</a>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Read -->
                            <div class="modal fade text-left" id="readPlgCCTV" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Detail Data Pelanggaran CCTV</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="col-md-6 mb-2">
                                                    <label>Nomor Pelanggaran : </label>
                                                    <input type="text" id="nomplg_detail" name="nomplg_detail" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Divisi / Bagian : </label>
                                                    <input type="text" id="bagplg_detail" name="bagplg_detail" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Tgl Kejadian : </label>
                                                    <input type="text" id="tglplg_detail" name="tglplg_detail" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Waktu Kejadian : </label>
                                                    <input type="text" id="wktplg_detail" name="wktplg_detail" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Kategori Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="ctgplg_detail" name="ctgplg_detail" disabled></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Jenis Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="jnsplg_detail" name="jnsplg_detail" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Server CCTV : </label>
                                                    <input type="text" class="form-control" id="areaplg_detail" name="areaplg_detail" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Lokasi CCTV : </label>
                                                    <input type="text" class="form-control" id="lokplg_detail" name="lokplg_detail" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>User Pelapor : </label>
                                                    <input type="text" class="form-control" id="userplg_detail" name="userplg_detail" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>User Pelanggar : </label>
                                                    <textarea class="form-control" type="text" id="pelplg_detail" name="pelplg_detail" disabled></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updatePlgCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                    <div class="modal-content">
                                        <div class="modal-header bg-secondary white">
                                            <h4 class="modal-title white" id="label-plgupdate"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="page-plgupdate" value="<?= $encpid; ?>" readonly>
                                                <input class="form-control" type="hidden" id="id-plgupdate" name="id-plgupdate" readonly>
                                                <input class="form-control" type="hidden" id="no-plgupdate" name="no-plgupdate" readonly>
                                                <input class="form-control" type="hidden" id="video-plgupdate" name="video-plgupdate" readonly>
                                                <!-- <div class="col-md-12 mb-2">
                                                    <label>Tanggal Waktu Kejadian : </label>
                                                    <input type="text" id="tgl-plgupdate" name="tgl-plgupdate" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tanggal Waktu Kejadian : </label>
                                                    <input type="datetime-local" name="tglwkt-plgupdate" class="form-control" required>
                                                </div> -->
                                                <div class="col-md-12 mb-2">
                                                    <label>Shift : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" id="shift-plgupdate" name="shift-plgupdate" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Bagian / Divisi : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" id="div-plgupdate" name="div-plgupdate" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_div_update = mysqli_query($conn, "SELECT * FROM divisi");
                                                        while($data_div_update = mysqli_fetch_assoc($query_div_update)) {
                                                        ?>
                                                        <option value="<?= $data_div_update["id_divisi"];?>"><?= $data_div_update["id_divisi"]." - ".$data_div_update["divisi_name"];?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Kejadian :</label>
                                                    <textarea class="form-control" type="text" id="kejadian-plgupdate" name="kejadian-plgupdate" placeholder="Input dan Jelaskan Kejadian Pelanggaran" required></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" id="keterangan-plgupdate" name="keterangan-plgupdate" placeholder="Input Keterangan" required></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Edit Video Pelanggaran (Video Max 30Mb) : </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="record-plg">
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn btn-outline-secondary">Update</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deletePlgCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Pelanggaran CCTV</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="id-plgdelete" name="id-plgdelete" readonly>
                                            <input type="hidden" class="form-control" id="no-plgdelete" name="no-plgdelete" readonly>
                                            <input type="hidden" class="form-control" id="rec-plgdelete" name="rec-plgdelete" readonly>
                                            <label id="label-plgdelete"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
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

<script type="text/javascript">
        
    $(document).ready(function() {
        
        $('.row-grouping-entrypel').DataTable({
            responsive: false,
            autoWidth: true,
            rowReorder: false,
            scrollX: true,
            "columnDefs": [
                { "visible": false, "targets": 6 },
            ],
            // "order": [[ 2, 'desc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;

                api.column(6, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="11">'+group+'</td></tr>'
                        );

                        last = group;
                    }
                } );
            }
        } );

        $('.row-grouping-entrypel tbody').on( 'click', 'tr.group', function () {
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
        $("#ctg-plg").on('change', function () {
            var CtgPlg = $('#ctg-plg').val();
            var data = "CATPELANGGARAN=" + CtgPlg;
            if (CtgPlg) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#jns-plg').html(htmlresponse);
                    }
                });
            } else {
                $('#jns-plg').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });

    $(document).ready(function () {
        $("#server-plg").on('change', function () {
            var SrvPlg = $('#server-plg').val();
            var data = "SERVERDVR=" + SrvPlg;
            if (SrvPlg) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: data,
                    success: function (htmlresponse) {
                        $('#cctv-plg').html(htmlresponse);
                    }
                });
            } else {
                $('#cctv-plg').html('<option value="" selected disabled>Please Select</option>');
            }
        });
    });

    $(document).ready(function(){
        $(document).on('click', '.detail_plgcctv', function(){  
            var nomor_plg = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{DETAILPLGCCTV:nomor_plg},  
                dataType:"json",  
                success:function(data){
                    $('#nomplg_detail').val(data.no_plg_cctv);
                    $('#bagplg_detail').val(data.divisi_name);
                    $('#tglplg_detail').val(data.data_tgl_plg);
                    $('#wktplg_detail').val(data.data_wkt_plg);
                    $('#ctgplg_detail').val(data.ctg_plg_cctv);
                    $('#jnsplg_detail').val(data.jns_plg_cctv);
                    $('#areaplg_detail').val(data.dvr_plg_cctv);
                    $('#lokplg_detail').val(data.lokasi_plg_cctv);
                    $('#userplg_detail').val(data.user_plg_cctv+' - '+data.username.toUpperCase());
                    $('#pelplg_detail').val(data.tersangka_plg_cctv);
                    $('#readPlgCCTV').modal('show');
                }  
            });
        });
    });

    $(document).ready(function(){
        $(document).on('click', '.update_plgcctv', function(){  
            var nomor_plg = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{UPDATEPLGCCTV:nomor_plg},  
                dataType:"json",  
                success:function(data){
                    $('#id-plgupdate').val(data.id_plg_cctv);
                    $('#no-plgupdate').val(data.no_plg_cctv);
                    // $('#tgl-plgupdate').val(data.tgl_plg_cctv);
                    $('#video-plgupdate').val(data.rekaman_plg_cctv);
                    $('#kejadian-plgupdate').val(data.kejadian_plg_cctv);
                    $('#keterangan-plgupdate').val(data.ket_plg_cctv);

                    $('#label-plgupdate').html("Update Pelanggaran CCTV Nomor : "+data.no_plg_cctv);

                    $('#shift-plgupdate').find('option[value="'+data.shift_plg_cctv+'"]').remove();
                    $('#shift-plgupdate').append($('<option></option>').html(data.shift_plg_cctv).attr('value', data.shift_plg_cctv).prop('selected', true));
                    $('#div-plgupdate').find('option[value="'+data.div_plg_cctv+'"]').remove();
                    $('#div-plgupdate').append($('<option></option>').html(data.div_plg_cctv+" - "+data.divisi_name).attr('value', data.id_divisi).prop('selected', true));
                    
                    $('#updatePlgCCTV').modal('show');
                }  
            });
        });
    });
    
    $(document).ready(function(){
        $(document).on('click', '.delete_plgcctv', function(){  
            var nomor_plg = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{DELETEPLGCCTV:nomor_plg},  
                dataType:"json",  
                success:function(data){
                    $('#id-plgdelete').val(data.id_plg_cctv);
                    $('#no-plgdelete').val(data.no_plg_cctv);
                    $('#rec-plgdelete').val(data.rekaman_plg_cctv);

                    $('#label-plgdelete').html("Apakah ingin membatalkan Pelanggaran CCTV Nomor : "+data.no_plg_cctv);
                    
                    $('#deletePlgCCTV').modal('show');
                }  
            });
        });
    });

</script>

<?php
    include ("includes/templates/alert.php");
?>