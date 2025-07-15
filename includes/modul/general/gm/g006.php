<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(InsertSTHH($_POST) > 0 ){
        $alert = array("Success!", "Berhasil Proses Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateSTHH($_POST) > 0 ){
        $alert = array("Success!", "Berhasil Terima Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatacheck"])){
    if(UpdateCheckSTHH($_POST) > 0 ){
        $alert = array("Success!", "Berhasil Terima Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteSTHH($_POST)){
        $alert = array("Success!", "Berhasil Hapus Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatacheck"])){
    if(DeleteCheckSTHH($_POST)){
        $alert = array("Success!", "Berhasil Hapus Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["receivedata"])){
    if(UpdateReceiveSTHH($_POST) > 0 ){
        $alert = array("Success!", "Berhasil Terima Data STHH", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}

$query_sthhnull = mysqli_query($conn, "SELECT datein, penerima, pengembali FROM sthh WHERE id_office = '$idoffice' AND id_department = '$iddept' AND datein IS NULL AND penerima IS NULL AND pengembali IS NULL");
$data_sthhnull = mysqli_fetch_assoc($query_sthhnull);

?>
<!-- Basic form layout section start -->
<section id="basic-select2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Serah Terima Handheld</h4>
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
                                <button type="button" class="btn btn-primary btn-min-width ml-1" data-toggle="modal" data-target="#entryuser">Entry Data</button>
                                <button type="button" class="btn btn-success btn-min-width ml-1" data-toggle="modal" data-target="#receiveuser">Receive Data</button>
                                    <div class="modal fade text-left" id="entryuser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data Peminjaman Handheld</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page_pinjam" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input class="form-control" type="hidden" name="officeid" id="officeid" value="<?= $idoffice ?>">
                                                            <input class="form-control" type="hidden" name="deptid" id="deptid" value="<?= $iddept ?>">
                                                            <input type="hidden" name="id_pinjam" value="<?= autonum(6, "no_pinjam", "sthh"); ?>" class="form-control">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor - SN Handheld : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="hhpinjam[]">
                                                                    <?php
                                                                        $sql_hh = "SELECT A.pluid, A.no_lambung, A.sn_barang, B.IDJenis, B.NamaJenis, C.IDBarang, C.NamaBarang FROM barang_assets AS A
                                                                        INNER JOIN masterjenis AS B ON RIGHT(A.pluid, 4) = B.IDJenis
                                                                        INNER JOIN mastercategory AS C ON LEFT(A.pluid, 6) = C.IDBarang
                                                                        WHERE A.ba_id_office = '$idoffice' AND A.ba_id_department = '$iddept' AND A.pluid LIKE 'A01933%' AND LENGTH(A.no_lambung) = 5 ORDER BY A.no_lambung ASC";
                                                                        $query_hh = mysqli_query($conn, $sql_hh);
                                                                        while($data_hh = mysqli_fetch_array($query_hh)) {
                                                                    ?>
                                                                        <option value="<?= $data_hh['no_lambung']."-".$data_hh['sn_barang'];?>"><?= $data_hh['no_lambung']." - ".$data_hh['sn_barang']; ?></option>
                                                                    <?php
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor - SN Battery : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="hhpinjam[]">
                                                                    <?php
                                                                        $sql_bt = "SELECT A.pluid, A.no_lambung, A.sn_barang, B.IDJenis, B.NamaJenis, C.IDBarang, C.NamaBarang FROM barang_assets AS A
                                                                        INNER JOIN masterjenis AS B ON RIGHT(A.pluid, 4) = B.IDJenis
                                                                        INNER JOIN mastercategory AS C ON LEFT(A.pluid, 6) = C.IDBarang
                                                                        WHERE A.ba_id_office = '$idoffice' AND A.ba_id_department = '$iddept' AND A.pluid LIKE 'B01931%' AND LENGTH(A.no_lambung) = 5 ORDER BY A.no_lambung ASC";
                                                                        $query_bt = mysqli_query($conn, $sql_bt);
                                                                        while($data_bt = mysqli_fetch_array($query_bt)) {
                                                                    ?>
                                                                        <option value="<?= $data_bt['no_lambung']."-".$data_bt['sn_barang'];?>"><?= $data_bt['no_lambung']." - ".$data_bt['sn_barang']; ?></option>
                                                                    <?php
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor - SN Handy Talky : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="hhpinjam[]">
                                                                    <?php
                                                                        $sql_ht = "SELECT A.pluid, A.no_lambung, A.sn_barang, B.IDJenis, B.NamaJenis, C.IDBarang, C.NamaBarang FROM barang_assets AS A
                                                                        INNER JOIN masterjenis AS B ON RIGHT(A.pluid, 4) = B.IDJenis
                                                                        INNER JOIN mastercategory AS C ON LEFT(A.pluid, 6) = C.IDBarang
                                                                        WHERE A.ba_id_office = '$idoffice' AND A.ba_id_department = '$iddept' AND A.pluid LIKE 'A01986%' AND LENGTH(A.no_lambung) = 5 ORDER BY A.no_lambung ASC";
                                                                        $query_ht = mysqli_query($conn, $sql_ht);
                                                                        while($data_ht = mysqli_fetch_array($query_ht)) {
                                                                    ?>
                                                                        <option value="<?= $data_ht['no_lambung']."-".$data_ht['sn_barang'];?>"><?= $data_ht['no_lambung']." - ".$data_ht['sn_barang']; ?></option>
                                                                    <?php
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Divisi / Bagian : </label>
                                                                <select id="bag-divisi" name="bag-divisi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query = mysqli_query($conn, "SELECT * FROM divisi");
                                                                        while($data_div = mysqli_fetch_assoc($query)) { ?>
                                                                        <option value="<?= $data_div['id_divisi'].$data_div['divisi_name'] ;?>" ><?= $data_div['divisi_name'];?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Sub Divisi : </label>
                                                                <select id="sub-divisi" name="sub-divisi" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>PIC Support : </label>
                                                                <select id="pic" name="pic" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                        <option value="<?=$username;?>" ><?= strtoupper($username);?></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Peminjam : </label>
                                                                <select name="peminjam" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_user_p = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01'");
                                                                        while($data_user_p = mysqli_fetch_assoc($query_user_p)) { ?>
                                                                        <option value="<?= $data_user_p['nik']." - ".strtoupper($data_user_p['username']);?>" ><?= $data_user_p['nik']." - ".strtoupper($data_user_p['username']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" type="text" name="ket" placeholder="Input keterangan (Optional)"></textarea>
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
                                    <div class="modal fade text-left" id="receiveuser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="myModalLabel">Receive Data Peminjaman Handheld</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page-updreceive" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <input class="form-control" type="hidden" name="officeid" id="officeid" value="<?= $idoffice ?>">
                                                            <input class="form-control" type="hidden" name="deptid" id="deptid" value="<?= $iddept ?>">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor - SN Barang : </label>
                                                                <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="hhreceive[]">
                                                                    <?php
                                                                       $sql_hh = "SELECT A.* FROM sthh AS A
                                                                       WHERE A.id_office = '$idoffice' AND A.id_department = '$iddept' AND A.datein IS NULL AND A.penerima IS NULL ORDER BY A.pluid ASC";
                                                                        $query_hh = mysqli_query($conn, $sql_hh);
                                                                        while($data_hh = mysqli_fetch_array($query_hh)) {
                                                                    ?>
                                                                        <option value="<?= $data_hh['pluid'];?>"><?= $data_hh['pluid']; ?></option>
                                                                    <?php
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Penerima : </label>
                                                                <select id="pic_receive" name="pic_receive" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                        <option value="<?=$username;?>" ><?= strtoupper($username);?></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Pengembali : </label>
                                                                <select name="peminjam_receive" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_user_p = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01'");
                                                                        while($data_user_p = mysqli_fetch_assoc($query_user_p)) { ?>
                                                                        <option value="<?= $data_user_p['nik']." - ".strtoupper($data_user_p['username']);?>" ><?= $data_user_p['nik']." - ".strtoupper($data_user_p['username']);?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" type="text" name="ket_receive" placeholder="Input keterangan (Optional)"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="receivedata" class="btn btn-outline-success">Receive</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        </div>
                        <form action="" method="post">
                        <table class="table display nowrap table-striped table-bordered zero-configuration text-center" id="table_sthh">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DOCNO</th>
                                    <th>DATEOUT</th>
                                    <th>UNIT</th>
                                    <th>NOMOR - SN</th>
                                    <th>PIC</th>
                                    <th>PEMINJAM</th>
                                    <th>DIVISI</th>
                                    <th>ACTION</th>
                                    <th class="icheck1">
                                        <input type="checkbox" id="checkall" class="checkall">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $result = "SELECT A.* FROM sthh AS A
                                WHERE A.id_office = '$idoffice' AND A.id_department = '$iddept' AND A.datein IS NULL AND A.penerima IS NULL ORDER BY A.no_pinjam DESC";
                                $query = mysqli_query($conn, $result);
                                while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><a title="Show Detail Data STHH Nomor <?= substr($data['pluid'], 0, 5); ?>" href="#" id="<?= $data['id_sthh']; ?>" name="detail_sthh" class="text-bold-600 detail_sthh" data-toggle="tooltip" data-placement="bottom"><?= $data['no_pinjam']; ?></a></td>
                                    <td><?= $data['dateout']." ".$data['jamkeluar'] ; ?></td>
                                    <td>
                                        <?php 
                                            if (substr($data['pluid'], 0, 2) == 'HH') {
                                                echo "HANDHELD";
                                            }
                                            elseif (substr($data['pluid'], 0, 2) == 'BT') {
                                                echo "BATRE HH";
                                            }
                                            
                                            elseif (substr($data['pluid'], 0, 2) == 'HT') {
                                                echo "HANDY TALKY";
                                            }
                                        ?>
                                    </td>
                                    <td><strong><?= $data['pluid']; ?></strong></td>
                                    <td><?= strtoupper($data["pic"]);?></td>
                                    <td><?= $data['nik']; ?></td>
                                    <td><?= $data['id_divisi']." - ".$data['id_sub_divisi']; ?></td>
                                    <td>
                                        <button title="Receive Data STHH Nomor <?= substr($data['pluid'], 0, 5); ?>" type="button" id="<?= $data['id_sthh']; ?>" name="receive_sthh" class="btn btn-icon btn-success receive_sthh" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        <button title="Cancel Data STHH Nomor <?= substr($data['pluid'], 0, 5); ?>" type="button" id="<?= $data['id_sthh']; ?>" name="delete_sthh" class="btn btn-icon btn-danger delete_sthh" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                    <td class="icheck1">
                                        <input type="checkbox" name="checkiddata[]" id="checkiddata" class="checkiddata" value="<?= $data['id_sthh']; ?>">
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Read -->
                            <div class="modal fade text-left" id="readModalSTHH" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white" id="red-labelsthh"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="col-md-6 mb-2">
                                                    <label>PIC : </label>
                                                    <input type="text" class="form-control" id="red-picsthh" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Peminjam : </label>
                                                    <input type="text" class="form-control" id="red-pinjamsthh" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Bagian : </label>
                                                    <input type="text" class="form-control" id="red-bagsthh" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Sub Divisi : </label>
                                                    <input type="text" class="form-control" id="red-divsthh" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Jam Keluar : </label>
                                                    <input type="text" class="form-control" id="red-jamsthh" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea type="text" class="form-control" id="red-ketsthh" disabled></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalSTHH" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="upd-labelsthh"></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" id="upd-idsthh" name="idsthh" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Pengembali : </label>
                                                        <select name="pengembali" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01'");
                                                                while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                                <option value="<?= $data_user['nik']." - ".strtoupper($data_user['username']);?>" ><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Penerima : </label>
                                                        <select name="penerima" class="select2 form-control block" style="width: 100%" type="text" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <option value="<?= $username;?>" ><?= strtoupper($username);?></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Keterangan :</label>
                                                        <textarea class="form-control" type="text" name="keterangan" placeholder="Input keterangan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="updatedata" class="btn btn-outline-success">Receive</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalSTHH" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input class="form-control" type="hidden" id="del-idsthh" name="del-idsthh" readonly>
                                            <label id="del-labelsthh"></label>
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
                            <!-- Modal Update By Check -->
                            <div class="modal fade text-left" id="updatecheckdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <!-- <form action="" method="post"> -->
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel">Penerimaan Data By Check</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="page-updcheck" value="<?= $encpid; ?>" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Pengembali : </label>
                                                    <select name="pengembalicheck" class="select2 form-control block" style="width: 100%" type="text">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_user_chk = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01'");
                                                            while($data_user_chk = mysqli_fetch_assoc($query_user_chk)) { ?>
                                                            <option value="<?= $data_user_chk['nik']." - ".strtoupper($data_user_chk['username']);?>" ><?= $data_user_chk['nik']." - ".strtoupper($data_user_chk['username']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Penerima : </label>
                                                    <select name="penerimacheck" class="select2 form-control block" style="width: 100%" type="text">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="<?= $username;?>" ><?= strtoupper($username);?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" name="keterangan" placeholder="Input keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedatacheck" onclick="return validateForm();" class="btn btn-outline-primary">Receive</button>
                                        </div>
                                    <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete By Check -->
                            <div class="modal fade text-left" id="deletecheckdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <!-- <form action="" method="post"> -->
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel">Delete Data By Check</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <label>Are you sure to delete the selected data?</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletedatacheck" onclick="return validateForm();" class="btn btn-outline-danger">Delete</button>
                                        </div>
                                    <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                        </form>
                        <!-- Button dropdowns with icons -->
                        <div class="btn-group mt-1 mr-1 mb-2 pull-right">
                            <button type="button" title="Action With Checkbox" class="btn btn-<?= isset($data_sthhnull) ? 'primary' : 'secondary'; ?> btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?= isset($data_sthhnull) ? '' : 'Disabled'; ?>>Checkbox Action</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" data-target="#updatecheckdata" href="#" title="Receive Data With Checkbox">Receive</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#deletecheckdata" href="#" title="Delete Data With Checkbox">Delete</a>
                            </div>
                        </div>
                        <!-- /btn-group -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script type="text/javascript">
    
$(document).ready(function(){
    $('#table_sthh').DataTable({
        info: true,
        searching: false,
        ordering: true,
        paging: false,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        scrollY: '50vh'
    });
});

$(document).ready(function () {
    $("#bag-divisi").on('change', function () {
        var HeadDivisi = $('#bag-divisi').val();
        var data = "HEADIV=" + HeadDivisi;
        if (HeadDivisi) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success: function (htmlresponse) {
                    $('#sub-divisi').html(htmlresponse);
                }
            });
        } else {
            $('#sub-divisi').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function() {
    // check / uncheck all
    var checkAll = $('input#checkall');
    var checkboxes = $('input[name="checkiddata[]"]');

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', true);
        } 
        else {
            checkAll.prop('checked', false);
        }
        checkAll.iCheck('update');
    });
});

function validateForm() {
    var count_checked = $('input[name="checkiddata[]"]:checked').length;
    if (count_checked == 0) {
        alert("Please check at least one checkbox");
        return false;
    } else {
        return true;
    }
}

$(document).ready(function(){
    $(document).on('click', '.detail_sthh', function(){  
        var id_hh = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONSTHH:id_hh},  
            dataType:"json",  
            success:function(data){
                $('#red-picsthh').val(data.pic.toUpperCase());
                $('#red-pinjamsthh').val(data.nik);
                $('#red-bagsthh').val(data.id_divisi);
                $('#red-divsthh').val(data.id_sub_divisi);
                $('#red-jamsthh').val(data.tgl_keluar);
                $('#red-ketsthh').val(data.keterangan);
                
                $('#red-labelsthh').html("Detail Data STHH Nomor : "+data.nomor_sthh);
                $('#readModalSTHH').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.receive_sthh', function(){  
        var id_hh = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONSTHH:id_hh},  
            dataType:"json",  
            success:function(data){
                $('#upd-idsthh').val(data.id_sthh);
                
                $('#upd-labelsthh').html("Receive Data STHH Nomor : "+data.nomor_sthh);
                $('#updateModalSTHH').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_sthh', function(){  
        var id_hh = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONSTHH:id_hh},  
            dataType:"json",  
            success:function(data){
                $('#del-idsthh').val(data.id_sthh);
                
                $('#del-labelsthh').html("Cancel Data STHH Nomor : "+data.nomor_sthh);
                $('#deleteModalSTHH').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>