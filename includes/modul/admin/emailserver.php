<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["insertdata"])){
    if(insertmailserver($_POST) > 0 ){
        $msg = encrypt("insertdata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedata"])){
    if(updatemailserver($_POST) > 0 ){
        $msg = encrypt("updatedata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(deletemailserver($_POST)){
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
                    <h4 class="card-title">Setting Mail Server</h4>
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
                                        data-toggle="modal" data-target="#entrymail">Entry Data Email</button>
                                    <div class="modal fade text-left" id="entrymail" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white"
                                                            id="myModalLabel">Input Data</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>Host : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="host"
                                                                placeholder="Host Mail Server" class="form-control" required>
                                                        </div>
                                                        <label>Account Email : </label>
                                                        <div class="form-group">
                                                            <input type="email" name="email"
                                                                placeholder="Mail Account" class="form-control" required>
                                                        </div>
                                                        <label>Port : </label>
                                                        <div class="form-group">
                                                            <input type="number" name="port"
                                                                placeholder="Port" class="form-control" required>
                                                        </div>
                                                        <label>Enkripsi : </label>
                                                        <div class="form-group">
                                                            <select name="enkripsi" class="select2 form-control block" style="width: 100%" type="text">
                                                            <option value="none" selected disabled>Please Select</option>
                                                            <?php
                                                                $enc = array('tls', 'ssl');
                                                                foreach ($enc as $e) {
                                                            ?>
                                                                <option value="<?= $e; ?>"><?= strtoupper($e); ?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                            </select>
                                                        </div>
                                                        <label>Password : </label>
                                                        <div class="form-group">
                                                            <input type="password" name="password"
                                                                placeholder="Password" class="form-control" required>
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
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered zero-configuration text-center">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Mail Account</th>
                                    <th>Password</th>
                                    <th>App Password</th>
                                    <th>Port</th>
                                    <th>Enkripsi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $query = mysqli_query($conn, "SELECT * FROM email_server ");
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= strtoupper($data['host']); ?></td>
                                    <td><?= strtoupper($data['email']); ?></td>
                                    <td><?= rplplus(decrypt($data['password'])); ?></td>
                                    <td><?= $data['app_password']; ?></td>
                                    <td><?= strtoupper($data['port']); ?></td>
                                    <td><?= strtoupper($data['enkripsi']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-success"><i class="ft-edit"
                                                data-toggle="modal"
                                                data-target="#update<?= $data['id']; ?>"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger"><i class="ft-delete"
                                                data-toggle="modal"
                                                data-target="#delete<?= $data['id']; ?>"></i></button>
                                    </td>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="update<?= $data['id']; ?>"
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
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
                                                        <input class="form-control" type="hidden" name="id" value="<?= $data['id']; ?>">
                                                        <label>Host : </label>
                                                        <div class="form-group">
                                                            <input type="text" name="host" value="<?= $data['host']; ?>"
                                                                placeholder="Host Mail Server" class="form-control" required>
                                                        </div>
                                                        <label>Account Email : </label>
                                                        <div class="form-group">
                                                            <input type="email" name="email" value="<?= $data['email']; ?>"
                                                                placeholder="Mail Account" class="form-control" required>
                                                        </div>
                                                        <label>Port : </label>
                                                        <div class="form-group">
                                                            <input type="number" name="port" value="<?= $data['port']; ?>"
                                                                placeholder="Port" class="form-control" required>
                                                        </div>
                                                        <label>Enkripsi : </label>
                                                        <div class="form-group">
                                                            <select name="enkripsi" class="form-control">
                                                            <?php
                                                                $enc = array('tls', 'ssl');
                                                                foreach ($enc as $e) {
                                                            ?>
                                                                <option value="<?= $e; ?>" <?= $data['enkripsi'] == $e ? 'selected' : ''; ?>><?= strtoupper($e); ?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                            </select>
                                                        </div>
                                                        <label>Password : </label>
                                                        <div class="form-group">
                                                            <input type="password" name="password" 
                                                                placeholder="Password" class="form-control" required>
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
                                    <div class="modal fade text-left" id="delete<?= $data['id']; ?>" role="dialog"
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
                                                    <input type="hidden" name="mailid" value="<?= $data['id']; ?>">
                                                    <p>Are you sure to delete account email : <?= $data['email']; ?>
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