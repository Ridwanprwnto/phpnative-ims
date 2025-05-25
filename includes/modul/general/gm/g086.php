<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];

if (isset($_SESSION['ALERT'])) {
    $alert = $_SESSION["ALERT"];
    unset($_SESSION['ALERT']);
}

$_SESSION['PRINTKKSONA'] = $_POST;

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["insertdata"])){
    if(InsertKKSONA($_POST) > 0 ){
        $datapost = $_POST["no-so"];
        $alert = array("Success!", "Data SO Nomor ".$datapost." Berhasil di Draft", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedata"])){
    if(DeleteKKSO($_POST) > 0 ){
        $datapost = $_POST["del-noso"];
        $alert = array("Success!", "Data SO Nomor ".$datapost." berhasil di Batalkan", "success", "$redirect");
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
                    <h4 class="card-title">Stock Opname Barang Non Aktiva</h4>
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
                        <button type="button" class="btn btn-primary square btn-min-width ml-1 mr-1 mb-2" data-toggle="modal" data-target="#insert-kkso">Draft SO</button>
                        <!-- Modal KKSO -->
                        <div class="modal fade text-left" id="insert-kkso" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="" method="post" enctype="multipart/form-data" role="form">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white">Entry Data Draft SO</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-2">
                                                <?php
                                                    $code = "S";
                                                    $id = autonum(5, "no_so", "head_stock_opname");

                                                    if (strlen($id) == 5) {
                                                        $newid = $code.$id;
                                                    }
                                                    else {
                                                        $newid = $code.substr($id, 1);
                                                    }
                                                ?>
                                                    <input type="hidden" name="page-so" value="<?= $redirect; ?>" class="form-control" readonly>
                                                    <input type="hidden" name="kondisi-so" value="<?= $arrcond[5]; ?>" class="form-control" readonly>
                                                    <label>REF SO : </label>
                                                    <input type="text" name="no-so" value="<?= $newid; ?>" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Petugas : </label>
                                                    <input type="text" name="petugas-so" value="<?= $nik." - ".strtoupper($username);?>"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Office : </label>
                                                    <select class="select2 form-control block" style="width: 100%" type="text" name="office-so" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$idoffice'");
                                                        while($data_off = mysqli_fetch_assoc($query_off)) {
                                                        ?>
                                                        <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Department : </label>
                                                    <select class="select2 form-control block" style="width: 100%"
                                                        type="text" name="dept-so" required>
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                        $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$iddept'");
                                                        while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                                        ?>
                                                        <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label>Nama Barang : </label>
                                                    <select class="select2 form-control" data-placeholder="Please Select" multiple="multiple" style="width: 100%" type="text" name="barangso[]" required>
                                                        <?php 
                                                            $query_plu = mysqli_query($conn, "SELECT A.IDBarang, A.NamaBarang, B.IDJenis, B.NamaJenis FROM mastercategory AS A
                                                            INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang ORDER BY A.NamaBarang ASC");
                                                            while($data_plu = mysqli_fetch_assoc($query_plu)) { 
                                                        ?>
                                                        <option value="<?= $data_plu['IDBarang'].$data_plu['IDJenis'];?>"><?= $data_plu['IDBarang'].$data_plu['IDJenis']." - ".$data_plu['NamaBarang']." ".$data_plu['NamaJenis']; ?></option>
                                                        <?php 
                                                            } 
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertdata" class="btn btn-outline-primary">Draft SO</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal KKSO -->
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered zero-configuration text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO REF SO</th>
                                        <th>TANGGAL</th>
                                        <th>PETUGAS</th>
                                        <th>TOTAL BARANG</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no = 1;
                                    $result = "SELECT A.*, B.fisik_so, COUNT(B.pluid_so) AS total, C.id_office, C.office_name, D.id_department, D.department_name, E.username FROM head_stock_opname AS A 
                                    INNER JOIN detail_stock_opname AS B ON A.no_so = B.no_so_head
                                    INNER JOIN office AS C ON A.office_so = C.id_office
                                    INNER JOIN department AS D ON A.dept_so = D.id_department
                                    INNER JOIN users AS E ON A.user_so = E.nik
                                    WHERE A.office_so = '$idoffice' AND A.dept_so = '$iddept' AND A.jenis_so = 0 AND A.status_so = 'N' GROUP BY A.no_so";
                                    $query = mysqli_query($conn, $result);
                                    while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $data["no_so"];?></td>
                                        <td><?= $data["tgl_so"];?></td>
                                        <td><?= $data["user_so"]." - ".strtoupper($data["username"]);?></td>
                                        <td><?= $data["total"];?></td>
                                        <td>
                                        <span class="dropdown">
                                            <button id="action<?= $data['no_so']; ?>" type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="action<?= $data['no_so']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <a href="#" class="dropdown-item detail_draftso" title="Detail Data SO Nomor <?= $data["no_so"]; ?>" data-toggle="tooltip" data-placement="bottom" name="detail_draftso" id="<?= $data['no_so']; ?>"><i class="ft-eye"></i>Detail SO</a>
                                                <a href="index.php?page=<?= $encpid;?>&ext=<?= encrypt($arrextmenu[1]);?>&id=<?= encrypt($data["no_so"]); ?>" title="Proses SO Nomor <?= $data["no_so"]; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-edit"></i>Proses SO</a>
                                                <a href="reporting/report-kkso-nonaktiva.php?nomor=<?= encrypt($data['no_so']);?>" title="Print KKSO Nomor <?= $data["no_so"]; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print KKSO</a>
                                                <a href="#" class="dropdown-item delete_draftso" title="Delete Data SO Nomor <?= $data["no_so"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_draftso" id="<?= $data['no_so']; ?>"><i class="ft-delete"></i>Batalkan SO</a>
                                            </span>
                                        </span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <!-- Modal Read -->
                                <div class="modal fade text-left" id="modalDetailDataSO" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info white">
                                                <h4 class="modal-title white">Detail Data Stock Opname</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="body_detaildataso">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                <!-- Modal Delete -->
                                <div class="modal fade text-left" id="modalDeleteDataSO" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form action="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" id="del-nodataso" name="del-noso" readonly>
                                                    <label id="del-labeldataso"></label>
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
        <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script type="text/javascript">

$(document).ready(function(){
    $(document).on('click', '.detail_draftso', function(){  
        var nomor_so = $(this).attr("id");  
        if(nomor_so != '') {  
            $.ajax({
                url:"action/datarequest.php",
                method:"POST",  
                data:{DETAILDATASONA: nomor_so},  
                success:function(data){  
                    $('#body_detaildataso').html(data);
                    $('#modalDetailDataSO').modal('show');
                }  
            });
        }
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_draftso', function(){  
        var nomor_so = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{DELETEDATASO:nomor_so},  
            dataType:"json",  
            success:function(data){
                $('#del-nodataso').val(data.no_so);
                $('#del-labeldataso').html("Nomor SO : "+data.no_so);
                $('#modalDeleteDataSO').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>