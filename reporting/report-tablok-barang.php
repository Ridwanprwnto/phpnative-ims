<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../vendor/fpdf/fpdf.php';

$from = mysqli_real_escape_string($conn, $_POST['startdate-tablok']);
$end = mysqli_real_escape_string($conn, $_POST['enddate-tablok']);
$user = mysqli_real_escape_string($conn, $_POST['user-tablok']);
$office = mysqli_real_escape_string($conn, $_POST['office-tablok']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-tablok']);

$offdep = $office.$dept;

$sql = "SELECT A.*, B.username AS req, C.username AS pic, D.id_office, D.office_name, E.department_name FROM st_dpd_head AS A
INNER JOIN users AS B ON A.req_st_dpd = B.nik
INNER JOIN users AS C ON A.pic_st_dpd = C.nik
INNER JOIN office AS D ON LEFT(A.offdep_st_dpd, 4) = D.id_office
INNER JOIN department AS E ON RIGHT(A.offdep_st_dpd, 4) = E.id_department
WHERE A.offdep_st_dpd = '$offdep' AND LEFT(A.date_st_dpd, 10) BETWEEN '$from' AND '$end'";
$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

// Ukuran kertas PDF
$pdf = new FPDF("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Tablok Barang');

$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'Office',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
$pdf->Cell(126 ,5,'',0,0);
$pdf->Cell(28 ,5,'Print Date',0,0);
$pdf->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
$pdf->Cell(28 ,5,'Department',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(126 ,5,'',0,0);
$pdf->Cell(28 ,5,'User Print',0,0);
$pdf->Cell(48 ,5,': '.$user, 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',14);

$pdf->Cell(278, 6, 'LAPORAN TABLOK BARANG', 0, 1, 'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','',10);
$pdf->Cell(278, 6, 'Periode : '.$from." - ".$end, 0, 1, 'C');

$pdf->Ln(8);

// st font yang ingin anda gunakan
$pdf->SetFont('Arial','B',9);

// queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
// Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
$pdf->Cell(10 ,8,'No',0,0,'C');
$pdf->Cell(95 ,8,'PLU - Nama Barang',0,0,'C');
$pdf->Cell(30 ,8,'Type Rak',0,0,'C');
$pdf->Cell(12 ,8,'Zona',0,0,'C');
$pdf->Cell(12 ,8,'Line',0,0,'C');
$pdf->Cell(12 ,8,'St',0,0,'C');
$pdf->Cell(12 ,8,'Rak',0,0,'C');
$pdf->Cell(12 ,8,'Shelf',0,0,'C');
$pdf->Cell(12 ,8,'Cell',0,0,'C');
$pdf->Cell(26 ,8,'IP',0,0,'C');
$pdf->Cell(12 ,8,'ID',0,0,'C');
$pdf->Cell(30 ,8,'Pemakaian DPD',0,1,'C');/*end of line*/

$pdf->Cell(275, 0, '', 1, 1, 'L');

$no = 1;

if (isset($from) && isset($end)) {
    
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $docno = $data["id_st_dpd"];

            $sql_detail = "SELECT A.*, B.*, C.*, D.* FROM st_dpd_detail AS A
            INNER JOIN type_plano AS B ON A.type_st_dpd_detail = B.id_type_plano 
            INNER JOIN zona_plano AS C ON A.zona_st_dpd_detail = C.id_zona_plano 
            INNER JOIN line_plano AS D ON A.line_st_dpd_detail = D.id_line_plano
            WHERE A.id_st_dpd_head = '$docno'";

            $query_detail = mysqli_query($conn, $sql_detail);

            $data_detail = mysqli_fetch_assoc(mysqli_query($conn, $sql_detail));
            $id_detail = $data_detail["id_st_dpd_head"];

            if(mysqli_num_rows($query_detail) > 0 ) {

                $pdf->SetFont('Arial','B',8);

                $pdf->Cell(275, 10, 'NO : '.$id_detail.'  |  TGL PENGAJUAN : '.$data['date_st_dpd'].'  |  USER PENGAJUAN : '.$data['req_st_dpd'].' - '.strtoupper($data['req']).'  |  USER APPROVE : '.$data['pic_st_dpd'].' - '.strtoupper($data['pic']), 0, 1, 'L');

                while($lihat = mysqli_fetch_array($query_detail)){

                    $desc = $lihat["plu_st_dpd_detail"]." - ".$lihat["nama_st_dpd_detail"];

                    $cellWidth = 95;
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

                    $pdf->SetFont('Arial','',8);
                    // Query yang ingin ditampilkan yang berada di database
                    $pdf->Cell(10 ,($line * $cellHeight),$no++,0,0,'C');

                    $xPos = $pdf->GetX(); //initial x (start of column position)
                    $yPos = $pdf->GetY();
                    $pdf->MultiCell($cellWidth, $cellHeight, $desc, 0);
                    $pdf->SetXY($xPos + $cellWidth , $yPos);

                    $pdf->Cell(30 ,($line * $cellHeight),$lihat["nm_type_plano"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["nm_zona_plano"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["nm_line_plano"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["station_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["rak_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["shelf_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["cell_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(26 ,($line * $cellHeight),$lihat["ip_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(12 ,($line * $cellHeight),$lihat["dpd_st_dpd_detail"],0,0,'C');
                    $pdf->Cell(30 ,($line * $cellHeight),$lihat["alokasi_st_dpd_detail"] == 'Y' ? 'YES' : 'NO',0,1,'C');/*end of line*/

                }

                $pdf->Cell(275, 0, '', 1, 1, 'L');

            }
            else {
                $msg = encrypt("datanotfound");
                header("location: ../error.php?alert=$msg");
                exit();
            }

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
$pdf->Output("LAPORAN-TABLOK-".$from."-".$end.".pdf","I");

?>