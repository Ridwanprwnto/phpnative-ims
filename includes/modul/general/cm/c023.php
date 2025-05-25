<?php
    
    $_SESSION['PRINTSN'] = $_POST;

    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];
    
    $page_id = $_GET['page'];
    
    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $encpid = "index.php?page=".encrypt($dec_page);

    if(isset($_POST["generatesn"])){
        if(GenerateSNBarang($_POST) > 0 ){
            $datapost = isset($_POST["nomor-sn"]) ? $_POST["nomor-sn"] : NULL;
            $alert = array("Success!", "Data Serial Number ".$datapost." Berhasil Ditambah", "success", "$encpid");
        }
        else {
            echo mysqli_error($conn);
        }
    }
    elseif(isset($_POST["deletedata"])){
        if(DeleteSNBarang($_POST)){
            $datapost = isset($_POST["data-sn"]) ? $_POST["data-sn"] : NULL;
            $alert = array("Success!", "Data Serial Number ".$datapost." Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title" id="horz-layout-basic">Data Serial Number Barang</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-min-width ml-1 mr-1 mb-1" data-toggle="modal" data-target="#generatesn">Generate SN</button>
                                    <!-- Start Modal -->
                                    <div class="modal fade text-left" id="generatesn" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-primary white">
                                                        <h4 class="modal-title white" id="myModalLabel">Entry Data Serial Number</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input class="form-control" type="hidden" name="office-sn" value="<?= $office_id; ?>">
                                                            <input class="form-control" type="hidden" name="dept-sn" value="<?= $dept_id; ?>">
                                                            <div class="col-md-12 mb-2">
                                                            <label>Nama Barang : </label>
                                                            <select type="text" name="barang-sn" id="barang-sn" class="select2 form-control block" style="width: 100%" required>
                                                                <option value="" selected disabled>Please Select</option>
                                                                <?php 
                                                                    $query_sn = mysqli_query($conn, "SELECT A.IDJenis, A.NamaJenis, B.IDBarang, B.NamaBarang FROM masterjenis AS A
                                                                    INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang");
                                                                    while($data_sn = mysqli_fetch_assoc($query_sn)) { ?>
                                                                    <option value="<?= $data_sn['IDBarang'].$data_sn['IDJenis'];?>">
                                                                        <?= $data_sn['IDBarang'].$data_sn['IDJenis']." - ".$data_sn['NamaBarang']." ".$data_sn['NamaJenis'];?>
                                                                    </option>
                                                                    <?php 
                                                                    } 
                                                                ?>
                                                            </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor SN Baru : </label>
                                                                <input class="form-control" type="text" name="nomor-sn" id="nomor-sn" readonly>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Nomor Aktiva : </label>
                                                                <input class="form-control" type="text" name="aktiva-sn" placeholder="Nomor aktiva 10 karakter" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="generatesn" class="btn btn-outline-primary">Generate</button>
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
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Serial Number</th>
                                    <th>Cetak Label</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            $sql = "SELECT A.*, B.office_name, C.department_name, D.NamaBarang, E.NamaJenis FROM serial_number AS A 
                            INNER JOIN office AS B ON A.office_serial_number = B.id_office
                            INNER JOIN department AS C ON A.dept_serial_number = C.id_department
                            INNER JOIN mastercategory AS D ON LEFT(A.pluid_serial_number, 6) = D.IDBarang
                            INNER JOIN masterjenis AS E ON RIGHT(A.pluid_serial_number, 4) = E.IDJenis
                            WHERE A.office_serial_number = '$office_id' AND A.dept_serial_number = '$dept_id'";
                            $result = mysqli_query($conn, $sql);
                            while($data = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['pluid_serial_number']; ?></td>
                                    <td><?= $data['NamaBarang']." ".$data['NamaJenis']; ?></td>
                                    <td><?= $data['nomor_serial_number']; ?></td>
                                    <td>
                                    <a title="Print Label SN <?= $data['nomor_serial_number']; ?>" data-toggle="tooltip" data-placement="bottom" href="reporting/report-sn.php?nomor=<?= encrypt($data['nomor_serial_number']);?>" class="btn btn-icon btn-primary" onclick="document.location.href='<?= $encpid;?>'" target="_blank" ><i class="ft-printer"></i> Print Label</a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-danger delete_serialnumber"  name="delete_serialnumber" id="<?= $data["id_serial_number"]; ?>" title="Delete Data Serial Number : <?= $data['nomor_serial_number']; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-delete"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Delete -->
                            <div class="modal fade text-left" id="deleteModalSerialNumber" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" name="page-sn" value="<?= $encpid; ?>" readonly>
                                            <input type="hidden" class="form-control" id="id-deldatasn" name="id-sn" readonly>
                                            <input type="hidden" class="form-control" id="sn-deldatasn" name="data-sn" readonly>
                                            <label id="label-deldatasn"></label>
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
    $(document).ready(function () {
        $("#barang-sn").on('change', function () {
            var barangSN = $('#barang-sn').val();
            if (barangSN) {
                $.ajax({
                    type: 'POST',
                    url: 'action/datarequest.php',
                    data: {
                        PLUIDSN: barangSN,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#nomor-sn').val((data[0]));
                        } else {
                            $('#nomor-sn').val('');
                        }
                    }
                });
            } else {
                $('#nomor-sn').val('');
            }
        });

    });
    
    $(document).ready(function(){
        $(document).on('click', '.delete_serialnumber', function(){  
            var id_sn = $(this).attr("id");  
            $.ajax({  
                url:"action/datarequest.php",  
                method:"POST",  
                data:{DELETESERIALNUMBER:id_sn},  
                dataType:"json",  
                success:function(data){
                    $('#id-deldatasn').val(data.id_serial_number);
                    $('#sn-deldatasn').val(data.nomor_serial_number);
                    
                    $('#label-deldatasn').html("Delete Data SN "+data.nomor_serial_number);
                    $('#deleteModalSerialNumber').modal('show');
                }  
            });
        });
    });
</script>

<?php
    include ("includes/templates/alert.php");
?>
