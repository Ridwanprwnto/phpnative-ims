<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$offdep = $idoffice.$iddept;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["prosesdata"])){
    if(CreateProject($_POST) > 0 ){
        $alert = array("Success!", "Project berhasil dibuat", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
?>

<!-- Basic form layout section start -->
<section id="basic-select2">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Entry Data Project</h4>
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
                        <button type="button" class="btn btn-success square btn-min-width" data-toggle="modal" data-target="#proses-ppnb">Add Project</button>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data" role="form">
                            <div class="table-responsive">
                                <table class="table text-center" id="table_create_project">
                                    <thead>
                                        <tr>
                                            <th scope="col">URUTAN</th>
                                            <th scope="col">PIC / PELAKSANA BAGIAN</th>
                                            <th scope="col">PENGERJAAN</th>
                                            <th scope="col">JUMLAH</th>
                                            <th scope="col">TINGKAT KESULITAN</th>
                                            <th><button type="button" name="add_dataproject" class="btn btn-success btn-xs add_dataproject"><i class="ft-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                                <div class="modal fade text-left" id="proses-ppnb">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white">Create Data Project</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" name="page-project" value="<?= $redirect; ?>" readonly>
                                                    <input class="form-control" type="hidden" name="user-project" value="<?= $usernik;?>" readonly>
                                                    <input class="form-control" type="hidden" name="office-project" value="<?= $idoffice;?>" readonly>
                                                    <input class="form-control" type="hidden" name="dept-project" value="<?= $iddept;?>" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Diperintahkan / Disetujui</label>
                                                        <input type="text" class="form-control" name="perintah-project" placeholder="Input nama atasan yang menyetujui" required>

                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Tanggal Usulan</label>
                                                        <input type="date" class="form-control" name="tgl-project" max="<?=date('Y-m-d')?>" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Judul Proyek</label>
                                                        <textarea class="form-control" type="text" name="judul-project" placeholder="Judul project" required></textarea>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Prioritas</label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="urgensi-project" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="SECEPATNYA">SECEPATNYA</option>
                                                        <option value="BISA DIPENDING">BISA DIPENDING</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>File Document (Optional) </label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="file-project">
                                                            <label class="custom-file-label">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosesdata" class="btn btn-outline-success">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>

$(document).ready(function(){

    var count = 0;

    $(document).on('click', '.add_dataproject', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><input type="number" name="urutan_project[]" placeholder="Input nomor urutan pengerjaan" class="form-control urutan_project" required/></td>';
        html += '<td><input type="text" name="pic_project[]" placeholder="Input tim bagian pelaksana" class="form-control pic_project" required/></td>';
        html += '<td><textarea class="form-control pengerjaan_project" type="text" name="pengerjaan_project[]" placeholder="Input keterangan pengerjaan proyek" required></textarea></td>';
        html += '<td><input type="number" name="jumlah_project[]" placeholder="Jumlah pengerjaan" class="form-control jumlah_project" required/></td>';
        html += '<td><select type="text" name="priority_project[]" class="select2 form-control block priority_project" style="width: 100%" required><option value="" selected disabled>Please Select</option><option value="Low">Low</option></option><option value="Medium">Medium</option><option value="High">High</option></select></td>';
        html += '<td><button type="button" name="remove_project" class="btn btn-danger btn-xs remove_project"><i class="ft-minus"></i></button></td>';
        $('tbody').append(html);
    });

    $(document).on('click', '.remove_project', function(){
        $(this).closest('tr').remove();
    });
});

$(document).ready(function(){
    var input = document.getElementById("reqDate");
    var today = new Date();
    var day = today.getDate();

    // Set month to string to add leading 0
    var mon = new String(today.getMonth()+1); //January is 0!
    var yr = today.getFullYear();

    if(mon.length < 2) { mon = "0" + mon; }
    if(day.length < 2) { dayn = "0" + day; }

    var date = new String( yr + '-' + mon + '-' + day );

    input.disabled = false; 
    input.setAttribute('max', date);
});

</script>

<?php
    include ("includes/templates/alert.php");
?>