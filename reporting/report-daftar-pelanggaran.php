<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST["user-pelanggaran"]);
$idcat = mysqli_real_escape_string($conn, $_POST["cat-pelanggaran"]);
$idjen = mysqli_real_escape_string($conn, $_POST["jenis-pelanggaran"]);

if ($idcat == "ALL" && $idjen == "ALL") {
    $sql = "SELECT A.*, B.* FROM category_pelanggaran AS A
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg ORDER BY B.id_head_ctg_plg ASC";
}
elseif($idcat != "ALL" && $idjen == "ALL") {
    $sql = "SELECT A.*, B.* FROM category_pelanggaran AS A
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    WHERE B.id_head_ctg_plg = '$idcat' ORDER BY B.id_head_ctg_plg ASC";
}
elseif ($idcat != "ALL" && $idjen != "ALL") {
    $sql = "SELECT A.*, B.* FROM category_pelanggaran AS A
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    WHERE B.id_jns_plg = '$idjen' ORDER BY B.id_head_ctg_plg ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header;
        global $user;

        $this->SetFont('Arial','',10);
        
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"),0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',12);
        
        $this->Cell(190, 10, 'REPORT DAFTAR KATEGORI PELANGGARAN CCTV', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'NO',1,0,'C');
        $this->Cell(60 ,8,'KATEGORI PELANGGARAN',1,0,'C');
        $this->Cell(120 ,8,'JENIS PELANGGARAN',1,1,'C');
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
$pdf->SetTitle('Report Daftar Kategori Pelanggaran CCTV');
$pdf->SetSubject('Kategori Pelanggaran');
$pdf->SetKeywords('Pelanggaran CCTV');
$pdf->SetCreator('IMS');

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($idcat) && isset($idcat) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $category = $data["id_ctg_plg"].". ".$data["name_ctg_plg"];
            $jenis = $data["name_jns_plg"];

            $pdf->SetWidths(array(10, 60, 120));
            $pdf->Row(array($no++, $category, $jenis));

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
$pdf->Output("REPORT DAFTAR KATEGORI PELANGGARAN CCTV.pdf","I");

?>