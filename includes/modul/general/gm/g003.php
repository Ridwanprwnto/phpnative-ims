<?php

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdatacat"])){
    if(InsertBarang($_POST) > 0 ){
        $datapost = isset($_POST["idbarang"]) ? $_POST["idbarang"] : NULL;
        $alert = array("Success!", "Kategori Barang PLU ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["importdatacat"])){
    if(ImportBarang($_POST) > 0 ){
        $alert = array("Success!", "Import Data Kategori Barang Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatacat"])){
    if(UpdateBarang($_POST) > 0 ){
        $datapost = isset($_POST["upd-idbarang"]) ? $_POST["upd-idbarang"] : NULL;
        $alert = array("Success!", "Kategori Barang PLU ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatacat"])){
    if(DeleteBarang($_POST)){
        $datapost = isset($_POST["del-idbarang"]) ? $_POST["del-idbarang"] : NULL;
        $alert = array("Success!", "Kategori Barang PLU ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdatajns"])){
    if(InsertJenis($_POST) > 0 ){
        $datapost = isset($_POST["idbarang"]) ? $_POST["idbarang"] : NULL;
        $alert = array("Success!", "Jenis Barang ID ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["importdatajns"])){
    if(ImportJenisBarang($_POST) > 0 ){
        $alert = array("Success!", "Import Data Jenis Barang Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatajns"])){
    if(UpdateJenis($_POST) > 0 ){
        $datapost = isset($_POST["oldidjenis"]) ? $_POST["oldidjenis"] : NULL;
        $alert = array("Success!", "Jenis Barang ID ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatajns"])){
    if(DeleteJenis($_POST)){
        $datapost = isset($_POST["del-idjenis"]) ? $_POST["del-idjenis"] : NULL;
        $alert = array("Success!", "Jenis Barang ID ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Entry Data Master Barang</h4>
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
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="category-barang" data-toggle="tab" href="#categorybarang" aria-expanded="true">Kategori Barang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="jenis-barang" data-toggle="tab" href="#jenisbarang" aria-expanded="false">Jenis Barang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="master-barang" data-toggle="tab" href="#masterbarang" aria-expanded="false">Master Barang</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="categorybarang" aria-expanded="true" aria-labelledby="category-barang">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <form method="post" action="reporting/report-file-masterbarang.php" target="_blank">
                                                <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entrycatbarang">Entry Category Barang</button>
                                                <button type="submit" name="exportdata" class="btn btn-secondary btn-min-width mt-1 mr-1 mb-1">Download Category File</button>
                                                <button type="button" class="btn btn-secondary btn-min-width mt-1 mr-1 mb-1 " data-toggle="modal" data-target="#entrycatbarang-import">Import Category Barang</button>
                                            </form>
                                            <div class="modal fade text-left" id="entrycatbarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Category Barang</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                        <label>Kategori Barang : </label>
                                                                        <select id="category" name="category" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_ctg = mysqli_query($conn, "SELECT * FROM categorybarang");
                                                                                while($data_ctg = mysqli_fetch_assoc($query_ctg)) {
                                                                            ?>
                                                                                <option value="<?= $data_ctg['IDCategory'];?>"><?= $data_ctg['IDCategory'].' - '.$data_ctg['CategoryName']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>PLU : </label>
                                                                        <input type="number" name="idbarang" placeholder="PLU Tipe Barang" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Barang : </label>
                                                                        <input type="text" name="namabarang"    
                                                                                placeholder="Nama Barang" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Satuan Barang : </label>
                                                                        <select name="satuanbarang" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_stn = mysqli_query($conn, "SELECT * FROM satuan");
                                                                                while($data_stn = mysqli_fetch_assoc($query_stn)) {
                                                                            ?>
                                                                                <option value="<?= $data_stn['id_satuan'];?>"><?= $data_stn['nama_satuan']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatacat" class="btn btn-outline-primary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                            <!-- Import Modal -->
                                            <div class="modal fade text-left" id="entrycatbarang-import" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post" enctype="multipart/form-data" role="form">
                                                            <div class="modal-header bg-secondary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Import Data Category Barang</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>File : </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="file-import" required>
                                                                            <label class="custom-file-label">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="importdatacat" class="btn btn-outline-primary">Import</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Kategori Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan Barang</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql = "SELECT A.IDBarang, A.NamaBarang, B.id_satuan, B.nama_satuan, C.IDCategory, C.CategoryName FROM mastercategory AS A
                                        INNER JOIN satuan AS B ON A.id_satuan = B.id_satuan
                                        INNER JOIN categorybarang AS C ON LEFT(A.IDBarang, 1) = C.IDCategory";
                                        $query = mysqli_query($conn, $sql);
                                        while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $data['IDBarang']; ?></td>
                                            <td><?= $data['CategoryName']; ?></td>
                                            <td><?= $data['NamaBarang']; ?></td>
                                            <td><?= $data['nama_satuan']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_categorybarang" title="Update Category Barang ID <?= $data["IDBarang"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_categorybarang" id="<?= $data["IDBarang"]; ?>"><i class="ft-edit"></i></button>
                                                <button type="button" class="btn btn-icon btn-danger delete_categorybarang" title="Delete Category Barang ID <?= $data["IDBarang"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_categorybarang" id="<?= $data["IDBarang"]; ?>"><i class="ft-delete"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalCatBarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="myModalLabel">Edit Data Category Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                        <input class="form-control" type="hidden" id="upd-nameoldcatbarang" name="namabarangold" readonly>
                                                        <div class="form-group">
                                                            <label>ID Barang : </label>
                                                            <input class="form-control" type="text" id="upd-idcatbarang" name="upd-idbarang" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Barang : </label>
                                                            <input type="text" id="upd-namecatbarang" name="namabarang" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Satuan Barang : </label>
                                                            <select id="upd-satuancatbarang" name="satuanbarang" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_stn = mysqli_query($conn, "SELECT * FROM satuan");
                                                                    while($data_stn = mysqli_fetch_assoc($query_stn)) {
                                                                ?>
                                                                    <option value="<?= $data_stn['id_satuan'];?>" <?= $data_stn['id_satuan'] == $data['id_satuan'] ? 'selected' : ''; ?>><?= $data_stn['nama_satuan']; ?></option>
                                                                <?php 
                                                                    } 
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedatacat" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalCatBarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Data Category Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="del-idbarang" name="del-idbarang" class="form-control" readonly>
                                                    <label id="del-labelidbarang"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatacat" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="jenisbarang" aria-labelledby="jenis-barang">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <form method="post" action="reporting/report-file-jenisbarang.php" target="_blank">
                                                <button type="button" class="btn btn-primary btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entrytipe">Entry Jenis Barang</button>
                                                <button type="submit" name="exportdatajenis" class="btn btn-secondary btn-min-width mt-1 mr-1 mb-1">Download Jenis File</button>
                                                <button type="button" class="btn btn-secondary btn-min-width mt-1 mr-1 mb-1 " data-toggle="modal" data-target="#entryjenisbarang-import">Import Jenis Barang</button>
                                            </form>
                                            <div class="modal fade text-left" id="entrytipe" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Entry Data Jenis Barang</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                        <label>Nama Barang : </label>
                                                                        <select id="idbarang" name="idbarang" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query = mysqli_query($conn, "SELECT * FROM mastercategory");
                                                                                while($data = mysqli_fetch_assoc($query)) {
                                                                            ?>
                                                                                <option value="<?= $data['IDBarang'];?>"><?= $data['IDBarang'].' - '.$data['NamaBarang']; ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Tipe Barang : </label>
                                                                        <input type="text" name="namajenis" placeholder="Nama Tipe Barang" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Harga : </label>
                                                                        <div class="card-block">
                                                                            <fieldset>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">Rp.</span>
                                                                                    </div>
                                                                                    <input type="number" class="form-control" placeholder="Input dengan nilai mata uang rupiah" aria-label="Amount (to the nearest dollar)" name="hargajenis">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">.00</span>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatajns" class="btn btn-outline-primary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                            <!-- Import Modal -->
                                            <div class="modal fade text-left" id="entryjenisbarang-import" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post" enctype="multipart/form-data" role="form">
                                                            <div class="modal-header bg-secondary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Import Data Jenis Barang</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>File : </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="file-importjenis" required>
                                                                            <label class="custom-file-label">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="importdatajns" class="btn btn-outline-primary">Import</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PLU</th>
                                            <th>Nama Barang</th>
                                            <th>Jenis</th>
                                            <th>Est Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql = "SELECT mastercategory.*, categorybarang.*, masterjenis.* FROM masterjenis
                                        INNER JOIN mastercategory ON masterjenis.IDBarang = mastercategory.IDBarang
                                        INNER JOIN categorybarang ON LEFT(masterjenis.IDBarang, 1) = categorybarang.IDCategory";
                                        $query = mysqli_query($conn, $sql);
                                        while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                            <tr>
                                                <td><?= $data['IDBarang']; ?></td>
                                                <td><?= $data['IDJenis']; ?></td>
                                                <td><?= $data['NamaBarang']; ?></td>
                                                <td><?= isset($data['NamaJenis']) ? $data['NamaJenis'] : '-';?></td>
                                                <td><?= "Rp " . number_format($data['HargaJenis'], 0, ",", "."); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_jenisbarang" title="Update Jenis Barang ID <?= $data["IDJenis"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_jenisbarang" id="<?= $data['IDJenis']; ?>"><i class="ft-edit"></i></button>
                                                <?php if ($id_group == $admin) {?>
                                                <button type="button" class="btn btn-icon btn-danger delete_jenisbarang" title="Delete Jenis Barang ID <?= $data["IDJenis"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_jenisbarang" id="<?= $data['IDJenis']; ?>"><i class="ft-delete"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalJnsBarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="myModalLabel">Edit Data Jenis Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nama Barang : </label>
                                                                <input type="text" id="upd-namectgbarang" class="form-control" disabled>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>ID Jenis Barang : </label>
                                                                <input class="form-control" type="text" id="upd-idjnsbarang" name="oldidjenis" readonly>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nama Jenis Barang : </label>
                                                                <input type="text" id="upd-namejnsbarang" name="namajenis" placeholder="Nama Jenis Barang" class="form-control">
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Harga : </label>
                                                                <div class="card-block">
                                                                    <fieldset>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp.</span>
                                                                            </div>
                                                                            <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)" id="upd-hargajnsbarang" name="hargajenis" required>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">.00</span>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedatajns" class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalJnsBarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Data Jenis Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="page" value="<?= $encpid; ?>" class="form-control" readonly>
                                                    <input type="hidden" id="del-idjenisbarang" name="del-idjenis" class="form-control" readonly>
                                                    <label id="del-labelidjnsbarang"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatajns" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="masterbarang" aria-labelledby="master-barang">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Estimasi Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $result = "SELECT A.*, B.*, C.nama_satuan FROM masterjenis AS A
                                        INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang
                                        INNER JOIN satuan AS C ON B.id_satuan = C.id_satuan";
                                        $query = mysqli_query($conn, $result);
                                        while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $data['IDBarang'].$data['IDJenis']; ?></td>
                                            <td><?= $data['NamaBarang']." ".$data['NamaJenis']; ?></td>
                                            <td><?= $data['nama_satuan']; ?></td>
                                            <td><?= "Rp " . number_format($data['HargaJenis'], 0, ",", "."); ?></td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>
$(document).ready(function(){
    $(document).on('click', '.update_categorybarang', function(){  
        var ID_cat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATECATBARANG:ID_cat},  
            dataType:"json",  
            success:function(data){
                $('#upd-idcatbarang').val(data.IDBarang);
                $('#upd-nameoldcatbarang').val(data.NamaBarang);
                $('#upd-namecatbarang').val(data.NamaBarang);

                $('#upd-satuancatbarang').find('option[value="'+data.id_satuan+'"]').remove();
                $('#upd-satuancatbarang').append($('<option></option>').html(data.nama_satuan).attr('value', data.id_satuan).prop('selected', true));

                $('#updateModalCatBarang').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_categorybarang', function(){  
        var ID_cat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETECATBARANG:ID_cat},  
            dataType:"json",  
            success:function(data){
                $('#del-idbarang').val(data.IDBarang);

                $('#del-labelidbarang').html("Are you sure to delete data ID "+data.IDBarang);

                $('#deleteModalCatBarang').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_jenisbarang', function(){  
        var ID_jns = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEJNSBARANG:ID_jns},  
            dataType:"json",  
            success:function(data){
                $('#upd-idjnsbarang').val(data.IDJenis);
                $('#upd-namectgbarang').val(data.NamaBarang);
                $('#upd-namejnsbarang').val(data.NamaJenis);
                $('#upd-hargajnsbarang').val(data.HargaJenis);

                $('#updateModalJnsBarang').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_jenisbarang', function(){  
        var ID_jns = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEJNSBARANG:ID_jns},  
            dataType:"json",  
            success:function(data){
                $('#del-idjenisbarang').val(data.IDJenis);

                $('#del-labelidjnsbarang').html("Are you sure to delete data ID "+data.IDJenis);

                $('#deleteModalJnsBarang').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>