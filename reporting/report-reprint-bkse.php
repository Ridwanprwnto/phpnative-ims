<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$id = mysqli_real_escape_string($conn, $_POST["id-bkse"]);

$sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username FROM bkse AS A
INNER JOIN office AS B ON A.office_bkse = B.id_office
INNER JOIN department AS C ON A.dept_bkse = C.id_department
INNER JOIN divisi AS D ON A.div_bkse = D.id_divisi
INNER JOIN mastercategory AS E ON LEFT(A.pluid_bkse, 6) = E.IDBarang
INNER JOIN masterjenis AS F ON RIGHT(A.pluid_bkse, 4) = F.IDJenis
INNER JOIN users AS G ON A.user_bkse = G.nik
WHERE A.nomor_bkse = '$id'";

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);


/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Reprint BA Kerusakan Sarana Elektrikal');

if (isset($id) && !empty($id)) {

    if($header) {

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190 ,5,'BERITA ACARA',0,1,'C');
        $pdf->Cell(190 ,5,'KERUSAKAN SARANA ELEKTRIKAL',0,1,'C');
        
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(190 ,5,'Reprint',0,1,'C');

        $pdf->Line(10, 26, 200, 26);

        $pdf->Ln(4);

        $pdf->Cell(190 ,5,'Nomor : '.$header["nomor_bkse"],0,1,'C');

        $pdf->Ln(4);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(190 ,5 ,'Menerangkan bahwa telah terjadi kerusakan sarana elektrikal dengan keterangan sebagai berikut :',0,1);

        $pdf->Ln(2);

        $office = $header["office_bkse"]." - ".strtoupper($header["department_name"]);
        $divisi = $header["divisi_name"];
        $tgl = $header["tgl_bkse"];
        $desc = $header['NamaBarang']." ".$header['NamaJenis']." ".$header['merk_bkse']." ".$header['tipe_bkse'];
        $sn = $header['sn_bkse'];
        $at = $header['at_bkse'];
        $no = $header['no_bkse'];
        $tempat = $header['penempatan_bkse'];
        $rusak = $header['kerusakan_bkse'];

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Depertemen',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$office,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Divisi',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$divisi,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Tanggal',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$tgl,0,1);

        $pdf->Ln(4);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Nama Barang',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$desc,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Serial Number',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$sn,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Nomor Aktiva',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$at,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Nomor Lambung',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$no,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Penempatan',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->Cell(150 ,5 ,$tempat,0,1);

        $pdf->Cell(8 ,5,'',0,0);
        $pdf->Cell(30 ,5 ,'Kerusakan',0,0);
        $pdf->Cell(2 ,5,':',0,0);
        $pdf->MultiCell(150 ,5 ,$rusak,0,1);

        $pdf->SetFont('Arial','',10);

        $pdf->Ln(2);

        $pdf->MultiCell(190 ,5 ,'Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',0,1);

        $pdf->Ln(6);

        $pdf->Cell(114 ,8,'Mengetahui,',1,0,'C');
        $pdf->Cell(38 ,8,'Pemakai,',1,0,'C');
        $pdf->Cell(38 ,8,'Dibuat,',1,1,'C');
        $pdf->Cell(38 ,28,'',1,0,'');
        $pdf->Cell(38 ,28,'',1,0,'');
        $pdf->Cell(38 ,28,'',1,0,'');
        $pdf->Cell(38 ,28,'',1,0,'');
        $pdf->Cell(38 ,28,'',1,1,'');
        $pdf->Cell(38 ,8,'DCM/DDCM',1,0,'C');
        $pdf->Cell(38 ,8,'SPV ADM',1,0,'C');
        $pdf->Cell(38 ,8,'SPV BAGIAN',1,0,'C');
        $pdf->Cell(38 ,8,'',1,0,'C');
        $pdf->Cell(38 ,8,strtoupper($header["username"]),1,1,'C');

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


$pdf->Output("BA-".$header['nomor_bkse']."-".$header['tgl_bkse'].".pdf","I");

?>
       