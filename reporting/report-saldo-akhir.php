<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../vendor/fpdf/fpdf.php';

$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$barang = $_POST["barangcetak"];
$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$arrdata = implode(", ", $barang);

if ($arrdata == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.nama_satuan FROM masterstock AS A
    INNER JOIN office AS B ON A.ms_id_office = B.id_office
    INNER JOIN department AS C ON A.ms_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN satuan AS F ON D.id_satuan = F.id_satuan
    WHERE A.ms_id_office = '$office' AND A.ms_id_department = '$dept' ORDER BY D.NamaBarang ASC";
}
else {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.nama_satuan FROM masterstock AS A
    INNER JOIN office AS B ON A.ms_id_office = B.id_office
    INNER JOIN department AS C ON A.ms_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN satuan AS F ON D.id_satuan = F.id_satuan
    WHERE A.ms_id_office = '$office' AND A.ms_id_department = '$dept' AND A.pluid IN ($arrdata) ORDER BY D.NamaBarang ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        global $header;
        global $user;

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
        
        $this->Ln(5);
        
        $this->SetFont('Arial','B',14);
        
        $this->Cell(190, 10, 'LAPORAN SALDO AKHIR BARANG', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,10,'No',1,0,'C');
        $this->Cell(26 ,10,'Kode Barang',1,0,'C');
        $this->Cell(107 ,10,'Nama Barang',1,0,'C');
        $this->Cell(20 ,10,'Satuan',1,0,'C');
        $this->Cell(25 ,10,'Saldo Akhir',1,1,'C');
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
$pdf->SetTitle('Report Data Saldo Akhir Barang');
$pdf->SetSubject('Saldo Akhir Barang');
$pdf->SetKeywords('SALDO');
$pdf->SetCreator('IMS');

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($office) && isset($dept) && isset($barang) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {
        while($data = mysqli_fetch_array($query)){

            $kode = $data['pluid'];
            $desc = $data['NamaBarang']." ".$data['NamaJenis'];
            $satuan = $data['nama_satuan'];
            $saldo = $data['saldo_akhir'];

            $pdf->Cell(12 ,6 ,$no++,1,0,'C');
            $pdf->Cell(26 ,6 ,$kode,1,0,'C');
            $pdf->Cell(107 ,6 ,$desc,1,0,'C');
            $pdf->Cell(20 ,6 ,$satuan,1,0,'C');
            $pdf->Cell(25 ,6 ,$saldo,1,1,'C');

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
$pdf->Output("LAPORAN SALDO AKHIR BARANG-".".pdf","I");

?>