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
$start = mysqli_real_escape_string($conn, $_POST["start-cetak"]);
$end = mysqli_real_escape_string($conn, $_POST["end-cetak"]);
$div = $_POST["divcetak"];
$arrdata = implode(", ", $div);

if ($arrdata == "ALL") {

    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' GROUP BY A.div_plg_cctv ORDER BY A.div_plg_cctv ASC";
    
}
else {

    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' AND A.div_plg_cctv IN ($arrdata) GROUP BY A.div_plg_cctv ORDER BY A.div_plg_cctv ASC";
    
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc(mysqli_query($conn, $sql));

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $start, $end;

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
        
        $this->Cell(199, 10, 'SUMMARY DATA PELANGGARAN CCTV PER BAGIAN', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,20,'No',1,0,'C');
        $this->Cell(54 ,20,'Bagian',1,0,'C');
        $this->Cell(108 ,10,'ID Kategori Pelanggaran',1,0,'C');
        $this->Cell(18 ,20,'Sub Total',1,1,'C');
        $this->Cell(12 ,0,'',0,0,'C');
        $this->Cell(54 ,0,'',0,0,'C');
        $this->Cell(18 ,-10,'1',1,0,'C');
        $this->Cell(18 ,-10,'2',1,0,'C');
        $this->Cell(18 ,-10,'3',1,0,'C');
        $this->Cell(18 ,-10,'4',1,0,'C');
        $this->Cell(18 ,-10,'5',1,0,'C');
        $this->Cell(18 ,-10,'6',1,0,'C');
        $this->Cell(18 ,0,'',0,1,'C');
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
$pdf->SetTitle('Report Data Summary Pelanggaran CCTV Per Bagian');
$pdf->SetSubject('Pelanggaran CCTV By User');
$pdf->SetKeywords('CCTV');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0, 0, 0, 0, 0, 0];
$cellHeight = 10;

if (isset($office) && isset($dept) && isset($start) && isset($end) && isset($div)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $div_detail = $data["div_plg_cctv"];

            $sql_1 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_1 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '1'";
            $cat1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_1));

            $sql_2 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_2 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '2'";
            $cat2 = mysqli_fetch_assoc(mysqli_query($conn, $sql_2));
            
            $sql_3 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_3 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '3'";
            $cat3 = mysqli_fetch_assoc(mysqli_query($conn, $sql_3));

            $sql_4 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_4 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '4'";
            $cat4 = mysqli_fetch_assoc(mysqli_query($conn, $sql_4));

            $sql_5 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_5 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '5'";
            $cat5 = mysqli_fetch_assoc(mysqli_query($conn, $sql_5));

            $sql_6 ="SELECT div_plg_cctv, COUNT(id_plg_cctv) AS total_6 FROM pelanggaran_cctv AS A WHERE office_plg_cctv = '$office' AND dept_plg_cctv = '$dept' AND div_plg_cctv = '$div_detail' AND DATE(tgl_plg_cctv) BETWEEN '$start' AND '$end' AND LEFT(ctg_plg_cctv, 1) = '6'";
            $cat6 = mysqli_fetch_assoc(mysqli_query($conn, $sql_6));

            $div_name_d = $data["divisi_name"];
            $tot1 = $cat1["total_1"];
            $tot2 = $cat2["total_2"];
            $tot3 = $cat3["total_3"];
            $tot4 = $cat4["total_4"];
            $tot5 = $cat5["total_5"];
            $tot6 = $cat6["total_6"];
            $total = $tot1 + $tot2 + $tot3 + $tot4 + $tot5 + $tot6;

            $pdf->SetWidths(array(12, 54, 18, 18, 18, 18, 18, 18, 18));
            $pdf->Row(array($no++, $div_name_d, $tot1, $tot2, $tot3, $tot4, $tot5, $tot6, $total));
            
            $to_satu = ($nol[0]+=$tot1);
            $to_dua = ($nol[1]+=$tot2);
            $to_tiga = ($nol[2]+=$tot3);
            $to_empat = ($nol[3]+=$tot4);
            $to_lima = ($nol[4]+=$tot5);
            $to_enam = ($nol[5]+=$tot6);
            $gd_total = ($nol[6]+=$total);
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66 ,$cellHeight,'Grand Total',1,0,'C');
        $pdf->Cell(18 ,$cellHeight,$to_satu,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_dua,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_tiga,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_empat,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_lima,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_enam,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$gd_total++,1,1,'L');

        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',8);

        $pdf->Cell(12 ,10,'ID',1,0,'C');
        $pdf->Cell(180 ,10,'Kategori Pelanggaran',1,1,'C');

        $sql_cat = "SELECT * FROM category_pelanggaran";
        $query_cat = mysqli_query($conn, $sql_cat);

        if(mysqli_num_rows($query_cat) > 0 ) {

            while($data_cat = mysqli_fetch_assoc($query_cat)){

            $idc = $data_cat["id_ctg_plg"];
            $category = $data_cat["name_ctg_plg"];

            $pdf->SetWidths(array(12, 180));
            $pdf->Row(array($idc, $category));
            
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
$pdf->Output("LAPORAN SUMMARY DATA PELANGGARAN CCTV PER BAGIAN.pdf","I");

?>