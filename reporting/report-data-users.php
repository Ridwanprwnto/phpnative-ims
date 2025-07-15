<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$nik = mysqli_real_escape_string($conn, $_POST['nik-cetak']);
$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$group = mysqli_real_escape_string($conn, $_POST['group-cetak']);
$div = mysqli_real_escape_string($conn, $_POST['div-cetak']);

if ($dept == "ALL" && $div == "ALL") {
    $sql = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
    INNER JOIN office ON users.id_office = office.id_office
    LEFT JOIN department ON users.id_department = department.id_department
    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
    LEFT JOIN groups ON users.id_group = groups.id_group
    LEFT JOIN level ON users.id_level = level.id_level
    WHERE users.id_office = '$office' AND users.id_group NOT LIKE '$group' OR users.id_group IS NULL ORDER BY users.nik ASC";
}
elseif ($dept == "ALL" && $div != "ALL") {
    $sql = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
    INNER JOIN office ON users.id_office = office.id_office
    LEFT JOIN department ON users.id_department = department.id_department
    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
    LEFT JOIN groups ON users.id_group = groups.id_group
    LEFT JOIN level ON users.id_level = level.id_level
    WHERE users.id_office = '$office' AND users.id_divisi = '$div' AND users.id_group NOT LIKE '$group' OR users.id_group IS NULL ORDER BY users.nik ASC";
}
elseif ($dept != "ALL" && $div == "ALL") {
    $sql = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
    INNER JOIN office ON users.id_office = office.id_office
    LEFT JOIN department ON users.id_department = department.id_department
    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
    LEFT JOIN groups ON users.id_group = groups.id_group
    LEFT JOIN level ON users.id_level = level.id_level
    WHERE users.id_office = '$office' AND users.id_department = '$dept' AND users.id_group NOT LIKE '$group' OR users.id_group IS NULL ORDER BY users.nik ASC";
}
else {
    $sql = "SELECT users.*, office.id_office, office.office_name, department.*, divisi.*, groups.*, level.* FROM users
    INNER JOIN office ON users.id_office = office.id_office
    LEFT JOIN department ON users.id_department = department.id_department
    LEFT JOIN divisi ON users.id_divisi = divisi.id_divisi
    LEFT JOIN groups ON users.id_group = groups.id_group
    LEFT JOIN level ON users.id_level = level.id_level
    WHERE users.id_office = '$office' AND users.id_department = '$dept' AND users.id_divisi = '$div' AND users.id_group NOT LIKE '$group' OR users.id_group IS NULL ORDER BY users.nik ASC";
}

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

$title = "REPORT DATA USERS";

if (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header, $user, $office, $nik, $title;
            
            /*output the result*/

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

            $this->Cell(278, 6, $title, 0, 1, 'C');

            $this->Ln(4);

            // st font yang ingin anda gunakan
            $this->SetFont('Arial','B',9);

            // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
            // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
            $this->Cell(10, 10, 'NO', 1, 0, 'C');
            $this->Cell(20, 10, 'PLUID', 1, 0, 'C');
            $this->Cell(67, 10, 'NAMA BARANG', 1, 0, 'C');
            $this->Cell(24, 10, 'QTY PP', 1, 0, 'C');
            $this->Cell(24, 10, 'QTY TERIMA', 1, 0, 'C');
            $this->Cell(29, 10, 'TGL TERIMA', 1, 0, 'C');
            $this->Cell(35, 10, 'PENERIMA', 1, 0, 'C');
            $this->Cell(66, 10, 'KETERANGAN', 1, 1, 'C');
        
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

    $pdf->SetTitle('Report Data Users');

    $no = 1;

    if (isset($office) && isset($nik) && isset($user)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_array($query)){

                
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
elseif (isset($_POST["printexcell"])) {

    $row_xcl = 2;
    $no_xcl = 1;

    if (isset($office) && isset($nik) && isset($user)) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // sheet peratama
        $sheet->setTitle('Sheet 1');

        $sheet->getStyle('A1:O1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'USERNAME');
        $sheet->setCellValue('D1', 'FULLNAME');
        $sheet->setCellValue('E1', 'KANTOR');
        $sheet->setCellValue('F1', 'DEPARTMENT');
        $sheet->setCellValue('G1', 'DIVISI');
        $sheet->setCellValue('H1', 'LEVEL');
        $sheet->setCellValue('I1', 'GROUP');
        $sheet->setCellValue('J1', 'EMAIL');
        $sheet->setCellValue('K1', 'BORN DAY');
        $sheet->setCellValue('L1', 'GENDER');
        $sheet->setCellValue('M1', 'IP ADDRESS');
        $sheet->setCellValue('N1', 'AKSES IP ADDRESS');
        $sheet->setCellValue('O1', 'AKSES LOGIN');
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $noinduk = $data_xcl["nik"];
                $username = strtoupper($data_xcl["username"]);
                $fullname = isset($data_xcl["full_name"]) ? strtoupper($data_xcl["full_name"]) : "-";
                $kantor = $data_xcl["id_office"]." - ".strtoupper($data_xcl["office_name"]);
                $depart = isset($data_xcl["id_department"]) ? $data_xcl["id_department"]." - ".strtoupper($data_xcl["department_name"]) : "-";
                $divisi = isset($data_xcl["id_divisi"]) ? $data_xcl["id_divisi"]." - ".strtoupper($data_xcl["divisi_name"]) : "-";
                $level = isset($data_xcl["id_level"]) ? $data_xcl["level_name"] : "-";
                $groups = isset($data_xcl["group_name"]) ? $data_xcl["group_name"] : "-";
                $email = strtoupper($data_xcl["email"]);
                $born = isset($data_xcl["tgl_lahir"]) ? $data_xcl["tgl_lahir"] : "-";

                if ($data_xcl["gender"] == "L") {
                    $gender = "LAKI-LAKI";
                }
                elseif ($data_xcl["gender"] == "P") {
                    $gender = "PEREMPUAN";
                }
                else {
                    $gender = "";
                }

                $ip = isset($data_xcl["ip_address"]) ? $data_xcl["ip_address"] : "-";
                $akses = $data_xcl["akses_ip"] == 1 ? "SEMUA ALAMAT IP" : "IP TERDAFTAR";
                $status = $data_xcl["status"];

                $sheet->setCellValue('A'.$row_xcl, $no_xcl++);
                $sheet->setCellValue('B'.$row_xcl, $noinduk);
                $sheet->setCellValue('C'.$row_xcl, $username);
                $sheet->setCellValue('D'.$row_xcl, $fullname);
                $sheet->setCellValue('E'.$row_xcl, $kantor);
                $sheet->setCellValue('F'.$row_xcl, $depart);
                $sheet->setCellValue('G'.$row_xcl, $divisi);
                $sheet->setCellValue('H'.$row_xcl, $level);
                $sheet->setCellValue('I'.$row_xcl, $groups);
                $sheet->setCellValue('J'.$row_xcl, $email);
                $sheet->setCellValue('K'.$row_xcl, $born);
                $sheet->setCellValue('L'.$row_xcl, $gender);
                $sheet->setCellValue('M'.$row_xcl, $ip);
                $sheet->setCellValue('N'.$row_xcl, $akses);
                $sheet->setCellValue('O'.$row_xcl, $status);
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

?>