<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);

$nobtb = $_POST["btbnocetak"];
$arrdata = implode(", ", $nobtb);

if ($arrdata == "ALL") {
    $sql = "SELECT A.*, B.office_name, C.department_name, D.ppid FROM penerimaan_pembelian AS A
    INNER JOIN office AS B ON A.office_penerimaan = B.id_office
    INNER JOIN department AS C ON A.dept_penerimaan = C.id_department
    INNER JOIN pembelian AS D ON A.pp_id_pembelian = D.id_pembelian
    WHERE A.office_penerimaan = '$office' AND A.dept_penerimaan = '$dept'";
}
else {
    $sql = "SELECT A.*, B.office_name, C.department_name, D.ppid FROM penerimaan_pembelian AS A
    INNER JOIN office AS B ON A.office_penerimaan = B.id_office
    INNER JOIN department AS C ON A.dept_penerimaan = C.id_department
    INNER JOIN pembelian AS D ON A.pp_id_pembelian = D.id_pembelian
    WHERE A.office_penerimaan = '$office' AND A.dept_penerimaan = '$dept' AND A.id_penerimaan IN ($arrdata)";
}

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header, $user, $office;
           
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

        $this->Cell(278, 6, 'LAPORAN PENERIMAAN PEMBELIAN VS REGISTER DAT', 0, 1, 'C');

        $this->Ln(4);

        // st font yang ingin anda gunakan
        $this->SetFont('Arial','B',9);

        // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
        // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
        $this->Cell(10, 10, 'NO', 1, 0, 'C');
        $this->Cell(29, 10, 'TGL TERIMA', 1, 0, 'C');
        $this->Cell(35, 10, 'PENERIMA', 1, 0, 'C');
        $this->Cell(20, 10, 'PLUID', 1, 0, 'C');
        $this->Cell(75, 10, 'NAMA BARANG', 1, 0, 'C');
        $this->Cell(20, 10, 'QTY BTB', 1, 0, 'C');
        $this->Cell(20, 10, 'QTY DAT', 1, 0, 'C');
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

$pdf->SetTitle('Report Penerimaan Pembelian Vs Register DAT');

$no = 1;

if (isset($user) && isset($office) && isset($dept) && isset($nobtb)) {
    
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){

            $docno = $data["id_penerimaan"];
            $id_pp = $data["ppid"];

            $sql_detail = "SELECT A.*, B.username, C.NamaBarang, D.NamaJenis FROM detail_penerimaan_pembelian AS A
            INNER JOIN users AS B ON A.user_penerima = B.nik
            INNER JOIN mastercategory AS C ON LEFT(A.pluid_penerimaan, 6) = C.IDBarang
            INNER JOIN masterjenis AS D ON RIGHT(A.pluid_penerimaan, 4) = D.IDJenis
            WHERE A.id_penerimaan_pp = '$docno' AND A.status_penerimaan = 'Y' GROUP BY A.id_penerimaan_detail ASC";
            $query_detail = mysqli_query($conn, $sql_detail);

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(39, 8, '    BTBNO : '.substr($docno, 1, 5), 1, 0, 'L');
            $pdf->Cell(236, 8, '    PPNO : '.$id_pp, 1, 1, 'L');

            if(mysqli_num_rows($query_detail) > 0 ) {
                while($lihat = mysqli_fetch_array($query_detail)){
        
                    $btbno_pp = $lihat["id_penerimaan_pp"];
                    $pluid_pp = $lihat["pluid_penerimaan"];
        
                    $sql_dat = "SELECT COUNT(noref_asset) AS total_dat FROM barang_assets WHERE noref_asset = '$btbno_pp' AND pluid = '$pluid_pp'";
                    $query_dat = mysqli_query($conn, $sql_dat);
                    $data_dat = mysqli_fetch_array($query_dat);

                    $pdf->SetFont('Arial','',8);
                    // Query yang ingin ditampilkan yang berada di database

                    $tgl = $lihat['tgl_penerimaan'];
                    $usern = strtoupper($lihat['user_penerima']." - ".$lihat["username"]);
                    $kode = $lihat["pluid_penerimaan"];
                    $barang = $lihat['NamaBarang']." ".$lihat['NamaJenis']." ".$lihat['merk_penerimaan']." ".$lihat['tipe_penerimaan'];
                    $qtybtb = $lihat["qty_penerimaan"];
                    $qtydat = $data_dat["total_dat"];
                    $ket = $lihat["keterangan_penerimaan"];

                    $pdf->SetWidths(array(10, 29, 35, 20, 75, 20, 20, 66));
                    $pdf->Row(array($no++, $tgl, $usern, $kode, $barang, $qtybtb, $qtydat, $ket));
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
$pdf->Output("LAPORAN-PEMBELIAN-".".pdf","I");

?>