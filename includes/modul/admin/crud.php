<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["updatedata"])){
    if(updatecrud($_POST) > 0 ){
        $msg = encrypt("updatedata");
        header("location: index.php?page=$encpid&alert=$msg");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<!-- Basic Tables start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4 class="card-title">Master Tabel CRUD</h4>
            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
            </div>
            <div class="card-content collapse show">
            <form action="" method="post">
                <div class="card-body">
                    <div class="table-responsive text-center">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>LEVEL</th>
                            <th>CREATE</th>
                            <th>READ</th>
                            <th>UPDATE</th>
                            <th>DELETE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            $result = "SELECT crud.*, level.* FROM crud
                            INNER JOIN level ON crud.id_level = level.id_level";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <th>
                            <div class="card-body">
                                <?= $no++; ?>
                            </div>
                            </th>
                            <td>
                            <div class="card-body">
                                <?= $data['level_name']; ?>
                            </div>
                            </td>
                            <td>
                            <div class="card-body">
                                <input type="checkbox" class="switch" id="switch-c" name="<?= $data['id_level'];?>" value="1" <?= $data['status_create'] == '1' ? 'checked' : ''; ?>/>
                            </div>
                            </td>
                            <td>
                            <div class="card-body">
                                <input type="checkbox" class="switch" id="switch-r" name="<?= $data['id_level'];?>" value="1" <?= $data['status_read'] == '1' ? 'checked' : ''; ?>/>
                            </div>
                            </td>
                            <td>
                            <div class="card-body">
                                <input type="checkbox" class="switch" id="switch-u" name="<?= $data['id_level'];?>" value="1" <?= $data['status_update'] == '1' ? 'checked' : ''; ?>/>
                            </div>
                            </td>
                            <td>
                            <div class="card-body">
                                <input type="checkbox" class="switch" id="switch-d" name="<?= $data['id_level'];?>" value="1" <?= $data['status_delete'] == '1' ? 'checked' : ''; ?>/>
                            </div>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    </div>
                    <button type="button" class="btn btn-secondary mt-1" data-toggle="modal" data-target="#updatedata"><i class="ft-edit-2"></i> Update Data</button>
                    <!-- Modal Proses Update -->
                    <div class="modal fade text-left" id="updatedata" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success white">
                                    <h4 class="modal-title white" id="myModalLabel1">Update Confirmation</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah anda yakin ingin mengupdate data?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" name="deletedata" class="btn btn-outline-success">Yes</button>
                                </div>
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
                                if($decmsg === "updatedata") {
                                    ?>
                            <div class="alert alert-success alert-dismissible ml-1 mr-2 mt-2 mb-2 pull-right"
                                role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Success!</strong> Data berhasil diupdate
                            </div>
                            <?php   
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- Basic Tables end -->