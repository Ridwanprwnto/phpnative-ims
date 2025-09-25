<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$awal = mysqli_real_escape_string($conn, $_POST['tgl-awal']);
$akhir = mysqli_real_escape_string($conn, $_POST['tgl-akhir']);

$sql = "SELECT A.*, B.office_name, C.department_name, D.username, E.* FROM pembelian AS A
INNER JOIN office AS B ON A.id_office = B.id_office
INNER JOIN department AS C ON A.id_department = C.id_department
INNER JOIN users AS D ON A.user = D.nik
INNER JOIN status_pembelian AS E ON A.status_pp = E.id_spp
WHERE A.id_office = '$office' AND A.id_department = '$dept' AND LEFT(A.proses_date, 10) BETWEEN '$awal' AND '$akhir' GROUP BY A.noref ORDER BY A.proses_date ASC";

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

$title = "REPORT DATA PERMOHONAN PEMBELIAN";

if (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header, $user, $office, $awal, $akhir, $title;
            
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

            $this->Cell(278, 6, $title, 0, 1, 'C');

            $this->Ln(2);

            $this->SetFont('Arial','',10);
            $this->Cell(278, 6, 'Periode : '.$awal." - ".$akhir, 0, 1, 'C');

            $this->Ln(4);

            // st font yang ingin anda gunakan
            $this->SetFont('Arial','B',9);

            // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
            // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
            $this->Cell(10, 10, 'NO', 1, 0, 'C');
            $this->Cell(20, 10, 'PLUID', 1, 0, 'C');
            $this->Cell(67, 10, 'NAMA BARANG', 1, 0, 'C');
            $this->Cell(24, 10, 'QTY PP', 1, 0, 'C');
            $this->Cell(24, 10, 'QTY TERIMA', 1, 0, 'C');
            $this->Cell(29, 10, 'TGL TERIMA', 1, 0, 'C');
            $this->Cell(35, 10, 'PENERIMA', 1, 0, 'C');
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

    $pdf->SetTitle('Report Data Permohonan Pembelian');

    $no = 1;

    if (isset($user) && isset($office) && isset($dept) && isset($awal) && isset($akhir)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_array($query)){

                $noref = $data["noref"];
                $ppid = $data["ppid"];
                $id_pp = $data["id_pembelian"];
                $tgl_pr = $data["proses_date"];
                $tgl_pj = $data["tgl_pengajuan"];
                $user_pros = $data["user"]." - ".$data["username"];
                $status = $data["status_name"];
                $keperluan = $data["keperluan"];

                $ppno = $data["ppid"];
                $jenispp = substr($ppno,0 ,3);
                if ($jenispp == "PPB") {
                    $pp = "BUDGET";
                }
                elseif ($jenispp == "PPG") {
                    $pp = "REGULER";
                }
                elseif ($jenispp == "PPM") {
                    $pp = "MUSNAH";
                }

                $sql_detail = "SELECT A.*, B.NamaBarang, C.NamaJenis FROM detail_pembelian AS A
                INNER JOIN mastercategory AS B ON LEFT(A.plu_id, 6) = B.IDBarang
                INNER JOIN masterjenis AS C ON RIGHT(A.plu_id, 4) = C.IDJenis
                WHERE A.noref = '$noref'";

                $query_detail = mysqli_query($conn, $sql_detail);

                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(275, 6, 'PPNO : '.$ppid.' - USER : '.strtoupper($user_pros).' - JENIS PP : '.$pp.' - TGL PROSES : '.$tgl_pr.' - TGL PENGAJUAN : '.$tgl_pj, 1, 1);
                $pdf->MultiCell(275, 6, 'KEPERLUAN PP : '.$keperluan, 1, 1);
                $pdf->Cell(275, 6, 'STATUS PP : '.strtoupper($status), 1, 1);

                $nod = 1;

                if(mysqli_num_rows($query_detail) > 0 ) {
                    while($lihat = mysqli_fetch_array($query_detail)){

                        $iddpp = $lihat["id_dpp"];
                        $idplu = $lihat["plu_id"];
                        $barang = $lihat['NamaBarang']." ".$lihat['NamaJenis']." ".$lihat['merk']." ".$lihat['tipe'];
                        $idqty = $lihat["qty"];
            
                        $sql_dpp = "SELECT A.*, B.*, C.username AS userp FROM penerimaan_pembelian AS A 
                        INNER JOIN detail_penerimaan_pembelian AS B ON A.id_penerimaan = B.id_penerimaan_pp
                        INNER JOIN users AS C ON B.user_penerima = C.nik
                        WHERE A.pp_id_pembelian = '$id_pp' AND B.pluid_penerimaan = '$idplu'";

                        $query_dpp = mysqli_query($conn, $sql_dpp);
                        $data_dpp = mysqli_fetch_array($query_dpp);

                        $pdf->SetFont('Arial','',8);

                        $qtybtb = isset($data_dpp['qty_penerimaan']) ? $data_dpp['qty_penerimaan'] : '-';
                        $tgl = isset($data_dpp['tgl_penerimaan']) ? $data_dpp['tgl_penerimaan'] : '-';
                        $usern = isset($data_dpp['user_penerima']) ? $data_dpp['user_penerima']." - ".strtoupper($data_dpp["userp"]) : '-';
                        $ket = isset($data_dpp['keterangan_penerimaan']) ? $data_dpp['keterangan_penerimaan'] : '-';

                        $pdf->SetWidths(array(10, 20, 67, 24, 24, 29, 35, 66));
                        $pdf->Row(array($nod++, $idplu, $barang, $idqty, $qtybtb, $tgl, $usern, $ket));
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
    $pdf->Output("REPORT-PP-".$awal." ".$akhir.".pdf","I");
}
elseif (isset($_POST["printexcell"])) {

    $row_xcl = 2;
    $no_xcl = 1;

    if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($user)) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // sheet peratama
        $sheet->setTitle('Sheet 1');

        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'JENIS PP');
        $sheet->setCellValue('C1', 'STATUS PP');
        $sheet->setCellValue('D1', 'NOMOR PP');
        $sheet->setCellValue('E1', 'TGL PP');
        $sheet->setCellValue('F1', 'NAMA BARANG');
        $sheet->setCellValue('G1', 'SATUAN');
        $sheet->setCellValue('H1', 'JUMLAH PP');
        $sheet->setCellValue('I1', 'EST HARGA TOTAL');
        $sheet->setCellValue('J1', 'KEPERLUAN');
        $sheet->setCellValue('K1', 'PIC PP');
        $sheet->setCellValue('L1', 'KETERANGAN PP');
        $sheet->setCellValue('M1', 'TGL REALISASI');
        $sheet->setCellValue('N1', 'JUMLAH REALISASI');
        $sheet->setCellValue('O1', 'KETERANGAN TERIMA');
        $sheet->setCellValue('P1', 'PENERIMA BARANG');
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $noref = $data_xcl["noref"];
                $ppid = $data_xcl["ppid"];
                $id_pp = $data_xcl["id_pembelian"];
                $tgl_pr = $data_xcl["proses_date"];
                $user_pros = $data_xcl["user"]." - ".strtoupper($data_xcl["username"]);
                $status = strtoupper($data_xcl["status_name"]);
                $keperluan = $data_xcl["keperluan"];

                $str_date_pp = $data_xcl['tgl_pengajuan'];
                $slash_date_pp  =  str_replace('-"', '/', $str_date_pp);
                $tgl_pj  =  date( "Y/m/d", strtotime($slash_date_pp));

                $ppno = $data_xcl["ppid"];
                $jenispp = substr($ppno,0 ,3);
                if ($jenispp == "PPB") {
                    $pp = "BUDGET";
                }
                elseif ($jenispp == "PPG") {
                    $pp = "REGULER";
                }
                elseif ($jenispp == "PPM") {
                    $pp = "MUSNAH";
                }

                $sql_pp = "SELECT A.*, B.NamaBarang, C.NamaJenis, D.nama_satuan FROM detail_pembelian AS A
                INNER JOIN mastercategory AS B ON LEFT(A.plu_id, 6) = B.IDBarang
                INNER JOIN masterjenis AS C ON RIGHT(A.plu_id, 4) = C.IDJenis
                INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
                WHERE A.noref = '$noref'";

                $query_pp = mysqli_query($conn, $sql_pp);

                if(mysqli_num_rows($query_pp) > 0 ) {
                    while($data_pp = mysqli_fetch_array($query_pp)){

                        $iddpp = $data_pp["id_dpp"];
                        $idplu = $data_pp["plu_id"];
                        $barang = $data_pp['NamaBarang']." ".$data_pp['NamaJenis']." ".$data_pp['merk']." ".$data_pp['tipe'];
                        $satuan = $data_pp["nama_satuan"];
                        $idqty = $data_pp["qty"];
                        $hargapp = number_format($data_pp['harga_pp']);
                        $peruntukan = $data_pp["keterangan"];

                        $sql_btb = "SELECT A.*, B.*, C.username AS userp FROM penerimaan_pembelian AS A 
                        INNER JOIN detail_penerimaan_pembelian AS B ON A.id_penerimaan = B.id_penerimaan_pp
                        LEFT JOIN users AS C ON B.user_penerima = C.nik
                        WHERE A.pp_id_pembelian = '$id_pp' AND B.pluid_penerimaan = '$idplu'";

                        $query_btb = mysqli_query($conn, $sql_btb);
                        $data_btb = mysqli_fetch_array($query_btb);

                        $qtybtb = isset($data_btb['qty_penerimaan']) ? $data_btb['qty_penerimaan'] : '-';
                        $tgl_btb = isset($data_btb['tgl_penerimaan']) ? date( "Y/m/d", strtotime(str_replace('-"', '/', substr($data_btb['tgl_penerimaan'], 0, -9)))) : '-';
                        $ket = isset($data_btb['keterangan_penerimaan']) ? $data_btb['keterangan_penerimaan'] : '-';
                        $usern = isset($data_btb['user_penerima']) ? $data_btb['user_penerima']." - ".strtoupper($data_btb["userp"]) : '-';
                        
                        $sheet->setCellValue('A'.$row_xcl, $no_xcl++);
                        $sheet->setCellValue('B'.$row_xcl, $pp);
                        $sheet->setCellValue('C'.$row_xcl, $status);
                        $sheet->setCellValue('D'.$row_xcl, $ppid);
                        $sheet->setCellValue('E'.$row_xcl, $tgl_pj);
                        $sheet->setCellValue('F'.$row_xcl, $idplu." - ".$barang);
                        $sheet->setCellValue('G'.$row_xcl, $satuan);
                        $sheet->setCellValue('H'.$row_xcl, $idqty);
                        $sheet->setCellValue('I'.$row_xcl, $hargapp);
                        $sheet->setCellValue('J'.$row_xcl, $peruntukan);
                        $sheet->setCellValue('K'.$row_xcl, $user_pros);
                        $sheet->setCellValue('L'.$row_xcl, $keperluan);
                        $sheet->setCellValue('M'.$row_xcl, $tgl_btb);
                        $sheet->setCellValue('N'.$row_xcl, $qtybtb);
                        $sheet->setCellValue('O'.$row_xcl, $ket);
                        $sheet->setCellValue('P'.$row_xcl, $usern);
                        $row_xcl++;

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
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);
    
    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename='.$title.' - '.$awal.' SD '.$akhir.'.xlsx');
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