<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

if(isset($_POST["terimabarangmasuk"])){
    if(ReceiveBarangMasuk($_POST) > 0 ){
        $alert_insert = "<strong>Success!</strong> Barang berhasil proses penerimaan";
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["batalbarangmasuk"])){
    if(CancelBarangMasuk($_POST) > 0 ){
        $alert_insert = "<strong>Success!</strong> Barang berhasil dibatalkan penerimaan";
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
                    <h4 class="card-title">Monitoring Data Surat Jalan Penerimaan Barang</h4>
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
                        <?php
                        if (isset($alert_insert)) {
                            ?>
                                <div class="alert alert-success alert-dismissible ml-1 pull-right" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?= $alert_insert; ?>
                                </div>
                            <?php
                        }
                        ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration row-grouping-suratjalan" id="detail_barangsj">
                                <thead>
                                    <tr>
                                        <th>DETAIL</th>
                                        <th>NO</th>
                                        <th>NOMOR SJ</th>
                                        <th>TANGGAL PROSES</th>
                                        <th>JUMLAH ITEM</th>
                                        <th>PENGIRIM</th>
                                        <th>KETERANGAN</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                $no = 1;
                                $sql = "SELECT A.*, B.*, COUNT(B.head_no_sj) AS tot_item, C.id_office, C.office_name, D.*, E.nik, E.username, F.NamaBarang, G.NamaJenis, H.id_office AS id_office_to, H.office_name AS office_name_to, I.id_department AS id_dept_to, I.department_name AS dept_name_to FROM surat_jalan AS A
                                INNER JOIN detail_surat_jalan AS B ON A.no_sj = B.head_no_sj
                                INNER JOIN office AS C ON LEFT(A.asal_sj, 4) = C.id_office
                                INNER JOIN department AS D ON RIGHT(A.asal_sj, 4) = D.id_department
                                LEFT JOIN users AS E ON A.user_sj = E.nik
                                INNER JOIN mastercategory AS F ON LEFT(B.pluid_sj, 6) = F.IDBarang
                                INNER JOIN masterjenis AS G ON RIGHT(B.pluid_sj, 4) = G.IDJenis
                                INNER JOIN office AS H ON LEFT(A.tujuan_sj, 4) = H.id_office
                                INNER JOIN department AS I ON RIGHT(A.tujuan_sj, 4) = I.id_department
                                WHERE LEFT(A.no_sj, 1) = 'M' AND LEFT(A.tujuan_sj, 4) = '$idoffice' AND RIGHT(A.tujuan_sj, 4) = '$iddept' AND B.status_sj = 'N' GROUP BY A.no_sj DESC";
                                $query = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <tr>
                                        <td class="details-datasj" id="<?= $data['no_sj']; ?>" onclick="changeIcon(this)">
                                            <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                        </td>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><strong><?= substr($data["no_sj"], 1, 5);?></strong></td>
                                        <td><?= $data["tanggal_sj"];?></td>
                                        <td><?= $data["tot_item"]; ?></td>
                                        <td>PENGIRIM : <?= $data["id_office"]." - ".strtoupper($data["office_name"])." DEPT. ".strtoupper($data["department_name"]); ?></td>
                                        <td>
                                            <h6 class="mb-0">
                                                <span class="text-bold-600"><?= $data["ket_sj"] == "" ? "-" : $data["ket_sj"]?></span> 
                                                <em></em>
                                            </h6>
                                        </td>
                                        <td>
                                            <button type="button" title="Terima Barang Nomor : <?= substr($data['no_sj'], 1, 5); ?>" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#update<?= $data["no_sj"];?>"><i class="ft-check-square"></i></button>
                                            <button type="button" title="Batal Terima Barang Nomor : <?= substr($data['no_sj'], 1, 5); ?>" class="btn btn-icon btn-danger" data-toggle="modal" data-target="#delete<?= $data["detail_no_sj"];?>"><i class="ft-delete"></i></button>
                                        </td>
                                        <!-- Modal Update -->
                                        <div class="modal fade text-left" id="update<?= $data['no_sj']; ?>"
                                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <form action="" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary white">
                                                            <h4 class="modal-title white" id="myModalLabel1">Receive Confirmation Nomor SJ : <?= substr($data['no_sj'], 1, 5); ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <input class="form-control" type="hidden" name="modifref-barang" value="<?= $arrmodifref[8]; ?>" readonly>
                                                                <input class="form-control" type="hidden" name="offdep-barang" value="<?= $data["tujuan_sj"]; ?>" readonly>
                                                                <input class="form-control" type="hidden" name="terima-barang" value="<?= $data["no_sj"]; ?>">
                                                                <input class="form-control" type="hidden" name="pluid-barang" value="<?= $data["pluid_sj"];?>">
                                                                <input class="form-control" type="hidden" name="sn-barang" value="<?= $data["sn_sj"];?>">
                                                                <input class="form-control" type="hidden" name="dat-barang" value="<?= $data["at_sj"];?>">
                                                                <div class="col-md-12 mb-2">
                                                                    <label>Penerima Barang : </label>
                                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="user-barang" required>
                                                                        <option value="" selected disabled>Please Select</option>
                                                                        <option value="<?=$usernik;?>" ><?= $usernik.' - '.strtoupper($username);?></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12 mb-2">
                                                                    <label>Penempatan Barang :</label>
                                                                    <textarea class="form-control" type="text" name="posisi-barang" placeholder="Input penempatan barang saat ini" required></textarea>
                                                                </div>
                                                                <div class="col-md-12 mb-2">
                                                                    <label>Keterangan :</label>
                                                                    <textarea class="form-control" type="text" name="ket-barang" placeholder="Input keterangan (Optional)"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="terimabarangmasuk" class="btn btn-outline-primary">Proses</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                        <!-- Modal Delete -->
                                            <div class="modal fade text-left" id="delete<?= $data['detail_no_sj']; ?>"
                                                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="" method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger white">
                                                                <h4 class="modal-title white" id="myModalLabel1">Cancel Confirmation</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control" type="hidden" name="del-idsj" value="<?= $data["no_sj"];?>" readonly>
                                                                <input class="form-control" type="hidden" name="del-office" value="<?= $data["id_office"];?>" readonly>
                                                                <input class="form-control" type="hidden" name="del-dept" value="<?= $data["id_department"];?>" readonly>
                                                                <label>Apakah anda yakin ingin membatalkan penerimaan barang nomor : <?= substr($data["no_sj"], 1, 5);?></label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="batalbarangmasuk" class="btn btn-outline-danger">Yes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <!-- End Modal -->
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
    
    $('.row-grouping-suratjalan').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,
        columnDefs: [
            { "visible": false, "targets": 5 },
        ],
        displayLength: 10,
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(5, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">'+group+'</td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    // $('.row-grouping-suratjalan tbody').on( 'click', 'tr.group', function () {
    //     if (typeof table !== 'undefined' && table.order()[0]) {
    //         var currentOrder = table.order()[0];
    //         if ( currentOrder[0] === 5 && currentOrder[1] === 'asc' ) {
    //             table.order( [ 5, 'desc' ] ).draw();
    //         }
    //         else {
    //             table.order( [ 5, 'asc' ] ).draw();
    //         }
    //     }
    // });

});


$(document).ready(function () {

var table = $('#detail_barangsj').DataTable({
    destroy: true,
    retrieve: true
});

// Add event listener for opening and closing details
$('#detail_barangsj').on('click', 'td.details-datasj', function () {
    var noref_sj = $(this).attr('id');
    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        createChild(row, noref_sj);
        // format(row.child, noref_sj);
        tr.addClass('shown');
    }
});

function createChild (row, noref_sj) {
    // This is the table we'll convert into a DataTable
    var table = $('<table class="display" width="100%"/>');

    // Display it the child row
    row.child( table ).show();

    $.ajax({
        url:'action/datarequest.php',
        method:"POST",  
        data:{ACTIONDETAILSJ:noref_sj},
        dataType: "json",
    }).done(function(data){
        table.DataTable( {
            data: data.data,
            columns: [
                { title: 'KODE - NAMA BARANG', data: 'DESC_BARANG' },
                { title: 'SERIAL NUMBER', data: 'SN_BARANG' },
                { title: 'NOMOR AKTIVA', data: 'DAT_BARANG' },
                { title: 'QTY', data: 'QTY_BARANG' },
                { title: 'KETERANGAN', data: 'KET_BARANG' },
            ],
            order: [[0, 'asc']]
        } );
    })
}
});

function changeIcon(anchor) {
    var icon = anchor.querySelector("i");
    var button = anchor.querySelector('button');

    icon.classList.toggle('la-plus');
    icon.classList.toggle('la-minus');

    button.classList.toggle('success');
    button.classList.toggle('danger');
}
</script>