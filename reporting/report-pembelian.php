<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST['user']);
$from = mysqli_real_escape_string($conn, $_POST['from']);
$end = mysqli_real_escape_string($conn, $_POST['end']);
$office = mysqli_real_escape_string($conn, $_POST['office']);
$dept = mysqli_real_escape_string($conn, $_POST['dept']);

$sql = "SELECT A.*, B.office_name, C.department_name, D.ppid, D.keperluan, E.id_office AS id_office_tujuan, E.office_name AS name_office_tujuan, F.department_name AS name_dept_tujuan FROM penerimaan_pembelian AS A
INNER JOIN office AS B ON A.office_penerimaan = B.id_office
INNER JOIN department AS C ON A.dept_penerimaan = C.id_department
INNER JOIN pembelian AS D ON A.pp_id_pembelian = D.id_pembelian
INNER JOIN office AS E ON D.office_to = E.id_office
INNER JOIN department AS F ON D.department_to = F.id_department
WHERE A.office_penerimaan = '$office' AND A.dept_penerimaan = '$dept'";
$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $office, $from, $end;
           
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

        $this->Cell(278, 6, 'LISTING LAPORAN PENERIMAAN PEMBELIAN', 0, 1, 'C');

        $this->Ln(2);

        $this->SetFont('Arial','',10);
        $this->Cell(278, 6, 'Periode : '.$from." - ".$end, 0, 1, 'C');

        $this->Ln(4);

        // st font yang ingin anda gunakan
        $this->SetFont('Arial','B',9);

        // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
        // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
        $this->Cell(10, 10, 'NO', 1, 0, 'C');
        $this->Cell(29, 10, 'TGL TERIMA', 1, 0, 'C');
        $this->Cell(35, 10, 'PENERIMA', 1, 0, 'C');
        $this->Cell(20, 10, 'PLUID', 1, 0, 'C');
        $this->Cell(40, 10, 'NAMA BARANG', 1, 0, 'C');
        $this->Cell(30, 10, 'MERK', 1, 0, 'C');
        $this->Cell(30, 10, 'TIPE', 1, 0, 'C');
        $this->Cell(15, 10, 'QTY', 1, 0, 'C');
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

$pdf->SetTitle('Report Laporan Pembelian');

$no = 1;

if (isset($from) && isset($end)) {
    
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $docno = $data["id_penerimaan"];
            $id_pp = $data["ppid"];
            $tujuan_pp = $data["id_office_tujuan"]." - ".strtoupper($data["name_office_tujuan"])." DEPT. ".strtoupper($data["name_dept_tujuan"]);
            $kep_pp = $data["keperluan"];

            $sql_detail = "SELECT detail_penerimaan_pembelian.*, users.username, mastercategory.NamaBarang, masterjenis.NamaJenis FROM detail_penerimaan_pembelian
            LEFT JOIN users ON detail_penerimaan_pembelian.user_penerima = users.nik
            LEFT JOIN mastercategory ON LEFT(detail_penerimaan_pembelian.pluid_penerimaan, 6) = mastercategory.IDBarang
            LEFT JOIN masterjenis ON RIGHT(detail_penerimaan_pembelian.pluid_penerimaan, 4) = masterjenis.IDJenis
            WHERE detail_penerimaan_pembelian.id_penerimaan_pp = '$docno' AND LEFT(detail_penerimaan_pembelian.tgl_penerimaan, 10) BETWEEN '$from' AND '$end' AND detail_penerimaan_pembelian.status_penerimaan = 'Y' GROUP BY detail_penerimaan_pembelian.id_penerimaan_detail ASC";
            $query_detail = mysqli_query($conn, $sql_detail);

            $data_detail = mysqli_fetch_assoc(mysqli_query($conn, $sql_detail));
            
            $noid_btb = isset($data_detail["id_penerimaan_pp"]) ? $data_detail["id_penerimaan_pp"] : null;

            $query_tgl = mysqli_query($conn, "SELECT tgl_penerimaan FROM detail_penerimaan_pembelian WHERE LEFT(tgl_penerimaan, 10) BETWEEN '$from' AND '$end' AND status_penerimaan = 'Y'");            

            if(mysqli_num_rows($query_tgl) > 0 ) {
                
                if ($data_detail) {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(275, 8, 'BTBNO : '.substr($noid_btb, 1, 5), 1, 1, 'L');
                    $pdf->Cell(275, 8, 'PPNO : '.$id_pp, 1, 1, 'L');
                    $pdf->Cell(275, 8, 'TUJUAN PP : '.$tujuan_pp, 1, 1, 'L');
                    $pdf->MultiCell(275, 8, 'KEPERLUAN PP : '.$kep_pp, 1);

                    while($lihat = mysqli_fetch_array($query_detail)){
            
                        $pdf->SetFont('Arial','',8);
                        // Query yang ingin ditampilkan yang berada di database

                        $tgl = $lihat['tgl_penerimaan'];
                        $usern = strtoupper($lihat['user_penerima']." - ".$lihat["username"]);
                        $kode = $lihat["pluid_penerimaan"];
                        $barang = $lihat['NamaBarang']." ".$lihat['NamaJenis'];
                        $merk = $lihat["merk_penerimaan"];
                        $tipe = $lihat["tipe_penerimaan"];
                        $qty = $lihat["qty_penerimaan"];
                        $ket = $lihat["keterangan_penerimaan"];

                        $pdf->SetWidths(array(10, 29, 35, 20, 40, 30, 30, 15, 66));
                        $pdf->Row(array($no++, $tgl, $usern, $kode, $barang, $merk, $tipe, $qty, $ket));
                    }
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

// Nama file ketika di print
$pdf->Output("LAPORAN-PEMBELIAN-".$from."-".$end.".pdf","I");

?>