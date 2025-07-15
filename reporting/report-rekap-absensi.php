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
$employe = mysqli_real_escape_string($conn, $_POST["employe-cetak"]);
$start = mysqli_real_escape_string($conn, $_POST["start-cetak"]);
$end = mysqli_real_escape_string($conn, $_POST["end-cetak"]);

$nik = substr($employe, 0, 10);

$absen = $_POST["absensicetak"];
$arrdata = implode(", ", $absen);

if ($arrdata == "ALL") {

    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM presensi AS A
    INNER JOIN office AS B ON A.office_presensi = B.id_office
    INNER JOIN department AS C ON A.dept_presensi = C.id_department
    INNER JOIN divisi AS D ON A.div_presensi = D.divisi_name
    WHERE A.office_presensi = '$office' AND A.dept_presensi = '$dept' AND A.nik_presensi = '$nik' AND DATE(A.tgl_presensi) BETWEEN '$start' AND '$end' ORDER BY A.tgl_presensi ASC";
    
}
else {

    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM presensi AS A
    INNER JOIN office AS B ON A.office_presensi = B.id_office
    INNER JOIN department AS C ON A.dept_presensi = C.id_department
    INNER JOIN divisi AS D ON A.div_presensi = D.divisi_name
    WHERE A.office_presensi = '$office' AND A.dept_presensi = '$dept' AND A.nik_presensi = '$nik' AND DATE(A.tgl_presensi) BETWEEN '$start' AND '$end' AND A.cek_presensi IN ($arrdata) ORDER BY A.tgl_presensi ASC";
    
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc(mysqli_query($conn, $sql));

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $start, $end, $employe;

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
        
        $this->Cell(199, 10, 'REPORT REKAPITULASI ABSENSI KARYAWAN', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

        $this->Ln(3);

        $this->Cell(190, 5, 'Karyawan : '.$employe, 0, 1, '');

        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,10,'No',1,0,'C');
        $this->Cell(30 ,10,'Day',1,0,'C');
        $this->Cell(30 ,10,'Date',1,0,'C');
        $this->Cell(40 ,10,'Absensi',1,0,'C');
        $this->Cell(78 ,10,'Keterangan',1,1,'C');
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
$pdf->SetTitle('Report Data Rekapitulasi Absensi');
$pdf->SetSubject('Rekapitulasi Absensi');
$pdf->SetKeywords('Absensi');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && isset($dept) && isset($start) && isset($end) && isset($employe) && isset($absen)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $day = strtoupper(date("l", strtotime($data["tgl_presensi"])));
            $tanggal = $data["tgl_presensi"];
            $ket_absen = $data["cek_presensi"];
            $ket = $data["ket_presensi"] == "" ? "-" : $data["ket_presensi"];

            $pdf->SetWidths(array(12, 30, 30, 40, 78));
            $pdf->Row(array($no++, $day, $tanggal, $ket_absen, $ket));
            
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
$pdf->Output("REPORT REKAPITULASI ABSENSI KARYAWAN ".$employe.".pdf","I");

?>