<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTSJM'])){
    $_POST = $_SESSION['PRINTSJM'];
    unset($_SESSION['PRINTSJM']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["sjm"];

if(isset($_GET["sjm"])) {
    if($_GET["sjm"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.username, F.*, G.*, H.office_address AS addressofficeto, H.id_office AS idofficeto, H.office_name AS officetoname, I.department_name AS deptto FROM mutasi AS A 
            INNER JOIN detail_mutasi AS B ON A.no_mutasi = B.head_no_mutasi
            INNER JOIN office AS C ON LEFT(A.asal_mutasi, 4) = C.id_office
            INNER JOIN department AS D ON RIGHT(A.asal_mutasi, 4) = D.id_department
            INNER JOIN users AS E ON A.user_mutasi = E.nik
            INNER JOIN mastercategory AS F ON LEFT(B.pluid_mutasi, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(B.pluid_mutasi, 4) = G.IDJenis
            INNER JOIN office AS H ON LEFT(A.tujuan_mutasi, 4) = H.id_office
            INNER JOIN department AS I ON RIGHT(A.tujuan_mutasi, 4) = I.id_department
            WHERE no_mutasi = '$decid'";
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

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Report Mutasi Aset Peralatan');

/*output the result*/

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);

$pdf->SetFont('Arial','',9);

$pdf->Cell(28 ,5,'Proses Date',0,0);
$pdf->Cell(48 ,5,': '.$header["tgl_mutasi"],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Nomor',0,0);
$pdf->Cell(48 ,5,': '.substr($header['no_mutasi'], 1, 5),0,1);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'SURAT JALAN MUTASI BARANG',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Line(10, 26, 200, 26);

$pdf->Ln(4);

$pdf->SetFont('Arial','',9);

$pdf->Cell(28 ,5,'From',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".strtoupper($header['office_name']),0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'To',0,0);
$pdf->Cell(48 ,5,': '.$header['idofficeto']." - ".strtoupper($header['officetoname']), 0, 1);

$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,': DEPT. '.strtoupper($header['department_name']),0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,': DEPT. '.strtoupper($header['deptto']), 0, 1);

// date("d-m-Y H:i:s")
$pdf->Cell(28 ,5,'Alamat',0,0);

$xPos = $pdf->GetX(); //initial x (start of column position)
$yPos = $pdf->GetY();
$pdf->MultiCell(48 ,5,': '.$header['office_address'],0,1);

$pdf->SetXY($xPos + 48 , $yPos);

$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Alamat',0,0);
$pdf->MultiCell(48 ,5,': '.$header['addressofficeto'], 0, 1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',9);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(100 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(54 ,8,'Serial Number',1,0,'C');
$pdf->Cell(26 ,8,'No Aktiva',1,1,'C');
/*Heading Of the table end*/
$pdf->SetFont('Arial','',9);

$no = 1;
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['pluid_mutasi']." - ".$data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk_mutasi']." ".$data['tipe_mutasi'];
        $sn = $data['sn_mutasi'];
        $at = $data['at_mutasi'];
        
        $pdf->SetWidths(array(10, 100, 54, 26));
        $pdf->Row(array($no++, $desc, $sn, $at));
                
    }

}

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);

$pdf->Cell(28 ,5,'Keterangan',0,0);
$pdf->Cell(48 ,5,':',0,0);
$pdf->Cell(114 ,5,'',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','',9);

$xPos = $pdf->GetX();
$yPos = $pdf->GetY();

$pdf->MultiCell(190,8,$header['ket_mutasi'],0,1);

// $pdf->SetXY($xPos + $cellWidth , $yPos);

$pdf->SetFont('Arial','',9);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(2);

$pdf->Cell(55 ,8,'Penerima',1,0,'C');
$pdf->Cell(80 ,8,'Menyetujui',1,0,'C');
$pdf->Cell(55 ,8,'Pembuat',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(80 ,28,'',1,0,'');
$pdf->Cell(55 ,28,'',1,1,'');
$pdf->Cell(55 ,8,'',1,0,'C');
$pdf->Cell(40 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(40 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(55 ,8,strtoupper($header['username']),1,1,'C');


$pdf->Output("SJ-MUTASI-".$header['no_mutasi']."-".date("d-m-Y").".pdf","I");

?>
       