<?php

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["prosesbaranghilang"])) {
    if(ProsesBarangHilang($_POST) > 0 ) {
        $datapost = isset($_POST["sn-barang"]) ? $_POST["sn-barang"] : NULL;
        $alert = array("Success!", "Status Barang Hilang SN ".$datapost." Berhasil Update Penempatan", "success", "$encpid");
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
                    <h4 class="card-title">Listing Data Barang Hilang / Belum Ditemukan</h4>
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
                                class="table display nowrap table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA BARANG</th>
                                        <th>SERIAL NUMBER</th>
                                        <th>NO AKTIVA</th>
                                        <th>NO LAMBUNG</th>
                                        <th>KETERANGAN</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.* FROM barang_assets AS A
                                INNER JOIN office AS B ON LEFT(A.ba_id_office, 4) = B.id_office
                                INNER JOIN department AS C ON RIGHT(A.ba_id_department, 4) = C.id_department
                                INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
                                INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
                                INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
                                WHERE A.ba_id_office = '$idoffice' AND A.ba_id_department = '$iddept' AND A.kondisi = '$arrcond[6]'";
                                $query = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $data["pluid"].' - '.$data["NamaBarang"].' '.$data["NamaJenis"].' '.$data["ba_merk"].' '.$data["ba_tipe"];?></td>
                                        <td><?= $data["sn_barang"];?></td>
                                        <td><?= $data["no_at"];?></td>
                                        <td><?= $data["no_lambung"];?></td>
                                        <td><?= $data["posisi"];?></td>
                                        <td>
                                            <button title="Update Status Barang SN : <?= $data['sn_barang']; ?>" type="button" class="btn btn-icon btn-success update_baranghilang" id="<?= $data["id_ba"];?>" name="update_baranghilang" data-toggle="tooltip" data-placement="bottom"><i class="ft-edit"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <!-- Modal Update -->
                            <div class="modal fade text-left" id="updateModalBarangHilang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                                <h4 class="modal-title white" id="upd-label-baranghilang"></h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input class="form-control" type="hidden" name="office-barang" value="<?= $idoffice;?>" readonly>
                                                    <input class="form-control" type="hidden" name="dept-barang" value="<?= $iddept;?>" readonly>
                                                    <input class="form-control" type="hidden" id="upd-pluid-baranghilang" name="pluid-barang" readonly>
                                                    <input class="form-control" type="hidden" id="upd-sn-baranghilang" name="sn-barang" readonly>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Ditemukan Dengan Kondisi : </label>
                                                        <select class="select2 form-control block" style="width: 100%" type="text" id="upd-kondisi-baranghilang" name="kond-barang" required>
                                                            <option value="" selected disabled>Please Select</option>
                                                            <?php
                                                                $query_kondisi = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi NOT LIKE '$arrcond[5]'");
                                                                while($data_kondisi = mysqli_fetch_assoc($query_kondisi)) { ?>
                                                                <option value="<?= $data_kondisi['id_kondisi'];?>"><?= $data_kondisi['id_kondisi']." - ".$data_kondisi['kondisi_name'];?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label>Lokasi Barang :</label>
                                                        <textarea class="form-control" type="text" id="upd-lok-baranghilang" name="lokasi-barang" placeholder="Input lokasi barang saat ini" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="prosesbaranghilang" class="btn btn-outline-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
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
$(document).ready(function(){
    $(document).on('click', '.update_baranghilang', function(){  
        var id_barang = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONBRGHILANG:id_barang},  
            dataType:"json",  
            success:function(data){
                $('#upd-pluid-baranghilang').val(data.pluid);
                $('#upd-sn-baranghilang').val(data.sn_barang);
                
                $('#upd-kondisi-baranghilang').find('option[value="'+data.kondisi+'"]').remove();
                $('#upd-kondisi-baranghilang').append($('<option></option>').html(data.kondisi+" - "+data.kondisi_name).attr('value', data.kondisi).prop('selected', true));

                var selectList = $('#upd-kondisi-baranghilang option');

                selectList.sort(function(a,b){
                    a = a.value;
                    b = b.value;

                    return a-b;
                });

                $('#upd-kondisi-baranghilang').html(selectList);

                $('#upd-lok-baranghilang').val(data.posisi);
                $('#upd-label-baranghilang').html("Update Status Barang SN : "+data.sn_barang);
                $('#updateModalBarangHilang').modal('show');
            }  
        });
    });
});
</script>

<?php
    include ("includes/templates/alert.php");
?>