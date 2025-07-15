<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insertoffice($_POST) > 0 ){
        $msg = encrypt("insertdata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(updateoffice($_POST) > 0 ){
        $msg = encrypt("updatedata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deleteoffice($_POST)){
        $msg = encrypt("deletedata");
        header("location: index.php?page=$encpid&alert=$msg");
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
                    <h4 class="card-title">Struktur Office</h4>
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
                                    <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-1"
                                        data-toggle="modal" data-target="#entryoffice">Entry Office</button>
                                    <div class="modal fade text-left" id="entryoffice" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data Office</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Company : </label>
                                                                <select name="company" class="select2 form-control block" style="width: 100%" type="text" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php 
                                                                        $query_cmp = mysqli_query($conn, "SELECT * FROM company");
                                                                        while($data_cmp = mysqli_fetch_assoc($query_cmp)) { ?>
                                                                        <option value="<?= $data_cmp['company_id'];?>" ><?= $data_cmp['company_jenis'].". ".$data_cmp['company_name'];?></option>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Office Code : </label>
                                                                <input type="text" name="kodeoffice" placeholder="Office Code" class="form-control" required>
                                                                </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Office Name : </label>
                                                                <input type="text" name="officename" placeholder="Office Full Name" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Office Short Name : </label>
                                                                <input type="text" name="shortname" placeholder="Office Short Name" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Email : </label>
                                                                <input type="email" name="email" placeholder="Email Address" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>City : </label>
                                                                <input type="text" name="city" placeholder="City" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Address : </label>
                                                                <textarea class="form-control square" name="address" placeholder="Address" required></textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                               <label>Postal Code : </label>
                                                                <input type="number" name="postalcode" placeholder="Postal Code" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Phone : </label>
                                                                <input type="text" name="phone" placeholder="Phone Number" class="form-control" required>
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
                                    <?php
                            if(isset($_GET["page"]) && isset($_GET["alert"])) {
                                $url_page  = $_GET["page"];
                                $url_alert = $_GET["alert"];
                                if($_GET["page"] === $url_page && $_GET["alert"] === $url_alert) {
                                    $strplus = rplplus($url_alert);
                                    $decmsg = decrypt($strplus);
                                    if($decmsg == true) {
                                        if($decmsg === "insertdata") {
                                            ?>
                                    <div class="alert alert-primary alert-dismissible ml-1 mr-1 pull-right"
                                        role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil ditambahkan
                                    </div>
                                    <?php
                                        }
                                        elseif($decmsg === "updatedata") {
                                            ?>
                                    <div class="alert alert-success alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil diupdate
                                    </div>
                                    <?php   
                                        }
                                        elseif($decmsg === "deletedata") {
                                            ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> Data berhasil dihapus
                                    </div>
                                    <?php
                                        }
                                    }
                                }
                            }
                            ?>
                                    <?php
                            if (isset($ins_kodelenght)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $ins_kodelenght; ?>
                                    </div>
                                    <?php
                            }
                            elseif (isset($ins_checkcode)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $ins_checkcode; ?>
                                    </div>
                                    <?php
                            }
                            elseif (isset($ins_checkname)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $ins_checkname; ?>
                                    </div>
                                    <?php
                            }
                            elseif (isset($error_delete)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible ml-1 mr-1 pull-right" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <?= $error_delete; ?>
                                    </div>
                                    <?php
                            }
                            ?>
                                </div>
                            </div>
                        </div>
                        <table class="table display nowrap table-striped table-bordered scroll-horizontal text-center">
                            <thead>
                                <tr>
                                    <th>Office Code</th>
                                    <th>Office Name</th>
                                    <th>Office Shortname</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th>Postal Code</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $query = mysqli_query($conn, "SELECT * FROM office ");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['id_office']; ?></td>
                                    <td><?= strtoupper($data['office_name']); ?></td>
                                    <td><?= $data['office_shortname']; ?></td>
                                    <td><?= $data['office_email']; ?></td>
                                    <td><?= $data['office_city']; ?></td>
                                    <td><?= $data['office_address']; ?></td>
                                    <td><?= $data['office_poscode']; ?></td>
                                    <td><?= $data['office_phone']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success"><i class="ft-edit"
                                                data-toggle="modal"
                                                data-target="#update<?= $data['id_office']; ?>"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger"><i class="ft-delete"
                                                data-toggle="modal"
                                                data-target="#delete<?= $data['id_office']; ?>"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id_office']; ?>"
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Changes Data</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="idofficeold" value="<?= $data['id_office']; ?>">
                                                            <div class="col-md-12 mb-2">
                                                            <label>Office Code: </label>
                                                                <input type="text" name="idoffice"
                                                                    placeholder="Office Code" class="form-control" value="<?= $data['id_office']; ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Office Name: </label>
                                                                <input type="text" name="officename"
                                                                    placeholder="Office Name" class="form-control" value="<?= $data['office_name']; ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Office Shortname: </label>
                                                                <input type="text" name="officeshortename"
                                                                    placeholder="Office Shortname" class="form-control" value="<?= $data['office_shortname']; ?>" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                            <label>Email: </label>
                                                                <input type="email" name="emailoffice" placeholder="Email Address"
                                                                    class="form-control" value="<?= $data['office_email']; ?>" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                            <label>City: </label>
                                                                <input type="text" name="city" placeholder="City"
                                                                    class="form-control" value="<?= $data['office_city']; ?>" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                            <label>Address: </label>
                                                                <textarea class="form-control square" name="address"
                                                                    placeholder="Address" required><?= $data['office_address']; ?></textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Postal Code: </label>
                                                                <input type="number" name="postalcode"
                                                                    placeholder="Postal Code" class="form-control" value="<?= $data['office_poscode']; ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                            <label>Phone: </label>
                                                                <input type="text" name="phone" placeholder="Phone Number"
                                                                    class="form-control" value="<?= $data['office_phone']; ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedata"
                                                            class="btn btn-outline-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="delete<?= $data['id_office']; ?>" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="idoffice" value="<?= $data['id_office']; ?>">
                                                    <p>Are you sure to delete ID Office : <?= $data['id_office']; ?>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedata" class="btn btn-outline-danger">Yes</button>
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
</section>
<!--/ Auto Fill table -->