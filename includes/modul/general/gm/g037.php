<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertmasterdat"])){
    if(InsertMasterDAT($_POST) > 0 ){
        $alert = array("Success!", "Master DAT Berhasil Ditambah", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["importmasterdat"])){
    if(UploadMasterDAT($_POST) > 0 ){
        $alert = array("Success!", "Master DAT Berhasil Diupload", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatemasterdat"])){
    if(UpdateMasterDAT($_POST) > 0 ){
        $datapost = isset($_POST["nomor-updkepdat"]) ? $_POST["nomor-updkepdat"] : NULL;
        $alert = array("Success!", "Master DAT ".$datapost." Berhasil Diupdate", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletemasterdat"])){
    if(DeleteMasterDAT($_POST) > 0 ){
        $datapost = isset($_POST["nomor-delkepdat"]) ? $_POST["nomor-delkepdat"] : NULL;
        $alert = array("Success!", "Master DAT ".$datapost." Berhasil Didelete", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatecheckmasterdat"])){
    if(UpdateCheckMasterDAT($_POST) > 0 ){
        $alert = array("Success!", "Master DAT berhasil di update", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletecheckmasterdat"])){
    if(DeleteCheckMasterDAT($_POST) > 0 ){
        $alert = array("Success!", "Master DAT berhasil di delete", "success", "$redirect");
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
                    <h4 class="card-title">Data Master Kepemilikan DAT</h4>
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
                        <!-- Normal Button -->
                        <button type="button" class="btn btn-primary btn-min-width mr-1" data-toggle="modal" data-target="#modalEntryDAT">Entry Master DAT</button>
                          <!-- /normal button -->

                        <!-- Button dropdowns with icons -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Import / Export Master DAT</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="reporting/report-file-masterdat.php?id=<?= encrypt($office_id.$dept_id);?>" target="_blank">Export Master DAT</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#modalUploadDAT" href="#">Import Master DAT</a>
                            </div>
                        </div>
                        <!-- /btn-group -->

                          <!-- Import Modal -->
                          <div class="modal fade text-left" id="modalUploadDAT" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post" enctype="multipart/form-data" role="form">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Import Data Master DAT</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-impmasdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-impmasdat" value="<?= $office_id;?>" class="form-control" readonly>
                                                <input type="hidden" name="dept-impmasdat" value="<?= $dept_id;?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>File : </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="file-impmasdat" required>
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" name="importmasterdat"
                                                class="btn btn-outline-info">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                        <!-- Modal Entry Master DAT -->
                        <div class="modal fade text-left" id="modalEntryDAT">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white">Entry Kepemilikan Data Aktiva Tetap</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input type="hidden" name="page-insmasdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-insmasdat" value="<?= $office_id;?>" class="form-control" readonly>
                                                <input type="hidden" name="dept-insmasdat" value="<?= $dept_id;?>" class="form-control" readonly>
                                                <table class="table table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">KODE - NAMA BARANG</th>
                                                        <th scope="col">NOMOR AKTIVA</th>
                                                        <th scope="col">QTY AKTIVA</th>
                                                        <th scope="col">TANGGAL PEROLEHAN</th>
                                                        <th><button type="button" name="add_master_aktiva" class="btn btn-success btn-xs add_master_aktiva"><i class="ft-plus"></i></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-master-aktiva">
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" name="insertmasterdat"
                                            class="btn btn-outline-primary">Save</button>
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
                                        $sql = "SELECT A.*, B.* FROM masterjenis AS A INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang ORDER BY B.NamaBarang ASC";
                                        $query = mysqli_query($conn, $sql);
                                        while($data = mysqli_fetch_assoc($query)) { ?>
                                            <option value="<?= $office_id.$dept_id.$data['IDBarang'].$data['IDJenis']; ?>">
                                                <?= $data['IDBarang'].$data['IDJenis']." - ".$data['NamaBarang']." ".$data['NamaJenis'];?>
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
                            <form action="" method="post" id="form-tabel-utama">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO AKTIVA</th>
                                        <th>TGL PEROLEHAN</th>
                                        <th>QTY AKTIVA</th>
                                        <th>KODE - KATEGORI BARANG</th>
                                        <th>STATUS DAT</th>
                                        <th>AKSI</th>
                                        <th>CHECK</th>
                                    </tr>
                                </thead>
                                <tbody class="datatable-src">
                                </tbody>
                            </table>
                            </form>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="modalUpdateDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="label-updkepdat"></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" name="page-updkepdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="id-updkepdat" id="id-updkepdat" class="form-control" readonly>
                                                    <input type="hidden" name="office-updkepdat" id="office-updkepdat" class="form-control" readonly>
                                                    <input type="hidden" name="dept-updkepdat" id="dept-updkepdat" class="form-control" readonly>
                                                    <input type="hidden" name="oldnomor-updkepdat" id="oldnomor-updkepdat" class="form-control" readonly>
                                                    <input type="hidden" name="oldstatus-updkepdat" id="oldstatus-updkepdat" class="form-control" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Tanggal Perolehan : </label>
                                                        <input type="date" name="tgl-updkepdat" id="tgl-updkepdat" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Nomor Aktiva : </label>
                                                        <input type="text" name="nomor-updkepdat" id="nomor-updkepdat" placeholder="Input nomor aktiva" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Qty Aktiva : </label>
                                                        <input type="number" name="qty-updkepdat" id="qty-updkepdat" placeholder="Input qty aktiva" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Master Barang : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="barang-updkepdat" id="barang-updkepdat" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php 
                                                                $sql_upd_kdat = "SELECT A.*, B.* FROM masterjenis AS A INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang ORDER BY B.NamaBarang ASC";
                                                                $query_upd_kdat = mysqli_query($conn, $sql_upd_kdat);
                                                                while($dat_upd_kdat = mysqli_fetch_assoc($query_upd_kdat)) { ?>
                                                                    <option value="<?= $dat_upd_kdat['IDBarang'].$dat_upd_kdat['IDJenis']; ?>">
                                                                        <?= $dat_upd_kdat['IDBarang'].$dat_upd_kdat['IDJenis']." - ".$dat_upd_kdat['NamaBarang']." ".$dat_upd_kdat['NamaJenis'];?>
                                                                    </option>
                                                                <?php 
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <?php
                                                    if ($id_group == $admin) { ?>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Status DAT : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="status-updkepdat" id="status-updkepdat" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <option value="Y">AKTIF</option>
                                                            <option value="N">TIDAK AKTIF</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="updatemasterdat" class="btn btn-outline-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="modalDeleteDAT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-danger white">
                                                <h4 class="modal-title white">Delete Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="page-delkepdat" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="id-delkepdat" id="id-delkepdat" class="form-control" readonly>
                                                <input type="hidden" name="office-delkepdat" id="office-delkepdat" class="form-control" readonly>
                                                <input type="hidden" name="dept-delkepdat" id="dept-delkepdat" class="form-control" readonly>
                                                <input type="hidden" name="barang-delkepdat" id="barang-delkepdat" class="form-control" readonly>
                                                <input type="hidden" name="nomor-delkepdat" id="nomor-delkepdat" class="form-control" readonly>
                                                <label id="label-delkepdat"></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="deletemasterdat" class="btn btn-outline-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update By Check -->
                            <div class="modal fade text-left" id="updatebrgcheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                    <form action="" method="post" id="form-updatecheck">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Update Master DAT Multiple</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-chkbarang" value="<?= $redirect; ?>" class="form-control" readonly>
                                            <input type="hidden" name="office-chkbarang" value="<?= $office_id; ?>" class="form-control" readonly>
                                            <input type="hidden" name="dept-chkbarang" value="<?= $dept_id; ?>" class="form-control" readonly>
                                            <div class="form-row" id="table-edtbarang-check">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatecheckmasterdat" class="btn btn-outline-primary">Update</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete By Check -->
                            <div class="modal fade text-left" id="deletebrgcheck" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                    <form action="" method="post" id="form-deletecheck">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white"
                                                id="myModalLabel">Delete Master DAT Multiple</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-chkbarang" value="<?= $redirect; ?>" class="form-control" readonly>
                                            <input type="hidden" name="office-chkbarang" value="<?= $office_id; ?>" class="form-control" readonly>
                                            <input type="hidden" name="dept-chkbarang" value="<?= $dept_id; ?>" class="form-control" readonly>
                                            <div class="form-row" id="table-dltbarang-check">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletecheckmasterdat" class="btn btn-outline-danger">Delete</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- <button type="button" title="Edit With Checkbox" onclick="return validateForm();" class="btn btn-primary btn-min-width mt-1 mb-2 pull-right">Edit By Checkbox</button> -->
                            <!-- Button dropdowns with icons -->
                            <div class="btn-group mt-1 mb-2 pull-right">
                                <button type="button" class="btn btn-primary btn-min-width dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Change Multiple Data</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="return validateForm('EDIT');" href="#">Update Data</a>
                                    <?php if ($id_group == $admin) { ?>
                                <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="return validateForm('DELETE');" href="#">Delete Data</a>
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
</section>
<!--/ Auto Fill table -->

<script>
$(document).ready(function(){

    var count = 0;

    $(document).on('click', '.add_master_aktiva', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="desc_master_aktiva[]" class="select2 form-control block desc_master_aktiva" style="width: 100%" required><option value="" selected disabled>Please Select</option><?= fill_select_pp(); ?></select></td>';
        html += '<td><input type="text" name="at_master_aktiva[]" class="form-control at_master_aktiva" placeholder="Input Nomor Aktiva" required/></td>';
        html += '<td><input type="number" min="1" step="1" name="qty_master_aktiva[]" class="form-control qty_master_aktiva" placeholder="Input Jumlah Qty Barang" required/></td>';
        html += '<td><input type="date" name="dok_master_aktiva[]" class="form-control dok_master_aktiva" placeholder="Input Tanggal Dokumen Aktif" required/></td>';
        html += '<td><button type="button" name="remove_master_aktiva" class="btn btn-danger btn-xs remove_master_aktiva"><i class="ft-minus"></i></button></td>';
        $('#table-master-aktiva').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_master_aktiva', function(){
        $(this).closest('tr').remove();
    });
});

$(document).ready(function(){
    load_data();
    function load_data(barang, keyword) {
        $.ajax({
            type:"POST",
            url:"action/datarequest.php",
            data: {MASBARANGSRC: barang, MASKEYSRC:keyword},
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
    $(document).on('click', '.update_kep_dat', function(){  
        var nomor_dat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIKEPDAT:nomor_dat},  
            dataType:"json",  
            success:function(data){
                $('#id-updkepdat').val(data.id_dat);
                $('#office-updkepdat').val(data.office_dat);
                $('#dept-updkepdat').val(data.dept_dat);
                $('#tgl-updkepdat').val(data.perolehan_dat);
                $('#oldnomor-updkepdat').val(data.no_dat);
                $('#nomor-updkepdat').val(data.no_dat);
                $('#qty-updkepdat').val(data.qty_dat);
                $('#oldstatus-updkepdat').val(data.status_dat);

                $('#barang-updkepdat').find('option[value="'+data.pluid_dat+'"]').remove();
                $('#barang-updkepdat').append($('<option></option>').html(data.pluid_dat+" - "+data.desc_dat).attr('value', data.pluid_dat).prop('selected', true));
                
                $('#status-updkepdat').find('option[value="'+data.status_dat+'"]').remove();
                $('#status-updkepdat').append($('<option></option>').html(data.status_dat == "Y" ? "AKTIF" : "TIDAK AKTIF").attr('value', data.status_dat).prop('selected', true));

                $('#label-updkepdat').html("Update Kepemilikan DAT Nomor : "+data.no_dat);
                $('#modalUpdateDAT').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_kep_dat', function(){  
        var nomor_dat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIKEPDAT:nomor_dat},  
            dataType:"json",  
            success:function(data){
                $('#id-delkepdat').val(data.id_dat);
                $('#office-delkepdat').val(data.office_dat);
                $('#dept-delkepdat').val(data.dept_dat);
                $('#nomor-delkepdat').val(data.no_dat);
                $('#barang-delkepdat').val(data.pluid_dat);
                
                $('#label-delkepdat').html("Nomor Seri DAT : "+data.no_dat);
                $('#modalDeleteDAT').modal('show');
            }  
        });
    });
});

function validateForm(aksi) {
    var count_checked = $('input[name="checkidmasdat[]"]:checked');
    if (count_checked.length == 0) {
        alert("Please check at least one checkbox");
        return false;
    }
    else {
        var groupid = "<?= $id_group; ?>"
        var offdep = "<?= $office_id.$dept_id; ?>"
        var array = []
        for (var i = 0; i < count_checked.length; i++) {
            array.push(count_checked[i].value)
        }
        $.ajax({
            type:'POST',
            url:'action/datarequest.php',
            data: {IDMASDATMULTIPLE:array, GROUPMASDATMULTIPLE:groupid, OFFDEPMASDATMULTIPLE:offdep, AKSIMASDATMULTIPLE:aksi},
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