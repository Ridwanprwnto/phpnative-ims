<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$username = $_SESSION["user_name"];

if (isset($_SESSION['KKSOAPAR'])){
    $_POST = $_SESSION['KKSOAPAR'];
    unset($_SESSION['KKSOAPAR']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["id"];

if(isset($_GET["id"])) {
    if($_GET["id"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.office_name, B.office_city, C.department_name, D.username, E.*, F.layout_name FROM head_so_apar AS A 
            INNER JOIN office AS B ON A.office_head_so_apar = B.id_office
            INNER JOIN department AS C ON A.dept_head_so_apar = C.id_department
            INNER JOIN users AS D ON A.user_head_so_apar = D.nik
            INNER JOIN so_apar AS E ON A.id_head_so_apar = E.id_head_so_apar
            INNER JOIN layout_apar AS F ON E.posisi_so_apar = F.id_layout
            WHERE A.id_head_so_apar = '$decid'";
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
        
        global $header, $username;

        /*output the result*/
        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header['office_head_so_apar']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Tanggal SO',0,0);
        $this->Cell(48 ,5,': '.$header['date_head_so_apar'], 0, 1);

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

        $this->Ln(4);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',14);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(63 ,10,'',0,0);
        $this->Cell(63 ,10,'KERTAS KERJA STOCK OPNAME APAR',0,0, 'C');
        $this->Cell(63 ,10,'',0,1);

        $this->SetFont('Arial','B',10);

        $this->Cell(190 ,5,'NO SO : '.$header['id_head_so_apar'],0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $this->Cell(10 ,12,'No',1,0,'C');
        $this->Cell(60 ,12,'ID - Lokasi Apar',1,0,'C');
        $this->Cell(30 ,12,'Masa Expired',1,0,'C');
        $this->Cell(60 ,12,'Keterangan',1,0,'C');
        $this->Cell(30 ,6,'Fisik',1,1,'C');
        $this->Cell(160 ,0,'',0,0,'C');
        $this->Cell(15 ,6,'Y',1,0,'C');
        $this->Cell(15 ,6,'N',1,1,'C');
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

$pdf->SetTitle('Report Kertas Kerja SO Apar');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['posisi_so_apar']." - ".$data['layout_name'];
        $expdate = strtotime($data["expired_so_apar"]);
        $datenow = time();
        $diff  = $expdate - $datenow;
        $sisa = floor($diff / (60 * 60 * 24)) . ' hari';

        $pdf->SetWidths(array(10, 60, 30, 60, 15, 15));
        $pdf->Row(array($no++, $desc, '', '', '', ''));

    }

}

$pdf->Ln(5);

$pdf->SetFont('Arial','',10);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].' '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(80 ,8,'Mengetahui',1,0,'C');
$pdf->Cell(60 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'Petugas SO',1,1,'C');

$pdf->Cell(40 ,28,'',1,0,'');
$pdf->Cell(40 ,28,'',1,0,'');
$pdf->Cell(60 ,28,'',0,0,'');
$pdf->Cell(50 ,28,'',1,1,'');

$pdf->Cell(40 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(40 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(60 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'',1,1,'C');

$pdf->Output("KKSO-".$header['id_head_so_apar']."-".date("d-m-Y").".pdf","I");

?>