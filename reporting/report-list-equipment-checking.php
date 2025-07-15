<?php
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$awal = $_POST["awal-cetak"];
$akhir = $_POST["akhir-cetak"];
$barang = $_POST["barang-cetak"];
$kondisi = $_POST["kondisi-cetak"];

if ($barang == "ALL" && $kondisi == "ALL"){
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username AS pic, E.NamaBarang, F.NamaJenis, G.username AS receive_user FROM equipment_checking AS A 
    INNER JOIN office AS B ON A.office_equip_check = B.id_office
    INNER JOIN department AS C ON A.dept_equip_check = C.id_department
    INNER JOIN users AS D ON A.pic_equip_check = D.nik
    INNER JOIN mastercategory AS E ON LEFT(A.plu_equip_check, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.plu_equip_check, 4) = F.IDJenis
    INNER JOIN users AS G ON A.receive_equip_check = G.nik
    WHERE A.office_equip_check = '$office' AND A.dept_equip_check = '$dept' AND A.date_equip_check BETWEEN '$awal' AND '$akhir' ORDER BY A.date_equip_check ASC";
}
elseif ($barang != "ALL" && $kondisi == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username AS pic, E.NamaBarang, F.NamaJenis, G.username AS receive_user FROM equipment_checking AS A 
    INNER JOIN office AS B ON A.office_equip_check = B.id_office
    INNER JOIN department AS C ON A.dept_equip_check = C.id_department
    INNER JOIN users AS D ON A.pic_equip_check = D.nik
    INNER JOIN mastercategory AS E ON LEFT(A.plu_equip_check, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.plu_equip_check, 4) = F.IDJenis
    INNER JOIN users AS G ON A.receive_equip_check = G.nik
    WHERE A.office_equip_check = '$office' AND A.dept_equip_check = '$dept' AND A.plu_equip_check = '$barang' AND A.date_equip_check BETWEEN '$awal' AND '$akhir' ORDER BY A.date_equip_check ASC";
}
elseif ($barang == "ALL" && $kondisi != "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username AS pic, E.NamaBarang, F.NamaJenis, G.username AS receive_user FROM equipment_checking AS A 
    INNER JOIN office AS B ON A.office_equip_check = B.id_office
    INNER JOIN department AS C ON A.dept_equip_check = C.id_department
    INNER JOIN users AS D ON A.pic_equip_check = D.nik
    INNER JOIN mastercategory AS E ON LEFT(A.plu_equip_check, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.plu_equip_check, 4) = F.IDJenis
    INNER JOIN users AS G ON A.receive_equip_check = G.nik
    WHERE A.office_equip_check = '$office' AND A.dept_equip_check = '$dept' AND A.kondisi_equip_check = '$kondisi' AND A.date_equip_check BETWEEN '$awal' AND '$akhir' ORDER BY A.date_equip_check ASC";
}
elseif ($barang != "ALL" && $kondisi != "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username AS pic, E.NamaBarang, F.NamaJenis, G.username AS receive_user FROM equipment_checking AS A 
    INNER JOIN office AS B ON A.office_equip_check = B.id_office
    INNER JOIN department AS C ON A.dept_equip_check = C.id_department
    INNER JOIN users AS D ON A.pic_equip_check = D.nik
    INNER JOIN mastercategory AS E ON LEFT(A.plu_equip_check, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.plu_equip_check, 4) = F.IDJenis
    INNER JOIN users AS G ON A.receive_equip_check = G.nik
    WHERE A.office_equip_check = '$office' AND A.dept_equip_check = '$dept' AND A.plu_equip_check = '$barang' AND A.kondisi_equip_check = '$kondisi' AND A.date_equip_check BETWEEN '$awal' AND '$akhir' AND A.plu_equip_check = '$barang' ORDER BY A.date_equip_check ASC";
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

/*A4 width : 219mm*/

class PDF extends PDF_MC_Table {
    // Page header
    function Header()
    {
        
        global $header, $user, $awal, $akhir;
        
        /*output the result*/
        $this->SetFont('Arial','',8);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header['office_equip_check']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);

        $this->Ln(4);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',12);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(63 ,7,'',0,0);
        $this->Cell(63 ,7,'REKAPITULASI HASIL PENGECEKAN BARANG NON AKTIVA',0,0, 'C');
        $this->Cell(63 ,7,'',0,1);

        $this->SetFont('Arial','',8);

        $strdate_awal  =  str_replace('-"', '/', $awal);
        $strdtstart  =  date( "d/m/Y", strtotime($strdate_awal));
        $strdate_akhir  =  str_replace('-"', '/', $akhir);
        $strdtstop  =  date( "d/m/Y", strtotime($strdate_akhir));

        $this->Cell(190 ,5,'Periode : '.$strdtstart." - ".$strdtstop,0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'No',1,0,'C');
        $this->Cell(13 ,8,'Docno',1,0,'C');
        $this->Cell(17 ,8,'Tanggal',1,0,'C');
        $this->Cell(50 ,8,'Nama Barang',1,0,'C');
        $this->Cell(22 ,8,'Diserahkan',1,0,'C');
        $this->Cell(22 ,8,'PIC',1,0,'C');
        $this->Cell(16 ,8,'Kondisi',1,0,'C');
        $this->Cell(40 ,8,'Keterangan',1,1,'C');
       
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Report List Equipment Checking');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($user) && isset($barang)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            $slash_date  =  str_replace('-"', '/', $data['date_equip_check']);
            $newDate  =  date( "d/m/Y", strtotime($slash_date));

            $docno = $data['no_equip_check'];
            $desc = $data["plu_equip_check"]." - ".$data["NamaBarang"]." ".$data["NamaJenis"];
            $pic = $data["pic_equip_check"]." - ".strtoupper($data["pic"]);
            $serah = $data['receive_equip_check']." - ".strtoupper($data["receive_user"]);

            $kondisi = $data['kondisi_equip_check'];
            $ket = $data['ket_equip_check'];
            
            $pdf->SetWidths(array(10, 13, 17, 50, 22, 22, 16, 40));
            $pdf->Row(array($no++, $docno, $newDate, $desc, $serah, $pic, $kondisi, $ket));

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

$pdf->Output("REPORT EQUIPMENT CHECK ".$awal." ".$akhir.".pdf","I");

?>