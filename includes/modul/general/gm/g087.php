<?php

    $_SESSION['PRINTDOCUMENT'] = $_POST;

    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $div_id = $_SESSION['divisi'];
    $user = $_SESSION["user_name"];
    
    $page_id = $_GET['page'];
    
    $dec_page = decrypt(rplplus($page_id));
    $encpid = encrypt($dec_page);

    $redirect = "index.php?page=".$encpid;

    if(isset($_POST["insertdata"])){
        if(UploadDokumen($_POST) > 0 ){
            $datapost = isset($_POST["jenis-doc"]) ? $_POST["jenis-doc"] : NULL;
            $alert = array("Success!", "Data Dokumen ".$datapost." Berhasil ditambah", "success", "$redirect");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["updatedata"])){
        if(UpdateDokumen($_POST)){
            $datapost = isset($_POST["upd-nodoc"]) ? $_POST["upd-nodoc"] : NULL;
            $alert = array("Success!", "Data Dokumen ".$datapost." Berhasil diupdate", "success", "$redirect");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        if(DeleteDokumen($_POST)){
            $datapost = isset($_POST["no-doc"]) ? $_POST["no-doc"] : NULL;
            $alert = array("Success!", "Data Dokumen ".$datapost." Berhasil dihapus", "success", "$redirect");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Dokumen</h4>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#generatesn">Entry Data</button>
                                    <!-- Start Modal -->
                                    <div class="modal fade text-left" id="generatesn" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data Document</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="office-doc" value="<?= $office_id; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="dept-doc" value="<?= $dept_id; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="div-doc" value="<?= $div_id; ?>" readonly>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Jenis Dokumen : </label>
                                                                <select type="text" name="jenis-doc" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="BA">BERITA ACARA</option>
                                                                    <option value="SJ">SURAT JALAN</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>Sub Jenis Dokumen : </label>
                                                                <select type="text" name="sub-doc" class="select2 form-control block" style="width: 100%" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="O">KIRIM</option>
                                                                    <option value="I">TERIMA</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tanggal : </label>
                                                                <input type="date" name="tgl-doc" max="<?=date('Y-m-d')?>" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor Dokumen : </label>
                                                                <input class="form-control" type="text" name="nomor-doc" placeholder="Nomor dokumen (optional)">
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" type="text" name="ket-doc" placeholder="Input Keterangan" required></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>File Dokumen : </label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="file-doc" required>
                                                                    <label class="custom-file-label">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="insertdata" class="btn btn-outline-primary">Save</button>
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
                                    <th>No</th>
                                    <th>Docno</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Sub Jenis Dokumen</th>
                                    <th>Tanggal</th>
                                    <th>Nomor</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql = "SELECT A.*, B.office_name, C.department_name FROM dokumen AS A 
                            INNER JOIN office AS B ON A.office_doc = B.id_office
                            INNER JOIN department AS C ON A.dept_doc = C.id_department
                            WHERE A.office_doc = '$office_id' AND A.dept_doc = '$dept_id' AND A.div_doc = '$div_id' ORDER BY A.no_doc DESC";
                            $result = mysqli_query($conn, $sql);
                            while($data = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['no_doc']; ?></td>
                                    <td><?= $data['jenis_doc'] == 'BA' ? 'BERITA ACARA' : 'SURAT JALAN'; ?></td>
                                    <td><?= $data['subjenis_doc'] == 'O' ? 'KIRIM' : 'TERIMA'; ?></td>
                                    <td><?= $data['tgl_doc']; ?></td>
                                    <td><?= $data['nomor_doc']; ?></td>
                                    <td><?= $data['ket_doc']; ?></td>
                                    <td>
                                        <!-- Icon Button dropdowns -->
                                        <div class="btn-group mb-1">
                                            <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="files/doc/index.php?nomor=<?= encrypt($data['file_doc']);?>" onclick="document.location.href='<?= $redirect;?>'" target="_blank" title="Cetak Dokumen Nomor <?= $data['no_doc']; ?>" data-toggle="tooltip" data-placement="bottom">Print Document</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item update_doc" href="#" title="Edit Dokumen Nomor <?= $data['no_doc']; ?>" name="update_doc" id="<?= $data["id_doc"]; ?>" data-toggle="tooltip" data-placement="bottom">Update Document</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_doc" href="#" title="Hapus Dokumen Nomor <?= $data['no_doc']; ?>" name="delete_doc" id="<?= $data["id_doc"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Document</a>
                                            </div>
                                        </div>
                                        <!-- /btn-group -->
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="modalUpdateDocument" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="" method="post" enctype="multipart/form-data" role="form">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="upd-labeldoc"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" class="form-control" name="upd-pagedoc" value="<?= $redirect; ?>" readonly>
                                                <input type="hidden" class="form-control" id="upd-iddoc" name="upd-iddoc" readonly>
                                                <input type="hidden" class="form-control" id="upd-nodoc" name="upd-nodoc" readonly>
                                                <input type="hidden" class="form-control" id="upd-oldfiledoc" name="upd-oldfiledoc" readonly>
                                                <div class="col-md-6 mb-2">
                                                    <label>Jenis Dokumen : </label>
                                                    <select type="text" name="upd-jenisdoc" id="upd-jenisdoc" class="select2 form-control block" style="width: 100%" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="BA">BERITA ACARA</option>
                                                        <option value="SJ">SURAT JALAN</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Sub Jenis Dokumen : </label>
                                                    <select type="text" name="upd-subdoc" id="upd-subdoc" class="select2 form-control block" style="width: 100%" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="O">KIRIM</option>
                                                        <option value="I">TERIMA</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Tanggal : </label>
                                                    <input type="date" name="upd-tgldoc" id="upd-tgldoc" class="form-control" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nomor Dokumen : </label>
                                                    <input class="form-control" type="text" name="upd-nomordoc" id="upd-nomordoc" placeholder="Nomor dokumen (optional)">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" name="upd-ketdoc" id="upd-ketdoc" placeholder="Input keterangan (Optional)"></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>File Dokumen : </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="docfile-update">
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn btn-outline-success">Yes</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="modalDeleteDocument" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="del-iddoc" name="id-doc" readonly>
                                            <input type="hidden" class="form-control" id="del-filedoc" name="file-doc" readonly>
                                            <input type="hidden" class="form-control" id="del-nodoc" name="no-doc" readonly>
                                            <label id="del-labeldoc"></label>
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
    $(document).on('click', '.update_doc', function(){  
        var nomor_doc = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEDOC:nomor_doc},  
            dataType:"json",  
            success:function(data){
                $('#upd-iddoc').val(data.id_doc);
                $('#upd-tgldoc').val(data.tgl_doc);
                $('#upd-nodoc').val(data.no_doc);
                $('#upd-oldfiledoc').val(data.file_doc);
                $('#upd-nomordoc').val(data.nomor_doc);
                $('#upd-ketdoc').val(data.ket_doc);

                $('#upd-jenisdoc').find('option[value="'+data.jenis_doc+'"]').remove();
                $('#upd-jenisdoc').append($('<option></option>').html(data.jenis_doc == "BA" ? "BERITA ACARA" : "SURAT JALAN").attr('value', data.jenis_doc).prop('selected', true));
                
                $('#upd-subdoc').find('option[value="'+data.subjenis_doc+'"]').remove();
                $('#upd-subdoc').append($('<option></option>').html(data.subjenis_doc == "O" ? "KIRIM" : "TERIMA").attr('value', data.subjenis_doc).prop('selected', true));
                
                $('#upd-labeldoc').html("Update Document Nomor : "+data.no_doc);
                $('#modalUpdateDocument').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_doc', function(){  
        var nomor_doc = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEDOC:nomor_doc},  
            dataType:"json",  
            success:function(data){
                $('#del-iddoc').val(data.id_doc);
                $('#del-filedoc').val(data.file_doc);
                $('#del-nodoc').val(data.no_doc);
                
                $('#del-labeldoc').html("Delete Document Nomor : "+data.no_doc);
                $('#modalDeleteDocument').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>