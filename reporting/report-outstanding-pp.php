<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';
require '../includes/function/tag.php';

// Memanggil file fpdf yang anda tadi simpan di folder htdoc
require '../includes/config/mc_table.php';

$username = mysqli_real_escape_string($conn, $_POST['user-cetak']);
$office = mysqli_real_escape_string($conn, $_POST['office-cetak']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-cetak']);
$awal = mysqli_real_escape_string($conn, $_POST['tgl-awal']);
$akhir = mysqli_real_escape_string($conn, $_POST['tgl-akhir']);

$sql = "SELECT A.*, B.*, C.*, D.username, E.*, F.id_office AS id_office_to, F.office_name AS id_office_name, G.id_department AS id_dept_to, G.department_name AS id_dept_name FROM pembelian AS A
        INNER JOIN office AS B ON A.id_office = B.id_office
        INNER JOIN department AS C ON A.id_department = C.id_department
        INNER JOIN users AS D ON A.user = D.nik
        INNER JOIN status_pembelian AS E ON A.status_pp = E.id_spp
        INNER JOIN office AS F ON A.office_to = F.id_office
        INNER JOIN department AS G ON A.department_to = G.id_department
        WHERE A.id_office = '$office' AND A.id_department = '$dept' AND LEFT(A.proses_date, 10) BETWEEN '$awal' AND '$akhir' AND A.status_pp != '$arrsp[1]' AND A.status_pp != '$arrsp[3]' AND A.status_pp != '$arrsp[10]' ORDER BY A.proses_date ASC";

$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $data, $username, $awal, $akhir;

        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Office',0,0);
        $this->Cell(48 ,5,': '.$data["id_office"]." - ".$data["office_name"],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$data["department_name"],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'User',0,0);
        $this->Cell(48 ,5,': '.$username, 0, 1);

        $this->Ln(5);

        $this->SetFont('Arial','B',12);

        $this->Cell(278, 6, 'LAPORAN OUTSTANDING PERMOHONAN PEMBELIAN', 0, 1, 'C');

        $this->Ln(2);

        $this->SetFont('Arial','',10);
        $this->Cell(278, 6, 'Periode : '.$awal." - ".$akhir, 0, 1, 'C');

        $this->Ln(4);
       
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
$pdf->SetTitle('Laporan Outstanding Permohonan Pembelian');
$pdf->SetSubject('Outstanding PP');
$pdf->SetKeywords('OUTPP');
$pdf->SetCreator('IMS');

// st font yang ingin anda gunakan
$pdf->SetFont('Arial','B',9);

// queri yang ingin di tampilkan di tabel sehingga ketika diubah tidak akan berpengaruh
// Kode 1, 0, 'C' dan banyak kode di bawah adalah ukuran lebar tabel ubah jika tidak sesuai keinginan anda.
$pdf->Cell(12, 8, 'NO', 1, 0, 'C');
$pdf->Cell(21, 8, 'KODE', 1, 0, 'C');
$pdf->Cell(64, 8, 'NAMA BARANG', 1, 0, 'C');
$pdf->Cell(18, 8, 'SATUAN', 1, 0, 'C');
$pdf->Cell(18, 8, 'QTY', 1, 0, 'C');
$pdf->Cell(30, 8, 'JUMLAH HARGA', 1, 0, 'C');
$pdf->Cell(98, 8, 'KETERANGAN', 1, 0, 'C');
$pdf->Cell(14, 8, 'STB', 1, 1, 'C');

$pdf->Cell(275, 0, '', 1, 1, 'L');

$pdf->SetFont('Arial','',9);

$no = 1;

if (isset($office) && isset($dept) && isset($awal) && isset($akhir)) {
    
    $query_head = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_head) > 0 ) {

        while($data_head = mysqli_fetch_array($query_head)){

            $noref = $data_head["noref"];
            $ppno = $data_head["ppid"];
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
            $user = $data_head["user"]." ".$data_head["username"];
            $proses = substr($data_head["proses_date"], 0, 10);
            $pengajuan = substr($data_head["tgl_pengajuan"], 0, 10);
            $status = $data_head["status_name"];
            $keperluan = $data_head["keperluan"];

            $pdf->SetFont('Arial','B',9);
            $pdf->MultiCell(275, 6, 'KEPERLUAN PP : '.$keperluan, 1, 1);
            $pdf->Cell(275, 6, 'PPNO : '.$ppno.' - USER : '.strtoupper($user).' - JENIS PP : '.$pp.' - TGL PROSES : '.$proses.' - TGL PENGAJUAN : '.$pengajuan, 1, 1);
            $pdf->Cell(275, 6, 'STATUS PP : '.strtoupper($status), 1, 1);

            $sql_detail = "SELECT A.*, B.NamaBarang, C.NamaJenis, C.HargaJenis, D.nama_satuan, E.status_penerimaan FROM detail_pembelian AS A
            INNER JOIN mastercategory AS B ON LEFT(A.plu_id, 6) = B.IDBarang
            INNER JOIN masterjenis AS C ON RIGHT(A.plu_id, 4) = C.IDJenis
            INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
            LEFT JOIN detail_penerimaan_pembelian AS E ON A.id_dpp = E.id_dpp_penerimaan
            WHERE A.noref = '$noref'";

            $query_detail = mysqli_query($conn, $sql_detail);
            if(mysqli_num_rows($query_detail) > 0 ) {

                while($data_detail = mysqli_fetch_array($query_detail)){

                    $pdf->SetFont('Arial','',9);

                    $kode = $data_detail["plu_id"];
                    $desc = $data_detail["NamaBarang"]." ".$data_detail["NamaJenis"]." ".$data_detail["merk"]." ".$data_detail["tipe"];
                    $satuan = $data_detail["nama_satuan"];
                    $qty = $data_detail["qty"];
                    $harga = $data_detail["harga_pp"];
                    $subtotal = 'Rp. '.number_format($harga, 2);
                    $ket = $data_detail["keterangan"];
                    $stb = isset($data_detail["status_penerimaan"]) ? $data_detail["status_penerimaan"] : "-";

                    $pdf->SetWidths(array(12, 21, 64, 18, 18, 30, 98, 14));
                    $pdf->Row(array($no++, $kode, $desc, $satuan, $qty, $subtotal, $ket, $stb));
        
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
$pdf->Output("OUTSTANDING-PP".".pdf","I");

?>