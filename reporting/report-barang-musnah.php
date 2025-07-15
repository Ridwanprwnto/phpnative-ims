<?php
/*call the EXCELL library*/
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$status = mysqli_real_escape_string($conn, $_POST['status-cetak']);

$barang = $_POST["barangcetak"];
$arrdata = implode(", ", $barang);

if ($arrdata == "ALL" && $status == "ALL") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' ORDER BY p3at.tgl_p3at ASC";
}
elseif ($arrdata == "ALL" && $status == "P3AT") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    INNER JOIN detail_p3at ON p3at.id_p3at = detail_p3at.id_head_p3at
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' AND detail_p3at.nomor_musnah IS NULL AND detail_p3at.tgl_approve IS NULL GROUP BY detail_p3at.id_head_p3at ORDER BY p3at.tgl_p3at ASC";
}
elseif ($arrdata == "ALL" && $status == "MUSNAH") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    INNER JOIN detail_p3at ON p3at.id_p3at = detail_p3at.id_head_p3at
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' AND detail_p3at.nomor_musnah IS NOT NULL AND detail_p3at.tgl_approve IS NOT NULL GROUP BY detail_p3at.id_head_p3at ORDER BY p3at.tgl_p3at ASC";
}
elseif ($arrdata != "ALL" && $status == "ALL") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    INNER JOIN detail_p3at ON p3at.id_p3at = detail_p3at.id_head_p3at
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' AND detail_p3at.pluid_p3at IN ($arrdata) GROUP BY detail_p3at.id_head_p3at ORDER BY p3at.tgl_p3at ASC";
}
elseif ($arrdata != "ALL" && $status == "P3AT") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    INNER JOIN detail_p3at ON p3at.id_p3at = detail_p3at.id_head_p3at
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' AND detail_p3at.pluid_p3at IN ($arrdata) AND detail_p3at.nomor_musnah IS NULL AND detail_p3at.tgl_approve IS NULL GROUP BY detail_p3at.id_head_p3at ORDER BY p3at.tgl_p3at ASC";
}
elseif ($arrdata != "ALL" && $status == "MUSNAH") {
    $sql = "SELECT p3at.*, office.*, department.*, users.username FROM p3at
    INNER JOIN office ON p3at.office_p3at = office.id_office
    INNER JOIN department ON p3at.dept_p3at = department.id_department
    INNER JOIN users ON p3at.user_p3at = users.nik
    INNER JOIN detail_p3at ON p3at.id_p3at = detail_p3at.id_head_p3at
    WHERE p3at.office_p3at = '$office' AND p3at.dept_p3at = '$dept' AND detail_p3at.pluid_p3at IN ($arrdata) AND detail_p3at.nomor_musnah IS NOT NULL AND detail_p3at.tgl_approve IS NOT NULL GROUP BY detail_p3at.id_head_p3at ORDER BY p3at.tgl_p3at ASC";
}

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

if (isset($_POST["printpdf"])) {
    
    // Memanggil file fpdf yang anda tadi simpan di folder htdoc
    require '../vendor/fpdf/fpdf.php';

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            
            global $header;
            global $office;
            global $dept;
            global $user;

            $this->SetFont('Arial','',10);

            $this->Cell(28 ,5,'Office',0,0);
            $this->Cell(48 ,5,': '.$office." - ".$header["office_name"],0,0);
            $this->Cell(126 ,5,'',0,0);
            $this->Cell(28 ,5,'Print Date',0,0);
            $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
            $this->Cell(28 ,5,'Department',0,0);
            $this->Cell(48 ,5,': '.$header["department_name"],0,0);
            $this->Cell(126 ,5,'',0,0);
            $this->Cell(28 ,5,'User',0,0);
            $this->Cell(48 ,5,': '.$user, 0, 1);

            $this->Ln(5);

            $this->SetFont('Arial','B',14);

            $this->Cell(278, 6, 'LISTING DATA PEMUSNAHAN BARANG INVENTARIS', 0, 1, 'C');

            $this->Ln(2);

            // st font yang ingin anda gunakan
            $this->SetFont('Arial','B',9);

            // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
            // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
            $this->Cell(12, 10, 'NO', 0, 0, 'C');
            $this->Cell(90, 10, 'NAMA BARANG', 0, 0, 'C');
            $this->Cell(30, 10, 'SERIAL NUMBER', 0, 0, 'C');
            $this->Cell(25, 10, 'NO AKTIVA', 0, 0, 'C');
            $this->Cell(30, 10, 'PEROLEHAN', 0, 0, 'C');
            $this->Cell(30, 10, 'NO PEMUSNAHAN', 0, 0, 'C');
            $this->Cell(58, 10, 'STATUS', 0, 1, 'C');

            $this->Cell(275, 0, '', 1, 1, 'L');
        
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

    // Ukuran kertas PDF
    $pdf = new PDF("L","mm","A4");
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Laporan Barang Musnah');
    $pdf->SetSubject('Daftar Barang Musnah');
    $pdf->SetKeywords('MINV');
    $pdf->SetCreator('IMS');

    $no = 1;

    if (isset($office) && isset($dept)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_assoc($query)){
                
                $id = $data["id_p3at"];

                $sql_detail = "SELECT detail_p3at.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM detail_p3at
                INNER JOIN mastercategory ON LEFT(detail_p3at.pluid_p3at, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_p3at.pluid_p3at, 4) = masterjenis.IDJenis
                WHERE detail_p3at.id_head_p3at = '$id'";

                $query_detail = mysqli_query($conn, $sql_detail);
                
                if(mysqli_num_rows($query_detail) > 0 ) {
                    
                    $pdf->SetFont('Arial','B',8);
                    
                    $pdf->Cell(275, 10, 'NO P3AT : '.$id.'  |  TGL : '.$data['tgl_p3at'].'  |  USER PROSES : '.$data['user_p3at'].' - '.strtoupper($data['username']), 0, 1, 'L');
                    
                    while($lihat = mysqli_fetch_assoc($query_detail)){

                        $pdf->SetFont('Arial','',8);
                        // Query yang ingin ditampilkan yang berada di database
                        $pdf->Cell(12, 8, $no, 0, 0, 'C');
                        $pdf->Cell(90, 8, $lihat['pluid_p3at'].' - '.$lihat['NamaBarang']." ".$lihat['NamaJenis']." ".$lihat['merk_p3at']." ".$lihat['tipe_p3at'], 0, 0,'C');
                        $pdf->Cell(30, 8, $lihat["sn_p3at"],0, 0, 'C');
                        $pdf->Cell(25, 8, $lihat['at_p3at'],0, 0, 'C');
                        $pdf->Cell(30, 8, $lihat['th_p3at'],0, 0,'C');
                        $pdf->Cell(30, 8, isset($lihat["nomor_musnah"]) == NULL ? "-" : $lihat["nomor_musnah"], 0, 0,'C');
                        $pdf->Cell(58, 8, isset($lihat["nomor_musnah"]) == NULL ? "P3AT" : "MUSNAH", 0, 1,'C');
                        
                        $no++;
                    }

                    $pdf->Cell(275, 0, '', 1, 1, 'L');
                    
                }
                else {
                    $msg = encrypt("datanotfound");
                    header("location: ../error.php?alert=$msg");
                    exit();
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

    // Nama file ketika di print
    $pdf->Output("DAFTAR-BARANG-PEMUSNAHAN".".pdf","I");
}
elseif (isset($_POST["printexcell"])) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $title = "DAFTAR-BARANG-PEMUSNAHAN";

    // sheet peratama
    $sheet->setTitle($title);

    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'NOMOR P3AT');
    $sheet->setCellValue('C1', 'NAMA BARANG');
    $sheet->setCellValue('D1', 'MERK');
    $sheet->setCellValue('E1', 'TIPE');
    $sheet->setCellValue('F1', 'SERIAL NUMBER');
    $sheet->setCellValue('G1', 'NOMOR AKTIVA');
    $sheet->setCellValue('H1', 'TAHUN PEROLEHAN');
    $sheet->setCellValue('I1', 'NOMOR PEMUSNAHAN');
    $sheet->setCellValue('J1', 'TGL PEMUSNAHAN');

    $row_xcl = 2;
    $no = 1;

    if (isset($office) && isset($dept)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_assoc($query)){
                
                $id = $data["id_p3at"];

                $sql_detail = "SELECT detail_p3at.*, mastercategory.NamaBarang, masterjenis.NamaJenis FROM detail_p3at
                INNER JOIN mastercategory ON LEFT(detail_p3at.pluid_p3at, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_p3at.pluid_p3at, 4) = masterjenis.IDJenis
                WHERE detail_p3at.id_head_p3at = '$id'";

                $query_detail = mysqli_query($conn, $sql_detail);
                
                if(mysqli_num_rows($query_detail) > 0 ) {
                    
                    while($data_detail = mysqli_fetch_assoc($query_detail)) {

                        $sheet->setCellValue('A'.$row_xcl, $no++);
                        $sheet->setCellValue('B'.$row_xcl, $data_detail['id_head_p3at']);
                        $sheet->setCellValue('C'.$row_xcl, $data_detail['pluid_p3at']." - ".$data_detail['NamaBarang']." ".$data_detail['NamaJenis']);
                        $sheet->setCellValue('D'.$row_xcl, $data_detail["merk_p3at"]);
                        $sheet->setCellValue('E'.$row_xcl, $data_detail["tipe_p3at"]);
                        $sheet->setCellValue('F'.$row_xcl, $data_detail["sn_p3at"]);
                        $sheet->setCellValue('G'.$row_xcl, $data_detail["at_p3at"]);
                        $sheet->setCellValue('H'.$row_xcl, $data_detail["th_p3at"]);
                        $sheet->setCellValue('I'.$row_xcl, $data_detail["nomor_musnah"]);
                        $sheet->setCellValue('J'.$row_xcl, $data_detail["tgl_approve"]);
                        $row_xcl++;

                    }
                    
                }
                else {
                    $msg = encrypt("datanotfound");
                    header("location: ../error.php?alert=$msg");
                    exit();
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

?>