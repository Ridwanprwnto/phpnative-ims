<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTP3AT'])){
    $_POST = $_SESSION['PRINTP3AT'];
    unset($_SESSION['PRINTP3AT']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["p3at"];

if(isset($_GET["p3at"])) {
    if($_GET["p3at"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.*, H.*, I.* FROM p3at AS A 
            INNER JOIN detail_p3at AS B ON A.id_p3at = B.id_head_p3at
            INNER JOIN office AS C ON LEFT(A.office_p3at, 4) = C.id_office
            INNER JOIN department AS D ON RIGHT(A.dept_p3at, 4) = D.id_department
            INNER JOIN users AS E ON A.user_p3at = E.nik
            INNER JOIN mastercategory AS F ON LEFT(B.pluid_p3at, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(B.pluid_p3at, 4) = G.IDJenis
            INNER JOIN company AS H ON H.company_id = C.company_office
            LEFT JOIN signature AS I ON CONCAT(A.office_p3at, A.dept_p3at) = CONCAT(I.office_sign, I.dept_sign)
            WHERE A.id_p3at = '$decid'";
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

$thn = explode("-",$header['tgl_p3at']); 
$bulan	= $thn[1];

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Berita Acara P3AT');

/*output the result*/

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,5, '',0,0);
$pdf->Cell(63 ,5, $header['company_jenis'].". ".$header['company_name'],0,1, 'C');

$pdf->SetFont('Arial','',10);

$pdf->Cell(63 ,5,'',0,0);
$pdf->Cell(63 ,5, strtoupper($header['department_name'].' '.$header['office_name'].' '.$header['id_office']), 0, 1, 'C');

$pdf->Cell(63 ,5,'',0,0);
$pdf->Cell(63 ,5, $header['office_address'].' ('.$header['office_poscode'].') ', 0, 1, 'C');

$pdf->Cell(63 ,5,'',0,0);
$pdf->Cell(63 ,5,'Telepon '.$header['office_phone'], 0, 1, 'C');

$pdf->Line(10, 32, 200, 32);

$pdf->Ln(4);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190 ,5,'PERMOHONAN PEMUSNAHAN',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(190 ,5,'No : '.$header['id_p3at'].' / P3AT / '.$header['office_shortname'].' / '.getRomawi($bulan).' / '.$thn[0],0,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','',10);

$pdf->Cell(20 ,5,'Kepada Yth',0,0);
$pdf->Cell(170 ,5,': Bapak '.$header["initial_head_sign"].' ('.$header["name_head_sign"].')',0,1);

$pdf->Cell(20 ,5,'Dari',0,0);
$pdf->Cell(170 ,5 ,': '.$header['department_name'].' '.$header['office_name'].' '.$header['id_office'],0,1);

$pdf->Cell(20 ,5,'Cc',0,0);
$pdf->Cell(170 ,5 ,': Bapak '.$header["initial_reg_sign"].' ('.$header["name_reg_sign"].')',0,1);

$pdf->Cell(20 ,5,'Perihal',0,0);
$pdf->Cell(170 ,5 ,': P3AT Sarana Elektrikal '.$header["department_initial"],0,1);

$pdf->Ln(2);

$pdf->Cell(190 ,5,'Dengan Hormat,',0,1);
$pdf->Cell(190 ,5 ,'Bersama ini kami menerangkan bahwa kondisi Peralatan Sarana Elektrikal DC dibawah ini:',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(9 ,8,'No',1,0,'C');
$pdf->Cell(60 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(38 ,8,'Nomor Seri',1,0,'C');
$pdf->Cell(27 ,8,'Nomor Aktiva',1,0,'C');
$pdf->Cell(32 ,8,'Tahun Perolehan',1,0,'C');
$pdf->Cell(24 ,8,'Nilai Aktiva',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $arr_tahun = array();
    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk_p3at']." ".$data['tipe_p3at'];
        $sn = $data['sn_p3at'];
        $at = $data['at_p3at'];
        $th = $data['th_p3at'];
        $nilai = number_format($data['nilai_p3at']);

        $pdf->SetWidths(array(9, 60, 38, 27, 32, 24));
        $pdf->Row(array($no++, $desc, $sn, $at, $th, $nilai));
        
        $nilai_at = $data['nilai_p3at'];
        $grandtotal = $total+=$nilai_at;

        $arr_tahun[] = $th;

    }

}

$pdf->Ln(2);

$pdf->SetFont('Arial','',10);

$pdf->MultiCell(190 ,5 ,'Peralatan tersebut diatas tidak terpakai karena dalam kondisi rusak, adapun pertimbangan untuk pengajuan P3AT adalah sebagai berikut:',0,1);

$pdf->Ln(2);

$pdf->Cell(190 ,5 ,'1. Direkomendasikan pemusnahan.',0,1);
$pdf->Cell(190 ,5 ,'2. Biaya perbaikan > 35% dari harga perolehan.',0,1);
$pdf->Cell(190 ,5 ,'3. Sisa nilai Buku/Aktiva dari keseluruhan peralatan tersebut sejumlah '.number_format($grandtotal).'.',0,1);

$pdf->Ln(2);

$pdf->MultiCell(190 ,5 ,'Maka dengan ini kami mengajukan usulan P3AT terhadap peralatan tersebut, agar peralatan yang telah dijelaskan diatas dapat dilakukan pemusnahan, guna mengeluarkan data Aktiva Tetap.',0,1);

$pdf->Ln(2);

$pdf->MultiCell(190 ,5 ,'Demikian Berita Acara ini kami sampaikan, atas perhatian dan bantuan Bapak/Ibu kami sampaikan terima kasih.',0,1);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Cell(38 ,8,'Disetujui,',0,0,'C');
$pdf->Cell(114 ,8,'Diketahui,',0,0,'C');
$pdf->Cell(38 ,8,'Dibuat,',0,1,'C');
$pdf->Cell(38 ,28,'',0,0,'');
$pdf->Cell(114 ,28,'',0,0,'');
$pdf->Cell(38 ,28,'',0,1,'');
$pdf->Cell(38 ,1,$header['initial_reg_sign'],0,0,'C');
$pdf->Cell(38 ,1,$header['initial_head_sign'],0,0,'C');
$pdf->Cell(38 ,1,$header['initial_vum_sign'],0,0,'C');
$pdf->Cell(38 ,1,$header['initial_dept_sign'],0,0,'C');
$pdf->Cell(38 ,1,$header['initial_deputy_sign'],0,1,'C');
$pdf->Cell(38 ,8,$header['name_reg_sign'],0,0,'C');
$pdf->Cell(38 ,8,$header['name_head_sign'],0,0,'C');
$pdf->Cell(38 ,8,$header['name_vum_sign'],0,0,'C');
$pdf->Cell(38 ,8,$header['name_dept_sign'],0,0,'C');
$pdf->Cell(38 ,8,$header['name_deputy_sign'],0,1,'C');


$pdf->Output("BA-P3AT-".$header['id_p3at']."-".date("d-m-Y").".pdf","I");

?>
       