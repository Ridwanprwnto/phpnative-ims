<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["prosesdatamutasi"])) {
    if(ProsesBarangMutasi($_POST) > 0 ){
        $alert = array("Success!", "Mutasi DAT Barang Berhasil Diproses", "success", "$encpid");
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
                    <h4 class="card-title">Mutasi Barang Data Aktiva Tetap</h4>
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
                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                            <li class="nav-item">
                                <a class="nav-link active" id="entry-mutasi" data-toggle="tab" href="#entrymutasi" aria-expanded="true">Entry Mutasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="data-mutasi" data-toggle="tab" href="#datamutasi" aria-expanded="false">Data Mutasi</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="entrymutasi" aria-expanded="true" aria-labelledby="entry-mutasi">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                                <form action="" method="post">
                                    <div class="table-responsive">
                                        <table class="table text-center" id="table_mutasibarang">
                                            <thead>
                                                <tr>
                                                    <th>PLU - NAMA BARANG</th>
                                                    <th>NOMOR AKTIVA</th>
                                                    <th>SERIAL NUMBER</th>
                                                    <th>MERK BARANG</th>
                                                    <th>TIPE BARANG</th>
                                                    <th><button type="button" name="add_mutasibarang" class="btn btn-success btn-xs add_mutasibarang"><i class="ft-plus"></i></button></th>
                                                </tr>
                                            </thead>
                                            <tbody id="repeater-mutasibarang">
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Modal Proses -->
                                    <div class="modal fade text-left" id="proses-mutasi">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success white">
                                                    <h4 class="modal-title white">Tujuan Mutasi</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-2">
                                                            <input type="hidden" name="page-mutasi" value="<?= $encpid;?>" class="form-control" readonly>
                                                            <input type="hidden" name="modifref-mutasi" value="<?= $arrmodifref[8]; ?>" class="form-control" readonly>
                                                            <input type="hidden" name="user-mutasi" value="<?= $usernik;?>" class="form-control" readonly>
                                                            <input type="hidden" name="ofdep-from-mutasi" value="<?= $idoffice.$iddept;?>" class="form-control" readonly>
                                                            <input type="hidden" name="kondisi-mutasi" value="<?= $arrcond[7];?>" class="form-control" readonly>
                                                            <label>Office : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="office-to-mutasi" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php
                                                                    $query_off = mysqli_query($conn, "SELECT * FROM office");
                                                                    while($data_off = mysqli_fetch_assoc($query_off)) { ?>
                                                                        <option value="<?= $data_off['id_office'];?>">
                                                                            <?= $data_off['id_office'].' - '.strtoupper($data_off['office_name']);?>
                                                                        </option>
                                                                    <?php 
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Department : </label>
                                                            <select class="select2 form-control block" style="width: 100%" type="text" name="dept-to-mutasi" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_dept = mysqli_query($conn, "SELECT * FROM department");
                                                                    while($data_dept = mysqli_fetch_assoc($query_dept)) { ?>
                                                                        <option value="<?= $datadept = $data_dept['id_department'];?>">
                                                                            <?= $data_dept['id_department'].' - '.strtoupper($data_dept['department_name']);?>
                                                                        </option>
                                                                    <?php
                                                                    } 
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>Keterangan :</label>
                                                            <textarea class="form-control" name="ket-mutasi" type="text" placeholder="Input keterangan (Optional)"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="prosesdatamutasi" class="btn btn-outline-success">Proses</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <button type="button" class="btn btn-success btn-min-width pull-right mb-2" data-toggle="modal" data-target="#proses-mutasi">Proses Mutasi</button>
                                </form>
                            </div>
                            <div class="tab-pane" id="datamutasi" aria-labelledby="data-mutasi">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered zero-configuration" id="monitor_mutasi">
                                    <thead>
                                        <tr>
                                            <th>DETAIL</th>
                                            <th>DOCNO</th>
                                            <th>TANGGAL</th>
                                            <th>TUJUAN</th>
                                            <th>PEMBUAT</th>
                                            <th>KETERANGAN</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $nol = 0;
                                            $no = 1;
                                            $sql = "SELECT A.*, C.id_office AS id_office_from, C.office_name AS name_office_from, D.department_name AS name_dept_from, E.username, H.id_office AS id_office_to, H.office_name AS office_name_to, I.id_department AS id_dept_to, I.department_name AS dept_name_to FROM mutasi AS A
                                            INNER JOIN office AS C ON LEFT(A.asal_mutasi, 4) = C.id_office
                                            INNER JOIN department AS D ON RIGHT(A.asal_mutasi, 4) = D.id_department
                                            LEFT JOIN users AS E ON A.user_mutasi = E.nik
                                            INNER JOIN office AS H ON LEFT(A.tujuan_mutasi, 4) = H.id_office
                                            INNER JOIN department AS I ON RIGHT(A.tujuan_mutasi, 4) = I.id_department
                                            WHERE LEFT(A.asal_mutasi, 4) = '$idoffice' AND RIGHT(A.asal_mutasi, 4) = '$iddept' ORDER BY A.no_mutasi DESC";
                                            $query = mysqli_query($conn, $sql);
                                            while ($data = mysqli_fetch_assoc($query)) {
                                        ?>
                                        <tr>
                                            <td class="details-monitor_mutasi" id="<?= $data["no_mutasi"]; ?>" onclick="changeIcon(this)">
                                                <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                            </td>
                                            <td><span class="text-bold-600"><?= substr($data["no_mutasi"], 1, 5);?></span></td>
                                            <td><em><?= $data['tgl_mutasi']; ?></em></td>
                                            <td><?= $data['id_office_to']." - ".strtoupper($data['office_name_to'])." ".strtoupper($data['dept_name_to']);?></td>
                                            <td><?= $data["user_mutasi"]." - ".strtoupper($data["username"]); ?></td>
                                            <td><?= $data["ket_mutasi"]; ?></td>
                                            <td>
                                                <a title="Reprint Mutasi DAT Nomor : <?= $data["no_mutasi"];?>" href="reporting/report-reprint-mutasi.php?sjm=<?= encrypt($data["no_mutasi"]); ?>" class="btn btn-icon btn-primary reprint_mutasidat" id="<?= $data["detail_no_mutasi"];?>" name="reprint_mutasidat" data-toggle="tooltip" data-placement="bottom" onclick="return postSESSION();" target="_blank"><i class="ft-printer"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <!-- Start Modal -->
                                    <!-- End Modal -->
                                </table>
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

$(document).ready(function(){
    
    var count = 0;

    $(document).on('click', '.add_mutasibarang', function(){
        count++;
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="kode_barang[]" class="select2 form-control block kode_barang" style="width: 100%" data-kode_barang_id="'+count+'" required><option value="" selected disabled>Please Select</option><?= fill_select_dat($idoffice.$iddept.$arrcond[5]); ?></select></td>';
        html += '<td><select type="text" name="no_aktiva[]" class="select2 form-control block no_aktiva" style="width: 100%" data-no_aktiva_id="'+count+'" id="no_aktiva_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><select type="text" name="sn_barang[]" class="select2 form-control block sn_barang" style="width: 100%" data-sn_barang_id="'+count+'" id="sn_barang_id'+count+'" required><option value="" selected disabled>Please Select</option></select></td>';
        html += '<td><input type="text" name="merk_barang[]" class="form-control merk_barang" id="merk_barang_id'+count+'" readonly/></td>';
        html += '<td><input type="text" name="tipe_barang[]" class="form-control tipe_barang" id="tipe_barang_id'+count+'" readonly/></td>';
        html += '<td><button type="button" name="remove_mutasibarang" class="btn btn-danger btn-xs remove_mutasibarang"><i class="ft-minus"></i></button></td>';
        
        $('#repeater-mutasibarang').append(html);

        $(".select2").select2();

    });

    $(document).on('click', '.remove_mutasibarang', function(){
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.kode_barang', function(){
        var kode_barang = $(this).val();
        var kode_barang_id = $(this).data('kode_barang_id');
        if (kode_barang) {
            $.ajax({
            url:"action/datarequest.php",
            method:"POST",
            data:{BARANGMUTASI:kode_barang},
                success:function(data){
                    var html = '';
                    html += data;
                    $('#no_aktiva_id'+kode_barang_id).html(html);

                    var html2 = '<option value="" selected disabled>Please Select</option>';
                    $('#sn_barang_id'+kode_barang_id).html(html2);

                    $('#merk_barang_id'+kode_barang_id).val('');
                    $('#tipe_barang_id'+kode_barang_id).val('');
                }
            })
        } else {
            $('#no_aktiva_id'+kode_barang_id).val('');
        }
    });

    $(document).on('change', '.no_aktiva', function(){
        var at_barang = $(this).val();
        var at_barang_id = $(this).data('no_aktiva_id');
        if (at_barang) {
            $.ajax({
            url:"action/datarequest.php",
            method:"POST",
            data:{ATMUTASI:at_barang},
                success:function(data){
                    var html = '';
                    html += data;
                    $('#sn_barang_id'+at_barang_id).html(html);

                    $('#merk_barang_id'+at_barang_id).val('');
                    $('#tipe_barang_id'+at_barang_id).val('');
                }
            })
        } else {
            $('#sn_barang_id'+kode_barang_id).val('');
        }
    });

    $(document).on('change', '.sn_barang', function(){
        var sn_barang = $(this).val();
        var sn_barang_id = $(this).data('sn_barang_id');
        if (sn_barang) {
            $.ajax({
                type: 'POST',
                url: 'action/datarequest.php',
                data: { SNMUTASI: sn_barang },
                dataType: "JSON",
                success: function (data) {
                    if (data.length > 0) {
                        $('#merk_barang_id'+sn_barang_id).val((data[0].ba_merk));
                        $('#tipe_barang_id'+sn_barang_id).val((data[0].ba_tipe));
                    } else {
                        $('#merk_barang_id'+sn_barang_id).val('');
                        $('#tipe_barang_id'+sn_barang_id).val('');
                    }
                }
            });
        }
    });
});    

$(document).ready(function () {

var table = $('#monitor_mutasi').DataTable({
    destroy: true,
    retrieve: true
});

// Add event listener for opening and closing details
$('#monitor_mutasi').on('click', 'td.details-monitor_mutasi', function () {
    var no_id = $(this).attr('id');
    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        createChild(row, no_id);
        // format(row.child, noref_pp);
        tr.addClass('shown');
    }
});

function createChild (row, no_id) {
    // This is the table we'll convert into a DataTable
    var table = $('<table class="display" width="100%"/>');

    // Display it the child row
    row.child( table ).show();

    $.ajax({
        url:'action/datarequest.php',
        method:"POST",  
        data:{ACTIONDETAILMONITORMUTASI:no_id},
        dataType: "json",
    }).done(function(data){
        table.DataTable( {
            data: data.data,
            columns: [
                { title: 'PLUID - DESC', data: 'PLUID_MUTASI' },
                { title: 'MERK', data: 'MERK_MUTASI' },
                { title: 'TIPE', data: 'TIPE_MUTASI', render: function(data) { 
                    return data.toUpperCase();
                } },
                { title: 'SN', data: 'SN_MUTASI' },
                { title: 'DAT', data: 'DAT_MUTASI' },
                { 
                    title: 'STATUS',
                    data: null,
                    render: function(data, type, row) {
                        if (row.STATUS_MUTASI == "Y") {
                            return '<div class="badge badge-info">COMPLETED</div>'
                        }
                        else if (row.STATUS_MUTASI == "N") {
                            return '<div class="badge badge-warning">PROCESS</div>'
                        }
                    }
                }
            ],
            order: [[1, 'asc']]
        } );
    })
}
});

function postSESSION() {
    var dataToSend = {
            PRINTMUTASI: 'PRINTSJM'
    };
    $.ajax({
        url: 'action/datarequest.php',
        method: 'POST',
        data: dataToSend,
        dataType:"json",  
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

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