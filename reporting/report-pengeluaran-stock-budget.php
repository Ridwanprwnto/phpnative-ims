<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../vendor/fpdf/fpdf.php';

$office = mysqli_real_escape_string($conn, $_POST["office"]);
$dept = mysqli_real_escape_string($conn, $_POST["department"]);
$year = mysqli_real_escape_string($conn, $_POST["year"]);
$pluid = mysqli_real_escape_string($conn, $_POST["pluid"]);
$user = mysqli_real_escape_string($conn, $_POST["user"]);

$sql = "SELECT detail_pembelian.*, pembelian.*, office.*, department.*, mastercategory.*, masterjenis.* FROM detail_pembelian
    INNER JOIN pembelian ON detail_pembelian.noref = pembelian.noref
    INNER JOIN office ON LEFT(detail_pembelian.id_offdep, 4) = office.id_office
    INNER JOIN department ON RIGHT(detail_pembelian.id_offdep, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(detail_pembelian.plu_id, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(detail_pembelian.plu_id, 4) = masterjenis.IDJenis
    WHERE LEFT(detail_pembelian.id_offdep, 4) = '$office' AND RIGHT(detail_pembelian.id_offdep, 4) = '$dept' AND LEFT(detail_pembelian.tgl_detail_pp, 4) = '$year' AND detail_pembelian.plu_id = '$pluid'";

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$sql_budget = "SELECT stock_budget, use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND plu_id = '$pluid'";
$query_budget = mysqli_query($conn, $sql_budget);
$data_budget = mysqli_fetch_assoc($query_budget);

/*A4 width : 219mm*/
$pdf = new FPDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Pengeluaran Saldo Budget');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();


$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'Office',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Print Date',0,0);
$pdf->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

$pdf->Cell(28 ,5,'Department',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'User',0,0);
$pdf->Cell(48 ,5,': '.$user, 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',14);

$pdf->Cell(190, 10, 'LAPORAN PENGELUARAN BUDGET', 0, 1, 'C');

$pdf->SetFont('Arial','',10);

$pdf->Cell(190, 5,'Tahun : '.$year, 0, 1, 'C');

$pdf->Ln(6);

$pdf->SetFont('Arial','',10);

$pdf->Cell(24 ,8,'PLU - DESC :',0,0,'L');
$pdf->Cell(166 ,8,$header["plu_id"]." - ".$header["NamaBarang"]." ".$header["NamaJenis"],0,1,'L');

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(12 ,10,'No',0,0,'C');
$pdf->Cell(35 ,10,'Tanggal',0,0,'C');
$pdf->Cell(40 ,10,'Docno',0,0,'C');
$pdf->Cell(25 ,10,'Qty Keluar',0,0,'C');
$pdf->Cell(25 ,10,'Qty Saldo',0,0,'C');
$pdf->Cell(53 ,10,'Nilai',0,1,'C');
/*end of line*/

$pdf->Line(10, 63, 200, 63);

$harga = $header["HargaJenis"];

$pdf->Cell(47 ,8,'Saldo Awal',0,0,'L');
$pdf->Cell(40 ,8,'',0,0,'C');
$pdf->Cell(25 ,8,'',0,0,'C');
$pdf->Cell(25 ,8,$saldo_awal = isset($data_budget["stock_budget"]) ? $data_budget["stock_budget"] : 0,0,0,'C');
$pdf->Cell(53 ,8,'Rp. '.number_format($nilai = $harga*$saldo_awal,2),0,1,'C');

$pdf->SetFont('Arial','',8);

$nol = 0;
$no = 1;
if (isset($office) && isset($dept) && isset($year) && isset($pluid) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $pdf->Cell(12 ,8,$no++,0,0,'C');
            $pdf->Cell(35 ,8,$data["tgl_pengajuan"],0,0,'C');
            $pdf->Cell(40 ,8,$data["ppid"],0,0,'C');
            $pdf->Cell(25 ,8,$qty = $data["qty"],0,0,'C');
            $pdf->Cell(25 ,8,$saldo_akhir = ($saldo_awal -= $qty),0,0,'C');
            $pdf->Cell(53 ,8,'Rp. '.number_format(($harga*$qty),2),0,1,'C');
            $qty_keluar = $nol+=$qty;

        }

        $pdf->SetFont('Arial','B',8);

        $pdf->Cell(47 ,8,'Saldo Akhir',0,0,'L');
        $pdf->Cell(40 ,8,'',0,0,'C');
        $pdf->Cell(25 ,8,$qty_keluar,0,0,'C');
        $pdf->Cell(25 ,8,$saldo_akhir,0,0,'C');
        $pdf->Cell(53 ,8,'Rp. '.number_format(($harga*$saldo_akhir),2),0,1,'C');
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
$pdf->Output("LAPORAN PENGELUARAN BUDGET-".$year."-".$pluid.".pdf","I");

?>