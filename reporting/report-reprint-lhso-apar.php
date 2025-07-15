<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$id = mysqli_real_escape_string($conn, $_POST["lhso_apar"]);

$sql = "SELECT A.*, B.office_name, B.office_city, C.department_name, D.username, E.*, F.layout_name FROM head_so_apar AS A 
INNER JOIN office AS B ON A.office_head_so_apar = B.id_office
INNER JOIN department AS C ON A.dept_head_so_apar = C.id_department
INNER JOIN users AS D ON A.user_head_so_apar = D.nik
INNER JOIN so_apar AS E ON A.id_head_so_apar = E.id_head_so_apar
INNER JOIN layout_apar AS F ON E.posisi_so_apar = F.id_layout
WHERE A.id_head_so_apar = '$id'";

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header;

        /*output the result*/
        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Office',0,0);
        $this->Cell(48 ,5,': '.$header['office_head_so_apar']." - ".$header['office_name'],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'Tanggal SO',0,0);
        $this->Cell(48 ,5,': '.$header['date_head_so_apar'], 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'Checked',0,0);
        $this->Cell(48 ,5,': '.$header['username'], 0, 1);

        $this->Ln(5);

        $this->SetFont('Arial','B',14);

        $this->Cell(278, 10, 'CHECKLIST SO APAR', 0, 1, 'C');

        $this->SetFont('Arial','',10);

        $this->Cell(278, 5,'Nomor SO : '.$header['id_head_so_apar'], 0, 1, 'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,10,'No',1,0,'C');
        $this->Cell(62 ,10,'ID - Lokasi Apar',1,0,'C');
        $this->Cell(20 ,10,'Kapasitas',1,0,'C');
        $this->Cell(20 ,10,'Merk',1,0,'C');
        $this->Cell(20 ,10,'Jenis / Isi',1,0,'C');
        $this->Cell(20 ,10,'Expired',1,0,'C');
        $this->Cell(20 ,10,'Indikator',1,0,'C');
        $this->Cell(20 ,10,'Bracket',1,0,'C');
        $this->Cell(20 ,10,'Petunjuk',1,0,'C');
        $this->Cell(20 ,10,'Checklist',1,0,'C');
        $this->Cell(46 ,10,'Keterangan',1,1,'C');
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
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAuthor('Inventory Management System');
$pdf->SetTitle('Report Data LHSO Apar');
$pdf->SetSubject('LHSO Apar');
$pdf->SetKeywords('LHSOA');
$pdf->SetCreator('IMS');

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($id)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $desc = $data['posisi_so_apar']." - ".$data['layout_name'];
            $kap = isset($data['berat_so_apar']) ? $data['berat_so_apar'].' KG' : '-';
            $merk = isset($data['merk_so_apar']) ? $data['merk_so_apar'] : '-';
            $jenis = isset($data['jenis_so_apar']) ? $data['jenis_so_apar'] : '-';
            $exp = isset($data['expired_so_apar']) ? $data['expired_so_apar'] : '-';
            $indikator = $data["indikator_so_apar"] == "Y" ? "V" : "X";
            $bracket = $data["bracket_so_apar"] == "Y" ? "V" : "X";
            $label = $data["label_so_apar"] == "Y" ? "V" : "X";
            $check = $data["checklist_so_apar"] == "Y" ? "V" : "X";
            $ket = $data["ket_so_apar"];

            $pdf->SetWidths(array(10, 62, 20, 20, 20, 20, 20, 20, 20, 20, 46));
            $pdf->Row(array($no++, $desc, $kap, $merk, $jenis, $exp, $indikator, $bracket, $label, $check, $ket));

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

$pdf->Ln(5);

$pdf->SetFont('Arial','',10);

$pdf->Cell(214,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(150 ,8,'Mengetahui',1,0,'C');
$pdf->Cell(28 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'Diperiksa',1,0,'C');
$pdf->Cell(50 ,8,'Petugas SO',1,1,'C');

$pdf->Cell(50 ,28,'',1,0,'');
$pdf->Cell(50 ,28,'',1,0,'');
$pdf->Cell(50 ,28,'',1,0,'');
$pdf->Cell(28 ,28,'',0,0,'');
$pdf->Cell(50 ,28,'',1,0,'');
$pdf->Cell(50 ,28,'',1,1,'');

$pdf->Cell(50 ,8,'DCM / DDCM',1,0,'C');
$pdf->Cell(50 ,8,'SPV Adm',1,0,'C');
$pdf->Cell(50 ,8,'SPV Warehouse',1,0,'C');
$pdf->Cell(28 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'Support DC',1,0,'C');
$pdf->Cell(50 ,8,'Warehouse',1,1,'C');

// Nama file ketika di print
$pdf->Output("LHSO_CHEKLIST_APAR.pdf","I");

?>