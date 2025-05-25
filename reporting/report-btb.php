<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTBTB'])){
    $_POST = $_SESSION['PRINTBTB'];
    unset($_SESSION['PRINTBTB']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["nomor"];

if(isset($_GET["nomor"])) {
    if($_GET["nomor"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.id_office, C.office_name, C.office_city, D.*, E.nik, E.username, F.*, G.*, H.*, I.id_office AS officeto, I.office_name AS office_nameto, J.department_name AS deptto, K.nama_satuan, L.date_khusus, L.ket_khusus, M.office_name, N.department_name FROM penerimaan_pembelian AS A 
            INNER JOIN detail_penerimaan_pembelian AS B ON A.id_penerimaan = B.id_penerimaan_pp
            INNER JOIN office AS C ON A.office_penerimaan = C.id_office
            INNER JOIN department AS D ON A.dept_penerimaan = D.id_department
            LEFT JOIN users AS E ON A.user_proses = E.nik
            INNER JOIN mastercategory AS F ON LEFT(B.pluid_penerimaan, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(B.pluid_penerimaan, 4) = G.IDJenis
            INNER JOIN pembelian AS H ON A.pp_id_pembelian = H.id_pembelian
            INNER JOIN office AS I ON H.office_to = I.id_office
            INNER JOIN department AS J ON H.department_to = J.id_department
            INNER JOIN satuan AS K ON F.id_satuan = K.id_satuan
            INNER JOIN barang_khusus AS L ON A.id_penerimaan = L.noref_khusus
            INNER JOIN office AS M ON A.office_penerimaan = M.id_office
            INNER JOIN department AS N ON A.dept_penerimaan = N.id_department
            WHERE A.pp_id_pembelian = '$decid' AND B.status_penerimaan = 'Y'";
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

$pdf->SetTitle('Bukti Terima Barang Atas Pembelian');

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
$pdf->SetFont('Arial','B',14);

$pdf->Cell(190 ,6,'BUKTI TERIMA BARANG PEMBELIAN',0,1, 'C');

$stb = $header['user_proses'] == NULL ? 'Sebagian' : 'Semua';

$pdf->Line(10, 18, 200, 18);

$pdf->Ln(4);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(190 ,5,'Nomor BTB : '.substr($header['id_penerimaan'], 1, 5),0,1,'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'Tujuan PP',0,0);
$pdf->Cell(48 ,5,': '.$header['officeto']." - ".$header['office_nameto'], 0, .0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tgl BTB',0,0);
$pdf->Cell(48 ,5,': '.$header['date_khusus'],0,1);

$pdf->Cell(28 ,5,'',0,0);
$pdf->Cell(48 ,5,': '."Dept. ".$header['deptto'], 0, 0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Noref PP',0,0);
$pdf->Cell(48 ,5,': '.$header['ppid'],0,1);

$pdf->Cell(114 ,5,'',0,0);
$pdf->Cell(28 ,5,'STB',0,0);
$pdf->Cell(48 ,5,': '.$stb,0,1);


$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(85 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(17 ,8,'Satuan',1,0,'C');
$pdf->Cell(12 ,8,'Qty',1,0,'C');
$pdf->Cell(66 ,8,'Keterangan',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['pluid_penerimaan']." - ".$data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk_penerimaan']." ".$data['tipe_penerimaan'];
        $satuan = $data['nama_satuan'];
        $qty = $data['qty_penerimaan'];
        $keterangan = $data['keterangan_penerimaan'];
        
        $pdf->SetWidths(array(10, 85, 17, 12, 66));
        $pdf->Row(array($no++, $desc, $satuan, $qty, $keterangan));

    }

}

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(28 ,5,'Keterangan',0,0);
$pdf->Cell(48 ,5,':',0,0);
$pdf->Cell(114 ,5,'',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',9);

$xPos=$pdf->GetX();
$yPos=$pdf->GetY();
$pdf->MultiCell($cellWidth,$cellHeight,$header['ket_khusus'],0,1);

// $pdf->SetXY($xPos + $cellWidth , $yPos);

$pdf->SetFont('Arial','',10);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(47 ,8,'Diterima',1,0,'C');
$pdf->Cell(95 ,8,'Diketahui',1,0,'C');
$pdf->Cell(47 ,8,'Dibuat',1,1,'C');
$pdf->Cell(47 ,28,'',1,0,'');
$pdf->Cell(47.5 ,28,'',1,0,'');
$pdf->Cell(47.5 ,28,'',0,0,'');
$pdf->Cell(47 ,28,'',1,1,'');
$pdf->Cell(47 ,8,'',1,0,'C');
$pdf->Cell(47.5 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(47.5 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(47 ,8,strtoupper($header['username']),1,1,'C');


$pdf->Output("FORM-BTB-".$header['id_penerimaan_pp']."-".date("d-m-Y").".pdf","I");

?>
       