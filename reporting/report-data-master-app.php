<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/code128.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$base = mysqli_real_escape_string($conn, $_POST["base-cetak"]);
$app = $_POST["appcetak"];
$use = $_POST["usecetak"];
$arrapp = implode(", ", $app);
$arruse = implode(", ", $use);

if ($base == "ALL" && $arrapp == "ALL" && $arruse == "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' ORDER BY B.name_app ASC";
}
elseif ($base == "ALL" && $arrapp == "ALL" && $arruse != "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND A.use_ver_app IN ($arruse) ORDER BY B.name_app ASC";
}
elseif ($base == "ALL" && $arrapp != "ALL" && $arruse != "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.code_app IN ($arrapp) AND A.use_ver_app IN ($arruse) ORDER BY B.name_app ASC";
}
elseif ($base != "ALL" && $arrapp != "ALL" && $arruse != "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.basis_app = '$base' AND B.code_app IN ($arrapp) AND A.use_ver_app IN ($arruse) ORDER BY B.name_app ASC";
}
elseif ($base != "ALL" && $arrapp != "ALL" && $arruse == "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.basis_app = '$base' AND B.code_app IN ($arrapp) ORDER BY B.name_app ASC";
}
elseif ($base != "ALL" && $arrapp == "ALL" && $arruse == "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.basis_app = '$base' ORDER BY B.name_app ASC";
}
elseif ($base != "ALL" && $arrapp == "ALL" && $arruse != "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.basis_app = '$base' AND A.use_ver_app IN ($arruse) ORDER BY B.name_app ASC";
}
elseif ($base == "ALL" && $arrapp != "ALL" && $arruse == "ALL") {
    $sql = "SELECT A.*, B.office_app, B.code_app, B.name_app, B.basis_app, B.peruntukan_app, C.id_office, C.office_name, D.id_department, D.department_name FROM version_app AS A
    INNER JOIN master_app AS B ON A.id_code_app = B.code_app
    INNER JOIN office AS C ON B.office_app = C.id_office 
    INNER JOIN department AS D ON B.dept_app = D.id_department 
    WHERE B.office_app = '$office' AND B.dept_app = '$dept' AND B.code_app IN ($arrapp) ORDER BY B.name_app ASC";
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

$title = $office." - REPORT DATA MASTER APPLICATION";

if (isset($_POST["printexcell"])) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // sheet peratama
    $sheet->setTitle('Sheet 1');

    $sheet->getStyle('A1:L1')->getFont()->setBold(true);

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'ID');
    $sheet->setCellValue('C1', 'BASE');
    $sheet->setCellValue('D1', 'APPLICATION NAME');
    $sheet->setCellValue('E1', 'REALESE');
    $sheet->setCellValue('F1', 'VERSION');
    $sheet->setCellValue('G1', 'PENGGUNAAN');
    $sheet->setCellValue('H1', 'PERUNTUKAN');
    $sheet->setCellValue('I1', 'SOURCE');
    $sheet->setCellValue('J1', 'INFORMATION');

    $row_xcl = 2;
    $no = 1;

    if (isset($user) && isset($office) && isset($dept)) {
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $sheet->setCellValue('A'.$row_xcl, $no++);
                $sheet->setCellValue('B'.$row_xcl, $data_xcl["code_app"]);
                $sheet->setCellValue('C'.$row_xcl, $data_xcl["basis_app"]);
                $sheet->setCellValue('D'.$row_xcl, $data_xcl['name_app']);
                $sheet->setCellValue('E'.$row_xcl, $data_xcl["rilis_ver_app"]);
                $sheet->setCellValue('F'.$row_xcl, $data_xcl["version_ver_app"]);
                $sheet->setCellValue('G'.$row_xcl, $data_xcl["use_ver_app"] == 'Y' ? 'EXIST' : 'OLD');
                $sheet->setCellValue('H'.$row_xcl, $data_xcl["peruntukan_app"]);
                $sheet->setCellValue('I'.$row_xcl, $data_xcl["basis_app"] == 'WEB' ? strtoupper($data_xcl["source_ver_app"]) : '-');
                $sheet->setCellValue('J'.$row_xcl, $data_xcl["fitur_ver_app"]);
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

    class PDF extends PDF_Code128 {
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
            
            $this->Cell(190, 8, 'LISTING DATA MASTER APPLICATION', 0, 1, 'C');
            
            $this->SetFont('Arial','',10);
            
            $this->Ln(2);
            
            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(10 ,8,'No',1,0,'C');
            $this->Cell(17 ,8,'ID',1,0,'C');
            $this->Cell(20 ,8,'Base',1,0,'C');
            $this->Cell(55 ,8,'Application Name',1,0,'C');
            $this->Cell(20 ,8,'Realese',1,0,'C');
            $this->Cell(15 ,8,'Version',1,0,'C');
            $this->Cell(15 ,8,'Use',1,0,'C');
            $this->Cell(38 ,8,'Peruntukan',1,1,'C');
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

    class PDF_MC_Table extends PDF
    {
        protected $widths;
        protected $aligns;

        function SetWidths($w)
        {
            // Set the array of column widths
            $this->widths = $w;
        }

        function SetAligns($a)
        {
            // Set the array of column alignments
            $this->aligns = $a;
        }

        function Row($data)
        {
            // Calculate the height of the row
            $nb = 0;
            for($i=0;$i<count($data);$i++)
                $nb = max($nb,$this->NbLines($this->widths[$i],$data[$i]));
            $h = 5*$nb;
            // Issue a page break first if needed
            $this->CheckPageBreak($h);
            // Draw the cells of the row
            for($i=0;$i<count($data);$i++)
            {
                $w = $this->widths[$i];
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                // Save the current position
                $x = $this->GetX();
                $y = $this->GetY();
                // Draw the border
                $this->Rect($x,$y,$w,$h);
                // Print the text
                $this->MultiCell($w,5,$data[$i],0,$a);
                // Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            // Go to the next line
            $this->Ln($h);
        }

        function CheckPageBreak($h)
        {
            // If the height h would cause an overflow, add a new page immediately
            if($this->GetY()+$h>$this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }

        function NbLines($w, $txt)
        {
            // Compute the number of lines a MultiCell of width w will take
            if(!isset($this->CurrentFont))
                $this->Error('No font has been set');
            $cw = $this->CurrentFont['cw'];
            if($w==0)
                $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',(string)$txt);
            $nb = strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
                $nb--;
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;
            while($i<$nb)
            {
                $c = $s[$i];
                if($c=="\n")
                {
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                    continue;
                }
                if($c==' ')
                    $sep = $i;
                $l += $cw[$c];
                if($l>$wmax)
                {
                    if($sep==-1)
                    {
                        if($i==$j)
                            $i++;
                    }
                    else
                        $i = $sep+1;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                }
                else
                    $i++;
            }
            return $nl;
        }
    }
    
    /*A4 width : 219mm*/
    $pdf = new PDF_MC_Table('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Report Data Master Application');
    $pdf->SetSubject('Master Application');
    $pdf->SetKeywords('MASTERAPP');
    $pdf->SetCreator('IMS');
    
    $start_x = $pdf->GetX(); //initial x (start of column position)
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    
    $pdf->SetFont('Arial','',8);
    
    $no = 1;
    if (isset($user) && isset($office) && isset($dept)) {
    
        $query_pdf = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_pdf) > 0 ) {
    
            while($data_pdf = mysqli_fetch_assoc($query_pdf)){

                $id_app = $data_pdf["code_app"];
                $base_app = $data_pdf["basis_app"];
                $name_app = $data_pdf["name_app"];
                $rilis_app = $data_pdf["rilis_ver_app"];
                $version_app = $data_pdf["version_ver_app"];
                $use_app = $data_pdf["use_ver_app"] == 'Y' ? 'EXIST' : 'OLD';
                $peruntukan_app = $data_pdf["peruntukan_app"];
                $bcd_app = $data_pdf["basis_app"] == 'WEB' ? $data_pdf["source_ver_app"] : '';
                
                $pdf->SetWidths(array(10, 17, 20, 55, 20, 15, 15, 38));
                $pdf->Row(array(
                    $no++, $id_app, $base_app, $name_app, $rilis_app, $version_app, $use_app, $peruntukan_app
                ));
                
                if (!empty($bcd_app)) {
                    $pdf->Cell(190, 11, '', 1, 1, 'C');
                    $pdf->Cell(190, -11, '', 1, 1, 'C');
                    $pdf->SetXY($pdf->GetX() + 10, $pdf->GetY() + 1); // Sesuaikan posisi x dan y
                    $pdf->Code128($pdf->GetX(), $pdf->GetY(), $bcd_app, 170, 5);
                    $pdf->Cell(190, 5, '', 0, 1, 'L');
                    $pdf->Cell(190, 5, strtoupper($bcd_app), 0, 1, 'C');
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
    $pdf->Output($title.".pdf","I");

}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
?>