<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$username = $_SESSION["user_name"];

if (isset($_SESSION['PRINTKKSONA'])){
    $_POST = $_SESSION['PRINTKKSONA'];
    unset($_SESSION['PRINTKKSONA']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["nomor"];

if(isset($_GET["nomor"])) {
    if($_GET["nomor"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.office_name, B.office_city, C.department_name, D.username, E.*, F.NamaBarang, G.NamaJenis FROM head_stock_opname AS A 
            INNER JOIN office AS B ON A.office_so = B.id_office
            INNER JOIN department AS C ON A.dept_so = C.id_department
            INNER JOIN users AS D ON A.user_so = D.nik
            INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head
            INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
            WHERE A.no_so = '$decid'";
            $query_h = mysqli_query($conn, $sql);
            $header = mysqli_fetch_assoc($query_h);
            if(!$header || empty($header)) {
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
    }
    else {
        $msg = encrypt("print-error");
        header("location: ../error.php?alert=$msg");
        exit();
    }
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header;
        global $username;
        
        /*output the result*/

        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header['office_so']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Tanggal SO',0,0);
        $this->Cell(48 ,5,': '.$header['tgl_so'], 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Petugas',0,0);
        $this->Cell(48 ,5,': '.$header['username'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$username, 0, 1);

        $this->Ln(2);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',14);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(190 ,10,'KERTAS KERJA STOCK OPNAME NON AKTIVA',0,0, 'C');

        $this->SetFont('Arial','B',10);

        $this->Ln(8);

        $this->Cell(190 ,10,'NO SO : '.$header['no_so'],0,1,'C');

        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'No',1,0,'C');
        $this->Cell(70 ,8,'Nama Barang',1,0,'C');
        $this->Cell(25 ,8,'Saldo Awal',1,0,'C');
        $this->Cell(25 ,8,'SO Fisik',1,0,'C');
        $this->Cell(60 ,8,'Keterangan',1,1,'C');
       
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
$pdf->SetTitle('Report Kertas Kerja SO Barang Non AKtiva');
$pdf->SetSubject('Report KKSO Non Aktiva');
$pdf->SetKeywords('KKSO');
$pdf->SetCreator('IMS');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data["pluid_so"]." - ".$data['NamaBarang']." ".$data['NamaJenis'];
        $saldo = $data["saldo_so"];
        $fisik = '';
        $ket = '';

        $pdf->SetWidths(array(10, 70, 25, 25, 60));
        $pdf->Row(array($no++, $desc, $saldo, $fisik, $ket));

    }

}

$pdf->Output("KKSO-".$header['no_so']."-".date("d-m-Y").".pdf","I");

?>