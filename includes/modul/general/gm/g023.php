<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["terimabarangservice"])){
    if(ReceiveBarangService($_POST) > 0 ){
        $alert = array("Success!", "Pengajuan Perbaikan Barang Selesai Proses Penerimaan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatacheck"])){
    if(ReceiveBarangCheck($_POST) > 0 ){
        $alert = array("Success!", "Pengajuan Perbaikan Barang Selesai Proses Penerimaan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(CancelBarang($_POST) > 0 ){
        $alert = array("Success!", "Pengajuan Perbaikan Barang Berhasil Dibatalkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatacheck"])){
    if(CancelBarangCheck($_POST) > 0 ){
        $alert = array("Success!", "Pengajuan Perbaikan Barang Berhasil Dibatalkan", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}

$query_servnull = mysqli_query($conn, "SELECT penerima_sj, tgl_penerimaan, kondisi_perbaikan FROM detail_surat_jalan WHERE LEFT(from_sj, 4) = '$idoffice' AND RIGHT(from_sj, 4) = '$iddept' AND penerima_sj IS NULL AND tgl_penerimaan IS NULL AND kondisi_perbaikan IS NULL");
$data_servnull = mysqli_fetch_assoc($query_servnull);

?>

<!-- Basic form layout section start -->
<section id="basic-select2">
    <!-- Striped rows start -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monitoring Perbaikan / Rekomendasi Pemusnahan</h4>
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
                        <div class="table-responsive">
                            <form action="" method="post">
                                <table class="table display nowrap table-striped table-bordered zero-configuration row-grouping-monservice">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NOMOR</th>
                                            <th>NAMA BARANG</th>
                                            <th>SERIAL NUMBER</th>
                                            <th>NO AKTIVA</th>
                                            <th>KETERANGAN</th>
                                            <th>AKSI</th>
                                            <th>CHECK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nol = 0;
                                        $no = 1;
                                        $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.*, H.id_office AS id_office_to, H.office_name AS office_name_to, I.id_department AS id_dept_to, I.department_name AS dept_name_to FROM surat_jalan AS A
                                        INNER JOIN detail_surat_jalan AS B ON A.no_sj = B.head_no_sj
                                        INNER JOIN office AS C ON LEFT(A.asal_sj, 4) = C.id_office
                                        INNER JOIN department AS D ON RIGHT(A.asal_sj, 4) = D.id_department
                                        LEFT JOIN users AS E ON A.user_sj = E.nik
                                        INNER JOIN mastercategory AS F ON LEFT(B.pluid_sj, 6) = F.IDBarang
                                        INNER JOIN masterjenis AS G ON RIGHT(B.pluid_sj, 4) = G.IDJenis
                                        INNER JOIN office AS H ON LEFT(A.tujuan_sj, 4) = H.id_office
                                        INNER JOIN department AS I ON RIGHT(A.tujuan_sj, 4) = I.id_department
                                        WHERE LEFT(A.no_sj, 1) = 'R' AND LEFT(A.asal_sj, 4) = '$idoffice' AND RIGHT(A.asal_sj, 4) = '$iddept' AND B.status_sj = 'N' ORDER BY A.tanggal_sj DESC";
                                        $query = mysqli_query($conn, $sql);
                                        while ($data = mysqli_fetch_assoc($query)) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $no++; ?></th>
                                            <td>
                                                <h6 class="mb-0"><?= $data["keperluan_sj"] == "PS" ? "PENGAJUAN PERBAIKAN BARANG" : "PENGAJUAN REKOMENDASI PEMUSNAHAN"; ?>
                                                    <span class="text-bold-600">NOMOR SJ : <?= substr($data["no_sj"], 1, 5); ?></span> on
                                                    <em><?= $data['tanggal_sj']; ?></em>
                                                </h6>
                                            </td>
                                            <td><?= $data["pluid_sj"].' - '.$data["NamaBarang"].' '.$data["NamaJenis"].' '.$data["merk_sj"].' '.$data["tipe_sj"];?>
                                            </td>
                                            <td><?= $data["sn_sj"];?></td>
                                            <td><?= $data["at_sj"];?></td>
                                            <td><?= $data["keterangan_sj"];?></td>
                                            <td>
                                                <button title="Receive Data Perbaikan Barang <?= $data["sn_sj"];?>" type="button" id="<?= $data["detail_no_sj"];?>" name="update_monservice" class="btn btn-icon btn-success update_monservice" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                                <button title="Cancel Data Perbaikan Barang <?= $data["sn_sj"];?>" type="button" id="<?= $data["detail_no_sj"];?>" name="delete_monservice" class="btn btn-icon btn-danger delete_monservice" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                            </td>
                                            <td class="icheck1">
                                                <input type="checkbox" name="checkidsj[]" id="checkidsj" value="<?= $data['detail_no_sj']; ?>">
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <!-- Modal Update -->
                                    <div class="modal fade text-left" id="updateModalMonService" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form action="" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="upd-labelmonservice"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="page-servbarang" value="<?= $encpid; ?>" readonly>
                                                            <input class="form-control" type="hidden" name="modifref-servbarang" value="<?= $arrmodifref[6]; ?>" readonly>
                                                            <input class="form-control" type="hidden" id="upd-idmonservice" name="id-servbarang" readonly>
                                                            <input class="form-control" type="hidden" id="upd-frommonservice" name="offdep-servbarang" readonly>
                                                            <input class="form-control" type="hidden" id="upd-docnomonservice" name="terima-servbarang" readonly>
                                                            <input class="form-control" type="hidden" id="upd-kodemonservice" name="pluid-servbarang" readonly>
                                                            <input class="form-control" type="hidden" id="upd-snmonservice" name="sn-servbarang" readonly>
                                                            <input class="form-control" type="hidden" id="upd-atmonservice" name="at-servbarang" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Penerima Barang : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="user-servbarang">
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="<?=$usernik;?>" ><?= $usernik.' - '.strtoupper($username);?></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Hasil Pemeriksaan : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" name="kond-servbarang">
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="<?= $arrcond[1]; ?>" >Dalam Kondisi Baik > Cadangan</option>
                                                                    <option value="<?= $arrcond[2]; ?>" >Tidak Bisa Digunakan > Rusak</option>
                                                                    <option value="<?= $arrcond[4]; ?>" >Usulan Musnah > P3AT</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Posisi Update :</label>
                                                                <textarea class="form-control" type="text" name="posisi-servbarang" placeholder="Posisi barang saat ini"></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Keterangan :</label>
                                                                <textarea class="form-control" type="text" name="keterangan-servbarang" placeholder="Input Keterangan Hasil Pengecekan Setelah Terima Barang / Hasil Follow Up Barang Tersebut (Optional)"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="terimabarangservice" class="btn btn-outline-success">Yes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalMonService" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="del-labelmonservice"></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" id="del-docnomonservice" name="no-brgserv" readonly>
                                                    <input class="form-control" type="hidden" id="del-idmonservice" name="id-brgserv" readonly>
                                                    <input class="form-control" type="hidden" id="del-kodemonservice" name="plu-brgserv" readonly>
                                                    <input class="form-control" type="hidden" id="del-snmonservice" name="sn-brgserv" readonly>
                                                    <input class="form-control" type="hidden" id="del-atmonservice" name="at-brgserv" readonly>
                                                    <input class="form-control" type="hidden" name="kondisi-brgserv" value="<?= $arrcond[2]; ?>" readonly>
                                                    <label>Are you sure to cancel this data?</label>
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
                                    <!-- Modal Update By Check -->
                                    <div class="modal fade text-left" id="updatecheckdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info white">
                                                    <h4 class="modal-title white" id="myModalLabel">Receive Barang By Checkbox</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="page-servbarang" value="<?= $encpid; ?>" readonly>
                                                        <input class="form-control" type="hidden" name="modifref-servbarang" value="<?= $arrmodifref[6]; ?>" readonly>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Penerima Barang : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="user-servbarang">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="<?=$usernik;?>" ><?= $usernik.' - '.strtoupper($username);?></option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Status Perbaikan : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="kond-servbarang">
                                                                <option value="" selected disabled>Please Select</option>
                                                                <option value="<?= $arrcond[1]; ?>" >Dalam Kondisi Baik > Cadangan</option>
                                                                <option value="<?= $arrcond[2]; ?>" >Tidak Bisa Digunakan > Rusak</option>
                                                                <option value="<?= $arrcond[4]; ?>" >Usulan Musnah > P3AT</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Posisi Update :</label>
                                                            <textarea class="form-control" type="text" name="posisi-servbarang" placeholder="Posisi barang saat ini"></textarea>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Keterangan :</label>
                                                            <textarea class="form-control" type="text" name="ket-servbarang" placeholder="Input Keterangan Hasil Pengecekan Setelah Terima Barang (Optional)"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="updatedatacheck" onclick="return validateForm();" class="btn btn-outline-info">Yes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete By Check -->
                                    <div class="modal fade text-left" id="deletecheckdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                            <!-- <form action="" method="post"> -->
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel">Cancel Data By Check</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <label>Are you sure to delete the selected data?</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatacheck" onclick="return validateForm();" class="btn btn-outline-danger">Delete</button>
                                                </div>
                                            <!-- </form> -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </form>
                            <!-- Button dropdowns with icons -->
                            <div class="btn-group mt-1 mb-2 mr-1 pull-right">
                                <button type="button" title="Action With Checkbox" class="btn btn-<?= isset($data_servnull) ? 'info' : 'secondary'; ?> btn-min-width dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" <?= isset($data_servnull) ? '' : 'Disabled'; ?>>Receive By Checkbox</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#updatecheckdata" href="#" title="Receive Data With Checkbox">Receive</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#deletecheckdata" href="#" title="Cancel Data With Checkbox">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>

$(document).ready(function() {
    
    $('.row-grouping-monservice').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,

        info: true,
        searching: false,
        ordering: true,
        paging: false,
        scrollCollapse: true,
        scrollY: '50vh',
        "columnDefs": [
            { "visible": false, "targets": 1 },
        ],
        // "order": [[ 2, 'desc' ]],
        "displayLength": 10,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    } );

    $('.row-grouping-monservice tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 1 && currentOrder[1] === 'asc' ) {
                table.order( [ 1, 'desc' ] ).draw();
            }
            else {
                table.order( [ 1, 'asc' ] ).draw();
            }
        }
    });

});

$(document).ready(function(){
    $(document).on('click', '.update_monservice', function(){  
        var id_sj = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMONSERVICE:id_sj},  
            dataType:"json",  
            success:function(data){
                $('#upd-idmonservice').val(data.detail_no_sj);
                $('#upd-frommonservice').val(data.from_sj);
                $('#upd-docnomonservice').val(data.no_sj);
                $('#upd-kodemonservice').val(data.pluid_sj);
                $('#upd-snmonservice').val(data.sn_sj);
                $('#upd-atmonservice').val(data.at_sj);
                
                $('#upd-labelmonservice').html("Receive Barang PBRP SN : "+data.sn_sj);
                $('#updateModalMonService').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_monservice', function(){  
        var id_sj = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMONSERVICE:id_sj},  
            dataType:"json",  
            success:function(data){
                $('#del-idmonservice').val(data.detail_no_sj);
                $('#del-docnomonservice').val(data.no_sj);
                $('#del-kodemonservice').val(data.pluid_sj);
                $('#del-snmonservice').val(data.sn_sj);
                $('#del-atmonservice').val(data.at_sj);
                
                $('#del-labelmonservice').html("Cancel Barang PBRP SN : "+data.sn_sj);
                $('#deleteModalMonService').modal('show');
            }  
        });
    });
});

function validateForm() {
    var count_checked = $("input[name='checkidsj[]']:checked").length;
    if (count_checked == 0) {
        alert("Please check at least one checkbox");
        return false;
    } else {
        return true;
    }
}
</script>

<?php
    include ("includes/templates/alert.php");
?>