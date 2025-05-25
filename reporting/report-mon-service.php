<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../vendor/fpdf/fpdf.php';

require '../vendor/autoload.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST['user-service']);
$from = mysqli_real_escape_string($conn, $_POST['startdate-service']);
$end = mysqli_real_escape_string($conn, $_POST['enddate-service']);
$office = mysqli_real_escape_string($conn, $_POST['office-service']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-service']);
$status = mysqli_real_escape_string($conn, $_POST['status-service']);

$sql = "SELECT A.*, B.id_office, B.office_name, C.department_name, D.id_office AS id_off_to, D.office_name AS off_to, E.department_name AS dept_to, F.username FROM surat_jalan AS A
INNER JOIN office AS B ON LEFT(A.asal_sj, 4) = B.id_office
INNER JOIN department AS C ON RIGHT(A.asal_sj, 4) = C.id_department
INNER JOIN office AS D ON LEFT(A.asal_sj, 4) = D.id_office
INNER JOIN department AS E ON RIGHT(A.tujuan_sj, 4) = E.id_department
LEFT JOIN users AS F ON A.user_sj = F.nik
WHERE LEFT(A.no_sj, 1) = 'R' AND LEFT(A.asal_sj, 4) = '$office' AND RIGHT(A.asal_sj, 4) = '$dept' AND LEFT(tanggal_sj, 10) BETWEEN '$from' AND '$end' ORDER BY A.tanggal_sj";

$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

if ($status == "A") {
    $statussj = "";
    $status = "All";
}
elseif ($status == "N") {
    $statussj = " AND detail_surat_jalan.status_sj = 'N'";
    $status = "Belum PTB";
}
elseif ($status == "Y") {
    $statussj = " AND detail_surat_jalan.status_sj = 'Y'";
    $status = "Sudah PTB";
}

if (isset($_POST["printpdf"])) {

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            
            global $header, $office, $from, $end, $user, $status;

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

            $this->Cell(278, 6, 'MONITORING DATA PERBAIKAN BARANG INVENTARIS', 0, 1, 'C');

            $this->Ln(2);

            $this->SetFont('Arial','',10);
            $this->Cell(278, 6, 'Periode : '.date( "d/m/Y", strtotime($from))." - ".date( "d/m/Y", strtotime($end)), 0, 1, 'C');
            
            $this->SetFont('Arial','',10);
            $this->Cell(278, 6, 'Status SJ : '.$status, 0, 1, 'C');

            $this->Ln(2);

            // st font yang ingin anda gunakan
            $this->SetFont('Arial','B',9);

            // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
            // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
            $this->Cell(12, 10, 'NO', 0, 0, 'C');
            $this->Cell(90, 10, 'NAMA BARANG', 0, 0, 'C');
            $this->Cell(30, 10, 'SN', 0, 0, 'C');
            $this->Cell(25, 10, 'NO AKTIVA', 0, 0, 'C');
            $this->Cell(50, 10, 'KETERANGAN', 0, 0, 'C');
            $this->Cell(30, 10, 'TGL TERIMA', 0, 0, 'C');
            $this->Cell(35, 10, 'PENERIMA', 0, 1, 'C');

            $this->Cell(275, 0, '', 1, 1, 'L');
        
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

    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Laporan Perbaikan Barang Inventaris');
    $pdf->SetSubject('Perbaikan Barang Inventaris');
    $pdf->SetKeywords('PBINV');
    $pdf->SetCreator('IMS');

    $no = 1;

    if (isset($from) && isset($end) && isset($status)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_array($query)){

                $docno = isset($data["no_sj"]) ? $data["no_sj"] : NULL;

                $sql_detail = "SELECT detail_surat_jalan.*, mastercategory.NamaBarang, masterjenis.NamaJenis, users.username FROM detail_surat_jalan
                INNER JOIN mastercategory ON LEFT(detail_surat_jalan.pluid_sj, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_surat_jalan.pluid_sj, 4) = masterjenis.IDJenis
                LEFT JOIN users ON detail_surat_jalan.penerima_sj = users.nik
                WHERE detail_surat_jalan.head_no_sj = '$docno'".$statussj;

                $query_detail = mysqli_query($conn, $sql_detail);
                $data_detail = mysqli_fetch_assoc(mysqli_query($conn, $sql_detail));
                
                if(mysqli_num_rows($query_detail) > 0 ) {

                    $pdf->SetFont('Arial','B',8);

                    $tgl_head = date( "d-m-Y", strtotime($data['tanggal_sj']));
                    $pdf->Cell(275, 10, 'NOSJ : '.substr($docno, 1, 5).'  |  TGL PENGAJUAN : '.$tgl_head.'  |  USER PROSES : '.$data['user_sj'].' - '.strtoupper($data['username']).'  |  TUJUAN : '.$data['id_office'].' - '.strtoupper($data['off_to']).'. DEPT - '.strtoupper($data['dept_to']), 0, 1, 'L');

                    while($lihat = mysqli_fetch_array($query_detail)){

                        if ($lihat["status_sj"] == "Y") {
                            $sts = "RECEIVED";
                        }
                        else {
                            $sts = "PROCCESS";
                        }
                        
                        $tgl = date( "d-m-Y", strtotime($lihat['tgl_penerimaan']));

                        $pdf->SetFont('Arial','',8);
                        // Query yang ingin ditampilkan yang berada di database
                        $pdf->Cell(12, 8, $no, 0, 0, 'C');
                        $pdf->Cell(90, 8, $lihat['pluid_sj'].' - '.$lihat['NamaBarang']." ".$lihat['NamaJenis']." ".$lihat['merk_sj']." ".$lihat['tipe_sj'], 0, 0,'C');
                        $pdf->Cell(30, 8, $lihat["sn_sj"],0, 0, 'C');
                        $pdf->Cell(25, 8, $lihat['at_sj'],0, 0, 'C');
                        $pdf->Cell(50, 8, $sts, 0, 0,'C');
                        $pdf->Cell(30, 8, isset($lihat['tgl_penerimaan']) ? $tgl : '-', 0, 0,'C');
                        $pdf->Cell(35, 8, isset($lihat["username"]) ? strtoupper($lihat["username"]) : '-', 0, 1,'C');
                        
                        $no++;
                    }

                    $pdf->Cell(275, 0, '', 1, 1, 'L');
                    
                }
                // else {
                //     $msg = encrypt("datanotfound");
                //     header("location: ../error.php?alert=$msg");
                //     exit();
                // }
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
    $pdf->Output("LAPORAN-PERBAIKAN-BARANG-".$from."-".$end.".pdf","I");
}
elseif (isset($_POST["printexcell"])) {

    $row_xcl = 2;
    $no_xcl = 1;

    if (isset($from) && isset($end) && isset($status)) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // sheet peratama
        $sheet->setTitle('Sheet 1');

        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'DOCNO');
        $sheet->setCellValue('C1', 'TGL PENGAJUAN');
        $sheet->setCellValue('D1', 'KEPERLUAN');
        $sheet->setCellValue('E1', 'KANTOR ASAL');
        $sheet->setCellValue('F1', 'TUJUAN');
        $sheet->setCellValue('G1', 'USER PEMBUAT');
        $sheet->setCellValue('H1', 'NAMA BARANG');
        $sheet->setCellValue('I1', 'SERIAL NUMBER');
        $sheet->setCellValue('J1', 'NOMOR AKTIVA');
        $sheet->setCellValue('K1', 'KETERANGAN / KERUSAKAN');
        $sheet->setCellValue('L1', 'STATUS PERBAIKAN');
        $sheet->setCellValue('M1', 'TGL TERIMA');
        $sheet->setCellValue('N1', 'PENERIMA');
        $sheet->setCellValue('O1', 'KONDISI BARANG');
        $sheet->setCellValue('P1', 'KETERANGAN TERIMA');
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $docno = isset($data_xcl["no_sj"]) ? $data_xcl["no_sj"] : NULL;
                $tgl_sj = isset($data_xcl["tanggal_sj"]) ? $data_xcl["tanggal_sj"] : NULL;
                $user_sj = isset($data_xcl["user_sj"]) ? $data_xcl["user_sj"] : NULL;
                $kep_sj = isset($data_xcl["keperluan_sj"]) ? $data_xcl["keperluan_sj"] : NULL;

                $user_pros = $user_sj." - ".strtoupper($data_xcl["username"]);

                $from_sj = $data_xcl["id_office"]." - ".strtoupper($data_xcl["office_name"]).". ".strtoupper($data_xcl["department_name"]);
                $to_sj = $data_xcl["id_off_to"]." - ".strtoupper($data_xcl["off_to"]).". ".strtoupper($data_xcl["dept_to"]);

                if ($kep_sj == "PS") {
                    $keperluan = "PENGAJUAN PERBAIKAN BARANG";
                }
                elseif ($kep_sj == "PM") {
                    $keperluan = "PENGAJUAN REKOMENDASI PEMUSNAHAN";
                }
                else {
                    $keperluan = "PENGAJUAN PERBAIKAN BARANG";
                }

                $sql_detail = "SELECT detail_surat_jalan.*, mastercategory.NamaBarang, masterjenis.NamaJenis, users.username, kondisi.kondisi_name FROM detail_surat_jalan
                INNER JOIN mastercategory ON LEFT(detail_surat_jalan.pluid_sj, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_surat_jalan.pluid_sj, 4) = masterjenis.IDJenis
                LEFT JOIN users ON detail_surat_jalan.penerima_sj = users.nik
                LEFT JOIN kondisi ON detail_surat_jalan.kondisi_perbaikan = kondisi.id_kondisi
                WHERE detail_surat_jalan.head_no_sj = '$docno'".$statussj;

                $query_detail = mysqli_query($conn, $sql_detail);

                if(mysqli_num_rows($query_detail) > 0 ) {
                    while($data_detail = mysqli_fetch_assoc($query_detail)){

                        $idplu = $data_detail["pluid_sj"];
                        $barang = $data_detail['NamaBarang']." ".$data_detail['NamaJenis']." ".$data_detail['merk_sj']." ".$data_detail['tipe_sj'];
                        $sn = $data_detail["sn_sj"];
                        $at = $data_detail["at_sj"];
                        $ket = $data_detail["keterangan_sj"];

                        $sts = $data_detail["status_sj"] == "Y" ? "RECEIVED" : "PROCCESS";
                        $tglterima = isset($data_detail["tgl_penerimaan"]) ? $data_detail["tgl_penerimaan"] : "-";
                        
                        $user_terima = $data_detail["penerima_sj"]." - ".strtoupper($data_detail["username"]);
                        $kond = $data_detail["kondisi_perbaikan"]." - ".$data_detail["kondisi_name"];
                        $ket_terima = isset($data_detail["ket_penerimaan_sj"]) ? $data_detail["ket_penerimaan_sj"] : "-";

                        $sheet->setCellValue('A'.$row_xcl, $no_xcl++);
                        $sheet->setCellValue('B'.$row_xcl, substr($docno, 1));
                        $sheet->setCellValue('C'.$row_xcl, $tgl_sj);
                        $sheet->setCellValue('D'.$row_xcl, $keperluan);
                        $sheet->setCellValue('E'.$row_xcl, $from_sj);
                        $sheet->setCellValue('F'.$row_xcl, $to_sj);
                        $sheet->setCellValue('G'.$row_xcl, $user_pros);
                        $sheet->setCellValue('H'.$row_xcl, $idplu." - ".$barang);
                        $sheet->setCellValue('I'.$row_xcl, $sn);
                        $sheet->setCellValue('J'.$row_xcl, $at);
                        $sheet->setCellValue('K'.$row_xcl, $ket);
                        $sheet->setCellValue('L'.$row_xcl, $sts);
                        $sheet->setCellValue('M'.$row_xcl, $tglterima);
                        $sheet->setCellValue('N'.$row_xcl, $user_terima);
                        $sheet->setCellValue('O'.$row_xcl, $kond);
                        $sheet->setCellValue('P'.$row_xcl, $ket_terima);
                        $row_xcl++;

                    }
                }
                // else {
                //     $msg = encrypt("datanotfound");
                //     header("location: ../error.php?alert=$msg");
                //     exit();
                // }
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
    header('Content-Disposition: attachment; filename=LAPORAN PERBAIKAN BARANG '.$from.' SD '.$end.'.xlsx');
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