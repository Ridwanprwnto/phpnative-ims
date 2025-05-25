<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$level_id = $_SESSION['level'];
$usernik = $_SESSION["user_nik"];

$_SESSION['PRINTPROJECT'] = $_POST;

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));

$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

if(isset($_POST["deletedata"])){
    if(CancelProject($_POST) > 0 ){
        $datapost = $_POST["del-no"];
        $alert = array("Success!", "Data Project  Nomor ".$datapost." berhasil dibatalkan", "success", "$redirect");
    }
    else {
        echo mysqli_error($conn);
    }
}
elseif(isset($_POST["uploaddata"])){
    if(UploadDocProject($_POST) > 0 ){
        $datapost = $_POST["no-project"];
        $alert = array("Success!", "Document Project  Nomor ".$datapost." berhasil diupload", "success", "$redirect");
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
                    <h4 class="card-title">Daftar Pengerjaan Proyek</h4>
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
                    <table class="table table-striped table-bordered row-grouping-project" id="list_project_task">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>DOCNO</th>
                                    <th>JUDUL PROJECT KERJA</th>
                                    <th>TAHAPAN KERJA</th>
                                    <th>INSTRUKSI</th>
                                    <th>PEMBUAT</th>
                                    <th>PRIORITAS</th>
                                    <th>PROGRESS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;

                                $query = mysqli_query($conn, "SELECT A.*, B.*, COUNT(B.ref_project_task) AS jumlah_task, COUNT(IF(B.status_project_task = 'Y', 1, NULL)) AS jumlah_selesai, COUNT(B.ref_project_task) AS jumlah_kerja, E.username, F.username AS user_pengerja FROM head_project AS A
                                INNER JOIN project_task AS B ON A.no_head_project = B.ref_project_task
                                INNER JOIN office AS C ON A.office_head_project = C.id_office
                                INNER JOIN department AS D ON A.dept_head_project = D.id_department
                                LEFT JOIN users AS E ON A.user_head_project = E.nik
                                LEFT JOIN users AS F ON B.user_project_task = F.nik
                                WHERE A.office_head_project = '$office_id' AND A.dept_head_project = '$dept_id' GROUP BY A.no_head_project ORDER BY A.tgl_head_project ASC");

                                while($data = mysqli_fetch_assoc($query)) {
                                    $jumlah = $data['jumlah_task'];
                                    $selesai = $data['jumlah_selesai'];
                                    $persentasi = number_format($selesai / $jumlah * 100);
                            ?>
                                <tr>
                                    <td class="details-dataproject" id="<?= $data['no_head_project']; ?>" onclick="changeIcon(this)">
                                        <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                    </td>
                                    <td><a href="javascript:void(0);" id="<?= $data['id_head_project']; ?>" name="detail_project" title="Show Detail Project Nomor <?= $data['no_head_project']; ?>" data-toggle="tooltip" data-placement="bottom" class="text-bold-600 detail_project"><?= $data['no_head_project']; ?></a></td>
                                    <td>
                                        <h5 class="mb-0"><?= $no++.". "; ?>
                                        <span class="text-bold-600"><?= $data['judul_head_project']; ?></span> on
                                        <em><?= date( "d/m/Y", strtotime($data['tgl_head_project'])); ?></em>
                                        </h5>
                                    </td>
                                    <td><?= $selesai." OF ".$jumlah; ?></td>
                                    <td><?= $data['approve_head_project']; ?></td>
                                    <td><?= $data['user_head_project']." - ".strtoupper($data['username']); ?></td>
                                    <td>
                                        <div class="badge badge-<?= $data['urgensi_head_project'] == 'SECEPATNYA' ? 'danger' : 'warning'; ?> label-square">
                                            <i class="ft-info font-medium-2"></i>
                                            <span><?= $data['urgensi_head_project']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center" id="example-caption-2">Progress <?= $persentasi; ?>%</div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $persentasi; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $persentasi; ?>%" aria-describedby="example-caption-2"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="dropdown">
                                            <button id="idaction<?= $data['no_head_project']; ?>" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right"><i class="ft-menu"></i></button>
                                            <span aria-labelledby="idaction<?= $data['no_head_project']; ?>" class="dropdown-menu mt-1 dropdown-menu-right">
                                                <a href="javascript:void(0);" id="<?= $data['no_head_project']; ?>" name="upload_project" title="Upload Document Project Nomor <?= $data['no_head_project']; ?>" class="dropdown-item upload_project" data-toggle="tooltip" data-placement="bottom"><i class="ft-upload"></i>Upload Dokumen</a>
                                                <a href="files/project/index.php?nomor=<?= encrypt($data['doc_head_project']);?>" title="Print Doc Project Nomor <?= $data['no_head_project']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item" class="dropdown-item" onclick="document.location.href='<?= $redirect;?>'" target="_blank" ><i class="ft-printer"></i>Print Dokumen</a>
                                                <a href="index.php?page=<?= $encpid; ?>&ext=<?= encrypt($arrextmenu[8]);?>&id=<?= encrypt($data['no_head_project']);?>" title="Update Project Nomor <?= $data['no_head_project']; ?>" data-toggle="tooltip" data-placement="bottom" class="dropdown-item"><i class="ft-edit-3"></i>Update Project</a>
                                                <a href="javascript:void(0);" id="<?= $data['no_head_project']; ?>" name="batal_project" title="Batalkan Project Nomor <?= $data['no_head_project']; ?>" class="dropdown-item batal_project" data-toggle="tooltip" data-placement="bottom"><i class="ft-trash"></i>Batalkan Project</a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal Upload Doc Project -->
                            <div class="modal fade text-left" id="modalUploadProject" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success white">
                                            <h4 class="modal-title white" id="upl-labelproject"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <input class="form-control" type="hidden" name="page-project" value="<?= $redirect; ?>" readonly>
                                                <input class="form-control" type="hidden" id="upl-idproject" name="id-project" readonly>
                                                <input class="form-control" type="hidden" id="upl-noproject" name="no-project" readonly>
                                                <input class="form-control" type="hidden" id="upl-docproject" name="docold-project" readonly>
                                                <div class="col-md-12 mb-2">
                                                    <label>File Document (Optional) </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="doc-project">
                                                        <label class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="uploaddata" class="btn grey btn-outline-success">Upload</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <!-- Modal Batal Peoject -->
                            <div class="modal fade text-left" id="modalDeleteProject" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger white">
                                            <h4 class="modal-title white">Delete Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" class="form-control" id="del-noproject" name="del-no" readonly>
                                            <input type="hidden" class="form-control" id="del-docproject" name="del-doc" readonly>
                                            <label id="del-labelproject"></label>
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
<!--/ Auto Fill table -->

<script>
    
$(document).ready(function() {
    
    $('#list_project_task').DataTable({
        columnDefs: [
            { "visible": false, "targets": 6 },
        ],
        displayLength: 10,
        order: [[2, 'desc']],
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(6, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="11">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    } );

    $('.row-grouping-project tbody').on( 'click', 'tr.group', function () {
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

    var table = $('#list_project_task').DataTable({
        destroy: true,
        retrieve: true,
        responsive: false,
        autoWidth: true,
        rowReorder: false,
        scrollX: false
    });

    // Add event listener for opening and closing details
    $('#list_project_task').on('click', 'td.details-dataproject', function () {
        var docno_project = $(this).attr('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            createChild(row, docno_project);
            // format(row.child, noref_pp);
            tr.addClass('shown');
        }
    });

    function createChild (row, docno_project) {
        // This is the table we'll convert into a DataTable
        var table = $('<table class="display" width="100%"/>');

        // Display it the child row
        row.child( table ).show();

        $.ajax({
            url:'action/datarequest.php',
            method:"POST",
            data:{ACTIONDETAILPROJECT:docno_project},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'TAHAPAN', data: 'urutan_project_task' },
                    { title: 'PIC / PELAKSANA', data: 'pic_project_task' },
                    { title: 'PENGERJAAN', data: 'pengerjaan_project_task' },
                    { title: 'JUMLAH', data: 'jumlah_project_task' },
                    { title: 'KESULITAN', data: 'priority_project_task' },
                    { title: 'TGL PENGERJAAN', data: 'EFEKTIF_PRYK' },
                    { title: 'KETERANGAN', data: 'KET_PRYK' },
                    { title: 'STATUS', data: 'STS_PRYK' },
                ],
                order: [[0, 'asc']]
            } );
        })
    }
});

$(document).ready(function(){
    $(document).on('click', '.batal_project', function(){  
        var nomor_proj = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPROJECT:nomor_proj},  
            dataType:"json",  
            success:function(data){
                $('#del-noproject').val(data.no_head_project);
                $('#del-docproject').val(data.doc_head_project);
                
                $('#del-labelproject').html("Batalkan Project Nomor : "+data.no_head_project);
                $('#modalDeleteProject').modal('show');
            }  
        });
    });
});


$(document).ready(function(){
    $(document).on('click', '.upload_project', function(){  
        var nomor_proj = $(this).attr("id");  
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONPROJECT:nomor_proj},  
            dataType:"json",  
            success:function(data){
                $('#upl-idproject').val(data.id_head_project);
                $('#upl-noproject').val(data.no_head_project);
                $('#upl-docoldproject').val(data.doc_head_project);
                
                $('#upl-labelproject').html("Upload Document Project Nomor : "+data.no_head_project);
                $('#modalUploadProject').modal('show');
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