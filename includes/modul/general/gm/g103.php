<?php

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$idoffice = $_SESSION["office"];
$iddept = $_SESSION["department"];
$div_id = $_SESSION['divisi'];
$usernik = $_SESSION["user_nik"];

$page_id = $_GET['page'];

$dec_page = decrypt(rplplus($page_id));
$encpid = encrypt($dec_page);

$redirect = "index.php?page=".$encpid;

$sql_periode = "SELECT A.*, B.head_id_divisi, C.officer_leader_assest, C.lvl_leader_assest FROM statusassessment AS A
INNER JOIN divisi_assessment AS B ON A.code_sts_assest = B.head_code_sts_assest
INNER JOIN leader_assessment AS C ON A.code_sts_assest = C.head_code_sts_assest
WHERE A.office_sts_assest = '$idoffice' AND A.dept_sts_assest = '$iddept' AND B.head_id_divisi = '$div_id' AND C.officer_leader_assest = '$usernik' AND A.flag_sts_assest = 'N'";

$getdata_periode = mysqli_query($conn, $sql_periode);
$row = mysqli_fetch_assoc($getdata_periode);
$row_id = isset($row["id_sts_assest"]) ? $row["id_sts_assest"] : NULL;
$row_doc = isset($row["code_sts_assest"]) ? $row["code_sts_assest"] : NULL;
$row_off = isset($row["office_sts_assest"]) ? $row["office_sts_assest"] : NULL;
$row_dpt = isset($row["dept_sts_assest"]) ? $row["dept_sts_assest"] : NULL;
$row_div = isset($row["head_id_divisi"]) ? $row["head_id_divisi"] : NULL;
$row_thn = isset($row["tahun_sts_assest"]) ? $row["tahun_sts_assest"] : NULL;
$row_lvl = isset($row["lvl_leader_assest"]) ? $row["lvl_leader_assest"] : NULL;

if(isset($_POST["insertdataassesment"])){
    if(CreatePenilaianTahunan($_POST) > 0 ){
        $datapost = isset($_POST["junior-assesment"]) ? $_POST["junior-assesment"] : NULL;
        $alert = array("Success!", "Anda telah selesai pengisian evaluasi penilaian tahunan dengan NIK ".$datapost." ", "success", "$redirect");
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
                    <h4 class="card-title">Form Evaluasi Penilaian Kinerja Tahunan</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                <?php
                    if(mysqli_num_rows($getdata_periode) > 0) { ?>
                    <div class="card-body">
                        <form class="form" id="form_entryemployee" action="" method="post">
                        <div class="form-body">
                            <h4 class="form-section"><i class="la la-user"></i> Data Employee</h4>
                            <div class="row">
                                <div class="form-group col-md-6 mb-2">
                                    <input type="hidden" name="page-assesment" value="<?= $redirect; ?>" class="form-control" readonly>
                                    <input type="hidden" name="lvl-assesment" value="<?= $row_lvl; ?>" class="form-control" readonly>
                                    <label>Office : </label>
                                    <select class="select2 form-control block" style="width: 100%" type="text" id="office-assesment" name="office-assesment" required>
                                        <option value="" selected disabled>Please Select</option>
                                        <?php
                                        if ($idoffice == $row_off) {
                                            $query_off = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$row_off'");
                                            while($data_off = mysqli_fetch_assoc($query_off)) {
                                            ?>
                                            <option value="<?= $data_off["id_office"];?>"><?= $data_off["id_office"]." - ".strtoupper($data_off["office_name"]);?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                <label>Department : </label>
                                    <select class="select2 form-control block" style="width: 100%" type="text" id="dept-assesment" name="dept-assesment" required>
                                        <option value="" selected disabled>Please Select</option>
                                        <?php
                                        if ($iddept == $row_dpt) {
                                            $query_dept = mysqli_query($conn, "SELECT * FROM department WHERE id_department = '$row_dpt'");
                                            while($data_dept = mysqli_fetch_assoc($query_dept)) {
                                            ?>
                                            <option value="<?= $data_dept["id_department"];?>"><?= strtoupper($data_dept["department_name"]);?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 mb-2">
                                    <label>Divisi : </label>
                                    <select class="select2 form-control block" style="width: 100%" type="text" id="div-assesment" name="div-assesment" required>
                                        <option value="" selected disabled>Please Select</option>
                                        <?php
                                        if ($div_id == $row_div) {
                                            $query_div = mysqli_query($conn, "SELECT * FROM divisi WHERE id_divisi = '$row_div'");
                                            while($data_div = mysqli_fetch_assoc($query_div)) {
                                            ?>
                                            <option value="<?= $data_div["id_divisi"];?>"><?= strtoupper($data_div["divisi_name"]);?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label>Assessed (Officer) : </label>
                                    <select id="officer-assesment" name="officer-assesment" class="select2 form-control block" style="width: 100%" type="text" required>
                                        <option value="" selected disabled>Please Select</option>
                                            <option value="<?=$nik;?>" ><?= strtoupper($nik." - ".$username);?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label>Tahun : </label>
                                    <select id="tahun-assesment" name="tahun-assesment" class="select2 form-control block" style="width: 100%" type="text" required>
                                        <option value="" selected disabled>Please Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label>Employee : </label>
                                    <select id="junior-assesment" name="junior-assesment" class="select2 form-control block" style="width: 100%" type="text" required>
                                        <option value="" selected disabled>Please Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label>Divisi : </label>
                                    <input type="text" name="divisi-assesment" id="divisi-assesment" class="form-control" readonly>
                                </div>
                            </div>
                            <h4 class="form-section"><i class="la la-check-circle"></i> Assessment Instrument</h4>
                            <table class="table table-striped table-bordered">
                            <?php
                                $total_poin = 0;
                                $sql_indikator = "SELECT * FROM indicator_assessment WHERE office_ind_assest = '$idoffice' ORDER BY num_ind_assest ASC";
                                $query_indikator = mysqli_query($conn, $sql_indikator);
                                while($result_indikator = mysqli_fetch_assoc($query_indikator)) {
                                    $id_indikator = $result_indikator["id_ind_assest"];
                                    
                                ?>
                                <input type="hidden" name="code-assesment[]" value="<?= $id_indikator; ?>" class="form-control" readonly>
                                <thead>
                                    <tr>
                                        <th colspan="12"><?= $result_indikator["num_ind_assest"].". ".$result_indikator["name_ind_assest"]; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <?php
                                        $id_instrument = array();
                                        $sql_instrument = "SELECT * FROM instrument_assessment WHERE id_head_ind_assest = '$id_indikator' ORDER BY code_ins_assest ASC";
                                        $query_instrument = mysqli_query($conn, $sql_instrument);
                                        while($result_instrument = mysqli_fetch_assoc($query_instrument)) {
                                        $id_instrument[] = $result_instrument["code_ins_assest"];
                                    ?>
                                        <td colspan="3">
                                            <?= $result_instrument["name_ins_assest"]; ?>
                                        </td>
                                    <?php
                                        }
                                    ?>
                                    </tr>
                                    <tr>
                                    <?php
                                    foreach ($id_instrument as $idx) {
                                        $sql_poin = "SELECT * FROM poin_assessment WHERE id_head_ins_assest = '$idx' ORDER BY value_poin_assest DESC";
                                        $query_poin = mysqli_query($conn, $sql_poin);
                                        while($result_poin = mysqli_fetch_assoc($query_poin)) {
                                        $id_poin = $result_poin["id_head_ins_assest"];
                                        $total_poin += $result_poin["value_poin_assest"];
                                        ?>
                                        <td class="text-center">
                                            <div class="row skin skin-flat">
                                                <input type="radio" value="<?= $result_poin["value_poin_assest"]; ?>" name="pointassesment-<?= $id_indikator; ?>" id="input-radio-point" class="input-radio-point">
                                                <label for="input-radio-point"><?= $result_poin["value_poin_assest"]; ?></label>
                                            </div>
                                        </td>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </tr>
                                </tbody>
                            <?php
                                }    
                            ?>
                            </table>
                            <div class="row">
                                <div class="form-group col-4 mb-2">
                                    <label>Jumlah Poin : </label>
                                    <input type="text" value="0" name="poin-assesment" id="poin-assesment" class="form-control" readonly>
                                </div>
                                <div class="form-group col-4 mb-2">
                                    <label>Poin Rata-rata : </label>
                                    <input type="text" value="0" name="avg-assesment" id="avg-assesment" class="form-control" readonly>
                                </div>
                                <div class="form-group col-4 mb-2">
                                    <label>Grade : </label>
                                    <input type="text" value="-" name="mutu-assesment" id="mutu-assesment" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label>Catatan penilaian : </label>
                                    <textarea class="form-control" rows="5" type="text" name="note-assesment" placeholder="Input Keterangan" required></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade text-left" id="proccess-assesment" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white">Proses Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Apakah anda sudah memastikan keseluruhan instrumen penilaian sudah sesuai dan terisi semua?, jika iya klik tombol ya untuk melanjutkan draft penilaian!</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="insertdataassesment" class="btn btn-outline-primary">Yes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <div class="form-actions right">
                            <button type="button"  class="btn btn-warning mr-1" id="resetButton" onclick="ResetForm()">
                            <i class="la la-retweet"></i> Reset
                            </button>
                            <button type="button"  class="btn btn-info mr-1" id="hitungButton" onclick="CalculatePoin()">
                            <i class="la la-calculator"></i> Hitung
                            </button>
                            <button type="submit" data-toggle="modal" data-target="#proccess-assesment" class="btn btn-primary">
                            <i class="la la-send-o"></i> Draft Penilaian
                            </button>
                        </div>
                        </form>
                    </div>
                    <?php
                        }
                        else {
                            include ("includes/templates/error-403.php");
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
        <!-- Striped rows end -->
</section>
<!-- // Basic form layout section end -->

<script>
$(document).ready(function() {
    $('input[type="radio"]').iCheck({
        radioClass: 'iradio_flat-blue'
    });
});

$(document).ready(function(){
    $("input[name=office-assesment],select[name=dept-assesment],select[name=div-assesment],select[name=officer-assesment]").on('change', function(){
        var office = $('#office-assesment').val();
        var dept = $('#dept-assesment').val();
        var div = $('#div-assesment').val();
        var leader = $('#officer-assesment').val();
        if(office && dept && div && leader) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {OFFICEASSESSMENT:office, DEPTASSESSMENT:dept, DIVASSESSMENT:div, LEADASSESSMENT:leader},
                success : function(htmlresponse) {
                    $('#tahun-assesment').html(htmlresponse);
                }
            });
        } else {
            $('#tahun-assesment').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function(){
    $("input[name=office-assesment],select[name=dept-assesment],select[name=div-assesment],select[name=officer-assesment],select[name=tahun-assesment]").on('change', function(){
        var office = $('#office-assesment').val();
        var dept = $('#dept-assesment').val();
        var div = $('#div-assesment').val();
        var leader = $('#officer-assesment').val();
        var tahun = $('#tahun-assesment').val();
        if(office && dept && div && leader && tahun) {
            $.ajax({
                type:'POST',
                url:'action/datarequest.php',
                data: {OFFICEASSESST:office, DEPTASSESST:dept, DIVASSESST:div, LEADASSESST:leader, TAHUNASSESST:tahun},
                success : function(htmlresponse) {
                    $('#divisi-assesment').val('');
                    $('#junior-assesment').html(htmlresponse);
                }
            });
        } else {
            $('#junior-assesment').html('<option value="" selected disabled>Please Select</option>');
        }
    });
});

$(document).ready(function () {
    $("#junior-assesment").on('change', function () {
        var id_junior = $('#junior-assesment').val();
        $.ajax({
            type: 'POST',
            url: 'action/datarequest.php',
            data: {
                JUNIORASSESSMENT: id_junior,
            },
            dataType: "JSON",
            success: function (data) {
                $('#divisi-assesment').val(data.divisi_name);
            }
        });
    });

});

function ResetForm() {
    var element = document.getElementById("form_entryemployee");
    element.reset();
    
    var checkradiobutton = $('input#input-radio-point');
    checkradiobutton.prop('checked', false);
    checkradiobutton.iCheck('update');

    $("#form_entryemployee").find('select').select2().val('').trigger('change');
}

const gradeScales = [
    { scale: 4, grade: 'A', points: [100, 90, 85] },
    { scale: 3, grade: 'B', points: [80, 75, 70] },
    { scale: 2, grade: 'C', points: [65, 60, 55] },
    { scale: 1, grade: 'D', points: [50, 40, 30] }
];

function calculateGradeByAverage(averagePoint) {
    let gradeThresholds = [];
    gradeScales.forEach(scaleObj => {
      const maxPoint = Math.max(...scaleObj.points);
      const minPoint = Math.min(...scaleObj.points);
      gradeThresholds.push({
        grade: scaleObj.grade,
        min: minPoint,
        max: maxPoint,
        scale: scaleObj.scale,
      });
    });
    gradeThresholds.sort((a,b) => b.min - a.min);
    for(let i=0; i<gradeThresholds.length; i++) {
      if(averagePoint >= gradeThresholds[i].min) {
        return gradeThresholds[i].grade;
      }
    }
    return 'No Grade';
}

function CalculatePoin() {
    let totalValue = 0;
    var totalChecked = $('input[type="radio"][name^="pointassesment-"]:checked').length;

    if (totalChecked === 0) {
        alert('Silakan pilih nilai untuk semua pertanyaan sebelum menghitung.');
        return;
    }
    if (totalChecked < 13) {
        alert('Mohon lengkapi semua pertanyaan.');
        return;
    }

    $('input[type="radio"]:checked').each(function() {
        totalValue += parseInt($(this).val());
    });

    const averagePoint = totalValue / totalChecked;
    const grade = calculateGradeByAverage(averagePoint);

    $('#poin-assesment').val(totalValue);
    $('#mutu-assesment').val(grade);
    $('#avg-assesment').val(averagePoint);

}
</script>

<?php
    include ("includes/templates/alert.php");
?>