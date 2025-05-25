<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../vendor/fpdf/fpdf.php';

$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$noref = mysqli_real_escape_string($conn, $_POST['noref-cetak']);

if ($noref == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.username FROM barang_khusus AS A
    INNER JOIN office AS B ON LEFT(A.offdep_khusus, 4) = B.id_office
    INNER JOIN department AS C ON RIGHT(A.offdep_khusus, 4) = C.id_department
    INNER JOIN users AS D ON A.user_khusus = D.nik
    WHERE LEFT(A.offdep_khusus, 4) = '$office' AND RIGHT(A.offdep_khusus, 4) = '$dept'";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.username FROM barang_khusus AS A
    INNER JOIN office AS B ON LEFT(A.offdep_khusus, 4) = B.id_office
    INNER JOIN department AS C ON RIGHT(A.offdep_khusus, 4) = C.id_department
    INNER JOIN users AS D ON A.user_khusus = D.nik
    WHERE A.noref_khusus = '$noref' AND LEFT(A.offdep_khusus, 4) = '$office' AND RIGHT(A.offdep_khusus, 4) = '$dept'";
}

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        
        global $header;
        global $office;
        global $user;

        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Office',0,0);
        $this->Cell(48 ,5,': '.$office." - ".$header["office_name"],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header["department_name"],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);

        $this->Ln(5);

        $this->SetFont('Arial','B',12);

        $this->Cell(190, 6, 'LAPORAN REGISTER DATA PERALATAN INVENTARIS', 0, 1, 'C');

        $this->Ln(2);

        // st font yang ingin anda gunakan
        $this->SetFont('Arial','B',9);

        // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
        // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
        $this->Cell(12, 10, 'NO', 0, 0, 'C');
        $this->Cell(90, 10, 'NAMA BARANG', 0, 0, 'C');
        $this->Cell(30, 10, 'SN', 0, 0, 'C');
        $this->Cell(25, 10, 'NO AKTIVA', 0, 0, 'C');
        $this->Cell(34, 10, 'KETERANGAN', 0, 1, 'C');

        $this->Cell(190, 0, '', 1, 1, 'L');
       
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

// Ukuran kertas PDF
$pdf = new PDF("P","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAuthor('Inventory Management System');
$pdf->SetTitle('Laporan Register Peralatan Inventaris');
$pdf->SetSubject('Register Peralatan Inventaris');
$pdf->SetKeywords('RINV');
$pdf->SetCreator('IMS');

$no = 1;

if (isset($office) && isset($dept) && isset($noref)) {
    
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $id = $data["noref_khusus"];

            $sql_detail = "SELECT barang_assets.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM barang_assets
            INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
            INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
            WHERE barang_assets.noref_asset = '$id' AND barang_assets.ba_id_office = '$office' AND ba_id_department = '$dept'";

            $query_detail = mysqli_query($conn, $sql_detail);

            $data_detail = mysqli_fetch_assoc(mysqli_query($conn, $sql_detail));

            $id_noref = isset($data_detail["noref_asset"]) ? $data_detail["noref_asset"] : null;

            if(mysqli_num_rows($query_detail) > 0 ) {

                $pdf->SetFont('Arial','B',8);

                $pdf->Cell(275, 10, 'NOREF : '.$id_noref.'  |  TANGGAL : '.$data['date_khusus'].'  |  USER PROSES : '.$data['user_khusus'].' - '.strtoupper($data['username']), 0, 1, 'L');

                while($lihat = mysqli_fetch_array($query_detail)){

                    $ref = substr($lihat["noref_asset"], 0, 1);

                    if ($ref == "K") {
                        $status = "KHUSUS";
                    }
                    elseif ($ref == "P") {
                        $status = "PEMBELIAN";
                    }
                    elseif ($ref == "M") {
                        $status = "MUTASI";
                    }

                    $desc = $lihat['pluid'].' - '.$lihat['NamaBarang']." ".$lihat['NamaJenis']." ".$lihat['ba_merk']." ".$lihat['ba_tipe'];

                    $cellWidth = 90;
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
                    $pdf->Cell(12, ($line * $cellHeight), $no, 0, 0, 'C');

                    $xPos = $pdf->GetX(); //initial x (start of column position)
                    $yPos = $pdf->GetY();
                    $pdf->MultiCell($cellWidth, $cellHeight, $desc, 0);
                    $pdf->SetXY($xPos + $cellWidth , $yPos);

                    $pdf->Cell(30, ($line * $cellHeight), $lihat["sn_barang"],0, 0, 'C');
                    $pdf->Cell(25, ($line * $cellHeight), $lihat['no_at'],0, 0, 'C');
                    $pdf->Cell(34, ($line * $cellHeight), $status, 0, 1,'C');
                    
                    $no++;
                }

                $pdf->Cell(190, 0, '', 1, 1, 'L');
                
            }
            // else {
            //     $msg = encrypt("datanotfound");
            //     header("location: ../error.php?alert=$msg");
            //     exit();
            // }
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
$pdf->Output("REGISTER-BARANG-INVENTARIS".".pdf","I");

?>