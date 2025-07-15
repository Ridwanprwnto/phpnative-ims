<?php
    
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];
    
    $page_id = $_GET['page'];
    
    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["entrydata"])){
        if(InsertMasterTeleBot($_POST) > 0 ){
            $datapost = isset($_POST["uname-masterbot"]) ? $_POST["uname-masterbot"] : NULL;
            $alert = array("Success!", "Data Master Telegram Bot ".$datapost." Berhasil Ditambah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        if(DeleteMasterTeleBot($_POST)){
            $datapost = isset($_POST["unamedel-mastertelebot"]) ? $_POST["unamedel-mastertelebot"] : NULL;
            $alert = array("Success!", "Data Master Telegram Bot ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Master Telegram BOT</h4>
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
                                    <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#mastertelebot">Entry Master Telebot</button>
                                    <!-- Start Modal -->
                                    <div class="modal fade text-left" id="mastertelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data Master Telegram BOT</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Username Telegram Bot : </label>
                                                                <input class="form-control" type="text" name="uname-masterbot" placeholder="Username telegram bot" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Token Telegram Bot : </label>
                                                                <input class="form-control" type="text" name="token-masterbot" placeholder="Token telegram bot" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Webhook Url : </label>
                                                                <textarea class="form-control" name="webhook-masterbot" type="text" placeholder="Input url webhook jika telegram bot menggunakan metode webhook (Optional)"></textarea>
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
                                    <th>Username Telegram BOT</th>
                                    <th>Token Telegram BOT</th>
                                    <th>Webhook Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql_mstrtele = "SELECT * FROM master_telebot";
                            $query_mstrtele = mysqli_query($conn, $sql_mstrtele);
                            while($data_mstrtele = mysqli_fetch_assoc($query_mstrtele)) {
                            ?>
                                <tr>
                                    <td><?= $data_mstrtele['uname_mstr_telebot']; ?></td>
                                    <td><?= $data_mstrtele['token_mstr_telebot']; ?></td>
                                    <td><?= $data_mstrtele['webhook_mstr_telebot']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger delete_mastertelebot"  name="delete_mastertelebot" id="<?= $data_mstrtele["id_mstr_telebot"]; ?>" title="Delete Data Master Telebot : <?= $data_mstrtele['uname_mstr_telebot']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalMastertelebot" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                            <input type="hidden" class="form-control" id="iddel-mastertelebot" name="iddel-mastertelebot" readonly>
                                            <input type="hidden" class="form-control" id="unamedel-mastertelebot" name="unamedel-mastertelebot" readonly>
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
    $(document).on('click', '.delete_mastertelebot', function(){  
        var id_mstr = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{MASTERTELEBOT:id_mstr},  
            dataType:"json",  
            success:function(data){
                $('#iddel-mastertelebot').val(data.id_mstr_telebot);
                $('#unamedel-mastertelebot').val(data.uname_mstr_telebot);
                
                $('#labeldel-mastertelebot').html("Delete Data Username Telegram BOT "+data.uname_mstr_telebot);
                $('#deleteModalMastertelebot').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>
