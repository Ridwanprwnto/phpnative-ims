<?php

$office_id = $_SESSION['office'];
$dept_id = $_SESSION['department'];
$div_id = $_SESSION['divisi'];

$page_id = $_GET['page'];

$strplus_pi = rplplus($page_id);
$dec_page = decrypt($strplus_pi);

$encpid = "index.php?page=".encrypt($dec_page);

if(isset($_POST["lapordata"])){
    if(LaporPenilaianTahunanLeader($_POST)){
        $datapost = isset($_POST["tahun-laporbuatperiodeassest"]) ? $_POST["tahun-laporbuatperiodeassest"] : NULL;
        $alert = array("Success!", "Penilaian Tahun ".$datapost." Berhasil Laporkan", "success", "$encpid");
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
                    <h4 class="card-title">Monitoring Data Assesment</h4>
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
                        <table class="table table-striped table-bordered zero-configuration row-grouping-dataassessment" id="monitor_data_assessment">
                            <thead>
                                <tr>
                                    <th>DETAIL</th>
                                    <th>NO</th>
                                    <th>KANTOR</th>
                                    <th>DIVISI</th>
                                    <th>TAHUN</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nom = 1;
                                $query_mon = mysqli_query($conn, "SELECT A.*, B.office_name, C.department_name, D.head_id_divisi, E.*, F.divisi_name, G.username FROM statusassessment AS A
                                INNER JOIN office AS B ON A.office_sts_assest = B.id_office
                                INNER JOIN department AS C ON A.dept_sts_assest = C.id_department
                                INNER JOIN divisi_assessment AS D ON A.code_sts_assest = D.head_code_sts_assest
                                INNER JOIN data_assessment AS E ON A.id_sts_assest = E.head_id_sts_assest
                                INNER JOIN divisi AS F ON E.div_data_assest = F.id_divisi
                                INNER JOIN users AS G ON E.junior_data_assest = G.nik
                                WHERE A.office_sts_assest = '$office_id' AND A.dept_sts_assest = '$dept_id' AND D.head_id_divisi = '$div_id' AND E.leader_data_assest = '$nik' GROUP BY A.id_sts_assest ORDER BY E.junior_data_assest ASC");
                                while($data_mon = mysqli_fetch_assoc($query_mon)) {
                                ?>
                                <tr>
                                    <td class="details-dataasst" id="<?= $data_mon["leader_data_assest"].$data_mon['id_sts_assest']; ?>" onclick="changeIcon(this)">
                                        <button type="button" class="btn btn-icon btn-pure success mr-1"><i class="la la-plus"></i></button>
                                    </td>
                                    <td><?= $nom++; ?></td>
                                    <td><?= $data_mon['office_sts_assest']." - ".strtoupper($data_mon['office_name'])." ".strtoupper($data_mon['department_name']); ?></td>
                                    <td><?= strtoupper($data_mon['divisi_name']); ?></td>
                                    <td>
                                        <h6 class="mb-0">
                                            <span class="text-bold-600">Tahun <?= $data_mon['tahun_sts_assest']; ?></span> on
                                            <em><?= date( "d M y", strtotime($data_mon['date_sts_assest'])); ?></em>
                                        </h6>
                                    </td>
                                    <td>
                                        <div class="badge badge-<?= $data_mon['status_data_assest'] == 'Y' ? 'info' : 'danger'; ?> "><?= $data_mon['status_data_assest'] == 'Y' ? 'Final' : 'Draft'; ?></div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-primary posting_datapenilaian" title="Lapor Penilaian Periode Tahun <?= $data_mon['tahun_sts_assest']; ?>" name="posting_datapenilaian" id="<?= $data_mon["leader_data_assest"].$data_mon["id_sts_assest"]; ?>" data-toggle="tooltip" data-placement="bottom"><i class="ft-check"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                            ?>
                            </tbody>
                            <!-- Modal -->
                            <div class="modal fade text-left" id="postingModalMonAssessment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <form message="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel1">Posting Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="page-laporbuatperiodeassest" value="<?= $encpid; ?>" class="form-control" readonly>
                                            <input type="hidden" id="id-laporbuatperiodeassest" name="id-laporbuatperiodeassest" class="form-control" readonly>
                                            <input type="hidden" id="docno-laporbuatperiodeassest" name="docno-laporbuatperiodeassest" class="form-control" readonly>
                                            <input type="hidden" id="leader-laporbuatperiodeassest" name="leader-laporbuatperiodeassest" class="form-control" readonly>
                                            <input type="hidden" id="tahun-laporbuatperiodeassest" name="tahun-laporbuatperiodeassest" class="form-control" readonly>
                                            <label id="label-laporbuatperiodeassest"></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="lapordata" class="btn btn-outline-primary">Yes</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </table>
                        <!-- Modal Read -->
                        <div class="modal fade text-left" id="nilaiModalMonAssessment" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info white">
                                        <h4 class="modal-title white">Detail Data Penilaian</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="body_detailpenilaian">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Auto Fill table -->

<script>
$(document).ready(function(){
    $(document).on('click', '.posting_datapenilaian', function(){  
        var id_tahunnilai = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONMONITORINGASSESSMENTLAPOR:id_tahunnilai},  
            dataType:"json",  
            success:function(data){
                $('#id-laporbuatperiodeassest').val(data.head_id_sts_assest);
                $('#docno-laporbuatperiodeassest').val(data.docno_data_assest);
                $('#leader-laporbuatperiodeassest').val(data.leader_data_assest);
                $('#tahun-laporbuatperiodeassest').val(data.th_data_assest);
                
                $('#label-laporbuatperiodeassest').html("Proses ini akan memposting semua draft data yang sudah dilakukan penilaian");
                $('#postingModalMonAssessment').modal('show');
            }  
        });
    });
});

$(document).ready(function() {
    
    $('.row-grouping-dataassessment').DataTable({
        responsive: false,
        autoWidth: false,
        rowReorder: false,
        scrollX: false,
        columnDefs: [
            { "visible": false, "targets": 4 },
        ],
        displayLength: 10,
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(4, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $('.row-grouping-dataassessment tbody').on( 'click', 'tr.group', function () {
        if (typeof table !== 'undefined' && table.order()[0]) {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 4 && currentOrder[1] === 'asc' ) {
                table.order( [ 4, 'desc' ] ).draw();
            }
            else {
                table.order( [ 4, 'asc' ] ).draw();
            }
        }
    });

});

$(document).ready(function () {

    var table = $('#monitor_data_assessment').DataTable({
        destroy: true,
        retrieve: true
    });

    // Add event listener for opening and closing details
    $('#monitor_data_assessment').on('click', 'td.details-dataasst', function () {
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
            data:{ACTIONDETAILMONITORASSESSMENTDRAFT:no_id},
            dataType: "json",
        }).done(function(data){
            table.DataTable( {
                data: data.data,
                columns: [
                    { title: 'TANGGAL', data: 'DATE_ASSESST' },
                    { title: 'DOCNO', data: 'DOCNO_ASSESST' },
                    { title: 'JUNIOR', data: 'JUNIOR_ASSESST', render: function(data) { 
                        return data.toUpperCase();
                    } },
                    { title: 'POIN', data: 'POIN_ASSESST' },
                    { title: 'GRADE', data: 'GRADE_ASSESST' },
                    { 
                        title: 'ACTION',
                        data: null,
                        render: function(data, type, row) {
                            return '<button type="button" class="btn btn-icon btn-info lihat_nikpenilaian" title="Lihat Laporan Hasil Evaluasi Penilaian NIK '+ row.JUNIOR_ASSESST.toUpperCase() + ' Periode Tahun ' + row.THN_ASSESST + '" name="lihat_nikpenilaian" id="' + row.DOCNO_ASSESST + '" data-toggle="tooltip" data-placement="bottom"><i class="ft-eye"></i></button>';
                        }
                    }
                ],
                order: [[1, 'asc']]
            } );
        })
    }
});

$(document).ready(function(){
    $(document).on('click', '.lihat_nikpenilaian', function(){
        var id_docno = $(this).attr("id");
        $.ajax({  
            url:"action/datarequest.php",  
            method:"POST",  
            data:{ACTIONDETAILASSESSMENT:id_docno},
            success:function(data){
                $('#body_detailpenilaian').html(data);
                $('#nilaiModalMonAssessment').modal('show');
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