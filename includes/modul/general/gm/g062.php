<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$usernik = $_SESSION["user_nik"];

if (isset($_SESSION['ALERT'])) {
    $alert = $_SESSION["ALERT"];
    unset($_SESSION['ALERT']);
}

$_SESSION['PRINTP3AT'] = $_POST;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["uploadp3at"])){
    if(UploadBAP3AT($_POST) > 0 ){
        $datapost = isset($_POST["id-p3at"]) ? $_POST["id-p3at"] : NULL;
        $alert = array("Success!", "Document P3AT Nomor ".$datapost." Berhasil Di Upload", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["cancelp3at"])){
    if(CancelP3AT($_POST) > 0 ){
        $datapost = isset($_POST["no-p3at"]) ? $_POST["no-p3at"] : NULL;
        $alert = array("Success!", "P3AT Nomor ".$datapost." Berhasil Di Batalkan", "success", "$redirect");
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
                    <h4 class="card-title">Daftar Data Monitoring P3AT</h4>
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
                        <table class="table table-striped table-bordered zero-configuration row-grouping-pemusnahan" id="table_monp3at">
                            <thead>
                                <tr>
                                    <th>Detail</th>
                                    <th>No</th>
                                    <th>Nomor P3AT</th>
                                    <th>Tanggal</th>
                                    <th>Pembuat</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;

                                $query = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name, D.username, E.* FROM p3at AS A
                                INNER JOIN office AS B ON A.office_p3at = B.id_office
                                INNER JOIN department AS C ON A.dept_p3at = C.id_department
                                INNER JOIN users AS D ON A.user_p3at = D.nik
                                INNER JOIN status_p3at AS E ON A.status_p3at = E.kode_sp3at
                                WHERE A.office_p3at = '$office_id' AND A.dept_p3at = '$dept_id' ORDER BY A.id_p3at DESC");

                                while($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td class="details-datap3at" id="<?= $data['id_p3at']; ?>" onclick="changeIcon(this)">
                                        <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                    </td>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['id_p3at']; ?></td>
                                    <td><?= $data['tgl_p3at']; ?></td>
                                    <td><?= $data['user_p3at']." - ".strtoupper($data['username']); ?></td>
                                    <td>
                                        <h6 class="mb-0">
                                            <span class="text-bold-600"><?= $data['judul_p3at'] == '' ? '-' : $data['judul_p3at']; ?></span>
                                            <em></em>
                                        </h6>
                                    </td>
                                    <td>
                                        <span class="badge badge-default badge-<?= $data['warna_sp3at']; ?> badge-lg"><i class="ft-info"></i> <?= $data['nama_sp3at']; ?></span>
                                    </td>
                                    <td>
                                        <span class="dropdown">
                                            <button id="idaction<?= $data['id_pembelian']; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="idaction<?= $data['id_pembelian']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <?php
                                                if ($data['status_p3at'] == $arrsp3at[1]){ ?>
                                                <a title="Buat PP Atas P3AT Nomor <?= $data['id_p3at']; ?>" data-toggle="tooltip" data-placement="bottom" href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[2]);?>&id=<?= encrypt($data['id_p3at']);?>" class="dropdown-item"><i class="ft-file-plus"></i>Buat PP</a>
                                                <a href="javascript:void(0);" id="<?= $data['id_p3at']; ?>" name="upload_p3at" title="Upload Document P3AT Nomor <?= $data['id_p3at']; ?>" class="dropdown-item upload_p3at" data-toggle="tooltip" data-placement="bottom"><i class="ft-upload"></i>Upload Dokumen</a>
                                                <?php } 
                                                if ($data['status_p3at'] == $arrsp3at[0]){ ?>
                                                <a title="Proses Pemusnahan Nomor <?= $data['id_p3at']; ?>" data-toggle="tooltip" data-placement="bottom" href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[9]);?>&id=<?= encrypt($data['id_p3at']);?>" class="dropdown-item"><i class="icon-fire"></i>Proses Musnah</a>
                                                <a href="javascript:void(0);" id="<?= $data['id_p3at']; ?>" name="cancel_p3at" title="Batalkan P3AT Nomor <?= $data['id_p3at']; ?>" class="dropdown-item cancel_p3at" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i> Cancel</a>
                                                <?php } 
                                                if ($data['approve_p3at'] != NULL){ ?>
                                                <a title="Print Bukti Approve P3AT" data-toggle="tooltip" data-placement="bottom" href="files/p3at/index.php?nomor=<?= encrypt($data['approve_p3at']);?>" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank"><i class="ft-printer"></i>Print BA P3AT</a>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalMonP3AT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post" enctype="multipart/form-data" role="form">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="upd-labelmonp3at"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-2">
                                                    <input type="hidden" class="form-control" id="upd-nomonp3at" name="no-p3at" readonly>
                                                    <input type="hidden" class="form-control" id="upd-idmonp3at" name="id-p3at" readonly>
                                                    <input type="hidden" class="form-control" name="page-p3at" value="<?= $redirect; ?>">
                                                    <input type="hidden" class="form-control" name="status-p3at" value="<?= $arrsp3at[1]; ?>" readonly>
                                                    <label>File Dokumen BA P3AT : </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="file-p3at">
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="uploadp3at" class="btn btn-outline-success">Upload Dokumen</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalMonP3AT" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Cancel Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input type="hidden" id="del-idmonp3at" name="no-p3at" class="form-control" readonly>
                                                <label id="del-labelmonp3at"></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="cancelp3at" class="btn btn-outline-danger">Yes</button>
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
    
$(document).ready(function() {
    
    $('.row-grouping-pemusnahan').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: true,
        "columnDefs": [
            { "visible": false, "targets": 6 },
        ],
        // "order": [[ 2, 'desc' ]],
        "displayLength": 10,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(6, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    } );

    $('.row-grouping-pemusnahan tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 6 && currentOrder[1] === 'asc' ) {
                table.order( [ 6, 'desc' ] ).draw();
            }
            else {
                table.order( [ 6, 'asc' ] ).draw();
            }
        }
    });

});


$(document).ready(function () {

    var table = $('#table_monp3at').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#table_monp3at').on('click', 'td.details-datap3at', function () {
        var no_p3at = $(this).attr('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            createChild(row, no_p3at);
            // format(row.child, noref_pp);
            tr.addClass('shown');
        }
    });

    function createChild (row, no_p3at) {
        // This is the table we'll convert into a DataTable
        var table = $('<table class="display" width="100%"/>');

        // Display it the child row
        row.child( table ).show();

        $.ajax({
            url:'action/datarequest.php',
            method:"POST",  
            data:{ACTIONDETAILP3AT:no_p3at},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'KODE BARANG', data: 'KODE_BARANG' },
                    { title: 'NAMA BARANG', data: 'NAMA_BARANG' },
                    { title: 'SERIAL NUMBER', data: 'SN' },
                    { title: 'NOMOR AKTIVA', data: 'DAT' },
                    { title: 'TH PEROLEHAN', data: 'THN' },
                    { title: 'NOMOR PEMUSNAHAN', data: 'NOMOR_MUSNAH' },
                    { title: 'TGL PEMUSANAHAN', data: 'TGL_MUSNAH' },
                ],
                order: [[1, 'asc']]
            } );
        })
    }

});

$(document).ready(function(){
    $(document).on('click', '.upload_p3at', function(){  
        var nomor_p3at = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONP3AT:nomor_p3at},  
            dataType:"json",  
            success:function(data){
                $('#upd-nomonp3at').val(data.no_p3at);
                $('#upd-idmonp3at').val(data.id_p3at);
                
                $('#upd-labelmonp3at').html("Upload Document P3AT Nomor : "+data.id_p3at);
                $('#updateModalMonP3AT').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.cancel_p3at', function(){  
        var nomor_p3at = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONP3AT:nomor_p3at},  
            dataType:"json",  
            success:function(data){
                $('#del-idmonp3at').val(data.id_p3at);
                
                $('#del-labelmonp3at').html("P3AT Nomor : "+data.id_p3at);
                $('#deleteModalMonP3AT').modal('show');
            }  
        });
    });
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

<?php
    include ("includes/templates/alert.php");
?>