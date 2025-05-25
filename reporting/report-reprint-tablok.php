<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$id = mysqli_real_escape_string($conn, $_POST["tbno"]);

$sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.nik, F.username FROM st_dpd_head AS A
INNER JOIN st_dpd_detail AS B ON A.id_st_dpd = B.id_st_dpd_head
INNER JOIN type_plano AS C ON B.type_st_dpd_detail = C.id_type_plano 
INNER JOIN zona_plano AS D ON B.zona_st_dpd_detail = D.id_zona_plano 
INNER JOIN line_plano AS E ON B.line_st_dpd_detail = E.id_line_plano
INNER JOIN users AS F ON A.req_st_dpd = F.nik
WHERE A.id_st_dpd = '$id'";
$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Form Pengajuan Tablok Barang');

/*output the result*/

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'FORM PENGAJUAN TABLOK ITEM BARU',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(189 ,1,'Reprint',0,1, 'C');

$pdf->Line(10, 24, 200, 24);

$pdf->Ln(8);

$pdf->SetFont('Arial','',9);

$pdf->Cell(28 ,5,'No',0,0);
$pdf->Cell(48 ,5,': '.$header['id_st_dpd'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Checked',0,0);
$pdf->Cell(48 ,5,': ', 0, 1);

$pdf->Cell(28 ,5,'Tanggal',0,0);
$pdf->Cell(48 ,5,': '.$header['date_st_dpd'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Validasi',0,0);
$pdf->Cell(48 ,5,': '.$header['req_st_dpd']." - ".strtoupper($header['username']), 0, 1);

$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,'',0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Adm Tablok',0,0);
$pdf->Cell(48 ,5,': ', 0, 1);

$pdf->Ln(4);

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(8 ,8,'No',1,0,'C');
$pdf->Cell(50 ,8,'PLU - Nama Barang',1,0,'C');
$pdf->Cell(28 ,8,'Type Rak',1,0,'C');
$pdf->Cell(10 ,8,'Zona',1,0,'C');
$pdf->Cell(10 ,8,'Line',1,0,'C');
$pdf->Cell(10 ,8,'St',1,0,'C');
$pdf->Cell(10 ,8,'Rak',1,0,'C');
$pdf->Cell(10 ,8,'Shelf',1,0,'C');
$pdf->Cell(10 ,8,'Cell',1,0,'C');
$pdf->Cell(20 ,8,'IP',1,0,'C');
$pdf->Cell(10 ,8,'ID',1,0,'C');
$pdf->Cell(15 ,8,'Use DPD',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($id) && !empty($id)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        $total = 0;
        while($data = mysqli_fetch_assoc($query_d)){

            $desc = $data["plu_st_dpd_detail"]." - ".$data["nama_st_dpd_detail"];

            $cellWidth = 50;
            $cellHeight = 8;

            if ($pdf->GetStringWidth($desc) < $cellWidth) {
                $line = 1;
            }
            else {
                # code...
                $textLenght = strlen($desc);
                $errMargin = 10;
                $startChar = 0;
                $maxChar = 0;
                $textArray = array();
                $tempString = "";

                while ($startChar < $textLenght) {
                    # code...
                    while ($pdf->GetStringWidth($tempString) < ($cellWidth-$errMargin) && ($startChar+$maxChar) < $textLenght) {
                        # code...
                        $maxChar++;
                        $tempString = substr($desc,$startChar,$maxChar);
                    }
                    $startChar = $startChar+$maxChar;
                    array_push($textArray, $tempString);
                    $maxChar = 0;
                    $tempString = '';
                }
                $line = count($textArray);
            }

            $pdf->Cell(8 ,($line * $cellHeight),$no++,1,0,'C');

            $xPos = $pdf->GetX(); //initial x (start of column position)
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, $desc, 1);
            $pdf->SetXY($xPos + $cellWidth , $yPos);

            $pdf->Cell(28 ,($line * $cellHeight),$data["nm_type_plano"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["nm_zona_plano"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["nm_line_plano"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["station_st_dpd_detail"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["rak_st_dpd_detail"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["shelf_st_dpd_detail"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["cell_st_dpd_detail"],1,0,'C');
            $pdf->Cell(20 ,($line * $cellHeight),$data["ip_st_dpd_detail"],1,0,'C');
            $pdf->Cell(10 ,($line * $cellHeight),$data["dpd_st_dpd_detail"],1,0,'C');
            $pdf->Cell(15 ,($line * $cellHeight),$data["alokasi_st_dpd_detail"] == 'Y' ? 'YES' : 'NO',1,1,'C');/*end of line*/
            
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

$pdf->SetFont('Arial','',9);

$pdf->Ln(8);

$pdf->Cell(55 ,8,'Mengetahui',1,0,'C');
$pdf->Cell(135 ,8,'Menyetujui',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,1,'');
$pdf->Cell(55 ,8,'DCM/DDCM',1,0,'C');
$pdf->Cell(45 ,8,'SPV Admin',1,0,'C');
$pdf->Cell(45 ,8,'SPV Warehouse',1,0,'C');
$pdf->Cell(45 ,8,'SPV Receiving',1,1,'C');


$pdf->Output("FORM-RP-TABLOK-".$header['id_st_dpd']."-".date("d-m-Y").".pdf","I");

?>
       