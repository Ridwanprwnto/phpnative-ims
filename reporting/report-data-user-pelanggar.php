<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$start = mysqli_real_escape_string($conn, $_POST["start-cetak"]);
$end = mysqli_real_escape_string($conn, $_POST["end-cetak"]);
$pelanggar = mysqli_real_escape_string($conn, $_POST["pelanggarcetak"]);

if ($pelanggar == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($pelanggar == "IDENTITAS TIDAK DIKETAHUI") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' AND L.username_plg_cctv = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($pelanggar == "BAGIAN LAIN") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' AND L.username_plg_cctv = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' AND LEFT(L.username_plg_cctv, 10) = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
    
$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $start, $end;

        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Office',0,0);
        $this->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
        
        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',12);
        
        $this->Cell(199, 10, 'REKAP DATA APPROVAL USER TEREKAM PELANGGARAN CCTV', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(9 ,8,'No',1,0,'C');
        $this->Cell(14 ,8,'NOP',1,0,'C');
        $this->Cell(28 ,8,'User Pelanggar',1,0,'C');
        $this->Cell(20 ,8,'Bagian',1,0,'C');
        $this->Cell(26 ,8,'Kategori',1,0,'C');
        $this->Cell(30 ,8,'Pelanggaran',1,0,'C');
        $this->Cell(20 ,8,'Tgl Kejadian',1,0,'C');
        $this->Cell(20 ,8,'Lokasi',1,0,'C');
        $this->Cell(24 ,8,'Sanksi',1,1,'C');
        /*end of line*/
       
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

/*A4 width : 219mm*/
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAuthor('Inventory Management System');
$pdf->SetTitle('Report Data Users Pelanggaran CCTV');
$pdf->SetSubject('User Pelanggaran CCTV');
$pdf->SetKeywords('CCTV');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0, 0, 0, 0, 0, 0];
$cellHeight = 10;

if (isset($office) && isset($dept) && isset($start) && isset($end)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $nop = $data['no_plg_cctv'];
            $user_p = $data['username_plg_cctv'];
            $div = $data['divisi_name'];
            $cat = $data['id_ctg_plg'].". ".$data['name_ctg_plg'];
            $plg = $data['name_jns_plg'];
            $lok = $data['penempatan_lay_cctv'];
            $tgl = $data['tgl_plg_cctv'];
            $snk = $data['name_fup_plg'];

            $pdf->SetWidths(array(9, 14, 28, 20, 26, 30, 20, 20, 24));
            $pdf->Row(array($no++, $nop, $user_p, $div, $cat, $plg, $tgl, $lok, $snk));

        }

    }
    else {
        $msg = encrypt("datanotfound");
        header("location: ../error.php?alert=$msg");
        exit();
    }
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

// Nama file ketika di print
$pdf->Output("LAPORAN DATA USERS PELANGGARAN CCTV.pdf","I");

?>