<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTSN'])){
    $_POST = $_SESSION['PRINTSN'];
    unset($_SESSION['PRINTSN']);
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
            $sql = "SELECT A.*, B.office_name, C.department_name, D.NamaBarang, E.NamaJenis FROM serial_number AS A
            INNER JOIN office AS B ON A.office_serial_number = B.id_office
            INNER JOIN department AS C ON A.dept_serial_number = C.id_department
            INNER JOIN mastercategory AS D ON LEFT(A.pluid_serial_number, 6) = D.IDBarang
            INNER JOIN masterjenis AS E ON RIGHT(A.pluid_serial_number, 4) = E.IDJenis
            WHERE A.nomor_serial_number = '$decid'";
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

$pdf = new PDF_Code128('L','mm', array(94,58));
$pdf->AddPage();

$pdf->SetTitle('Serial Number Barang');

$pdf->SetFont('Arial','',10);

$desc = $header["NamaBarang"]." ".$header['NamaJenis'];

$num = 0;
$num_top = 10;
$num_bcd = 18;
$num_bottom = 29;

$desccut = strlen($desc) > 35 ? substr($desc, 0, 35) : $desc;
$aktiva = $header["dat_serial_number"];

$pdf->SetXY(8,$num_top+$num);
$pdf->Write(8,$desccut);
$code = $header["nomor_serial_number"];
$pdf->Code128(8,$num_bcd,$code,76,10);
$pdf->SetXY(8,$num_bottom);
$pdf->Write(8,"SN : ".$code);
$pdf->SetXY(62,$num_bottom);
$pdf->Write(8,$aktiva);

$pdf->Output($header['nomor_serial_number'].".pdf","I");

?>
       