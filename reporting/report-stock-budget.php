<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$year = mysqli_real_escape_string($conn, $_POST["tahun-cetak"]);
$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);

$barang = $_POST["barangcetak"];

$arrdata = implode(", ", $barang);

if ($arrdata == "ALL") {
    $sql = "SELECT budget.tahun_periode, budget.stock_budget, budget.use_budget, budget.plu_id, mastercategory.NamaBarang, masterjenis.NamaJenis, masterjenis.HargaJenis, statusbudget.status_budget, office.id_office, office.office_name, department.id_department, department.department_name FROM budget
    INNER JOIN mastercategory ON LEFT(budget.plu_id, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(budget.plu_id, 4) = masterjenis.IDJenis
    INNER JOIN statusbudget ON budget.tahun_periode = statusbudget.tahun_periode
    INNER JOIN office ON budget.id_office = office.id_office
    INNER JOIN department ON budget.id_department = department.id_department
    WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND statusbudget.status_budget = 'Y' ORDER BY mastercategory.NamaBarang ASC";
}
else {
    $sql = "SELECT budget.tahun_periode, budget.stock_budget, budget.use_budget, budget.plu_id, mastercategory.NamaBarang, masterjenis.NamaJenis, masterjenis.HargaJenis, statusbudget.status_budget, office.id_office, office.office_name, department.id_department, department.department_name FROM budget
    INNER JOIN mastercategory ON LEFT(budget.plu_id, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(budget.plu_id, 4) = masterjenis.IDJenis
    INNER JOIN statusbudget ON budget.tahun_periode = statusbudget.tahun_periode
    INNER JOIN office ON budget.id_office = office.id_office
    INNER JOIN department ON budget.id_department = department.id_department
    WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND statusbudget.status_budget = 'Y' AND budget.plu_id IN ($arrdata) ORDER BY mastercategory.NamaBarang ASC";
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

        $this->Ln(4);

        $this->SetFont('Arial','B',14);

        $this->Cell(190, 8, 'LAPORAN STOCK BUDGET PERIODE TAHUNAN', 0, 1, 'C');

        $this->SetFont('Arial','',10);

        $this->Cell(190, 5, 'Tahun : '.$header['tahun_periode'], 0, 1, 'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,8,'NO',1,0,'C');
        $this->Cell(118 ,8,'NAMA BARANG',1,0,'C');
        $this->Cell(20 ,8,'SALDO',1,0,'C');
        $this->Cell(20 ,8,'TERPAKAI',1,0,'C');
        $this->Cell(20 ,8,'SISA',1,1,'C');
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

$pdf->SetTitle('Laporan Stock Budget Tahunan');

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0];
if (isset($office) && isset($dept) && isset($year) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $subtotal = $data["HargaJenis"];
            $kd_brg = $data['plu_id'];
            $desc_brg = $kd_brg." - ".$data['NamaBarang']." ".$data['NamaJenis'];
            $qty = $data['stock_budget'];
            $used = $data['use_budget'];
            $sisa = ($qty-$used);

            $pdf->SetWidths(array(12, 118, 20, 20, 20));
            $pdf->Row(array($no++, $desc_brg, $qty, $used, $sisa));

            $totalsaldo = $nol[0] += $qty;
            $totalused = $nol[1] += $used;
            $totalsisa = $nol[2] += $sisa;
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(130 ,6,'TOTAL',1,0,'');
        $pdf->Cell(20 ,6,$totalsaldo,1,0,'');
        $pdf->Cell(20 ,6,$totalused,1,0,'');
        $pdf->Cell(20 ,6,$totalsisa,1,1,'');

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
$pdf->Output("LAPORAN-BUDGET-".$office."-".$dept."-".$year.".pdf","I");

?>