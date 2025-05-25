<?php
$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);
$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$_SESSION['WATCHPLG'] = $dec_page;

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["updatedata"])){
    if(ApprovePelanggaranCCTV($_POST) > 0 ){
        $datapost = isset($_POST["no-plg"]) ? $_POST["no-plg"] : NULL;
        $alert = array("Success!", "Sanksi Atas Pelanggaran CCTV Nomor ".$datapost." Berhasil di Approve", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["rejectdata"])){
    if(RejectPelanggaranCCTV($_POST) > 0 ){
        $datapost = isset($_POST["no-plg"]) ? $_POST["no-plg"] : NULL;
        $alert = array("Success!", "Data Pelanggaran CCTV Nomor ".$datapost." Berhasil di Reject", "success", "$redirect");
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
                    <h4 class="card-title">All Data Pelanggaran CCTV Menunggu Approval</h4>
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
                        <form class="form" action="" method="post">
                            <div class="form-body">
                                <h4 class="form-section"><i class="la la-search"></i>Check Users Tercatat Pelanggaran</h4>
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2 ml-2">
                                        <select id="check-user-pelanggar" name="check-user-pelanggar" class="select2 form-control block" style="width: 100%" data-placeholder="Please Select" type="text" required>
                                            <option value="" selected disabled>Please Select</option>
                                            <?php 
                                                $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01' ORDER BY username ASC");
                                                while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                <option value="<?= $idoffice.$iddept.$data_user['nik']." - ".strtoupper($data_user['username']); ?>"><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                            <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <h4 class="form-section"><i class="la la-gavel"></i>Approve Pelanggaran</h4>
                                <div class="row">
                                    <div class="form-group col-md-12 mb-0">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered row-grouping-approve">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pelanggaran</th>
                                    <th>Tgl Waktu Kejadian</th>
                                    <th>Bagian</th>
                                    <th>Kategori Pelanggaran</th>
                                    <th>Lokasi CCTV</th>
                                    <th>Pelapor</th>
                                    <th>Atasan Yg Memfollow Up</th>
                                    <th>Rekaman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $result = "SELECT A.*, B.id_office, C.id_department, D.id_divisi, D.divisi_name, J.username, K.name_fup_plg, L.username AS user_proses FROM pelanggaran_cctv AS A
                            INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
                            INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
                            INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
                            INNER JOIN users AS J ON A.user_plg_cctv = J.nik
                            INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
                            INNER JOIN users AS L ON A.proses_plg_cctv = L.nik
                            WHERE A.office_plg_cctv = '$idoffice' AND A.dept_plg_cctv = '$iddept' AND A.status_plg_cctv = 'N' ORDER BY A.tgl_plg_cctv DESC";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><strong><?= $data['no_plg_cctv']; ?></strong></td>
                                    <td><?= $data['tgl_plg_cctv']; ?></td>
                                    <td><?= $data['divisi_name']; ?></td>
                                    <td>
                                        <h5 class="mb-0">
                                        <span class="text-bold-600"><?= $data['ctg_plg_cctv']; ?></span>
                                        <em></em>
                                        </h5>
                                    </td>
                                    <td><?= $data['lokasi_plg_cctv']; ?></td>
                                    <td><?= $data['user_plg_cctv']." - ".strtoupper($data['username']); ?></td>
                                    <td><?= $data['proses_plg_cctv']." - ".strtoupper($data['user_proses']); ?></td>
                                    <td>
                                        <a title="<?= $data['rekaman_plg_cctv'] != NULL ? 'Lihat Rekaman Pelanggaran Nomor : '.$data['no_plg_cctv'] : ''; ?>" data-toggle="tooltip" data-placement="bottom" onclick="window.open('', 'popupwindow', 'scrollbars=yes,resizable=yes,width=auto,height=auto');return true" target="popupwindow" href="<?= $data['rekaman_plg_cctv'] != NULL ? "files/record/index.php?id=".encrypt($data['rekaman_plg_cctv']) : '#'; ?>" class="<?= $data['rekaman_plg_cctv'] != NULL ? 'btn btn-icon btn-warning' : ''; ?>"><i class="<?= $data['rekaman_plg_cctv'] != NULL ? 'ft-film' : ''; ?>"></i></a>
                                    </td>
                                    <td>
                                        <span class="dropdown">
                                            <button id="idaction<?= $data['no_plg_cctv']; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="idaction<?= $data['no_plg_cctv']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <a href="javascript:void(0);" id="<?= $data['id_plg_cctv']; ?>" name="approve_dataplgcctv" title="Approve Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" class="dropdown-item approve_dataplgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-check-square"></i>Approve</a>
                                                <a href="javascript:void(0);" id="<?= $data['id_plg_cctv']; ?>" name="reject_dataplgcctv" title="Reject Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" class="dropdown-item reject_dataplgcctv" data-toggle="tooltip" data-placement="bottom"><i class="ft-x-square"></i>Reject</a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="ModalApprovePlgCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="approve-labelplgcctv"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" id="appv-idplgcctv" name="id-plg" readonly>
                                                <input class="form-control" type="hidden" id="appv-noplgcctv" name="no-plg" readonly>
                                                <input class="form-control" type="hidden" name="app-atasan" value="<?= $nik; ?>" readonly>
                                                <div class="col-md-6 mb-2">
                                                    <label>Tgl Kejadian : </label>
                                                    <input type="text" class="form-control" id="appv-tglplgcctv" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Waktu Kejadian : </label>
                                                    <input type="text" class="form-control" id="appv-wktplgcctv" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Kategori Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="appv-catplgcctv" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Jenis Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="appv-jnsplgcctv" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Kejadian :</label>
                                                    <textarea class="form-control" type="text" id="appv-kejadianplgcctv" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" id="appv-ketplgcctv" disabled></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Lokasi CCTV : </label>
                                                    <input type="text" class="form-control" id="appv-lokasiplgcctv" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>User Pelapor Pelanggaran : </label>
                                                    <input type="text" class="form-control" id="appv-pelaporplgcctv" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Atasan Yang Memfollow Up : </label>
                                                    <input type="text" class="form-control" id="appv-fupplgcctv" disabled>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>User Pelanggar : </label>
                                                    <textarea class="form-control" type="text" id="appv-pelanggarplgcctv" disabled></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Sanksi Pelanggaran : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" id="appv-sanksiplgcctv" name="fup-plg" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_fup = mysqli_query($conn, "SELECT * FROM fup_pelanggaran WHERE id_fup_plg NOT LIKE '2'");
                                                            while($data_fup = mysqli_fetch_assoc($query_fup)) { ?>
                                                            <option value="<?= $data_fup['id_fup_plg']; ?>"><?= $data_fup['name_fup_plg'];?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Penjelasan :</label>
                                                    <textarea class="form-control" type="text" id="appv-penjelasanplgcctv" name="fup-penjelasan" placeholder="Penjelasan atas user ybs melakukan pelanggaran (Optional)"><?= $data['penjelasan_plg_cctv']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn btn-outline-primary">Approve</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="ModalRejectPlgCCTV" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="reject-labelplgcctv"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="reject-idplgcctv" name="id-plg" readonly>
                                            <input type="hidden" class="form-control" id="reject-noplgcctv" name="no-plg" readonly>
                                            <label>Data pelanggaran yang di reject akan di kembalikan ke menu Follow Up Pelanggaran CCTV.</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="rejectdata" class="btn btn-outline-danger">Yes</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                        <!-- Modal Read -->
                        <div class="modal fade text-left" id="detailSanksiPelanggaranCCTV" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header bg-secondary white">
                                        <h4 class="modal-title white"
                                            id="myModalLabel">Detail Data Approval User Terekam Pelanggaran CCTV</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modal_readdatasnkplg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function () {
    $("#check-user-pelanggar").on('change', function () {
        var id_users = $('#check-user-pelanggar').val();
        var data = "DETAILDATASANKSIPLG=" + id_users;
        if (id_users) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: data,
                success:function(data){  
                    $('#modal_readdatasnkplg').html(data);
                    $('#detailSanksiPelanggaranCCTV').modal('show');
                }  
            });
        }
    });
});

$(document).ready(function() {
    
    $('.row-grouping-approve').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,
        "columnDefs": [
            { "visible": false, "targets": 4 },
        ],
        // "order": [[ 2, 'desc' ]],
        "displayLength": 50,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(4, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="11">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    } );

    $('.row-grouping-approve tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 4 && currentOrder[1] === 'asc' ) {
                table.order( [ 4, 'desc' ] ).draw();
            }
            else {
                table.order( [ 4, 'asc' ] ).draw();
            }
        }
    });

});

$(document).ready(function(){
    $(document).on('click', '.approve_dataplgcctv', function(){  
        var id_plgcctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEMODALPLG:id_plgcctv},  
            dataType:"json",  
            success:function(data){
                $('#appv-idplgcctv').val(data.id_plg_cctv);
                $('#appv-noplgcctv').val(data.no_plg_cctv);
                $('#appv-tglplgcctv').val(data.data_tgl_plg);
                $('#appv-wktplgcctv').val(data.data_wkt_plg);
                $('#appv-catplgcctv').val(data.ctg_plg_cctv);
                $('#appv-jnsplgcctv').val(data.jns_plg_cctv);
                $('#appv-kejadianplgcctv').val(data.kejadian_plg_cctv);
                $('#appv-ketplgcctv').val(data.ket_plg_cctv);
                $('#appv-lokasiplgcctv').val(data.lokasi_plg_cctv);
                $('#appv-pelaporplgcctv').val(data.user_plg_cctv+" - "+data.pelapor_cctv.toUpperCase());
                $('#appv-fupplgcctv').val(data.proses_plg_cctv+" - "+data.fup_cctv.toUpperCase());
                $('#appv-pelanggarplgcctv').val(data.tersangka_plg_cctv);
                $('#appv-penjelasanplgcctv').val(data.penjelasan_plg_cctv);

                $('#appv-sanksiplgcctv').find('option[value="'+data.fup_plg_cctv+'"]').remove();

                $('#appv-sanksiplgcctv').append($('<option></option>').html(data.name_fup_plg).attr('value', data.fup_plg_cctv).prop('selected', true));

                $('#approve-labelplgcctv').html("Approve Pelanggaran Nomor : "+data.no_plg_cctv);

                $('#ModalApprovePlgCCTV').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.reject_dataplgcctv', function(){  
        var id_plgcctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{RESETPLGCCTV:id_plgcctv},  
            dataType:"json",  
            success:function(data){
                $('#reject-idplgcctv').val(data.id_plg_cctv);
                $('#reject-noplgcctv').val(data.no_plg_cctv);

                $('#reject-labelplgcctv').html("Reject Pelanggaran Nomor : "+data.no_plg_cctv);

                $('#ModalRejectPlgCCTV').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>