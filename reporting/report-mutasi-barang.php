<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$office = mysqli_real_escape_string($conn, $_POST["office"]);
$dept = mysqli_real_escape_string($conn, $_POST["department"]);
$awal = mysqli_real_escape_string($conn, $_POST["tglawal"]);
$akhir = mysqli_real_escape_string($conn, $_POST["tglakhir"]);
$pluid = mysqli_real_escape_string($conn, $_POST["pluid"]);
$user = mysqli_real_escape_string($conn, $_POST["user"]);

$sql = "SELECT A.*, B.*, C.*, D.*, E.* FROM masterstock AS A
    INNER JOIN office AS B ON A.ms_id_office = B.id_office
    INNER JOIN department AS C ON A.ms_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    WHERE A.ms_id_office = '$office' AND A.ms_id_department = '$dept' AND A.pluid = '$pluid'";

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$sql_saldo = "SELECT qty_awal_btb_dpd, qty_akhir_btb_dpd FROM btb_dpd WHERE office_btb_dpd = '$office' AND dept_btb_dpd = '$dept' AND pluid_btb_dpd = '$pluid' AND tgl_btb_dpd BETWEEN '$awal' AND '$akhir'";
$query_saldo = mysqli_query($conn, $sql_saldo);
$data_saldo = mysqli_fetch_assoc($query_saldo);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $awal, $akhir;

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

        $this->Ln(2);

        $this->SetFont('Arial','B',12);

        $this->Cell(190, 10, 'LAPORAN KARTU STOCK MUTASI BARANG', 0, 1, 'C');

        $this->SetFont('Arial','',10);
        $this->Cell(190, 6, 'Periode : '.$awal." - ".$akhir, 0, 1, 'C');

        // $pdf->Cell(190, 5,'Bulan : '.$header['nama_bulan']." ".$header['tahun_periode'], 0, 1, 'C');

        $this->Ln(2);

        $this->SetFont('Arial','',10);

        $this->Cell(24 ,6,'PLU - DESC :',0,0,'L');
        $this->Cell(166 ,6,$header["pluid"]." - ".$header["NamaBarang"]." ".$header["NamaJenis"],0,1,'L');

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,10,'No',0,0,'C');
        $this->Cell(18 ,10,'Tanggal',0,0,'C');
        $this->Cell(20 ,10,'Docno',0,0,'C');
        $this->Cell(22 ,10,'Transaksi',0,0,'C');
        $this->Cell(16 ,10,'Qty',0,0,'C');
        $this->Cell(16 ,10,'Saldo',0,0,'C');
        $this->Cell(88 ,10,'Keterangan',0,1,'C');
        /*end of line*/
        
        $this->Line(10, 47, 200, 47);
        $this->Line(10, 55, 200, 55);
       
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

$pdf->SetTitle('Laporan Kartu Stock Mutasi Barang');

$pdf->SetFont('Arial','B',8);

$pdf->Cell(28 ,8,'Saldo Awal',0,0,'L');
$pdf->Cell(20 ,8,'',0,0,'C');
$pdf->Cell(22 ,8,'',0,0,'C');
$pdf->Cell(16 ,8,'',0,0,'C');
$pdf->Cell(16 ,8,$saldo = isset($data_saldo["qty_awal_btb_dpd"]) ? $data_saldo["qty_awal_btb_dpd"] : 0,0,0,'C');
$pdf->Cell(88 ,8,'',0,1,'C');

$pdf->Line(10, 65, 200, 65);

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($pluid) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $sql_detail = "SELECT A.*, B.*, C.*, D.*, E.*, F.username AS pic, G.username AS penerima FROM btb_dpd AS A
            INNER JOIN office AS B ON A.office_btb_dpd = B.id_office
            INNER JOIN department AS C ON A.dept_btb_dpd = C.id_department
            INNER JOIN mastercategory AS D ON LEFT(A.pluid_btb_dpd, 6) = D.IDBarang
            INNER JOIN masterjenis AS E ON RIGHT(A.pluid_btb_dpd, 4) = E.IDJenis
            LEFT JOIN users AS F ON A.pic_btb_dpd = F.nik
            LEFT JOIN users AS G ON A.penerima_btb_dpd = G.nik
            WHERE A.office_btb_dpd = '$office' AND A.dept_btb_dpd = '$dept' AND A.pluid_btb_dpd = '$pluid' AND A.tgl_btb_dpd BETWEEN '$awal' AND '$akhir'";

            $query_detail = mysqli_query($conn, $sql_detail);

            if(mysqli_num_rows($query_detail) > 0 ) {

                while($data_detail = mysqli_fetch_assoc($query_detail)){

                    $saldo_awal = isset($data_detail["qty_awal_btb_dpd"]) ? $data_detail["qty_awal_btb_dpd"] : 0;

                    $ket = $data_detail["ket_btb_dpd"];

                    $docno = substr($data_detail["no_btb_dpd"], 1);
                    $tgl_mutasi = $data_detail["tgl_btb_dpd"];
                    $code = substr($data_detail["no_btb_dpd"], 0, 1);
                    $qty = isset($data_detail["qty_akhir_btb_dpd"]) ? $data_detail["qty_akhir_btb_dpd"] : 0;
                    $hitung = $data_detail["hitung_btb_dpd"];

                    if($code == 'I') {
                        $desc_code = 'Penerimaan';
                        $saldo_akhir = ($saldo_awal += $qty);
                    }
                    elseif($code == 'O') {
                        $desc_code = 'Pengeluaran';
                        $saldo_akhir = ($saldo_awal -= $qty);
                    }
                    elseif($code == 'A') {
                        $desc_code = 'Penyesuaian';
                        if($hitung == '+') {
                            $saldo_akhir = ($saldo_awal += $qty);
                        }
                        elseif($hitung == '-') {
                            $saldo_akhir = ($saldo_awal -= $qty);
                        }
                    }


                    $cellWidth = 88;
                    $cellHeight = 8;

                    if ($pdf->GetStringWidth($ket) < $cellWidth) {
                        $line = 1;
                    }
                    else {
                        # code...
                        $textLenght = strlen($ket);
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
                                $tempString = substr($ket,$startChar,$maxChar);
                            }
                            $startChar = $startChar+$maxChar;
                            array_push($textArray, $tempString);
                            $maxChar = 0;
                            $tempString = '';
                        }
                        $line = count($textArray);
                    }
                    
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(190, 8, 'PIC : '.$data_detail["pic_btb_dpd"]." - ".$data_detail["pic"].'  |  Penerima : '.$data_detail['penerima_btb_dpd']." - ".$data_detail["penerima"], 0, 1, 'L');

                    $pdf->SetFont('Arial','',8);
                    $pdf->Cell(10 ,($line * $cellHeight),$no++,0,0,'C');
                    $pdf->Cell(18 ,($line * $cellHeight),$tgl_mutasi,0,0,'C');
                    $pdf->Cell(20 ,($line * $cellHeight),$docno,0,0,'C');
                    $pdf->Cell(22 ,($line * $cellHeight),$desc_code,0,0,'C');
                    $pdf->Cell(16 ,($line * $cellHeight),$qty,0,0,'C');
                    $pdf->Cell(16 ,($line * $cellHeight),$saldo_akhir,0,0,'C');
                    
                    $xPos = $pdf->GetX(); //initial x (start of column position)
                    $yPos = $pdf->GetY();
                    $pdf->MultiCell($cellWidth, $cellHeight, $ket, 0);

                    $pdf->Cell(190, 0, '', 1, 1, 'L');

                }
        
            }
            // else {
            //     $msg = encrypt("datanotfound");
            //     header("location: ../error.php?alert=$msg");
            //     exit();
            // }

        }

        $pdf->SetFont('Arial','B',8);

        $pdf->Cell(28 ,8,'Saldo Akhir',0,0,'L');
        $pdf->Cell(20 ,8,'',0,0,'C');
        $pdf->Cell(22 ,8,'',0,0,'C');
        $pdf->Cell(16 ,8,'',0,0,'C');
        $pdf->Cell(16 ,8,isset($saldo_awal) ? $saldo_awal : $header["saldo_akhir"],0,0,'C');
        $pdf->Cell(88 ,8,'',0,1,'C');
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
$pdf->Output("LAPORAN KARTU STOCK MUTASI BARANG-".$awal."-".$akhir."-".$pluid.".pdf","I");

?>