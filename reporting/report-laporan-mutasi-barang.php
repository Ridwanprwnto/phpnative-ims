<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$office = mysqli_real_escape_string($conn, $_POST["office"]);
$dept = mysqli_real_escape_string($conn, $_POST["department"]);
$awal = mysqli_real_escape_string($conn, $_POST["tglawal"]);
$akhir = mysqli_real_escape_string($conn, $_POST["tglakhir"]);
$desc = mysqli_real_escape_string($conn, $_POST["pluid"]);
$transaksi = mysqli_real_escape_string($conn, $_POST["transaksi"]);
$user = mysqli_real_escape_string($conn, $_POST["user"]);

if ($transaksi == "+") {
    $id = "Penerimaan";
}
elseif ($transaksi == "-"){
    $id = "Pengeluaran";
}

if ($desc == "ALL") {
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.username AS pic, G.username AS penerima FROM btb_dpd AS A
    INNER JOIN office AS B ON A.office_btb_dpd = B.id_office
    INNER JOIN department AS C ON A.dept_btb_dpd = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid_btb_dpd, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid_btb_dpd, 4) = E.IDJenis
    INNER JOIN users AS F ON A.pic_btb_dpd = F.nik
    INNER JOIN users AS G ON A.penerima_btb_dpd = G.nik
    WHERE A.office_btb_dpd = '$office' AND A.dept_btb_dpd = '$dept' AND A.hitung_btb_dpd = '$transaksi' AND A.tgl_btb_dpd BETWEEN '$awal' AND '$akhir' ORDER BY A.id_btb_dpd ASC";

}
else {
    $pluid = substr($desc, 0, 10);
    $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.username AS pic, G.username AS penerima FROM btb_dpd AS A
    INNER JOIN office AS B ON A.office_btb_dpd = B.id_office
    INNER JOIN department AS C ON A.dept_btb_dpd = C.id_department
    INNER JOIN mastercategory AS D ON LEFT(A.pluid_btb_dpd, 6) = D.IDBarang
    INNER JOIN masterjenis AS E ON RIGHT(A.pluid_btb_dpd, 4) = E.IDJenis
    INNER JOIN users AS F ON A.pic_btb_dpd = F.nik
    INNER JOIN users AS G ON A.penerima_btb_dpd = G.nik
    WHERE A.office_btb_dpd = '$office' AND A.dept_btb_dpd = '$dept' AND A.pluid_btb_dpd = '$pluid' AND A.hitung_btb_dpd = '$transaksi' AND A.tgl_btb_dpd BETWEEN '$awal' AND '$akhir' ORDER BY A.id_btb_dpd ASC";
}

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $awal, $akhir, $id, $desc;
           
        /*output the result*/
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

        $this->Ln(5);

        $this->SetFont('Arial','B',12);

        $this->Cell(190, 10, 'LAPORAN MUTASI BARANG', 0, 1, 'C');

        $this->SetFont('Arial','',10);
        $this->Cell(190, 6, 'Periode : '.$awal." - ".$akhir, 0, 1, 'C');
        $this->Cell(190, 6, 'Type : '.$id, 0, 1, 'C');
        $this->Cell(190, 6, 'Barang : '.$desc, 0, 1, 'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'NO',1,0,'C');
        $this->Cell(60 ,8,'NAMA BARANG',1,0,'C');
        $this->Cell(24 ,8,'TRANSAKSI',1,0,'C');
        $this->Cell(16 ,8,'JUMLAH',1,0,'C');
        $this->Cell(80 ,8,'KETERANGAN',1,1,'C');
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
$pdf->SetTitle('Laporan Mutasi Barang');
$pdf->SetSubject('Laporan Mutasi Barang');
$pdf->SetKeywords('LMB');
$pdf->SetCreator('IMS');

$pdf->SetFont('Arial','',8);

$no = 1;
if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($desc) && isset($transaksi) && isset($user)) {
    
    $query_detail = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_detail) > 0 ) {

        while($data = mysqli_fetch_assoc($query_detail)){

            $plu = $data['pluid_btb_dpd'];
            $desc = $plu." - ".$data['NamaBarang']." ".$data['NamaJenis'];
            $qty = $data['qty_akhir_btb_dpd'];
            $ket = $data['ket_btb_dpd'];
            
            if (substr($data['no_btb_dpd'], 0, 1) == "I") {
                $trans = "PENERIMAAN";
            }
            elseif (substr($data['no_btb_dpd'], 0, 1) == "O") {
                $trans = "PENGELUARAN";
            }
            elseif (substr($data['no_btb_dpd'], 0, 1) == "A") {
                $trans = "PENYESUAIAN";
            }

            $pdf->Cell(190, 8, 'DOCNO : '.substr($data["no_btb_dpd"], 1).'  |  TGL : '.$data["tgl_btb_dpd"].'  |  PIC : '.$data["pic_btb_dpd"]." - ".strtoupper($data["pic"]).'  |  PENERIMA : '.$data['penerima_btb_dpd']." - ".strtoupper($data["penerima"]), 1, 1, 'L');

            $pdf->SetWidths(array(10, 60, 24, 16, 80));
            $pdf->Row(array($no++, $desc, $trans, $qty, $ket));
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
$pdf->Output("LAPORAN MUTASI BARANG-".$awal."-".$akhir.".pdf","I");

?>