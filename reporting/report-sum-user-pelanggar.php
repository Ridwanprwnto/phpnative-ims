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
$pelanggar = mysqli_real_escape_string($conn, $_POST["pelanggarcetak"]);

if ($pelanggar == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, J.full_name, user_pelanggaran_cctv.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' GROUP BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ORDER BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ASC";
}
elseif ($pelanggar == "IDENTITAS TIDAK DIKETAHUI" || $pelanggar == "BAGIAN LAIN") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, J.full_name, user_pelanggaran_cctv.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' AND user_pelanggaran_cctv.username_plg_cctv = '$pelanggar' GROUP BY user_pelanggaran_cctv.username_plg_cctv ORDER BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, J.full_name, user_pelanggaran_cctv.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
    INNER JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
    INNER JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
    INNER JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
    INNER JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv = 'Y' AND LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) = '$pelanggar' GROUP BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10)";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

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
        
        $this->Cell(199, 10, 'SUMMARY DATA PELANGGARAN CCTV PER USER', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);
        $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

        $this->Ln(3);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,20,'No',1,0,'C');
        $this->Cell(54 ,20,'User Pelanggar',1,0,'C');
        $this->Cell(108 ,10,'No Kategori Pelanggaran',1,0,'C');
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
$pdf->SetTitle('Report Data Summary Pelanggaran CCTV Per User');
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

if (isset($office) && isset($dept) && isset($start) && isset($end)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $user_detail = substr($data["username_plg_cctv"], 0, 10);

            $sql_1 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_1, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10) = '$user_detail' AND B.id_head_ctg_plg = '1' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_1));

            $sql_2 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_2, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10) = '$user_detail' AND B.id_head_ctg_plg = '2' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat2 = mysqli_fetch_assoc(mysqli_query($conn, $sql_2));
            
            $sql_3 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_3, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10) = '$user_detail' AND B.id_head_ctg_plg = '3' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat3 = mysqli_fetch_assoc(mysqli_query($conn, $sql_3));

            $sql_4 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_4, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10)= '$user_detail' AND B.id_head_ctg_plg = '4' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat4 = mysqli_fetch_assoc(mysqli_query($conn, $sql_4));

            $sql_5 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_5, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10) = '$user_detail' AND B.id_head_ctg_plg = '5' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat5 = mysqli_fetch_assoc(mysqli_query($conn, $sql_5));

            $sql_6 ="SELECT A.tersangka_plg_cctv, A.id_head_jns_plg, COUNT(B.id_head_ctg_plg) AS total_6, C.username_plg_cctv FROM pelanggaran_cctv AS A INNER JOIN jenis_pelanggaran AS B ON A.id_head_jns_plg = B.id_jns_plg INNER JOIN user_pelanggaran_cctv AS C ON A.no_plg_cctv = C.head_no_plg_cctv WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(C.username_plg_cctv, 10) = '$user_detail' AND B.id_head_ctg_plg = '6' AND A.status_plg_cctv = 'Y' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end'";
            $cat6 = mysqli_fetch_assoc(mysqli_query($conn, $sql_6));

            $user_d = $data["username_plg_cctv"];
            $tot1 = $cat1["total_1"];
            $tot2 = $cat2["total_2"];
            $tot3 = $cat3["total_3"];
            $tot4 = $cat4["total_4"];
            $tot5 = $cat5["total_5"];
            $tot6 = $cat6["total_6"];
            $total = $tot1 + $tot2 + $tot3 + $tot4 + $tot5 + $tot6;

            $pdf->SetWidths(array(12, 54, 18, 18, 18, 18, 18, 18, 18));
            $pdf->Row(array($no++, $user_d, $tot1, $tot2, $tot3, $tot4, $tot5, $tot6, $total));
            
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

        $pdf->Cell(12 ,10,'No',1,0,'C');
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
$pdf->Output("LAPORAN SUMMARY DATA PELANGGARAN CCTV PER USERS.pdf","I");

?>