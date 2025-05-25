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

$user = mysqli_real_escape_string($conn, $_POST["user-dvr"]);
$office = mysqli_real_escape_string($conn, $_POST["office-dvr"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-dvr"]);
$id = mysqli_real_escape_string($conn, $_POST["server-dvr"]);

if ($id == "ALL") {
    $sql = "SELECT A.*, B.kode_area_cctv, B.ip_area_cctv, C.id_office, C.office_name, D.department_name, E.divisi_name, F.name_bag_cctv FROM layout_cctv AS A
    INNER JOIN area_cctv AS B ON A.head_id_area_cctv = B.id_area_cctv
    INNER JOIN office AS C ON B.office_area_cctv = C.id_office
    INNER JOIN department AS D ON B.dept_area_cctv = D.id_department
    INNER JOIN divisi AS E ON B.divisi_area_cctv = E.id_divisi
    INNER JOIN bagian_cctv AS F ON A.kode_head_bag_cctv = F.kode_bag_cctv
    WHERE B.office_area_cctv = '$office' AND B.dept_area_cctv = '$dept' ORDER BY A.head_id_area_cctv ASC";
}
else {
    $sql = "SELECT A.*, B.kode_area_cctv, B.ip_area_cctv, C.id_office, C.office_name, D.department_name, E.divisi_name, F.name_bag_cctv FROM layout_cctv AS A
    INNER JOIN area_cctv AS B ON A.head_id_area_cctv = B.id_area_cctv
    INNER JOIN office AS C ON B.office_area_cctv = C.id_office
    INNER JOIN department AS D ON B.dept_area_cctv = D.id_department
    INNER JOIN divisi AS E ON B.divisi_area_cctv = E.id_divisi
    INNER JOIN bagian_cctv AS F ON A.kode_head_bag_cctv = F.kode_bag_cctv
    WHERE B.office_area_cctv = '$office' AND B.dept_area_cctv = '$dept' AND A.head_id_area_cctv = '$id' ORDER BY A.channel_lay_cctv ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

if (isset($_POST["printpdf"])) {
    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header;
            global $user;
    
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
            
            $this->Cell(190, 10, 'REKAP DATA TITIK LAYOUT PENEMPATAN CCTV', 0, 1, 'C');
            
            $this->SetFont('Arial','',10);
            
            $this->Ln(3);
            
            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(10 ,8,'No',1,0,'C');
            $this->Cell(30 ,8,'Host / IP Address',1,0,'C');
            $this->Cell(30 ,8,'Area',1,0,'C');
            $this->Cell(18 ,8,'Port',1,0,'C');
            $this->Cell(18 ,8,'Kode',1,0,'C');
            $this->Cell(18 ,8,'Jenis',1,0,'C');
            $this->Cell(66 ,8,'Titik Penempatan',1,1,'C');
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
    $pdf->SetTitle('Report Data Titik Layout CCTV');
    $pdf->SetSubject('Data CCTV');
    $pdf->SetKeywords('CCTV');
    $pdf->SetCreator('IMS');
    
    $start_x = $pdf->GetX(); //initial x (start of column position)
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    
    $pdf->SetFont('Arial','',8);
    
    $no = 1;
    if (isset($office) && isset($dept) && isset($id)) {
    
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {
    
            while($data = mysqli_fetch_assoc($query)){
    
                $ip = $data["ip_area_cctv"];
                $area = $data["kode_area_cctv"]." - ".$data["divisi_name"];
                $ch = $data["channel_lay_cctv"];
                $lay = $data["kode_head_bag_cctv"].".".$data["no_lay_cctv"];
                $jenis = $data["jenis_lay_cctv"];
                $titik = $data["penempatan_lay_cctv"];
    
                $pdf->SetWidths(array(10, 30, 30, 18, 18, 18, 66));
                $pdf->Row(array($no++, $ip, $area, $ch, $lay, $jenis, $titik));
    
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
    $pdf->Output("REPORT DATA TITIK LAYOUT PENEMPATAN CCTV.pdf","I");
}
elseif (isset($_POST["printexcell"])) {
    $row_xcl = 2;
    $no_xcl = 1;

    if (isset($office) && isset($dept) && isset($user)) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // sheet peratama
        $sheet->setTitle('Sheet 1');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'IP SERVER CCTV');
        $sheet->setCellValue('C1', 'AREA CCTV');
        $sheet->setCellValue('D1', 'PORT');
        $sheet->setCellValue('E1', 'JENIS CCTV');
        $sheet->setCellValue('F1', 'AREA PENEMPATAN');
        $sheet->setCellValue('G1', 'KODE');
        $sheet->setCellValue('H1', 'TITIK PENEMPATAN');
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $ip = $data_xcl["ip_area_cctv"];
                $area = $data_xcl["kode_area_cctv"]." - ".$data_xcl["divisi_name"];
                $ch = $data_xcl["channel_lay_cctv"];
                $jenis = $data_xcl["jenis_lay_cctv"];
                $bag_cctv = $data_xcl["name_bag_cctv"];
                $lay = $data_xcl["kode_head_bag_cctv"].".".$data_xcl["no_lay_cctv"];
                $titik = $data_xcl["penempatan_lay_cctv"];

                $sheet->setCellValue('A'.$row_xcl, $no_xcl++);
                $sheet->setCellValue('B'.$row_xcl, $ip);
                $sheet->setCellValue('C'.$row_xcl, $area);
                $sheet->setCellValue('D'.$row_xcl, $ch);
                $sheet->setCellValue('E'.$row_xcl, $jenis);
                $sheet->setCellValue('F'.$row_xcl, $bag_cctv);
                $sheet->setCellValue('G'.$row_xcl, $lay);
                $sheet->setCellValue('H'.$row_xcl, $titik);
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
    header('Content-Disposition: attachment; filename=REPORT DATA TITIK LAYOUT PENEMPATAN CCTV.xlsx');
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