<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$sj = mysqli_real_escape_string($conn, $_POST["sj-service"]);

$sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.*, H.office_name AS officeto, I.department_name AS deptto FROM surat_jalan AS A 
INNER JOIN detail_surat_jalan AS B ON A.no_sj = B.head_no_sj
INNER JOIN office AS C ON LEFT(A.asal_sj, 4) = C.id_office
INNER JOIN department AS D ON RIGHT(A.asal_sj, 4) = D.id_department
LEFT JOIN users AS E ON A.user_sj = E.nik
INNER JOIN mastercategory AS F ON LEFT(B.pluid_sj, 6) = F.IDBarang
INNER JOIN masterjenis AS G ON RIGHT(B.pluid_sj, 4) = G.IDJenis
INNER JOIN office AS H ON LEFT(A.tujuan_sj, 4) = H.id_office
INNER JOIN department AS I ON RIGHT(A.tujuan_sj, 4) = I.id_department
WHERE no_sj = '$sj'";
$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Surat Jalan Perbaikan Barang');

/*output the result*/

$xPos = $pdf->GetX();
$yPos = $pdf->GetY();

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

$keperluan = $header["keperluan_sj"] == "PS" ? "Pengajuan Perbaikan Alat" : "Pengajuan Rekomendasi Pemusnahan";
/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'SURAT JALAN',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->SetFont('Arial','',9);
$pdf->Cell(189 ,1,'Reprint',0,1, 'C');

$pdf->Line(10, 24, 200, 24);

$pdf->Ln(6);

$pdf->Cell(28 ,5,'Nomor SJ',0,0);
$pdf->Cell(48 ,5,': '.substr($header['no_sj'], 1, 5),0,0,'');
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tgl Cetak',0,0);
$pdf->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
$pdf->Cell(28 ,5,'Jenis SJ',0,0);
$pdf->Cell(162 ,5,': '.$keperluan, 0, 1,'');

$pdf->Ln(4);

$pdf->Cell(28 ,5,'Dari',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name']." ".$header['office_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tgl Buat',0,0);
$pdf->Cell(48 ,5,': '.$header['tanggal_sj'], 0, 1);

$pdf->Cell(28 ,5,'Tujuan',0,0);
$pdf->Cell(48 ,5,': '.$header['deptto']." ".$header['officeto'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Di Cetak Oleh',0,0);
$pdf->Cell(48 ,5,': '.$header['username'], 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);
/*Heading Of the table*/
$pdf->Cell(8 ,8,'No',1,0,'C');
$pdf->Cell(61 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(35 ,8,'Serial Number',1,0,'C');
$pdf->Cell(23 ,8,'No Aktiva',1,0,'C');
$pdf->Cell(61 ,8,'Keterangan / Kerusakan',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',9);

$no = 1;

if (isset($sj) && !empty($sj)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk_sj']." ".$data['tipe_sj'];
            $sn = $data['sn_sj'];
            $at = $data['at_sj'];
            $ket = $data["keterangan_sj"];

            $pdf->SetWidths(array(8, 61, 35, 23, 61));
            $pdf->Row(array($no++, $desc, $sn, $at, $ket));

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

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);

$pdf->Cell(190 ,8,'Keterangan :',0,1);

$pdf->SetFont('Arial','',9);
$pdf->MultiCell(190 ,5,$header['ket_sj'],0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','',9);

$pdf->MultiCell(190,8,'',0,1);

// $pdf->SetXY($xPos + $cellWidth , $yPos);

$pdf->SetFont('Arial','',9);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(55 ,8,'Diterima',1,0,'C');
$pdf->Cell(80 ,8,'Disetujui',1,0,'C');
$pdf->Cell(55 ,8,'Dibuat',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(80 ,28,'',1,0,'');
$pdf->Cell(55 ,28,'',1,1,'');
$pdf->Cell(55 ,8,'',1,0,'C');
$pdf->Cell(40 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(40 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(55 ,8,$header['nik'].' - '.$header['username'],1,1,'C');


$pdf->Output("FORM-SJ-".$header['no_sj']."-".date("d-m-Y").".pdf","I");

?>
       