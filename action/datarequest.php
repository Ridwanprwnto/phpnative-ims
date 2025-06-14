<?php

//Include database configuration file
include '../includes/config/conn.php';
include '../includes/function/func.php';
require '../includes/function/tag.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST["SESSIONLOG"]) && !empty($_POST["SESSIONLOG"])) {
        
        session_start();
        $sess = $_POST['SESSIONLOG'];

        $sql = "SELECT nik FROM users WHERE nik = '$sess'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);

        if($result == TRUE){
            unset($_SESSION["user_nik"]);
            if(!isset($_SESSION["user_nik"])) {
                echo '0'; //session expired
            }  
        else {
                echo '1'; //session expired
            }
        }
        else {
            echo '1'; //session expired
        }

    }

    if(isset($_POST["noref"]) && !empty($_POST["noref"])){
        //Get all state data
        $noref = mysqli_real_escape_string($conn, $_POST['noref']);

        $sql = "SELECT A.id_category, B.CategoryName FROM pembelian AS A 
        INNER JOIN categorybarang AS B ON A.id_category = B.IDCategory
        WHERE A.noref = '$noref'";
        $query = mysqli_query($conn, $sql);

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row["id_category"].'">'.$row["id_category"]." - ".$row["CategoryName"].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if (isset($_POST["pluidpp"]) && !empty($_POST["pluidpp"])) {

        //Get all state data
        $pluid = mysqli_real_escape_string($conn, $_POST['pluidpp']);

        $query = mysqli_query($conn, "SELECT A.*, B.* FROM masterjenis AS A
        INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang WHERE LEFT(B.IDBarang, 1) = '$pluid'");
        
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['IDBarang'].$row['IDJenis'].'">'.$row['IDBarang'].$row['IDJenis'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["penerimaan"]) && !empty($_POST["penerimaan"])){

        //Get all state data
        $id_penerimaan = mysqli_real_escape_string($conn, $_POST['penerimaan']);

        $sql = "SELECT A.id_penerimaan_detail, A.pluid_penerimaan, A.qty_penerimaan, B.pp_id_pembelian, C.ppid, D.IDBarang, D.NamaBarang, E.IDJenis, E.NamaJenis FROM detail_penerimaan_pembelian AS A
        INNER JOIN penerimaan_pembelian AS B ON A.id_penerimaan_pp = B.id_penerimaan
        INNER JOIN pembelian AS C ON B.pp_id_pembelian = C.id_pembelian
        INNER JOIN mastercategory AS D ON LEFT(A.pluid_penerimaan, 6) = D.IDBarang
        INNER JOIN masterjenis AS E ON RIGHT(A.pluid_penerimaan, 4) = E.IDJenis
        WHERE A.id_penerimaan_pp = '$id_penerimaan'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        $nol = 0;

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){
                $id_plu = $row["pluid_penerimaan"];
                $qty = $row["qty_penerimaan"];

                $sql_asset = mysqli_query($conn, "SELECT COUNT(noref_asset) AS total FROM barang_assets WHERE noref_asset = '$id_penerimaan' AND pluid = '$id_plu'");
                $data_asset = mysqli_fetch_assoc($sql_asset);
                $total = $data_asset["total"];

                if ($total != $qty ) {
                    echo '<option value="'.$row['id_penerimaan_detail'].$row['pluid_penerimaan'].'">'.$row['pluid_penerimaan'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
                }
            }
        }
        else {
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["NOBTB"]) && !empty($_POST["NOBTB"]) && isset($_POST["PLUID"]) && !empty($_POST["PLUID"])){
        //Get all state data
        $nopp = mysqli_real_escape_string($conn, $_POST['NOBTB']);
        $gab = mysqli_real_escape_string($conn, $_POST['PLUID']);
        $id = substr($gab, 0, -10);
        $pluid = substr($gab, -10);

        $no = 1;
        $sql = "SELECT detail_penerimaan_pembelian.id_penerimaan_detail, detail_penerimaan_pembelian.merk_penerimaan, detail_penerimaan_pembelian.tipe_penerimaan, detail_penerimaan_pembelian.qty_penerimaan, penerimaan_pembelian.pp_id_pembelian, penerimaan_pembelian.office_penerimaan, penerimaan_pembelian.dept_penerimaan FROM detail_penerimaan_pembelian
        INNER JOIN penerimaan_pembelian ON detail_penerimaan_pembelian.id_penerimaan_pp = penerimaan_pembelian.id_penerimaan
        WHERE detail_penerimaan_pembelian.id_penerimaan_pp = '$nopp' AND detail_penerimaan_pembelian.id_penerimaan_detail = '$id' AND detail_penerimaan_pembelian.pluid_penerimaan = '$pluid' GROUP BY detail_penerimaan_pembelian.id_penerimaan_pp";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            
            $data = mysqli_fetch_assoc($query);

            $dataqty = $data["qty_penerimaan"];
        for($i = 0; $i < $dataqty; $i++) {
            ?>
                <tr>
                    <td>
                        <input type="hidden" name="offdep_btb_pp[]" value="<?= $data['office_penerimaan'].$data['dept_penerimaan']; ?>" class="form-control" readonly/>
                        <span><?= $no++; ?></span>
                    </td>
                    <td>
                        <input type="text" name="merk_btb_pp[]" value="<?= $data['merk_penerimaan']; ?>" placeholder="Input Merk Barang (Optional)" class="form-control"/>
                    </td>
                    <td>
                        <input type="text" name="tipe_btb_pp[]" value="<?= $data['tipe_penerimaan']; ?>" placeholder="Input Tipe Barang (Optional)" class="form-control"/>
                    </td>
                    <td>
                        <input type="text" name="sn_btb_pp[]" class="form-control" placeholder="Input Serial Number Barang" required/>
                    </td>
                    <td>
                        <input type="text" name="at_btb_pp[]" class="form-control" placeholder="Input Nomor Aktiva" required/>
                    </td>
                    <td>
                        <input type="text" name="nomor_btb_pp[]" placeholder="Input Nomor Lambung (Optional)" class="form-control"/>
                    </td>
                    <td>
                        <button type="button" name="remove_brg_btb" class="btn btn-danger btn-xs remove_brg_btb"><i class="ft-minus"></i></button>
                    </td>
                </tr>
            <?php
            }
        } 
        else { ?>
            <tr>
                <td colspan='7'>No data available in table</td>
            </tr>
        <?php
        }
    }

    if(isset($_POST["PLUservice"]) && !empty($_POST["PLUservice"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['PLUservice']);
        $plu_service = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$plu_service' AND kondisi = '$arrcond[2]'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["PLUIDservice"]) && !empty($_POST["PLUIDservice"]) &&  isset($_POST["SNservice"]) && !empty($_POST["SNservice"])){

        $row = array();
        $data = mysqli_real_escape_string($conn, $_POST['PLUIDservice']);
        $plu_service = substr($data, 8);
        $sn_service = mysqli_real_escape_string($conn, $_POST['SNservice']);

        $sql = "SELECT * FROM barang_assets WHERE pluid = '$plu_service' AND sn_barang = '$sn_service'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["HEADIV"]) && !empty($_POST["HEADIV"])){
        //Get all state data
        $head_div = substr($_POST['HEADIV'], 0, 4);

        $sql = "SELECT * FROM sub_divisi WHERE id_divisi = '$head_div'";
        $query = mysqli_query($conn, $sql);

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sub_divisi_name'].'">'.$row['sub_divisi_name'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["PLUmusnah"]) && !empty($_POST["PLUmusnah"])){

        //Get all state data
        $office = substr($_POST['PLUmusnah'], 0, 4);
        $dept = substr($_POST['PLUmusnah'], 4, 4);
        $kondisi = substr($_POST['PLUmusnah'], 8, 2);
        $plu_musnah = substr($_POST['PLUmusnah'], 10, 10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu_musnah' AND kondisi = '$kondisi' GROUP BY no_at";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$office.$dept.$kondisi.$row['no_at'].'">'.$row['no_at'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["ATmusnah"]) && !empty($_POST["ATmusnah"])){

        $office = substr($_POST['ATmusnah'], 0, 4);
        $dept = substr($_POST['ATmusnah'], 4, 4);
        $kondisi = substr($_POST['ATmusnah'], 8, 2);
        $at_musnah = substr($_POST['ATmusnah'], 10, 10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND no_at = '$at_musnah' AND kondisi = '$kondisi'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$office.$dept.$kondisi.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["SNmusnah"]) && !empty($_POST["SNmusnah"])){

        $row = array();
        $office = substr($_POST['SNmusnah'], 0, 4);
        $dept = substr($_POST['SNmusnah'], 4, 4);
        $kondisi = substr($_POST['SNmusnah'], 8, 2);
        $sn_musnah = substr($_POST['SNmusnah'], 10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND sn_barang = '$sn_musnah' AND kondisi = '$kondisi'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["PLUmutasi"]) && !empty($_POST["PLUmutasi"])){
        //Get all state data
        $plu_mutasi = mysqli_real_escape_string($conn, $_POST['PLUmutasi']);

        $pluid = substr($plu_mutasi, 8);
        $idoffice = substr($plu_mutasi, 0, -14);
        $iddept = substr($plu_mutasi, 4, -10);
        
        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND kondisi NOT LIKE '$arrcond[5]'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["PLUIDmutasi"]) && !empty($_POST["PLUIDmutasi"]) &&  isset($_POST["SNmutasi"]) && !empty($_POST["SNmutasi"])){

        $row = array();
        $plu_mutasi = mysqli_real_escape_string($conn, $_POST['PLUIDmutasi']);
        $sn_mutasi = mysqli_real_escape_string($conn, $_POST['SNmutasi']);

        $pluid = substr($plu_mutasi, 8);
        $idoffice = substr($plu_mutasi, 0, -14);
        $iddept = substr($plu_mutasi, 4, -10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND sn_barang = '$sn_mutasi'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["typetablok"]) && !empty($_POST["typetablok"])){
        //Get all state data
        $id_type = substr($_POST['typetablok'], 0, 3);

        $sql = "SELECT * FROM zona_plano WHERE id_type_plano_head = '$id_type'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['id_zona_plano'].$row['nm_zona_plano'].'">'.$row['nm_zona_plano'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["zonatablok"]) && !empty($_POST["zonatablok"])){
        $id_zona = substr($_POST['zonatablok'], 0, 3);

        $sql = "SELECT A.id_line_plano, A.nm_line_plano, B.item_zona_plano FROM line_plano AS A
        INNER JOIN zona_plano AS B ON A.id_zona_plano_head = B.id_zona_plano
        WHERE A.id_zona_plano_head = '$id_zona'";
        $query = mysqli_query($conn, $sql);

        $query_head = mysqli_query($conn, $sql);
        $data_head = mysqli_fetch_assoc($query_head);
        
        if (isset($data_head) && is_array($data_head) && count($data_head) > 0) {
            $item = array($data_head["item_zona_plano"]);
        } else {
            $item = array();
        }

        $line = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $line[] = $data["id_line_plano"].$data["nm_line_plano"];
            }
            die(json_encode(array(
                "line"=>$line,
                "item"=>$item
            )));
        }
    }

    if(isset($_POST["linetablok"]) && !empty($_POST["linetablok"])){
        $id_line = substr($_POST['linetablok'], 0, 3);

        $sql = "SELECT A.*, B.* FROM line_plano AS A
        INNER JOIN gateway_dpd AS B ON A.id_line_plano = B.id_head_line_plano
        WHERE A.id_line_plano = '$id_line'";
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);
        
        $station = array();
        $rak = array();
        $shelf = array();
        $cell = array();
        $id = array();
        $ip = array();
        if (isset($data) && is_array($data) && count($data) > 0) {

            $station = array($data["station_line_plano"]);

            for($i = 1; $i <= $data["rak_line_plano"]; $i++) {
                $formattedNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                $rak[] = $formattedNumber++;
            }
            for($i = 1; $i <= $data["shelf_line_plano"]; $i++) {
                $shelf[] = $i;
            }
            for($i = 1; $i <= $data["cell_line_plano"]; $i++) {
                $cell[] = $i;
            }
            for($i = 1; $i <= $data["iddpd_line_plano"]; $i++) {
                $id[] = $i;
            }

            $queryIP = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($queryIP);
            if($rowCount > 0){
                while($row = mysqli_fetch_assoc($queryIP)){ 
                    $ip[] = $row["ip_gateway_dpd"];
                }
            }

        } else {
            $station = array();
            $rak = array();
            $shelf = array();
            $cell = array();
            $id = array();
            $ip = array();
        }
        
        die(json_encode(array(
            "station"=>$station,
            "rak"=>$rak,
            "shelf"=>$shelf,
            "cell"=>$cell,
            "id"=>$id,
            "ip"=>$ip
        )));
    }

    if(isset($_POST["DELETEPLUTABLOK"]) && !empty($_POST["DELETEPLUTABLOK"])){

        $id = $_POST['DELETEPLUTABLOK'];

        $sql = "SELECT id_st_dpd_detail AS id, docno_st_dpd_detail AS docno, plu_st_dpd_detail AS plu, nama_st_dpd_detail AS descname FROM st_dpd_detail WHERE docno_st_dpd_detail = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["BarcodeBarang"]) && !empty($_POST["BarcodeBarang"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['BarcodeBarang']);

        $pluid = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT no_at FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND kondisi != '$arrcond[5]' GROUP BY no_at";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['no_at'].'">'.$row['no_at'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
        }
    }

    if(isset($_POST["BarcodeHandheld"]) && !empty($_POST["BarcodeHandheld"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['BarcodeHandheld']);

        $pluid = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT no_at FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND kondisi != '$arrcond[5]' GROUP BY no_at";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['no_at'].'">'.$row['no_at'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
        }
    }

    if(isset($_POST["BarcodeNL"]) && !empty($_POST["BarcodeNL"])){

        $data = mysqli_real_escape_string($conn, $_POST['BarcodeNL']);

        $pluid = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT no_lambung, sn_barang FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND kondisi != '$arrcond[5]' AND LENGTH(no_lambung) = 5 ORDER BY no_lambung ASC";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="ALL">ALL</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'."'".$row['no_lambung']."'".'">'.$row['no_lambung'].'</option>';
            }
        }
    }

    if(isset($_POST["OFFICEID"]) && !empty($_POST["OFFICEID"]) && isset($_POST["DEPTID"]) && !empty($_POST["DEPTID"]) && isset($_POST["TAHUNID"]) && !empty($_POST["TAHUNID"]) && isset($_POST["BULANID"]) && !empty($_POST["BULANID"]) && isset($_POST["BARANGID"]) && !empty($_POST["BARANGID"])){

        $row = array();
        $office = mysqli_real_escape_string($conn, $_POST['OFFICEID']);
        $dept = mysqli_real_escape_string($conn, $_POST['DEPTID']);
        $tahun = mysqli_real_escape_string($conn, $_POST['TAHUNID']);
        $bulan = mysqli_real_escape_string($conn, $_POST['BULANID']);
        $plu = mysqli_real_escape_string($conn, $_POST['BARANGID']);

        $sql = "SELECT stock_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND id_bulan = '$bulan' AND plu_id = '$plu'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["PLUBROUT"]) && !empty($_POST["PLUBROUT"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['PLUBROUT']);
        $plu = substr($data, 8, 10);
        $idoffice = substr($data, 0, 4);
        $iddept = substr($data, 4, 4);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$plu'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["PLUIDBROUT"]) && !empty($_POST["PLUIDBROUT"]) &&  isset($_POST["SNBROUT"]) && !empty($_POST["SNBROUT"])){

        $row = array();
        $data = mysqli_real_escape_string($conn, $_POST['PLUIDBROUT']);
        $plu = substr($data, 8, 10);
        $idoffice = substr($data, 0, 4);
        $iddept = substr($data, 4, 4);
        $sn = mysqli_real_escape_string($conn, $_POST['SNBROUT']);

        $sql = "SELECT sn_barang, ba_merk, ba_tipe, no_at FROM barang_assets
        WHERE pluid = '$plu' AND sn_barang = '$sn' AND ba_id_office = '$idoffice' AND ba_id_department = '$iddept'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }
    elseif(isset($_POST["PLUBROUTNA"]) && !empty($_POST["PLUBROUTNA"])){

        $data = mysqli_real_escape_string($conn, $_POST['PLUBROUTNA']);
        $plu = substr($data, 8, 10);
        $idoffice = substr($data, 0, 4);
        $iddept = substr($data, 4, 4);
        
        $sql = "SELECT A.saldo_akhir, D.nama_satuan FROM masterstock AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
        INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
        WHERE pluid = '$plu' AND ms_id_office = '$idoffice' AND ms_id_department = '$iddept'";
        $query = mysqli_query($conn, $sql);
        
        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["BKSEIDBARANG"]) && !empty($_POST["BKSEIDBARANG"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['BKSEIDBARANG']);
        $idbarang = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$idbarang' AND kondisi IN ('$arrcond[0]', '$arrcond[1]') ";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["BKSEBARANG"]) && !empty($_POST["BKSEBARANG"]) &&  isset($_POST["BKSESN"]) && !empty($_POST["BKSESN"])){

        $row = array();
        $data = mysqli_real_escape_string($conn, $_POST['BKSEBARANG']);
        $idbrg = substr($data, 8);
        $snbrg = mysqli_real_escape_string($conn, $_POST['BKSESN']);

        $sql = "SELECT * FROM barang_assets WHERE pluid = '$idbrg' AND sn_barang = '$snbrg'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["BKBIDBARANG"]) && !empty($_POST["BKBIDBARANG"])){
        //Get all state data
        $data = mysqli_real_escape_string($conn, $_POST['BKBIDBARANG']);
        $idbarang = substr($data, 8);
        $idoffice = substr($data, 0, -14);
        $iddept = substr($data, 4, -10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$idbarang' AND kondisi = '$arrcond[1]'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["BKBBARANG"]) && !empty($_POST["BKBBARANG"]) &&  isset($_POST["BKBSN"]) && !empty($_POST["BKBSN"])){

        $row = array();
        $data = mysqli_real_escape_string($conn, $_POST['BKBBARANG']);
        $idbrg = substr($data, 8);
        $snbrg = mysqli_real_escape_string($conn, $_POST['BKBSN']);

        $sql = "SELECT * FROM barang_assets WHERE pluid = '$idbrg' AND sn_barang = '$snbrg'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["IDSATUAN"]) && !empty($_POST["IDSATUAN"])){

        $row = array();
        $dataplu = mysqli_real_escape_string($conn, $_POST['IDSATUAN']);
        $id = substr($dataplu, 6, 4);

        $sql = "SELECT A.id_satuan, B.HargaJenis, C.nama_satuan FROM mastercategory AS A 
        INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang
        INNER JOIN satuan AS C ON A.id_satuan = C.id_satuan
        WHERE B.IDJenis = '$id'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["IDSJP"]) && !empty($_POST["IDSJP"])){

        //Get all state data
        $idsjp = mysqli_real_escape_string($conn, $_POST['IDSJP']);

        $sql = "SELECT A.pluid_sj, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM detail_surat_jalan AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid_sj, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_sj, 4) = C.IDJenis
        WHERE A.head_no_sj = '$idsjp' GROUP BY A.pluid_sj";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['pluid_sj'].'">'.$row['pluid_sj'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
        
    }

    if(isset($_POST["SJPID"]) && !empty($_POST["SJPID"]) && isset($_POST["IDBRP"]) && !empty($_POST["IDBRP"])){

        //Get all state data
        $idsjp = mysqli_real_escape_string($conn, $_POST['SJPID']);
        $idbrp = mysqli_real_escape_string($conn, $_POST['IDBRP']);

        $sql = "SELECT sn_sj FROM detail_surat_jalan WHERE head_no_sj = '$idsjp' AND pluid_sj = '$idbrp' AND status_sj = 'Y'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['sn_sj'].'">'.$row['sn_sj'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
        
    }

    if(isset($_POST["SJP"]) && !empty($_POST["SJP"]) && isset($_POST["BRP"]) && !empty($_POST["BRP"]) && isset($_POST["SNP"]) && !empty($_POST["SNP"])){

        //Get all state data
        $idsjp = mysqli_real_escape_string($conn, $_POST['SJP']);
        $idbrp = mysqli_real_escape_string($conn, $_POST['BRP']);
        $idsnp = mysqli_real_escape_string($conn, $_POST['SNP']);

        $sql = "SELECT A.at_sj, A.kondisi_perbaikan, B.* FROM detail_surat_jalan AS A
        INNER JOIN kondisi AS B ON A.kondisi_perbaikan = B.id_kondisi
        WHERE A.head_no_sj = '$idsjp' AND A.pluid_sj = '$idbrp' AND A.sn_sj = '$idsnp'";
        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
        
    }

    if(isset($_POST["PLUIDSN"]) && !empty($_POST["PLUIDSN"])){

        $row = array();
        $datapost = mysqli_real_escape_string($conn, $_POST['PLUIDSN']);
        $idjenis = substr($datapost,6);

        $autoid = autonum(6, "nomor_serial_number", "serial_number");

        $row[] = "IMS-".$idjenis."-".$autoid;
        die(json_encode($row));
    }

    if(isset($_POST["CATPELANGGARAN"]) && !empty($_POST["CATPELANGGARAN"])){
        //Get all state data
        $id_cat = mysqli_real_escape_string($conn, $_POST['CATPELANGGARAN']);

        $sql = "SELECT id_jns_plg, name_jns_plg FROM jenis_pelanggaran WHERE id_head_ctg_plg = '$id_cat'";
        $query = mysqli_query($conn, $sql);

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['name_jns_plg'].'">'.$row['name_jns_plg'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["SERVERDVR"]) && !empty($_POST["SERVERDVR"])){
        //Get all state data
        $id_dvr = mysqli_real_escape_string($conn, $_POST['SERVERDVR']);

        $sql = "SELECT id_lay_cctv, kode_head_bag_cctv, no_lay_cctv, channel_lay_cctv, penempatan_lay_cctv FROM layout_cctv WHERE head_id_area_cctv = '$id_dvr' ORDER BY channel_lay_cctv ASC";
        $query = mysqli_query($conn, $sql);

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['kode_head_bag_cctv'].'.'.$row['no_lay_cctv'].' - '.$row['penempatan_lay_cctv'].'">'.$row['kode_head_bag_cctv'].'.'.$row['no_lay_cctv'].' - '.$row['penempatan_lay_cctv'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["BARANGSRC"]) && !empty($_POST["BARANGSRC"]) || isset($_POST["KEYSRC"]) && !empty($_POST["KEYSRC"])){

        //Get all data
        $data = $_POST['BARANGSRC'];

        $id_group = substr($data, 0, 4);
        $office = substr($data, 4, 4);
        $dept = substr($data, 8, 4);
        $barangid = substr($data, 12, 10);

        $keyid = $_POST['KEYSRC'];

        $s_key = '%'. $keyid .'%';
        
        $no = 1;
        $sql = "SELECT A.*, B.NamaBarang, C.NamaJenis, D.office_name, E.department_name FROM barang_assets AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
        INNER JOIN office AS D ON A.ba_id_office = D.id_office
        INNER JOIN department AS E ON A.ba_id_department = E.id_department
        WHERE LEFT(A.dat_asset, 4) = '$office' AND RIGHT(A.dat_asset, 4) = '$dept' AND A.pluid = '$barangid' AND A.kondisi NOT LIKE '$arrcond[5]' AND (A.pluid LIKE ? OR B.NamaBarang LIKE ? OR C.NamaJenis LIKE ? OR A.ba_merk LIKE ? OR A.ba_tipe LIKE ? OR A.sn_barang LIKE ? OR A.no_at LIKE ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $s_key, $s_key, $s_key, $s_key, $s_key, $s_key, $s_key);
        mysqli_stmt_execute($stmt);

        // cek hasil query
        if (!$stmt) {
            die('Query Error : '.mysqli_errno($conn).' - '.mysqli_error($conn));
        }
        
        // ambil hasil query
        $result = mysqli_stmt_get_result($stmt);

        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            while ($data = mysqli_fetch_assoc($result)) { ?>
                
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= $data["pluid"]." - ".$data["NamaBarang"].' '.$data["NamaJenis"]." ".$data["ba_merk"]." ".$data["ba_tipe"];?></td>
                    <td><?= $data["sn_barang"];?></td>
                    <td><?= $data["no_at"];?></td>
                    <td><?= $data["no_lambung"];?></td>
                    <td>
                        <!-- Icon Button dropdowns -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-menu"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item read_data" href="javascript:void(0)" title="Detail Data SN <?= $data['sn_barang']; ?>" name="read_data" id="<?= $data["id_ba"]; ?>" data-toggle="tooltip" data-placement="bottom">Show Data</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item update_data" href="javascript:void(0)" title="Update Data SN <?= $data['sn_barang']; ?>" name="update_data" id="<?= $data["id_ba"]; ?>" data-toggle="tooltip" data-placement="bottom">Edit Data</a>
                            <?php if ($id_group == $arrgroup[0] || $id_group == $arrgroup[1]) {?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete_data" href="javascript:void(0)" title="Delete Data SN <?= $data['sn_barang']; ?>" name="delete_data" id="<?= $data["id_ba"]; ?>" data-toggle="tooltip" data-placement="bottom">Delete Data</a>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- /btn-group -->
                    </td>
                    <td class="icheck1">
                    <input type="checkbox" name="checkidbarang[]" id="checkidbarang" class="checkidbarang" value="<?= $data['id_ba']; ?>">
                    </td>
                </tr>
                
            <?php }
        }
        else { 
        ?>
            <tr>
                <td colspan='7'>No data available in table</td>
            </tr>
        <?php
        }
    }

    if(isset($_POST["READMODAL"]) && !empty($_POST["READMODAL"])){

        //Get all data
        $id = $_POST['READMODAL'];

        $sql = "SELECT A.*, B.NamaBarang, C.NamaJenis, D.office_name, E.department_name, F.kondisi_name FROM barang_assets AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
        INNER JOIN office AS D ON A.ba_id_office = D.id_office
        INNER JOIN department AS E ON A.ba_id_department = E.id_department
        INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
        WHERE A.id_ba = '$id'";
        
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        ?>
            <div class="col-md-6 mb-2">
                <label>Office : </label>
                <input type="text"  class="form-control" value="<?= $data["ba_id_office"]." ".$data["office_name"]; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>Department : </label>
                <input type="text" class="form-control" value="<?= $data["ba_id_department"]." ".$data["department_name"]; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>Referensi : </label>
                <input type="text" class="form-control" value="<?= substr($data["noref_asset"], 0, 1) == "P" ? "Pembelian" : "Khusus"; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>Serial Number : </label>
                <input type="text" class="form-control" value="<?= $data["sn_barang"]; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>No AT : </label>
                <input type="text" class="form-control" value="<?= $data["no_at"]; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>No Lambung : </label>
                <input type="text" class="form-control" value="<?= $data["no_lambung"]; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label>Kondisi : </label>
                <input type="text" class="form-control" value="<?= $data["kondisi"]." - ".$data["kondisi_name"]; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label>Posisi / Penempatan : </label>
                <textarea class="form-control" type="text" readonly><?= $data["posisi"]; ?></textarea>
            </div>
        <?php
    }

    if(isset($_POST["UPDATEMODAL"]) && !empty($_POST["UPDATEMODAL"])){

        //Get all data
        $id = $_POST['UPDATEMODAL'];

        $sql = "SELECT A.*, LEFT(A.dat_asset, 4) AS office_asset, RIGHT(A.dat_asset, 4) AS dept_asset, B.NamaBarang, C.NamaJenis, D.office_name, E.department_name, F.kondisi_name FROM barang_assets AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
        INNER JOIN office AS D ON A.ba_id_office = D.id_office
        INNER JOIN department AS E ON A.ba_id_department = E.id_department
        INNER JOIN kondisi AS F ON A.kondisi = F.id_kondisi
        WHERE A.id_ba = '$id'";
        
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEMODAL"]) && !empty($_POST["DELETEMODAL"])){

        //Get all data
        $id = $_POST['DELETEMODAL'];

        $sql = "SELECT A.*, B.NamaBarang, C.NamaJenis, D.office_name, E.department_name FROM barang_assets AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
        INNER JOIN office AS D ON A.ba_id_office = D.id_office
        INNER JOIN department AS E ON A.ba_id_department = E.id_department
        WHERE A.id_ba = '$id'";
        
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEMODALPLG"]) && !empty($_POST["UPDATEMODALPLG"])){

        //Get all data
        $id = $_POST['UPDATEMODALPLG'];

        $sql = "SELECT A.*, LEFT(A.tgl_plg_cctv, 10) AS data_tgl_plg, RIGHT(A.tgl_plg_cctv, 8) AS data_wkt_plg, E.username AS pelapor_cctv, F.username AS fup_cctv, G.name_fup_plg FROM pelanggaran_cctv AS A
        INNER JOIN users AS E ON A.user_plg_cctv = E.nik
        LEFT JOIN users AS F ON A.proses_plg_cctv = F.nik
        INNER JOIN fup_pelanggaran AS G ON A.fup_plg_cctv = G.id_fup_plg
        WHERE A.id_plg_cctv = '$id'";
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["LISTCATPELANGGARAN"]) && !empty($_POST["LISTCATPELANGGARAN"])){
        
        //Get all state data
        $id = mysqli_real_escape_string($conn, $_POST['LISTCATPELANGGARAN']);

        $sql = "SELECT * FROM jenis_pelanggaran WHERE id_head_ctg_plg = '$id'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['id_jns_plg'].'">'.$row['name_jns_plg'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
            echo '<option value="ALL">ALL</option>';
        }
    }

    if(isset($_POST["OFFPLGCCTVSRC"]) && !empty($_POST["OFFPLGCCTVSRC"]) && isset($_POST["DEPTPLGCCTVSRC"]) && !empty($_POST["DEPTPLGCCTVSRC"]) && isset($_POST["TGLAWLPLGCCTVSRC"]) && !empty($_POST["TGLAWLPLGCCTVSRC"]) && isset($_POST["TGLAKRPLGCCTVSRC"]) && !empty($_POST["TGLAKRPLGCCTVSRC"]) || isset($_POST["KEYPLGCCTVSRC"]) && !empty($_POST["KEYPLGCCTVSRC"])){

        //Get all data
        $office = $_POST['OFFPLGCCTVSRC'];
        $dept = $_POST['DEPTPLGCCTVSRC'];
        $tglawal = $_POST['TGLAWLPLGCCTVSRC'];
        $tglakhir = $_POST['TGLAKRPLGCCTVSRC'];
        $keyid = $_POST['KEYPLGCCTVSRC'];

        $s_key = '%'. $keyid .'%';
        
        $no = 1;

        $sql = "SELECT A.*, B.id_office, C.id_department, D.id_divisi, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.kode_head_bag_cctv, G.no_lay_cctv, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, K.name_fup_plg FROM pelanggaran_cctv AS A
        INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
        INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
        INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
        LEFT JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
        LEFT JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
        LEFT JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
        LEFT JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
        LEFT JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
        LEFT JOIN users AS J ON A.user_plg_cctv = J.nik
        LEFT JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
        WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND A.date_plg_cctv BETWEEN '$tglawal' AND '$tglakhir' AND (A.no_plg_cctv LIKE ? OR A.tgl_plg_cctv LIKE ? OR A.shift_plg_cctv LIKE ? OR D.divisi_name LIKE ? OR F.name_ctg_plg LIKE ? OR G.penempatan_lay_cctv LIKE ? OR K.name_fup_plg LIKE ?) ORDER BY A.no_plg_cctv ASC";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $s_key, $s_key, $s_key, $s_key, $s_key, $s_key, $s_key);
        mysqli_stmt_execute($stmt);

        // cek hasil query
        if (!$stmt) {
            die('Query Error : '.mysqli_errno($conn).' - '.mysqli_error($conn));
        }
        
        // ambil hasil query
        $result = mysqli_stmt_get_result($stmt);

        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            while ($data = mysqli_fetch_assoc($result)) { ?>
                
                <tr>
                    <td><?= $no++; ?></td>
                    <td><a title="Show Detail Data Pelanggaran Nomor : <?= $data['no_plg_cctv']; ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" name="read_datahplg" id="<?= $data["no_plg_cctv"]; ?>" class="text-bold-600 read_datahplg"><?= $data['no_plg_cctv']; ?></a></td>
                    <td><?= $data['tgl_plg_cctv']; ?></td>
                    <td><?= $data['shift_plg_cctv']; ?></td>
                    <td><?= $data['divisi_name']; ?></td>
                    <td><?= $data['ctg_plg_cctv']; ?></td>
                    <td><?= $data['dvr_plg_cctv']." | ".$data['lokasi_plg_cctv']; ?></td>
                    <td>
                        <?php
                        if ($data['status_plg_cctv'] == 'S') { ?>
                        <div class="badge badge-danger">BELUM FUP</div>
                        <?php }
                        elseif ($data['status_plg_cctv'] == 'N') { ?>
                        <div class="badge badge-warning">SUDAH FUP ATASAN BELUM DI APPROVE</div>
                        <?php }
                        elseif ($data['status_plg_cctv'] == 'Y') { ?>
                        <div class="badge badge-success">SUDAH FUP DAN SUDAH DI APPROVE</div>
                        <?php }
                        ?>
                    </td>
                    <td>
                        <a title="<?= $data['rekaman_plg_cctv'] != NULL ? 'Lihat Rekaman Pelanggaran Nomor : '.$data['no_plg_cctv'] : ''; ?>" onclick="window.open('', 'popupwindow', 'scrollbars=yes,resizable=yes,width=auto,height=auto');return true" target="popupwindow" href="<?= $data['rekaman_plg_cctv'] != NULL ? "files/record/index.php?id=".encrypt($data['rekaman_plg_cctv']) : '#'; ?>" class="<?= $data['rekaman_plg_cctv'] != NULL ? 'btn btn-icon btn-primary' : ''; ?>"><i class="<?= $data['rekaman_plg_cctv'] != NULL ? 'ft-film' : ''; ?>"></i></a>
                    </td>
                </tr>
                
            <?php }
        }
        else { ?>
            <tr>
                <td colspan='9'>No data available in table</td>
            </tr>
        <?php
        }
    }

    if(isset($_POST["RMHISTORYPLGCCTV"]) && !empty($_POST["RMHISTORYPLGCCTV"])){

        //Get all data
        $id = $_POST['RMHISTORYPLGCCTV'];

        $sql = "SELECT A.*, B.id_office, C.id_department, D.id_divisi, D.divisi_name, E.id_head_ctg_plg, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.kode_head_bag_cctv, G.no_lay_cctv, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.divisi_name AS area_cctv, J.username, K.name_fup_plg FROM pelanggaran_cctv AS A
        INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
        INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
        INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
        LEFT JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
        LEFT JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
        LEFT JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
        LEFT JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
        LEFT JOIN divisi AS I ON H.divisi_area_cctv = I.id_divisi
        LEFT JOIN users AS J ON A.user_plg_cctv = J.nik
        LEFT JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
        WHERE A.no_plg_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        ?>
            <div class="col-md-12 mb-2">
                <label>Nomor Pelanggaran : </label>
                <input type="text" class="form-control" value="<?= $data['no_plg_cctv']; ?>" disabled>
            </div>
            <div class="col-md-6 mb-2">
                <label>Tgl Kejadian : </label>
                <input type="text" class="form-control" value="<?= substr($data['tgl_plg_cctv'], 0, -9); ?>" disabled>
            </div>
            <div class="col-md-6 mb-2">
                <label>Waktu Kejadian : </label>
                <input type="text" class="form-control" value="<?= substr($data['tgl_plg_cctv'], 11, 5); ?>" disabled>
            </div>
            <div class="col-md-12 mb-2">
                <label>Kategori Pelanggaran :</label>
                <textarea class="form-control" type="text" disabled><?= $data['ctg_plg_cctv']; ?></textarea>
            </div>
            <div class="col-md-12 mb-2">
                <label>Jenis Pelanggaran :</label>
                <textarea class="form-control" type="text" disabled><?= $data['jns_plg_cctv']; ?></textarea>
            </div>
            <div class="col-md-6 mb-2">
                <label>Area CCTV : </label>
                <input type="text" class="form-control" value="<?= $data['dvr_plg_cctv']; ?>" disabled>
            </div>
            <div class="col-md-6 mb-2">
                <label>Lokasi CCTV : </label>
                <input type="text" class="form-control" value="<?= $data['lokasi_plg_cctv']; ?>" disabled>
            </div>
            <div class="col-md-6 mb-2">
                <label>User Pelapor Pelanggaran : </label>
                <input type="text" class="form-control" value="<?= $data['user_plg_cctv']." - ".strtoupper($data['username']); ?>" disabled>
            </div>
            <div class="col-md-6 mb-2">
                <label>User Pelanggar : </label>
                <input type="text" class="form-control" value="<?= $data['tersangka_plg_cctv'] == NULL ? '-' : $data['tersangka_plg_cctv']; ?>" disabled>
            </div>
        <?php
    }

    if(isset($_POST["OFFICEmusnah"]) && !empty($_POST["OFFICEmusnah"]) &&  isset($_POST["DEPTmusnah"]) && !empty($_POST["DEPTmusnah"])){

        $row = array();
        $off_musnah = mysqli_real_escape_string($conn, $_POST['OFFICEmusnah']);
        $dep_musnah = mysqli_real_escape_string($conn, $_POST['DEPTmusnah']);

        $sql = "SELECT * FROM signature WHERE office_sign = '$off_musnah' AND dept_sign = '$dep_musnah'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["IDSO"]) && !empty($_POST["IDSO"]) && isset($_POST["LOKASISO"]) && !empty($_POST["LOKASISO"]) && isset($_POST["STATUSSO"]) && !empty($_POST["STATUSSO"])){

        $id = mysqli_real_escape_string($conn, $_POST['IDSO']);
        $lokasi = mysqli_real_escape_string($conn, strtoupper($_POST['LOKASISO']));
        $kondisi = substr(mysqli_real_escape_string($conn, $_POST['STATUSSO']), 0, 2);

        $qty = 1;
        $query_asset = mysqli_query($conn, "SELECT * FROM asset_stock_opname WHERE id_so_asset = '$id'");
        $data_asset = mysqli_fetch_assoc($query_asset);

        $so_head = $data_asset["noref_so_asset"];
        $pluid = $data_asset["pluid_so_asset"];
        $kondisi_old = $data_asset["kondisi_so_asset"];

        $query_fisik = mysqli_query($conn, "SELECT fisik_so FROM detail_stock_opname WHERE no_so_head = '$so_head' AND pluid_so = '$pluid'");
        $data_fisik = mysqli_fetch_assoc($query_fisik);
        $dataqty = $data_fisik["fisik_so"];

        if($data_asset["kondisi_so_asset"] === NULL && $data_asset["lokasi_so_asset"] === NULL) {
            if ($kondisi != "07") {
                $fisik = ($dataqty+$qty);
                mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = '$fisik' WHERE no_so_head = '$so_head' AND pluid_so = '$pluid'");
            }
        }
        else {
            if ($kondisi_old == "07") {
                if ($kondisi != "07") {
                    $fisik = ($dataqty+$qty);
                    mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = '$fisik' WHERE no_so_head = '$so_head' AND pluid_so = '$pluid'");
                }
            }
            else{
                if ($kondisi == "07") {
                    $fisik = ($dataqty-$qty);
                    mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = '$fisik' WHERE no_so_head = '$so_head' AND pluid_so = '$pluid'");
                }
            }
        }

        // Update to database
        mysqli_query($conn, "UPDATE asset_stock_opname SET kondisi_so_asset = '$kondisi', lokasi_so_asset = '$lokasi' WHERE id_so_asset = '$id'");

    }

    if(isset($_POST["IDSO"]) && !empty($_POST["IDSO"]) && isset($_POST["FISIKSO"]) && !empty($_POST["FISIKSO"]) && isset($_POST["KETSO"]) && !empty($_POST["KETSO"])){

        $id = mysqli_real_escape_string($conn, $_POST['IDSO']);
        $fisik = mysqli_real_escape_string($conn, $_POST['FISIKSO']);
        $ket = mysqli_real_escape_string($conn, strtoupper($_POST['KETSO']));

        // Update to database
        mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = '$fisik', keterangan_so = '$ket' WHERE no_so_detail = '$id'");

        echo $fisik;
    }

    if(isset($_POST["DETAILDATASANKSIPLG"]) && !empty($_POST["DETAILDATASANKSIPLG"])){

        $id = $_POST['DETAILDATASANKSIPLG'];
        $office = substr($id, 0, 4);
        $dept = substr($id, 4, 4);
        $nik = substr($id, 8, 10);

        $no = 1;
        $sql = "SELECT A.username_plg_cctv, B.*, C.id_office, D.id_department, E.id_divisi, E.divisi_name, F.id_head_ctg_plg, F.name_jns_plg, G.id_ctg_plg, G.name_ctg_plg, H.kode_head_bag_cctv, H.no_lay_cctv, H.channel_lay_cctv, H.penempatan_lay_cctv, I.kode_area_cctv, I.ip_area_cctv, J.name_fup_plg FROM user_pelanggaran_cctv AS A
        INNER JOIN pelanggaran_cctv AS B ON A.head_no_plg_cctv = B.no_plg_cctv
        INNER JOIN office AS C ON B.office_plg_cctv = C.id_office
        INNER JOIN department AS D ON B.dept_plg_cctv = D.id_department
        INNER JOIN divisi AS E ON B.div_plg_cctv = E.id_divisi
        INNER JOIN jenis_pelanggaran AS F ON B.id_head_jns_plg = F.id_jns_plg
        INNER JOIN category_pelanggaran AS G ON F.id_head_ctg_plg = G.id_ctg_plg
        INNER JOIN layout_cctv AS H ON B.id_head_lay_cctv = H.id_lay_cctv
        INNER JOIN area_cctv AS I ON H.head_id_area_cctv = I.id_area_cctv
        INNER JOIN fup_pelanggaran AS J ON B.fup_plg_cctv = J.id_fup_plg
        WHERE LEFT(A.username_plg_cctv, 10) = '$nik' AND B.office_plg_cctv = '$office' AND B.dept_plg_cctv = '$dept' AND B.status_plg_cctv = 'Y' ORDER BY A.head_no_plg_cctv ASC";

        $query = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($query);

        if ($rowCount > 0) { ?>
        <div class="form-row" >
            <div class="col-md-12 mb-2">
                <label>USER PELANGGAR</label>
                <input type="text" class="form-control" value="<?= substr($id, 8); ?>" disabled>
            </div>
            <div class="col-md-12 mb-2">
                <label>DATA PELANGGARAN</label>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Pelanggaran</th>
                            <th>Tgl Waktu Kejadian</th>
                            <th>Shift</th>
                            <th>Divisi / Bagian</th>
                            <th>Kategori Pelanggaran</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Area Lokasi CCTV</th>
                            <th>Sanksi</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)) { 
                            if ($data['fup_plg_cctv'] == "1") {
                                $color_fup = "secondary";
                            }
                            elseif ($data['fup_plg_cctv'] == "3") {
                                $color_fup = "warning";
                            }
                            elseif ($data['fup_plg_cctv'] == "4") {
                                $color_fup = "danger";
                            }
                            elseif ($data['fup_plg_cctv'] == "5") {
                                $color_fup = "info";
                            }
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $data['no_plg_cctv']; ?></strong></td>
                            <td><?= $data['tgl_plg_cctv']; ?></td>
                            <td><?= $data['shift_plg_cctv']; ?></td>
                            <td><?= $data['divisi_name']; ?></td>
                            <td><?= $data['id_ctg_plg'].". ".$data['name_ctg_plg']; ?></td>
                            <td><?= $data['name_jns_plg']; ?></td>
                            <td><?= $data['kode_head_bag_cctv'].".".$data['no_lay_cctv']." ".$data['penempatan_lay_cctv']; ?></td>
                            <td>
                                <div class="badge badge-<?= $color_fup; ?> label-square">
                                    <i class="ft-info font-medium-2"></i>
                                    <span><?= $data['name_fup_plg']; ?></span>
                                </div>
                            </td>
                        </tr>
                <?php 
                    }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        }
        else { ?>
            <h4>
                <span class="text-bold-600">404</span> Data not found!
            </h4>
        <?php }
    }

    if(isset($_POST["DETAILPLGCCTV"]) && !empty($_POST["DETAILPLGCCTV"])){

        //Get all data
        $id = $_POST['DETAILPLGCCTV'];
        $nop = substr($id, 0, 6);
        $office = substr($id, 6, 4);
        $dept = substr($id, 10, 4);

        $sql = "SELECT A.no_plg_cctv, A.user_plg_cctv, A.tersangka_plg_cctv, A.ctg_plg_cctv, A.jns_plg_cctv, A.dvr_plg_cctv, A.lokasi_plg_cctv, LEFT(A.tgl_plg_cctv, 10) AS data_tgl_plg, RIGHT(A.tgl_plg_cctv, 8) AS data_wkt_plg, D.divisi_name, E.name_jns_plg, F.id_ctg_plg, F.name_ctg_plg, G.kode_head_bag_cctv, G.no_lay_cctv, G.channel_lay_cctv, G.penempatan_lay_cctv, H.kode_area_cctv, H.ip_area_cctv, I.username, J.name_fup_plg FROM pelanggaran_cctv AS A
        INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
        LEFT JOIN jenis_pelanggaran AS E ON A.id_head_jns_plg = E.id_jns_plg
        LEFT JOIN category_pelanggaran AS F ON E.id_head_ctg_plg = F.id_ctg_plg
        LEFT JOIN layout_cctv AS G ON A.id_head_lay_cctv = G.id_lay_cctv
        LEFT JOIN area_cctv AS H ON G.head_id_area_cctv = H.id_area_cctv
        INNER JOIN users AS I ON A.user_plg_cctv = I.nik
        INNER JOIN fup_pelanggaran AS J ON A.fup_plg_cctv = J.id_fup_plg
        WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND A.no_plg_cctv = '$nop'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEPLGCCTV"]) && !empty($_POST["UPDATEPLGCCTV"])){

        //Get all data
        $id = $_POST['UPDATEPLGCCTV'];

        $sql = "SELECT A.id_plg_cctv, A.no_plg_cctv, A.tgl_plg_cctv, A.shift_plg_cctv, A.kejadian_plg_cctv, A.ket_plg_cctv, A.rekaman_plg_cctv, A.div_plg_cctv, B.divisi_name FROM pelanggaran_cctv AS A
        INNER JOIN divisi AS B ON A.div_plg_cctv = B.id_divisi
        WHERE A.id_plg_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEPLGCCTV"]) && !empty($_POST["DELETEPLGCCTV"])){

        //Get all data
        $id = $_POST['DELETEPLGCCTV'];

        $sql = "SELECT id_plg_cctv, no_plg_cctv, rekaman_plg_cctv FROM pelanggaran_cctv WHERE id_plg_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["READUSERNIK"]) && !empty($_POST["READUSERNIK"])){

        //Get all data
        $id = $_POST['READUSERNIK'];

        $sql = "SELECT A.nik, A.username, A.email, A.ip_address, A.id_office, A.id_department, A.id_divisi, A.id_group, A.id_level, A.akses_ip, B.office_name, C.department_name, D.divisi_name, E.level_name, F.group_name FROM users AS A
        INNER JOIN office AS B ON A.id_office = B.id_office
        LEFT JOIN department AS C ON A.id_department = C.id_department
        LEFT JOIN divisi AS D ON A.id_divisi = D.id_divisi
        LEFT JOIN level AS E ON A.id_level = E.id_level
        LEFT JOIN groups AS F ON A.id_group = F.id_group
        WHERE A.nik = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["EDITUSERNIK"]) && !empty($_POST["EDITUSERNIK"])){

        //Get all data
        $id = $_POST['EDITUSERNIK'];

        $sql = "SELECT A.nik, A.username, A.full_name, A.id_level, B.level_name FROM users AS A 
        LEFT JOIN level AS B ON A.id_level = B.id_level
        WHERE A.nik = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEUSERNIK"]) && !empty($_POST["UPDATEUSERNIK"])){

        //Get all data
        $id = $_POST['UPDATEUSERNIK'];

        $sql = "SELECT A.id, A.nik, A.username, A.full_name, A.ip_address, A.id_office, A.id_department, A.id_divisi, A.id_group, A.id_level, A.akses_ip, A.status, B.office_name, C.department_name, D.divisi_name, E.level_name, F.group_name FROM users AS A 
        INNER JOIN office AS B ON A.id_office = B.id_office
        LEFT JOIN department AS C ON A.id_department = C.id_department
        LEFT JOIN divisi AS D ON A.id_divisi = D.id_divisi
        LEFT JOIN level AS E ON A.id_level = E.id_level
        LEFT JOIN groups AS F ON A.id_group = F.id_group
        WHERE A.id = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEUSERNIK"]) && !empty($_POST["DELETEUSERNIK"])){

        //Get all data
        $id = $_POST['DELETEUSERNIK'];

        $sql = "SELECT nik FROM users WHERE nik = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEIPAD"]) && !empty($_POST["UPDATEIPAD"])){

        //Get all data
        $id = $_POST['UPDATEIPAD'];

        $sql = "SELECT A.*, B.id_iseg, B.name_iseg FROM ip_address AS A
                INNER JOIN ip_segment AS B ON A.seg_ipad = B.id_iseg
                WHERE A.id_ipad = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEIPAD"]) && !empty($_POST["DELETEIPAD"])){

        //Get all data
        $id = $_POST['DELETEIPAD'];

        $sql = "SELECT id_ipad, ip_ipad FROM ip_address WHERE id_ipad = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEDELIVAN"]) && !empty($_POST["UPDATEDELIVAN"])){

        //Get all data
        $id = $_POST['UPDATEDELIVAN'];

        $sql = "SELECT A.*, B.nama_type_mobil, C.* FROM mobil AS A
                INNER JOIN tipe_mobil AS B ON A.type_kode_mobil = B.kode_type_mobil
                LEFT JOIN jenis_mobil AS C ON A.jenis_mobil = C.no_jns_mobil
                WHERE A.id_mobil = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEDELIVAN"]) && !empty($_POST["DELETEDELIVAN"])){

        //Get all data
        $id = $_POST['DELETEDELIVAN'];

        $sql = "SELECT A.*, B.nama_type_mobil, C.office_shortname FROM mobil AS A
                INNER JOIN tipe_mobil AS B ON A.type_kode_mobil = B.kode_type_mobil
                INNER JOIN office AS C ON A.office_mobil = C.id_office
                WHERE A.id_mobil = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEREPASNIK"]) && !empty($_POST["DELETEREPASNIK"])){

        //Get all data
        $id = $_POST['DELETEREPASNIK'];

        $sql = "SELECT id_reset_pass, nik_reset FROM reset_pass WHERE id_reset_pass = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATECATBARANG"]) && !empty($_POST["UPDATECATBARANG"])){

        //Get all data
        $id = $_POST['UPDATECATBARANG'];

        $sql = "SELECT A.*, B.* FROM mastercategory AS A
                INNER JOIN satuan AS B ON A.id_satuan = B.id_satuan
                WHERE A.IDBarang = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETECATBARANG"]) && !empty($_POST["DELETECATBARANG"])){

        //Get all data
        $id = $_POST['DELETECATBARANG'];

        $sql = "SELECT IDBarang FROM mastercategory WHERE IDBarang = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATEJNSBARANG"]) && !empty($_POST["UPDATEJNSBARANG"])){

        //Get all data
        $id = $_POST['UPDATEJNSBARANG'];

        $sql = "SELECT A.*, B.* FROM mastercategory AS A
                INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang
                WHERE B.IDJenis = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEJNSBARANG"]) && !empty($_POST["DELETEJNSBARANG"])){

        //Get all data
        $id = $_POST['DELETEJNSBARANG'];

        $sql = "SELECT IDJenis FROM masterjenis WHERE IDJenis = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["RESETPLGCCTV"]) && !empty($_POST["RESETPLGCCTV"])){

        //Get all data
        $id = $_POST['RESETPLGCCTV'];

        $sql = "SELECT id_plg_cctv, no_plg_cctv FROM pelanggaran_cctv WHERE id_plg_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATELAYOUTINGCCTV"]) && !empty($_POST["UPDATELAYOUTINGCCTV"])){

        //Get all data
        $id = $_POST['UPDATELAYOUTINGCCTV'];

        $sql = "SELECT A.id_lay_cctv, A.kode_head_bag_cctv, A.no_lay_cctv, A.penempatan_lay_cctv, A.channel_lay_cctv, A.jenis_lay_cctv, B.id_area_cctv, B.ip_area_cctv, C.divisi_name, D.kode_bag_cctv, D.name_bag_cctv FROM layout_cctv AS A
                INNER JOIN area_cctv AS B ON A.head_id_area_cctv = B.id_area_cctv
                INNER JOIN divisi AS C ON B.divisi_area_cctv = C.id_divisi
                INNER JOIN bagian_cctv AS D ON A.kode_head_bag_cctv = D.kode_bag_cctv
                WHERE A.id_lay_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETELAYOUTINGCCTV"]) && !empty($_POST["DELETELAYOUTINGCCTV"])){

        //Get all data
        $id = $_POST['DELETELAYOUTINGCCTV'];

        $sql = "SELECT id_lay_cctv, kode_head_bag_cctv, no_lay_cctv, penempatan_lay_cctv, channel_lay_cctv, jenis_lay_cctv FROM layout_cctv
                WHERE id_lay_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEAREACCTV"]) && !empty($_POST["DELETEAREACCTV"])){

        //Get all data
        $id = $_POST['DELETEAREACCTV'];

        $sql = "SELECT id_area_cctv, ip_area_cctv FROM area_cctv
                WHERE id_area_cctv = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEAREAAPAR"]) && !empty($_POST["DELETEAREAAPAR"])){

        //Get all data
        $id = $_POST['DELETEAREAAPAR'];

        $sql = "SELECT id_layout, layout_name FROM layout_apar
                WHERE id_layout = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETESERIALNUMBER"]) && !empty($_POST["DELETESERIALNUMBER"])){

        //Get all data
        $id = $_POST['DELETESERIALNUMBER'];

        $sql = "SELECT id_serial_number, nomor_serial_number FROM serial_number
                WHERE id_serial_number = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATECATPELANGGARAN"]) && !empty($_POST["UPDATECATPELANGGARAN"])){

        //Get all data
        $id = $_POST['UPDATECATPELANGGARAN'];

        $sql = "SELECT * FROM category_pelanggaran
                WHERE id_ctg_plg = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONJNSPELANGGARAN"]) && !empty($_POST["ACTIONJNSPELANGGARAN"])){

        //Get all data
        $id = $_POST['ACTIONJNSPELANGGARAN'];

        $sql = "SELECT A.*, B.* FROM jenis_pelanggaran AS A 
        INNER JOIN category_pelanggaran AS B ON A.id_head_ctg_plg = B.id_ctg_plg
        WHERE A.id_jns_plg = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["PERIODEBUDGETDEPT"]) && !empty($_POST["PERIODEBUDGETDEPT"])){

        //Get all data
        $id = $_POST['PERIODEBUDGETDEPT'];

        $sql = "SELECT * FROM statusbudget WHERE id_sb = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DETAILPP"]) && !empty($_POST["DETAILPP"])){

        $id = $_POST['DETAILPP'];

        $sql = "SELECT A.*, B.*, C.NamaBarang, D.NamaJenis, E.id_office, E.office_name, F.id_department, F.department_name, G.nik, G.username, H.*, I.id_office AS id_office_to, I.office_name AS id_office_name, J.id_department AS id_dept_to, J.department_name AS id_dept_name, K.status_penerimaan FROM pembelian AS A
        INNER JOIN detail_pembelian AS B ON A.noref = B.noref
        INNER JOIN mastercategory AS C ON LEFT(B.plu_id, 6) = C.IDBarang
        INNER JOIN masterjenis AS D ON RIGHT(B.plu_id, 4) = D.IDJenis
        INNER JOIN office AS E ON A.id_office = E.id_office
        INNER JOIN department AS F ON A.id_department = F.id_department
        INNER JOIN users AS G ON A.user = G.nik
        INNER JOIN status_pembelian AS H ON A.status_pp = H.id_spp
        INNER JOIN office AS I ON A.office_to = I.id_office
        INNER JOIN department AS J ON A.department_to = J.id_department
        LEFT JOIN detail_penerimaan_pembelian AS K ON B.id_dpp = K.id_dpp_penerimaan
        WHERE A.id_pembelian = '$id'";

        $query_header = mysqli_query($conn, $sql);
        $data_header = mysqli_fetch_assoc($query_header);
        $rowCount = mysqli_num_rows($query_header);

        if ($rowCount > 0) { ?>
        <div class="form-row">
            <div class="col-md-12 mb-2">
                <label>NOMOR PP </label>
                <input class="form-control text-bold-600" type="text" value="<?= $data_header['ppid']; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>DARI </label>
                <input class="form-control" type="text" value="<?= $data_header['id_office']." - ".strtoupper($data_header['office_name'])." DEPT. ".strtoupper($data_header['department_name']);?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>TUJUAN </label>
                <input class="form-control" type="text" value="<?= $data_header['id_office_to']." - ".strtoupper($data_header['id_office_name'])." DEPT. ".strtoupper($data_header['id_dept_name']);?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
            <label>JENIS PP </label>
            <?php
            if (substr($data_header['ppid'], 0, 3) == "PPB") {
                $pp = "BUDGET";
            }
            elseif (substr($data_header['ppid'], 0, 3) == "PPG") {
                $pp = "REGULER";
            }
            elseif (substr($data_header['ppid'], 0, 3) == "PPM") {
                $pp = "MUSNAH";
            }
            ?>
                <input class="form-control" type="text" value="<?= $pp; ?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
            <label>DI PROSES </label>
                <input class="form-control" type="text" value="<?= $data_header['user']." - ".strtoupper($data_header['username']);?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
            <label>TANGGAL PENGAJUAN </label>
                <input class="form-control" type="text" value="<?= $data_header['tgl_pengajuan'] ? $data_header['tgl_pengajuan'] : '-'; ?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
            <label>NO SP </label>
                <input class="form-control" type="text" value="<?php if(!$data_header['spno'] || empty($data_header['spno'])) { echo '-'; } else { echo $data_header['spno']; }?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>KEPERLUAN </label>
                <textarea class="form-control" type="text" readonly><?= $data_header["keperluan"];?></textarea>
            </div>
            <div class="col-md-6 mb-2">
                <label>KETERANGAN </label>
                <textarea class="form-control" type="text" readonly><?= $data_header["reminder"];?></textarea>
            </div>
            <div class="col-md-12 mb-2">
                <label>ITEM BARANG</label>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>KODE BARANG</th>
                            <th>NAMA BARANG</th>
                            <th>QTY</th>
                            <th>UNIT COST</th>
                            <th>SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    $no = 1;
                    $nol = 0;
                    $query_detail = mysqli_query($conn, $sql);
                    while ($data_detail = mysqli_fetch_assoc($query_detail)) {
                        if ($data_detail['status_pp'] === $arrsp[9] || $data_detail['status_pp'] === $arrsp[10]) {
                            $rows_rcv = $data_detail['status_penerimaan'] === "Y" ? 'class="bg-info white"' : "";
                        }
                ?>
                        <tr <?= isset($rows_rcv) ? $rows_rcv : NULL; ?>>
                            <th scope="row"><?= $no++;?></th>
                            <td><?= $data_detail['plu_id'];?></td>
                            <td><?= $data_detail['NamaBarang']." ".$data_detail['NamaJenis']." ".$data_detail['merk']." ".$data_detail['tipe'];?></td>
                            <td><?= $qty = $data_detail['qty'];?></td>
                            <td><?='Rp. '. number_format(($data_detail['harga_pp'] / $qty),2);?></td>
                            <td><?= 'Rp. '.number_format($subtotal = $data_detail['harga_pp'], 2);?></td>
                            <?php $total = $nol+=$subtotal; ?>
                        </tr>
                <?php 
                    }
                ?>
                        <tr>
                            <th colspan="5">TOTAL</th>
                            <th colspan="1"><?='Rp. ' .number_format($total,2); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        }
    }

    if(isset($_POST["ACTIONPP"]) && !empty($_POST["ACTIONPP"])){

        $id = $_POST['ACTIONPP'];

        $sql = "SELECT id_pembelian, ppid, noref, ref_musnah, id_office, id_department FROM pembelian
                WHERE id_pembelian = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONUPDATEPP"]) && !empty($_POST["ACTIONUPDATEPP"])){

        $id = $_POST['ACTIONUPDATEPP'];

        $sql = "SELECT detail_pembelian.*, pembelian.id_pembelian, pembelian.ppid, mastercategory.NamaBarang, masterjenis.NamaJenis, masterjenis.HargaJenis FROM detail_pembelian
                INNER JOIN pembelian ON detail_pembelian.noref = pembelian.noref
                INNER JOIN mastercategory ON LEFT(detail_pembelian.plu_id, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_pembelian.plu_id, 4) = masterjenis.IDJenis
                WHERE detail_pembelian.id_dpp = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DELETEDOC"]) && !empty($_POST["DELETEDOC"])){

        $id = $_POST['DELETEDOC'];

        $sql = "SELECT * FROM dokumen WHERE id_doc = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONDETAILPP"]) && !empty($_POST["ACTIONDETAILPP"])){

        $id = $_POST['ACTIONDETAILPP'];

        $sql = "SELECT detail_pembelian.plu_id AS KODE_BARANG, detail_pembelian.qty AS QTY, detail_pembelian.harga_pp AS SUB_TOTAL, detail_pembelian.keterangan AS KETERANGAN, CONCAT(mastercategory.NamaBarang, ' ', masterjenis.NamaJenis) AS NAMA_BARANG, masterjenis.HargaJenis AS UNIT_COST, IF(detail_penerimaan_pembelian.status_penerimaan IS NULL, '-', detail_penerimaan_pembelian.status_penerimaan) AS STATUS_RCV_PP FROM detail_pembelian
                INNER JOIN mastercategory ON LEFT(detail_pembelian.plu_id, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_pembelian.plu_id, 4) = masterjenis.IDJenis
                LEFT JOIN detail_penerimaan_pembelian ON detail_pembelian.id_dpp = detail_penerimaan_pembelian.id_dpp_penerimaan
                WHERE detail_pembelian.noref = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["IDBUDGET"]) && !empty($_POST["IDBUDGET"]) && isset($_POST["SALDOBUDGET"]) && !empty($_POST["SALDOBUDGET"])){

        $id = mysqli_real_escape_string($conn, $_POST['IDBUDGET']);
        $saldo = mysqli_real_escape_string($conn, $_POST['SALDOBUDGET']);

        // Update to database
        mysqli_query($conn, "UPDATE budget SET stock_budget = '$saldo' WHERE id_budget = '$id'");
    }

    if(isset($_POST["DELETEDATASO"]) && !empty($_POST["DELETEDATASO"])){

        $id = $_POST['DELETEDATASO'];

        $sql = "SELECT no_so FROM head_stock_opname
                WHERE no_so = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["DETAILDATASO"]) && !empty($_POST["DETAILDATASO"])){

        $id = $_POST['DETAILDATASO'];

        $sql = "SELECT A.*, B.*, C.IDJenis, C.NamaJenis, D.nama_satuan, E.saldo_awal, E.saldo_akhir, COUNT(F.pluid_so_asset) AS total FROM detail_stock_opname AS A 
        INNER JOIN mastercategory AS B ON LEFT(A.pluid_so, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_so, 4) = C.IDJenis
        INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
        INNER JOIN masterstock AS E ON A.pluid_so = E.pluid
        INNER JOIN asset_stock_opname AS F ON A.pluid_so = F.pluid_so_asset
        WHERE A.no_so_head = '$id' GROUP BY A.pluid_so ";

        $query_header = mysqli_query($conn, $sql);
        $data_header = mysqli_fetch_assoc($query_header);
        $rowCount = mysqli_num_rows($query_header);

        if ($rowCount > 0) { ?>
        <div class="form-row">
            <div class="col-md-12 mb-2">
                <label>DAFTAR BARANG SO</label>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2">KODE BARANG</th>
                            <th rowspan="2">NAMA BARANG</th>
                            <th rowspan="2">SATUAN</th>
                            <th rowspan="2">SALDO AWAL</th>
                            <th rowspan="2">DAT</th>
                            <th rowspan="2">FISIK</th>
                            <th colspan="2">SELISIH</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $nol = [ 0, 0, 0, 0, 0 ];
                    $query_detail = mysqli_query($conn, $sql);
                    while ($data_detail = mysqli_fetch_assoc($query_detail)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $no++;?></th>
                            <td><?= $data_detail['pluid_so'];?></td>
                            <td><?= $data_detail['NamaBarang']." ".$data_detail['NamaJenis'];?></td>
                            <td><?= $data_detail['nama_satuan'];?></td>
                            <td><?= $stock = $data_detail['saldo_akhir'];?></td>
                            <td><?= $asset = $data_detail['total'];?></td>
                            <td><?= $fisik = $data_detail['fisik_so'] == NULL ? 0 : $data_detail['fisik_so'];?></td>
                            <td><?= $selisih_stock = ($fisik - $stock);?></td>
                            <td><?= $selisih_asset = ($fisik - $asset);?></td>
                            <?php
                                $total_stock = ($nol[0]+=$stock);
                                $total_asset = ($nol[1]+=$asset);
                                $total_fisik = ($nol[2]+=$fisik);
                                $total_selisih_stock = ($nol[3]+=$selisih_stock);
                                $total_selisih_asset = ($nol[4]+=$selisih_asset);
                            ?>
                        </tr>
                    <?php 
                        }
                    ?>
                        <?php if (isset($total_stock) && isset($total_asset) && isset($total_fisik) && isset($total_selisih_stock) && isset($total_selisih_asset)) { ?>
                        <tr>
                            <th colspan="4">TOTAL :</th>
                            <td><?= $total_stock;?></td>
                            <td><?= $total_asset;?></td>
                            <td><?= $total_fisik;?></td>
                            <td><?= $total_selisih_stock;?></td>
                            <td><?= $total_selisih_asset;?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        }
    }

    if(isset($_POST["DETAILDATASONA"]) && !empty($_POST["DETAILDATASONA"])){

        $id = $_POST['DETAILDATASONA'];

        $sql = "SELECT A.*, B.*, C.IDJenis, C.NamaJenis, D.nama_satuan FROM detail_stock_opname AS A 
        INNER JOIN mastercategory AS B ON LEFT(A.pluid_so, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_so, 4) = C.IDJenis
        INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
        WHERE A.no_so_head = '$id'";

        $query_header = mysqli_query($conn, $sql);
        $data_header = mysqli_fetch_assoc($query_header);
        $rowCount = mysqli_num_rows($query_header);

        if ($rowCount > 0) { ?>
        <div class="form-row">
            <div class="col-md-12 mb-2">
                <label>DAFTAR BARANG SO</label>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2">KODE BARANG</th>
                            <th rowspan="2">NAMA BARANG</th>
                            <th rowspan="2">SATUAN</th>
                            <th rowspan="2">SALDO AWAL</th>
                            <th rowspan="2">DAT</th>
                            <th rowspan="2">FISIK</th>
                            <th colspan="2">SELISIH</th>
                        </tr>
                        <tr>
                            <th>SALDO</th>
                            <th>DAT</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $nol = [ 0, 0, 0, 0, 0 ];
                    $query_detail = mysqli_query($conn, $sql);
                    while ($data_detail = mysqli_fetch_assoc($query_detail)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $no++;?></th>
                            <td><?= $data_detail['pluid_so'];?></td>
                            <td><?= $data_detail['NamaBarang']." ".$data_detail['NamaJenis'];?></td>
                            <td><?= $data_detail['nama_satuan'];?></td>
                            <td><?= $stock = $data_detail['saldo_so'];?></td>
                            <td><?= $asset = $data_detail['asset_so'];?></td>
                            <td><?= $fisik = $data_detail['fisik_so'] == NULL ? 0 : $data_detail['fisik_so']; ?></td>
                            <td><?= $selisih_stock = ($fisik - $stock);?></td>
                            <td><?= $selisih_asset = ($fisik - $asset);?></td>
                            <?php
                                $total_stock = ($nol[0]+=$stock);
                                $total_asset = ($nol[1]+=$asset);
                                $total_fisik = ($nol[2]+=$fisik);
                                $total_selisih_stock = ($nol[3]+=$selisih_stock);
                                $total_selisih_asset = ($nol[4]+=$selisih_asset);
                            ?>
                        </tr>
                    <?php 
                        }
                    ?>
                        <?php if (isset($total_stock) && isset($total_asset) && isset($total_fisik) && isset($total_selisih_stock) && isset($total_selisih_asset)) { ?>
                        <tr>
                            <th colspan="4">TOTAL :</th>
                            <td><?= $total_stock;?></td>
                            <td><?= $total_asset;?></td>
                            <td><?= $total_fisik;?></td>
                            <td><?= $total_selisih_stock;?></td>
                            <td><?= $total_selisih_asset;?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        }
    }

    if(isset($_POST["DELETEDATASOAPAR"]) && !empty($_POST["DELETEDATASOAPAR"])){

        $id = $_POST['DELETEDATASOAPAR'];

        $sql = "SELECT id_head_so_apar FROM head_so_apar
                WHERE id_head_so_apar = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["UPDATESOAPAR"]) && !empty($_POST["UPDATESOAPAR"])){

        $id = mysqli_real_escape_string($conn, $_POST['UPDATESOAPAR']);

        $sql = "SELECT A.*, B.id_layout, B.layout_name FROM so_apar AS A
        INNER JOIN layout_apar AS B ON A.posisi_so_apar = B.id_layout
        WHERE A.id_so_apar = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));

    }

    if(isset($_POST["ACTIONDETAILPROJECT"]) && !empty($_POST["ACTIONDETAILPROJECT"])){

        $id = $_POST['ACTIONDETAILPROJECT'];

        $sql = "SELECT A.*, IF(A.user_project_task IS NULL, '-', A.user_project_task) AS USER_PRYK, IF(A.efektif_project_task IS NULL, '-', A.efektif_project_task) AS EFEKTIF_PRYK, IF(A.ket_project_task IS NULL, '-', A.ket_project_task) AS KET_PRYK, IF(A.status_project_task = 'Y', 'SELESAI', 'PROSES') AS STS_PRYK, B.username AS user_pengerja FROM project_task AS A
                LEFT JOIN users AS B ON A.user_project_task = B.nik
                WHERE A.ref_project_task = '$id' ORDER BY A.urutan_project_task ASC";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["ACTIONPROJECT"]) && !empty($_POST["ACTIONPROJECT"])){

        $id = $_POST['ACTIONPROJECT'];

        $sql = "SELECT id_head_project, no_head_project, doc_head_project FROM head_project
                WHERE no_head_project = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONUPDATEPROJECT"]) && !empty($_POST["ACTIONUPDATEPROJECT"])){

        $id = $_POST['ACTIONUPDATEPROJECT'];

        $sql = "SELECT * FROM project_task
                WHERE id_project_task = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["IDTASK"]) && !empty($_POST["IDTASK"]) && isset($_POST["URUTTASK"]) && !empty($_POST["URUTTASK"]) && isset($_POST["PICTASK"]) && !empty($_POST["PICTASK"]) && isset($_POST["KETTASK"]) && !empty($_POST["KETTASK"]) && isset($_POST["JUMLAHTASK"]) && !empty($_POST["JUMLAHTASK"]) && isset($_POST["SULITTASK"]) && !empty($_POST["SULITTASK"])){

        $id = mysqli_real_escape_string($conn, $_POST['IDTASK']);
        $urut = mysqli_real_escape_string($conn, $_POST['URUTTASK']);
        $pic = mysqli_real_escape_string($conn, $_POST['PICTASK']);
        $ket = mysqli_real_escape_string($conn, $_POST['KETTASK']);
        $jumlah = mysqli_real_escape_string($conn, $_POST['JUMLAHTASK']);
        $sulit = mysqli_real_escape_string($conn, $_POST['SULITTASK']);

        // Update to database
        mysqli_query($conn, "UPDATE project_task SET urutan_project_task = '$urut', pic_project_task = '$pic', pengerjaan_project_task = '$ket', jumlah_project_task = '$jumlah', priority_project_task = '$sulit' WHERE id_project_task = '$id'");

    }

    if(isset($_POST["ACTIONP3AT"]) && !empty($_POST["ACTIONP3AT"])){

        $id = $_POST['ACTIONP3AT'];

        $sql = "SELECT id_p3at, no_p3at FROM p3at
                WHERE id_p3at = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONDETAILP3AT"]) && !empty($_POST["ACTIONDETAILP3AT"])){

        $id = $_POST['ACTIONDETAILP3AT'];

        $sql = "SELECT detail_p3at.pluid_p3at AS KODE_BARANG, detail_p3at.sn_p3at AS SN, detail_p3at.at_p3at AS DAT, detail_p3at.th_p3at AS THN, CONCAT(mastercategory.NamaBarang, ' ', masterjenis.NamaJenis) AS NAMA_BARANG, IF(detail_p3at.nomor_musnah IS NULL, '-', detail_p3at.nomor_musnah) AS NOMOR_MUSNAH, IF(detail_p3at.tgl_approve IS NULL, '-', detail_p3at.tgl_approve) AS TGL_MUSNAH FROM detail_p3at
                INNER JOIN mastercategory ON LEFT(detail_p3at.pluid_p3at, 6) = mastercategory.IDBarang
                INNER JOIN masterjenis ON RIGHT(detail_p3at.pluid_p3at, 4) = masterjenis.IDJenis
                WHERE detail_p3at.id_head_p3at = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["KEHADIRANUSERS"]) && !empty($_POST["KEHADIRANUSERS"])){

        //Get all data
        $id = $_POST['KEHADIRANUSERS'];
        $nik = substr($id, 0, 10);

        $sql = "SELECT A.nik, B.divisi_name FROM users AS A
        LEFT JOIN divisi AS B ON A.id_divisi = B.id_divisi
        WHERE A.nik = '$id'";
        
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONJENISDELIVAN"]) && !empty($_POST["ACTIONJENISDELIVAN"])){

        $id = mysqli_real_escape_string($conn, $_POST['ACTIONJENISDELIVAN']);

        $sql = "SELECT * FROM jenis_mobil WHERE id_jns_mobil = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));

    }

    if(isset($_POST["LISTDELIVAN"]) && !empty($_POST["LISTDELIVAN"])){
        
        //Get all state data
        $office = mysqli_real_escape_string($conn, substr($_POST['LISTDELIVAN'], 0, 4));
        $id = mysqli_real_escape_string($conn, substr($_POST['LISTDELIVAN'], 4, 2));

        $sql = "SELECT A.*, B.office_shortname FROM mobil AS A
        INNER JOIN office AS B ON A.office_mobil = B.id_office
        WHERE A.office_mobil = '$office' AND A.jenis_mobil = '$id' ORDER BY A.no_mobil ASC";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="ALL">ALL</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'."'".$row['no_mobil']."'".'">'.$row['office_shortname'].$row['no_mobil'].'</option>';
            }
        }
        else{
            echo '<option value="ALL">ALL</option>';
        }
    }

    if(isset($_POST["OFFPRESENSI"]) && !empty($_POST["OFFPRESENSI"]) && isset($_POST["NIKPRESENSI"]) && !empty($_POST["NIKPRESENSI"]) && isset($_POST["TGLPRESENSI"]) && !empty($_POST["TGLPRESENSI"])){

        //Get all data
        $office = $_POST['OFFPRESENSI'];
        $nik = $_POST['NIKPRESENSI'];
        $tgl = $_POST['TGLPRESENSI'];

        $query_presensi = mysqli_query($conn, "SELECT no_presensi, cek_presensi FROM presensi WHERE office_presensi = '$office' AND nik_presensi = '$nik' AND tgl_presensi = '$tgl'");

        if (mysqli_num_rows($query_presensi) === 1) {

            $data_presensi = mysqli_fetch_assoc($query_presensi);

            $search_id = $data_presensi["no_presensi"];

            require __DIR__ . '../../vendor/autoload.php';

            $client = new Google_Client();
            $client->setApplicationName('Google Sheets and PHP');
            $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig('../includes/config/client_secret.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $service = new Google_Service_Sheets($client);
            $spreadsheetId = "110xb7dSbyPLWW9HvRkKqv_iWnviAlQnZzg0om8u_SBQ";

            // OPERASI READ
            $sheet = "SHEET_PRESENSI_IMS!";
            $range = "A2:L";
            $response = $service->spreadsheets_values->get($spreadsheetId, $sheet.$range);
            $result = $response->getValues();

            $rows = [];
            $no = 1;
            foreach ($result as $i => $v) {
                if ($v[2] == $search_id) {
                    $rows = $v;
                    if ($rows[9] == "CUTI" || $rows[9] == "CUTI MENDADAK" || $rows[9] == "SAKIT" || $rows[9] == "ALPA" || $rows[9] == "LIBUR PENGGANTI") {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" name="edit_cekold_hadir[]" value="<?= $rows[9]; ?>" class="form-control" readonly/>
                                <input type="hidden" name="edit_id_hadir[]" value="<?= $rows[2]; ?>" class="form-control" readonly/>
                                <span><?= $no++; ?></span>
                            </td>
                            <td>
                                <input type="hidden" name="edit_user_hadir[]" value="<?= $rows[5].' - '.$rows[6]; ?>" class="form-control" readonly/>
                                <span><?= $rows[5].' - '.$rows[6]; ?></span>
                            </td>
                            <td>
                                <input type="hidden" name="edit_bagian_hadir[]" value="<?= $rows[7]; ?>" class="form-control" readonly/>
                                <span><?= $rows[7]; ?></span>
                            </td>
                            <td>
                                <input type="hidden" name="edit_tgl_hadir[]" value="<?= $rows[8]; ?>" class="form-control" readonly/>
                                <span><?= $rows[8]; ?></span>
                            </td>
                            <td>
                                <select type="text" id="edit_cek_hadir" name="edit_cek_hadir[]" class="select form-control" required>
                                    <option value="" selected disabled>Please Select</option>
                                    <?php
                                        $cek = array('CUTI', 'CUTI MENDADAK', 'SAKIT', 'ALPA', 'LIBUR PENGGANTI');
                                        foreach ($cek as $c) {
                                    ?>
                                        <option value="<?= $c; ?>" <?= $c == $rows[9] ? 'selected' : ''; ?>><?= $c; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <textarea class="form-control" type="text" name="edit_ket_hadir[]" placeholder="Input keterangan (Optional)"><?= isset($rows[11]) ? $rows[11] : ""; ?></textarea>
                            </td>
                        </tr>
                        <?php
                    }
                    else {?>
                        <tr id="loadpresensi-spinner">
                            <td colspan="6">No data available in table</td>
                        </tr>
                    <?php
                    }
                }
            }
        }
        else { ?>
            <tr id="loadpresensi-spinner">
                <td colspan="6">No data available in table</td>
            </tr>
        <?php
        }

    }

    if(isset($_POST["APPROVEEDITKEHADIRAN"]) && !empty($_POST["APPROVEEDITKEHADIRAN"])){

        $id = $_POST['APPROVEEDITKEHADIRAN'];

        $sql = "SELECT A.no_aprv_presensi, B.ref_data_presensi, B.ceknew_data_presensi, B.ket_data_presensi, C.nik_presensi, C.user_presensi, C.div_presensi, C.tgl_presensi, C.cek_presensi, C.ket_presensi FROM approval_presensi AS A
                INNER JOIN data_presensi AS B ON A.no_aprv_presensi = B.no_data_presensi
                INNER JOIN presensi AS C ON B.ref_data_presensi = C.no_presensi
                WHERE A.no_aprv_presensi = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONDETAILPRESENSI"]) && !empty($_POST["ACTIONDETAILPRESENSI"])){

        $id = $_POST['ACTIONDETAILPRESENSI'];

        $sql = "SELECT A.no_aprv_presensi, B.ref_data_presensi, B.cekold_data_presensi, B.ceknew_data_presensi, B.jam_data_presensi, B.ket_data_presensi, CONCAT(C.nik_presensi, ' - ', C.user_presensi) AS users_presensi, C.user_presensi, C.div_presensi, C.tgl_presensi, C.cek_presensi, C.ket_presensi FROM approval_presensi AS A
                INNER JOIN data_presensi AS B ON A.no_aprv_presensi = B.no_data_presensi
                INNER JOIN presensi AS C ON B.ref_data_presensi = C.no_presensi
                WHERE A.no_aprv_presensi = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["MASTERTELEBOT"]) && !empty($_POST["MASTERTELEBOT"])){

        $id = $_POST['MASTERTELEBOT'];

        $sql = "SELECT id_mstr_telebot, uname_mstr_telebot FROM master_telebot
                WHERE id_mstr_telebot = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }
    
    if(isset($_POST["MASTERSERVICEAPI"]) && !empty($_POST["MASTERSERVICEAPI"])){

        $id = $_POST['MASTERSERVICEAPI'];

        $sql = "SELECT id_srv_api, name_srv_api FROM service_api
                WHERE id_srv_api = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["AKSIROLETRANSAKSI"]) && !empty($_POST["AKSIROLETRANSAKSI"])){

        $id = $_POST['AKSIROLETRANSAKSI'];

        $sql = "SELECT id_role_trans, no_role_trans, inisial_role_trans, name_role_trans FROM role_transaksi
                WHERE id_role_trans  = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["AKSIUSERTELEBOT"]) && !empty($_POST["AKSIUSERTELEBOT"])){

        $id = $_POST['AKSIUSERTELEBOT'];

        $sql = "SELECT id_user_tele, no_user_tele, nik_user_tele, status_user_tele FROM user_telebot
                WHERE id_user_tele = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONSTHH"]) && !empty($_POST["ACTIONSTHH"])){

        $id = $_POST['ACTIONSTHH'];

        $sql = "SELECT id_sthh, no_pinjam, pic, LEFT(pluid, 5) AS nomor_sthh, CONCAT(dateout, ' ', jamkeluar) AS tgl_keluar, nik, keterangan, id_divisi, id_sub_divisi FROM sthh
        WHERE id_sthh = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONMONSERVICE"]) && !empty($_POST["ACTIONMONSERVICE"])){

        $id = $_POST['ACTIONMONSERVICE'];

        $sql = "SELECT A.*, B.no_sj FROM detail_surat_jalan AS A
        INNER JOIN surat_jalan AS B ON A.head_no_sj = B.no_sj
        WHERE A.detail_no_sj = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONBRGHILANG"]) && !empty($_POST["ACTIONBRGHILANG"])){

        $id = $_POST['ACTIONBRGHILANG'];

        $sql = "SELECT A.pluid, A.sn_barang, A.kondisi, A.posisi, B.kondisi_name FROM barang_assets AS A
        INNER JOIN kondisi AS B ON A.kondisi = B.id_kondisi WHERE A.id_ba = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["BARANGMUTASI"]) && !empty($_POST["BARANGMUTASI"])){

        //Get all state data
        $office = substr($_POST['BARANGMUTASI'], 0, 4);
        $dept = substr($_POST['BARANGMUTASI'], 4, 4);
        $kondisi = substr($_POST['BARANGMUTASI'], 8, 2);
        $plu = substr($_POST['BARANGMUTASI'], 10, 10);

        $sql = "SELECT * FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4)  = '$dept' AND pluid = '$plu' AND kondisi NOT LIKE '$kondisi' GROUP BY no_at";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$office.$dept.$kondisi.$row['no_at'].'">'.$row['no_at'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["ATMUTASI"]) && !empty($_POST["ATMUTASI"])){

        $office = substr($_POST['ATMUTASI'], 0, 4);
        $dept = substr($_POST['ATMUTASI'], 4, 4);
        $kondisi = substr($_POST['ATMUTASI'], 8, 2);
        $at = substr($_POST['ATMUTASI'], 10, 10);

        $sql = "SELECT * FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND no_at = '$at' AND kondisi NOT LIKE '$kondisi'";
        $query = mysqli_query($conn, $sql);
        
        //Count total number of rows
        $rowCount = mysqli_num_rows($query);
        
        //Display states list
        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$office.$dept.$kondisi.$row['sn_barang'].'">'.$row['sn_barang'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["SNMUTASI"]) && !empty($_POST["SNMUTASI"])){

        $row = array();
        $office = substr($_POST['SNMUTASI'], 0, 4);
        $dept = substr($_POST['SNMUTASI'], 4, 4);
        $kondisi = substr($_POST['SNMUTASI'], 8, 2);
        $sn = substr($_POST['SNMUTASI'], 10);

        $sql = "SELECT * FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND sn_barang = '$sn' AND kondisi NOT LIKE '$kondisi'";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode($row));
        }
    }

    if(isset($_POST["ACTIONMUTASI"]) && !empty($_POST["ACTIONMUTASI"])){

        $id = $_POST['ACTIONMUTASI'];

        $sql = "SELECT A.*, B.asal_mutasi FROM detail_mutasi AS A 
        INNER JOIN mutasi AS B ON A.head_no_mutasi = B.no_mutasi
        WHERE A.detail_no_mutasi = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONPROSESMUSNAH"]) && !empty($_POST["ACTIONPROSESMUSNAH"])){

        $id = $_POST['ACTIONPROSESMUSNAH'];

        $sql = "SELECT id_detail_p3at, id_head_p3at, pluid_p3at, sn_p3at, at_p3at, offdep_p3at FROM detail_p3at WHERE id_detail_p3at = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["EDITBARANGCHECKBOX"]) && !empty($_POST["EDITBARANGCHECKBOX"]) && isset($_POST["GROUPIDUSER"]) && !empty($_POST["GROUPIDUSER"]) && isset($_POST["AKSIUPDATE"]) && !empty($_POST["AKSIUPDATE"])){

        //Get all data
        $idbrg = $_POST['EDITBARANGCHECKBOX'];
        $idgrp = $_POST['GROUPIDUSER'];
        $aksi = $_POST['AKSIUPDATE'];
        $strdata = implode(", ", $idbrg);
        
        $no = 1;
        $sql = "SELECT A.id_ba, A.dat_asset, A.ba_id_office, A.ba_id_department, A.pluid, A.ba_merk, A.ba_tipe, A.sn_barang, A.no_at, A.no_lambung, A.kondisi, A.posisi, B.NamaBarang, C.NamaJenis FROM barang_assets AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
        WHERE A.id_ba IN ($strdata)";
        $query_head = mysqli_query($conn, $sql);

        if ($query_head) {
            $data_head = mysqli_fetch_assoc($query_head);
            $desc = $data_head['pluid']." - ".$data_head['NamaBarang']." ".$data_head['NamaJenis'];
        ?>
        <div class="col-md-12 mb-2">
            <label>Nama Barang</label>
            <input type="text" class="form-control" value="<?= $desc; ?>" disabled>
        </div>
        <div class="col-md-12 mb-2">
            <label>Data Barang</label>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <?php if ($idgrp === $arrgroup[0]) { ?>
                        <th>Serial Number</th>
                        <?php } ?>
                        <th>Nomor Aktiva</th>
                        <th>Nomor Lambung</th>
                        <th>Kondisi</th>
                        <th>Penempatan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $result = array();
                    $query_detail = mysqli_query($conn, $sql);
                    while($data_detail = mysqli_fetch_assoc($query_detail)) {
                        $result[] = $data_detail;
                    }
                    foreach ($result as $rows) {
                    ?>
                        <tr>
                            <td>
                                <input type="hidden" name="id_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['id_ba']; ?>" class="form-control" readonly/>
                                <input type="hidden" name="snold_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['sn_barang']; ?>" class="form-control" readonly/>
                                <?php if ($idgrp !== $arrgroup[0]) { ?>
                                    <input type="hidden" name="sn_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['sn_barang']; ?>" class="form-control" required/>
                                <?php } ?>
                                <input type="hidden" name="offdep_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= substr($rows['dat_asset'], 0, 4).substr($rows['dat_asset'], 4, 4).$rows['pluid']; ?>" class="form-control" readonly/>
                                <span><?= $no++; ?></span>
                            </td>
                            <td>
                                <input type="<?= $aksi == "EDIT" ? "text" : "hidden"; ?>" name="merk_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['ba_merk']; ?>" placeholder="Input Merk Barang (Optional)" class="form-control"/>
                                <?= $aksi == "EDIT" ? "" : "<span>".$rows['ba_merk']."</span>"; ?>
                            </td>
                            <td>
                                <input type="<?= $aksi == "EDIT" ? "text" : "hidden"; ?>" name="tipe_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['ba_tipe']; ?>" placeholder="Input Tipe Barang (Optional)" class="form-control"/>
                                <?= $aksi == "EDIT" ? "" : "<span>".$rows['ba_tipe']."</span>"; ?>
                            </td>
                            <?php if ($idgrp === $arrgroup[0]) { ?>
                            <td>
                                <input type="<?= $aksi == "EDIT" ? "text" : "hidden"; ?>" name="sn_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['sn_barang']; ?>" class="form-control" placeholder="Input Serial Number Barang" required/>
                                <?= $aksi == "EDIT" ? "" : "<span>".$rows['sn_barang']."</span>"; ?>
                            </td>
                            <?php } ?>
                            <td>
                                <input type="<?= $aksi == "EDIT" ? "text" : "hidden"; ?>" name="at_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['no_at']; ?>" class="form-control" placeholder="Input Nomor Aktiva" required/>
                                <?= $aksi == "EDIT" ? "" : "<span>".$rows['no_at']."</span>"; ?>
                            </td>
                            <td>
                                <input type="<?= $aksi == "EDIT" ? "text" : "hidden"; ?>" name="no_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" value="<?= $rows['no_lambung']; ?>" placeholder="Input Nomor Lambung (Optional)" class="form-control"/>
                                <?= $aksi == "EDIT" ? "" : "<span>".$rows['no_lambung']."</span>"; ?>
                            </td>
                            <td>
                                <?php
                                if ($aksi == "EDIT") {
                                ?>
                                <select type="text" name="kondisi_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" class="form-control">
                                    <option value="" selected disabled>Pilih Kondisi</option>
                                    <?php
                                        $query_cond = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi != '$arrcond[5]'");
                                        while($data_cond = mysqli_fetch_assoc($query_cond)) { ?>
                                        <option value="<?= $data_cond['id_kondisi']; ?>" <?= $data_cond['id_kondisi'] == $rows['kondisi'] ? 'selected' : ''; ?> ><?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name'];?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                                <?php
                                }
                                elseif ($aksi == "DELETE") {
                                ?>
                                    <?php
                                        $query_cond = mysqli_query($conn, "SELECT * FROM kondisi WHERE id_kondisi = '".$rows['kondisi']."'");
                                        while($data_cond = mysqli_fetch_assoc($query_cond)) { 
                                    ?>
                                        <span><?= $data_cond['id_kondisi']." - ".$data_cond['kondisi_name']; ?></span>
                                    <?php 
                                        }
                                    ?>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($aksi == "EDIT") {
                                ?>
                                    <textarea type="text" name="posisi_<?= $aksi == "EDIT" ? "edit" : "delete"; ?>_check[]" placeholder="Input Posisi / Penempatan (Optional)" class="form-control"><?= $rows["posisi"]; ?></textarea>
                                <?php
                                }
                                elseif ($aksi == "DELETE") {
                                ?>
                                <span><?= $rows["posisi"]; ?></span>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php                                                          
        } 
        else { 
        ?>
            <tr>
                <td colspan='6'>No data available in table</td>
            </tr>
        <?php
        }
    }

    if(isset($_POST["OFFDEPABSENSI"]) && !empty($_POST["OFFDEPABSENSI"]) && isset($_POST["NIKABSENSI"]) && !empty($_POST["NIKABSENSI"]) && isset($_POST["BULANABSENSI"]) && !empty($_POST["BULANABSENSI"])){

        //Get all data
        $office = substr($_POST['OFFDEPABSENSI'], 0, 4);
        $dept = substr($_POST['OFFDEPABSENSI'], 4, 4);
        $nik = $_POST['NIKABSENSI'];
        $bulan = $_POST['BULANABSENSI'];
        $month_now = date("Y-m");

        require __DIR__ . '../../vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig('../includes/config/client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = "110xb7dSbyPLWW9HvRkKqv_iWnviAlQnZzg0om8u_SBQ";

        try {
            // OPERASI READ
            $sheet = $bulan == $month_now ? "Realisasi!" : "JADWALREAL-".$bulan."!";
            $range = "A7:AJ";
            $response = $service->spreadsheets_values->get($spreadsheetId, $sheet.$range);
            $data = $response->getValues();
        } catch (Google_Service_Exception $e) {
            if($e->getCode() == 404){ // <- Change is here
                return FALSE;
            }
        }

        if (!isset($data)) {?>
            <tr id="loadpresensi-spinner">
                <td colspan="6">No data available in table sheet!</td>
            </tr>
        <?php
        }
        else {
            $array = array();

            foreach ($data as $i => $v) {
                if ($v[0] == $nik) {                                    
                    unset($v[0], $v[1], $v[2], $v[3], $v[4]);
                    $array = array_values($v);
                }
            }

            if (!empty($array)) {
                $size = count($array);
                $cols = 1;
                $rows = $increment = ceil($size / $cols);
                $no = 1;
                $num = 0;
                $h = 0;
                for( $i = 0; $i < $rows; $i++) {
                    for( $j = $i; $j < $size; $j += $increment) {
                        if ($array[$j] == "A") {
                            $jdwltext = "ALPA";
                            $checklist = "disabled";
                            $colorlist = 'class="badge badge-danger"';
                        }
                        elseif ($array[$j] == "S") {
                            $jdwltext = "SAKIT";
                            $checklist = "disabled";
                            $colorlist = 'class="badge badge-warning"';
                        }
                        elseif ($array[$j] == "C" || $array[$j] == "CUTI") {
                            $jdwltext = "CUTI";
                            $checklist = "disabled";
                            $colorlist = 'class="badge badge-primary"';
                        }
                        elseif ($array[$j] == "L" || $array[$j] == "LP") {
                            $jdwltext = "LIBUR PENGGANTI";
                            $checklist = "disabled";
                            $colorlist = 'class="badge badge-info"';
                        }
                        elseif ($array[$j] == "OFF") {
                            $jdwltext = $array[$j];
                            $colorlist = 'class="badge badge-secondary"';
                            $checklist = $bulan == date("Y-m") ? "" : "disabled";
                        }
                        else {
                            $jdwltext = $array[$j];
                            $colorlist = 'class="badge badge-success"';
                            $checklist = $bulan == $month_now ? "" : "disabled";
                        }
                        $hari = date("l", strtotime("+".$h++." day", strtotime($bulan)));
                        ?>
                        <tr>
                            <td>
                                <span><?= $no++; ?></span>
                            </td>
                            <td>
                                <span <?= $hari == "Sunday" ? 'class="badge badge-danger"' : '' ; ?>><?= $hari; ?></span>
                            </td>
                            <td>
                                <span><?= $tgl = date("Y-m-d", strtotime("+".$num++." day", strtotime($bulan))); ?></span>
                            </td>
                            <td>
                                <span <?= $colorlist; ?>><?= $jdwltext; ?></span>
                            </td>
                            <td class="icheck1">
                                <input type="checkbox" name="checkjadwalinput[]" id="checkjadwalinput" class="checkjadwalinput" value="<?= $office.$dept.$nik.$tgl.$array[$j]; ?>" <?= $checklist; ?>>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            else { 
            ?>
                <tr id="loadpresensi-spinner">
                    <td colspan="6">No data available in table</td>
                </tr>
            <?php
            }
        }
    }

    if(isset($_POST["EDITJADWALCHECKBOX"]) && !empty($_POST["EDITJADWALCHECKBOX"])){

        //Get all data
        $id = $_POST['EDITJADWALCHECKBOX'];
        $strdata = implode(", ", $id);
        $head_nik = substr($strdata, 8, 10);

        $sql = "SELECT A.username, A.full_name, B.divisi_name FROM users AS A
        INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
        WHERE A.nik = '$head_nik'";
        $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        ?>
        <div class="col-md-6 mb-2">
            <label>NIK - USERNAME</label>
            <input type="text" class="form-control" value="<?= $head_nik." - ".strtoupper($result["full_name"]); ?>" disabled>
        </div>
        <div class="col-md-6 mb-2">
            <label>DIVISI / BAGIAN</label>
            <input type="text" name="bagian-posting" class="form-control" value="<?= $result["divisi_name"]; ?>" readonly>
        </div>
        <div class="col-md-12 mb-2">
            <label>DATA PERUBAHAN JADWAL</label>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>JADWAL EXIST</th>
                        <th>JADWAL PERUBAHAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no = 1;
                    foreach ($id as $rows) {
                        $office = substr($rows, 0, 4);
                        $dept = substr($rows, 4, 4);
                        $nik = substr($rows, 8, 10);
                        $tgl = substr($rows, 18, 10);
                        $jadwal = substr($rows, 28);
                        if ($jadwal == "OFF") {
                            $jdwltext = $jadwal;
                            $checklist = "";
                            $colorlist = 'class="badge badge-secondary"';
                        }
                        else {
                            $jdwltext = $jadwal;
                            $checklist = "";
                            $colorlist = 'class="badge badge-success"';
                        }
                    ?>
                        <tr>
                            <td>
                                <span><?= $no++; ?></span>
                                <input type="hidden" name="edit2_nik_hadir[]" value="<?= $head_nik." - ".strtoupper($result["username"]); ?>" class="form-control" readonly/>
                            </td>
                            <td>
                                <span><?= $tgl; ?></span>
                                <input type="hidden" name="edit2_tgl_hadir[]" value="<?= $tgl; ?>" class="form-control" readonly/>
                            </td>
                            <td>
                                <span <?= $colorlist; ?> ><?= $jdwltext; ?></span>
                                <input type="hidden" name="edit2_jadwal_hadir[]" value="<?= $jdwltext; ?>" class="form-control" readonly/>
                            </td>
                            <td>
                                <select type="text" name="edit2_rubah_hadir[]" class="select2 form-control block" style="width: 100%" required>
                                    <option value="" selected disabled>Please Select</option>
                                    <?php
                                        $query_jk = mysqli_query($conn, "SELECT * FROM jadwal_kerja WHERE dept_jk = '$dept'");
                                        while($data_jk = mysqli_fetch_assoc($query_jk)) { ?>
                                        <option value="<?= $data_jk['jam_jk']; ?>" <?= $data_jk['jam_jk'] == $jdwltext ? 'selected' : ''; ?> ><?= $data_jk['data_jk'];?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <textarea class="form-control" type="text" name="edit2_ket_hadir[]" placeholder="Input keterangan (Optional)"></textarea>
                            </td>
                        </tr>
                    <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }


    if(isset($_POST["OFFDEPEDITABSENSI"]) && !empty($_POST["OFFDEPEDITABSENSI"]) && isset($_POST["NIKEDITABSENSI"]) && !empty($_POST["NIKEDITABSENSI"]) && isset($_POST["TANGGALEDITABSENSI"]) && !empty($_POST["TANGGALEDITABSENSI"])){

        //Get all data
        $office = substr($_POST['OFFDEPEDITABSENSI'], 0, 4);
        $dept = substr($_POST['OFFDEPEDITABSENSI'], 4, 4);
        $nik = $_POST['NIKEDITABSENSI'];
        $tgl = $_POST['TANGGALEDITABSENSI'];

        $query_presensi = mysqli_query($conn, "SELECT * FROM presensi WHERE office_presensi = '$office' AND dept_presensi = '$dept' AND nik_presensi = '$nik' AND tgl_presensi = '$tgl' AND cek_presensi = 'RUBAH SHIFT' OR office_presensi = '$office' AND dept_presensi = '$dept' AND nik_presensi = '$nik' AND tgl_presensi = '$tgl' AND cek_presensi = 'TUKAR OFF'");

        if (mysqli_num_rows($query_presensi) > 0) {
            $no = 1;
            while ($data_presensi = mysqli_fetch_assoc($query_presensi)) {
                ?>
                <tr>
                    <td>
                        <span><?= $no++; ?></span>
                        <input type="hidden" name="edit_user_hadir[]" value="<?= $data_presensi["nik_presensi"]; ?>" class="form-control" readonly/>
                        <input type="hidden" name="edit_cekold_hadir[]" value="<?= $data_presensi["cek_presensi"]; ?>" class="form-control" readonly/>
                        <input type="hidden" name="edit_jamold_hadir[]" value="<?= $data_presensi["jam_presensi"]; ?>" class="form-control" readonly/>
                    </td>
                    <td>
                        <span><?= $data_presensi["tgl_presensi"]; ?></span>
                        <input type="hidden" name="edit_tgl_hadir[]" value="<?= $data_presensi["tgl_presensi"]; ?>" class="form-control" readonly/>
                    </td>
                    <td>
                        <select type="text" id="edit_jam_hadir" name="edit_jam_hadir[]" class="select2 form-control block" style="width: 100%" required>
                            <option value="" selected disabled>Please Select</option>
                            <?php
                                $query_jk = mysqli_query($conn, "SELECT * FROM jadwal_kerja WHERE dept_jk = '$dept'");
                                while($data_jk = mysqli_fetch_assoc($query_jk)) { ?>
                                <option value="<?= $data_jk['jam_jk']; ?>" <?= $data_jk['jam_jk'] == $data_presensi["jam_presensi"] ? 'selected' : ''; ?> ><?= $data_jk['data_jk'];?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" type="text" name="edit_ket_hadir[]" placeholder="Input keterangan (Optional)"><?= $data_presensi["ket_presensi"]; ?></textarea>
                    </td>
                    <td class="icheck1">
                        <input type="checkbox" name="edit_id_hadir[]" id="edit_id_hadir" class="edit_id_hadir" value="<?= $data_presensi["no_presensi"]; ?>">
                    </td>
                </tr>
                <?php
            }
        }
        else {
        ?>
            <tr id="loadpresensi-spinner">
                <td colspan="5">No data available in table</td>
            </tr>
        <?php
        }
    }

    if(isset($_POST["AKSIKEPDAT"]) && !empty($_POST["AKSIKEPDAT"])){

        $id = $_POST['AKSIKEPDAT'];

        $sql = "SELECT A.*, CONCAT(B.NamaBarang, ' ', C.NamaJenis) AS desc_dat FROM dat AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid_dat, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_dat, 4) = C.IDJenis
        WHERE A.id_dat = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONHEADIV"]) && !empty($_POST["ACTIONHEADIV"])){

        $id = $_POST['ACTIONHEADIV'];

        $sql = "SELECT * FROM head_divisi WHERE id_head_div = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONDIV"]) && !empty($_POST["ACTIONDIV"])){

        $id = $_POST['ACTIONDIV'];

        $sql = "SELECT id_divisi, divisi_name FROM divisi WHERE id_divisi = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["SATUANBRGAKTIVA"]) && !empty($_POST["SATUANBRGAKTIVA"])){

        $data = mysqli_real_escape_string($conn, $_POST['SATUANBRGAKTIVA']);
        $id = substr($data, 8, 6);

        $sql = "SELECT B.nama_satuan FROM mastercategory AS A
        INNER JOIN satuan AS B ON A.id_satuan = B.id_satuan
        WHERE A.IDBarang = '$id'";
        
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);

        die(json_encode($result));
        
    }

    if(isset($_POST["ACTIONMASTERAPP"]) && !empty($_POST["ACTIONMASTERAPP"])){

        $id = $_POST['ACTIONMASTERAPP'];

        $sql = "SELECT id_app, code_app, name_app, jenis_app, develop_app, func_app, peruntukan_app FROM master_app WHERE id_app = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONVERSIAPP"]) && !empty($_POST["ACTIONVERSIAPP"])){

        $id = $_POST['ACTIONVERSIAPP'];

        $sql = "SELECT A.id_ver_app, A.id_code_app, A.rilis_ver_app, A.version_ver_app, A.fitur_ver_app, A.info_ver_app, A.use_ver_app, A.source_ver_app, A.manual_ver_app, B.name_app, B.basis_app FROM version_app AS A 
        INNER JOIN master_app AS B ON A.id_code_app = B.code_app
        WHERE A.id_ver_app = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONDETAILSJ"]) && !empty($_POST["ACTIONDETAILSJ"])){

        $id = $_POST['ACTIONDETAILSJ'];

        $sql = "SELECT CONCAT(A.pluid_sj, ' - ', B.NamaBarang, ' ', C.NamaJenis) AS DESC_BARANG, A.sn_sj AS SN_BARANG, IF(A.at_sj IS NULL, '-', A.at_sj) AS DAT_BARANG, A.qty_sj AS QTY_BARANG, A.keterangan_sj AS KET_BARANG FROM detail_surat_jalan AS A
        INNER JOIN mastercategory AS B ON LEFT(A.pluid_sj, 6) = B.IDBarang
        INNER JOIN masterjenis AS C ON RIGHT(A.pluid_sj, 4) = C.IDJenis
        WHERE A.head_no_sj = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["EDITUSERSCHECKBOX"]) && !empty($_POST["EDITUSERSCHECKBOX"]) && isset($_POST["EDITGROUPCHECKBOX"]) && !empty($_POST["EDITGROUPCHECKBOX"])){

    //Get all data
        $idgrp = $_POST['EDITGROUPCHECKBOX'];
        $nik = $_POST['EDITUSERSCHECKBOX'];
        $datanik = implode(", ", $nik);

        $query_users = mysqli_query($conn, "SELECT id, nik, username, full_name, id_office, id_department, id_divisi, id_group, id_level FROM users WHERE id IN ($datanik)");

        if ($query_users) { ?>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NIK - USERNAME</th>
                        <th>NAMA LENGKAP</th>
                        <th>OFFICE</th>
                        <th>DEPARTMENT</th>
                        <th>DIVISI</th>
                        <th>GROUP</th>
                        <th>LEVEL</th>
                    </tr>
                </thead>
                <tbody>
        <?php
            $no = 1;
            if (mysqli_num_rows($query_users) > 0) {
                while ($data_users = mysqli_fetch_assoc($query_users)) { 
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="upduser_id[]" value="<?= $data_users['id']; ?>" class="form-control" readonly/>
                            <span><?= $no++; ?></span>
                        </td>
                        <td>
                            <span><?= $data_users['nik'].' - '.strtoupper($data_users['username']); ?></span>
                        </td>
                        <td>
                            <input type="text" name="upduser_name[]" value="<?= $data_users['full_name']; ?>" placeholder="Entry name" class="form-control" required/>
                        </td>
                        <td>
                            <select type="text" name="upduser_office[]" class="form-control">
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    if ($idgrp === $arrgroup[0]) {
                                        $query_office = mysqli_query($conn, "SELECT id_office, office_name FROM office");
                                    }
                                    else {
                                        $officeid = $data_users['id_office'];
                                        $query_office = mysqli_query($conn, "SELECT id_office, office_name FROM office WHERE id_office = '$officeid'");
                                    }
                                    while($data_office = mysqli_fetch_assoc($query_office)) { ?>
                                    <option value="<?= $data_office['id_office']; ?>" <?= $data_office['id_office'] == $data_users['id_office'] ? 'selected' : ''; ?>><?= $data_office['id_office']." - ".strtoupper($data_office['office_name']);?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select type="text" name="upduser_dept[]" class="form-control">
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    if ($idgrp === $arrgroup[0] || $idgrp === $arrgroup[1]) {
                                        $query_dept = mysqli_query($conn, "SELECT id_department, department_name FROM department");
                                    }
                                    else {
                                        $deptid = $data_users['id_department'];
                                        $query_dept = mysqli_query($conn, "SELECT id_department, department_name FROM department WHERE id_department = '$deptid'");
                                    }
                                    while($data_dept = mysqli_fetch_assoc($query_dept)) { ?>
                                    <option value="<?= $data_dept['id_department']; ?>" <?= $data_dept['id_department'] == $data_users['id_department'] ? 'selected' : ''; ?>><?= $data_dept['id_department']." - ".strtoupper($data_dept['department_name']);?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select type="text" name="upduser_divisi[]" class="form-control">
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    $query_divisi = mysqli_query($conn, "SELECT id_divisi, divisi_name FROM divisi");
                                    while($data_divisi = mysqli_fetch_assoc($query_divisi)) { ?>
                                    <option value="<?= $data_divisi['id_divisi']; ?>" <?= $data_divisi['id_divisi'] == $data_users['id_divisi'] ? 'selected' : ''; ?>><?= $data_divisi['id_divisi']." - ".strtoupper($data_divisi['divisi_name']);?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select type="text" name="upduser_group[]" class="form-control">
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    if ($idgrp === $arrgroup[0]) {
                                        $query_group = mysqli_query($conn, "SELECT id_group, group_name FROM groups");
                                    }
                                    elseif($idgrp === $arrgroup[1]) {
                                        $query_group = mysqli_query($conn, "SELECT id_group, group_name FROM groups WHERE id_group NOT LIKE '$arrgroup[0]'");
                                    }
                                    else {
                                        $groupid = $data_users['id_group'];
                                        $query_group = mysqli_query($conn, "SELECT id_group, group_name FROM groups WHERE id_group = '$groupid'");
                                    }
                                    while($data_group = mysqli_fetch_assoc($query_group)) { ?>
                                    <option value="<?= $data_group['id_group']; ?>" <?= $data_group['id_group'] == $data_users['id_group'] ? 'selected' : ''; ?>><?= $data_group['id_group']." - ".strtoupper($data_group['group_name']);?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select type="text" name="upduser_level[]" class="form-control">
                                <option value="" selected disabled>Please Select</option>
                                <?php
                                    if ($idgrp === $arrgroup[0]) {
                                        $query_level = mysqli_query($conn, "SELECT id_level, level_name FROM level");
                                    }
                                    else {
                                        $query_level = mysqli_query($conn, "SELECT id_level, level_name FROM level WHERE id_level NOT LIKE '$arrlvl[4]'");
                                    }
                                    while($data_level = mysqli_fetch_assoc($query_level)) { ?>
                                    <option value="<?= $data_level['id_level']; ?>" <?= $data_level['id_level'] == $data_users['id_level'] ? 'selected' : ''; ?>><?= $data_level['id_level']." - ".strtoupper($data_level['level_name']);?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
            ?>
                </tbody>
            </table>
        <?php
            }
        }
    }

    if(isset($_POST["STATIONPLANO"]) && !empty($_POST["STATIONPLANO"])){
        $id_zona = mysqli_real_escape_string($conn, $_POST['STATIONPLANO']);

        $sql = "SELECT A.station_zona_plano, B.nm_type_plano FROM zona_plano AS A
        INNER JOIN type_plano AS B ON A.id_type_plano_head = B.id_type_plano
        WHERE A.id_zona_plano = '$id_zona'";
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);
        
        $station = array();
        if (isset($data) && is_array($data) && count($data) > 0) {

            for($i = 1; $i <= $data["station_zona_plano"]; $i++) {
                if ($data["nm_type_plano"] == "BULKY FRACTION") {
                    $formattedNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $station[] = $formattedNumber++;
                }
                else {
                    $station[] = $i;
                }
            }

        } else {
            $station = array();
        }
        
        die(json_encode(array(
            "station"=>$station
        )));
    }

    if(isset($_POST["ACTIONDETAILTABLOK"]) && !empty($_POST["ACTIONDETAILTABLOK"])){

        $id = $_POST['ACTIONDETAILTABLOK'];

        $sql = "SELECT B.rak_pertemanan_plano AS RAK_PERTEMANAN, B.line_pertemanan_plano AS LINE_PERTEMANAN FROM st_dpd_detail AS A
                LEFT JOIN pertemanan_plano AS B ON A.docno_st_dpd_detail = B.id_tablok
                WHERE A.docno_st_dpd_detail = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["ACTIONDETAILAPPROVETABLOK"]) && !empty($_POST["ACTIONDETAILAPPROVETABLOK"])){

        $id = $_POST['ACTIONDETAILAPPROVETABLOK'];

        $sql = "SELECT B.docno_st_dpd_detail AS DOCNO, B.plu_st_dpd_detail AS PLU, B.nama_st_dpd_detail AS DESK, B.type_st_dpd_detail AS TYPE_RAK, B.line_st_dpd_detail AS LINE_RAK, B.zona_st_dpd_detail AS ZONA, B.station_st_dpd_detail AS STATION, B.rak_st_dpd_detail AS RAK, B.shelf_st_dpd_detail AS SHELF, B.cell_st_dpd_detail AS CELL, B.item_st_dpd_detail AS TYPE_ITEM, B.carton_st_dpd_detail AS KEL_CTN, B.ip_st_dpd_detail AS IP_DPD, B.dpd_st_dpd_detail AS ID_DPD FROM st_dpd_head AS A
                LEFT JOIN st_dpd_detail AS B ON A.id_st_dpd = B.id_st_dpd_head
                WHERE A.id_st_dpd = '$id'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["DETAILPERTEMANAN"]) && !empty($_POST["DETAILPERTEMANAN"])){

        $id = $_POST['DETAILPERTEMANAN'];

        $no = 1;
        $sql = "SELECT A.docno_st_dpd_detail, B.* FROM st_dpd_detail AS A
        INNER JOIN pertemanan_plano AS B ON A.docno_st_dpd_detail = B.id_tablok
        WHERE A.docno_st_dpd_detail = '$id'";

        $query = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($query);

        if ($rowCount > 0) { ?>
        <div class="form-row" >
            <div class="col-md-12 mb-2">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>LINE</th>
                            <th>RAK</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)) { 
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $data['line_pertemanan_plano']; ?></td>
                            <td><?= $data['rak_pertemanan_plano']; ?></td>
                        </tr>
                <?php 
                    }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        }
        else { ?>
            <h5>
                <span class="text-bold-600">404</span> Data not found!
            </h5>
        <?php }
    }

    if(isset($_POST["FOLLUPTABLOK"]) && !empty($_POST["FOLLUPTABLOK"])){

        $id = $_POST['FOLLUPTABLOK'];

        $sql = "SELECT id_st_dpd AS DOCNO FROM st_dpd_head WHERE id_st_dpd = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["OFFABSENSISRC"]) && !empty($_POST["OFFABSENSISRC"]) && isset($_POST["TGLAWLABSENSISRC"]) && !empty($_POST["TGLAWLABSENSISRC"]) && isset($_POST["TGLAKRABSENSISRC"]) && !empty($_POST["TGLAKRABSENSISRC"])){

        //Get all data
        $office = $_POST['OFFABSENSISRC'];
        $tglawal = $_POST['TGLAWLABSENSISRC'];
        $tglakhir = $_POST['TGLAKRABSENSISRC'];

        $query_presensi = mysqli_query($conn, "SELECT * FROM presensi WHERE office_presensi = '$office' AND tgl_presensi BETWEEN '$tglawal' AND '$tglakhir'");

        $rows_presensi = array();
        if (mysqli_num_rows($query_presensi) > 0) {
            while($data_presensi = mysqli_fetch_assoc($query_presensi)) {
                $dataarr = array(
                    $data_presensi["ts_presensi"],
                    $data_presensi["id_presensi"],
                    $data_presensi["no_presensi"],
                    $data_presensi["office_presensi"],
                    $data_presensi["input_presensi"],
                    $data_presensi["nik_presensi"],
                    $data_presensi["user_presensi"],
                    $data_presensi["div_presensi"],
                    $data_presensi["tgl_presensi"],
                    $data_presensi["cek_presensi"],
                    $data_presensi["ket_presensi"]
                );
                $rows_presensi[] = $dataarr;
            }
        }

        require __DIR__ . '../../vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig('../includes/config/client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = "110xb7dSbyPLWW9HvRkKqv_iWnviAlQnZzg0om8u_SBQ";

        try {
            // OPERASI READ
            $sheet = "SHEET_PRESENSI_IMS!";
            $range = "A2:J";
            $response = $service->spreadsheets_values->get($spreadsheetId, $sheet.$range);
            $data = $response->getValues();
        } catch (Google_Service_Exception $e) {
            if($e->getCode() == 404){ // <- Change is here
                return FALSE;
            }
        }

        if (!isset($data)) {?>
            <tr id="loadpresensi-spinner">
                <td colspan="10">No data available in table sheet!</td>
            </tr>
        <?php
        }
        else {
            if (!empty($data)) {
                $rows = [];
                $no = 1;

                $compareValuesArray1 = array_column($rows_presensi, 2);
                $compareValuesArray2 = array_column($data, 2);

                $missingValues = array_diff($compareValuesArray1, $compareValuesArray2);

                if (empty($missingValues)) {
                    ?>
                        <tr id="loadpresensi-spinner">
                            <td colspan="10">No data failed posting</td>
                        </tr>
                    <?php
                } else {
                    foreach ($rows_presensi as $item) {
                        if (!in_array($item[2], $compareValuesArray2)) {
                            $rows = $item;
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="no_absensi_gagal[]" value="<?= $rows[2]; ?>" class="form-control" readonly/>
                                    <span><?= $no++; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[0]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[4]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[5]." - ".$rows[6]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[7]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[8]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[9]; ?></span>
                                </td>
                                <td>
                                    <span><?= $rows[10]; ?></span>
                                </td>
                                <td class="icheck1">
                                    <input type="checkbox" name="check_id_hadir[]" id="check_id_hadir" class="check_id_hadir" value="<?= $rows[2]; ?>">
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
            }
            else { 
            ?>
                <tr id="loadpresensi-spinner">
                    <td colspan="10">No data available in table</td>
                </tr>
            <?php
            }
        }
    }

    if(isset($_POST["UPDATEMASTERPENILAIAN"]) && !empty($_POST["UPDATEMASTERPENILAIAN"])){

        //Get all data
        $id = $_POST['UPDATEMASTERPENILAIAN'];

        $sql = "SELECT * FROM indicator_assessment
                WHERE id_ind_assest = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["ACTIONINSTRUMENPENILAIAN"]) && !empty($_POST["ACTIONINSTRUMENPENILAIAN"])){

        //Get all data
        $id = $_POST['ACTIONINSTRUMENPENILAIAN'];

        $sql = "SELECT A.*, B.* FROM instrument_assessment AS A 
        INNER JOIN indicator_assessment AS B ON A.id_head_ind_assest  = B.id_ind_assest 
        WHERE A.id_ins_assest = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["PERIODEASSESSMENT"]) && !empty($_POST["PERIODEASSESSMENT"])){

        //Get all data
        $id = $_POST['PERIODEASSESSMENT'];

        $sql = "SELECT * FROM statusassessment WHERE id_sts_assest = '$id' OR code_sts_assest = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
        
    }

    if(isset($_POST["OFFICEASSESSMENT"]) && !empty($_POST["OFFICEASSESSMENT"]) && isset($_POST["DEPTASSESSMENT"]) && !empty($_POST["DEPTASSESSMENT"]) && isset($_POST["DIVASSESSMENT"]) && !empty($_POST["DIVASSESSMENT"]) && isset($_POST["LEADASSESSMENT"]) && !empty($_POST["LEADASSESSMENT"])){

        $off = mysqli_real_escape_string($conn, $_POST['OFFICEASSESSMENT']);
        $dpt = mysqli_real_escape_string($conn, $_POST['DEPTASSESSMENT']);
        $div = mysqli_real_escape_string($conn, $_POST['DIVASSESSMENT']);
        $nik = mysqli_real_escape_string($conn, $_POST['LEADASSESSMENT']);

        $sql = "SELECT A.*, B.head_id_divisi, C.officer_leader_assest FROM statusassessment AS A
        INNER JOIN divisi_assessment AS B ON A.code_sts_assest = B.head_code_sts_assest
        INNER JOIN leader_assessment AS C ON A.code_sts_assest = C.head_code_sts_assest
        WHERE A.office_sts_assest = '$off' AND A.dept_sts_assest = '$dpt' AND B.head_id_divisi = '$div' AND C.officer_leader_assest = '$nik' AND A.flag_sts_assest = 'N' GROUP BY A.tahun_sts_assest ORDER BY A.tahun_sts_assest ASC";
        $query = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($query);

        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['tahun_sts_assest'].'">'.$row['tahun_sts_assest'].'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["OFFICEASSESST"]) && !empty($_POST["OFFICEASSESST"]) && isset($_POST["DEPTASSESST"]) && !empty($_POST["DEPTASSESST"]) && isset($_POST["DIVASSESST"]) && !empty($_POST["DIVASSESST"]) && isset($_POST["LEADASSESST"]) && !empty($_POST["LEADASSESST"]) && isset($_POST["TAHUNASSESST"]) && !empty($_POST["TAHUNASSESST"])){

        $off = mysqli_real_escape_string($conn, $_POST['OFFICEASSESST']);
        $dpt = mysqli_real_escape_string($conn, $_POST['DEPTASSESST']);
        $div = mysqli_real_escape_string($conn, $_POST['DIVASSESST']);
        $nik = mysqli_real_escape_string($conn, $_POST['LEADASSESST']);
        $thn = mysqli_real_escape_string($conn, $_POST['TAHUNASSESST']);

        $sql = "SELECT A.*, B.head_id_divisi, C.officer_leader_assest, C.junior_leader_assest, D.username FROM statusassessment AS A
        INNER JOIN divisi_assessment AS B ON A.code_sts_assest = B.head_code_sts_assest
        INNER JOIN leader_assessment AS C ON A.code_sts_assest = C.head_code_sts_assest
        INNER JOIN users AS D ON C.junior_leader_assest = D.nik
        WHERE A.office_sts_assest = '$off' AND A.dept_sts_assest = '$dpt' AND B.head_id_divisi = '$div' AND C.officer_leader_assest = '$nik' AND A.flag_sts_assest = 'N' AND A.tahun_sts_assest = '$thn' GROUP BY C.junior_leader_assest";
        $query = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($query);

        if($rowCount > 0){
            echo '<option value="" selected disabled>Please Select</option>';
            while($row = mysqli_fetch_assoc($query)){ 
                echo '<option value="'.$row['junior_leader_assest'].'">'.$row['junior_leader_assest'].' - '.strtoupper($row['username']).'</option>';
            }
        }
        else{
            echo '<option value="" selected disabled>Please Select</option>';
        }
    }

    if(isset($_POST["JUNIORASSESSMENT"]) && !empty($_POST["JUNIORASSESSMENT"])){

        $id = mysqli_real_escape_string($conn, $_POST['JUNIORASSESSMENT']);

        $sql = "SELECT A.id_divisi, B.divisi_name FROM users AS A
        INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
        WHERE A.nik = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
    }

    if(isset($_POST["ACTIONDETAILMONITORASSESSMENT"]) && !empty($_POST["ACTIONDETAILMONITORASSESSMENT"])){

        $dataid = $_POST['ACTIONDETAILMONITORASSESSMENT'];
        $nik = substr($dataid, 0, 10);
        $id = substr($dataid, 10);

        $sql = "SELECT A.head_id_sts_assest AS ID_ASSESST, A.docno_data_assest AS DOCNO_ASSESST, A.th_data_assest AS THN_ASSESST, A.date_data_assest AS DATE_ASSESST, CONCAT(A.leader_data_assest, ' - ', B.username) AS LEADER_ASSESST, A.poin_data_assest AS POIN_ASSESST, A.mutu_data_assest AS GRADE_ASSESST, A.status_data_assest AS STATUS_ASSESST FROM data_assessment AS A
        INNER JOIN users AS B ON A.leader_data_assest = B.nik
        WHERE A.head_id_sts_assest = '$id' AND A.junior_data_assest = '$nik'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($result = mysqli_fetch_assoc($query)) {
                $result = array(
                    "ID_ASSESST"=>$result["ID_ASSESST"],
                    "DOCNO_ASSESST"=>$result["DOCNO_ASSESST"],
                    "THN_ASSESST"=>$result["THN_ASSESST"],
                    "DATE_ASSESST"=>$result["DATE_ASSESST"],
                    "LEADER_ASSESST"=>$result["LEADER_ASSESST"],
                    "POIN_ASSESST"=>$result["POIN_ASSESST"],
                    "GRADE_ASSESST"=>$result["GRADE_ASSESST"],
                    "STATUS_ASSESST"=>$result["STATUS_ASSESST"],
                    "ENCRYPT_ASSESST"=>encrypt($result["DOCNO_ASSESST"]),
                );
                $row[] = $result;
            }
            die(json_encode(array(
                "data"=>$row
            )));
        }

    }

    if(isset($_POST["ACTIONMONITORINGASSESSMENT"]) && !empty($_POST["ACTIONMONITORINGASSESSMENT"])){

        $dataid = mysqli_real_escape_string($conn, $_POST['ACTIONMONITORINGASSESSMENT']);
        $nik = substr($dataid, 0, 10);
        $code = substr($dataid, 10, 4);
        $id = substr($dataid, 14);

        $sql = "SELECT A.head_id_sts_assest, A.leader_data_assest, A.junior_data_assest, B.code_sts_assest FROM data_assessment AS A
        INNER JOIN statusassessment AS B ON A.head_id_sts_assest = B.id_sts_assest
        WHERE A.head_id_sts_assest = '$id' AND A.junior_data_assest = '$nik'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
    }

    if(isset($_POST["ACTIONMONITORINGASSESSMENTNIK"]) && !empty($_POST["ACTIONMONITORINGASSESSMENTNIK"])){

        $id = mysqli_real_escape_string($conn, $_POST['ACTIONMONITORINGASSESSMENTNIK']);

        $sql = "SELECT head_id_sts_assest, docno_data_assest, leader_data_assest, junior_data_assest FROM data_assessment WHERE docno_data_assest = '$id'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
    }

    if(isset($_POST["ACTIONMONITORINGASSESSMENTLAPOR"]) && !empty($_POST["ACTIONMONITORINGASSESSMENTLAPOR"])){

        $dataid = mysqli_real_escape_string($conn, $_POST['ACTIONMONITORINGASSESSMENTLAPOR']);
        $nik = substr($dataid, 0, 10);
        $id = substr($dataid, 10);

        $sql = "SELECT A.head_id_sts_assest, A.docno_data_assest, A.leader_data_assest, A.th_data_assest, B.code_sts_assest FROM data_assessment AS A
        INNER JOIN statusassessment AS B ON A.head_id_sts_assest = B.id_sts_assest
        WHERE A.head_id_sts_assest = '$id' AND A.leader_data_assest = '$nik'";

        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        die(json_encode($data));
    }

    if(isset($_POST["ACTIONDETAILMONITORASSESSMENTDRAFT"]) && !empty($_POST["ACTIONDETAILMONITORASSESSMENTDRAFT"])){

        $dataid = $_POST['ACTIONDETAILMONITORASSESSMENTDRAFT'];
        $nik = substr($dataid, 0, 10);
        $id = substr($dataid, 10);

        $sql = "SELECT A.head_id_sts_assest AS ID_ASSESST, A.docno_data_assest AS DOCNO_ASSESST, A.th_data_assest AS THN_ASSESST, A.date_data_assest AS DATE_ASSESST, CONCAT(A.junior_data_assest, ' - ', B.username) AS JUNIOR_ASSESST, A.poin_data_assest AS POIN_ASSESST, A.mutu_data_assest AS GRADE_ASSESST, A.status_data_assest AS STATUS_ASSESST FROM data_assessment AS A
        INNER JOIN users AS B ON A.junior_data_assest = B.nik
        WHERE A.head_id_sts_assest = '$id' AND A.leader_data_assest = '$nik'";

        $query = mysqli_query($conn, $sql);

        $row = array();
        if ($query) {
            while($data = mysqli_fetch_assoc($query)) {
                $row[] = $data;
            }
            die(json_encode(array("data"=>$row)));
        }
        
    }

    if(isset($_POST["ACTIONDETAILASSESSMENT"]) && !empty($_POST["ACTIONDETAILASSESSMENT"])){

        $id = $_POST['ACTIONDETAILASSESSMENT'];

        $sql = "SELECT A.*, B.*, C.office_name, D.department_name, E.divisi_name, F.level_name, G.full_name AS leader_name, H.full_name AS junior_name FROM data_assessment AS A 
        INNER JOIN sub_data_assessment AS B ON A.docno_data_assest = B.head_docno_data_assest
        INNER JOIN office AS C ON A.office_data_assest = C.id_office
        INNER JOIN department AS D ON A.dept_data_assest = D.id_department
        INNER JOIN divisi AS E ON A.div_data_assest = E.id_divisi
        INNER JOIN level AS F ON A.lvl_data_assest = F.id_level
        INNER JOIN users AS G ON A.leader_data_assest = G.nik
        INNER JOIN users AS H ON A.junior_data_assest = H.nik
        WHERE A.docno_data_assest = '$id'";

        $query_header = mysqli_query($conn, $sql);
        $data_header = mysqli_fetch_assoc($query_header);
        $rowCount = mysqli_num_rows($query_header);

        if ($rowCount > 0) { ?>
        <div class="form-row">
            <div class="col-md-6 mb-2">
                <label>KANTOR </label>
                <input class="form-control" type="text" value="<?= $data_header["office_data_assest"]." - ".strtoupper($data_header['office_name']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>DEPARTEMEN </label>
                <input class="form-control" type="text" value="<?= strtoupper($data_header['department_name']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>DIVISI </label>
                <input class="form-control" type="text" value="<?= strtoupper($data_header['divisi_name']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>TAHUN </label>
                <input class="form-control" type="text" value="<?= $data_header['th_data_assest']; ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>ASSESSED </label>
                <input class="form-control" type="text" value="<?= $data_header['leader_data_assest']." - ".strtoupper($data_header['leader_name']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label>KARYAWAN </label>
                <input class="form-control" type="text" value="<?= $data_header['junior_data_assest']." - ".strtoupper($data_header['junior_name']); ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label>INSTRUMEN EVALUASI PENILAIAN</label>
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>INDIKATOR PENILAIAN</th>
                            <th>POIN</th>
                            <th>GRADE</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $query_detail = mysqli_query($conn, $sql);
                    while ($data_detail = mysqli_fetch_assoc($query_detail)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $no++;?></th>
                            <td><?= $data_detail['indikator_sub_data_assest'];?></td>
                            <td><?= $data_detail['poin_sub_data_assest'];?></td>
                            <td><?= $data_detail['mutu_sub_data_assest'];?></td>
                        </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td colspan="2">NILAI</td>
                        <td><?= $data_header['poin_data_assest'];?></td>
                        <td><?= $data_header['mutu_data_assest'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 mb-2">
                <label>CATATAN : </label>
                <textarea class="form-control" type="text" readonly><?= $data_header["note_data_assest"]; ?></textarea>
            </div>
        </div>
    <?php
        }
    }

    if(isset($_POST["PRINTASSESSMENT"]) && !empty($_POST["PRINTASSESSMENT"])){

        $dataid = mysqli_real_escape_string($conn, $_POST['PRINTASSESSMENT']);
        
        session_start();

        if(!isset($_SESSION[$dataid])) {
            $_SESSION[$dataid] = $_POST;
        }

        die(json_encode(encrypt($dataid)));
    }

}
else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}

?>