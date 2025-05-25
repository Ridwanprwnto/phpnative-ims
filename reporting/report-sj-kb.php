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
$status = mysqli_real_escape_string($conn, $_POST["status-cetak"]);
$awal = $_POST["awal-cetak"];
$akhir = $_POST["akhir-cetak"];

if ($status == "A") {
    $sql = "SELECT A.*, B.id_office AS id_off_to, B.office_name AS name_off_to, B.office_city AS city_off_to, C.id_department AS id_dept_to, C.department_name AS name_dpt_to, D.username, E.id_office AS id_off_from, E.office_name AS name_off_from, F.id_department AS id_dept_from, F.department_name AS name_dpt_from FROM surat_jalan AS A 
    INNER JOIN office AS B ON LEFT(A.tujuan_sj, 4) = B.id_office
    INNER JOIN department AS C ON RIGHT(A.tujuan_sj, 4) = C.id_department
    INNER JOIN users AS D ON A.user_sj = D.nik
    INNER JOIN office AS E ON LEFT(A.asal_sj, 4) = E.id_office
    INNER JOIN department AS F ON RIGHT(A.asal_sj, 4) = F.id_department
    WHERE LEFT(A.asal_sj, 4) = '$office' AND RIGHT(A.asal_sj, 4) = '$dept' AND LEFT(A.no_sj, 1) = 'M' AND LEFT(A.tanggal_sj, 10) BETWEEN '$awal' AND '$akhir' ORDER BY A.tanggal_sj ASC";
    $status = "All";
}
elseif ($status == "N") {
    $sql = "SELECT A.*, B.id_office AS id_off_to, B.office_name AS name_off_to, B.office_city AS city_off_to, C.id_department AS id_dept_to, C.department_name AS name_dpt_to, D.username, E.id_office AS id_off_from, E.office_name AS name_off_from, F.id_department AS id_dept_from, F.department_name AS name_dpt_from FROM surat_jalan AS A 
    INNER JOIN office AS B ON LEFT(A.tujuan_sj, 4) = B.id_office
    INNER JOIN department AS C ON RIGHT(A.tujuan_sj, 4) = C.id_department
    INNER JOIN users AS D ON A.user_sj = D.nik
    INNER JOIN office AS E ON LEFT(A.asal_sj, 4) = E.id_office
    INNER JOIN department AS F ON RIGHT(A.asal_sj, 4) = F.id_department
    WHERE LEFT(A.asal_sj, 4) = '$office' AND RIGHT(A.asal_sj, 4) = '$dept' AND LEFT(A.no_sj, 1) = 'M' AND A.status_terima_sj = 'N' AND LEFT(A.tanggal_sj, 10) BETWEEN '$awal' AND '$akhir' ORDER BY A.tanggal_sj ASC";
    $status = "Belum PTB";
}
elseif ($status == "Y") {
    $sql = "SELECT A.*, B.id_office AS id_off_to, B.office_name AS name_off_to, B.office_city AS city_off_to, C.id_department AS id_dept_to, C.department_name AS name_dpt_to, D.username, E.id_office AS id_off_from, E.office_name AS name_off_from, F.id_department AS id_dept_from, F.department_name AS name_dpt_from FROM surat_jalan AS A 
    INNER JOIN office AS B ON LEFT(A.tujuan_sj, 4) = B.id_office
    INNER JOIN department AS C ON RIGHT(A.tujuan_sj, 4) = C.id_department
    INNER JOIN users AS D ON A.user_sj = D.nik
    INNER JOIN office AS E ON LEFT(A.asal_sj, 4) = E.id_office
    INNER JOIN department AS F ON RIGHT(A.asal_sj, 4) = F.id_department
    WHERE LEFT(A.asal_sj, 4) = '$office' AND RIGHT(A.asal_sj, 4) = '$dept' AND LEFT(A.no_sj, 1) = 'M' AND A.status_terima_sj = 'Y' AND LEFT(A.tanggal_sj, 10) BETWEEN '$awal' AND '$akhir' ORDER BY A.tanggal_sj ASC";
    $status = "Sudah PTB";
}

$query = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query);

/*A4 width : 219mm*/

class PDF extends PDF_MC_Table {
    // Page header
    function Header()
    {
        
        global $header, $user, $awal, $akhir, $status;
        
        /*output the result*/
        $this->SetFont('Arial','',8);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header["id_off_from"]." - ".$header["name_off_from"],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header["name_dpt_from"],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);

        $this->Ln(4);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',12);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(63 ,7,'',0,0);
        $this->Cell(63 ,7,'REPORT DATA SURAT JALAN KIRIM BARANG',0,0, 'C');
        $this->Cell(63 ,7,'',0,1);

        $this->SetFont('Arial','',8);

        $this->Cell(190 ,5,'Status PTB : '.$status,0,1,'C');

        $this->Cell(190 ,5,'Periode : '.$awal." sd ".$akhir,0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,10,'No',1,0,'C');
        $this->Cell(40 ,10,'Nama Barang',1,0,'C');
        $this->Cell(27 ,10,'Serial Number',1,0,'C');
        $this->Cell(27 ,10,'Nomor Aktiva',1,0,'C');
        $this->Cell(10 ,10,'Qty',1,0,'C');
        $this->Cell(30 ,10,'Keterangan',1,0,'C');
        $this->Cell(19 ,10,'Status',1,0,'C');
        $this->Cell(27 ,10,'Penerima',1,1,'C');
       
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

$pdf->SetTitle('Listing Data Surat Jalan Kirim Barang');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($user) && isset($status)) {

    $query_h = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_h) > 0 ) {
        while($data_h = mysqli_fetch_assoc($query_h)){

            $nosj = isset($data_h["no_sj"]) ? $data_h["no_sj"] : null;

            $jenis_h = substr($nosj, 0, 1);

            $nosj_h = "NOMOR : ".substr($nosj, 1, 5);
            $date_h = "TANGGAL : ".$data_h["tanggal_sj"];
            $from_h = "TUJUAN : ".$data_h["id_off_to"]." - ".strtoupper($data_h["name_off_to"])." DEPT. ".strtoupper($data_h["name_dpt_to"]);
            $ket_h = "KETERANGAN : ".$data_h["ket_sj"];

            $pdf->SetFont('Arial','B',8);

            $pdf->Cell(190, 6, $nosj_h, 1, 1);
            $pdf->Cell(190, 6, $date_h, 1, 1);
            $pdf->Cell(190, 6, $from_h, 1, 1);
            $pdf->MultiCell(190, 6, $ket_h, 1);

            $sql_d = "SELECT A.*, B.NamaBarang, C.NamaJenis, D.username FROM detail_surat_jalan AS A
            INNER JOIN mastercategory AS B ON LEFT(A.pluid_sj, 6) = B.IDBarang
            INNER JOIN masterjenis AS C ON RIGHT(A.pluid_sj, 4) = C.IDJenis
            LEFT JOIN users AS D ON A.penerima_sj = D.nik
            WHERE A.head_no_sj = '$nosj' AND A.jenis_sj = '$jenis_h' ORDER BY A.head_no_sj ASC";

            $no = 1;

            $query_d = mysqli_query($conn, $sql_d);
            if(mysqli_num_rows($query_d) > 0 ) {
                while($data_d = mysqli_fetch_assoc($query_d)){
        
                    $desc = $data_d["pluid_sj"]." - ".$data_d["NamaBarang"]." ".$data_d["NamaJenis"]." ".$data_d["merk_sj"]." ".$data_d["tipe_sj"];
                    $sn = $data_d['sn_sj'] == '' ? '-' : $data_d['sn_sj'];
                    $at = $data_d['at_sj'] == '' ? '-' : $data_d['at_sj'];
                    $qty = $data_d['qty_sj'] == '' ? '-' : $data_d['qty_sj'];
                    $ket = $data_d['keterangan_sj'] == '' ? '-' : $data_d['keterangan_sj'];
                    $sts = $data_d['status_sj'] == 'Y' ? 'RECEIVED' : 'PENDING';
                    $penerima = $data_d['penerima_sj'] == NULL ? '-' : $data_d["penerima_sj"]." - ".strtoupper($data_d["username"]);
                    
                    $pdf->SetFont('Arial','',8);

                    $pdf->SetWidths(array(10, 40, 27, 27, 10, 30, 19, 27));
                    $pdf->Row(array($no++, $desc, $sn, $at, $qty, $ket, $sts, $penerima));

                }
            }
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

$pdf->Output("REPORT SJ KB ".$awal." ".$akhir.".pdf","I");

?>