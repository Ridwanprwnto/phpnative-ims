<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];
$iddiv = $_SESSION['divisi'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$_SESSION['WATCHPLG'] = $dec_page;

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["updatedata"])){
    if(FUPPelanggaranCCTV($_POST) > 0 ){
        $datapost = isset($_POST["no-plg"]) ? $_POST["no-plg"] : NULL;
        $alert = array("Success!", "Data Pelanggaran CCTV Nomor ".$datapost." berhasil di FUP", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["rejectdata"])){
    if(RejectPelanggaranCCTV($_POST) > 0 ){
        $datapost = isset($_POST["no-plg"]) ? $_POST["no-plg"] : NULL;
        $alert = array("Success!", "Data Pelanggaran CCTV Nomor ".$datapost." berhasil di reset", "success", "$encpid");
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
                    <h4 class="card-title">All Data Follow Up Pelanggaran CCTV</h4>
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
                                <h4 class="form-section"><i class="la la-info-circle"></i>Follow Up Pelanggaran</h4>
                                <div class="row">
                                    <div class="form-group col-md-12 mb-0">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered row-grouping-fup">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pelanggaran</th>
                                    <th>Tgl Waktu Kejadian</th>
                                    <th>Bagian</th>
                                    <th>Kategori Pelanggaran</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Lokasi CCTV</th>
                                    <th>Follow Up</th>
                                    <th>Status FUP</th>
                                    <th>Rekaman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php

                            $no = 1;
                            $result = "SELECT A.*, B.id_office, C.id_department, D.id_divisi, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.kode_head_bag_cctv, G.no_lay_cctv, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, K.name_fup_plg, user_pelanggaran_cctv.*  FROM pelanggaran_cctv AS A
                            INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
                            INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
                            INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
                            LEFT JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
                            LEFT JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
                            LEFT JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
                            LEFT JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
                            LEFT JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
                            LEFT JOIN users AS J ON A.user_plg_cctv = J.nik
                            LEFT JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
                            LEFT JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv
                            WHERE A.office_plg_cctv = '$idoffice' AND A.dept_plg_cctv = '$iddept' AND A.status_plg_cctv != 'Y' GROUP BY A.no_plg_cctv ORDER BY A.no_plg_cctv DESC";
                            $query = mysqli_query($conn, $result);
                            while($data = mysqli_fetch_assoc($query)) {
                                $div_plg = $data["div_plg_cctv"];
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
                                    <td><?= $data['jns_plg_cctv']; ?></td>
                                    <td><?= $data['lokasi_plg_cctv']; ?></td>
                                    <td><?= $data['name_fup_plg']; ?></td>
                                    <td>
                                        <?php
                                            if ($data['status_plg_cctv'] == "S") {
                                                $sts = "WAITING";
                                                $clr = "danger";
                                                $txt = "BELUM FUP";
                                            }
                                            elseif ($data['status_plg_cctv'] == "N") {
                                                $sts = "PENDING";
                                                $clr = "success";
                                                $txt = "MENUNGGU APPROVAL";
                                            }
                                        ?>
                                        <div class="badge badge-<?= $clr; ?>" title="<?= $txt; ?>" data-toggle="tooltip" data-placement="bottom"><?= $sts; ?></div>
                                    </td>
                                    <td>
                                        <a title="<?= $data['rekaman_plg_cctv'] != NULL ? 'Lihat Rekaman Pelanggaran Nomor : '.$data['no_plg_cctv'] : ''; ?>" data-toggle="tooltip" data-placement="bottom" onclick="window.open('', 'popupwindow', 'scrollbars=yes,resizable=yes,width=auto,height=auto');return true" target="popupwindow" href="<?= $data['rekaman_plg_cctv'] != NULL ? "files/record/index.php?id=".encrypt($data['rekaman_plg_cctv']) : '#'; ?>" class="<?= $data['rekaman_plg_cctv'] != NULL ? 'btn btn-icon btn-warning' : ''; ?>"><i class="<?= $data['rekaman_plg_cctv'] != NULL ? 'ft-film' : ''; ?>"></i></a>
                                    </td>
                                    <td>
                                        <button type="button" name="fup_data" id="<?= $data["id_plg_cctv"]; ?>" title="FUP Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-primary fup_data"><i class="ft-check-square"></i></button>
                                        <?php if ($data['status_plg_cctv'] == "N") { ?>
                                            <button type="button" title="Reset FUP Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-danger reset_dataplgcctv" name="reset_dataplgcctv" id="<?= $data["id_plg_cctv"]; ?>"><i class="ft-refresh-cw"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="dataModalUpdatePlg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel1">Follow Up Pelanggaran</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" id="idupd-plg" name="id-plg" readonly>
                                                <div class="col-md-6 mb-2">
                                                    <label>Nomor Pelanggaran : </label>
                                                    <input type="text" class="form-control" id="noupd-plg" name="no-plg" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Lokasi Kejadian : </label>
                                                    <input type="text" class="form-control" id="lokasi-plg" name="lokasi-plg" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Tgl Kejadian : </label>
                                                    <input type="text" class="form-control" id="tgl-plg" name="tgl-plg" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Waktu Kejadian : </label>
                                                    <input type="text" class="form-control" id="waktu-plg" name="waktu-plg" disabled>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Kategori Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="kategori-plg" name="kategori-plg" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Jenis Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="jns-plg" name="jns-plg" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Kejadian Pelanggaran :</label>
                                                    <textarea class="form-control" type="text" id="kejadian-plg" name="kejadian-plg" disabled></textarea>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label>Keterangan :</label>
                                                    <textarea class="form-control" type="text" id="keterangan-plg" name="keterangan-plg" disabled></textarea>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Atasan Follow Up  : </label>
                                                    <select name="fup-atasan" class="select2 form-control block" style="width: 100%" type="text" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_fup = mysqli_query($conn, "SELECT nik, username FROM users WHERE nik = '$nik'");
                                                            while($data_fup = mysqli_fetch_assoc($query_fup)) { ?>
                                                            <option value="<?= $data_fup['nik']; ?>"><?= $data_fup['nik'].' - '.strtoupper($data_fup['username']); ?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>User Pelanggar : </label>
                                                    <select name="fupuser[]" class="select2 form-control block" style="width: 100%" data-placeholder="Please Select" type="text" multiple="multiple" required>
                                                        <optgroup label="TIDAK TERIDENTIFIKASI">
                                                        <?php
                                                            $ident = array('IDENTITAS TIDAK DIKETAHUI', 'BAGIAN LAIN');
                                                            foreach ($ident as $i) {
                                                        ?>
                                                            <option value="<?= $i; ?>"><?= $i; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                        </optgroup>
                                                        <optgroup label="TERIDENTIFIKASI">
                                                        <?php 
                                                            $query_user = mysqli_query($conn, "SELECT nik, username FROM users WHERE id_office = '$idoffice' AND id_group NOT LIKE 'GP01' ORDER BY username ASC");
                                                            while($data_user = mysqli_fetch_assoc($query_user)) { ?>
                                                            <option value="<?= $data_user['nik']." - ".strtoupper($data_user['username']);?>"><?= $data_user['nik']." - ".strtoupper($data_user['username']);?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Follow Up : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="fup-sanksi" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php 
                                                            $query_sanksi = mysqli_query($conn, "SELECT * FROM fup_pelanggaran WHERE id_fup_plg NOT LIKE '2'");
                                                            while($data_sanksi = mysqli_fetch_assoc($query_sanksi)) { ?>
                                                            <option value="<?= $data_sanksi['id_fup_plg']; ?>"><?= $data_sanksi['name_fup_plg']; ?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Penjelasan :</label>
                                                    <textarea class="form-control" type="text" name="fup-penjelasan" placeholder="Penjelasan atas user ybs melakukan pelanggaran (Optional)"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatedata" class="btn btn-outline-primary">Follw Up</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="dataModalDeletePlg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="del-labelplgcctv"></h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="del-idplgcctv" name="id-plg" readonly>
                                            <input type="hidden" class="form-control" id="del-noplgcctv" name="no-plg" readonly>
                                            <label>Pelanggaran yang sudah di follow up ini datanya akan di reset.</label>
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
$(document).ready(function() {
    
    $('.row-grouping-fup').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,
        "columnDefs": [
            { "visible": false, "targets": 4 },
        ],
        // "order": [[ 2, 'desc' ]],
        "displayLength": 10,
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

    $('.row-grouping-fup tbody').on( 'click', 'tr.group', function () {
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

$(document).ready(function(){
    $(document).on('click', '.fup_data', function(){  
        var fup_id = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATEMODALPLG:fup_id},  
            dataType:"json",  
            success:function(data){
                $('#idupd-plg').val(data.id_plg_cctv);
                $('#noupd-plg').val(data.no_plg_cctv);
                $('#lokasi-plg').val(data.lokasi_plg_cctv);
                $('#tgl-plg').val(data.data_tgl_plg);
                $('#waktu-plg').val(data.data_wkt_plg);
                $('#kategori-plg').val(data.ctg_plg_cctv);
                $('#jns-plg').val(data.jns_plg_cctv);
                $('#kejadian-plg').val(data.kejadian_plg_cctv);
                $('#keterangan-plg').val(data.ket_plg_cctv);
                $('#dataModalUpdatePlg').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.reset_dataplgcctv', function(){  
        var id_plgcctv = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{RESETPLGCCTV:id_plgcctv},  
            dataType:"json",  
            success:function(data){
                $('#del-idplgcctv').val(data.id_plg_cctv);
                $('#del-noplgcctv').val(data.no_plg_cctv);

                $('#del-labelplgcctv').html("Reset FUP Nomor Pelanggaran : "+data.no_plg_cctv);

                $('#dataModalDeletePlg').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>