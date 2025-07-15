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
$absen = mysqli_real_escape_string($conn, $_POST["absensi-cetak"]);
$start = mysqli_real_escape_string($conn, $_POST["start-cetak"]);
$end = mysqli_real_escape_string($conn, $_POST["end-cetak"]);
$div = $_POST["divcetak"];
$arrdata = implode(", ", $div);

if ($arrdata == "ALL") {

    $sql = "SELECT A.*, COUNT(A.cek_presensi) AS jml_presensi, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM presensi AS A
    INNER JOIN office AS B ON A.office_presensi = B.id_office
    INNER JOIN department AS C ON A.dept_presensi = C.id_department
    INNER JOIN divisi AS D ON A.div_presensi = D.divisi_name
    WHERE A.office_presensi = '$office' AND A.dept_presensi = '$dept' AND A.cek_presensi = '$absen' AND DATE(A.tgl_presensi) BETWEEN '$start' AND '$end' GROUP BY A.nik_presensi ORDER BY COUNT(A.cek_presensi) DESC";
    
}
else {

    $sql = "SELECT A.*, COUNT(A.cek_presensi) AS jml_presensi, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM presensi AS A
    INNER JOIN office AS B ON A.office_presensi = B.id_office
    INNER JOIN department AS C ON A.dept_presensi = C.id_department
    INNER JOIN divisi AS D ON A.div_presensi = D.divisi_name
    WHERE A.office_presensi = '$office' AND A.dept_presensi = '$dept' AND A.cek_presensi = '$absen' AND DATE(A.tgl_presensi) BETWEEN '$start' AND '$end' AND A.div_presensi IN ($arrdata) GROUP BY A.nik_presensi ORDER BY COUNT(A.cek_presensi) DESC";
    
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc(mysqli_query($conn, $sql));

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $start, $end, $absen;

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
        
        $this->Cell(199, 10, 'SUMMARY DATA LAPORAN ABSENSI PERBAGIAN', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');
        $this->Cell(190, 5, 'Keterangan : '.ucwords(strtolower($absen)), 0, 1, 'C');

        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,10,'No',1,0,'C');
        $this->Cell(40 ,10,'Bagian',1,0,'C');
        $this->Cell(120 ,10,'Karyawan',1,0,'C');
        $this->Cell(18 ,10,'Jumlah',1,1,'C');
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
$pdf->SetTitle('Report Data Summary Absensi Per Bagian');
$pdf->SetSubject('Absensi Perbagian');
$pdf->SetKeywords('Absensi');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && isset($dept) && isset($start) && isset($end) && isset($div) && isset($absen)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $div_detail = $data["div_presensi"];
            $users = $data["nik_presensi"]." - ".$data["user_presensi"];
            $tot = $data["jml_presensi"];

            $pdf->SetWidths(array(12, 40, 120, 18));
            $pdf->Row(array($no++, $div_detail, $users, $tot));
            
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
$pdf->Output("LAPORAN SUMMARY ABSENSI PER BAGIAN.pdf","I");

?>