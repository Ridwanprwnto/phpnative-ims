<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

$office = mysqli_real_escape_string($conn, $_POST["office-lnl"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-lnl"]);
$gab = mysqli_real_escape_string($conn, $_POST["barang-lnl"]);
$lambung = $_POST["lambunglnl"];
$kondisi = "06";

$pluid = substr($gab, 8);

$arrdata = implode(", ", $lambung);

if ($arrdata == "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM barang_assets
    LEFT JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    LEFT JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    WHERE LEFT(barang_assets.dat_asset, 4) = '$office' AND RIGHT(barang_assets.dat_asset, 4) = '$dept' AND barang_assets.pluid = '$pluid' AND barang_assets.kondisi != '$kondisi' AND LENGTH(barang_assets.no_lambung) = 5 ORDER BY barang_assets.no_lambung ASC";
}
else {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM barang_assets
    LEFT JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    LEFT JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    WHERE LEFT(barang_assets.dat_asset, 4) = '$office' AND RIGHT(barang_assets.dat_asset, 4) = '$dept' AND barang_assets.pluid = '$pluid' AND barang_assets.kondisi != '$kondisi' AND barang_assets.no_lambung IN ($arrdata) ORDER BY barang_assets.no_lambung ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$pdf = new PDF_Code128('L','mm', array(94,60));
$pdf->AddPage();

$pdf->SetTitle('Data Barcode Nomor Lambung Peralatan Inventaris');

if (isset($office) && isset($dept) && isset($gab) && isset($lambung)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {
        
        while($data = mysqli_fetch_array($query)) {
            
            $nomor = $data["no_lambung"];
            $desc = $data["NamaBarang"]." ".$data['NamaJenis'];
            $code = $data["no_lambung"];

            $pdf->SetFont('Arial','B',20);
            $pdf->Cell(30,17,$nomor,1,1,'C');
            
            $pdf->SetFont('Arial','',8);

            $pdf->Code128(11,28,$code,28,5);
            $pdf->Cell(30,7,"",1,1,'C');
            $pdf->Cell(30,5,'PD : '.date("d-m-Y"),1,1,'C');

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
$pdf->Output("REPORT BARCODE LNL".".pdf","I");

?>