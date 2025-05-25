<?php
    
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];
    
    $page_id = $_GET['page'];
    
    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["entrydata"])){
        if(InsertMasterServiceAPI($_POST) > 0 ){
            $datapost = isset($_POST["name-apiservice"]) ? $_POST["name-apiservice"] : NULL;
            $alert = array("Success!", "Data Master Service API ".$datapost." Berhasil Ditambah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        if(DeleteMasterServiceAPI($_POST)){
            $datapost = isset($_POST["unamedel-masterapi"]) ? $_POST["unamedel-masterapi"] : NULL;
            $alert = array("Success!", "Data Master Service API ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Master API Services</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#masterapi">Entry Data API</button>
                                    <!-- Start Modal -->
                                    <div class="modal fade text-left" id="masterapi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data API</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Service Name : </label>
                                                                <input class="form-control" type="text" name="name-apiservice" placeholder="Enter service name" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>URL API : </label>
                                                                <textarea class="form-control" name="url-apiservice" type="text" placeholder="Enter URL"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="entrydata" class="btn btn-outline-primary">Save</button>
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
                                    <th>Service Name</th>
                                    <th>API Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql_mstrapi = "SELECT * FROM service_api";
                            $query_mstrapi = mysqli_query($conn, $sql_mstrapi);
                            while($data_mstrapi = mysqli_fetch_assoc($query_mstrapi)) {
                            ?>
                                <tr>
                                    <td><?= $data_mstrapi['name_srv_api']; ?></td>
                                    <td><?= $data_mstrapi['url_srv_api']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger delete_masterapi"  name="delete_masterapi" id="<?= $data_mstrapi["id_srv_api"]; ?>" title="Delete Data Service API : <?= $data_mstrapi['name_srv_api']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalMasterAPI" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="iddel-masterapi" name="iddel-masterapi" readonly>
                                            <input type="hidden" class="form-control" id="unamedel-masterapi" name="unamedel-masterapi" readonly>
                                            <label id="labeldel-mastertelebot"></label>
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
<!-- // Basic form layout section end -->

<script>
$(document).ready(function(){
    $(document).on('click', '.delete_masterapi', function(){  
        var id_mstr = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{MASTERSERVICEAPI:id_mstr},  
            dataType:"json",  
            success:function(data){
                $('#iddel-masterapi').val(data.id_srv_api);
                $('#unamedel-masterapi').val(data.name_srv_api);
                
                $('#labeldel-masterapi').html("Delete Data Service API "+data.name_srv_api);
                $('#deleteModalMasterAPI').modal('show');
            }
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>