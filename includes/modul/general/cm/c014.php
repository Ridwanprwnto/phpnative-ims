<?php

$idoffice = $_SESSION['office'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdatatype"])){
    if(InsertTypePlano($_POST) > 0 ){
        $datapost = $_POST["nmtype-plano"];
        $alert = array("Success!", "Data Type Planogram ".$datapost." Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatatype"])){
    if(DeleteTypePlano($_POST)){
        $datapost = $_POST["idtype-plano"];
        $alert = array("Success!", "Data Type Planogram ".$datapost." Berhasil Dihapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdatazona"])){
    if(InsertZonaPlano($_POST) > 0 ){
        $datapost = $_POST["nmzona-plano"];
        $alert = array("Success!", "Data Zona Planogram ".$datapost." Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatazona"])){
    if(DeleteZonaPlano($_POST)){
        $datapost = $_POST["idzona-plano"];
        $alert = array("Success!", "Data Zona Planogram ".$datapost." Berhasil Dihapus", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdataline"])){
    if(InsertLinePlano($_POST) > 0 ){
        $datapost = $_POST["nmline-plano"];
        $alert = array("Success!", "Data Line Planogram ".$datapost." Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedataline"])){
    if(DeleteLinePlano($_POST)){
        $datapost = $_POST["idline-plano"];
        $alert = array("Success!", "Data Line Planogram ".$datapost." Berhasil Dihapus", "success", "$redirect");
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
                    <h4 class="card-title">Master Tabel Planogram</h4>
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
                                <a class="nav-link active" id="type-planogram" data-toggle="tab" href="#typeplanogram" aria-expanded="true">Type Planogram</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="zona-planogram" data-toggle="tab" href="#zonaplanogram" aria-expanded="false">Zona Planogram</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="line-planogram" data-toggle="tab" href="#lineplanogram" aria-expanded="false">Line Planogram</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="typeplanogram" aria-expanded="true" aria-labelledby="type-planogram">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mb-1" data-toggle="modal" data-target="#entrytype">Entry Type Rak</button>
                                            <div class="modal fade text-left" id="entrytype" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Input Data Type Rak</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="idtype-plano" value="<?= autonum(2, 'id_type_plano', 'type_plano'); ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Office : </label>
                                                                        <select name="office-type" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_type_off = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$idoffice'");
                                                                                while($data_type_off = mysqli_fetch_assoc($query_type_off)) {
                                                                            ?>
                                                                                <option value="<?= $data_type_off['id_office'];?>"><?= $data_type_off['id_office'].' - '.strtoupper($data_type_off['office_name']); ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Type Rak : </label>
                                                                        <input type="text" name="nmtype-plano" placeholder="Entry type rak name" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatatype"
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
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                        <tr>
                                            <th>ID Type Rak</th>
                                            <th>Nama Type Rak</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $query_type = mysqli_query($conn, "SELECT * FROM type_plano WHERE office_type_plano = '$idoffice'");
                                    while($data_type = mysqli_fetch_assoc($query_type)) {
                                    ?>
                                        <tr>
                                            <td><?= $data_type['id_type_plano']; ?></td>
                                            <td><?= $data_type['nm_type_plano']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-danger mr-1"><i class="ft-delete" data-toggle="modal" data-target="#delete<?= $data_type['id_type_plano']; ?>"></i></button>
                                            </td>
                                            <!-- Modal Delete -->
                                            <div class="modal fade text-left" id="delete<?= $data_type['id_type_plano']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <form action="" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger white">
                                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="page-plano" value="<?= $redirect; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="idtype-plano" value="<?= $data_type['id_type_plano']; ?>" readonly>
                                                            <p>Are you sure to delete ID Type Rak : <?= $data_type['id_type_plano']; ?>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" name="deletedatatype" class="btn btn-outline-danger">Yes</button>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="zonaplanogram" aria-labelledby="zona-planogram">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mb-1" data-toggle="modal" data-target="#entryzona">Entry Zona Rak</button>
                                            <div class="modal fade text-left" id="entryzona" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white"
                                                                    id="myModalLabel">Input Data Zona Rak</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="idzona-plano" value="<?= autonum(2, 'id_zona_plano', 'zona_plano'); ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Office : </label>
                                                                        <select name="office-plano" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_off = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$idoffice'");
                                                                                while($data_off = mysqli_fetch_assoc($query_off)) {
                                                                            ?>
                                                                                <option value="<?= $data_off['id_office'];?>"><?= $data_off['id_office'].' - '.strtoupper($data_off['office_name']); ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Type Rak : </label>
                                                                        <select type="text" name="idtype-plano" class="select2 form-control block" style="width: 100%">
                                                                        <option value="none" selected disabled>Please Select</option>
                                                                        <?php 
                                                                            $sql_type = mysqli_query($conn, "SELECT * FROM type_plano WHERE office_type_plano = '$idoffice'");
                                                                        while($d_type = mysqli_fetch_assoc($sql_type)) {
                                                                        ?>
                                                                        <option value="<?= $d_type['id_type_plano'];?>" ><?= $d_type['nm_type_plano'];?></option>
                                                                        <?php 
                                                                            } 
                                                                        ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Zona : </label>
                                                                        <input type="text" name="nmzona-plano" placeholder="Entry zona name" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jumlah Stasiun : </label>
                                                                        <input type="number" name="station-plano" placeholder="Input jumlah stasiun" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Type Item : </label>
                                                                        <select type="text" name="itemtype-plano" class="select2 form-control block" style="width: 100%" required>
                                                                        <option value="none" selected disabled>Please Select</option>
                                                                        <option value="FOOD">FOOD</option>
                                                                        <option value="NON FOOD">NON FOOD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatazona" class="btn btn-outline-primary">Save</button>
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
                                            <th>ID Zona Rak</th>
                                            <th>Type Rak</th>
                                            <th>Nama Zona Rak</th>
                                            <th>Jumlah Station</th>
                                            <th>Type Item</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $query_zona = mysqli_query($conn, "SELECT zona_plano.*, type_plano.* FROM zona_plano
                                        INNER JOIN type_plano ON zona_plano.id_type_plano_head = type_plano.id_type_plano
                                        WHERE office_zona_plano = '$idoffice'");
                                        while($data_zona = mysqli_fetch_assoc($query_zona)) {
                                    ?>
                                        <tr>
                                            <td><?= $data_zona['id_zona_plano']; ?></td>
                                            <td><?= $data_zona['nm_type_plano']; ?></td>
                                            <td><?= $data_zona['nm_zona_plano']; ?></td>
                                            <td><?= $data_zona['station_zona_plano']; ?></td>
                                            <td><?= $data_zona['item_zona_plano']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-danger mr-1"><i class="ft-delete" data-toggle="modal" data-target="#delete<?= $data_zona['id_zona_plano']; ?>"></i></button>
                                            </td>
                                            <!-- Modal Delete -->
                                            <div class="modal fade text-left" id="delete<?= $data_zona['id_zona_plano']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <form action="" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger white">
                                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="page-plano" value="<?= $redirect; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="idzona-plano" value="<?= $data_zona['id_zona_plano']; ?>" readonly>
                                                            <p>Are you sure to delete ID Zona Rak : <?= $data_zona['id_zona_plano']; ?>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="deletedatazona" class="btn btn-outline-danger">Yes</button>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="lineplanogram" aria-labelledby="line-planogram">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mb-1" data-toggle="modal" data-target="#entryline">Entry Line Rak</button>
                                            <div class="modal fade text-left" id="entryline" role="dialog"
                                                aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Input Data Line Rak</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <input type="hidden" name="page" value="<?= $redirect; ?>" class="form-control" readonly>
                                                                    <input type="hidden" name="idline-plano" value="<?= autonum(2, 'id_line_plano', 'line_plano'); ?>" class="form-control" readonly>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Office : </label>
                                                                        <select name="office-plano" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php 
                                                                                $query_z_off = mysqli_query($conn, "SELECT * FROM office WHERE id_office = '$idoffice'");
                                                                                while($data_z_off = mysqli_fetch_assoc($query_z_off)) {
                                                                            ?>
                                                                                <option value="<?= $data_z_off['id_office'];?>"><?= $data_z_off['id_office'].' - '.strtoupper($data_z_off['office_name']); ?></option>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <label>Zona :</label>
                                                                        <select type="text" id="id-zona-plano" name="idzona-plano" class="select2 form-control block" style="width: 100%">
                                                                        <option value="none" selected disabled>Please Select</option>
                                                                        <?php 
                                                                            $sql_zona = mysqli_query($conn, "SELECT zona_plano.*, type_plano.* FROM zona_plano
                                                                            INNER JOIN type_plano ON zona_plano.id_type_plano_head = type_plano.id_type_plano
                                                                            WHERE zona_plano.office_zona_plano = '$idoffice' ORDER BY id_type_plano_head ASC");
                                                                            while($d_zona = mysqli_fetch_assoc($sql_zona)) {
                                                                        ?>
                                                                        <option value="<?= $d_zona['id_zona_plano'];?>" ><?= $d_zona['nm_zona_plano']." - ".$d_zona['nm_type_plano'];?></option>
                                                                        <?php 
                                                                            } 
                                                                        ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <label>Station :</label>
                                                                        <select type="text" id="station-zona-plano" name="stationzona-plano" class="select2 form-control block" style="width: 100%">
                                                                            <option value="none" selected disabled>Please Select</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Nama Line : </label>
                                                                        <input type="text" name="nmline-plano" placeholder="Input nama line" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jumlah Rak : </label>
                                                                        <table class="table table-striped text-center">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th><button type="button" name="add_rak_plano" class="btn btn-success btn-xs add_rak_plano"><i class="ft-plus"></i></button></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="table-rak-plano">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jumlah Shelfing : </label>
                                                                        <table class="table table-striped text-center">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th><button type="button" name="add_shelf_plano" class="btn btn-success btn-xs add_shelf_plano"><i class="ft-plus"></i></button></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="table-shelf-plano">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jumlah Cell : </label>
                                                                        <table class="table table-striped text-center">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th><button type="button" name="add_cell_plano" class="btn btn-success btn-xs add_cell_plano"><i class="ft-plus"></i></button></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="table-cell-plano">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>IP Gateway DPD : </label>
                                                                        <table class="table table-striped text-center">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>IP DPD</th>
                                                                                    <th>ID Low</th>
                                                                                    <th>ID High</th>
                                                                                    <th><button type="button" name="add_ip_dpd" class="btn btn-success btn-xs add_ip_dpd"><i class="ft-plus"></i></button></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="table-ip-dpd">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdataline" class="btn btn-outline-primary">Save</button>
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
                                            <th>ID Line Rak</th>
                                            <th>Type Zona Rak</th>
                                            <th>Nama Line Rak</th>
                                            <th>Station</th>
                                            <th>Jumlah Rak</th>
                                            <th>Jumlah Shelf</th>
                                            <th>Jumlah Cell</th>
                                            <th>IP DPD</th>
                                            <th>Jumlah ID DPD</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query_line = mysqli_query($conn, "SELECT line_plano.*, zona_plano.*, type_plano.* FROM line_plano
                                            INNER JOIN zona_plano ON line_plano.id_zona_plano_head = zona_plano.id_zona_plano
                                            INNER JOIN type_plano ON zona_plano.id_type_plano_head = type_plano.id_type_plano
                                            WHERE office_line_plano = '$idoffice'");
                                            while($data_line = mysqli_fetch_assoc($query_line)) {
                                        ?>
                                        <tr>
                                            <td><?= $data_line['id_line_plano']; ?></td>
                                            <td><?= $data_line['nm_zona_plano']." - ".$data_line['nm_type_plano']; ?></td>
                                            <td><?= $data_line['nm_line_plano']; ?></td>
                                            <td><?= $data_line['station_line_plano']; ?></td>
                                            <td><?= $data_line['rak_line_plano']; ?></td>
                                            <td><?= $data_line['shelf_line_plano']; ?></td>
                                            <td><?= $data_line['cell_line_plano']; ?></td>
                                            <td><?= $data_line['ip_line_plano']; ?></td>
                                            <td><?= $data_line['iddpd_line_plano']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-danger mr-1"><i class="ft-delete" data-toggle="modal" data-target="#delete<?= $data_line['id_line_plano']; ?>"></i></button>
                                            </td>
                                            <!-- Modal Delete -->
                                            <div class="modal fade text-left" id="delete<?= $data_line['id_line_plano']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <input type="hidden" name="idline-plano" value="<?= $data_line['id_line_plano']; ?>">
                                                            <p>Are you sure to delete ID Line Rak : <?= $data_line['id_line_plano']; ?>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="deletedataline" class="btn btn-outline-danger">Yes</button>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
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
        var count = 0;
        $(document).on('click', '.add_rak_plano', function(){
            count++;
            var html = '';
            html += '<tr>';
            html += '<td><select type="text" name="rak-plano[]" class="select2 form-control block rak-plano" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_digit(100, 2); ?></select></td>';
            html += '<td><button type="button" name="remove_rak_plano" class="btn btn-danger btn-xs remove_rak_plano"><i class="ft-minus"></i></button></td>';
            $('#table-rak-plano').append(html);
            $(".select2").select2();
        });
        $(document).on('click', '.remove_rak_plano', function(){
            $(this).closest('tr').remove();
        });
    });
    $(document).ready(function(){
        var count = 0;
        $(document).on('click', '.add_shelf_plano', function(){
            count++;
            var html = '';
            html += '<tr>';
            html += '<td><select type="text" name="shelf-plano[]" class="select2 form-control block shelf-plano" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_digit(10, 1); ?></select></td>';
            html += '<td><button type="button" name="remove_shelf_plano" class="btn btn-danger btn-xs remove_shelf_plano"><i class="ft-minus"></i></button></td>';
            $('#table-shelf-plano').append(html);
            $(".select2").select2();
        });
        $(document).on('click', '.remove_shelf_plano', function(){
            $(this).closest('tr').remove();
        });
    });
    $(document).ready(function(){
        var count = 0;
        $(document).on('click', '.add_cell_plano', function(){
            count++;
            var html = '';
            html += '<tr>';
            html += '<td><select type="text" name="cell-plano[]" class="select2 form-control cell shelf-plano" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_digit(10, 1); ?></select></td>';
            html += '<td><button type="button" name="remove_cell_plano" class="btn btn-danger btn-xs remove_cell_plano"><i class="ft-minus"></i></button></td>';
            $('#table-cell-plano').append(html);
            $(".select2").select2();
        });
        $(document).on('click', '.remove_cell_plano', function(){
            $(this).closest('tr').remove();
        });
    });
    $(document).ready(function(){
        var count = 0;
        $(document).on('click', '.add_ip_dpd', function(){
            count++;
            var html = '';
            html += '<tr>';
            html += '<td><input type="text" name="ipdpd_plano[]" class="form-control ipdpd_plano" placeholder="Input IP DPD" required/></td>';
            html += '<td><select type="text" name="id-plano[]" class="select2 form-control block id-plano" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_digit(117, 1); ?></select></td>';
            html += '<td><select type="text" name="id-plano[]" class="select2 form-control block id-plano" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_digit(117, 1); ?></select></td>';
            html += '<td><button type="button" name="remove_ip_dpd" class="btn btn-danger btn-xs remove_ip_dpd"><i class="ft-minus"></i></button></td>';
            $('#table-ip-dpd').append(html);
            $(".select2").select2();
        });
        $(document).on('click', '.remove_ip_dpd', function(){
            $(this).closest('tr').remove();
        });
    });
    $(document).ready(function () {
        $("#id-zona-plano").on('change', function () {
            var stationID = $('#id-zona-plano').val();
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: {STATIONPLANO:stationID},
                dataType:"json",
                success: function (data) {
                    if (data.station && data.station.length > 0) {
                        $('#station-zona-plano').html('<option value="" selected disabled>Please Select</option>');
                        $.each(data.station, function(index, value) {
                            $('#station-zona-plano').append($('<option></option>').attr('value', value).text(value));
                        });
                    }
                    else {
                        $('#station-zona-plano').html('<option value="" selected disabled>Please Select</option>');
                    }
                }
            });
        });
    });
</script>

<?php
    include ("includes/templates/alert.php");
?>