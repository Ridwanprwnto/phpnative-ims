<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$status = mysqli_real_escape_string($conn, $_POST['status-cetak']);

if ($status == "A") {
    $sql = "SELECT A.*, B.office_name, C.department_name, D.username FROM head_project AS A
    INNER JOIN office AS B ON A.office_head_project = B.id_office
    INNER JOIN department AS C ON A.dept_head_project = C.id_department
    INNER JOIN users AS D ON A.user_head_project = D.nik
    WHERE A.office_head_project = '$office' AND A.dept_head_project = '$dept' ORDER BY A.tgl_head_project ASC";
}
elseif ($status == "Y") {
    $sql = "SELECT A.*, B.office_name, C.department_name, D.username FROM head_project AS A
    INNER JOIN office AS B ON A.office_head_project = B.id_office
    INNER JOIN department AS C ON A.dept_head_project = C.id_department
    INNER JOIN users AS D ON A.user_head_project = D.nik
    WHERE A.office_head_project = '$office' AND A.dept_head_project = '$dept' AND A.status_head_project = '$status' ORDER BY A.tgl_head_project ASC";
}
elseif ($status == "N") {
    $sql = "SELECT A.*, B.office_name, C.department_name, D.username FROM head_project AS A
    INNER JOIN office AS B ON A.office_head_project = B.id_office
    INNER JOIN department AS C ON A.dept_head_project = C.id_department
    INNER JOIN users AS D ON A.user_head_project = D.nik
    WHERE A.office_head_project = '$office' AND A.dept_head_project = '$dept' AND A.status_head_project = '$status' ORDER BY A.tgl_head_project ASC";
}


$query_header = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_header);

$title = "REPORT LIST DATA LAPORAN PROJECT";

if (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header, $user, $office, $dept, $title;
            
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

            $this->Ln(4);

            // st font yang ingin anda gunakan
            $this->SetFont('Arial','B',9);

            $this->SetFillColor(190,190,190);

            // queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
            // Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
            $this->Cell(26, 10, 'TAHAPAN', 1, 0, 'C', true);
            $this->Cell(20, 10, 'PIC', 1, 0, 'C', true);
            $this->Cell(77, 10, 'PENGERJAAN', 1, 0, 'C', true);
            $this->Cell(20, 10, 'JUMLAH', 1, 0, 'C', true);
            $this->Cell(24, 10, 'KESULITAN', 1, 0, 'C', true);
            $this->Cell(25, 10, 'REALISASI', 1, 0, 'C', true);
            $this->Cell(66, 10, 'KETERANGAN', 1, 0, 'C', true);
            $this->Cell(20, 10, 'STATUS', 1, 1, 'C', true);
        
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

    $pdf->SetTitle('Report Data Laporan Project');

    $pdf->SetFillColor(0,255,128);

    $no = 1;

    if (isset($user) && isset($office) && isset($dept)) {
        
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_array($query)){

                $docno = $data["no_head_project"];
                $user_pros = $data["user_head_project"]." - ".$data["username"];
                $instruksi = $data["approve_head_project"];
                $tgl_pros = $data["tgl_head_project"];
                $prioritas = $data["urgensi_head_project"];
                $judul = $data["judul_head_project"];
                $stsproyek = $data['status_head_project'] == 'Y' ? 'SELESAI' : 'PROSES';

                $sts_color = $data['status_head_project'] == 'Y' ? true : false;

                $pdf->SetFont('Arial','B',9);
                $pdf->MultiCell(278, 6, 'DOCNO : '.$docno.' - TGL : '.$tgl_pros.' - USER : '.strtoupper($user_pros).' - INSTRUKSI : '.$instruksi.' - PRIORITAS : '.$prioritas, 1, 1);
                $pdf->MultiCell(278, 6, 'PROYEK : '.$judul, 1, 1);
                $pdf->MultiCell(278, 6, 'PROGRES : '.$stsproyek, 1, 1, $sts_color);
                
                $sql_detail = "SELECT A.*, B.username AS user_proses FROM project_task AS A
                LEFT JOIN users AS B ON A.user_project_task = B.nik
                WHERE A.ref_project_task = '$docno' ORDER BY A.urutan_project_task";

                $query_detail = mysqli_query($conn, $sql_detail);

                if(mysqli_num_rows($query_detail) > 0 ) {
                    while($data_detail = mysqli_fetch_assoc($query_detail)){

                        $pdf->SetFont('Arial','',8);

                        $tahap = $data_detail['urutan_project_task'];
                        $pic = $data_detail['pic_project_task'];
                        $kerja = $data_detail['pengerjaan_project_task'];
                        $jumlah = $data_detail['jumlah_project_task'];
                        $sulit = strtoupper($data_detail['priority_project_task']);
                        $userp = isset($data_detail['user_proses']) ? $data_detail['user_project_task']." - ".strtoupper($data_detail['user_proses']) : '-';
                        $efektif = isset($data_detail['efektif_project_task']) ? $data_detail['efektif_project_task'] : '-';
                        $ket = isset($data_detail['ket_project_task']) ? $data_detail['ket_project_task'] : '-';
                        $sts = $data_detail['status_project_task'] == 'Y' ? 'SELESAI' : 'PROSES';

                        $pdf->SetWidths(array(26, 20, 77, 20, 24, 25, 66, 20));
                        $pdf->Row(array($tahap, $pic, $kerja, $jumlah, $sulit, $efektif, $ket, $sts));
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
    $pdf->Output("REPORT-PROJECT.pdf","I");
}

?>