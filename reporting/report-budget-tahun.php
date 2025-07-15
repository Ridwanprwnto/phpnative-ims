<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../vendor/fpdf/fpdf.php';

$office = mysqli_real_escape_string($conn, $_POST["office"]);
$dept = mysqli_real_escape_string($conn, $_POST["department"]);
$year = mysqli_real_escape_string($conn, $_POST["year"]);
$user = mysqli_real_escape_string($conn, $_POST["user"]);

$sql = "SELECT budget.*, COUNT(budget.plu_id) AS plu_total, mastercategory.*, masterjenis.*, statusbudget.*, bulan.*, office.*, department.* FROM budget
    INNER JOIN mastercategory ON LEFT(budget.plu_id, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(budget.plu_id, 4) = masterjenis.IDJenis
    INNER JOIN statusbudget ON budget.tahun_periode = statusbudget.tahun_periode
    INNER JOIN bulan ON budget.id_bulan = bulan.id_bulan
    INNER JOIN office ON budget.id_office = office.id_office
    INNER JOIN department ON budget.id_department = department.id_department
    WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND statusbudget.status_budget = 'Y' GROUP BY plu_id";

$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

/*A4 width : 219mm*/
$pdf = new FPDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Stock Budget Tahunan');

$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'Office',0,0);
$pdf->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
$pdf->Cell(126 ,5,'',0,0);
$pdf->Cell(28 ,5,'Print Date',0,0);
$pdf->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

$pdf->Cell(28 ,5,'Department',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(126 ,5,'',0,0);
$pdf->Cell(28 ,5,'User',0,0);
$pdf->Cell(48 ,5,': '.$user, 0, 1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',14);

$pdf->Cell(278, 8, 'LAPORAN STOCK BUDGET TAHUNAN', 0, 1, 'C');

$pdf->SetFont('Arial','',10);

$pdf->Cell(278, 5, 'Tahun : '.$header['tahun_periode'], 0, 1, 'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(12 ,16,'No',1,0,'C');
$pdf->Cell(76 ,16,'Nama Barang',1,0,'C');
$pdf->Cell(25 ,16,'Saldo',1,0,'C');
$pdf->Cell(132 ,8,'Bulan',1,0,'C');
$pdf->Cell(32 ,16,'Harga Total',1,1,'C');
$pdf->Cell(12 ,0,'',0,0,'C');
$pdf->Cell(76 ,0,'',0,0,'C');
$pdf->Cell(25,0,'',0,0,'C');
$pdf->Cell(11,-8,'01',1,0,'C');
$pdf->Cell(11,-8,'02',1,0,'C');
$pdf->Cell(11,-8,'03',1,0,'C');
$pdf->Cell(11,-8,'04',1,0,'C');
$pdf->Cell(11,-8,'05',1,0,'C');
$pdf->Cell(11,-8,'06',1,0,'C');
$pdf->Cell(11,-8,'07',1,0,'C');
$pdf->Cell(11,-8,'08',1,0,'C');
$pdf->Cell(11,-8,'09',1,0,'C');
$pdf->Cell(11,-8,'10',1,0,'C');
$pdf->Cell(11,-8,'11',1,0,'C');
$pdf->Cell(11,-8,'12',1,0,'C');
$pdf->Cell(32 ,0,'',0,1,'C');
/*end of line*/

$pdf->SetFont('Arial','',8);

$no = 1;
$nol = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

if (isset($office) && isset($dept) && isset($year) && isset($user)) {

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_array($query)){
    
            $desc = $data['plu_id'].' - '.$data['NamaBarang']." ".$data['NamaJenis'];

            $cellWidth = 76;
            $cellHeight = 12;

            if ($pdf->GetStringWidth($desc) < $cellWidth) {
                $line = 1;
            }
            else {
                # code...
                $textLenght = strlen($desc);
                $errMargin = 10;
                $startChar = 0;
                $maxChar = 0;
                $textArray = array();
                $tempString = "";

                while ($startChar < $textLenght) {
                    # code...
                    while ($pdf->GetStringWidth($tempString) < ($cellWidth-$errMargin) && ($startChar+$maxChar) < $textLenght) {
                        # code...
                        $maxChar++;
                        $tempString = substr($desc,$startChar,$maxChar);
                    }
                    $startChar = $startChar+$maxChar;
                    array_push($textArray, $tempString);
                    $maxChar = 0;
                    $tempString = '';
                }
                $line = count($textArray);
            }

            $plu = $data["plu_id"];

            $data1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '01' AND plu_id = '$plu'"));
            $satu = $data1['stock_budget'];
            
            $data2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '02' AND plu_id = '$plu'"));
            $dua = $data2['stock_budget'];

            $data3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '03' AND plu_id = '$plu'"));
            $tiga = $data3['stock_budget'];
            
            $data4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '04' AND plu_id = '$plu'"));
            $empat = $data4['stock_budget'];

            $data5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '05' AND plu_id = '$plu'"));
            $lima = $data5['stock_budget'];
            
            $data6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '06' AND plu_id = '$plu'"));
            $enam = $data6['stock_budget'];
            
            $data7 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '07' AND plu_id = '$plu'"));
            $tujuh = $data7['stock_budget'];
            
            $data8 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '08' AND plu_id = '$plu'"));
            $delapan = $data8['stock_budget'];
            
            $data9 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '09' AND plu_id = '$plu'"));
            $sembilan = $data9['stock_budget'];
            
            $data10 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '10' AND plu_id = '$plu'"));
            $sepuluh = $data10['stock_budget'];
                        
            $data11 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '11' AND plu_id = '$plu'"));
            $sebelas = $data11['stock_budget'];
                        
            $data12 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$year' AND id_bulan = '12' AND plu_id = '$plu'"));
            $duabelas = $data12['stock_budget'];

            $subtotal = $data["HargaJenis"];
            $pdf->Cell(12 ,($line * $cellHeight),$no,1,0,'C');

            $xPos = $pdf->GetX(); //initial x (start of column position)
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, $desc, 1);
            $pdf->SetXY($xPos + $cellWidth , $yPos);

            $pdf->Cell(25 ,($line * $cellHeight / 2), 'Awal' ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $satu == NULL ? '-' : $satu ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $dua == NULL ? '-' : $dua ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $tiga == NULL ? '-' : $tiga ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $empat == NULL ? '-' : $empat ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $lima == NULL ? '-' : $lima ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $enam == NULL ? '-' : $enam ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $tujuh == NULL ? '-' : $tujuh ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $delapan == NULL ? '-' : $delapan ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $sembilan == NULL ? '-' : $sembilan ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $sepuluh == NULL ? '-' : $sepuluh ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $sebelas == NULL ? '-' : $sebelas ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $duabelas == NULL ? '-' : $duabelas ,1,0,'C');
            $pdf->Cell(32 ,($line * $cellHeight / 2),'Rp. '.number_format($total = $subtotal*$satu + $subtotal*$dua + $subtotal*$tiga + $subtotal*$empat + $subtotal*$lima + $subtotal*$enam + $subtotal*$tujuh + $subtotal*$delapan + $subtotal*$sembilan + $subtotal*$sepuluh + $subtotal*$sebelas + $subtotal*$duabelas, 2),1,1,'C');

            $no++;
            $total += $subtotal;
            $t_satu = ($nol[0]+=$satu);
            $t_dua = ($nol[1]+=$dua);
            $t_tiga = ($nol[2]+=$tiga);
            $t_empat = ($nol[3]+=$empat);
            $t_lima = ($nol[4]+=$lima);
            $t_enam = ($nol[5]+=$enam);
            $t_tujuh = ($nol[6]+=$tujuh);
            $t_delapan = ($nol[7]+=$delapan);
            $t_sembilan = ($nol[8]+=$sembilan);
            $t_sepuluh = ($nol[9]+=$sepuluh);
            $t_sebelas = ($nol[10]+=$sebelas);
            $t_duabelas = ($nol[11]+=$duabelas);
            $grandtotal = ($nol[12]+=$total);
            
            $sql1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_01 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode ='$year' AND budget.id_bulan = '01' AND budget.plu_id = '$plu'"));
            
            $out_satu = $satu - $sql1['out_01'];

            $sql2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_02 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode ='$year' AND budget.id_bulan = '02' AND budget.plu_id = '$plu'"));
            
            $out_dua = $dua - $sql2['out_02'];

            $sql3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_03 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '03' AND budget.plu_id = '$plu'"));

            $out_tiga = $tiga - $sql3['out_03'];

            $sql4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_04 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '04' AND budget.plu_id = '$plu'"));

            $out_empat = $empat - $sql4['out_04'];

            $sql5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_05 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '05' AND budget.plu_id = '$plu'"));

            $out_lima = $lima - $sql5['out_05'];

            $sql6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_06 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '06' AND budget.plu_id = '$plu'"));

            $out_enam = $enam - $sql6['out_06'];

            $sql7 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_07 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '07' AND budget.plu_id = '$plu'"));

            $out_tujuh = $tujuh - $sql7['out_07'];

            $sql8 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_08 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '08' AND budget.plu_id = '$plu'"));

            $out_delapan = $delapan - $sql8['out_08'];

            $sql9 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_09 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '09' AND budget.plu_id = '$plu'"));

            $out_sembilan = $sembilan - $sql9['out_09'];

            $sql10 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_10 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '10' AND budget.plu_id = '$plu'"));

            $out_sepuluh = $sepuluh - $sql10['out_10'];

            $sql11 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_11 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '11' AND budget.plu_id = '$plu'"));

            $out_sebelas = $sebelas - $sql11['out_11'];

            $sql12 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT budget.stock_budget, SUM(detail_pembelian.qty) AS out_12 FROM budget
            INNER JOIN detail_pembelian ON budget.id_budget = detail_pembelian.id_budget
            WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year' AND budget.id_bulan = '12' AND budget.plu_id = '$plu'"));

            $out_duabelas = $duabelas - $sql12['out_12'];

            $pdf->Cell(12 ,($line * $cellHeight),'',0,0,'C');

            $xPos = $pdf->GetX(); //initial x (start of column position)
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, '', 0);
            $pdf->SetXY($xPos + $cellWidth , $yPos);

            $pdf->Cell(25 ,($line * $cellHeight / 2), 'Akhir' ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_satu == NULL ? '-' : $out_satu ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_dua == NULL ? '-' : $out_dua ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_tiga == NULL ? '-' : $out_tiga ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_empat == NULL ? '-' : $out_empat ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_lima == NULL ? '-' : $out_lima ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_enam == NULL ? '-' : $out_enam ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_tujuh == NULL ? '-' : $out_tujuh ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_delapan == NULL ? '-' : $out_delapan ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_sembilan == NULL ? '-' : $out_sembilan ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_sepuluh == NULL ? '-' : $out_sepuluh ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_sebelas == NULL ? '-' : $out_sebelas ,1,0,'C');
            $pdf->Cell(11 ,($line * $cellHeight / 2), $out_duabelas == NULL ? '-' : $out_duabelas ,1,0,'C');
            $pdf->Cell(32 ,($line * $cellHeight / 2),'Rp. '.number_format($out_total = $subtotal*$out_satu + $subtotal*$out_dua + $subtotal*$out_tiga + $subtotal*$out_empat + $subtotal*$out_lima + $subtotal*$out_enam + $subtotal*$out_tujuh + $subtotal*$out_delapan + $subtotal*$out_sembilan + $subtotal*$out_sepuluh + $subtotal*$out_sebelas + $subtotal*$out_duabelas, 2),1,1,'C');
            // Query yang ingin ditampilkan yang berada di database

            $out_total += $subtotal;
            $to_satu = ($nol[13]+=$out_satu);
            $to_dua = ($nol[14]+=$out_dua);
            $to_tiga = ($nol[15]+=$out_tiga);
            $to_empat = ($nol[16]+=$out_empat);
            $to_lima = ($nol[17]+=$out_lima);
            $to_enam = ($nol[18]+=$out_enam);
            $to_tujuh = ($nol[19]+=$out_tujuh);
            $to_delapan = ($nol[20]+=$out_delapan);
            $to_sembilan = ($nol[21]+=$out_sembilan);
            $to_sepuluh = ($nol[22]+=$out_sepuluh);
            $to_sebelas = ($nol[23]+=$out_sebelas);
            $to_duabelas = ($nol[24]+=$out_duabelas);
            $out_grandtotal = ($nol[25]+=$out_total);
            
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(88 ,12,'Total :',1,0,'C');
        $pdf->Cell(25 ,6, 'Awal' ,1,0,'C');
        $pdf->Cell(11 ,6, $t_satu ,1,0,'C');
        $pdf->Cell(11 ,6, $t_dua ,1,0,'C');
        $pdf->Cell(11 ,6, $t_tiga ,1,0,'C');
        $pdf->Cell(11 ,6, $t_empat ,1,0,'C');
        $pdf->Cell(11 ,6, $t_lima ,1,0,'C');
        $pdf->Cell(11 ,6, $t_enam ,1,0,'C');
        $pdf->Cell(11 ,6, $t_tujuh ,1,0,'C');
        $pdf->Cell(11 ,6, $t_delapan ,1,0,'C');
        $pdf->Cell(11 ,6, $t_sembilan ,1,0,'C');
        $pdf->Cell(11 ,6, $t_sepuluh ,1,0,'C');
        $pdf->Cell(11 ,6, $t_sebelas ,1,0,'C');
        $pdf->Cell(11 ,6, $t_duabelas ,1,0,'C');
        $pdf->Cell(32 ,6,'Rp. '.number_format($grandtotal, 2),1,1,'C');

        
        $pdf->Cell(88 ,-12,'',0,0,'C');
        $pdf->Cell(25 ,6, 'Akhir' ,1,0,'C');
        $pdf->Cell(11 ,6, $to_satu ,1,0,'C');
        $pdf->Cell(11 ,6, $to_dua ,1,0,'C');
        $pdf->Cell(11 ,6, $to_tiga ,1,0,'C');
        $pdf->Cell(11 ,6, $to_empat ,1,0,'C');
        $pdf->Cell(11 ,6, $to_lima ,1,0,'C');
        $pdf->Cell(11 ,6, $to_enam ,1,0,'C');
        $pdf->Cell(11 ,6, $to_tujuh ,1,0,'C');
        $pdf->Cell(11 ,6, $to_delapan ,1,0,'C');
        $pdf->Cell(11 ,6, $to_sembilan ,1,0,'C');
        $pdf->Cell(11 ,6, $to_sepuluh ,1,0,'C');
        $pdf->Cell(11 ,6, $to_sebelas ,1,0,'C');
        $pdf->Cell(11 ,6, $to_duabelas ,1,0,'C');
        $pdf->Cell(32 ,6,'Rp. '.number_format($out_grandtotal, 2),1,1,'C');

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
$pdf->Output("LAPORAN-BUDGET-TAHUNAN".$office."-".$dept."-".$year.".pdf","I");

?>