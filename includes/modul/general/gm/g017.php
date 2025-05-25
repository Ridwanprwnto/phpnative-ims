<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdata"])){
    if(InsertBarangPembelian($_POST) > 0 ){
        $datapost = isset($_POST["btbno"]) ? $_POST["btbno"] : NULL;
        $alert = array("Success!", "Data barang ref pembelian ".$datapost." berhasil di tambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdatakhusus"])){
    if(InsertBarangKhusus($_POST) > 0 ){
        $alert = array("Success!", "Data barang ref khusus berhasil di tambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["importdatakhusus"])){
    if(ImportBarangKhusus($_POST) > 0 ){
        $alert = array("Success!", "Data barang ref khusus berhasil di tambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(UpdateBarangAssets($_POST) > 0 ){
        $datapost = isset($_POST["sn-msbarang"]) ? $_POST["sn-msbarang"] : NULL;
        $alert = array("Success!", "Data barang inventaris SN ".$datapost." berhasil di update", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteBarangAssets($_POST) > 0 ){
        $alert = array("Success!", "Data barang inventaris berhasil di hapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatebrgcheckdata"])){
    if(UpdateCheckBarang($_POST) > 0 ){
        $alert = array("Success!", "Data barang inventaris berhasil di update", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletebrgcheckdata"])){
    if(DeleteCheckBarang($_POST) > 0 ){
        $alert = array("Success!", "Data barang inventaris berhasil di delete", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Entry Barang Inventaris</h4>
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

                        <!-- Button dropdowns with icons -->
                        <div class="btn-group mr-1">
                            <button type="button" class="btn btn-info btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Entry Barang Inventaris</button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" data-toggle="modal" data-target="#entrybarang-pembelian" href="#">Pembelian</a>
                                <?php if ($id_group == $admin || $id_group == $support) { ?>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" data-toggle="modal" data-target="#entrybarang-khusus" href="#">Khusus</a>
                                <?php } ?>
                            </div>
                        </div>
                          <!-- /btn-group -->

                        <!-- Button dropdowns with icons -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Upload Barang Inventaris</button>
                            <div class="dropdown-menu">
                                <?php if ($id_group == $admin || $id_group == $support) { ?>
                                <a class="dropdown-item" href="reporting/report-upload-baranginv.php?id=<?= encrypt('MSBRGINV');?>" target="_blank">Download CSV File</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#entrybarang-import" href="#">Upload CSV File</a>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- /btn-group -->

                          <!-- Import Modal -->
                          <div class="modal fade text-left" id="entrybarang-import" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post" enctype="multipart/form-data" role="form">
                                        <div class="modal-header bg-warning white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Upload Master Barang Inventaris Khusus</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-import" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-import" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-import" value="<?= $arrmodifref[2]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-import" value="<?= $idoffice;?>" class="form-control" readonly>
                                                <input type="hidden" name="dept-import" value="<?= $iddept;?>" class="form-control" readonly>
                                                <input type="hidden" name="user-import" value="<?= $usernik;?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>File : </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="file-import" required>
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan : </label>
                                                    <textarea class="form-control" type="text" name="ket-import" placeholder="Input Keterangan" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="importdatakhusus"
                                                class="btn btn-outline-warning">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                        <!-- Modal Entry PP -->
                        <div class="modal fade text-left" id="entrybarang-pembelian">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header bg-info white">
                                        <h4 class="modal-title white">Entry Master Barang Inventaris Melalui Pembelian</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="user-brgpp" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="page-brgpp" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-brgpp" value="<?= $arrmodifref[1]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="kondisi" id="kondisi" value="<?= $arrcond[1];?>" class="form-control" readonly>
                                                <div class="col-md-4 mb-2">
                                                    <label>NO BTB :</label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="btbno" id="btbno">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_btb = mysqli_query($conn, "SELECT id_penerimaan FROM penerimaan_pembelian WHERE office_penerimaan = '$idoffice' AND dept_penerimaan = '$iddept' ORDER BY id_penerimaan DESC");
                                                            while($data_btb = mysqli_fetch_assoc($query_btb)) { ?>
                                                            <option value="<?= $data_btb['id_penerimaan'];?>" ><?= substr($data_btb['id_penerimaan'], 1); ?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-8 mb-2">
                                                    <label>Nama Barang :</label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="datapluid" id="datapluid">
                                                        <option value="">Please Select</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label id="table-insbarang-pp">Data Barang :</label>
                                                    <table class="table table-striped text-center" id="table-insbarang-pp">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Merk Barang</th>
                                                                <th>Tipe Barang</th>
                                                                <th>Serial Number</th>
                                                                <th>Nomor Aktiva</th>
                                                                <th>Nomor Lambung</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="insertdata" class="btn btn-outline-info">Save</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <!-- Modal Entry Baru -->
                        <div class="modal fade text-left" id="entrybarang-khusus">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header bg-info white">
                                        <h4 class="modal-title white">Entry Master Barang Inventaris Khusus</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input type="hidden" name="page-baru" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-baru" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-baru" value="<?= $arrmodifref[2]; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-baru" value="<?= $idoffice;?>" class="form-control" readonly>
                                                <input type="hidden" name="dept-baru" value="<?= $iddept;?>" class="form-control" readonly>
                                                <table class="table table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">KODE - NAMA BARANG</th>
                                                        <th scope="col">MERK BARANG</th>
                                                        <th scope="col">TIPE BARANG</th>
                                                        <th scope="col">SERIAL NUMBER</th>
                                                        <th scope="col">NOMOR AKTIVA</th>
                                                        <th scope="col">NOMOR LABEL</th>
                                                        <th scope="col">KONDISI</th>
                                                        <th><button type="button" name="add_barang_khusus" class="btn btn-success btn-xs add_barang_khusus"><i class="ft-plus"></i></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-barang-khusus">
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" name="insertdatakhusus"
                                            class="btn btn-outline-info">Save</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <p>Filter Data Barang</p>
                                <select class="select2 form-control block" style="width: 100%" type="text" name="barang-src" id="barang-src">
                                    <option value="" selected disabled>Please Select</option>
                                    <?php 
                                        $q_brgsrc = mysqli_query($conn, "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
                                        INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
                                        INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis 
                                        WHERE LEFT(A.dat_asset, 4) = '$idoffice' AND RIGHT(A.dat_asset, 4) = '$iddept' GROUP BY C.IDJenis ASC
                                        ");
                                        while($d_brgsrc = mysqli_fetch_assoc($q_brgsrc)) { ?>
                                            <option value="<?= $id_group.$idoffice.$iddept.$d_brgsrc['pluid']; ?>"> <?= $d_brgsrc['pluid']." - ".$d_brgsrc['NamaBarang']." ".$d_brgsrc['NamaJenis'];?>
                                        </option>
                                        <?php 
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <p>Search</p>
                                <input type="text" name="keyword-src" id="keyword-src" class="form-control" placeholder="Keyword">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form action="" method="post">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA BARANG</th>
                                        <th>SERIAL NUMBER</th>
                                        <th>NO AKTIVA</th>
                                        <th>NO LAMBUNG</th>
                                        <th>AKSI</th>
                                        <th>CHECK</th>
                                    </tr>
                                </thead>
                                <tbody class="datatable-src">
                                </tbody>
                                <!-- Modal Read -->
                                <div class="modal fade text-left" id="dataModalRead" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info white">
                                                <h4 class="modal-title white">Detail Data Barang</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row" id="modal_readdata">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Update -->
                                <div class="modal fade text-left" id="dataModalUpdate" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="label-msbarang"></h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" name="page-msbarang" value="<?= $redirect; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="user-msbarang" value="<?= $usernik; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="modifref-msbarang" value="<?= $arrmodifref[0]; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="id-msbarang" id="id-msbarang" class="form-control" readonly>
                                                    <input type="hidden" name="office-msbarang" id="office-msbarang" class="form-control" readonly>
                                                    <input type="hidden" name="dept-msbarang" id="dept-msbarang" class="form-control" readonly>
                                                    <input type="hidden" name="barang-msbarang" id="barang-msbarang" class="form-control" readonly>
                                                    <input type="hidden" name="kondisiold-msbarang" id="kondisiold-msbarang" class="form-control" readonly>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Merk : </label>
                                                        <input type="text" name="merk-msbarang" id="merk-msbarang" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Type / Model : </label>
                                                        <input type="text" name="type-msbarang" id="type-msbarang" class="form-control">
                                                    </div>
                                                    <?php if ($id_group == $admin || $id_group == $support) { ?>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Serial Number : </label>
                                                        <input type="text" name="sn-msbarang" id="sn-msbarang" class="form-control" required>
                                                    </div>
                                                    <?php } else { ?>
                                                        <input type="hidden" name="sn-msbarang" id="sn-msbarang" class="form-control">
                                                    <?php } ?>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Nomor Aktiva : </label>
                                                        <input type="text" name="dat-msbarang" id="dat-msbarang" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label>Nomor Lambung : </label>
                                                        <input type="text" name="no-msbarang" id="no-msbarang" class="form-control">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Kondisi Barang : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" id="kondisidis-msbarang" name="kondisi-msbarang">
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $query_kon_brg = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi NOT LIKE '$arrcond[5]'");
                                                                while($data_kon_brg = mysqli_fetch_assoc($query_kon_brg)) { ?>
                                                                <option value="<?= $data_kon_brg['id_kondisi'];?>"><?= $data_kon_brg['id_kondisi']." - ".$data_kon_brg['kondisi_name'];?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Posisi / Penempatan : </label>
                                                        <textarea class="form-control" type="text" name="posisi-msbarang" id="posisi-msbarang" placeholder="Posisi / Penempatan Barang"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="updatedata" class="btn btn-outline-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Delete -->
                                <div class="modal fade text-left" id="dataModalDelete" role="dialog" aria-hidden="true">
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
                                                <input class="form-control" type="hidden" name="del-page" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" name="del-barang" id="del-barang">
                                                <input class="form-control" type="hidden" name="del-noref" id="del-noref">
                                                <input class="form-control" type="hidden" name="del-pluid" id="del-pluid">
                                                <input class="form-control" type="hidden" name="del-noseri" id="del-noseri">
                                                <input class="form-control" type="hidden" name="del-nodat" id="del-nodat">
                                                <input class="form-control" type="hidden" name="del-office" id="del-office">
                                                <input class="form-control" type="hidden" name="del-dept" id="del-dept">
                                                <label id="del-label"></label>
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
                                <div class="modal fade text-left" id="updatebrgcheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                        <!-- <form action="" method="post"> -->
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white"
                                                    id="myModalLabel">Update Data Barang By Checkbox</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="page-chkbarang" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="user-chkbarang" value="<?= $usernik; ?>" class="form-control" readonly>
                                                <input type="hidden" name="modifref-chkbarang" value="<?= $arrmodifref[0]; ?>" class="form-control" readonly>
                                                <div class="form-row" id="table-edtbarang-check">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="updatebrgcheckdata" class="btn btn-outline-primary">Update</button>
                                            </div>
                                        <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Update By Check -->
                                <div class="modal fade text-left" id="deletebrgcheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                        <!-- <form action="" method="post"> -->
                                            <div class="modal-header bg-danger white">
                                                <h4 class="modal-title white"
                                                    id="myModalLabel">Delete Data Barang By Checkbox</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row" id="table-dltbarang-check">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="deletebrgcheckdata" class="btn btn-outline-danger">Delete</button>
                                            </div>
                                        <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </table>
                            </form>
                            <!-- <button type="button" title="Edit With Checkbox" onclick="return validateForm();" class="btn btn-primary btn-min-width mt-1 mb-2 pull-right">Edit By Checkbox</button> -->
                            <!-- Button dropdowns with icons -->
                            <div class="btn-group mt-1 mb-2 pull-right">
                                <button type="button" class="btn btn-primary btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Update By Checkbox</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="return validateForm('EDIT');" href="#">Update Barang</a>
                                    <?php if ($id_group == $admin) { ?>
                                <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="return validateForm('DELETE');" href="#">Delete Barang</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- /btn-group -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function() {
    $("#btbno").on('change', function() {
        var btbno = $('#btbno').val();
        var data = "penerimaan="+btbno;
        if(btbno){
            $.ajax ({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success : function(htmlresponse) {
                    $('#datapluid').html(htmlresponse);
                    $('#table-insbarang-pp tbody').html("");
                }
            });
        }
        else {
            $('#datapluid').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function(){

    $("input[name=btbno],select[name=datapluid]").on('change', function(){
        var noBTB = $('#btbno').val();
        var pluID = $('#datapluid').val();
        if(noBTB && pluID) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {NOBTB:noBTB, PLUID:pluID},
                beforeSend: function() {
                    if($(document).find('#loadbrgpp-spinner').length > 0) {
                        $(document).find('#loadbrgpp-spinner').remove();
                    }
                    $('#table-insbarang-pp tbody').append('<tr><td colspan="6"><i id="loadbrgpp-spinner" class="la la-spinner spinner"></i></td></tr>');
                },
                success:function(data){
                    if (data.length > 0) {
                        $('#table-insbarang-pp tbody').html(data);
                    }
                }
            });
        }
    });

    $(document).on('click', '.remove_brg_btb', function(){
        $(this).closest('tr').remove();
    });

});


$(document).ready(function(){

    var count = 0;

    $(document).on('click', '.add_barang_khusus', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="desc_barang_khusus[]" class="select2 form-control block desc_barang_khusus" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_pp(); ?></select></td>';
        html += '<td><input type="text" name="merk_barang_khusus[]" class="form-control merk_barang_khusus" placeholder="Input Merk Barang (Optional)"/></td>';
        html += '<td><input type="text" name="tipe_barang_khusus[]" class="form-control tipe_barang_khusus" placeholder="Input Tipe Barang (Optional)"/></td>';
        html += '<td><input type="text" name="sn_barang_khusus[]" class="form-control sn_barang_khusus" placeholder="Input Serial Number" required/></td>';
        html += '<td><input type="text" name="at_barang_khusus[]" class="form-control at_barang_khusus" placeholder="Input Nomor Aktiva" required/></td>';
        html += '<td><input type="text" name="no_barang_khusus[]" class="form-control no_barang_khusus" placeholder="Input Nomor Lambung (Optional)"/></td>';
        html += '<td><select type="text" name="kondisi_barang_khusus[]" class="select2 form-control block kondisi_barang_khusus" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_kondisi($arrcond[5]); ?></select></td>';
        html += '<td><button type="button" name="remove_barang_khusus" class="btn btn-danger btn-xs remove_barang_khusus"><i class="ft-minus"></i></button></td>';
        $('#table-barang-khusus').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_barang_khusus', function(){
        $(this).closest('tr').remove();
    });
});

$(document).ready(function(){
    load_data();
    function load_data(barang, keyword) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: {BARANGSRC: barang, KEYSRC:keyword},
            beforeSend: function() {
                hideSpinner();
                showSpinner();
            },
            success:function(hasil) {
                $('.datatable-src').html(hasil);
            },
            complete: function() {
                $('.icheck1 input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });
            }
        });
    }
    $('#keyword-src').keyup(function(){
        var barang = $("#barang-src").val();
        var keyword = $("#keyword-src").val();
        load_data(barang, keyword);
    });
    $('#barang-src').change(function(){
        $("#keyword-src").val('');
        var barang = $("#barang-src").val();
        var keyword = $("#keyword-src").val();
        load_data(barang, keyword);
    });
    function hideSpinner() {
        $('.datatable-src').html("");
        if($(document).find('#loadbarang-spinner').length > 0) {
            $(document).find('#loadbarang-spinner').remove();
        }
    }
    function showSpinner() {
        $('.datatable-src').append('<tr><td colspan="6"><i id="loadbarang-spinner" class="la la-spinner spinner"></i></td></tr>');
    }
});

$(document).ready(function(){
    $(document).on('click', '.read_data', function(){  
        var barang_id = $(this).attr("id");  
        if(barang_id != '') {  
            $.ajax({
                url:"action/datarequest.php",
                method:"POST",  
                data:{READMODAL: barang_id},  
                success:function(data){  
                    $('#modal_readdata').html(data);
                    $('#dataModalRead').modal('show');
                }  
            });
        }
    });
});


$(document).ready(function(){
    $(document).on('click', '.update_data', function(){  
        var barang_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEMODAL:barang_id},  
            dataType:"json",  
            success:function(data){
                $('#id-msbarang').val(data.id_ba);
                $('#office-msbarang').val(data.office_asset);
                $('#dept-msbarang').val(data.dept_asset);
                $('#barang-msbarang').val(data.pluid);
                $('#merk-msbarang').val(data.ba_merk);
                $('#type-msbarang').val(data.ba_tipe);
                $('#sn-msbarang').val(data.sn_barang);
                $('#dat-msbarang').val(data.no_at);
                $('#no-msbarang').val(data.no_lambung);
                $('#kondisiold-msbarang').val(data.kondisi);
                $('#posisi-msbarang').val(data.posisi);

                $('#kondisidis-msbarang').find('option[value="'+data.kondisi+'"]').remove();

                $('#kondisidis-msbarang').append($('<option></option>').html(data.kondisi+" - "+data.kondisi_name).attr('value', data.kondisi).prop('selected', true));

                $('#label-msbarang').html("Edit Data Barang SN : "+data.sn_barang);

                $('#dataModalUpdate').modal('show');
            }  
        });
    });
});
 
$(document).ready(function(){
    $(document).on('click', '.delete_data', function(){  
        var barang_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEMODAL:barang_id},  
            dataType:"json",  
            success:function(data){
                $('#del-barang').val(data.id_ba);
                $('#del-noref').val(data.noref_asset);
                $('#del-pluid').val(data.pluid);
                $('#del-noseri').val(data.sn_barang);
                $('#del-nodat').val(data.no_at);
                $('#del-office').val(data.ba_id_office);
                $('#del-dept').val(data.ba_id_department);
                
                $('#del-label').html("Delete Data Barang "+data.pluid+" - "+data.NamaBarang+" "+data.NamaJenis+" SN "+data.sn_barang);
                $('#dataModalDelete').modal('show');
            }  
        });  
    });
});

function validateForm(aksi) {
    var count_checked = $('input[name="checkidbarang[]"]:checked');
    if (count_checked.length == 0) {
        alert("Please check at least one checkbox");
        return false;
    }
    else {
        var groupid = "<?= $id_group; ?>"
        var array = []
        for (var i = 0; i < count_checked.length; i++) {
            array.push(count_checked[i].value)
        }
        $.ajax({
            type:'POST',
            url:'action/datarequest.php',
            data: {EDITBARANGCHECKBOX:array, GROUPIDUSER:groupid, AKSIUPDATE:aksi},
            success:function(data){
                if (aksi == "EDIT") {
                    $('#table-edtbarang-check').html(data);
                    $('#updatebrgcheck').modal('show');
                }
                else if (aksi == "DELETE") {
                    $('#table-dltbarang-check').html(data);
                    $('#deletebrgcheck').modal('show');
                }
            }
        });
    }
}

</script>

<?php
    include ("includes/templates/alert.php");
?>