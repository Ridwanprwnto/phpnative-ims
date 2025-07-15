<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

$office = mysqli_real_escape_string($conn, $_POST["office-rebarcode"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-rebarcode"]);
$barcodebb = $_POST["nomorbronjong"];

$arrdata = implode(", ", $barcodebb);

if ($arrdata == "ALL") {
    $sql = "SELECT * FROM barcode_bronjong WHERE office_bb = '$office' AND dept_bb = '$dept'";
}
else {
    $sql = "SELECT * FROM barcode_bronjong WHERE office_bb = '$office' AND dept_bb = '$dept' AND nomor_bb IN ($arrdata) ORDER BY nomor_bb ASC";
}

$pdf = new PDF_Code128('L','mm', array(94,69));
$pdf->AddPage();

$pdf->SetTitle('Reprint Data Nomor Barcode Bronjong');

$pdf->SetFont('Arial','',16);

if (isset($barcodebb)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {
        
        $num = 0;
        $num_top = 10;
        $num_bcd = 20;
        $num_bottom = 45;
        
        while($data = mysqli_fetch_array($query)){

            $pdf->SetFont('Arial','',12);
            $printDate = date("Y-m-d H:i:s");
            $code = $data["nomor_bb"];

            $pdf->SetXY(2,$num_top+$num);
            $pdf->Cell(89 ,8,'Print date: '.$printDate,0,1,'C');
            $pdf->Code128(2,$num_bcd,$code,90,20);
            $pdf->SetXY(20,$num_bottom);
            
            $pdf->SetFont('Arial','B',20);
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
$pdf->Output($office."_BARCODE_BRONJONG_".date("Y-m-d").".pdf","I");

?>