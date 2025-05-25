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
$datadat = mysqli_real_escape_string($conn, $_POST["data-cetak"]);
$barang = $_POST["barangcetak"];
$arrdata = implode(", ", $barang);
$kepdat = $office.$dept;
$datadoc = $datadat == 'DAT' ? 'Kepemilikan' : 'Lokasi';

if ($datadat == "LOK" && $arrdata == "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.*, masterjenis.*, kondisi.* FROM barang_assets
    INNER JOIN office ON barang_assets.ba_id_office = office.id_office
    INNER JOIN department ON barang_assets.ba_id_department = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    INNER JOIN kondisi ON barang_assets.kondisi = kondisi.id_kondisi
    WHERE barang_assets.ba_id_office = '$office' AND barang_assets.ba_id_department = '$dept' GROUP BY barang_assets.pluid ORDER BY barang_assets.pluid ASC";
}
elseif ($datadat == "DAT" && $arrdata == "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.*, masterjenis.*, kondisi.* FROM barang_assets
    INNER JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    INNER JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    INNER JOIN kondisi ON barang_assets.kondisi = kondisi.id_kondisi
    WHERE barang_assets.dat_asset = '$kepdat' GROUP BY barang_assets.pluid ORDER BY barang_assets.pluid ASC";
}
elseif ($datadat == "LOK" && $arrdata != "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.*, masterjenis.*, kondisi.* FROM barang_assets
    INNER JOIN office ON barang_assets.ba_id_office = office.id_office
    INNER JOIN department ON barang_assets.ba_id_department = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    INNER JOIN kondisi ON barang_assets.kondisi = kondisi.id_kondisi
    WHERE barang_assets.ba_id_office = '$office' AND barang_assets.ba_id_department = '$dept' AND barang_assets.pluid IN ($arrdata) GROUP BY barang_assets.pluid ORDER BY barang_assets.pluid ASC";
}
elseif ($datadat == "DAT" && $arrdata != "ALL") {
    $sql = "SELECT barang_assets.*, office.*, department.*, mastercategory.*, masterjenis.*, kondisi.* FROM barang_assets
    INNER JOIN office ON LEFT(barang_assets.dat_asset, 4) = office.id_office
    INNER JOIN department ON RIGHT(barang_assets.dat_asset, 4) = department.id_department
    INNER JOIN mastercategory ON LEFT(barang_assets.pluid, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(barang_assets.pluid, 4) = masterjenis.IDJenis
    INNER JOIN kondisi ON barang_assets.kondisi = kondisi.id_kondisi
    WHERE barang_assets.dat_asset = '$kepdat' AND barang_assets.pluid IN ($arrdata) GROUP BY barang_assets.pluid ORDER BY barang_assets.pluid ASC";
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
        
        $this->Cell(199, 8, 'SUMMARY DATA PERALATAN INVENTARIS', 0, 1, 'C');
        
        $this->SetFont('Arial','',10);

        $this->Cell(190, 6, 'Data By : '.$datadoc, 0, 1, 'C');
        
        $this->Ln(2);
        
        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(12 ,20,'No',1,0,'C');
        $this->Cell(54 ,20,'Nama Barang',1,0,'C');
        $this->Cell(108 ,10,'Status Barang',1,0,'C');
        $this->Cell(18 ,20,'Sub Total',1,1,'C');
        $this->Cell(12 ,0,'',0,0,'C');
        $this->Cell(54 ,0,'',0,0,'C');
        $this->Cell(18 ,-10,'Baik',1,0,'C');
        $this->Cell(18 ,-10,'Cadangan',1,0,'C');
        $this->Cell(18 ,-10,'Rusak',1,0,'C');
        $this->Cell(18 ,-10,'Perbaikan',1,0,'C');
        $this->Cell(18 ,-10,'P3AT',1,0,'C');
        $this->Cell(18 ,-10,'Hilang',1,0,'C');
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
$pdf->SetTitle('Report Data Summary Peralatan Inventaris');
$pdf->SetSubject('Peralatan Inventaris');
$pdf->SetKeywords('SINV');
$pdf->SetCreator('IMS');

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0, 0, 0, 0, 0, 0];
$cellHeight = 10;

if (isset($office) && isset($dept) && isset($barang)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $plu = $data["pluid"];

            if ($datadat == "LOK") {

                $data_baik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_baik FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[0]'"));

                $data_cadangan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_cadangan FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[1]'"));
    
                $data_rusak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_rusak FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[2]'"));
    
                $data_service = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_service FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[3]'"));
    
                $data_p3at = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_p3at FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[4]'"));
    
                $data_hilang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_hilang FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND kondisi = '$arrcond[6]'"));
    
            }
            elseif ($datadat == "DAT") {
                
                $data_baik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_baik FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[0]'"));
    
                $data_cadangan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_cadangan FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[1]'"));
    
                $data_rusak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_rusak FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[2]'"));
    
                $data_service = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_service FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[3]'"));
    
                $data_p3at = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_p3at FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[4]'"));
    
                $data_hilang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi) AS total_hilang FROM barang_assets WHERE dat_asset = '$kepdat' AND pluid = '$plu' AND kondisi = '$arrcond[6]'"));
                
            }

            $desc = $data["pluid"].' - '.$data['NamaBarang']." ".$data['NamaJenis'];
            $satu = $data_baik["total_baik"];
            $dua = $data_cadangan["total_cadangan"];
            $tiga = $data_rusak["total_rusak"];
            $empat = $data_service["total_service"];
            $lima = $data_p3at["total_p3at"];
            $tujuh = $data_hilang["total_hilang"];
            $total = $satu + $dua + $tiga + $empat + $lima + $tujuh;

            $pdf->SetWidths(array(12, 54, 18, 18, 18, 18, 18, 18, 18));
            $pdf->Row(array($no++, $desc, $satu, $dua, $tiga, $empat, $lima, $tujuh, $total));
            
            $to_satu = ($nol[0]+=$satu);
            $to_dua = ($nol[1]+=$dua);
            $to_tiga = ($nol[2]+=$tiga);
            $to_empat = ($nol[3]+=$empat);
            $to_lima = ($nol[4]+=$lima);
            $to_tujuh = ($nol[5]+=$tujuh);
            $gd_total = ($nol[6]+=$total);
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66 ,$cellHeight,'Grand Total',1,0,'C');
        $pdf->Cell(18 ,$cellHeight,$to_satu,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_dua,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_tiga,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_empat,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_lima,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$to_tujuh,1,0,'L');
        $pdf->Cell(18 ,$cellHeight,$gd_total++,1,1,'L');
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
$pdf->Output("LAPORAN SUMMARY PERALATAN INVENTARIS.pdf","I");

?>