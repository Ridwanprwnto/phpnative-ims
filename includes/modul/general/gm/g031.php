<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];
$username = $_SESSION["user_name"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["updatebarangmutasi"])){
    if(UpdateBarangMutasi($_POST) > 0 ){
        $datapost = isset($_POST["sn-mutasi"]) ? $_POST["sn-mutasi"] : NULL;
        $alert = array("Success!", "DAT Barang SN ".$datapost." Berhasil Proses Mutasi", "success", "$encpid");
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
                    <h4 class="card-title">Proses Mutasi Barang DAT</h4>
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
                            <table
                                class="table table-striped table-bordered zero-configuration row-grouping-mutasi">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DOCNO</th>
                                        <th>NAMA BARANG</th>
                                        <th>SRIAL NUMBER</th>
                                        <th>NO AKTIVA</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                $nol = 0;
                                $no = 1;
                                $sql = "SELECT A.*, B.*, C.id_office AS id_office_from, C.office_name AS name_office_from, D.department_name AS name_dept_from, E.*, F.*, G.*, H.id_office AS id_office_to, H.office_name AS office_name_to, I.id_department AS id_dept_to, I.department_name AS dept_name_to FROM mutasi AS A
                                INNER JOIN detail_mutasi AS B ON A.no_mutasi = B.head_no_mutasi
                                INNER JOIN office AS C ON LEFT(A.asal_mutasi, 4) = C.id_office
                                INNER JOIN department AS D ON RIGHT(A.asal_mutasi, 4) = D.id_department
                                LEFT JOIN users AS E ON A.user_mutasi = E.nik
                                INNER JOIN mastercategory AS F ON LEFT(B.pluid_mutasi, 6) = F.IDBarang
                                INNER JOIN masterjenis AS G ON RIGHT(B.pluid_mutasi, 4) = G.IDJenis
                                INNER JOIN office AS H ON LEFT(A.tujuan_mutasi, 4) = H.id_office
                                INNER JOIN department AS I ON RIGHT(A.tujuan_mutasi, 4) = I.id_department
                                WHERE LEFT(A.tujuan_mutasi, 4) = '$idoffice' AND RIGHT(A.tujuan_mutasi, 4) = '$iddept' AND B.status_mutasi = 'N'";
                                $query = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <h6 class="mb-0"><strong>FROM : </strong><?= $data['id_office_from']." - ".strtoupper($data['name_office_from'])." ".strtoupper($data['name_dept_from']);?> |
                                                <span class="text-bold-600">DOCNO : <?= substr($data["no_mutasi"], 1, 5);?></span> on
                                                <em><?= $data['tgl_mutasi']; ?></em>
                                            </h6>
                                        </td>
                                        <td><?= $data["pluid_mutasi"].' - '.$data["NamaBarang"].' '.$data["NamaJenis"].' '.$data["merk_mutasi"].' '.$data["tipe_mutasi"];?>
                                        </td>
                                        <td><?= $data["sn_mutasi"];?></td>
                                        <td><?= $data["at_mutasi"];?></td>
                                        <td>
                                            <button title="Update Mutasi Barang SN : <?= $data["sn_mutasi"];?>" type="button" class="btn btn-icon btn-success update_mutasidat" id="<?= $data["detail_no_mutasi"];?>" name="update_mutasidat" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <!-- Modal Update -->
                                <div class="modal fade text-left" id="updateMutasiDATBarang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success white">
                                                    <h4 class="modal-title white" id="upd-labelmutasi"></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <input class="form-control" type="hidden" name="page-mutasi" value="<?= $encpid;?>" readonly>
                                                        <input class="form-control" type="hidden" name="user-mutasi" value="<?= $usernik;?>" readonly>
                                                        <input class="form-control" type="hidden" name="office-mutasi" value="<?= $idoffice;?>" readonly>
                                                        <input class="form-control" type="hidden" name="dept-mutasi" value="<?= $iddept;?>" readonly>
                                                        <input class="form-control" type="hidden" name="kondisi-mutasi" value="<?= $arrcond[7];?>" readonly>
                                                        <input class="form-control" type="hidden" name="kondisi-cadangan" value="<?= $arrcond[1];?>" readonly>
                                                        <input class="form-control" type="hidden" id="upd-idmutasi" name="dno-mutasi" readonly>
                                                        <input class="form-control" type="hidden" id="upd-nomutasi" name="no-mutasi" readonly>
                                                        <input class="form-control" type="hidden" id="upd-plumutasi" name="pluid-mutasi" readonly>
                                                        <input class="form-control" type="hidden" id="upd-snmutasi" name="sn-mutasi" readonly>
                                                        <input class="form-control" type="hidden" id="upd-offdep" name="offdepfrom-mutasi" readonly>
                                                        <div class="col-md-12 mb-2">
                                                            <label>No Aktiva Awal : </label>
                                                            <input class="form-control" type="text" id="upd-atmutasi" name="upd-atmutasi" readonly>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label>No Aktiva Baru : </label>
                                                            <input class="form-control" type="text" name="aktiva-mutasi" placeholder="Input nomor aktiva baru" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="updatebarangmutasi" class="btn btn-outline-success">Update</button>
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
    </div>
    <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function() {
    
    $('.row-grouping-mutasi').DataTable({
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: false,
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
                        '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    } );

    $('.row-grouping-mutasi tbody').on( 'click', 'tr.group', function () {
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
    $(document).on('click', '.update_mutasidat', function(){  
        var id_mts = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMUTASI:id_mts},  
            dataType:"json",  
            success:function(data){
                $('#upd-idmutasi').val(data.detail_no_mutasi);
                $('#upd-nomutasi').val(data.head_no_mutasi);
                $('#upd-plumutasi').val(data.pluid_mutasi);
                $('#upd-snmutasi').val(data.sn_mutasi);
                $('#upd-atmutasi').val(data.at_mutasi);
                $('#upd-offdep').val(data.asal_mutasi);
                
                $('#upd-labelmutasi').html("Proses Mutasi Barang SN : "+data.sn_mutasi);
                $('#updateMutasiDATBarang').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>