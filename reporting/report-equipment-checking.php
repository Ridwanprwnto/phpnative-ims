<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTEQUIPCHECK'])){
    $_POST = $_SESSION['PRINTEQUIPCHECK'];
    unset($_SESSION['PRINTEQUIPCHECK']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET["no"]);

if(isset($_GET["no"])) {
    if($_GET["no"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.username AS pic, G.username AS terimauser FROM equipment_checking AS A
            INNER JOIN office AS B ON A.office_equip_check = B.id_office
            INNER JOIN department AS C ON A.dept_equip_check = C.id_department
            INNER JOIN mastercategory AS D ON LEFT(A.plu_equip_check, 6) = D.IDBarang
            INNER JOIN masterjenis AS E ON RIGHT(A.plu_equip_check, 4) = E.IDJenis
            INNER JOIN users AS F ON A.pic_equip_check = F.nik
            INNER JOIN users AS G ON A.receive_equip_check = G.nik
            WHERE A.no_equip_check ='$decid'";
            $query_h = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($query_h);
            if(!$data || empty($data)) {
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

$pdf = new PDF_Code128('L','mm', array(94,58));
$pdf->AddPage();

$pdf->SetTitle('Report Label Form Equipment Checking');

/*output the result*/    
$off = $data["id_office"]." - ".$data["office_shortname"]." ".$data["department_initial"];

$date  =  date( "d-m-Y", strtotime($data['date_equip_check']));

$docno = $data["no_equip_check"];

$desc = "ALAT : ".$data["NamaBarang"]." ".$data['NamaJenis'];
$desccut = strlen($desc) > 34 ? substr($desc, 0, 34) : $desc;

$pic = $data["pic_equip_check"]." - ".strtoupper($data["pic"]);
$piccut = strlen($pic) > 36 ? substr($pic, 0, 36) : $pic;

$kondisi = $data["kondisi_equip_check"];

$ket = $data["ket_equip_check"];
$ketcut = strlen($ket) > 69 ? substr($ket, 0, 69) : $ket;

$pdf->SetXY(8,10);
$pdf->Cell(76,0,'',1,0,'C');
$pdf->SetXY(8,24);
$pdf->Cell(76,0,'',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetXY(8,0);
$pdf->Write(8,$off);

$pdf->SetXY(8,4);
$pdf->Write(8,'TGL : '.$date);
$pdf->SetXY(52,4);
$pdf->Write(8,'DOCNO : '.$docno);

$pdf->SetFont('Arial','',10);
$pdf->SetXY(8,9);
$pdf->Write(8,"PIC : ".$piccut);
$pdf->SetXY(8,13);
$pdf->Write(8,$desccut);
$pdf->SetXY(8,17);

$pdf->SetFont('Arial','B',10);
$pdf->Write(8,"KONDISI : ".$kondisi);

$pdf->SetXY(8,23);
$pdf->Write(8,"KETERANGAN : ");

$pdf->SetFont('Arial','',10);
$pdf->SetXY(8,29);
$pdf->MultiCell(76,4,$ketcut,0,1,);

$pdf->Output("LABEL FORM CHECKING ".$data['no_equip_check'].".pdf","I");

?>
       