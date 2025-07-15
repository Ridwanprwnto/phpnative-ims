<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["prosespengajuantablok"])){
    if(ProsesPengajuanTablok($_POST)){
        $alert = array("Success!", "Data item yang diajukan penablokan selesai di follow up.", "success", "$redirect");
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
                    <h4 class="card-title">Daftar Pengajuan Tablok Barang</h4>
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
                            <table class="table table-striped table-bordered" id="list_approve_tablok">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO PENGAJUAN</th>
                                        <th>TANGGAL</th>
                                        <th>USER MENGAJUKAN</th>
                                        <th>KETERANGAN</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sql = "SELECT st_dpd_head.*, users.username, st_dpd_detail.* FROM st_dpd_head
                                    INNER JOIN st_dpd_detail ON st_dpd_head.id_st_dpd = st_dpd_detail.id_st_dpd_head
                                    INNER JOIN users ON st_dpd_head.req_st_dpd = users.nik
                                    WHERE LEFT(st_dpd_head.offdep_st_dpd, 4) = '$idoffice' AND RIGHT(st_dpd_head.offdep_st_dpd, 4) = '$iddept' AND st_dpd_head.pic_st_dpd IS NULL GROUP BY st_dpd_head.id_st_dpd ASC";
                                    $query = mysqli_query($conn, $sql);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                     ?>
                                    <tr>
                                        <td class="details-approvetablok" id="<?= $data['id_st_dpd']; ?>" onclick="changeIcon(this)">
                                            <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                        </td>
                                        <td><?= $data["id_st_dpd"];?></td>
                                        <td><?= $data["date_st_dpd"];?></td>
                                        <td><?= $data["req_st_dpd"]." - ".strtoupper($data["username"]);?></td>
                                        <td><?= $data["ket_st_dpd"];?></td>
                                        <td>
                                            <!-- Icon Button dropdowns -->
                                            <div class="btn-group mb-1">
                                                <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item approve_tablok" href="#" title="FU Tablok Nomor <?= $data['id_st_dpd']; ?>" name="approve_tablok" id="<?= $data["id_st_dpd"]; ?>" data-toggle="tooltip" data-placement="bottom">Complete</a>
                                                </div>
                                            </div>
                                            <!-- /btn-group -->
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- Modal Read -->
                            <div class="modal fade text-left" id="readModalDetailPertemanan" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info white">
                                            <h4 class="modal-title white">Detail Data Pertemanan</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="modal_readdatapertemanan">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Edit Edit -->
                            <div class="modal fade text-left" id="prosestablokbarang">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header bg-primary white">
                                                <h4 class="modal-title white">Proses Pengajuan Penablokan</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input class="form-control" type="hidden" name="user-tablokbarang" value="<?= $usernik; ?>" readonly>
                                                <input class="form-control" type="hidden" id="id-tablokbarang" name="id-tablokbarang" readonly>
                                                <label id="labeltablokbarang"></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosespengajuantablok" class="btn btn-outline-primary">Proses</button>
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
    </div>
        <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
    $(document).ready(function () {

        var table = $('#list_approve_tablok').DataTable({
            destroy: true,
            retrieve: true,
            responsive: false,
            autoWidth: true,
            rowReorder: false,
            scrollX: false
        });

        $('#list_approve_tablok').on('click', 'td.details-approvetablok', function () {
            var docno_tablok = $(this).attr('id');
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                createChild(row, docno_tablok);
                tr.addClass('shown');
            }
        });

        function createChild (row, docno_tablok) {
            var table = $('<table class="display" width="100%"/>');

            row.child( table ).show();

            $.ajax({
                url:'action/datarequest.php',
                method:"POST",
                data:{ACTIONDETAILAPPROVETABLOK:docno_tablok},
                dataType: "json",
            }).done(function(data){
                table.DataTable( {
                    destroy: true,
                    retrieve: true,
                    responsive: false,
                    paging: false,
                    autoWidth: true,
                    rowReorder: false,
                    scrollX: false,
                    data: data.data,
                    columns: [
                        { title: 'DOCNO', data: 'DOCNO',
                            render : function(data, type, row) {
                            return '<a title="Show Pertemanan" href="#" id="'+data+'" name="detail_plu_pertemanan" class="text-bold-600 detail_plu_pertemanan" data-toggle="tooltip" data-placement="bottom">'+data+'</a>';
                        } 
                        },
                        { title: 'PLU', data: 'PLU' },
                        { title: 'DESC', data: 'DESK' },
                        { title: 'TYPE ITEM', data: 'TYPE_ITEM' },
                        { title: 'TYPE RAK', data: 'TYPE_RAK' },
                        { title: 'LINE', data: 'LINE_RAK' },
                        { title: 'ZONA', data: 'ZONA' },
                        { title: 'STATION', data: 'STATION' },
                        { title: 'RAK', data: 'RAK' },
                        { title: 'SHELF', data: 'SHELF' },
                        { title: 'CELL', data: 'CELL' },
                        { title: 'QTY CTN IN PALLET', data: 'KEL_CTN' },
                        { title: 'IP DPD', data: 'IP_DPD' },
                        { title: 'ID DPD', data: 'ID_DPD' },
                    ],
                    order: [[0, 'asc']]
                } );
            })
        }
    });

    $(document).ready(function(){
        $(document).on('click', '.detail_plu_pertemanan', function(){  
            var docnoFr = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{DETAILPERTEMANAN:docnoFr},
                success:function(data){
                    $('#modal_readdatapertemanan').html(data);
                    $('#readModalDetailPertemanan').modal('show');
                }  
            });
        });
    });
        
    $(document).ready(function(){
        $(document).on('click', '.approve_tablok', function(){  
            var idTablok = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{FOLLUPTABLOK:idTablok},  
                dataType:"json",  
                success:function(data){
                    $('#id-tablokbarang').val(data.DOCNO);
                    
                    $('#labeltablokbarang').html("Pengajuan Nomor : "+data.DOCNO);
                    $('#prosestablokbarang').modal('show');
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