<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

$office = mysqli_real_escape_string($conn, substr($_POST["jenis-barcode"], 0, 4));
$jenis = mysqli_real_escape_string($conn, substr($_POST["jenis-barcode"], 4));
$mobil = $_POST["mobilbarcode"];

$arrdata = implode(", ", $mobil);

if ($jenis == "ALL" && $arrdata == "ALL") {
    $sql = "SELECT mobil.*, office.office_shortname FROM mobil
    INNER JOIN office ON mobil.office_mobil = office.id_office
    WHERE mobil.office_mobil = '$office' ORDER BY mobil.no_mobil ASC";
}
elseif ($jenis != "ALL" && $arrdata == "ALL") {
    $sql = "SELECT mobil.*, office.office_shortname FROM mobil
    INNER JOIN office ON mobil.office_mobil = office.id_office
    WHERE mobil.office_mobil = '$office' AND mobil.jenis_mobil = '$jenis' ORDER BY mobil.no_mobil ASC";
}
elseif ($jenis != "ALL" && $arrdata != "ALL") {
    $sql = "SELECT mobil.*, office.office_shortname FROM mobil
    INNER JOIN office ON mobil.office_mobil = office.id_office
    WHERE mobil.office_mobil = '$office' AND mobil.jenis_mobil = '$jenis' AND mobil.no_mobil IN ($arrdata) ORDER BY mobil.no_mobil ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$pdf = new PDF_Code128('L','mm', array(94,69));
$pdf->AddPage();

$pdf->SetTitle('Data Barcode Delivery Van');

$pdf->SetFont('Arial','B',30);

if (isset($mobil)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {
        
        $num = 0;
        $num_top = 10;
        $num_bcd = 8;
        $num_bottom = 45;
        
        while($data = mysqli_fetch_array($query)){

            $code = $data["office_shortname"].$data['no_mobil'];

            $pdf->SetXY(2,$num_top+$num);
            $pdf->Cell(89 ,8,"",0,1,'C');
            $pdf->Code128(2,$num_bcd,$code,90,30);
            $pdf->SetXY(22,$num_bottom);
            $pdf->Write(2,$code);

            $looprows = $num+=34;
        
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
$pdf->Output("DATA-BARCODE-VAN".".pdf","I");

?>