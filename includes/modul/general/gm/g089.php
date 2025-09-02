<?php
    $office_id = $_SESSION['office'];
    $page_id = $_GET['page'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["deletedata"])){
        if(DeleteDataRepass($_POST) > 0 ){
            $datapost = isset($_POST["nik-restpass"]) ? $_POST["nik-restpass"] : NULL;
            $alert = array("Success!", "Data Repass User NIK ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Reset Password Users</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <div class="card-text">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration text-center">
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL</th>
                                        <th>NIK</th>
                                        <th>EMAIL</th>
                                        <th>CODE</th>
                                        <th>LINK</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            $sql = "SELECT * FROM reset_pass WHERE office_reset = '$office_id' AND status_reset = 'N'";
                                            $query = mysqli_query($conn, $sql);
                                            while ($data = mysqli_fetch_assoc($query)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data["tgl_reset"]; ?></td>
                                            <td><?= $data["nik_reset"]; ?></td>
                                            <td><?= $data["email_reset"]; ?></td>
                                            <td>
                                                <strong><?= $data["code_reset"]; ?></strong>
                                            </td>
                                            <td>
                                                <a title="Link Reset Password" href="<?= $data["url_reset"]; ?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" class="btn btn-float btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-external-link"></i>
                                                    <span>Link</span>
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" title="Delete Data Repass NIK : <?= $data['nik_reset']; ?>" name="delete_repassuser" id="<?= $data['id_reset_pass']; ?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-danger delete_repassuser"><i class="ft-delete"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?> 
                                    </tbody>
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalRepas" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" id="id-restpass" name="id-restpass" readonly>
                                                    <input type="hidden" id="nik-restpass" name="nik-restpass" readonly>
                                                    <label id="label-restpass"></label>
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
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function(){
    $(document).on('click', '.delete_repassuser', function(){  
        var nik_user = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEREPASNIK:nik_user},  
            dataType:"json",  
            success:function(data){
                $('#id-restpass').val(data.id_reset_pass);
                $('#nik-restpass').val(data.nik_reset);
                
                $('#label-restpass').html("Are you sure to delete data reset password NIK "+data.nik_reset);

                $('#deleteModalRepas').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>