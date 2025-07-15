<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["postingdata"])){
    if(PostingTablokPertemanan($_POST) > 0 ){
        $datapost = isset($_POST["plu-id"]) ? $_POST["plu-id"] : NULL;
        $alert = array("Success!", "Data Pertemanan PLU ".$datapost." Berhasil Diposting", "success", "$redirect");
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
                    <h4 class="card-title">Group Pertemanan Tabel Lokasi Planogram</h4>
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
                        <form class="form" id="form_nearest_group" action="" method="post">
                        <div class="form-body">
                            <h4 class="form-section"><i class="la la-dropbox"></i> Data Item</h4>
                            <div class="row">
                                <input type="hidden" name="page-id" value="<?= $redirect; ?>" class="form-control" readonly>
                                <input type="hidden" name="ip-id" value="<?= getUserIP(); ?>" class="form-control" readonly>
                                <input type="hidden" name="user-id" value="<?= $usernik." - ".strtoupper($username); ?>" class="form-control" readonly>
                                <input type="hidden" id="office-id" name="office-id" value="<?= $office_id; ?>" class="form-control" readonly>
                                <div class="form-group col-md-12 mb-2">
                                    <label>PLUID</label>
                                    <input type="text" id="plu-id" name="plu-id" class="form-control" placeholder="Enter PLUID" required>
                                </div>
                            </div>
                            <h4 class="form-section"><i class="la la-address"></i> Data Tabel Lokasi</h4>
                            <div class="row">
                                <div class="form-group col-3 mb-2">
                                    <label>Deskripsi</label>
                                    <input type="text" id="desc-plano" name="desc-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Tipe Item</label>
                                    <input type="text" id="item-plano" name="item-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Tipe Rak</label>
                                    <input type="text" id="tipe-plano" name="tipe-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Zona</label>
                                    <input type="text" id="zona-plano" name="zona-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Line</label>
                                    <input type="text" id="line-plano" name="line-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Rak</label>
                                    <input type="text" id="rak-plano" name="rak-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Shelf</label>
                                    <input type="text" id="shelf-plano" name="shelf-plano" class="form-control" readonly>
                                </div>
                                <div class="form-group col-3 mb-2">
                                    <label>Cell</label>
                                    <input type="text" id="cell-plano" name="cell-plano" class="form-control" readonly>
                                </div>
                            </div>
                            <h4 class="form-section"><i class="la la-address"></i> Data Pertemanan</h4>
                            <table class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Line</th>
                                        <th scope="col">Rak</th>
                                        <th><button type="button" name="add_nearest_group" class="btn btn-success btn-xs add_nearest_group"><i class="ft-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody id="table-nearest-group">
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade text-left" id="proses-nearest-group" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success white">
                                        <h4 class="modal-title white">Posting Confirmation</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Pastikan data line dan rak pertemanan yang anda input sudah sesuai!</label>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">No</button>
                                    <button type="submit" name="postingdata" class="btn btn-outline-success">Yes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        </form>
                        <button type="button" class="btn btn-success btn-min-width pull-right mb-1" data-toggle="modal" data-target="#proses-nearest-group">Posting</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>

var apiUrl = <?= json_encode(GetAPIService('EXPRESS-PG')) ?>;

$(document).ready(function(){

    let count = 0;

    $(document).on('click', '.add_nearest_group', function(){
        count++;
        
        var html = '';
        html += '<tr>';
        html += '<td><select type="text" name="line_nearest_group['+String(count)+']" data-line_nearest_group_id="'+String(count)+'" class="select2 form-control block line_nearest_group" data-placeholder="Please Select" style="width: 100%" required></select></td>';
        html += '<td><select type="text" name="rak_nearest_group['+String(count)+'][]" data-rak_nearest_group_id="'+count+'" id="rak_nearest_group_id'+String(count)+'" class="select2 form-control block rak_nearest_group" style="width: 100%" data-placeholder="Please Select" multiple="multiple" required></select></td>';
        html += '<td><button type="button" name="remove_nearest_group" class="btn btn-danger btn-xs remove_nearest_group"><i class="ft-minus"></i></button></td>';
        html += '</tr>';

        $('#table-nearest-group').append(html);
        $(".select2").select2();

        var tipeRAK = $("#tipe-plano").val();
        var dataToSend = {
            "tiperak": tipeRAK
        };
        $.ajax({
            url: apiUrl+"/auth/zonarak",
            method: "POST",
            data: JSON.stringify(dataToSend),
            contentType: "application/json",
            success: function(response) {
                var $select = $('#table-nearest-group tr:last-child .line_nearest_group');
                $select.empty();
                // Add placeholder option
                $select.append(
                    $('<option></option>')
                        .val('')
                        .text('Please select an option')
                        .prop('selected', true)
                        .prop('disabled', true)
                );
                // Add actual options from response
                response.forEach(function(item) {
                    var option = $('<option></option>').val(item.pla_zonarak).text(item.pla_zonarak);
                    $select.append(option);
                });
                $select.select2(); // Reinitialize select2
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data: ", error);
            }
        });
    });

    $(document).on('change', '.line_nearest_group', function(){
        
        var tipeRAK = $("#tipe-plano").val();
        var lineType = $(this).val();
        var lineTypeID = $(this).data('line_nearest_group_id');

        var dataToSend = {
            "tiperak": tipeRAK,
            "linerak": lineType
        };
        $.ajax({
            url: apiUrl+"/auth/linerak",
            method: "POST",
            data: JSON.stringify(dataToSend),
            contentType: "application/json",
            success: function(response) {
                // Menggunakan closest untuk menemukan baris yang tepat
                var $select = $(this).closest('tr').find('.rak_nearest_group[data-rak_nearest_group_id="' + lineTypeID + '"]');
                $select.empty();
                // Add actual options from response
                response.forEach(function(item) {
                    var option = $('<option></option>').val(item.pla_rak).text(item.pla_rak);
                    $select.append(option);
                });
                $select.select2(); // Reinitialize select2
            }.bind(this), // Bind 'this' untuk menjaga konteks
            error: function(xhr, status, error) {
                console.error("Error fetching data: ", error);
            }
        });
    });

    $(document).on('click', '.remove_nearest_group', function(){
        $(this).closest('tr').remove();
    });
});

function resetRowsTable() {
    $('#table-nearest-group').empty();
}

$(document).ready(function(){
    load_data();
    function load_data(offID, pluID) {
        var dataToSend = {
            "office": offID,
            "pluid": pluID
        };
        $.ajax({
            url: apiUrl+"/auth/tablokplano",
            method:"POST",
            data: JSON.stringify(dataToSend),
            contentType: "application/json",
            success:function(response) {
                $("#desc-plano").val(response.mbr_full_nama || '');
                $("#item-plano").val(response.pla_zonabarang || '');
                $("#tipe-plano").val(response.pla_fk_tipe || '');
                $("#zona-plano").val(response.pla_zona || '');
                $("#line-plano").val(response.pla_line || '');
                $("#rak-plano").val(response.pla_rak || '');
                $("#shelf-plano").val(response.pla_shelf || '');
                $("#cell-plano").val(response.pla_cell || '');
            },
            error: function(xhr, status, error){
                console.log("Terjadi kesalahan: " + error);
                $("#desc-plano, #item-plano, #tipe-plano, #zona-plano, #line-plano, #rak-plano, #shelf-plano, #cell-plano").val('');
            }
        });
    }
    $('#office-id').keyup(function(){
        var offID = $("#office-id").val();
        var pluID = $("#plu-id").val();
        load_data(offID, pluID);
        resetRowsTable();
    });
    $('#plu-id').keyup(function(){
        var offID = $("#office-id").val();
        var pluID = $("#plu-id").val();
        load_data(offID, pluID);
        resetRowsTable();
    });
});

</script>

<?php
    include ("includes/templates/alert.php");
?>