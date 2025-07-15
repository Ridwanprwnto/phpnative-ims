<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

$office = mysqli_real_escape_string($conn, $_POST["office-lat"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-lat"]);
$gab = mysqli_real_escape_string($conn, $_POST["barang-lat"]);
$aktiva = mysqli_real_escape_string($conn, $_POST["aktiva-lat"]);
$kondisi = "06";

$pluid = substr($gab, 8);

if ($aktiva == "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM barang_assets
    LEFT JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    LEFT JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    WHERE LEFT(barang_assets.dat_asset, 4) = '$office' AND RIGHT(barang_assets.dat_asset, 4) = '$dept' AND barang_assets.pluid = '$pluid' AND barang_assets.kondisi != '$kondisi' ORDER BY barang_assets.no_at";
}
else {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM barang_assets
    LEFT JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    LEFT JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    WHERE LEFT(barang_assets.dat_asset, 4) = '$office' AND RIGHT(barang_assets.dat_asset, 4) = '$dept' AND barang_assets.pluid = '$pluid' AND barang_assets.no_at = '$aktiva' AND barang_assets.kondisi != '$kondisi' ORDER BY barang_assets.no_at";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$pdf = new PDF_Code128('L','mm', array(94,58));
$pdf->AddPage();

$pdf->SetTitle('Report LAT Barcode 1/2 Size');

$pdf->SetFont('Arial','',8);

if (isset($office) && isset($dept) && isset($gab) && isset($aktiva)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {
        
        $num = 0;
        $num_top = 6;
        $num_desc = 10;
        $num_sn = 13;
        $num_bcd = 20;
        $num_bottom = 24;
        while($data = mysqli_fetch_array($query)){

            $off = isset($data["dat_asset"]) ? substr($data["dat_asset"], 0, 4)." - ".$data["office_shortname"]." ".$data["department_initial"] : "-";
            $desc = $data["NamaBarang"]." ".$data['ba_merk']." ".$data['ba_tipe'];

            $pdf->SetXY(8,$num+=$num_bottom);
            $pdf->Cell(76,0,'',0,0,'C');

            $pdf->SetXY(8,$num_top);
            $pdf->Write(8,$off);
            $pdf->SetXY(8,$num_desc);
            $pdf->Write(8,$desc);
            $sn = $data["sn_barang"];
            $pdf->SetXY(8,$num_sn);
            $pdf->Write(8,$sn);
            $code = $data["no_at"];
            $pdf->Code128(8,$num_bcd,$code,50,5);
            $pdf->SetXY(8,$num_bottom);
            $pdf->Write(8,$code);
            $pdf->SetXY(27,$num_bottom);
            $pdf->Write(8,'Print Date : '.date("d-m-Y"));

            $looprows = $num+=20;
            
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
$pdf->Output("REPORT STICKER BARCODE AKTIVA".".pdf","I");

?>