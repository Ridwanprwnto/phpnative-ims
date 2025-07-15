<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTPP'])){
    $_POST = $_SESSION['PRINTPP'];
    unset($_SESSION['PRINTPP']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["ppid"];

if(isset($_GET["ppid"])) {
    if($_GET["ppid"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.*, H.id_office AS officeto, H.office_name AS office_nameto, I.department_name AS deptto, J.nama_satuan FROM pembelian AS A 
            INNER JOIN detail_pembelian AS B ON A.noref = B.noref
            INNER JOIN office AS C ON A.id_office = C.id_office
            INNER JOIN department AS D ON A.id_department = D.id_department
            INNER JOIN users AS E ON A.user = E.nik
            INNER JOIN mastercategory AS F ON LEFT(B.plu_id, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(B.plu_id, 4) = G.IDJenis
            INNER JOIN office AS H ON A.office_to = H.id_office
            INNER JOIN department AS I ON A.department_to = I.id_department
            INNER JOIN satuan AS J ON F.id_satuan = J.id_satuan
            WHERE ppid = '$decid'";
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

$pdf->SetTitle('Form Pengajuan Pembelian');

/*output the result*/

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$cellWidth = 121;  //define cell width
$cellHeight = 5;    //define cell height

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',16);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'FORM PERMOHONAN PEMBELIAN',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Line(10, 24, 200, 24);

$pdf->Ln(8);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(190 ,5,'NO PP : '.$header['ppid'],0,1,'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'From',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tgl Proses',0,0);
$pdf->Cell(48 ,5,': '.$header['proses_date'], 0, 1);

$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,': Dept. '.$header['department_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tgl Pengajuan',0,0);
$pdf->Cell(48 ,5,': '.$header['tgl_pengajuan'], 0, 1);

$pdf->Cell(28 ,5,'To',0,0);
$pdf->Cell(48 ,5,': '.$header['officeto']." - ".$header['office_nameto'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Pembuat',0,0);
$pdf->Cell(48 ,5,': '.$header['nik'], 0, 1);

$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,': Dept. '.$header['deptto'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Nomor',0,0);
$pdf->Cell(48 ,5,': '.$header['noref'], 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(14 ,8,'PLU',1,0,'C');
$pdf->Cell(71 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(17 ,8,'Satuan',1,0,'C');
$pdf->Cell(12 ,8,'Qty',1,0,'C');
$pdf->Cell(33 ,8,'Estimasi Harga',1,0,'C');
$pdf->Cell(33 ,8,'Subtotal',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $kdbarang = substr($data['plu_id'], 1, 5);
        $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk']." ".$data['tipe'];
        $satuan = $data['nama_satuan'];
        $qty = $data['qty'];
        $harga = 'Rp. '.number_format($cost = $data["HargaJenis"],2);
        $sbt = 'Rp. '.number_format($subtotal = $cost*$qty,2);
        
        $pdf->SetWidths(array(10, 14, 71, 17, 12, 33, 33));
        $pdf->Row(array($no++, $kdbarang, $desc, $satuan, $qty, $harga, $sbt));

        $total += $subtotal;

    }

}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(157 ,8,'Total :',1,0,'C');
$pdf->Cell(33 ,8,'Rp. '.number_format($total, 2),1,1,'C');
// $pdf->Cell($cellWidth ,0,'',1,0,'C');
// $pdf->Cell(69 ,0,'',1,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(28 ,5,'Keperluan',0,0);
$pdf->Cell(48 ,5,':',0,0);
$pdf->Cell(114 ,5,'',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',9);

$xPos=$pdf->GetX();
$yPos=$pdf->GetY();
$pdf->MultiCell($cellWidth,$cellHeight,$header['keperluan'],0,1);

// $pdf->SetXY($xPos + $cellWidth , $yPos);

$pdf->SetFont('Arial','',10);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(110 ,8,'Menyetujui',1,0,'C');
$pdf->Cell(25 ,8,'',0,0,'C');
$pdf->Cell(55 ,8,'Pemohon',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(25 ,28,'',0,0,'');
$pdf->Cell(55 ,28,'',1,1,'');
$pdf->Cell(55 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(55 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(25 ,8,'',0,0,'C');
$pdf->Cell(55 ,8,'',1,1,'C');


$pdf->Output("FORM-PP-".$header['ppid']."-".date("d-m-Y").".pdf","I");

?>
       