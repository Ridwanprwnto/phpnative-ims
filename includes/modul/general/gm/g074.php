<?php

$idoffice = $_SESSION['office'];
$iddept = $_SESSION['department'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["insertdatacat"])){
    if(InsertCtgPlg($_POST) > 0 ){
        $datapost = isset($_POST["name-cat"]) ? $_POST["name-cat"] : NULL;
        $alert = array("Success!", "Data Kategori Pelanggaran CCTV ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatacat"])){
    if(UpdateCtgPlg($_POST)){
        $datapost = isset($_POST["name-cat"]) ? $_POST["name-cat"] : NULL;
        $alert = array("Success!", "Data Kategori Pelanggaran CCTV ".$datapost." Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatacat"])){
    if(DeleteCtgPlg($_POST)){
        $datapost = isset($_POST["name-cat"]) ? $_POST["name-cat"] : NULL;
        $alert = array("Success!", "Data Kategori Pelanggaran CCTV ".$datapost." Berhasil Dihapus", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["insertdatajns"])){
    if(InsertJnsPlg($_POST) > 0 ){
        $datapost = isset($_POST["name-jns"]) ? $_POST["name-jns"] : NULL;
        $alert = array("Success!", "Data Jenis Pelanggaran CCTV ".$datapost." Berhasil Ditambah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["updatedatajns"])){
    if(UpdateJnsPlg($_POST)){
        $alert = array("Success!", "Data Jenis Pelanggaran CCTV Berhasil Dirubah", "success", "$encpid");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["deletedatajns"])){
    if(DeleteJnsPlg($_POST)){
        $alert = array("Success!", "Data Jenis Pelanggaran CCTV Berhasil Dihapus", "success", "$encpid");
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
                    <h4 class="card-title">Master Pelanggaran Terekam CCTV</h4>
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
                                <a class="nav-link active" id="category-pelanggaran" data-toggle="tab" href="#categorypelanggaran" aria-expanded="true">Kategori Pelanggaran</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="jenis-pelanggaran" data-toggle="tab" href="#jenispelanggaran" aria-expanded="false">Jenis Pelanggaran</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="categorypelanggaran" aria-expanded="true" aria-labelledby="category-pelanggaran">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entrycatplg">Tambah Kategori</button>
                                            <!-- Create Modal -->
                                            <div class="modal fade text-left" id="entrycatplg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Tambah Data Kategori Pelanggaran</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Karegori : </label>
                                                                        <input type="text" name="name-cat" placeholder="Input Kategori Pelanggaran" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatacat" class="btn btn-outline-primary">Save</button>
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
                                            <th>Kategori Pelanggaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $result = "SELECT * FROM category_pelanggaran";
                                    $query = mysqli_query($conn, $result);
                                    while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data['name_ctg_plg']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_catpelanggaran" title="Update Kategori Pelanggaran <?= $data["name_ctg_plg"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_catpelanggaran" id="<?= $data['id_ctg_plg']; ?>"><i class="ft-edit"></i></button>
                                                <?php if ($id_group == $arrgroup[0]) { ?>
                                                <button type="button" class="btn btn-icon btn-danger delete_catpelanggaran" title="Delete Kategori Pelanggaran <?= $data["name_ctg_plg"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_catpelanggaran" id="<?= $data['id_ctg_plg']; ?>"><i class="ft-delete"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalCatPelanggaran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-updcatpelanggaran"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" id="id-updcatpelanggaran" name="id-cat" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kategori Pelanggaran : </label>
                                                                <input type="text" id="name-updcatpelanggaran" name="name-cat" placeholder="Input Kategori Pelanggaran" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedatacat" class="btn btn-outline-success">Edit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalCatPelanggaran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form message="" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger white">
                                                    <h4 class="modal-title white" id="myModalLabel1">Delete Confirmation</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" class="form-control" id="page-delcatpelanggaran" name="page-cat" value="<?= $encpid; ?>" readonly>
                                                    <input type="hidden" class="form-control" id="id-delcatpelanggaran" name="id-cat" readonly>
                                                    <input type="hidden" class="form-control" id="name-delcatpelanggaran" name="name-cat" readonly>
                                                    <label id="label-delcatpelanggaran"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatacat" class="btn btn-outline-danger">Yes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </table>
                            </div>
                            <div class="tab-pane" id="jenispelanggaran" aria-labelledby="jenis-pelanggaran">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary square btn-min-width ml-1 mt-1 mr-1 mb-1" data-toggle="modal" data-target="#entryjnsplg">Tambah Jenis</button>
                                            <div class="modal fade text-left" id="entryjnsplg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post">
                                                            <div class="modal-header bg-primary white">
                                                                <h4 class="modal-title white" id="myModalLabel">Tambah Data Jenis Pelanggaran</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Kategori Pelanggaran : </label>
                                                                        <select class="select2 form-control block" style="width: 100%" type="text" name="id-cat" required>
                                                                            <option value="" selected disabled>Please Select</option>
                                                                            <?php
                                                                            $query_cat = mysqli_query($conn, "SELECT * FROM category_pelanggaran");
                                                                            while($data_cat = mysqli_fetch_assoc($query_cat)) {
                                                                            ?>
                                                                            <option value="<?= $data_cat["id_ctg_plg"];?>"><?= $data_cat["id_ctg_plg"].". ".$data_cat["name_ctg_plg"];?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <label>Jenis Pelanggaran :</label>
                                                                        <textarea class="form-control" type="text" name="name-jns" placeholder="Input Jenis Pelanggaran" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="insertdatajns" class="btn btn-outline-primary">Save</button>
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
                                            <th>Kategori Pelanggaran</th>
                                            <th>Jenis Pelanggaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $result = "SELECT A.*, B.* FROM jenis_pelanggaran AS A
                                    INNER JOIN category_pelanggaran AS B ON A.id_head_ctg_plg = B.id_ctg_plg";
                                    $query = mysqli_query($conn, $result);
                                    while($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data['id_ctg_plg'].". ".$data['name_ctg_plg']; ?></td>
                                            <td><?= $data['name_jns_plg']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-success update_jnspelanggaran" title="Update Jenis Pelanggaran <?= $data["name_jns_plg"]; ?>" data-toggle="tooltip" data-placement="bottom" name="update_jnspelanggaran" id="<?= $data['id_jns_plg']; ?>"><i class="ft-edit"></i></button>
                                                <?php if ($id_group == $arrgroup[0]) { ?>
                                                <button type="button" class="btn btn-icon btn-danger delete_jnspelanggaran" title="Delete Jenis Pelanggaran <?= $data["name_jns_plg"]; ?>" data-toggle="tooltip" data-placement="bottom" name="delete_jnspelanggaran" id="<?= $data['id_jns_plg']; ?>"><i class="ft-delete"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- Update Modal -->
                                    <div class="modal fade text-left" id="updateModalJnsPelanggaran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="label-updjnspelanggaran"></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <input type="hidden" class="form-control" id="id-updjnspelanggaran" name="id-jns" readonly>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Kategori Pelanggaran : </label>
                                                                <select class="select2 form-control block" style="width: 100%" type="text" id="catid-updjnspelanggaran" name="id-cat" required>
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <?php
                                                                    $query_cat = mysqli_query($conn, "SELECT * FROM category_pelanggaran");
                                                                    while($data_cat = mysqli_fetch_assoc($query_cat)) {
                                                                    ?>
                                                                    <option value="<?= $data_cat["id_ctg_plg"]; ?>" ><?= $data_cat["id_ctg_plg"].". ".$data_cat["name_ctg_plg"];?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label>Jenis Pelanggaran : </label>
                                                                <textarea class="form-control" type="text"  id="name-updjnspelanggaran" name="name-jns" placeholder="Input Jenis Pelanggaran" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updatedatajns" class="btn btn-outline-success">Edit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    <!-- Modal Delete -->
                                    <div class="modal fade text-left" id="deleteModalJnsPelanggaran" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" class="form-control" id="id-deljnspelanggaran" name="id-jns" readonly>
                                                    <input type="hidden" class="form-control" id="name-deljnspelanggaran" name="name-jns" readonly>
                                                    <label id="label-deljnspelanggaran"></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="deletedatajns" class="btn btn-outline-danger">Yes</button>
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
    </div>
</section>
<!--/ Auto Fill table -->

<script>

$(document).ready(function(){
    $(document).on('click', '.update_catpelanggaran', function(){  
        var ID_cat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{UPDATECATPELANGGARAN:ID_cat},  
            dataType:"json",  
            success:function(data){
                $('#id-updcatpelanggaran').val(data.id_ctg_plg);
                $('#name-updcatpelanggaran').val(data.name_ctg_plg);
                $('#label-updcatpelanggaran').html("Edit Kategori Pelanggaran Nomor ID "+data.id_ctg_plg);

                $('#updateModalCatPelanggaran').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_catpelanggaran', function(){  
        var ID_cat = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",
            data:{UPDATECATPELANGGARAN:ID_cat},  
            dataType:"json",  
            success:function(data){
                $('#id-delcatpelanggaran').val(data.id_ctg_plg);
                $('#name-delcatpelanggaran').val(data.name_ctg_plg);
                $('#label-delcatpelanggaran').html("Delete Kategori Pelanggaran "+data.name_ctg_plg);

                $('#deleteModalCatPelanggaran').modal('show');
            }  
        });
    });
});


$(document).ready(function(){
    $(document).on('click', '.update_jnspelanggaran', function(){  
        var ID_jns = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONJNSPELANGGARAN:ID_jns},  
            dataType:"json",  
            success:function(data){
                $('#id-updjnspelanggaran').val(data.id_jns_plg);

                $('#catid-updjnspelanggaran').find('option[value="'+data.id_ctg_plg+'"]').remove();
                $('#catid-updjnspelanggaran').append($('<option></option>').html(data.id_ctg_plg+". "+data.name_ctg_plg).attr('value', data.id_ctg_plg).prop('selected', true));

                $('#name-updjnspelanggaran').val(data.name_jns_plg);
                $('#label-updjnspelanggaran').html("Edit Jenis Pelanggaran Nomor ID "+data.id_jns_plg);

                $('#updateModalJnsPelanggaran').modal('show');
            }  
        });
    });
});

$(document).ready(function(){
    $(document).on('click', '.delete_jnspelanggaran', function(){  
        var ID_jns = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONJNSPELANGGARAN:ID_jns},  
            dataType:"json",  
            success:function(data){
                $('#id-deljnspelanggaran').val(data.id_jns_plg);
                $('#name-deljnspelanggaran').val(data.name_jns_plg);
                $('#label-deljnspelanggaran').html("Delete Jenis Pelanggaran Nomor ID "+data.id_jns_plg);

                $('#deleteModalJnsPelanggaran').modal('show');
            }  
        });
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>