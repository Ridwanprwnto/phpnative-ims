<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../vendor/fpdf/fpdf.php';

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$barang = $_POST["barangcetak"];
$arrdata = implode(", ", $barang);

if ($arrdata == "ALL") {
    $sql = "SELECT masterstock.*, office.*, department.*, mastercategory.*, masterjenis.* FROM masterstock
    INNER JOIN office ON masterstock.ms_id_office = office.id_office
    INNER JOIN department ON masterstock.ms_id_department = department.id_department
    INNER JOIN mastercategory ON LEFT(masterstock.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(masterstock.pluid, 4) = masterjenis.IDJenis
    WHERE masterstock.ms_id_office = '$office' AND masterstock.ms_id_department = '$dept' ORDER BY masterstock.pluid ASC";
}
else {
    $sql = "SELECT masterstock.*, office.*, department.*, mastercategory.*, masterjenis.* FROM masterstock
    INNER JOIN office ON masterstock.ms_id_office = office.id_office
    INNER JOIN department ON masterstock.ms_id_department = department.id_department
    INNER JOIN mastercategory ON LEFT(masterstock.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(masterstock.pluid, 4) = masterjenis.IDJenis
    WHERE masterstock.ms_id_office = '$office' AND masterstock.ms_id_department = '$dept' AND masterstock.pluid IN ($arrdata) ORDER BY masterstock.pluid ASC";
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
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',12);
        
        $this->Cell(199, 10, 'LAPORAN FISIK VS AKTIVA BARANG', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,8,'No',1,0,'C');
        $this->Cell(102 ,8,'Nama Barang',1,0,'C');
        $this->Cell(25 ,8,'Fisik',1,0,'C');
        $this->Cell(25 ,8,'Akiva',1,0,'C');
        $this->Cell(25 ,8,'Selisih',1,1,'C');
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
$pdf->SetTitle('Report Data Fisik Aktiva Barang');
$pdf->SetSubject('Fisik Aktiva Barang');
$pdf->SetKeywords('FAB');
$pdf->SetCreator('IMS');

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0];
if (isset($office) && isset($dept) && isset($barang)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $plu = $data["pluid"];

            $data_asset = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(pluid) AS saldo_asset FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi NOT LIKE '$arrcond[5]'"));

            $desc = $data['pluid']." - ".$data['NamaBarang']." ".$data['NamaJenis'];

            $cellHeight = 6;

            $pdf->Cell(12 ,$cellHeight,$no++,1,0,'C');
            $pdf->Cell(102 ,$cellHeight,$desc,1,0,'C');
            $pdf->Cell(25 ,$cellHeight,$fisik = isset($data["saldo_akhir"]) ? $data["saldo_akhir"] : '-',1,0,'C');
            $pdf->Cell(25 ,$cellHeight,$asset = $data_asset["saldo_asset"],1,0,'C');
            $pdf->Cell(25 ,$cellHeight,$selisih = ($fisik - $asset),1,1,'C');
            // Query yang ingin ditampilkan yang berada di database

            $to_fisik = ($nol[0]+=$fisik);
            $to_aktiva = ($nol[1]+=$asset);
            $to_selisih = ($nol[2]+=$selisih);

        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(114 ,$cellHeight,'Total',1,0,'C');
        $pdf->Cell(25 ,$cellHeight,$to_fisik,1,0,'C');
        $pdf->Cell(25 ,$cellHeight,$to_aktiva,1,0,'C');
        $pdf->Cell(25 ,$cellHeight,$to_selisih,1,1,'C');

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
$pdf->Output("LAPORAN FISK VS AKTIVA BARANG.pdf","I");

?>