<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST["user-ip"]);
$office = mysqli_real_escape_string($conn, $_POST["office-ip"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-ip"]);
$seg = mysqli_real_escape_string($conn, $_POST["segment-ip"]);
$status = mysqli_real_escape_string($conn, $_POST["status-ip"]);

if ($seg == "ALL" && $status == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.* FROM ip_address AS A
    INNER JOIN office AS B ON A.office_ipad = B.id_office
    INNER JOIN department AS C ON A.dept_ipad = C.id_department
    INNER JOIN ip_segment AS D ON A.seg_ipad = D.id_iseg
    WHERE A.office_ipad = '$office' AND A.dept_ipad = '$dept' ORDER BY A.name_ipad ASC";
}
elseif ($seg == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.* FROM ip_address AS A
    INNER JOIN office AS B ON A.office_ipad = B.id_office
    INNER JOIN department AS C ON A.dept_ipad = C.id_department
    INNER JOIN ip_segment AS D ON A.seg_ipad = D.id_iseg
    WHERE A.office_ipad = '$office' AND A.dept_ipad = '$dept' AND A.status_ipad = '$status' ORDER BY A.name_ipad ASC";
}
elseif ($status == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.* FROM ip_address AS A
    INNER JOIN office AS B ON A.office_ipad = B.id_office
    INNER JOIN department AS C ON A.dept_ipad = C.id_department
    INNER JOIN ip_segment AS D ON A.seg_ipad = D.id_iseg
    WHERE A.office_ipad = '$office' AND A.dept_ipad = '$dept' AND A.seg_ipad = '$seg' ORDER BY A.name_ipad ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.* FROM ip_address AS A
    INNER JOIN office AS B ON A.office_ipad = B.id_office
    INNER JOIN department AS C ON A.dept_ipad = C.id_department
    INNER JOIN ip_segment AS D ON A.seg_ipad = D.id_iseg
    WHERE A.office_ipad = '$office' AND A.dept_ipad = '$dept' AND A.seg_ipad = '$seg' AND A.status_ipad = '$status' ORDER BY A.name_ipad ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

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
        
        $this->Cell(190, 10, 'REKAP DATA IP ADDRESS', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        
        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'No',1,0,'C');
        $this->Cell(50 ,8,'Segment',1,0,'C');
        $this->Cell(30 ,8,'IP Address',1,0,'C');
        $this->Cell(80 ,8,'Host / User',1,0,'C');
        $this->Cell(20 ,8,'Status',1,1,'C');
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
$pdf->SetTitle('Report Data IP Address');
$pdf->SetSubject('IP Address');
$pdf->SetKeywords('IP');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($office) && isset($dept) && isset($seg) && isset($status)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $segment = $data['name_iseg'];
            $ip = $data["ip_ipad"];
            $name = $data["name_ipad"];
            $sts = $data["status_ipad"] == "Y" ? "Active" : "Non Active";

            $pdf->SetWidths(array(10, 50, 30, 80, 20));
            $pdf->Row(array($no++, $segment, $ip, $name, $sts));

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
$pdf->Output("REPORT DATA IP ADDRESS.pdf","I");

?>