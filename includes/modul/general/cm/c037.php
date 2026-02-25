<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertgsheet"])){
    if(InsertSheetMaster($_POST)){
        $alert = array("Success!", "Berhasil Menambahkan Master Google Sheet", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST["updategsheet"])){
    if(UpdateSheetMaster($_POST)){
        $alert = array("Success!", "Berhasil Merubah Master Google Sheet", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST["deletegsheet"])){
    if(DeleteSheetMaster($_POST)){
        $alert = array("Success!", "Berhasil Menghapus Master Google Sheet", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST["syncrongsheet_masteraktiva"])){
    if(SyncronDataGSheetMasterAktiva($_POST)){
        $alert = array("Success!", "Berhasil Sync Data Google Sheet Master Aktiva", "success", "$redirect");
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
                    <h4 class="card-title">Master Google Sheet API</h4>
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
                        
                    <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#entryusertelegram" <?= $id_group == $admin ? "" : "disabled"; ?>>Entry Master Google Sheet</button>
                        <div class="modal fade text-left" id="entryusertelegram" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel">Entry Master Google Sheet</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-insgsheet" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Master Sheet : </label>
                                                    <input type="text" name="master-insgsheet" placeholder="Subject Data Sheet" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Link Google Sheet : </label>
                                                    <input type="text" name="link-insgsheet" placeholder="Enter URL" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Link ID Google Sheet : </label>
                                                    <textarea class="form-control" name="linkid-insgsheet" type="text" placeholder="Enter URL ID"></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Flag Sync : </label>
                                                    <select name="status-insgsheet" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="Y" >Yes</option>
                                                        <option value="N" >No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertgsheet" class="btn btn-outline-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <table class="table display nowrap table-striped table-bordered scroll-horizontal text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Data Sheet</th>
                                    <th>Link Sheet ID</th>
                                    <th>Flag Sync Data</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                $sql_sheet = "SELECT * FROM sheet";
                                $query_sheet = mysqli_query($conn, $sql_sheet);
                                while($data_sheet = mysqli_fetch_assoc($query_sheet)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data_sheet['doc_sheet']; ?></td>
                                    <td><?= $data_sheet['subject_sheet']; ?></td>
                                    <td><?= $data_sheet['link_sheet'].$data_sheet['linkid_sheet']; ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data_sheet['flagsync_sheet'] == "N" ? "danger" : "info"; ?> "><?= $data_sheet['flagsync_sheet'] == "N" ? "No" : "Yes"; ?></div>
                                    </td>
                                    <td>
                                        <a title="Link Google Sheet <?= $data_sheet["subject_sheet"]; ?>" href="<?= $data_sheet["link_sheet"].$data_sheet['linkid_sheet']; ?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="bottom"><i class="ft-external-link"></i>
                                        </a>
                                    <?php
                                        if ($data_sheet['flagsync_sheet'] == "Y") { ?>
                                        <button type="button" class="btn btn-icon btn-warning syncron_gsheet" title="Syncron Data Sheet <?= $data_sheet["subject_sheet"]; ?>" data-toggle="tooltip" data-placement="bottom" name="syncron_gsheet" id="<?= $data_sheet["id_sheet"]; ?>" <?= $data_sheet['flagsync_sheet'] == "N" ? "disabled" : ""; ?>><i class="ft-repeat"></i></button>                    
                                    <?php
                                        }
                                    ?>
                                        <button type="button" class="btn btn-icon btn-success update_gsheet" title="Update Data Sheet <?= $data_sheet["subject_sheet"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_gsheet" id="<?= $data_sheet["id_sheet"]; ?>" <?= $id_group == $admin ? "" : "disabled"; ?>><i class="ft-edit"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger delete_gsheet" title="Delete Data Sheet <?= $data_sheet["subject_sheet"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_gsheet" id="<?= $data_sheet["id_sheet"]; ?>" <?= $id_group == $admin ? "" : "disabled"; ?>><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Sync -->
                            <div class="modal fade text-left" id="synModalGSheet" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning white">
                                            <h4 class="modal-title white">Syncron Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-syngsheet" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" name="office-syngsheet" value="<?= $office_id; ?>" class="form-control" readonly>
                                                <input type="hidden" name="dept-syngsheet" value="<?= $dept_id; ?>" class="form-control" readonly>
                                                <input type="hidden" id="id-syngsheet" name="id-syngsheet" class="form-control" readonly>
                                                <input type="hidden" id="subject-syngsheet" name="subject-syngsheet" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Data Sheet : </label>
                                                    <select id="data-syngsheet" name="data-syngsheet" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" id="btn-syncrongsheet" name="syncrongsheet" class="btn btn-outline-warning" disabled>Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalGSheet" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white">Update Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" name="page-updgsheet" value="<?= $redirect; ?>" class="form-control" readonly>
                                                <input type="hidden" id="id-updgsheet" name="id-updgsheet" class="form-control" readonly>
                                                <input type="hidden" id="tmpsubject-updgsheet" name="tmpsubject-updgsheet" class="form-control" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>Data Sheet : </label>
                                                    <input type="text" id="subject-updgsheet" name="subject-updgsheet" class="form-control" placeholder="Subject Data Sheet" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Link : </label>
                                                    <input type="text" id="link-updgsheet" name="link-updgsheet" class="form-control" placeholder="Enter URL" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Link ID : </label>
                                                    <textarea class="form-control" id="linkid-updgsheet" name="linkid-updgsheet" type="text" placeholder="Enter URL ID" required></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Flag Sync : </label>
                                                    <select id="sync-updgsheet" name="sync-updgsheet" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="Y" >Yes</option>
                                                        <option value="N" >No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updategsheet" class="btn btn-outline-success">Yes</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalGSheet" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-delgsheet" value="<?= $redirect; ?>" class="form-control" readonly>
                                            <input type="hidden" id="id-delgsheet" name="id-delgsheet" class="form-control" readonly>
                                            <input type="hidden" id="subject-delgsheet" name="subject-delgsheet" class="form-control" readonly>
                                            <label id="label-delgsheet"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="deletegsheet" class="btn btn-outline-danger">Yes</button>
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
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.syncron_gsheet', function(){
        var IDsheet = $(this).attr("id");

        $.ajax({
            url: "action/datarequest.php",
            method: "POST",
            data: { AKSIMASTERSHEET: IDsheet },
            dataType: "json",
            success: function(data) {
                // Set hidden fields
                $('#id-syngsheet').val(data.id_sheet);
                $('#subject-syngsheet').val(data.subject_sheet);

                // Clear dropdown dulu sebelum append agar tidak duplikat
                $('#data-syngsheet').find('option:not(:disabled)').remove();

                // Mapping subject ke daftar sheet yang tersedia
                var sheetOptions = {
                    "MASTER AKTIVA IMS": [
                        { label: "SHEET_MASTERAKTIVA_IMS",    value: "SHEET_MASTERAKTIVA_IMS" }
                    ],
                    // Tambahkan subject lain di sini jika ada
                    // "MASTER LAINNYA": [
                    //     { label: "SHEET_LAINNYA", value: "SHEET_LAINNYA" }
                    // ]
                };

                var options = sheetOptions[data.subject_sheet];

                if (options && options.length > 0) {
                    $.each(options, function(index, sheet) {
                        $('#data-syngsheet').append(
                            $('<option></option>').text(sheet.label).val(sheet.value)
                        );
                    });

                    // Auto select option pertama
                    $('#data-syngsheet option:first').prop('selected', true);

                    $('#synModalGSheet').modal('show');
                } else {
                    alert('Tidak ada konfigurasi sheet untuk subject: ' + data.subject_sheet);
                }
            },
            error: function(xhr, status, error) {
                alert('Gagal mengambil data: ' + error);
            }
        });
    });

    var sheetConfig = {
        "SHEET_MASTERAKTIVA_IMS": {
            btnName: 'syncrongsheet_masteraktiva'
        }
    };

    $(document).on('change', '#data-syngsheet', function(){
        var selectedVal = $(this).val();
        var btnSubmit   = $('#btn-syncrongsheet');

        // Reset ke default jika belum pilih
        if (!selectedVal || selectedVal === "") {
            btnSubmit.attr('name', 'syncrongsheet').prop('disabled', true);
            return;
        }

        var config = sheetConfig[selectedVal];

        if (config) {
            btnSubmit.attr('name', config.btnName).prop('disabled', false);
        }
    });

    // Reset saat modal ditutup
    $('#synModalGSheet').on('hidden.bs.modal', function(){
        $('#btn-syncrongsheet').attr('name', 'syncrongsheet').prop('disabled', true);
        $('#data-syngsheet').find('option:not(:disabled)').remove();
        $('#data-syngsheet').val('');
    });
});

$(document).ready(function(){
    $(document).on('click', '.update_gsheet', function(){  
        var IDsheet = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIMASTERSHEET:IDsheet},  
            dataType:"json",  
            success:function(data){
                $('#id-updgsheet').val(data.id_sheet);
                $('#tmpsubject-updgsheet').val(data.subject_sheet);
                $('#subject-updgsheet').val(data.subject_sheet);
                $('#link-updgsheet').val(data.link_sheet);
                $('#linkid-updgsheet').val(data.linkid_sheet);

                $('#sync-updgsheet').find('option[value="'+data.flagsync_sheet+'"]').remove();
                
                if (data.flagsync_sheet == "Y") {
                    var $name_sts = "Yes";
                }
                else {
                    var $name_sts = "No";
                }
                
                $('#sync-updgsheet').append($('<option></option>').html($name_sts).attr('value', data.flagsync_sheet).prop('selected', true));

                $('#updateModalGSheet').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_gsheet', function(){  
        var IDrole = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{AKSIMASTERSHEET:IDrole},  
            dataType:"json",  
            success:function(data){
                $('#id-delgsheet').val(data.id_sheet);
                $('#subject-delgsheet').val(data.subject_sheet);
                
                $('#label-delgsheet').html("Delete Data Master Google Sheet "+data.subject_sheet);

                $('#deleteModalGSheet').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>