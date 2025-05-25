<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$id = mysqli_real_escape_string($conn, $_POST["docno"]);

$sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.username AS pic, G.username AS penerima, H.* FROM btb_dpd AS A
INNER JOIN office AS B ON A.office_btb_dpd = B.id_office
INNER JOIN department AS C ON A.dept_btb_dpd = C.id_department
INNER JOIN mastercategory AS D ON LEFT(A.pluid_btb_dpd, 6) = D.IDBarang
INNER JOIN masterjenis AS E ON RIGHT(A.pluid_btb_dpd, 4) = E.IDJenis
INNER JOIN users AS F ON A.pic_btb_dpd = F.nik
INNER JOIN users AS G ON A.penerima_btb_dpd = G.nik
INNER JOIN satuan AS H ON D.id_satuan = H.id_satuan
WHERE A.no_btb_dpd ='$id'";

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

$code = substr($header["no_btb_dpd"], 0, 1);

if($code == 'I') {
    $desc_code = 'PENERIMAAN';
}
elseif($code == 'O') {
    $desc_code = 'PENGELUARAN';
}
elseif($code == 'A') {
    $desc_code = 'PENYESUAIAN';
}

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Report Reprint Bukti Mutasi Barang');

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

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'BUKTI '.$desc_code.' BARANG',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->SetFont('Arial','',9);
$pdf->Cell(189 ,1,'Reprint',0,1, 'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','',9);

$pdf->Cell(28 ,5,'Office',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tanggal',0,0);
$pdf->Cell(48 ,5,': '.$header['tgl_btb_dpd'], 0, 1);

$pdf->Cell(28 ,5,'Department',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Docno',0,0);
$pdf->Cell(48 ,5,': '.substr($header['no_btb_dpd'], 1), 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(82 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(20 ,8,'Satuan',1,0,'C');
$pdf->Cell(13 ,8,'Qty',1,0,'C');
$pdf->Cell(65 ,8,'Keterangan',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',9);

$no = 1;

if (isset($id) && !empty($id)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            $desc = $data["pluid_btb_dpd"]." - ".$data["NamaBarang"]." ".$data["NamaJenis"];
            $satuan = $data["nama_satuan"];
            $qty = $data["qty_akhir_btb_dpd"];
            $ket = $data["ket_btb_dpd"];

            $pdf->SetWidths(array(10, 82, 20, 13, 65));
            $pdf->Row(array($no++, $desc, $satuan, $qty, $ket));
         
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

$pdf->Ln(8);

$pdf->Cell(55 ,8,'Diterima',1,0,'C');
$pdf->Cell(80 ,8,'Diketahui',1,0,'C');
$pdf->Cell(55 ,8,'Dibuat',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(80 ,28,'',1,0,'');
$pdf->Cell(55 ,28,'',1,1,'');
$pdf->Cell(55 ,8,$header['penerima_btb_dpd'].' - '.$header['penerima'],1,0,'C');
$pdf->Cell(40 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(40 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(55 ,8,$header['pic_btb_dpd'].' - '.$header['pic'],1,1,'C');


$pdf->Output("BUKTI-".$desc_code."-BARANG-".substr($header['no_btb_dpd'], 1).".pdf","I");

?>
       