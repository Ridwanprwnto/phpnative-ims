<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$data = mysqli_real_escape_string($conn, $_POST["data-cetak"]);
$barang = $_POST["barangcetak"];
$cnd = $_POST["kondisicetak"];
$arrdata = implode(", ", $barang);
$arrdatacnd = implode(", ", $cnd);
$kepdat = $office.$dept;
$datadoc = $data == 'DAT' ? 'Kepemilikan' : 'Lokasi';

if ($data == "LOK" && $arrdata == "ALL" && $arrdatacnd == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.ba_id_office = '$office' AND A.ba_id_department = '$dept' AND A.kondisi NOT LIKE '$arrcond[5]' ORDER BY A.pluid ASC";
}
elseif ($data == "DAT" && $arrdata == "ALL" && $arrdatacnd == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.dat_asset = '$kepdat' AND A.kondisi NOT LIKE '$arrcond[5]' ORDER BY A.pluid ASC";
}
elseif ($data == "LOK" && $arrdata == "ALL" && $arrdatacnd != "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.ba_id_office = '$office' AND A.ba_id_department = '$dept' AND A.kondisi IN ($arrdatacnd) ORDER BY A.pluid ASC";
}
elseif ($data == "DAT" && $arrdata == "ALL" && $arrdatacnd != "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.dat_asset = '$kepdat' AND A.kondisi IN ($arrdatacnd) ORDER BY A.pluid ASC";
}
elseif ($data == "LOK" && $arrdata != "ALL" && $arrdatacnd == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.ba_id_office = '$office' AND A.ba_id_department = '$dept' AND A.pluid IN ($arrdata) AND A.kondisi NOT LIKE '$arrcond[5]' ORDER BY A.pluid ASC";
}
elseif ($data == "DAT" && $arrdata != "ALL" && $arrdatacnd == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.dat_asset = '$kepdat' AND A.pluid IN ($arrdata) AND A.kondisi NOT LIKE '$arrcond[5]' ORDER BY A.pluid ASC";
}
elseif ($data == "LOK" && $arrdata != "ALL" && $arrdatacnd != "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.ba_id_office = '$office' AND A.ba_id_department = '$dept' AND A.pluid IN ($arrdata) AND A.kondisi IN ($arrdatacnd) ORDER BY A.pluid ASC";
}
elseif ($data == "DAT" && $arrdata != "ALL" && $arrdatacnd != "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.username, H.office_name AS kep_office, I.department_name AS kep_dept FROM barang_assets AS A
    INNER JOIN office AS B ON A.ba_id_office = B.id_office
    INNER JOIN department AS C ON A.ba_id_department = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid, 4) = E.IDJenis
    INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
    LEFT JOIN users AS G ON A.user_asset = G.nik
    LEFT JOIN office AS H ON LEFT(A.dat_asset, 4) = H.id_office
    LEFT JOIN department AS I ON RIGHT(A.dat_asset, 4) = I.id_department
    WHERE A.dat_asset = '$kepdat' AND A.pluid IN ($arrdata) AND A.kondisi IN ($arrdatacnd) ORDER BY A.pluid ASC";
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

$title = $office." - LAPORAN DATA PERALATAN INVENTARIS";

if (isset($_POST["printexcell"])) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // sheet peratama
    $sheet->setTitle('Sheet 1');

    $sheet->getStyle('A1:L1')->getFont()->setBold(true);

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'KEPEMILIKAN ASSET');
    $sheet->setCellValue('C1', 'LOKASI');
    $sheet->setCellValue('D1', 'NAMA BARANG');
    $sheet->setCellValue('E1', 'SERIAL NUMBER');
    $sheet->setCellValue('F1', 'NO AKTIVA');
    $sheet->setCellValue('G1', 'NO LAMBUNG');
    $sheet->setCellValue('H1', 'STATUS');
    $sheet->setCellValue('I1', 'USER');
    $sheet->setCellValue('J1', 'REFERENSI');
    $sheet->setCellValue('K1', 'DATE MODIFIED');
    $sheet->setCellValue('L1', 'PENEMPATAN');

    $row_xcl = 2;
    $no = 1;

    if (isset($office) && isset($dept) && isset($barang)) {
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $sheet->setCellValue('A'.$row_xcl, $no++);
                $sheet->setCellValue('B'.$row_xcl, isset($data_xcl["dat_asset"]) ? substr($data_xcl["dat_asset"], 0, 4)." - ".strtoupper($data_xcl["kep_office"])." ".strtoupper($data_xcl["kep_dept"]) : "-");
                $sheet->setCellValue('C'.$row_xcl, $data_xcl["ba_id_office"]." - ".strtoupper($data_xcl["office_name"]." ".$data_xcl["department_name"]));
                $sheet->setCellValue('D'.$row_xcl, $data_xcl['pluid']." - ".$data_xcl['NamaBarang']." ".$data_xcl['NamaJenis']." ".$data_xcl['ba_merk']." ".$data_xcl['ba_tipe']);
                $sheet->setCellValue('E'.$row_xcl, $data_xcl["sn_barang"]);
                $sheet->setCellValue('F'.$row_xcl, $data_xcl["no_at"]);
                $sheet->setCellValue('G'.$row_xcl, $data_xcl["no_lambung"]);
                $sheet->setCellValue('H'.$row_xcl, $data_xcl["kondisi"]." - ".$data_xcl["kondisi_name"]);
                $sheet->setCellValue('I'.$row_xcl, $data_xcl["user_asset"]." - ".strtoupper($data_xcl["username"]));
                $sheet->setCellValue('J'.$row_xcl, $data_xcl["referensi_asset"]);
                $sheet->setCellValue('K'.$row_xcl, $data_xcl["modified_asset"]);
                $sheet->setCellValue('L'.$row_xcl, strtoupper($data_xcl["posisi"]));
                $row_xcl++;
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
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);
    
    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename='.$title.'.xlsx');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

}
elseif (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table {
        // Page header
        function Header()
        {
            
            global $header;
            global $user;
            global $datadoc;
    
            $this->SetFont('Arial','',10);
    
            $this->Cell(28 ,5,'Office',0,0);
            $this->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
            $this->Cell(38 ,5,'',0,0);
            $this->Cell(28 ,5,'Print Date',0,0);
            $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
            
            $this->Cell(28 ,5,'Department',0,0);
            $this->Cell(48 ,5,': '.$header['department_name'],0,0);
            $this->Cell(38 ,5,'',0,0);
            $this->Cell(28 ,5,'User',0,0);
            $this->Cell(48 ,5,': '.$user, 0, 1);
            
            $this->Ln(3);
            
            $this->SetFont('Arial','B',12);
            
            $this->Cell(190, 8, 'LISTING DATA PERALATAN INVENTARIS', 0, 1, 'C');
            
            $this->SetFont('Arial','',10);

            $this->Cell(190, 6, 'Data By : '.$datadoc, 0, 1, 'C');
            
            $this->Ln(2);
            
            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(10 ,8,'No',1,0,'C');
            $this->Cell(37 ,8,'Nama Barang',1,0,'C');
            $this->Cell(25 ,8,'SN',1,0,'C');
            $this->Cell(20 ,8,'No Aktiva',1,0,'C');
            $this->Cell(20 ,8,'Nomor',1,0,'C');
            $this->Cell(20 ,8,'Status',1,0,'C');
            $this->Cell(20 ,8,'Modified',1,0,'C');
            $this->Cell(38 ,8,'Penempatan',1,1,'C');
            /*end of line*/
           
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
    
    /*A4 width : 219mm*/
    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Report Data Peralatan Inventaris');
    $pdf->SetSubject('Peralatan Inventaris');
    $pdf->SetKeywords('PINV');
    $pdf->SetCreator('IMS');
    
    $start_x = $pdf->GetX(); //initial x (start of column position)
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    
    $pdf->SetFont('Arial','',8);
    
    $no = 1;
    if (isset($office) && isset($dept) && isset($barang)) {
    
        $query_pdf = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_pdf) > 0 ) {
    
            while($data_pdf = mysqli_fetch_assoc($query_pdf)){
    
                $desc = $data_pdf['NamaBarang']." ".$data_pdf['NamaJenis']." ".$data_pdf['ba_merk']." ".$data_pdf['ba_tipe'];
                $sn = $data_pdf["sn_barang"];
                $at = $data_pdf["no_at"];
                $nomor = $data_pdf["no_lambung"] == '' ? '-' : $data_pdf["no_lambung"];
                $kond = $data_pdf["kondisi"]." - ".$data_pdf["kondisi_name"];
                $modified = $data_pdf["modified_asset"];
                $posisi = strtoupper($data_pdf["posisi"]);
    
                $pdf->SetWidths(array(10, 37, 25, 20, 20, 20, 20, 38));
                $pdf->Row(array($no++, $desc, $sn, $at, $nomor, $kond, $modified, $posisi));
    
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
    $pdf->Output($title.".pdf","I");

}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
?>