<?php

// function get version app
function getVersion() {

    $version = "V 2.0.5.0";

    return $version;
}
// End function get version app end

// ---------------------------- //

// Function cookie user login
function log_cookie($encuser, $encpass) {

    // Create expired cookie id
    setcookie ("id", $encuser, time() + (60 * 10));
    setcookie ("key", $encpass, time() + (60 * 10));

}
// End function cookie user login

// ---------------------------- //

// Function encrypt data
function encrypt($message){

    $encryption_key = '6676636b74686572756c6521'; //some random hex characters
    $key            = hex2bin($encryption_key);

    // algos AES-256-CBC menghasilkan sting lenght > 16 char
    $nonceSize      = openssl_cipher_iv_length('AES-256-CBC');
    $nonce          = openssl_random_pseudo_bytes($nonceSize);
    $ciphertext     = openssl_encrypt($message, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $nonce);
    $crypt          = base64_encode($nonce.$ciphertext);

    return $crypt; 
    
}
// End function encrypt data

// ---------------------------- //

// Function decrypt data
function decrypt($message){

    $encryption_key = '6676636b74686572756c6521'; //some random hex characters
    $key            = hex2bin($encryption_key);
    $message        = base64_decode($message);
    $nonceSize      = openssl_cipher_iv_length('AES-256-CBC');
    $nonce          = mb_substr($message, 0, $nonceSize, '8bit');
    $ciphertext     = mb_substr($message, $nonceSize, null, '8bit');

    // check string > 16 untuk menghindari manipulasi input url
    if(strlen($ciphertext) >= 16 ) {

        $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $nonce);

        return $plaintext;

    }

    else {

        return false;

    }
    
    // Sample encrypt
    // $string    = "Lets Try This";
    // $encrypted = encrypt($string);
    // $decrypted = decrypt($encrypted);

}
// End function decrypt data

// ---------------------------- //

// Function replace + plus encrypt data
function rplplus($cryptkey) {

    $strplus = str_ireplace(" ", "+", $cryptkey);
    return $strplus;

}
// End function replace + plus encrypt data

// ---------------------------- //

// function autonumber new id  from table
function autoid($lenght_id, $lenght_num, $id, $table) {

    global $conn;

    // mengambil id terbesar pada tabel
    $query = 'SELECT '.$id.' AS max_id FROM '.$table.' WHERE '.$id.' IS NOT NULL ORDER BY '.$id.' DESC LIMIT 1';
    $result = mysqli_query($conn, $query);    
    $data   = mysqli_fetch_assoc($result);

    $last_id = isset($data) ? $data['max_id'] : NULL;

    // mengambil id string
    $code = substr($last_id, 0, $lenght_id);

    // mengambil nilai angka
    $num = substr($last_id, $lenght_id, $lenght_num);
    
    // menambahkan nilai angka dengan 1
    // variabel $lenght_num mengambil jumlah nilai number pada database misal nilai angka adalah 2 
    // kemudian memberikan string 0 agar panjang string angka menjadi 2
    // ex: angka baru = 6 maka ditambahkan strig 0 dua kali
    // sehingga menjadi 06
    $new_num = str_repeat("0", $lenght_num - strlen($num+1)).($num+1);
    
    // mengabungkan id dengan nilai baru
    $new_id = $code.$new_num;

    return $new_id;

    // Sample autonumberstr
    // ID From DB it's = LV001
    // $code = 2;
    // $num = 3;
    // $tablename = 'id';
    // $dbname = 'office';
    // echo autonumberstr($code, $num, $tablename, $dbname);
   
}
// End function autonumber id table

// ---------------------------- //

// function autonumber new id varchar from table
function autokeynum($lenght_value, $id, $key1, $col1, $key2, $col2, $table){

    global $conn;

    // mengambil  terbesar pada tabel
    $query = 'SELECT MAX(RIGHT('.$id.', '.$lenght_value.')) AS max_id FROM '.$table.' WHERE '.$col1.' = "'.$key1.'" AND '.$col2.' = "'.$key2.'" ORDER BY '.$id;
    $result = mysqli_query($conn, $query);
    $data   = mysqli_fetch_assoc($result);
    
    $last_id = $data['max_id'];

    $num = (int) substr($last_id, 0, $lenght_value);
    
    $new_num = str_repeat("0", $lenght_value - strlen($num+1)).($num+1);

    return $new_num;

}
// End function autonumber in table

// ---------------------------- //

// function autonumber new id varchar from table
function autonum($lenght_value, $id, $table){

    global $conn;

    // mengambil  terbesar pada tabel
    $query = 'SELECT MAX(RIGHT('.$id.', '.$lenght_value.')) AS max_id FROM '.$table.' ORDER BY '.$id;
    $result = mysqli_query($conn, $query);
    $data   = mysqli_fetch_assoc($result);
    
    $last_id = $data['max_id'];

    $num = (int) substr($last_id, 0, $lenght_value);
    
    $new_num = str_repeat("0", $lenght_value - strlen($num+1)).($num+1);

    return $new_num;

    // Sample autonumber
    // $tablename = 'id';
    // $dbname = 'office';
    // echo autonumber($tablename, $dbname);

}
// End function autonumber in table

// ---------------------------- //

// function get ip client
function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif(isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    }
    elseif(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    elseif(isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    }
    elseif(isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    else {
        $ipaddress = 'UNKNOWN';
    }

    $ip = $ipaddress;

    return $ip;
}
// End function get ip client end

// ---------------------------- //

// function browser client
function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
  
    // First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
  
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
  
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
    }
  
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
  
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
  
    $arragent = array(
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform
    );

    $browser = $arragent['name'];
    $v = $arragent['version'];
    // $on = 'on';
    // $platfrm = $arragent['platform'];

    $newaggent = $browser.' '.$v;

    return $newaggent;

  }
// End function get browser client end

// ---------------------------- //

// function date romawi
function getRomawi($bln){
    switch ($bln){
            case 1: 
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
      }
}
// End function date romawi

// ---------------------------- //

// ---------------------------------------- Functions Register Modul ---------------------------------------- //

// Function registrasi
function registrasi($data) {

    global $conn;
    global $timezone;

    // Input data
    $page =  $data["page"];
    $date   = date("Y-m-d H:i:s");
    $office = htmlspecialchars(strtoupper($data["user-office"]));
    $nik    = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-nik"]));
    $user   = htmlspecialchars(mysqli_real_escape_string($conn, strtolower(stripslashes($data["user-name"]))));
    $email  = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["user-email"])));
    $pass   = mysqli_real_escape_string($conn, $data["user-password"]);
    
    // Validasi Office Code 4 char
    if(strlen($office) != 4) {

        $GLOBALS['alert'] = array("Gagal!", "Kode Gudang Wajib 4 Digit", "error", "$page");
        return false;
        die();

    }

    // Check office di database
    $result_office = mysqli_query($conn, "SELECT id_office FROM office WHERE id_office = '$office'");

    if(!mysqli_fetch_assoc($result_office)) {

        $GLOBALS['alert'] = array("Gagal!", "Kode Gudang ".$office." Tidak Terdaftar", "error", "$page");
        return false;

    }

    // Validasi NIK 10 char
    if(strlen($nik) != 10) {

        $GLOBALS['alert'] = array("Gagal!", "Harap Periksa Kembali NIK Anda", "error", "$page");
        return false;

    }

    // Check nik di database
    $result = mysqli_query($conn, "SELECT nik FROM users WHERE nik ='$nik' ");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "NIK ".$nik." Telah Terdaftar", "error", "$page");
        return false;

    }

    // Check username di database
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username ='$user' ");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Username ".$user." Telah Terdaftar", "error", "$page");
        return false;

    }

    // Validasi password < 6
    if(strlen($pass) < 6) {

        $GLOBALS['alert'] = array("Gagal!", "Password Minimal 6 Karakter", "error", "$page");
        return false;

    }

    // Enkripsi password
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insert data to database
    mysqli_query($conn, "INSERT INTO users (id_office, nik, username, email, password, create_date) VALUES ('".$office."','".$nik."','".$user."','".$email."','".$pass."','".$date."')");

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION["ALERTREGISTER"] = $_POST;
    
    return mysqli_affected_rows($conn);

}
// End Function registrasi

// ---------------------------- //

// Function update profile modul dev
function updateprofile($data) {

    global $conn;

    // Input data
    $id_page = htmlspecialchars($data["page"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));
    $div = htmlspecialchars(mysqli_real_escape_string($conn, $data["divisi"]));
    $lvl = htmlspecialchars(mysqli_real_escape_string($conn, $data["level"]));
    $id_user = htmlspecialchars(mysqli_real_escape_string($conn, $data["nik"]));
    $fullname = htmlspecialchars(mysqli_real_escape_string($conn, $data["fullname"]));
    $gender = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["gender"]) ? $data["gender"] : NULL));
    $tgllahir = htmlspecialchars(mysqli_real_escape_string($conn, $data["birthday"] == "" ? "0000-00-00" : $data["birthday"]));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["email"])));
    $oldgambar = $data["oldfoto"];

    if ($_FILES['foto']['error'] === 4) {

        $gambar = $oldgambar;

    }
    else {

        $gambar = uploadfoto($id_user, $id_page);

        if ($gambar === FALSE) {
            return FALSE;
        }

    }

    mysqli_query($conn, "UPDATE users SET full_name = '$fullname', gender = '$gender', tgl_lahir = '$tgllahir', email = '$email', foto = '$gambar' WHERE nik = '$id_user'");

    if (isset($data["leader"])) {        
        $leader = $data["leader"];
        if (count($leader) > 0) {
    
            if (in_array('DELETE', $leader)) {
                mysqli_query($conn, "DELETE FROM subleader_users WHERE nik_head_lead_user = '$id_user'");
                mysqli_query($conn, "DELETE FROM leader_users WHERE nik_lead_user = '$id_user'");
            } else {
                $sql_leader = "SELECT nik_lead_user FROM leader_users WHERE nik_lead_user = '$id_user'";
                $query_leader = mysqli_query($conn, $sql_leader);
            
                $datalead = implode(", ", $leader);
                if(mysqli_num_rows($query_leader) > 0 ) {
        
                    mysqli_query($conn, "DELETE FROM subleader_users WHERE nik_head_lead_user = '$id_user'");
                    mysqli_query($conn, "DELETE FROM leader_users WHERE nik_lead_user = '$id_user'");
                    
                    foreach ($leader as $arr)  {
                        mysqli_query($conn, "INSERT INTO subleader_users (nik_head_lead_user, name_sublead_user) VALUES ('$id_user', '$arr')");
                    }
                    
                    mysqli_query($conn, "INSERT INTO leader_users (office_lead_user, dept_lead_user, div_lead_user, lvl_lead_user, nik_lead_user, name_lead_user) VALUES ('$office', '$dept', '$div', '$lvl', '$id_user', '$datalead')");
                }
                else {
                    mysqli_query($conn, "INSERT INTO leader_users (office_lead_user, dept_lead_user, div_lead_user, lvl_lead_user, nik_lead_user, name_lead_user) VALUES ('$office', '$dept', '$div', '$lvl', '$id_user', '$datalead')");
        
                    foreach ($leader as $arr)  {
                        mysqli_query($conn, "INSERT INTO subleader_users (nik_head_lead_user, name_sublead_user) VALUES ('$id_user', '$arr')");
                    }
                }
            }
            
        }
    }

    return mysqli_affected_rows($conn);

}
// End function update profile

// ---------------------------- //

// Function update / upload foto
function uploadfoto($id_user, $id_page) {

    $name  = $_FILES["foto"]["name"];
    $size  = $_FILES["foto"]["size"];
    $error = $_FILES["foto"]["error"];
    $tmp   = $_FILES["foto"]["tmp_name"];

    $eksgambarvalid = ["jpg", "jpeg", "png", "bmp"];
    $eksgambar      = explode('.', $name);
    $eksgambar      = strtolower(end($eksgambar));

    if(!in_array($eksgambar, $eksgambarvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format gambar", "error", "$id_page");
        return false;

    }

    $maxsize = 1024 * 1000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 1MB", "error", "$id_page");
        return false;
    
    }

    $gambar = $id_user;
    $gambar .= '.';
    $gambar .= $eksgambar;

    move_uploaded_file($tmp, 'files/img/' . $gambar);

    return $gambar;

}
// End function update / upload foto

// ---------------------------- //

// ---------------------------------------- End ---------------------------------------- //



















// ---------------------------------------- Functions User Modul ---------------------------------------- //

// Function Insert New User
function insert_newuser($data) {

    global $conn;
    global $timezone;

    // Input data
    $id_page    = htmlspecialchars($data["page"]);
    $date   = date("Y-m-d H:i:s");
    $nik    = htmlspecialchars($data["nikuser"]);
    $user   = htmlspecialchars(mysqli_real_escape_string($conn, strtolower(stripslashes($data["username"]))));
    $fullname = htmlspecialchars(mysqli_real_escape_string($conn, $data["fullname"]));
    $email  = strtolower($data["email"]);
    $pass   = mysqli_real_escape_string($conn, $data["password"]);
    $ip = htmlspecialchars($data["ipaddress"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));
    $div = htmlspecialchars(mysqli_real_escape_string($conn, $data["divisi"]));
    $group = htmlspecialchars(mysqli_real_escape_string($conn, $data["group"]));
    $level = htmlspecialchars(mysqli_real_escape_string($conn, $data["level"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["status"]));
    $akses = htmlspecialchars($data["akses"]);

    // Validasi NIK 10 char
    if(strlen($nik) != 10) {

        $GLOBALS['alert'] = array("Gagal!", "Harap Periksa Kembali NIK Anda", "error", "$id_page");
        return false;

    }

    // Check nik di database
    $result = mysqli_query($conn, "SELECT nik FROM users WHERE nik ='$nik'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "NIK ".$nik." Telah Terdaftar", "error", "$id_page");
        return false;

    }

    // Check username di database
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username ='$user' ");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Username ".$user." Telah Terdaftar", "error", "$id_page");
        return false;

    }

    // Validasi password < 6
    if(strlen($pass) < 6) {

        $GLOBALS['alert'] = array("Gagal!", "Password Minimal 6 Char", "error", "$id_page");
        return false;

    }

    // Enkripsi password
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insert data to database
    mysqli_query($conn, "INSERT INTO users (nik, username, email, password, full_name, ip_address, id_office, id_department, id_divisi, id_group, id_level, status, create_date, akses_ip) VALUES ('$nik', '$user', '$email', '$pass', '$fullname', '$ip', '$office', '$dept', '$div', '$group', '$level', '$status', '$date', '$akses')");

    return mysqli_affected_rows($conn);

}
// End Function Insert New User

// ---------------------------- //

// function update username
function updateuser($data) {

    global $conn;

    // Input data
    $id_page  = htmlspecialchars($data["page"]);
    $id_user  = htmlspecialchars(mysqli_real_escape_string($conn, $data["nik"]));
    $username = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["username"])));

    // Check input user
    if($username == "" || empty($username)) {
        $GLOBALS['alert'] = array("Gagal!", "Username tidak boleh kosong", "error");
        return false;

    }

    // Check username di database
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username' ");

    if(mysqli_fetch_assoc($result)) {
        $GLOBALS['alert'] = array("Gagal!", "Username ".$username." telah terdaftar", "error", "$id_page");
        return false;

    }

    // Update to database
    $query = "UPDATE users SET username = '$username' WHERE nik = '$id_user' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update username

// ---------------------------- //

// Function update password
function updatepwd($data) {

    global $conn;

    // Input data
    $id_page  = htmlspecialchars($data["page"]);
    $id_user = htmlspecialchars(mysqli_real_escape_string($conn, $data["nik"]));
    $oldpassword = mysqli_real_escape_string($conn, $data["oldpassword"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $repassword = mysqli_real_escape_string($conn, $data["repassword"]);

    // Check input password
    if($oldpassword == "" || empty($oldpassword) && $password == "" || empty($password) && $repassword == "" || empty($repassword)) {

        $GLOBALS['alert'] = array("Gagal!", "Password tidak boleh kosong", "error", "$id_page");
        return false;

    }

    // Cek password berdasarkan id modul user
    $result = mysqli_query($conn, "SELECT password FROM users WHERE nik = '$id_user' ");
    $row    = mysqli_fetch_assoc($result);

    // Validasi password
    $cekpassword = password_verify($oldpassword, $row["password"]);
    if($cekpassword === false) {

        $GLOBALS['alert'] = array("Gagal!", "Password lama tidak sesuai", "error", "$id_page");
        return false;

    }

    // Validasi password < 6
    if(strlen($password) < 6) {

        $GLOBALS['alert'] = array("Gagal!", "Password minimal 6 char", "error", "$id_page");
        return false;

    }
        
    // Validasi password dengan repassword
    if( $password !== $repassword ) {

        $GLOBALS['alert'] = array("Gagal!", "Password baru tidak sesuai", "error", "$id_page");
        return false;
    
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Update password to database
    $query = "UPDATE users SET password = '$password' WHERE nik = '$id_user' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update password

// ---------------------------- //

// function user aktivasi
function usersaktivasi($data) {

    global $conn;

    // Input data
    $id_page = htmlspecialchars($data["page"]);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id"]));
    $nikold = htmlspecialchars(mysqli_real_escape_string($conn, $data["nikold"]));
    $nik = htmlspecialchars(mysqli_real_escape_string($conn, $data["nik"]));
    $fullname = htmlspecialchars(mysqli_real_escape_string($conn, $data["fullname"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));
    $div = htmlspecialchars(mysqli_real_escape_string($conn, $data["divisi"]));
    $group = htmlspecialchars(mysqli_real_escape_string($conn, $data["group"]));
    $level = htmlspecialchars(mysqli_real_escape_string($conn, $data["level"]));
    $ip = htmlspecialchars(mysqli_real_escape_string($conn, $data["ipaddress"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["status"]));
    $akses = htmlspecialchars($data["akses"]);

    if ($nikold != $nik) {
        // Validasi NIK 10 char
        if(strlen($nik) != 10) {

            $GLOBALS['alert'] = array("Gagal!", "Harap Periksa Kembali NIK yang ingin anda rubah", "error", "$id_page");
            return false;

        }

        // Check nik di database
        $result = mysqli_query($conn, "SELECT nik FROM users WHERE nik ='$nik'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['alert'] = array("Gagal!", "NIK ".$nik." Telah Terdaftar", "error", "$id_page");
            return false;

        }
    }

    // Update user to database
    $query = "UPDATE users SET nik = '$nik', id_office = '$office', id_department = '$dept', id_divisi = '$div', full_name = '$fullname', ip_address = '$ip', id_group = '$group', id_level = '$level', akses_ip = '$akses', status = '$status' WHERE id = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update user aktivasi

// ---------------------------- //

// function update user
function EditDataUser($data) {

    global $conn;

    // Input data
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["nik"]));
    $fullname = htmlspecialchars(mysqli_real_escape_string($conn, $data["fullname"]));
    $level = htmlspecialchars(mysqli_real_escape_string($conn, $data["level"]));

    // Update user to database
    $query = "UPDATE users SET id_level = '$level', full_name = '$fullname' WHERE nik = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update user

// ---------------------------- //

// function delete user
function deluser($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["delnik"]));
    
    $result = "DELETE FROM users WHERE nik = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete user

// ---------------------------- //

// Function insert master barang modul user
function InsertBarang($data) {

    global $conn;

    // Input data post
    $id_page = htmlspecialchars($data['page']);
    $id_cat = htmlspecialchars(mysqli_real_escape_string($conn, $data['category']));
    $id_brg = htmlspecialchars(mysqli_real_escape_string($conn, $data['idbarang']));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["namabarang"]))));
    $satuan = htmlspecialchars(mysqli_real_escape_string($conn, $data['satuanbarang']));

    $newid = $id_cat.$id_brg;

    // Validasi PLU Jenis wajib 5 digit
    if(strlen($id_brg) != 5) {

        $GLOBALS['alert'] = array("Gagal!", "Panjang PLU Wajib 5 Digit", "error", "$id_page");
        return false;

    }

    // Check id jenis barang jika sudah ada
    $query = mysqli_query($conn, "SELECT IDBarang FROM mastercategory WHERE IDBarang = '$newid'");
    
    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "PLU ".$id_brg." Sudah Terdaftar", "error", "$id_page");
        return false;
    
    }

    // Check nama barang di database
    $result = mysqli_query($conn, "SELECT NamaBarang FROM mastercategory WHERE NamaBarang = '$nama'");
   
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Nama Barang Sudah Terdaftar", "error", "$id_page");
        return false;
    
    }

    mysqli_query($conn, "INSERT INTO mastercategory (IDBarang, NamaBarang, id_satuan) VALUES ('$newid', '$nama', '$satuan')");


    return mysqli_affected_rows($conn);

}
// End function insert master barang

// ---------------------------- //

// Function import csv
function ImportBarang($data) {

    global $conn;

    $id_page = htmlspecialchars($data['page']);

    $name  = $_FILES["file-import"]["name"];
    $size  = $_FILES["file-import"]["size"];
    $error = $_FILES["file-import"]["error"];
    $tmp   = $_FILES["file-import"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$id_page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File Yang Diizinkan Hanya Format CSV", "error", "$id_page");
        return false;
 
    }

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        $stored = [];
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            $cat = isset($line[0]) ? strtoupper($line[0]) : NULL;
            $pluid = isset($line[1]) ? $line[1] : NULL;
            $desc = isset($line[2]) ? strtoupper($line[2]) : NULL;
            $satuan = isset($line[3]) ? strtoupper($line[3]) : NULL;

            $gab = $cat.$pluid;

            //skip current row if it is a duplicate
            if (in_array($pluid, $stored)) {continue;}

            $query_cat = mysqli_query($conn, "SELECT IDCategory FROM categorybarang WHERE IDCategory = '$cat'");
            $result_cat = mysqli_fetch_assoc($query_cat);

            if (!$result_cat) {

                $GLOBALS['alert'] = array("Gagal!", "ID Category ".$cat." Tidak Terdaftar", "error", "$id_page");
                return false;

            }
            
            // Validasi PLU Jenis wajib 5 digit
            if(strlen($pluid) != 5) {

                $GLOBALS['alert'] = array("Gagal!", "Panjang PLU Wajib 5 Digit", "error", "$id_page");
                return false;

            }

            // Check id jenis barang jika sudah ada
            $query = mysqli_query($conn, "SELECT IDBarang FROM mastercategory WHERE IDBarang = '$gab'");

            if(mysqli_fetch_assoc($query)) {

                $GLOBALS['alert'] = array("Gagal!", "PLU ".$gab." Sudah Terdaftar", "error", "$id_page");
                return false;

            }

            // Check nama barang di database
            $result = mysqli_query($conn, "SELECT NamaBarang FROM mastercategory WHERE NamaBarang = '$desc'");

            if(mysqli_fetch_assoc($result)) {

                $GLOBALS['alert'] = array("Gagal!", "Nama Barang ".$desc." Sudah Terdaftar", "error", "$id_page");
                return false;

            }

            $query_satuan = mysqli_query($conn, "SELECT id_satuan FROM satuan WHERE id_satuan = '$satuan'");
            $result_satuan = mysqli_fetch_assoc($query_satuan);

            if (!$result_satuan) {

                $GLOBALS['alert'] = array("Gagal!", "ID Satuan ".$satuan." Tidak Terdaftar", "error", "$id_page");
                return false;
                die();

            }
            

            mysqli_query($conn, "INSERT INTO mastercategory (IDBarang, NamaBarang, id_satuan) VALUES  ('$gab', '$desc', '$satuan')");
            //remember inserted value

            $stored[] = $pluid;
            
        }

        fclose($file);

        return mysqli_affected_rows($conn);
        
    }

}
// End function import csv

// ---------------------------- //

// function update master barang
function UpdateBarang($data) {

    global $conn;

    // Input data
    $id_page = htmlspecialchars($data['page']);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["upd-idbarang"]));
    $namaold = htmlspecialchars($data["namabarangold"]);
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes($data["namabarang"])));
    $satuan = htmlspecialchars(mysqli_real_escape_string($conn, $data['satuanbarang']));

    if ($namaold != $nama) {
        // Check nama barang di database
        $query = mysqli_query($conn, "SELECT NamaBarang FROM mastercategory WHERE NamaBarang = '$nama'");
    
        if(mysqli_fetch_assoc($query)) {
    
            $GLOBALS['alert'] = array("Gagal!", "Nama Barang ".$nama." Sudah Terdaftar", "error", "$id_page");
            return false;
            die();
    
        }
    }
    
    // Update user to database
    $query = "UPDATE mastercategory SET NamaBarang = '$nama', id_satuan = '$satuan' WHERE IDBarang = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update user aktivasi

// ---------------------------- //

// function delete barang
function DeleteBarang($data) {

    global $conn;

    $page = htmlspecialchars($data["page"]);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["del-idbarang"]));
    
    // Check nama barang di database
    $query = mysqli_query($conn, "SELECT IDBarang FROM masterjenis WHERE IDBarang = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "PLU Barang ".$id." Tidak Dapat Dihapus, Karena Sudah Memiliki Jenis Barang", "error", "$page");
        return false;
        die();
    
    }

    $result = "DELETE FROM mastercategory WHERE IDBarang = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete barang

// ---------------------------- //

// Function insert jenis barang modul user
function InsertJenis($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $idbarang = htmlspecialchars(mysqli_real_escape_string($conn, $data["idbarang"]));
    $namajenis = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["namajenis"])));
    $hargajenis = htmlspecialchars(mysqli_real_escape_string($conn, $data["hargajenis"]));

    $idmax = autonum(4, 'IDJenis', 'masterjenis');

    // Check Form Input
    if($hargajenis < 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Estimasi Harga Tidak Boleh Kurang Dari Nol", "error", "$page");
        return false;
        die();
    }
    
    // Check Form Input
    if($namajenis == "" || empty($namajenis)) {
        $sql = "INSERT INTO masterjenis (IDBarang, IDJenis, HargaJenis) VALUES ('$idbarang', '$idmax', '$hargajenis')";
    }
    else {
        $sql = "INSERT INTO masterjenis (IDBarang, IDJenis, NamaJenis, HargaJenis) VALUES ('$idbarang', '$idmax', '$namajenis', '$hargajenis')";
    }

    // Insert data to database
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);

}
// End function insert data submenu

// ---------------------------- //

// Function import csv
function ImportJenisBarang($data) {

    global $conn;

    $id_page = htmlspecialchars($data['page']);

    $name  = $_FILES["file-importjenis"]["name"];
    $size  = $_FILES["file-importjenis"]["size"];
    $error = $_FILES["file-importjenis"]["error"];
    $tmp   = $_FILES["file-importjenis"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$id_page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File Yang Diizinkan Hanya Format CSV", "error", "$id_page");
        return false;
 
    }

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            $pluid = isset($line[0]) ? strtoupper($line[0]) : NULL;
            $desc = isset($line[1]) ? strtoupper($line[1]) : NULL;
            $estharga = isset($line[2]) ? strtoupper($line[2]) : NULL;

            // Check id jenis barang jika sudah ada
            $query_desc = mysqli_query($conn, "SELECT IDBarang FROM mastercategory WHERE IDBarang = '$pluid'");
            $result_desc = mysqli_fetch_assoc($query_desc);

            if(!$result_desc) {

                $GLOBALS['alert'] = array("Gagal!", "PLU Barang ".$pluid." Tidak Terdaftar", "error", "$id_page");
                return false;

            }

            if($estharga < 0 ) {

                $GLOBALS['alert'] = array("Gagal!", "Estimasi Harga Tidak Boleh Kurang Dari Nol", "error", "$id_page");
                return false;
                die();
                
            }

            $idmax = autonum(4, 'IDJenis', 'masterjenis');
            $idnew = $idmax++;            

            //remember inserted value
            $sql_insert = "INSERT INTO masterjenis (IDBarang, IDJenis, NamaJenis, HargaJenis) VALUES ('$pluid', '$idnew', '$desc', '$estharga')";
    
            mysqli_query($conn, $sql_insert);
            
        }

        fclose($file);

        return mysqli_affected_rows($conn);
        
    }

}
// End function import csv

// ---------------------------- //

// function update master barang
function UpdateJenis($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $oldid = htmlspecialchars(mysqli_real_escape_string($conn, $data["oldidjenis"]));
    $namajenis = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["namajenis"])));
    $hargajenis = htmlspecialchars(mysqli_real_escape_string($conn, $data["hargajenis"]));
    
    // Check Form Input
    if($hargajenis < 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Estimasi Harga Tidak Boleh Kurang Dari Nol", "error", "$page");
        return false;
        die();
    }

    // Check Form Input
    if($namajenis == "" || empty($namajenis)) {
        $query = "UPDATE masterjenis SET NamaJenis = NULL, HargaJenis = '$hargajenis' WHERE IDJenis = '$oldid'";
    }
    else {
        $query = "UPDATE masterjenis SET NamaJenis = '$namajenis', HargaJenis = '$hargajenis' WHERE IDJenis = '$oldid'";
    }

    // Update user to database
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update user aktivasi

// ---------------------------- //

// function delete jenis barang
function DeleteJenis($data) {

    global $conn;

    $page = htmlspecialchars($data["page"]);
    $id = isset($data["del-idjenis"]) ? htmlspecialchars(mysqli_real_escape_string($conn, $data["del-idjenis"])) : NULL;
    
    $query1 = mysqli_query($conn, "SELECT pluid FROM masterstock WHERE RIGHT(pluid, 4) = '$id'");

    if(mysqli_fetch_assoc($query1)) {

        $GLOBALS['alert'] = array("Gagal!", "PLU Barang ".$id." Tidak Dapat Dihapus, Karena Telah Memiliki Saldo", "error", "$page");
        return false;
        die();
    
    }
    else {
        
        $query2 = mysqli_query($conn, "SELECT plu_id FROM detail_pembelian WHERE RIGHT(plu_id, 4) = '$id'");

        if (mysqli_fetch_assoc($query2)) {

            $GLOBALS['alert'] = array("Gagal!", "PLU Barang ".$id." Tidak Dapat Dihapus, Karena Telah Memiliki History Transaksi", "error", "$page");
            return false;
            die();

        }
        else {
            
            $sql = "DELETE FROM masterjenis WHERE IDJenis = '$id'";
            mysqli_query($conn, $sql);
        }

    }

    return mysqli_affected_rows($conn);

}
// End function delete jenis barang

// ---------------------------- //

// Function insert periode tahunan
function InsertPeriodeTahunan($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));

    // Validasi tahun wajib 4 digit
    if(strlen($tahun) != 4) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat Kesalahan Format Tahun", "error", "$page");
        return false;
        die();
    }

    // Check tahun jika sudah ada
    $query = mysqli_query($conn, "SELECT tahun_periode FROM periodebudget WHERE tahun_periode = '$tahun'");

    if($data = mysqli_fetch_assoc($query)) {
        $GLOBALS['alert'] = array("Gagal!", "Tahun ".$tahun." Sudah Terdaftar", "error", "$page");
        return false;
        die();
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO periodebudget (tahun_periode) VALUES ('$tahun')");

    return mysqli_affected_rows($conn);

}
// End function insert periode tahunan

// ---------------------------- //

// function update periode tahunan
function UpdatePeriodeTahunan($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idtahun"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));

    // Check tahun jika sudah ada
    $query = mysqli_query($conn, "SELECT tahun_periode FROM periodebudget WHERE tahun_periode = '$tahun'");

    if($data = mysqli_fetch_assoc($query)) {
        $GLOBALS['alert'] = array("Gagal!", "Tahun ".$tahun." Sudah Terdaftar", "error", "$page");
        return false;
        die();
    }
    
    // Update user to database
    $query = "UPDATE periodebudget SET tahun_periode = '$tahun' WHERE id_tahun = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update periode tahunan

// ---------------------------- //

// function delete periode tahunan
function DeletePeriodeTahunan($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idtahun"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));

    // Check tahun jika sudah ada yang proses per department
    $query = mysqli_query($conn, "SELECT tahun_periode FROM statusbudget WHERE tahun_periode = '$tahun'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Periode Tahun ".$tahun." Sudah Di Proses Perdepartment", "error", "$page");
        return false;
        die();
    
    }
    else {
        $sql = "DELETE FROM periodebudget WHERE id_tahun = '$id'";
        mysqli_query($conn, $sql);
    }


    return mysqli_affected_rows($conn);

}
// End function delete periode tahunan

// ---------------------------- //

// Function proses budget tahunan per department
function insert_dept_budget($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));

    // Check tahun jika department sudah proses 
    $query = mysqli_query($conn, "SELECT * FROM statusbudget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun'");

    if($data = mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Office ".$data["id_office"]." department ".$data["id_department"]." sudah proses budget periode tahun ".$data["tahun_periode"], "error", "$page");
        return false;
    
    }

    $sql = "SELECT A.*, B.* FROM masterjenis AS A
    INNER JOIN mastercategory AS B ON A.IDBarang = B.IDBarang ORDER BY A.IDBarang ASC";

    $query_brg = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query_brg) > 0) {
        while($row = mysqli_fetch_assoc($query_brg)) {
            $barang = $row["IDBarang"].$row["IDJenis"];

            mysqli_query($conn, "INSERT INTO budget (id_office, id_department, tahun_periode, plu_id) VALUES ('$office', '$dept', '$tahun', '$barang')");
            
        }
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO statusbudget (id_office, id_department, tahun_periode) VALUES ('$office', '$dept', '$tahun')");

    return mysqli_affected_rows($conn);

}
// End function proses budget tahunan per department

// ---------------------------- //

// Function import csv
function ImportBudget($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["department"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["tahun"]));

    $name  = $_FILES["filecsv"]["name"];
    $size  = $_FILES["filecsv"]["size"];
    $error = $_FILES["filecsv"]["error"];
    $tmp   = $_FILES["filecsv"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        $stored = [];
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            $pluid = isset($line[0]) ? $line[0] : NULL;
            $qty = isset($line[2]) ? $line[2] : NULL;

            //skip current row if it is a duplicate
            if (in_array($pluid, $stored)) {continue;}

            $idbarang = substr($pluid, 0, -4);
            $idjenis = substr($pluid, 6);

            $query = mysqli_query($conn, "SELECT IDBarang, IDJenis FROM masterjenis WHERE IDBarang = '$idbarang' AND IDJenis = '$idjenis'");
            $data = mysqli_fetch_assoc($query);

            $plu = $data['IDBarang'].$data['IDJenis'];

            if ($pluid !== $plu) {
                $GLOBALS['alert'] = array("Gagal!", "Kode Barang ".$pluid." Tidak Terdaftar di Master Barang", "error", "$page");
                return false;
            }

            $querytahun_budget = mysqli_query($conn, "SELECT status_budget FROM statusbudget WHERE tahun_periode = '$tahun' AND id_office = '$office' AND id_department = '$dept'");

            if (!mysqli_fetch_assoc($querytahun_budget)) {
                $GLOBALS['alert'] = array("Gagal!", "Belum proses budget per department periode tahun ".$tahun, "error", "$page");
                return false;
            }

            $queryST = mysqli_query($conn, "SELECT status_budget FROM statusbudget WHERE tahun_periode = '$tahun' AND id_office = '$office' AND id_department = '$dept' AND status_budget = 'Y'");

            if (mysqli_fetch_assoc($queryST)) {
                $GLOBALS['alert'] = array("Gagal!", "Periode Tahun ".$tahun." sudah final data tidak bisa ditambahkan kedalam draft", "error", "$page");
                return false;
            }

            mysqli_query($conn, "UPDATE budget SET stock_budget = $qty WHERE tahun_periode = '$tahun' AND id_office = '$office' AND id_department = '$dept' AND plu_id = '$pluid'");

            //remember inserted value
            $stored[] = $pluid;
            
        }

        fclose($file);

    }
    
    return mysqli_affected_rows($conn);

}
// End function import csv

// ---------------------------- //

// function reset budget
function ResetBudget($data) {

    global $conn;

    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept"]));

    $result = "UPDATE budget SET stock_budget = 0 WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun'";
    
    $query = mysqli_query($conn, $result);

    return mysqli_affected_rows($conn);

}
// End function reset budget

// ---------------------------- //

// function delete budget
function delete_dept_budget($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page"]));
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idsb"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));

    // Check id statusbudget
    $query = mysqli_query($conn, "SELECT * FROM statusbudget WHERE tahun_periode = '$tahun' AND id_office = '$office' AND id_department = '$dept' AND status_budget = 'Y'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Budget tahun ".$tahun." sudah final tidak dapat di hapus", "error", "$page");
        return false;
    
    }

    mysqli_query($conn, "DELETE FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun'");
    mysqli_query($conn, "DELETE FROM statusbudget WHERE id_sb = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete budget per dept

// ---------------------------- //

// function Proses Budget
function ProsesBudget($data) {

    global $conn;

    // Input data post
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));
    
    // Update periode budget to database
    $queryupdatesb = "UPDATE statusbudget SET status_budget = 'Y' WHERE tahun_periode = '$tahun' AND id_office = '$office' AND id_department = '$dept'";
    mysqli_query($conn, $queryupdatesb);

    $querybulan = mysqli_query($conn, "SELECT id_bulan FROM bulan");
    while ($databulan = mysqli_fetch_assoc($querybulan)) {
        
        $data = $databulan['id_bulan'];
        // Insert data to database
        mysqli_query($conn, "INSERT INTO tutupperiode (id_office, id_department, tahun_periode, id_bulan) VALUES ('$office', '$dept', '$tahun', '$data')");

    }

    return mysqli_affected_rows($conn);

}
// End function update periode budget

// ---------------------------- //

// function insert pembelian from budget
function GeneratePP($data) {

    global $conn;

    // Input data post
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref"]));
    $cat = htmlspecialchars(mysqli_real_escape_string($conn, $data["catbarang"]));
    $officeid = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $deptid = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept"]));
    $userid = htmlspecialchars(mysqli_real_escape_string($conn, $data["user"]));

    // Insert pembelian to database
    mysqli_query($conn, "INSERT INTO pembelian (noref, id_category, id_office, id_department, user) VALUES ('$noref', '$cat', '$officeid', '$deptid', '$userid')");

    return mysqli_affected_rows($conn);
}   
// End function Geberate PPB

// ---------------------------- //

// function insert pembelian from budget
function InsertPP($data) {

    global $conn;

    // Input data post
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref"]));
    $officefrom = htmlspecialchars(mysqli_real_escape_string($conn, $data["officedata"]));
    $deptfrom = htmlspecialchars(mysqli_real_escape_string($conn, $data["deptdata"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["userdata"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahundata"]));
    $bulan = htmlspecialchars(mysqli_real_escape_string($conn, $data["bulandata"]));
    $pluid = htmlspecialchars(mysqli_real_escape_string($conn, $data["barangdata"]));
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merkdata"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipedata"])));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qtydata"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["ketdata"]))));
    $offdep = $officefrom.$deptfrom;

    if ($qty <= 0) {
        $GLOBALS['err_minqty'] = "<strong>Gagal!</strong> Qty tidak boleh kurang atau sama dengan 0";
        return false;
    }

    $querystock = mysqli_query($conn, "SELECT id_budget, stock_budget FROM budget WHERE id_office = '$officefrom' AND id_department = '$deptfrom' AND tahun_periode = '$tahun' AND id_bulan = '$bulan' AND plu_id = '$pluid'");
    $datastock = mysqli_fetch_assoc($querystock);

    if (!$datastock || empty($datastock)) {
        $GLOBALS['err_notplu'] = "<strong>Gagal!</strong> Tidak ada budget atas PLU ".$pluid;
        return false;
    }
    else {
        if ($qty > $datastock["stock_budget"]) {
            $GLOBALS['err_plusstock'] = "<strong>Gagal!</strong> Qty PLU ".$pluid." melebihi dari stock budget";
            return false;
        }
        else {
            $id_bgt = $datastock["id_budget"];
            $stock_bgt = $datastock['stock_budget'];
        }
    }

    $query_bout = mysqli_query($conn, "SELECT id_budget, qty FROM detail_pembelian WHERE id_budget = '$id_bgt'");
    $rowCount = mysqli_num_rows($query_bout);
    
    if($rowCount > 0){
        $nol = 0;
        while($row = mysqli_fetch_assoc($query_bout)){ 
            $totalqty = $row["qty"];
            $saldo = $nol+=$totalqty;
        }
        $sisa = ($stock_bgt - $saldo);
        if ($qty > $sisa) {
            $GLOBALS['err_minstock'] = "<strong>Gagal!</strong> Qty PLU ".$pluid." kurang dari stock budget terpakai";
            return false;
        }
    }

    $querysameid = mysqli_query($conn, "SELECT bulan.id_bulan, bulan.nama_bulan, detail_pembelian.plu_id, budget.id_budget FROM detail_pembelian
    INNER JOIN budget ON detail_pembelian.id_budget = budget.id_budget
    INNER JOIN bulan ON budget.id_bulan = bulan.id_bulan 
    WHERE detail_pembelian.id_budget = '$id_bgt' AND detail_pembelian.proses = 'N'");
    $dataid = mysqli_fetch_assoc($querysameid);

    $id = $dataid['plu_id'];
    $idbulan = $dataid['nama_bulan'];
    
    if ($dataid) {
        $GLOBALS['err_duplicateid'] = "<strong>Gagal!</strong> PLU ".$id." budget bulan ".$idbulan." sudah ditambahkan";
        return false;
    }

    $queryplu = mysqli_query($conn, "SELECT plu_id FROM detail_pembelian WHERE noref = '$noref' AND plu_id = '$pluid'");
    
    if ($dataplu = mysqli_fetch_assoc($queryplu)) {
        $GLOBALS['err_duplicateplu'] = "<strong>Gagal!</strong> menambahkan PLU ".$dataplu["plu_id"]." sudah ada";
        return false;
    }

    // Insert pembelian to database
    mysqli_query($conn, "INSERT INTO detail_pembelian (id_budget, noref, id_offdep, user_pp, plu_id, merk, tipe, qty, keterangan) VALUES ('$id_bgt', '$noref', '$offdep', '$user', '$pluid', '$merk', '$tipe', '$qty', '$ket')");

    return mysqli_affected_rows($conn);
}
// End function update stock budget and insert pembelian

// ---------------------------- //

// function delete noref ppb
function DeletePPNoref($data) {

    global $conn;

    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref"]));

    $result = "DELETE FROM pembelian WHERE noref = '$noref'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete noref ppb

// ---------------------------- //

// function proses PPB
function ProsesPP($data) {

    global $conn;
    global $timezone;

    // Input data post
    $sproses = htmlspecialchars(mysqli_real_escape_string($conn, $data["statusproses"]));
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref"]));
    $officeto = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-to"]));
    $deptto = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-to"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user"]));
    $prosesdate = date("Y-m-d H:i:s");
    $keperluan = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["keperluan"]))));

    // Update outpp dari noref
    $resultoutpp = "UPDATE detail_pembelian SET proses = 'Y' WHERE noref = '$noref'";
    mysqli_query($conn, $resultoutpp);

    $id = 'PPB';
    $dateid = date("d/m/Y");
    
    $ppbno = $id.'/'.$noref.'/'.$dateid;
    // Insert pembelian to database
    $resultppb = "UPDATE pembelian SET ppid = '$ppbno', office_to = '$officeto', department_to = '$deptto', user = '$user', proses_date = '$prosesdate', status_pp = '$sproses', keperluan = '$keperluan' WHERE noref = '$noref'";
    mysqli_query($conn, $resultppb);

    $encppb = encrypt($ppbno);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTPP'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-pp.php?ppid=".$encppb."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function update stock budget and insert pembelian

// ---------------------------- //

// function delete ppb plu
function DeletePP($data) {

    global $conn;

    $id_dpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-dpp"]));

    $result = "DELETE FROM detail_pembelian WHERE id_dpp = '$id_dpp'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete ppb plu

// ---------------------------- //

// function insert sthh
function InsertSTHH($data) {

    global $conn;

    // Input data post
    $page = $data["page_pinjam"];
    $noid = htmlspecialchars(mysqli_real_escape_string($conn, $data["id_pinjam"]));
    $officeid = htmlspecialchars(mysqli_real_escape_string($conn, $data["officeid"]));
    $deptid = htmlspecialchars(mysqli_real_escape_string($conn, $data["deptid"]));
    $headdivisi = htmlspecialchars(substr($data["bag-divisi"], 4));
    $subdivisi = htmlspecialchars(mysqli_real_escape_string($conn, $data["sub-divisi"]));
    $nik = htmlspecialchars(mysqli_real_escape_string($conn, $data["peminjam"]));
    $pic = htmlspecialchars(mysqli_real_escape_string($conn, $data["pic"]));
    $dateout = htmlspecialchars(mysqli_real_escape_string($conn, date("Y-m-d H:i:s")));
    $ket = htmlspecialchars(stripslashes(strtoupper($data["ket"])));

    $hh = isset($data["hhpinjam"]) ? $data["hhpinjam"] : NULL;

    // Memecah string tgl dan waktu
    $tgl = substr($dateout, 0, 10);
    $jam = substr($dateout, 10);

    // Check Data HH / BT NULL
    if(empty($hh) || $hh == '' || $hh == NULL) {

        $GLOBALS['alert'] = array("Gagal!", "Data Handheld / Batre belum ada yang dipilih", "error", "$page");
        return false;

    }

    $sql = mysqli_query($conn, "SELECT pluid FROM sthh WHERE datein IS NULL AND penerima IS NULL AND pengembali IS NULL");

    $rowsarr = array();

    if(mysqli_num_rows($sql) > 0) {
        while ($data = mysqli_fetch_assoc($sql)) {

            $rowsarr[] = $data["pluid"];

        }
    }

    $check_arr = array_intersect($rowsarr, $hh);

    if (!empty($check_arr)) {
        foreach ($check_arr as $val) {
            mysqli_query($conn, "UPDATE sthh SET datein = '$tgl', pengembali = '$nik', penerima = '$pic', ket_terima = '$ket' WHERE pluid = '$val' AND datein IS NULL AND penerima IS NULL AND pengembali IS NULL");
        }
    }

    foreach ($hh as $arrdata)  {
        mysqli_query($conn, "INSERT INTO sthh (no_pinjam, id_office, id_department, pluid, id_divisi, id_sub_divisi, nik, pic, keterangan, dateout, jamkeluar) VALUES ('$noid', '$officeid', '$deptid', '$arrdata', '$headdivisi', '$subdivisi', '$nik', '$pic', '$ket', '$tgl', '$jam')");
    }

    return mysqli_affected_rows($conn);

}   
// End function insert sthh

// ---------------------------- //

// function Update STHH
function UpdateSTHH($data) {

    global $conn;

    // Input data post
    $idsthh = htmlspecialchars(mysqli_real_escape_string($conn, $data["idsthh"]));
    $datein = date("Y-m-d H:i:s");
    $penerima = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper(stripslashes($data["penerima"]))));
    $pengembali = htmlspecialchars(mysqli_real_escape_string($conn, $data["pengembali"]));
    $ket = htmlspecialchars(strtoupper($data["keterangan"]));

    // Update Data STHH Penerima dan jam masuk
    $sql = "UPDATE sthh SET datein = '$datein', pengembali = '$pengembali', penerima = '$penerima', ket_terima = '$ket' WHERE id_sthh = '$idsthh'";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}
// End function update penerima STHH

// ---------------------------- //

// function Update STHH By Check
function UpdateCheckSTHH($data) {

    global $conn;

    // Input data post
    $page = $data["page-updcheck"];
    $idsthh = $data["checkiddata"];
    $datein = date("Y-m-d H:i:s");
    $penerima = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper(stripslashes(isset($data["penerimacheck"]) ? $data["penerimacheck"] : NULL))));
    $pengembali = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["pengembalicheck"]) ? $data["pengembalicheck"] : NULL));
    $ket = htmlspecialchars(strtoupper($data["keterangan"]));

    if($penerima == "" || empty($penerima)) {
        $GLOBALS['alert'] = array("Gagal!", "Data penerima tidak boleh kosong", "error", "$page");
        return false;
    }

    if ($pengembali == "" || empty($pengembali)) {
        $GLOBALS['alert'] = array("Gagal!", "Data pengembali tidak boleh kosong", "error", "$page");
        return false;
    }

    foreach ($idsthh as $arrdata)  {
        // Update Data STHH Penerima dan jam masuk
        $sql = "UPDATE sthh SET datein = '$datein', pengembali = '$pengembali', penerima = '$penerima', ket_terima = '$ket' WHERE id_sthh = '$arrdata'";
        mysqli_query($conn, $sql);
    }

    return mysqli_affected_rows($conn);
}
// End function update penerima STHH

// ---------------------------- //

// function Update STHH
function UpdateReceiveSTHH($data) {

    global $conn;

    // Input data post
    $page = $data["page-updreceive"];
    $idsthh = $data["hhreceive"];
    $idoffice = $data["officeid"];
    $iddept = $data["deptid"];
    $datein = date("Y-m-d H:i:s");
    $penerima = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper(stripslashes(isset($data["pic_receive"]) ? $data["pic_receive"] : NULL))));
    $pengembali = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["peminjam_receive"]) ? $data["peminjam_receive"] : NULL));
    $ket = htmlspecialchars(strtoupper($data["ket_receive"]));

    if($penerima == "" || empty($penerima)) {
        $GLOBALS['alert'] = array("Gagal!", "Data penerima tidak boleh kosong", "error", "$page");
        return false;
    }

    if ($pengembali == "" || empty($pengembali)) {
        $GLOBALS['alert'] = array("Gagal!", "Data pengembali tidak boleh kosong", "error", "$page");
        return false;
    }

    foreach ($idsthh as $arrdata)  {
        // Update Data STHH Penerima dan jam masuk
        $sql = "UPDATE sthh SET datein = '$datein', pengembali = '$pengembali', penerima = '$penerima', ket_terima = '$ket' WHERE pluid = '$arrdata' AND id_office = '$idoffice' AND id_department = '$iddept'";
        mysqli_query($conn, $sql);
    }

    return mysqli_affected_rows($conn);
}
// End function update penerima STHH

// ---------------------------- //

// function Delete STHH By Check
function DeleteCheckSTHH($data) {

    global $conn;

    $idsthh = $data["checkiddata"];

    foreach ($idsthh as $arrdata)  {
        // Update Data STHH Penerima dan jam masuk
        $sql = "DELETE FROM sthh WHERE id_sthh = '$arrdata'";
        mysqli_query($conn, $sql);
    }

    return mysqli_affected_rows($conn);
}
// End function Delete STHH By Check

// ---------------------------- //

// function delete ID STHH
function DeleteSTHH($data) {

    global $conn;

    $idsthh = htmlspecialchars(mysqli_real_escape_string($conn, $data["del-idsthh"]));

    $result = "DELETE FROM sthh WHERE id_sthh = '$idsthh'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete ID STHH

// ---------------------------- //

// function delete pp
function cancelpp($data) {

    global $conn;

    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp"]));
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref"]));

    $sp3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["status-p3at"]));
    $id_p3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-p3at"]));
    $jenis = substr($data["ppid"], 0, 3);

    $query_cekpp = mysqli_query($conn, "SELECT * FROM detail_pembelian WHERE noref = '$noref'");
    $rowCount = mysqli_num_rows($query_cekpp);
    
    if($rowCount > 0){
        while($row = mysqli_fetch_assoc($query_cekpp)){
            $office = substr($row["id_offdep"], 0, 4);
            $dept = substr($row["id_offdep"], 4, 4);
            $tahun = substr($row["tgl_detail_pp"], 0, 4);
            $pluid = $row["plu_id"];
            $qtypp = $row["qty"];

            $query_th = mysqli_query($conn, "SELECT status_budget FROM statusbudget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND status_budget = 'Y'");
        
            if(mysqli_num_rows($query_th) > 0) {
           
                $data_budget = mysqli_fetch_assoc(mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'"));
                $saldobgt = isset($data_budget['use_budget']) ? $data_budget['use_budget'] : 0;
                $qtybudget = $saldobgt-$qtypp;
    
                mysqli_query($conn, "UPDATE budget SET use_budget = '$qtybudget' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
            }
        }
    }

    if (!empty($id_p3at) || $id_p3at != "") {
        if ($jenis == "PPM") {
            mysqli_query($conn, "UPDATE p3at SET status_p3at = '$sp3at', noref_pp = NULL, judul_p3at = NULL WHERE id_p3at = '$id_p3at'");
        }
    }

    mysqli_query($conn, "DELETE FROM detail_pembelian WHERE noref = '$noref'");

    mysqli_query($conn, "DELETE FROM pembelian WHERE id_pembelian = '$idpp'");

    mysqli_query($conn, "DELETE FROM mon_status_pp WHERE mspp_noref = '$noref'");

    return mysqli_affected_rows($conn);
}
// End function delete pp

// ---------------------------- //

// function Update / Approve pp
function approvepp($data) {

    global $conn;

    // Update data post
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idppapprove"]));
    $noref = htmlspecialchars($data["norefapprove"]);
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["spidapprove"]));
    $user = htmlspecialchars($data["userapprove"]);
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql1 = "UPDATE pembelian SET status_pp = '$idsp' WHERE id_pembelian = '$idpp'";

    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$idsp', '$user', '$date')";
    
    mysqli_query($conn, $sql1);
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function Update / Approve pp

// ---------------------------- //

// function Update / Cancel pp
function cancelpps1($data) {

    global $conn;

    // Update data post
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["norefcancel"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["usercancel"]));
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idppcancel"]));
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["spidcancel"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["keterangan"])));
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql = "UPDATE pembelian SET status_pp = '$idsp', reminder = '$ket' WHERE id_pembelian = '$idpp'";
    mysqli_query($conn, $sql);

    // Insert data monitoing status pembelian
    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date, mspp_keterangan) VALUES ('$noref', '$idsp', '$user', '$date', '$ket')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function Update / Cancel pp

// ---------------------------- //

// function Update / approve sebagian pp
function approvehalf($data) {

    global $conn;

    // Update data post
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref-approvehalf"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-approvehalf"]));
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp-approvehalf"]));
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["spid-approvehalf"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["keterangan"])));
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql = "UPDATE pembelian SET status_pp = '$idsp', reminder = '$ket' WHERE id_pembelian = '$idpp'";
    mysqli_query($conn, $sql);

    // Insert data monitoing status pembelian
    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date, mspp_keterangan) VALUES ('$noref', '$idsp', '$user', '$date', '$ket')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function Update / approve sebagian pp

// ---------------------------- //

// function Update / approve semua pp
function approveall($data) {

    global $conn;

    // Update data post
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref-approveall"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-approveall"]));
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp-approveall"]));
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["spid-approveall"]));
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql = "UPDATE pembelian SET status_pp = '$idsp', reminder = NULL WHERE id_pembelian = '$idpp'";
    mysqli_query($conn, $sql);

    // Insert data monitoing status pembelian
    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$idsp', '$user', '$date')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function Update / approve semua pp

// ---------------------------- //

// function cancel pp
function cancelpps3($data) {

    global $conn;

    // Update data post
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp-cancel"]));
    $noref = htmlspecialchars($data["noref-cancel"]);
    $user = htmlspecialchars($data["user-cancel"]);
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["spid-cancel"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["keterangan"])));
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql = "UPDATE pembelian SET status_pp = '$idsp', reminder = '$ket' WHERE id_pembelian = '$idpp'";
    mysqli_query($conn, $sql);

    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date, mspp_keterangan) VALUES ('$noref', '$idsp', '$user', '$date', '$ket')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function cancel pp

// ---------------------------- //

// function input sp
function prosesnosp($data) {

    global $conn;

    // Update data post
    $noref = htmlspecialchars($data["noref-inputspno"]);
    $user = htmlspecialchars($data["user-inputspno"]);
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp-inputspno"]));
    $idsp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idsp-inputspno"]));
    $date = date("Y-m-d H:i:s");

    // Update data status pembelian
    $sql = "UPDATE pembelian SET status_pp = '$idsp' WHERE id_pembelian = '$idpp'";
    mysqli_query($conn, $sql);
    
    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$idsp', '$user', '$date')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}
// End function input sp

// ---------------------------- //

// function revisi qty pp
function UpdateRevisiPP($data) {

    global $conn;

    $page = $data["page"];
    $ppid = mysqli_real_escape_string($conn, $data["idpp"]);
    $offdep = mysqli_real_escape_string($conn, $data["idoffdep"]);
    $iddpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddpp"]));
    $pluid = mysqli_real_escape_string($conn, $data["pluid"]);
    $tgl = mysqli_real_escape_string($conn, $data["tglpp"]);
    $noref = mysqli_real_escape_string($conn, $data["noref"]);
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merk"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipe"])));
    $qtyold = htmlspecialchars(mysqli_real_escape_string($conn, $data["qtyold"]));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qty"]));
    $cost = htmlspecialchars($data["hargajenis"]);
    $harga = ($cost * $qty);
    $jenis = htmlspecialchars(mysqli_real_escape_string($conn, $data["jenispp"]));
    $office = substr($offdep, 0, 4);
    $dept = substr($offdep, 4, 4);
    $tahun = substr($tgl, 0, 4);

    if ($qty <= 0) {
        $GLOBALS['alert'] = array("Gagal!", "Qty tidak boleh kurang atau sama dengan nol", "error", "$page");
        return false;
    }

    if ($jenis == "PPG") {

        $querystock = mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
        $datastock = mysqli_fetch_assoc($querystock);
        $stock_bgt = isset($datastock['use_budget']) ? $datastock['use_budget'] : 0;

        $qtyupdate = $stock_bgt - $qtyold + $qty;
        
        mysqli_query($conn, "UPDATE budget SET use_budget = '$qtyupdate' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
    
    }

    mysqli_query($conn, "UPDATE detail_pembelian SET merk = '$merk', tipe = '$tipe', qty = '$qty', harga_pp = '$harga' WHERE id_dpp = '$iddpp'");

    return mysqli_affected_rows($conn);

}
// End function revisi qty

// ---------------------------- //

// function insert data pp from detail_pembelian to masterstock and update status pembelian
function prosesterimapp($data) {

    global $conn;

    // Input data post
    $idpp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpp"]));
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["idnoref"]));
    $spp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idspp"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user"]));
    $date = date("Y-m-d H:i:s");
    // $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $data["tgl"]));
    $status = "N";
    $offdep = $office.$dept;

    $id = autoid("1", "5", "id_penerimaan", "penerimaan_pembelian");

    if(strlen($id) == 5) {

        // kode PP
        $code = "P";

        // menmbahkan kode PP dengan number baru pada func auto id
        $newid = $code.$id;

    }
    else {
        $newid = $id;
    }

    // Insert pembelian to database
    mysqli_query($conn, "UPDATE pembelian SET status_pp = '$spp', reminder = NULL WHERE id_pembelian = '$idpp'");

    mysqli_query($conn, "INSERT INTO penerimaan_pembelian (id_penerimaan, pp_id_pembelian, office_penerimaan, dept_penerimaan) VALUES ('$newid', '$idpp', '$office', '$dept')");

    mysqli_query($conn, "INSERT INTO barang_khusus (noref_khusus, offdep_khusus, date_khusus) VALUES ('$newid', '$offdep', NULL)");
    
    $query_pp = mysqli_query($conn, "SELECT * FROM detail_pembelian WHERE noref = '$noref'");

    if(mysqli_num_rows($query_pp) > 0) {
        while($data_PP = mysqli_fetch_assoc($query_pp)) {
            $id = $data_PP["id_dpp"];
            $pluid = $data_PP["plu_id"];
            $merk = $data_PP["merk"];
            $tipe = $data_PP["tipe"];
            $qty = $data_PP["qty"];

            mysqli_query($conn, "INSERT INTO detail_penerimaan_pembelian (id_penerimaan_pp, id_dpp_penerimaan, tgl_penerimaan, pluid_penerimaan, merk_penerimaan, tipe_penerimaan, qty_pembelian, status_penerimaan) VALUES ('$newid', '$id', NULL, '$pluid', '$merk', '$tipe', '$qty', '$status')");

        }
    }

    $sql2 = "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$spp', '$user', '$date')";
    mysqli_query($conn, $sql2);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert data master barang dan update stock barang
function InsertBarangPembelian($data) {

    global $conn;

    // Input data post
    $page = $data["page-brgpp"];
    $user = $data["user-brgpp"];
    $ref = $data["modifref-brgpp"];
    $ppno = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["btbno"]) ? $data["btbno"] : NULL));
    $kondisi = htmlspecialchars(mysqli_real_escape_string($conn, $data["kondisi"]));
    $date = date("Y-m-d H:i:s");
    $temp_pluid = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["datapluid"]) ? $data["datapluid"] : NULL));
    $pluid = substr($temp_pluid, -10);
    
    if (!isset($_POST["sn_btb_pp"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada data barang yang dipilih", "error", "$page");
        return false;
    }

    if(count(array_unique($_POST["sn_btb_pp"], SORT_REGULAR)) < count($_POST["sn_btb_pp"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat penginputan serial number duplicate", "error", "$page");
        return false;
    }

    $queryjumlah = mysqli_query($conn, "SELECT COUNT(noref_asset) AS total FROM barang_assets WHERE noref_asset = '$ppno'");
    $datajumlah = mysqli_fetch_assoc($queryjumlah);
    $jumlah = $datajumlah["total"];

    $queryqty = mysqli_query($conn, "SELECT SUM(qty_penerimaan) AS qty FROM detail_penerimaan_pembelian WHERE id_penerimaan_pp = '$ppno'");
    $dataqty = mysqli_fetch_assoc($queryqty);
    $qty = $dataqty["qty"];

    if ($jumlah >= $qty) {
        $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." melebihi jumlah pembelian", "error", "$page");
        return false;
    }

    $final = array();

    for($countcek = 0; $countcek < count($_POST["offdep_btb_pp"]); $countcek++) {

        $arrcek = array(
            $office = substr($data["offdep_btb_pp"][$countcek], 0, 4),
            $dept = substr($data["offdep_btb_pp"][$countcek], 4, 4),
            $pluid,
            $merkcek = $data["merk_btb_pp"][$countcek],
            $tipecek = $data["tipe_btb_pp"][$countcek],
            $sncek = $data["sn_btb_pp"][$countcek],
            $atcek = $data["at_btb_pp"][$countcek],
            $lambung = $data["nomor_btb_pp"][$countcek]
        );

        $query_sn_cek = mysqli_query($conn, "SELECT sn_barang FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND pluid = '$pluid' AND sn_barang = '$sncek'");

        if(mysqli_num_rows($query_sn_cek) > 0 ) {
            
            $GLOBALS['alert'] = array("Gagal!", "Serial Number Barang ".$sncek." telah terdaftar di masterbarang inventaris", "error", "$page");
            return false;
        }
        
        if (strlen($atcek) != 10) { 
            $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan penginputan data aktiva barang ".$atcek, "error", "$page");
            return false;
        }

        $query_dat = mysqli_query($conn, "SELECT no_dat, qty_dat, status_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$atcek'");
        $data_dat = mysqli_fetch_assoc($query_dat);

        if (!$data_dat) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$atcek." Belum Terdaftar di Master Kepemilikan DAT", "error", "$page");
            return false;
        }

        if(array_count_values($data["at_btb_pp"])[$atcek] != $data_dat["qty_dat"]){
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$atcek." Melebihi Qty Terdaftar DAT", "error", "$page");
            return false;
        }

        if ($data_dat["status_dat"] === "N") {
            $GLOBALS['alert'] = array("Gagal!", "Status Kepemilikan DAT Nomor Aktiva ".$atcek." Sudah Tidak Aktif / Pernah Ada History Pemusnahan", "error", "$page");
            return false;
        }

        $final[] = $arrcek;
    }

    foreach ($final as $i => $v) {

        $rows = $v;
        
        mysqli_query($conn, "INSERT INTO barang_assets (noref_asset, dat_asset, ba_id_office, ba_id_department, pluid, ba_merk, ba_tipe, sn_barang, no_at, no_lambung, kondisi, user_asset, referensi_asset, modified_asset) VALUES ('$ppno', '".$rows[0].$rows[1]."', '$rows[0]', '$rows[1]', '$rows[2]', '$rows[3]', '$rows[4]', '$rows[5]', '$rows[6]', '$rows[7]', '$kondisi', '$user', '$ref', '$date')");
    
    }

    
    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update master barang
function UpdateBarangAssets($data) {

    global $conn;

    // Input data post
    $page = $data["page-msbarang"];
    $user = $data["user-msbarang"];
    $ref = $data["modifref-msbarang"];
    $id = $data["id-msbarang"];
    $office = $data["office-msbarang"];
    $dept = $data["dept-msbarang"];
    $pluid = $data["barang-msbarang"];
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, $data["merk-msbarang"]));
    $type = htmlspecialchars(mysqli_real_escape_string($conn, $data["type-msbarang"]));
    $sn = htmlspecialchars(mysqli_real_escape_string($conn, $data["sn-msbarang"]));
    $dat = htmlspecialchars(mysqli_real_escape_string($conn, $data["dat-msbarang"]));
    $no = htmlspecialchars(mysqli_real_escape_string($conn, $data["no-msbarang"]));
    $posisi = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["posisi-msbarang"])));
    $date = date("Y-m-d H:i:s");
    $kondisi = $data["kondisi-msbarang"];

    if (strlen($dat) != 10) {
        $GLOBALS['alert'] = array("Gagal!", "Nomor aktiva tidak boleh lebih atau kurang dari 10 digit", "error", "$page");
        return false;
    }

    $query_dat = mysqli_query($conn, "SELECT no_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$dat'");

    $data_dat = mysqli_fetch_assoc($query_dat);

    if (!$data_dat) {
        $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$dat." Belum Terdaftar di Master Kepemilikan DAT", "error", "$page");
        return false;
    }

    mysqli_query($conn, "UPDATE barang_assets SET ba_merk = '$merk', ba_tipe = '$type', sn_barang = '$sn', no_at = '$dat', no_lambung = '$no', posisi = '$posisi', kondisi = '$kondisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE id_ba = '$id'");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function Update Barang By Check
function UpdateCheckBarang($data) {

    global $conn;

    // Input data post
    $page = $data["page-chkbarang"];
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-chkbarang"]));
    $ref = htmlspecialchars(mysqli_real_escape_string($conn, $data["modifref-chkbarang"]));
    $date = date("Y-m-d H:i:s");
    
    if (!isset($data["sn_edit_check"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada data barang yang dipilih", "error", "$page");
        return false;
    }

    if(count(array_unique($data["sn_edit_check"], SORT_REGULAR)) < count($data["sn_edit_check"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat penginputan serial number duplicate", "error", "$page");
        return false;
    }

    $result = array();
    for($countarr = 0; $countarr < count($data["sn_edit_check"]); $countarr++) {

        $arrcek = array(
            $data["id_edit_check"][$countarr],
            $snold = $data["snold_edit_check"][$countarr],
            $office = substr($data["offdep_edit_check"][$countarr], 0, 4),
            $dept = substr($data["offdep_edit_check"][$countarr], 4, 4),
            $pluid = substr($data["offdep_edit_check"][$countarr], 8, 10),
            strtoupper($data["merk_edit_check"][$countarr]),
            strtoupper($data["tipe_edit_check"][$countarr]),
            $sn = strtoupper($data["sn_edit_check"][$countarr]),
            $at = strtoupper($data["at_edit_check"][$countarr]),
            strtoupper($data["no_edit_check"][$countarr]),
            $data["kondisi_edit_check"][$countarr],
            strtoupper($data["posisi_edit_check"][$countarr])
        );

        if ($sn !== $snold) {
            $query_sn_cek = mysqli_query($conn, "SELECT sn_barang FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND pluid = '$pluid' AND sn_barang = '$sn'");

            if(mysqli_num_rows($query_sn_cek) > 0 ) {
                
                $GLOBALS['alert'] = array("Gagal!", "Serial Number Barang ".$sn." telah terdaftar di masterbarang inventaris", "error", "$page");
                return false;
            }
        }
        
        if (strlen($at) != 10) { 
            $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan penginputan data aktiva barang ".$at, "error", "$page");
            return false;
        }

        $query_dat = mysqli_query($conn, "SELECT no_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$at'");

        if (mysqli_num_rows($query_dat) === 0 ) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$at." Belum Terdaftar di Master Kepemilikan DAT", "error", "$page");
            return false;
        }

        $result[] = $arrcek;
    }
    
    foreach ($result as $rows) {
    
        mysqli_query($conn, "UPDATE barang_assets SET ba_merk = '$rows[5]', ba_tipe = '$rows[6]', sn_barang = '$rows[7]', no_at = '$rows[8]', no_lambung = '$rows[9]', kondisi = '$rows[10]', posisi = '$rows[11]', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE id_ba = '$rows[0]'");
    }

    return mysqli_affected_rows($conn);
}
// End function

// function Delete Barang By Check
function DeleteCheckBarang($data) {

    global $conn;

    $id = $data["id_delete_check"];

    for($countarr = 0; $countarr < count($id); $countarr++) {

        $id_barang = $id[$countarr];
        
        $query_check = mysqli_query($conn, "SELECT noref_asset FROM barang_assets WHERE id_ba = '$id_barang'");
        $data_check = mysqli_fetch_assoc($query_check);
        $noref = $data_check["noref_asset"];
        
        mysqli_query($conn, "DELETE FROM barang_assets WHERE id_ba = '$id_barang'");

        if(substr($noref, 0, 1) == "K"){

            $query = mysqli_query($conn, "SELECT COUNT(noref_asset) AS total_ref FROM barang_assets WHERE noref_asset = '$noref'");
            $data = mysqli_fetch_assoc($query);
    
            if ($data["total_ref"] == 0) {
    
                mysqli_query($conn, "DELETE FROM barang_khusus WHERE noref_khusus = '$noref'");
            
            }
        
        }

    }

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function delete master barang
function DeleteBarangAssets($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["del-page"]);
    $id_astbarang = mysqli_real_escape_string($conn, $data["del-barang"]);
    $office = mysqli_real_escape_string($conn, $data["del-office"]);
    $dept = mysqli_real_escape_string($conn, $data["del-dept"]);
    $noref = mysqli_real_escape_string($conn, $data["del-noref"]);
    $pluid = mysqli_real_escape_string($conn, $data["del-pluid"]);
    $sn = mysqli_real_escape_string($conn, $data["del-noseri"]);
    $dat = mysqli_real_escape_string($conn, $data["del-nodat"]);

    $query_asset = mysqli_query($conn, "SELECT status_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$dat'");
    $data_asset = mysqli_fetch_assoc($query_asset);

    if(mysqli_num_rows($query_asset) === 0){
        $GLOBALS['alert'] = array("Gagal!", "Kepemilikan DAT Nomor Aktiva ".$dat." Tidak Terdaftar", "error", "$page");
        return false;
    }

    if ($data_asset["status_dat"] == "Y") {
        $GLOBALS['alert'] = array("Gagal!", "Status Kepemilikan DAT Nomor Aktiva ".$dat." Masih Aktif", "error", "$page");
        return false;
    }

    if(substr($noref,0 ,1) == "K"){

        $query = mysqli_query($conn, "SELECT COUNT(noref_asset) AS total FROM barang_assets WHERE noref_asset = '$noref'");
        $data = mysqli_fetch_assoc($query);

        if ($data["total"] == 1) {

            mysqli_query($conn, "DELETE FROM barang_khusus WHERE noref_khusus = '$noref'");
        
        }
    
    }

    mysqli_query($conn, "DELETE FROM barang_assets WHERE id_ba = '$id_astbarang'");

    return mysqli_affected_rows($conn);

}
// End function delete master barang

// ---------------------------- //

// function update data penerimaan pembelian
function UpdateBarangStock($data) {

    global $conn;

    // Input data post
    $page = $data["page"];
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, $data["norefdata"]));
    $id_pd = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpdata"]));
    $idspp = htmlspecialchars(mysqli_real_escape_string($conn, $data["idsppdata"]));
    $ppid = mysqli_real_escape_string($conn, $data["ppiddata"]);
    $pluid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluiddata"]));
    $qty_terima = htmlspecialchars(mysqli_real_escape_string($conn, $data["qtyterima"]));
    $qty_pp = $data["qtypp"];
    $tgl = htmlspecialchars($data["tglterima"]);
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["userdata"]));
    $btb = htmlspecialchars(mysqli_real_escape_string($conn, $data["btbdata"]));
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merkdata"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipedata"])));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["katerangan"])));
    
    if ($qty_terima <= 0) {
        $GLOBALS['alert'] = array("Gagal!", "Qty tidak boleh kurang atau sama dengan nol", "error", "$page");
        return false;
    }

    if ($qty_terima > $qty_pp) {
        $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." melebihi jumlah qty PP", "error", "$page");
        return false;
    }
    
    $sql_update = "UPDATE detail_penerimaan_pembelian SET tgl_penerimaan = '$tgl', user_penerima = '$user', no_btb = '$btb', merk_penerimaan = '$merk', tipe_penerimaan = '$tipe', qty_penerimaan = '$qty_terima', keterangan_penerimaan = '$ket' WHERE id_penerimaan_detail = '$id_pd'";
    mysqli_query($conn, $sql_update);
    
    $query_qty = mysqli_query($conn, "SELECT SUM(qty_penerimaan) AS total_terima FROM detail_penerimaan_pembelian WHERE id_penerimaan_pp = '$ppid'");
    $data_qty = mysqli_fetch_assoc($query_qty);
    $qty_baru = $data_qty["total_terima"];

    if($qty_baru > 0) {
        mysqli_query($conn, "UPDATE pembelian SET status_pp = '$idspp' WHERE noref = '$noref'");
    }

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update data penerimaan pembelian
function UpdateBarangStockSecond($data) {

    global $conn;

    // Input data post
    $page = $data["page"];
    $id_pd = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpdata"]));
    $pluid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluiddata"]));
    $tgl = htmlspecialchars($data["tgldata"]);
    $qty_second = htmlspecialchars(mysqli_real_escape_string($conn, $data["qtyterima"]));
    $qty_update = htmlspecialchars(mysqli_real_escape_string($conn, $data["qtyterimabaru"]));
    $qty_pp = $data["qtypp"];
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["userdata"]));
    $btb = htmlspecialchars(mysqli_real_escape_string($conn, $data["btbdata"]));
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merkdata"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipedata"])));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["katerangan"])));

    $qty_check = $qty_second + $qty_update;

    $qty_terima = isset($qty_check) ? $qty_check : 0;
    
    if ($qty_update <= 0) {
        $GLOBALS['alert'] = array("Gagal!", "Qty tidak boleh kurang atau sama dengan nol", "error", "$page");
        return false;
    }

    if ($qty_terima > $qty_pp) {
        $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." melebihi jumlah qty PP", "error", "$page");
        return false;
    }

    $sql_update = "UPDATE detail_penerimaan_pembelian SET tgl_penerimaan = '$tgl', user_penerima = '$user', no_btb = '$btb', merk_penerimaan = '$merk', tipe_penerimaan = '$tipe', qty_penerimaan = '$qty_terima', keterangan_penerimaan = '$ket' WHERE id_penerimaan_detail = '$id_pd'";
    mysqli_query($conn, $sql_update);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete master barang
function DeleteBarangStock($data) {

    global $conn;

    $id_pembelian = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpembelian"]));
    $id_pdetail = htmlspecialchars(mysqli_real_escape_string($conn, $data["idpdetail"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept"]));
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluid"]));
    $spp = htmlspecialchars(mysqli_real_escape_string($conn, $data["sppid"]));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qty"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["sppid"]));
    
    mysqli_query($conn, "DELETE FROM detail_penerimaan_pembelian WHERE id_penerimaan_detail = '$id_pdetail'");

    $query_pp = mysqli_query($conn, "SELECT id_penerimaan FROM penerimaan_pembelian
    WHERE pp_id_pembelian = '$id_pembelian'");
    $data_pp = mysqli_fetch_assoc($query_pp);

    $idpenerimaan = $data_pp["id_penerimaan"];

    $query_dpp = mysqli_query($conn, "SELECT COUNT(id_penerimaan_pp) AS jumlah FROM detail_penerimaan_pembelian
    WHERE id_penerimaan_pp = '$idpenerimaan'");
    $data_dpp = mysqli_fetch_assoc($query_dpp);
    
    if($data_dpp["jumlah"] <= 0) {
        mysqli_query($conn, "UPDATE pembelian SET status_pp = '$status' WHERE id_pembelian = '$id_pembelian'");
    }

    return mysqli_affected_rows($conn);

}
// End function delete master barang

// ---------------------------- //

// function update status pembelian and masterstock
function ProsesBarangStock($data) {

    global $conn;

    // Input data post
    $page_success = $_POST["pagesuccess"];
    $sp3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["sp3at"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["userproses"]));
    $spid10 = htmlspecialchars(mysqli_real_escape_string($conn, $data["spid-10"]));
    $spid11 = htmlspecialchars(mysqli_real_escape_string($conn, $data["spid-11"]));
    $ppid = htmlspecialchars(mysqli_real_escape_string($conn, $data["ppid"]));
    $ppbid = htmlspecialchars(mysqli_real_escape_string($conn, $data["ppbid"]));
    $noref = htmlspecialchars(mysqli_real_escape_string($conn, substr($data["ppbid"],4 ,5)));
    $ket = htmlspecialchars(strtoupper($data["ket-ppid"]));
    $tgl = htmlspecialchars($data["tgl-ppid"]);
    $date = date("Y-m-d H:i:s");

    $query_selisih = mysqli_query($conn, "SELECT SUM(qty_pembelian) AS total_pp, SUM(qty_penerimaan) AS total_terima FROM detail_penerimaan_pembelian WHERE id_penerimaan_pp = '$ppid'");
    $data_selisih = mysqli_fetch_assoc($query_selisih);
    $qty_pp = $data_selisih["total_pp"];
    $qty_terima = $data_selisih["total_terima"];

    if ($qty_terima != $qty_pp) {
        mysqli_query($conn, "UPDATE pembelian SET status_pp = '$spid10' WHERE ppid = '$ppbid'");
    }
    else {
        mysqli_query($conn, "UPDATE pembelian SET status_pp = '$spid11' WHERE ppid = '$ppbid'");
        
        mysqli_query($conn, "UPDATE penerimaan_pembelian SET date_penerimaan = '$tgl', user_proses = '$user', ket_penerimaan = '$ket' WHERE id_penerimaan = '$ppid'");
    
        mysqli_query($conn, "UPDATE barang_khusus SET date_khusus = '$tgl', user_khusus = '$user', ket_khusus = '$ket' WHERE noref_khusus = '$ppid'");

        $query_p3at = mysqli_query($conn, "SELECT noref_pp FROM p3at WHERE noref_pp = '$p3at'");
    
        if ($query_p3at) {
            mysqli_query($conn, "UPDATE p3at SET status_p3at = '$sp3at' WHERE noref_pp = '$ppbid'");
        }

    }

    $query_pp = mysqli_query($conn, "SELECT office_penerimaan, dept_penerimaan FROM penerimaan_pembelian WHERE id_penerimaan = '$ppid'");
    $data_pp = mysqli_fetch_assoc($query_pp);
    $office = $data_pp["office_penerimaan"];
    $dept = $data_pp["dept_penerimaan"];

    $query_mutasi = mysqli_query($conn, "SELECT * FROM detail_penerimaan_pembelian WHERE id_penerimaan_pp = '$ppid' AND status_penerimaan = 'N' ");
    
    $code = "I";

    if(mysqli_num_rows($query_mutasi) > 0) {
        while($data_mutasi = mysqli_fetch_assoc($query_mutasi)) {
            $qty = $data_mutasi["qty_penerimaan"];
            $qty_pp_temp = $data_mutasi["qty_pembelian"];

            if ($qty == $qty_pp_temp) {
                $id_d = $data_mutasi["id_penerimaan_detail"];
                $id = $code.autonum(5, 'no_btb_dpd', 'btb_dpd');
                // $tgl = substr($data_mutasi["tgl_penerimaan"], 0, 10);
                $user = $data_mutasi["user_penerima"];
                $pluid = $data_mutasi["pluid_penerimaan"];
                $ket = $data_mutasi["keterangan_penerimaan"];

                $sql_saldo = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'";
                $query_saldo = mysqli_query($conn, $sql_saldo);

                if ($data_saldo = mysqli_fetch_assoc($query_saldo)) {
                
                    $saldo_before = $data_saldo["saldo_akhir"];
                    $saldo = ($saldo_before + $qty);
                    // Update Stock from pembelian to database
                    mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'");
                
                }
                else {
                    
                    mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid, saldo_akhir) VALUES ('$office', '$dept', '$pluid', '$qty')");
                
                }
                // Update data to database
                mysqli_query($conn, "UPDATE detail_penerimaan_pembelian SET status_penerimaan = 'Y' WHERE id_penerimaan_detail = '$id_d'");

                // Insert data to database
                mysqli_query($conn, "INSERT INTO btb_dpd (no_btb_dpd, office_btb_dpd, dept_btb_dpd, tgl_btb_dpd, pluid_btb_dpd, pic_btb_dpd, penerima_btb_dpd, hitung_btb_dpd, qty_awal_btb_dpd, qty_akhir_btb_dpd, ket_btb_dpd) VALUES ('$id', '$office', '$dept', '$tgl', '$pluid', '$user', '$user', '+', '$saldo_before', '$qty', '$ket')");
        
                // Insert data to database
                mysqli_query($conn, "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$spid11', '$user', '$date')");

            }
        }
    }

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    $_SESSION["ALERT"] = array("Success!", "BTB Nomor ".$ppid." Berhasil Update Stock Barang Penerimaan Pembelian", "success", "$page_success");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert data master barang dan update stock barang
function InsertBarangKhusus($data) {

    global $conn;

    // Input data post
    $page = $data["page-baru"];
    $ref = $data["modifref-baru"];
    $office = htmlspecialchars($data["office-baru"]);
    $dept = htmlspecialchars($data["dept-baru"]);
    $user = htmlspecialchars($data["user-baru"]);
    $tgl = date("Y-m-d H:i:s");
    $offdep = $office.$dept;

    $code = "K";
    $id = autonum(5, 'noref_khusus', 'barang_khusus');
    $newid = $code.$id;

    if (!isset($data["sn_barang_khusus"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada data barang yang dipilih", "error", "$page");
        return false;
    }

    if(count(array_unique($data["sn_barang_khusus"], SORT_REGULAR)) < count($data["sn_barang_khusus"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat penginputan serial number duplicate", "error", "$page");
        return false;
    }

    $final = array();

    for($countcek = 0; $countcek < count($data["desc_barang_khusus"]); $countcek++) {

        $arrcek = array(
            $pluid = substr($data["desc_barang_khusus"][$countcek], 0, 10),
            htmlspecialchars(strtoupper($data["merk_barang_khusus"][$countcek])),
            htmlspecialchars(strtoupper($data["tipe_barang_khusus"][$countcek])),
            $sn = htmlspecialchars(strtoupper($data["sn_barang_khusus"][$countcek])),
            $aktiva = htmlspecialchars(strtoupper($data["at_barang_khusus"][$countcek])),
            htmlspecialchars(strtoupper($data["no_barang_khusus"][$countcek])),
            $data["kondisi_barang_khusus"][$countcek]
        );

        if (strlen($aktiva) != 10) { 
            $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan penginputan data aktiva barang ".$aktiva, "error", "$page");
            return false;
        }
        
        $query_dat = mysqli_query($conn, "SELECT no_dat, status_dat, qty_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$aktiva'");
        $data_dat = mysqli_fetch_assoc($query_dat);
        
        $status_dat = isset($data_dat["status_dat"]) ? $data_dat["status_dat"] : NULL;
        $qty_dat = isset($data_dat["qty_dat"]) ? $data_dat["qty_dat"] : NULL;

        if (mysqli_num_rows($query_dat) === 0) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$aktiva." Belum Terdaftar di Master Kepemilikan DAT", "error", "$page");
            return false;
        }

        if ($status_dat === "N") {
            $GLOBALS['alert'] = array("Gagal!", "Status Kepemilikan DAT Nomor Aktiva ".$aktiva." Sudah Tidak Aktif / Pernah Ada History Pemusnahan", "error", "$page");
            return false;
        }

        $query_asset = mysqli_query($conn, "SELECT COUNT(no_at) AS total_asset FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND no_at = '$aktiva'");
        $data_asset = mysqli_fetch_assoc($query_asset);
        $tot_asset = $data_asset["total_asset"];

        $newtot_asset = ($tot_asset + array_count_values($data["at_barang_khusus"])[$aktiva]);

        if ($newtot_asset > $qty_dat) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$aktiva." Melebihi Qty Terdaftar DAT", "error", "$page");
            return false;
        }

        $queryidsn = mysqli_query($conn, "SELECT pluid, sn_barang FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND sn_barang = '$sn'");
        $dataidsn = mysqli_fetch_assoc($queryidsn);

        if($dataidsn) {
            $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." SN ".$sn." sudah terdaftar", "error", "$page");
            return false;
        }

        $final[] = $arrcek;
    }

    foreach ($final as $rows) {

        $sql = "SELECT pluid FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$rows[0]'";
        $query = mysqli_query($conn, $sql);
        
        if (!$data = mysqli_fetch_assoc($query)) {
    
            mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid) VALUES ('$office', '$dept', '$pluid')");
        
        }

        mysqli_query($conn, "INSERT INTO barang_assets (noref_asset, dat_asset, ba_id_office, ba_id_department, pluid, ba_merk, ba_tipe, sn_barang, no_at, no_lambung, kondisi, user_asset, referensi_asset, modified_asset) VALUES ('$newid', '$offdep', '$office', '$dept', '$rows[0]', '$rows[1]', '$rows[2]', '$rows[3]', '$rows[4]', '$rows[5]', '$rows[6]', '$user', '$ref', '$tgl')");
        
    }
    
    mysqli_query($conn, "INSERT INTO barang_khusus (noref_khusus, offdep_khusus, date_khusus, user_khusus) VALUES ('$newid', '$offdep', '$tgl', '$user')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function import csv
function ImportBarangKhusus($data) {

    global $conn;

    $page = $data["page-import"];
    $user = $data["user-import"];
    $ref = $data["modifref-import"];
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office-import"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dept-import"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-import"]));
    $ket = htmlspecialchars(strtoupper($data["ket-import"]));
    $tgl = date("Y-m-d H:i:s");
    $offdep = $office.$dept;
      
    $id = "K";
    $idmax = autonum(5, 'noref_khusus', 'barang_khusus');

    if(strlen($idmax) == 5) {
        // menmbahkan kode referensi dengan number baru pada func auto id
        $newid = $id.$idmax;
    }
    else {
        $newid = $id.substr($idmax, 1);
    }

    $name  = $_FILES["file-import"]["name"];
    $size  = $_FILES["file-import"]["size"];
    $error = $_FILES["file-import"]["error"];
    $tmp   = $_FILES["file-import"]["tmp_name"];

    if ($error === 4) {
        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }

    $result_arr = array();

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        $stored = [];
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            //skip current row if it is a duplicate
            if (in_array($line[0].$line[4], $stored)) {continue;}
            
            $rows_arr = array(
                $pluid = mysqli_real_escape_string($conn, strtoupper($line[0])),
                $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($line[1]))),
                $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($line[2]))),
                $nohh = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($line[3]))),
                $sn = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($line[4]))),
                $at = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($line[5]))),
                $kondisi = mysqli_real_escape_string($conn, $line[6])
            );

            $idbarang = mysqli_real_escape_string($conn, substr($line[0], 0, -4));
            $idjenis = mysqli_real_escape_string($conn, substr($line[0], 6));

            $query = mysqli_query($conn, "SELECT IDBarang, IDJenis FROM masterjenis WHERE IDBarang = '$idbarang' AND IDJenis = '$idjenis'");
            $data = mysqli_fetch_assoc($query);

            $plu = $data['IDBarang'].$data['IDJenis'];

            if ($pluid !== $plu) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan PLU/ID ".$id." tidak memiliki master barang", "error", "$page");
                return false;
            }
            
            $query_dat = mysqli_query($conn, "SELECT no_dat, qty_dat, status_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND pluid_dat = '$pluid' AND no_dat = '$at'");

            $data_dat = mysqli_fetch_assoc($query_dat);

            if (!$data_dat) {
                $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$at." Belum Terdaftar di Master Kepemilikan DAT", "error", "$page");
                return false;
            }

            $query_asset = mysqli_query($conn, "SELECT COUNT(no_at) AS total_asset FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND no_at = '$at'");
            $data_asset = mysqli_fetch_assoc($query_asset);

            $newtot_asset = ($data_asset["total_asset"] + array_count_values($line)[$at]);
    
            if ($newtot_asset > $data_dat["qty_dat"]) {
                $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$at." Melebihi Qty Terdaftar DAT", "error", "$page");
                return false;
            }

            if ($data_dat["status_dat"] === "N") {
                $GLOBALS['alert'] = array("Gagal!", "Status Kepemilikan DAT Nomor Aktiva ".$at." Sudah Tidak Aktif / Pernah Ada History Pemusnahan", "error", "$page");
                return false;
            }

            $querysn = mysqli_query($conn, "SELECT sn_barang FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND sn_barang = '$sn'");
            $datasn = mysqli_fetch_assoc($querysn);

            if($datasn) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan, SN ".$sn." sudah terdaftar", "error", "$page");
                return false;
            }

            $queryidsn = mysqli_query($conn, "SELECT pluid, sn_barang FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND sn_barang = '$sn' AND no_lambung = '$nohh'");
            $dataidsn = mysqli_fetch_assoc($queryidsn);

            if($dataidsn) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan, PLU ".$pluid." SN ".$sn." No lambung ".$nohh." sudah terdaftar", "error", "$page");
                return false;
            }

            if(strlen($at) != 10) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan, please check column no aktiva", "error", "$page");
                return false;
            }

            $querycond = mysqli_query($conn, "SELECT id_kondisi FROM kondisi WHERE id_kondisi = '$kondisi'");
            $datacond = mysqli_fetch_assoc($querycond);

            if(!$datacond) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan, please check column 6 value ".$kondisi, "error", "$page");
                return false;
            }

            //remember inserted value
            $stored[] = $line[0].$line[4];

            $result_arr[] = $rows_arr;
            
        }

        fclose($file);
        
    }
    
    foreach ($result_arr as $r) {

        $query_stock = mysqli_query($conn, "SELECT pluid FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$r[0]'");
            
        if (mysqli_num_rows($query_stock) === 0) {
            mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid) VALUES ('$office', '$dept', '$r[0]')");
        }
    
        mysqli_query($conn, "INSERT INTO barang_assets (noref_asset, dat_asset, ba_id_office, ba_id_department, pluid, ba_merk, ba_tipe, no_lambung, sn_barang, no_at, kondisi, user_asset, referensi_asset, modified_asset) VALUES ('$newid', '".$office.$dept."', '$office', '$dept', '$r[0]', '$r[1]', '$r[2]', '$r[3]', '$r[4]', '$r[5]', '$r[6]', '$user', '$ref', '$tgl')");
    
    }

    mysqli_query($conn, "INSERT INTO barang_khusus (noref_khusus, offdep_khusus, date_khusus, user_khusus, ket_khusus) VALUES ('$newid', '$offdep', '$tgl', '$user', '$ket')");

    return mysqli_affected_rows($conn);
}
// End function import csv

// ---------------------------- //

// function insert data service barang
function ProsesBarangService($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-service"]));
    $ref = mysqli_real_escape_string($conn, $data["modifref-service"]);
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-service"]));
    $ofdep_from = htmlspecialchars(mysqli_real_escape_string($conn, $data["ofdep-from-service"]));
    $office_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-to-service"]));
    $dept_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-to-service"]));
    $ket_to = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-to-service"])));
    $keperluan = htmlspecialchars($data["keperluan-service"]);
    $kondisi = htmlspecialchars(mysqli_real_escape_string($conn, $data["kondisi-service"]));
    $tgl = date("Y-m-d H:i:s");

    $ofdep_to = $office_to.$dept_to;
    $office = substr($ofdep_from, 0, 4);
    $dept = substr($ofdep_from, 4, 4);

    $id = "R";
    $idmax = autonum(5, 'no_sj', 'surat_jalan');

    if(strlen($idmax) == 5) {
        $nosj = $id.$idmax;
    }
    else {
        $nosj = $id.substr($idmax, 1);
    }

    if (!isset($_POST["kode_barang"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data barang belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    for($count = 0; $count < count($_POST["kode_barang"]); $count++) {
        $dataarr = array(
            $kd_brg = substr($_POST["kode_barang"][$count], 10, 10),
            $not_brg = substr($_POST["no_aktiva"][$count], 10, 10),
            $sn_brg = substr($_POST["sn_barang"][$count], 10),
            $mrk_brg = $_POST["merk_barang"][$count],
            $tpe_brg = $_POST["tipe_barang"][$count],
            $ket_brg = strtoupper($_POST["ket_barang"][$count])
        );
        
        mysqli_query($conn, "INSERT INTO detail_surat_jalan (head_no_sj, jenis_sj, from_sj, pluid_sj, merk_sj, tipe_sj, sn_sj, at_sj, keterangan_sj) VALUES ('$nosj', '$id', '$ofdep_from', '$kd_brg', '$mrk_brg', '$tpe_brg', '$sn_brg', '$not_brg', '$ket_brg')");

        mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$tgl', posisi = NULL WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$kd_brg' AND sn_barang = '$sn_brg'");
    
    }

    mysqli_query($conn, "INSERT INTO surat_jalan (no_sj, tanggal_sj, user_sj, asal_sj, tujuan_sj, keperluan_sj, ket_sj) VALUES ('$nosj', '$tgl', '$user', '$ofdep_from', '$ofdep_to', '$keperluan', '$ket_to')");

    $encsj = encrypt($nosj);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTSJP'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-service.php?sjp=".$encsj."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function Insert KKSO
function InsertKKSO($data) {

    global $conn;

    $page = $_POST["page-so"];
    $noso = $_POST["no-so"];
    $tgl = date("Y-m-d H:i:s");
    $user = htmlspecialchars(mysqli_real_escape_string($conn, substr($data["petugas-so"], 0, 10)));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office-so"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dept-so"]));
    $kondisi = htmlspecialchars($_POST["kondisi-so"]);
    $barang = $_POST["barangso"];
    $offdep = $office.$dept;
    $sts = 1;

    $query_checkso = mysqli_query($conn, "SELECT pluid_so_asset FROM asset_stock_opname WHERE LEFT(offdep_so_asset, 4) = '$office' AND RIGHT(offdep_so_asset, 4) = '$dept'");

    while ($data_checkso = mysqli_fetch_assoc($query_checkso)) {

        $dataexist = $data_checkso["pluid_so_asset"];
        if (in_array($dataexist, $barang)) {

            $dataarrtostring = implode(", ", $barang);
            $GLOBALS['alert'] = array("Gagal!", "Kode Barang ".$dataexist." sedang dalam proses SO", "error", "$page");
            return false;
            
        }
    }

    foreach ($barang as $arrdata)  {

        $query_ast = mysqli_query($conn, "SELECT COUNT(pluid) AS total, pluid FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND pluid = '$arrdata' AND kondisi != '$kondisi'");

        while($data_ast = mysqli_fetch_assoc($query_ast)) {

            $asset = $data_ast["total"];
            $pluid_cek = $data_ast["pluid"];

        }

        $query_saldo = mysqli_query($conn, "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$arrdata'");
        $data_saldo = mysqli_fetch_assoc($query_saldo);

        if ($data_saldo) {
            $saldo = $data_saldo["saldo_akhir"];
        }

        $saldo = isset($saldo) ? $saldo : NULL;

        $query_asset = mysqli_query($conn, "SELECT pluid, sn_barang, no_at FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND pluid = '$pluid_cek' AND kondisi != '$kondisi'");
        
        if(mysqli_num_rows($query_asset) > 0) {
            while($data_asset = mysqli_fetch_assoc($query_asset)) {
                $plu_id = $data_asset["pluid"];
                $sn = $data_asset["sn_barang"];
                $at = $data_asset["no_at"];

                // Insert data to database
                $sql_insert_asset = "INSERT INTO asset_stock_opname (noref_so_asset, offdep_so_asset, pluid_so_asset, noat_so_asset, sn_so_asset) VALUES ('$noso', '$offdep', '$plu_id', '$at', '$sn')";
                mysqli_query($conn, $sql_insert_asset);
            }

            // Insert data to database
            $sql_insert_detail = "INSERT INTO detail_stock_opname (no_so_head, pluid_so, saldo_so, asset_so) VALUES ('$noso', '$plu_id', '$saldo', '$asset')";     
            mysqli_query($conn, $sql_insert_detail);
        }

    }

    $sql_insert_head = "INSERT INTO head_stock_opname (no_so, tgl_so, user_so, office_so, dept_so, jenis_so) VALUES ('$noso', '$tgl', '$user', '$office', '$dept', 1)";

    mysqli_query($conn, $sql_insert_head);
    
    return mysqli_affected_rows($conn);
}
// End function KKSO

// ---------------------------- //

// function delete draft KKSO
function DeleteKKSO($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["del-noso"]);
    
    mysqli_query($conn, "DELETE FROM asset_stock_opname WHERE noref_so_asset = '$no_so'");
    mysqli_query($conn, "DELETE FROM detail_stock_opname WHERE no_so_head = '$no_so'");
    mysqli_query($conn, "DELETE FROM head_stock_opname WHERE no_so = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function delete draft KKSO

// ---------------------------- //

// function Update / Reset LHSO
function ResetLHSO($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["reset-noso"]);
    
    $query_detail = mysqli_query($conn, "SELECT pluid_so FROM detail_stock_opname WHERE no_so_head = '$no_so'");
    while($data_detail = mysqli_fetch_assoc($query_detail)){
        $pluid = $data_detail["pluid_so"];
        
        mysqli_query($conn, "UPDATE asset_stock_opname SET kondisi_so_asset = NULL, lokasi_so_asset = NULL WHERE pluid_so_asset = '$pluid'");

    }
    $fisik = 0;
    mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = '$fisik' WHERE no_so_head = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function Update / Reset LHSO

// ---------------------------- //

// function Adjust SO
function AdjustLHSO($data) {

    global $conn;

    $page = $_POST["page-noso"];
    $page_success = $_POST["pagesuccess-noso"];
    $ref = mysqli_real_escape_string($conn, $data["modifref-noso"]);
    $user = mysqli_real_escape_string($conn, substr($data["user-noso"], 0, 10));
    $no_so = mysqli_real_escape_string($conn, $data["adjust-noso"]);
    $date = date("Y-m-d H:i:s");

    $query_branch = mysqli_query($conn, "SELECT * FROM head_stock_opname WHERE no_so = '$no_so'");
    $data_branch = mysqli_fetch_assoc($query_branch);

    if (!$data_branch) {
        $GLOBALS['alert'] = array("Gagal!", "Tidak ada data draft stock opname", "error", "$page");
        return false;
    }
    else {
        if ($data_branch["status_so"] == "Y") {
            $GLOBALS['alert'] = array("Gagal!", "Data SO ".$no_so." sudah pernah dilakukan Adjust", "error", "$page");
            return false;
        }
    }

    $query_branch = mysqli_query($conn, "SELECT office_so, dept_so FROM head_stock_opname WHERE no_so = '$no_so'");
    $data_branch = mysqli_fetch_assoc($query_branch);
    $office = $data_branch["office_so"];
    $dept = $data_branch["dept_so"];
    $dat_asset = $office.$dept;

    $result_update = array();

    $query_detail = mysqli_query($conn, "SELECT pluid_so, fisik_so FROM detail_stock_opname WHERE no_so_head = '$no_so'");
    while($data_detail = mysqli_fetch_assoc($query_detail)){
        $pluid = $data_detail["pluid_so"];
        $fisik = $data_detail["fisik_so"];

        $query_so = "SELECT pluid_so_asset, sn_so_asset, noat_so_asset, kondisi_so_asset, lokasi_so_asset FROM asset_stock_opname WHERE noref_so_asset = '$no_so' AND pluid_so_asset = '$pluid'";
        $resultso = mysqli_query($conn, $query_so);

        if (!$resultso) {
            $GLOBALS['alert'] = array("Gagal!", "Data hasil SO tidak ditemukan", "error", "$page");
            return false;
        }

        while($data_so = mysqli_fetch_assoc($resultso)){
            $pluid_dataso = $data_so["pluid_so_asset"];
            $sn_dataso = $data_so["sn_so_asset"];
            $noat_dataso = $data_so["noat_so_asset"];
            $kondisi_dataso = $data_so["kondisi_so_asset"];
            $posisi_dataso = $data_so["lokasi_so_asset"];

            if ($data_so["kondisi_so_asset"] === NULL && $data_so["lokasi_so_asset"] === NULL) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat barang yang belum dilakukan opname", "error", "$page");
                return false;
            }
            
            $arrso = [
                $pluid_dataso,
                $sn_dataso,
                $noat_dataso,
                $kondisi_dataso,
                $posisi_dataso
            ];

            $result_update[] = $arrso;
        }

        mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$fisik' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'");

        mysqli_query($conn, "DELETE FROM asset_stock_opname WHERE noref_so_asset = '$no_so' AND pluid_so_asset = '$pluid'");
    }
    
    foreach ($result_update as $row) {
        $query_update = "UPDATE barang_assets SET kondisi = '$row[3]', posisi = '$row[4]', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE dat_asset = '$dat_asset' AND pluid = '$row[0]' AND sn_barang = '$row[1]' AND no_at = '$row[2]'";

        if (!mysqli_query($conn, $query_update)) {
            $GLOBALS['alert'] = array("Gagal!", "Tidak dapat mencocokan data hasil SO", "error", "$page");
            return false;
        }
    }

    mysqli_query($conn, "UPDATE head_stock_opname SET status_so = 'Y' WHERE no_so = '$no_so'");

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    $_SESSION["ALERT"] = array("Success!", "Data SO ".$no_so." Berhasil di Adjust", "success", "$page_success");

    return mysqli_affected_rows($conn);

}
// End function Adjust SO

// ---------------------------- //

// Function Upload LHSO
function UploadSO($data) {

    global $conn;

    $page = $_POST["page-so"];
    $noref = $_POST["noref-so"];
    $offdep = htmlspecialchars($_POST["offdep-so"]);

    $name  = $_FILES["filecsv-so"]["name"];
    $size  = $_FILES["filecsv-so"]["size"];
    $error = $_FILES["filecsv-so"]["error"];
    $tmp   = $_FILES["filecsv-so"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        $stored = [];
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            $kdbarang = isset($line[0]) ? $line[0] : NULL;
            $snbarang = isset($line[1]) ? $line[1] : NULL;
            $atbarang = isset($line[2]) ? $line[2] : NULL;
            $stbarang = isset($line[3]) ? $line[3] : NULL;
            $lkbarang = isset($line[4]) ? strtoupper($line[4]) : NULL;

            //skip current row if it is a duplicate
            if (in_array($snbarang, $stored)) { continue;}

            $query = mysqli_query($conn, "SELECT * FROM asset_stock_opname WHERE noref_so_asset = '$noref' AND pluid_so_asset = '$kdbarang' AND sn_so_asset = '$snbarang' AND noat_so_asset = '$atbarang'");

            if (!mysqli_fetch_assoc($query)) {
                $GLOBALS['alert'] = array("Gagal!", "Data SO tidak matching", "error", "$page");
                return false;
            }

            // Insert data to database
            $sql_asset = "UPDATE asset_stock_opname SET kondisi_so_asset = '$stbarang', lokasi_so_asset = '$lkbarang' WHERE noref_so_asset = '$noref' AND pluid_so_asset = '$kdbarang' AND sn_so_asset = '$snbarang' AND noat_so_asset = '$atbarang'";
            mysqli_query($conn, $sql_asset);

            //remember inserted value
            $stored[] = $snbarang;
            
        }

        fclose($file);
        
    }

    $query_cekasset = mysqli_query($conn, "SELECT pluid_so FROM detail_stock_opname WHERE no_so_head = '$noref'");
        
    if(mysqli_num_rows($query_cekasset) > 0) {
        while($data_so = mysqli_fetch_assoc($query_cekasset)) {
            $pluid_so = $data_so["pluid_so"];

            $qty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total FROM asset_stock_opname WHERE noref_so_asset = '$noref' AND pluid_so_asset = '$pluid_so' AND kondisi_so_asset NOT LIKE '06' AND kondisi_so_asset NOT LIKE '07'"));
       
            $data = $qty["total"];

            $sql_detail = "UPDATE detail_stock_opname SET fisik_so = '$data' WHERE no_so_head = '$noref' AND pluid_so = '$pluid_so'";
            mysqli_query($conn, $sql_detail);
        }
    }

    return mysqli_affected_rows($conn);

}
// End function import csv

// ---------------------------- //

// function Update / Proses Terima Barang Service
function ReceiveBarangService($data) {

    global $conn;

    $page = $data["page-servbarang"];
    $ref = mysqli_real_escape_string($conn, $data["modifref-servbarang"]);
    $id = mysqli_real_escape_string($conn, $data["id-servbarang"]);
    $offdep = mysqli_real_escape_string($conn, $data["offdep-servbarang"]);
    $no_sj = mysqli_real_escape_string($conn, $data["terima-servbarang"]);
    $pluid = mysqli_real_escape_string($conn, $data["pluid-servbarang"]);
    $sn = mysqli_real_escape_string($conn, $data["sn-servbarang"]);
    $at = mysqli_real_escape_string($conn, $data["at-servbarang"]);
    $kondisi = mysqli_real_escape_string($conn, isset($data["kond-servbarang"]) ? $data["kond-servbarang"] : NULL);
    $user = mysqli_real_escape_string($conn, isset($data["user-servbarang"]) ? $data["user-servbarang"] : NULL);
    $posisi = mysqli_real_escape_string($conn, strtoupper($data["posisi-servbarang"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["keterangan-servbarang"])));
    $tgl = date("Y-m-d");
    $date = date("Y-m-d H:i:s");

    $office = substr($offdep, 0, 4);
    $dept = substr($offdep, 4);

    if($user == "" || empty($user)) {
        $GLOBALS['alert'] = array("Gagal!", "Data penerima belum ada yang dipilih", "error", "$page");
        return false;
    }

    if ($kondisi == "" || empty($kondisi)) {
        $GLOBALS['alert'] = array("Gagal!", "Data status perbaikan belum ada yang dipilih", "error", "$page");
        return false;
    }

    mysqli_query($conn, "UPDATE detail_surat_jalan SET status_sj = 'Y', kondisi_perbaikan = '$kondisi', penerima_sj = '$user', tgl_penerimaan = '$tgl', ket_penerimaan_sj = '$ket' WHERE detail_no_sj = '$id'");
    mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', posisi = '$posisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND sn_barang = '$sn' AND no_at = '$at'");

    $encnoref = encrypt($id);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTBPHP3'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-hppb.php?noref=".$encnoref."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);

}
// function Update / Proses Terima Barang Service

// ---------------------------- //

// function insert data barang p3at
function InsertBarangMusnah($data) {

    global $conn;

    // Input data post
    $pluid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluid-musnah"]));
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, $data["merk-musnah"]));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, $data["tipe-musnah"]));
    $sn = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["sn-musnah"])));
    $aktiva = htmlspecialchars(mysqli_real_escape_string($conn, substr($data["at-musnah"], 0, 10)));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tp-musnah"]));
    $nilai = htmlspecialchars(mysqli_real_escape_string($conn, $data["nilai-musnah"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-musnah"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-musnah"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-musnah"]));
    $tgl = date("Y-m-d H:i:s");
    $offdep = $office.$dept;

    // Check sn di database
    $result = mysqli_query($conn, "SELECT at_p3at FROM detail_p3at WHERE pluid_p3at ='$pluid' AND sn_p3at ='$sn'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['err_duplat'] = "Gagal! Nomor SN ".$sn." telah ditambahkan/sudah pernah proses P3AT.";
        return false;

    }

    mysqli_query($conn, "INSERT INTO detail_p3at (offdep_p3at, pluid_p3at, merk_p3at, tipe_p3at, sn_p3at, at_p3at, th_p3at, nilai_p3at) VALUES ('$offdep', '$pluid', '$merk', '$tipe', '$sn', '$aktiva', '$tahun', '$nilai')");

    return mysqli_affected_rows($conn);
}   
// End function

// function delete Pengajuan P3AT
function DeleteBarangMusnah($data) {

    global $conn;

    $id_p3at = mysqli_real_escape_string($conn, $data["del-p3at"]);
    
    mysqli_query($conn, "DELETE FROM detail_p3at WHERE id_detail_p3at = '$id_p3at'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function proses p3at
function ProsesBarangMusnah($data) {

    global $conn;

    // Input data post
    $page = $data["page-musnah"];
    $ref = mysqli_real_escape_string($conn, $data["modifref-musnah"]);
    $no_p3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["no-musnah"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-musnah"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-musnah"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-musnah"]));
    $sp3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["status-musnah"]));
    $sign = htmlspecialchars(mysqli_real_escape_string($conn, $data["sign-musnah"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-musnah"])));
    $tgl = date("Y-m-d H:i:s");

    $offdep = $office.$dept;

    if (!isset($_POST["kode_barang"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data barang belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    for($count = 0; $count < count($_POST["kode_barang"]); $count++) {
        $dataarr = array(
            $kd_brg = substr($_POST["kode_barang"][$count], 10, 10),
            $not_brg = substr($_POST["no_aktiva"][$count], 10, 10),
            $sn_brg = substr($_POST["sn_barang"][$count], 10),
            $mrk_brg = $_POST["merk_barang"][$count],
            $tpe_brg = $_POST["tipe_barang"][$count],
            $tahun = $_POST["tahun_barang"][$count],
            $nilai = $_POST["nilai_barang"][$count]
        );
        
        mysqli_query($conn, "INSERT INTO detail_p3at (id_head_p3at, offdep_p3at, pluid_p3at, merk_p3at, tipe_p3at, sn_p3at, at_p3at, th_p3at, nilai_p3at) VALUES ('$no_p3at', '$offdep', '$kd_brg', '$mrk_brg', '$tpe_brg', '$sn_brg', '$not_brg', '$tahun', '$nilai')");

        mysqli_query($conn, "UPDATE barang_assets SET user_asset = '$user', referensi_asset = '$ref', modified_asset = '$tgl' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$kd_brg' AND sn_barang = '$sn_brg'");
    
    }

    mysqli_query($conn, "INSERT INTO p3at (id_p3at, tgl_p3at, office_p3at, dept_p3at, judul_p3at, user_p3at, sign_p3at, status_p3at) VALUES ('$no_p3at', '$tgl', '$office', '$dept', '$ket', '$user', '$sign', '$sp3at')");

    $encp3at = encrypt($no_p3at);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTP3AT'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-ba-p3at.php?p3at=".$encp3at."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete sub divisi
function deletesubdivisi($data) {

    global $conn;

    $id_subdiv = mysqli_real_escape_string($conn, $data["idsubdiv"]);
    
    mysqli_query($conn, "DELETE FROM sub_divisi WHERE id_sub_divisi = '$id_subdiv'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// Function insert sub divisi
function insertsubdivisi($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $divisiid = htmlspecialchars(mysqli_real_escape_string($conn, $data["divisiid"]));
    $subdivisiname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["subdivisiname"])));

    // Check tahun jika nama sub divisi sudah ada
    $query = mysqli_query($conn, "SELECT sub_divisi_name FROM sub_divisi WHERE sub_divisi_name = '$subdivisiname'");

    if($data = mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Nama Sub Divisi ".$subdivisiname." Telah Terdaftar", "error", "$page");
        return false;
    
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO sub_divisi (id_divisi, sub_divisi_name) VALUES ('$divisiid', '$subdivisiname')");

    return mysqli_affected_rows($conn);

}
// End function insert sub divisi

// ---------------------------- //

// function update kondisi dan penempatan barang
function ProsesBarangHilang($data) {

    global $conn;

    // Input data post
    $office = mysqli_real_escape_string($conn, $data["office-barang"]);
    $dept = mysqli_real_escape_string($conn, $data["dept-barang"]);
    $pluid = mysqli_real_escape_string($conn, $data["pluid-barang"]);
    $sn = mysqli_real_escape_string($conn, $data["sn-barang"]);
    $kondisi = htmlspecialchars(mysqli_real_escape_string($conn, $data["kond-barang"]));
    $lokasi = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["lokasi-barang"])));

    // Update pembelian to database
    mysqli_query($conn, "UPDATE barang_assets SET posisi = '$lokasi', kondisi = '$kondisi' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$pluid' AND sn_barang = '$sn'");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert data ppnb
function InsertBarangPPNB($data) {

    global $conn;

    // Input data post
    $page = $data["page-ppnb"];
    $noref = htmlspecialchars($data["noref-ppnb"]);
    $offdep = htmlspecialchars($data["offdep-ppnb"]);
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-ppnb"]));
    $pluid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluid-ppnb"]));
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merk-ppnb"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipe-ppnb"])));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qty-ppnb"]));
    $cost = htmlspecialchars($data["harga-ppnb"]);
    $harga = ($cost * $qty);
    $keterangan = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["keterangan-ppnb"])));
    $office = substr($offdep, 0, 4);
    $dept = substr($offdep, 4, 4);

    // Check qty
    if($qty <= 0) {

        $GLOBALS['alert'] = array("Gagal!", "jumlah qty tidak boleh kurang atau sama dengan 0", "error", "$page");
        return false;

    }

    // Check pluid di database
    $result = mysqli_query($conn, "SELECT plu_id FROM detail_pembelian WHERE noref = '$noref' AND plu_id ='$pluid'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." sudah masuk kedalam list PP", "error", "$page");
        return false;

    }

    $query_pp = mysqli_query($conn, "SELECT tgl_pengajuan FROM pembelian WHERE noref = '$noref'");
    $result_pp = mysqli_fetch_assoc($query_pp);
    $dateprop = $result_pp['tgl_pengajuan'];
    $tahun = substr($result_pp['tgl_pengajuan'], 0, 4);

    $querystock = mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
    $datastock = mysqli_fetch_assoc($querystock);
    $use_budget = isset($datastock['use_budget']) ? $datastock['use_budget'] : 0;

    $update_usebgt = $use_budget + $qty;
    
    mysqli_query($conn, "UPDATE budget SET use_budget = '$update_usebgt' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
    
    $sql = "INSERT INTO detail_pembelian (noref, tgl_detail_pp, id_offdep, user_pp, plu_id, merk, tipe, qty, harga_pp, keterangan, proses) VALUES ('$noref', '$dateprop', '$offdep', '$user', '$pluid', '$merk', '$tipe', '$qty', '$harga', '$keterangan', 'Y')";
        
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete data ppnb
function DeleteBarangPPNB($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-dppnb"]);
    $noref = mysqli_real_escape_string($conn, $data["noref-dppnb"]);
    $id_dppnb = mysqli_real_escape_string($conn, $data["id-dppnb"]);
    $offdep = mysqli_real_escape_string($conn, $data["offdep-dppnb"]);
    $office = substr($offdep, 0, 4);
    $dept = substr($offdep, 4, 4);
    $date = mysqli_real_escape_string($conn, $data["date-dppnb"]);
    $tahun = substr($date, 0, 4);
    $pluid = mysqli_real_escape_string($conn, $data["pluid-dppnb"]);
    $qty_pp = mysqli_real_escape_string($conn, $data["qtypp-dppnb"]);

    $q_rows_item = mysqli_query($conn, "SELECT noref FROM detail_pembelian WHERE noref = '$noref'");
    $d_rows_item = mysqli_num_rows($q_rows_item);

    if($d_rows_item === 1){

        $GLOBALS['alert'] = array("Gagal!", "Daftar item barang PP tidak boleh kosong", "error", "$page");
        return false;

    }

    $querystock = mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
    $datastock = mysqli_fetch_assoc($querystock);
    $stock_bgt = isset($datastock['use_budget']) ? $datastock['use_budget'] : 0;

    $update_usebgt = ($stock_bgt - $qty_pp);
    
    mysqli_query($conn, "UPDATE budget SET use_budget = '$update_usebgt' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");
    
    mysqli_query($conn, "DELETE FROM detail_pembelian WHERE id_dpp = '$id_dppnb'");


    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert data mutasi barang
function ProsesBarangMutasi($data) {

    global $conn;

    // Input data post
    $page = mysqli_real_escape_string($conn, $data["page-mutasi"]);
    $ref = mysqli_real_escape_string($conn, $data["modifref-mutasi"]);
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-mutasi"]));
    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-mutasi"]);
    $ofdep_from = htmlspecialchars(mysqli_real_escape_string($conn, $data["ofdep-from-mutasi"]));
    $office_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-to-mutasi"]));
    $dept_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-to-mutasi"]));
    $ket = htmlspecialchars(strtoupper($data["ket-mutasi"]));
    $tgl = date("Y-m-d H:i:s");

    $office = substr($ofdep_from, 0, 4);
    $dept = substr($ofdep_from, 4, 4);

    $ofdep_to = $office_to.$dept_to;

    $id = "M";
    $idmax = autonum(5, 'no_mutasi', 'mutasi');
    $nomutasi = $id.$idmax;

    if (!isset($_POST["kode_barang"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data barang belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    for($count = 0; $count < count($_POST["kode_barang"]); $count++) {

        $kd_brg = substr($_POST["kode_barang"][$count], 10, 10);
        $not_brg = substr($_POST["no_aktiva"][$count], 10, 10);
        $sn_brg = substr($_POST["sn_barang"][$count], 10);
        $mrk_brg = $_POST["merk_barang"][$count];
        $tpe_brg = $_POST["tipe_barang"][$count];
        
        mysqli_query($conn, "INSERT INTO detail_mutasi (head_no_mutasi, id_office_mutasi, id_dept_mutasi, pluid_mutasi, merk_mutasi, tipe_mutasi, sn_mutasi, at_mutasi) VALUES ('$nomutasi', '$office', '$dept', '$kd_brg', '$mrk_brg', '$tpe_brg', '$sn_brg', '$not_brg')");

        mysqli_query($conn, "UPDATE barang_assets SET ba_id_office = '$office_to', ba_id_department = '$dept_to', kondisi = '$kondisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$tgl' WHERE pluid = '$kd_brg' AND sn_barang = '$sn_brg'");
    }

    mysqli_query($conn, "INSERT INTO mutasi (no_mutasi, tgl_mutasi, user_mutasi, asal_mutasi, tujuan_mutasi, ket_mutasi) VALUES ('$nomutasi', '$tgl', '$user', '$ofdep_from', '$ofdep_to', '$ket')");

    mysqli_query($conn, "INSERT INTO barang_khusus (noref_khusus, offdep_khusus, date_khusus, user_khusus) VALUES ('$nomutasi', '$ofdep_to', '$tgl', '$user')");

    $encmutasi = encrypt($nomutasi);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTSJM'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-mutasi.php?sjm=".$encmutasi."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function Update Aktiva Barang Mutasi
function UpdateBarangMutasi($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-mutasi"]);
    $no = mysqli_real_escape_string($conn, $data["dno-mutasi"]);
    $nom = mysqli_real_escape_string($conn, $data["no-mutasi"]);
    $pluid = mysqli_real_escape_string($conn, $data["pluid-mutasi"]);
    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-mutasi"]);
    $cad = mysqli_real_escape_string($conn, $data["kondisi-cadangan"]);
    $sn = mysqli_real_escape_string($conn, $data["sn-mutasi"]);
    $aktiva = mysqli_real_escape_string($conn, $data["aktiva-mutasi"]);
    $user = mysqli_real_escape_string($conn, $data["user-mutasi"]);
    $datold = mysqli_real_escape_string($conn, $data["upd-atmutasi"]);
    $officeto = mysqli_real_escape_string($conn, $data["office-mutasi"]);
    $deptto = mysqli_real_escape_string($conn, $data["dept-mutasi"]);
    $offdepto = $officeto.$deptto;
    
    $offdepfrom = mysqli_real_escape_string($conn, $data["offdepfrom-mutasi"]);
    $officefrom = substr($offdepfrom, 0, 4);
    $deptfrom = substr($offdepfrom, 4, 4);

    if (strlen($aktiva) != 10) { 
        $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan penulisan data aktiva barang ".$aktiva, "error", "$page");
        return false;
    }

    $sql = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$officeto' AND ms_id_department = '$deptto' AND pluid = '$pluid'";
    $query = mysqli_query($conn, $sql);

    if ($data = mysqli_fetch_assoc($query)) {
        
        $saldo_akhir = $data["saldo_akhir"];
        $saldo = ($saldo_akhir + 1);
        mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo' WHERE ms_id_office = '$officeto' AND ms_id_department = '$deptto' AND pluid = '$pluid'");
    
    }
    else {
        $saldo = 1;
        mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid, saldo_akhir) VALUES ('$officeto', '$deptto', '$pluid', '$saldo')");
    
    }

    $query_asset = mysqli_query($conn, "SELECT COUNT(no_at) AS total_asset FROM barang_assets WHERE dat_asset = '$offdepfrom' AND no_at = '$datold' AND kondisi = '$kondisi'");
    $data_asset = mysqli_fetch_assoc($query_asset);
    
    $query_dat = mysqli_query($conn, "SELECT pluid_dat, qty_dat FROM dat WHERE office_dat = '$officefrom' AND dept_dat = '$deptfrom' AND no_dat = '$datold'");
    $data_dat = mysqli_fetch_assoc($query_dat);

    $pluiddat = $data_dat["pluid_dat"];
    $qty = $data_dat["qty_dat"];

    if ($data_asset["total_asset"] == $qty) {
        mysqli_query($conn, "UPDATE dat SET status_dat = 'N' WHERE office_dat = '$officefrom' AND dept_dat = '$deptfrom' AND no_dat = '$datold'");
        mysqli_query($conn, "INSERT INTO dat (office_dat, dept_dat, no_dat, pluid_dat, qty_dat) VALUES ('$officeto', '$deptto', '$aktiva', '$pluiddat', '$qty')");
    }

    mysqli_query($conn, "UPDATE detail_mutasi SET status_mutasi = 'Y', new_at = '$aktiva', user_proses = '$user' WHERE detail_no_mutasi = '$no'");
    mysqli_query($conn, "UPDATE barang_assets SET dat_asset = '$offdepto', no_at = '$aktiva', kondisi = '$cad', noref_asset = '$nom' WHERE pluid = '$pluid' AND sn_barang = '$sn'");

    return mysqli_affected_rows($conn);

}
// function Update Aktiva Barang Mutasi

// ---------------------------- //

// Function insert type plano
function InsertTypePlano($data) {

    global $conn;

    // Input data post
    $id_office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-type"]));
    $id_type = htmlspecialchars(mysqli_real_escape_string($conn, $data["idtype-plano"]));
    $name_type = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["nmtype-plano"])));

    if (strlen($id_type) == 2) {
    
        // kode spp
        $code = "T";

        // menmbahkan kode type rak dengan number baru pada func auto id
        $newid = $code.$id_type;


    }
    else {

        $newid = $id_type;

    }
    
    // Insert data to database
    mysqli_query($conn, "INSERT INTO type_plano (id_type_plano, office_type_plano, nm_type_plano) VALUES ('$newid', '$id_office', '$name_type')");

    return mysqli_affected_rows($conn);

}
// End function insert data type rak

// ---------------------------- //

// function delete type rak
function DeleteTypePlano($data) {

    global $conn;

    $page = htmlspecialchars($data["page-plano"]);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idtype-plano"]));

    // Check id type rak yang berelasi dengan tabel zona di database
    $query = mysqli_query($conn, "SELECT id_type_plano_head FROM zona_plano WHERE id_type_plano_head = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Type rak yang telah memiliki zona tidak dapat di hapus", "error", "$page");
        return false;
    
    }

    $result = "DELETE FROM type_plano WHERE id_type_plano = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete type rak

// ---------------------------- //

// Function insert zona plano
function InsertZonaPlano($data) {

    global $conn;

    // Input data post
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-plano"]));
    $id_type = htmlspecialchars(mysqli_real_escape_string($conn, $data["idtype-plano"]));
    $id_zona = htmlspecialchars(mysqli_real_escape_string($conn, $data["idzona-plano"]));
    $name_zona = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["nmzona-plano"])));
    $type_item = htmlspecialchars(mysqli_real_escape_string($conn, $data["itemtype-plano"]));
    $station = htmlspecialchars($data["station-plano"]);

    if (strlen($id_zona) == 2) {
    
        // kode spp
        $code = "Z";

        // menmbahkan kode zona rak dengan number baru pada func auto id
        $newid = $code.$id_zona;


    }
    else {

        $newid = $id_zona;

    }
    
    // Insert data to database
    mysqli_query($conn, "INSERT INTO zona_plano (id_zona_plano, office_zona_plano, id_type_plano_head, nm_zona_plano, station_zona_plano, item_zona_plano) VALUES ('$newid', '$office', '$id_type', '$name_zona', '$station', '$type_item')");

    return mysqli_affected_rows($conn);

}
// End function insert data zona rak

// ---------------------------- //

// function delete zona rak
function DeleteZonaPlano($data) {

    global $conn;

    $page = htmlspecialchars($data["page-plano"]);
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idzona-plano"]));

    // Check id zona rak yang berelasi dengan tabel line di database
    $query = mysqli_query($conn, "SELECT id_zona_plano_head FROM line_plano WHERE id_zona_plano_head = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Zona rak yang telah memiliki line tidak dapat di hapus", "error", "$page");
        return false;
    
    }

    $result = "DELETE FROM zona_plano WHERE id_zona_plano = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete type rak

// ---------------------------- //

// Function insert line plano
function InsertLinePlano($data) {

    global $conn;

    // Input data post
    $page = $data["page"];
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-plano"]));
    $id_zona = htmlspecialchars(mysqli_real_escape_string($conn, $data["idzona-plano"]));
    $id_line = htmlspecialchars(mysqli_real_escape_string($conn, $data["idline-plano"]));
    $name_line = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["nmline-plano"])));
    $station = htmlspecialchars($data["stationzona-plano"]);
    $rak = htmlspecialchars($data["rak-plano"]);
    $shelf = htmlspecialchars($data["shelf-plano"]);
    $cell = htmlspecialchars($data["cell-plano"]);
    $id = htmlspecialchars($data["id-plano"]);
    $ip_dpd = implode(", ", $data["ipdpd_plano"]);

    if (strlen($id_line) == 2) {
    
        // kode spp
        $code = "L";

        // menmbahkan kode line rak dengan number baru pada func auto id
        $newid = $code.$id_line;


    }
    else {

        $newid = $id_line;

    }

    if (!isset($data["ipdpd_plano"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada IP DPD yang tambahkan", "error", "$page");
        return false;
    }

    if(count(array_unique($data["ipdpd_plano"], SORT_REGULAR)) < count($data["ipdpd_plano"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat IP DPD duplicate", "error", "$page");
        return false;
    }

    for($count = 0; $count < count($data["ipdpd_plano"]); $count++) {

        $ip = $data["ipdpd_plano"][$count];
        mysqli_query($conn, "INSERT INTO gateway_dpd (id_head_line_plano, ip_gateway_dpd) VALUES ('$newid', '$ip')");

    }
    
    // Insert data to database
    mysqli_query($conn, "INSERT INTO line_plano (id_line_plano, office_line_plano, id_zona_plano_head, nm_line_plano, station_line_plano, rak_line_plano, shelf_line_plano, cell_line_plano, iddpd_line_plano, ip_line_plano) VALUES ('$newid', '$office', '$id_zona', '$name_line', '$station', '$rak', '$shelf', '$cell', '$id', '$ip_dpd')");

    return mysqli_affected_rows($conn);

}
// End function insert data line rak

// ---------------------------- //

// function delete line rak
function DeleteLinePlano($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idline-plano"]));
    
    $sql_line = "DELETE FROM line_plano WHERE id_line_plano = '$id'";
    mysqli_query($conn, $sql_line);

    $sql_gwdpd = "DELETE FROM gateway_dpd WHERE id_head_line_plano = '$id'";
    mysqli_query($conn, $sql_gwdpd);

    return mysqli_affected_rows($conn);

}
// End function delete line rak

// ---------------------------- //

// function insert data 
function InsertBarangTablok($data) {

    global $conn;

    // Input data post
    $page = $data["page-plano"];
    $office = $data["office-tablok"];
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["plu-tablok"])));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["nama-tablok"])));
    $type = htmlspecialchars(substr($data["type-tablok"], 3));
    $line = htmlspecialchars(substr($data['line-tablok'], 3));
    $zona = htmlspecialchars(substr($data['zona-tablok'], 3));
    $station = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["station-tablok"])));
    $rak = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["rak-tablok"])));
    $shelf = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["shelf-tablok"])));
    $cell = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["cell-tablok"])));
    $ip = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ip-tablok"])));
    $id = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["id-tablok"])));
    $item = htmlspecialchars(mysqli_real_escape_string($conn, $data["item-tablok"]));
    $ctn = htmlspecialchars(mysqli_real_escape_string($conn, $data["carton-tablok"]));

    if(strlen($plu) != 8 ) {

        $GLOBALS['alert'] = array("Gagal!", "Please check PLU tidak boleh lebih atau kurang dari 8 digit", "error", "$page");
        return false;

    }

    $docno = autonum(7, 'docno_st_dpd_detail', 'st_dpd_detail');

    for($count = 0; $count < count($data["line_friend_plano"]); $count++) {

        $friend_line = strtoupper($data["line_friend_plano"][$count]);
        $friend_rak = $data["rak_friend_plano"][$count];
        mysqli_query($conn, "INSERT INTO pertemanan_plano (id_tablok, line_pertemanan_plano, rak_pertemanan_plano) VALUES ('$docno', '$friend_line', '$friend_rak')");

    }

    mysqli_query($conn, "INSERT INTO st_dpd_detail (plu_st_dpd_detail, office_st_dpd_detail, docno_st_dpd_detail, nama_st_dpd_detail, type_st_dpd_detail, line_st_dpd_detail, zona_st_dpd_detail, station_st_dpd_detail, rak_st_dpd_detail, shelf_st_dpd_detail, cell_st_dpd_detail, ip_st_dpd_detail, dpd_st_dpd_detail, item_st_dpd_detail, carton_st_dpd_detail) VALUES ('$plu', '$office', '$docno', '$nama', '$type', '$line', '$zona', '$station', '$rak', '$shelf', '$cell', '$ip', '$id', '$item', '$ctn')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

function UploadBarangTablok($data) {

    global $conn;

    $page = $data["page-tablok"];
    $office = htmlspecialchars($data["office-tablok"]);

    $name  = $_FILES["file-tablok"]["name"];
    $size  = $_FILES["file-tablok"]["size"];
    $error = $_FILES["file-tablok"]["error"];
    $tmp   = $_FILES["file-tablok"]["tmp_name"];

    if ($error === 4) {
        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }

    $idmax = autonum(7, 'docno_st_dpd_detail', 'st_dpd_detail');

    $result_arr = array();

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        fgetcsv($file);

        while (($r = fgetcsv($file)) !== FALSE) {
            
            if (strtoupper($r[2]) === "F") {
                $r_typeRak = "FRACTION";
            }
            elseif (strtoupper($r[2]) === "BK") {
                $r_typeRak = "BULKY";
            }
            elseif (strtoupper($r[2]) === "BF") {
                $r_typeRak = "BULKY FRACTION";
            }
            else {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan pada kolom TYPE_RAK, data yang diterima hanya berisi nilai 'F' (Untuk Fraction), 'BK' (Untuk Bulky), 'BF' (Untuk Bulky Fraction).", "error", "$page");
                return false;
            }

            
            if (strtoupper($r[3]) === "F") {
                $r_typeItem = "FOOD";
            }
            elseif (strtoupper($r[3]) === "NF") {
                $r_typeItem = "NON FOOD";
            }
            else {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan pada kolom TYPE_ITEM, data yang diterima hanya berisi nilai 'F' (Untuk FOOD), dan 'NF' (Untuk NON FOOD).", "error", "$page");
                return false;
            }

            $query_zona = mysqli_query($conn, "SELECT * FROM zona_plano WHERE office_zona_plano = '$office' AND nm_zona_plano = '$r[5]' AND item_zona_plano = '$r_typeItem'");
            $data_zona = mysqli_fetch_assoc($query_zona);

            if (!$data_zona) {
                $GLOBALS['alert'] = array("Gagal!", "Terdapat kesalahan pada kolom ZONA ".$r[5].", TYPE_ITEM ".$r_typeItem." tidak terdaftar di master zona", "error", "$page");
                return false;
            }
            
            $rows_arr = array(
                $plu = htmlspecialchars(mysqli_real_escape_string($conn, $r[0])),
                $desc = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($r[1]))),
                $type = htmlspecialchars(mysqli_real_escape_string($conn, $r_typeRak)),
                $item = htmlspecialchars(mysqli_real_escape_string($conn, $r_typeItem)),
                $line = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($r[4]))),
                $zona = htmlspecialchars(mysqli_real_escape_string($conn, $r[5])),
                $station = htmlspecialchars(mysqli_real_escape_string($conn, $r[6])),
                $rak = htmlspecialchars(mysqli_real_escape_string($conn, $r[7])),
                $shelf = htmlspecialchars(mysqli_real_escape_string($conn, $r[8])),
                $cell = htmlspecialchars(mysqli_real_escape_string($conn, $r[9])),
                $ctn = htmlspecialchars(mysqli_real_escape_string($conn, $r[10])),
                $ip = htmlspecialchars(mysqli_real_escape_string($conn, $r[11])),
                $id = htmlspecialchars(mysqli_real_escape_string($conn, $r[12]))
            );

            $result_arr[] = $rows_arr;
            
        }
        fclose($file);
    }
    
    foreach ($result_arr as $r) {
    
        mysqli_query($conn, "INSERT INTO st_dpd_detail (docno_st_dpd_detail, office_st_dpd_detail, plu_st_dpd_detail, nama_st_dpd_detail, type_st_dpd_detail, line_st_dpd_detail, zona_st_dpd_detail, station_st_dpd_detail, rak_st_dpd_detail, shelf_st_dpd_detail, cell_st_dpd_detail, carton_st_dpd_detail, item_st_dpd_detail, ip_st_dpd_detail, dpd_st_dpd_detail) VALUES ('$idmax', '$office', '$r[0]', '$r[1]', '$r[2]', '$r[4]', '$r[5]', '$r[6]', '$r[7]', '$r[8]', '$r[9]', '$r[10]', '$r[3]', '$r[11]', '$r[12]')");
    
    }

    return mysqli_affected_rows($conn);
}

// ---------------------------- //

// function delete tablok item
function DeleteBarangTablok($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["del-docno-tablok"]));

    $sql_line = "DELETE FROM st_dpd_detail WHERE docno_st_dpd_detail = '$id'";
    mysqli_query($conn, $sql_line);

    $sql_gwdpd = "DELETE FROM pertemanan_plano WHERE id_tablok = '$id'";
    mysqli_query($conn, $sql_gwdpd);
    
    return mysqli_affected_rows($conn);

}
// End function delete line rak

// ---------------------------- //

function DeleteCheckTablok($data) {

    global $conn;

    $id = $data["check_id_tablok"];

    foreach ($id as $row)  {
        $sql = "DELETE FROM pertemanan_plano WHERE id_tablok = '$row'";
        mysqli_query($conn, $sql);
    }

    foreach ($id as $row)  {
        $sql = "DELETE FROM st_dpd_detail WHERE docno_st_dpd_detail = '$row'";
        mysqli_query($conn, $sql);
    }

    return mysqli_affected_rows($conn);
}

// ---------------------------- //

// function proses tablok
function ProsesBarangTablok($data) {

    global $conn;

    // Input data post
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-tablok"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-tablok"]));
    $no_id = autonum(6, 'id_st_dpd', 'st_dpd_head');
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-tablok"]));
    $ket = htmlspecialchars(strtoupper($data["ket-tablok"]));
    $tgl = date("Y-m-d H:i:s");
    $ofdep = $office.$dept;

    mysqli_query($conn, "INSERT INTO st_dpd_head (id_st_dpd, date_st_dpd, req_st_dpd, offdep_st_dpd, ket_st_dpd) VALUES ('$no_id', '$tgl', '$user', '$ofdep', '$ket')");

    mysqli_query($conn, "UPDATE st_dpd_detail SET id_st_dpd_head = '$no_id' WHERE id_st_dpd_head IS NULL");

    $encptbno = encrypt($no_id);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTPTBLK'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-pengajuan-tablok.php?tbno=".$encptbno."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //


// function approve tablok
function ApproveBarangTablok($data) {

    global $conn;

    // Update data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-tablok"]));
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, $data["plu-tablok"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-tablok"]));

    mysqli_query($conn, "UPDATE st_dpd_head SET pic_st_dpd = '$user', pluid_st_dpd = '$plu' WHERE id_st_dpd = '$id'");

    mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo' WHERE id_st_dpd = '$id'");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert data master mobil
function InsertMobil($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page-mobil"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-mobil"]));
    $nomor = htmlspecialchars(mysqli_real_escape_string($conn, $data["no-mobil"]));
    $polisi = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["no-polisi"])));
    $type = htmlspecialchars(mysqli_real_escape_string($conn, $data["type-mobil"]));
    $jenis = htmlspecialchars(mysqli_real_escape_string($conn, $data["jenis-mobil"]));

    // Check nomor di database
    $result = mysqli_query($conn, "SELECT office_mobil, no_mobil FROM mobil WHERE office_mobil ='$office' AND jenis_mobil = '$jenis' AND no_mobil ='$nomor'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Kendaraan Jenis ".$jenis." Nomor ".$nomor." Telah Terdaftar", "error", "$page");
        return false;

    }

    if(strlen($nomor) != 3) {

        $GLOBALS['alert'] = array("Gagal!", "Nomor Kendaraan Tidak Boleh Lebih Atau Kurang Dari 3 Digit Angka", "error", "$page");
        return false;

    }

    mysqli_query($conn, "INSERT INTO mobil (office_mobil, jenis_mobil, no_mobil, nopol_mobil, type_kode_mobil) VALUES ('$office', '$jenis', '$nomor', '$polisi', '$type')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update master mobil
function UpdateMobil($data) {

    global $conn;

    $page = htmlspecialchars($data["page-mobil"]);
    $id = mysqli_real_escape_string($conn, $data["id-mobil"]);
    $office = mysqli_real_escape_string($conn, $data["office-mobil"]);
    $jenis = mysqli_real_escape_string($conn, $data["jenis-mobil"]);
    $type = mysqli_real_escape_string($conn, $data["type-mobil"]);
    $nomorold = htmlspecialchars(mysqli_real_escape_string($conn, $data["nomor-mobilold"]));
    $nomor = htmlspecialchars(mysqli_real_escape_string($conn, $data["nomor-mobil"]));
    $nopol = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["nopol-mobil"])));

    if ($nomorold != $nomor) {
        $result = mysqli_query($conn, "SELECT office_mobil, no_mobil FROM mobil WHERE office_mobil ='$office' AND no_mobil ='$nomor'");
    
        if(mysqli_fetch_assoc($result)) {
    
            $GLOBALS['alert'] = array("Gagal!", "Kendaraan Nomor ".$nomor." Telah Terdaftar", "error", "$page");
            return false;
    
        }
    }

    if(strlen($nomor) != 3) {

        $GLOBALS['alert'] = array("Gagal!", "Nomor Kendaraan Tidak Boleh Lebih Atau Kurang Dari 3 Digit Angka", "error", "$page");
        return false;

    }
    
    mysqli_query($conn, "UPDATE mobil SET jenis_mobil = '$jenis', type_kode_mobil = '$type', no_mobil = '$nomor', nopol_mobil = '$nopol' WHERE id_mobil = '$id'");

    return mysqli_affected_rows($conn);

}
// End function edit

// ---------------------------- //

// function delete nomor mobil
function DeleteMobil($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["id-mobil"]);
    
    mysqli_query($conn, "DELETE FROM mobil WHERE id_mobil = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// Function insert data layout lokasi apar
function insert_layapar($data) {

    global $conn;

    // Input data post
    $lid = htmlspecialchars(mysqli_real_escape_string($conn, $data["kodelayout"]));
    $lname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["lokasiname"])));
    $loffice = mysqli_real_escape_string($conn, $data["officename"]);

    if (strlen($lid) == 3) {
    
        // kode layout apar
        $codelay = "A-";

        // menmbahkan kode layot id dengan number baru pada func auto id
        $newid = $codelay.$lid;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO layout_apar (id_layout, id_office_layout, layout_name) VALUES ('$newid', '$loffice', '$lname')");

        return mysqli_affected_rows($conn);

    }
    else {

        // Check layout name di database
        $result = mysqli_query($conn, "SELECT layout_name FROM layout_apar WHERE layout_name ='$lname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['alert'] = array("<strong>Gagal!</strong> Nama lokasi layout ".$lname." sudah ada.", "danger");
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO layout_apar (id_layout, id_office_layout, layout_name) VALUES ('$lid', '$loffice', '$lname')");

        return mysqli_affected_rows($conn);

    }

}
// End function insert data layout apar

// function delete layout apar
function delete_layapar($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["idlayout"]);
    
    mysqli_query($conn, "DELETE FROM layout_apar WHERE id_layout = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// Function Insert KKSO Apar
function InsertKKSO_Apar($data) {

    global $conn;

    $page = $_POST["page-so"];
    $noso = $_POST["no-so"];
    $user = htmlspecialchars(mysqli_real_escape_string($conn, substr($data["petugas-so"], 0, 10)));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office-so"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dept-so"]));
    $tgl = date("Y-m-d");

    $query_status = mysqli_query($conn, "SELECT * FROM head_so_apar WHERE office_head_so_apar = '$office' AND dept_head_so_apar = '$dept' AND status_head_so_apar = 'N'");

    if(mysqli_fetch_assoc($query_status)) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat data SO yang belum diselesaikan", "error", "$page");
        return false;
    }

    mysqli_query($conn, "INSERT INTO head_so_apar (id_head_so_apar, office_head_so_apar, dept_head_so_apar, date_head_so_apar, user_head_so_apar) VALUES ('$noso', '$office', '$dept', '$tgl', '$user')");

    $query_layout = mysqli_query($conn, "SELECT id_layout FROM layout_apar WHERE id_office_layout = '$office' AND dept_layout_apar = '$dept'");
    
    if(mysqli_num_rows($query_layout) > 0) {
        while($data_layout = mysqli_fetch_assoc($query_layout)) {
            $id_layout = $data_layout["id_layout"];

            // Insert data to database
            mysqli_query($conn, "INSERT INTO so_apar (id_head_so_apar, posisi_so_apar) VALUES ('$noso', '$id_layout')");
        }
    }

    return mysqli_affected_rows($conn);

}
// End function Insert KKSO

// ---------------------------- //

// function delete draft KKSO apar
function DeleteKKSO_Apar($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["del-noso"]);
    
    mysqli_query($conn, "DELETE FROM so_apar WHERE id_head_so_apar = '$no_so'");
    mysqli_query($conn, "DELETE FROM head_so_apar WHERE id_head_so_apar = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function delete draft KKSO

// ---------------------------- //

// function insert data opname apar
function UpdateSOApar($data) {

    global $conn;

    // Input data post
    $id = $data["upd-idso"];
    $fisik = $data["upd-fisiksoapar"];
    $kapasitas = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-kapsoapar"]) ? $data["upd-kapsoapar"] : NULL));
    $isi = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-isisoapar"]) ? $data["upd-isisoapar"] : NULL));
    $expired = htmlspecialchars(mysqli_real_escape_string($conn, $data["upd-expsoapar"]));
    $indikator = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-indikatorsoapar"]) ? $data["upd-indikatorsoapar"] : NULL));
    $bracket = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-bracketsoapar"]) ? $data["upd-bracketsoapar"] : NULL));
    $label = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-labelsoapar"]) ? $data["upd-labelsoapar"] : NULL));
    $checklist = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-checksoapar"]) ? $data["upd-checksoapar"] : NULL));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, isset($data["upd-ketsoapar"]) ? strtoupper($data["upd-ketsoapar"]) : NULL));

    if ($fisik == "Y") {
        mysqli_query($conn, "UPDATE so_apar SET berat_so_apar = '$kapasitas', jenis_so_apar = '$isi', expired_so_apar = '$expired', indikator_so_apar = '$indikator', bracket_so_apar = '$bracket', label_so_apar = '$label', checklist_so_apar = '$checklist', ket_so_apar = '$ket' WHERE id_so_apar = '$id'");
    } else {
        mysqli_query($conn, "UPDATE so_apar SET berat_so_apar = NULL, jenis_so_apar = NULL, expired_so_apar = NULL, indikator_so_apar = NULL, bracket_so_apar = '$bracket', label_so_apar = '$label', checklist_so_apar = '$checklist', ket_so_apar = '$ket' WHERE id_so_apar = '$id'");
    }

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function Update / Reset SO Apar
function ResetSOApar($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["reset-noso"]);
    
    mysqli_query($conn, "UPDATE so_apar SET berat_so_apar = NULL, merk_so_apar = NULL, jenis_so_apar = NULL, expired_so_apar = NULL, jenis_so_apar = NULL, indikator_so_apar = NULL, bracket_so_apar = NULL, label_so_apar = NULL, checklist_so_apar = NULL, ket_so_apar = NULL WHERE id_head_so_apar = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function Update / Reset SO Apar

// ---------------------------- //

// function Update SO Apar
function DeleteSOApar($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["reset-lokasi"]);
    
    mysqli_query($conn, "UPDATE so_apar SET berat_so_apar = NULL, merk_so_apar = NULL, jenis_so_apar = NULL, expired_so_apar = NULL, jenis_so_apar = NULL, indikator_so_apar = NULL, bracket_so_apar = NULL, label_so_apar = NULL, checklist_so_apar = NULL, ket_so_apar = NULL WHERE id_so_apar = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function Update SO Apar

// ---------------------------- //

// function Finish SO Apar
function FinishSOApar($data) {

    global $conn;

    $page = $data["page-noso"];
    $page_scs = $data["pagesuccess-noso"];
    $no_so = $data["finish-noso"];
    
    $sql = "SELECT * FROM so_apar WHERE id_head_so_apar = '$no_so'";
    $query = mysqli_query($conn, $sql);
    while($data = mysqli_fetch_assoc($query)){

        if ($data["bracket_so_apar"] === NULL && $data["label_so_apar"] === NULL && $data["checklist_so_apar"] === NULL) {
            $GLOBALS['alert'] = array("Gagal!", "Terdapat lokasi yang belum dilakukan opname / belum input data so", "error", "$page");
            return false;
        }

    }

    mysqli_query($conn, "UPDATE head_so_apar SET status_head_so_apar = 'Y' WHERE id_head_so_apar = '$no_so'");

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    $_SESSION["ALERT"] = array("Success!", "Data SO ".$no_so." Berhasil di Posting", "success", "$page_scs");

    return mysqli_affected_rows($conn);

}
// End function Finish SO Apar

// ---------------------------- //

// function insert data btb barang
function InsertBarangOut($data) {

    global $conn;

    // Input data post
    $page = $data["page-btb"];
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-btb"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-btb"]));
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, $data["plu-btb"]));
    $date = htmlspecialchars(mysqli_real_escape_string($conn, $data["tgl-btb"]));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qty-btb"]));
    $pic = htmlspecialchars(mysqli_real_escape_string($conn, $data["pic-btb"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-btb"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-btb"])));

    $id = autonum(5, "no_btb_dpd", "btb_dpd");
    
    // kode Pengeluaran
    $code = "O";
    $trans = "-";
    $newid = $code.$id;

    $sql = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'";
    $query = mysqli_query($conn, $sql);

    if ($data = mysqli_fetch_assoc($query)) {
        $saldo = $data["saldo_akhir"];
        if ($qty > $saldo) {
            $GLOBALS['alert'] = array("Gagal!", "Saldo Barang ".$plu." Tidak Tersedia", "error", "$page");
            return false;
        }
        else {
            $stock = ($saldo - $qty);
            // Update Stock from pembelian to database
            mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$stock' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'");
        
        }
    }
    else {
        $GLOBALS['alert'] = array("Gagal!", "Master Saldo Barang ".$plu." Tidak Tersedia", "error", "$page");
        return false;
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO btb_dpd (no_btb_dpd, office_btb_dpd, dept_btb_dpd, tgl_btb_dpd, pluid_btb_dpd, pic_btb_dpd, penerima_btb_dpd, hitung_btb_dpd, qty_awal_btb_dpd, qty_akhir_btb_dpd, ket_btb_dpd) VALUES ('$newid', '$office', '$dept', '$date', '$plu', '$pic', '$user', '$trans', '$saldo', '$qty', '$ket')");

    $encbmb = encrypt($newid);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTBMB'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-bukti-mutasi-barang.php?no=".$encbmb."', '_blank')
    </script>";
    
    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function insert data barang Keluar
function InsertBarangKeluar($data) {

    global $conn;

    // Input data post
    $page = $data["page-brout"];
    $jenis = "M";
    $temp = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluid-brout"]));
    $pluid = substr($temp, 8);
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merk-brout"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipe-brout"])));
    $sn = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["sn-brout"])));
    $aktiva = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["aktiva-brout"])));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-brout"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-brout"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-brout"]));
    $ket = htmlspecialchars(strtoupper($data["keterangan-brout"]));
    $tgl = date("Y-m-d H:i:s");
    $from = $office.$dept;
    $qty = 1;

    // Check sn di database
    $result = mysqli_query($conn, "SELECT sn_sj FROM detail_surat_jalan WHERE from_sj = '$from' AND pluid_sj ='$pluid' AND sn_sj ='$sn' AND head_no_sj IS NULL");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Barang SN ".$sn." telah ditambahkan", "error", "$page");
        return false;

    }

    // Check sn service rusak di database
    $result = mysqli_query($conn, "SELECT sn_sj FROM detail_surat_jalan WHERE from_sj = '$from' AND pluid_sj ='$pluid' AND sn_sj ='$sn' AND status_sj = 'N'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Barang SN ".$sn." sudah pernah dikeluarkan tetapi belum diterima", "error", "$page");
        return false;

    }

    $sql_qsj = "SELECT SUM(qty_sj) AS saldo_qty FROM detail_surat_jalan WHERE jenis_sj = '$jenis' AND from_sj = '$from' AND head_no_sj IS NULL AND pluid_sj = '$pluid'";
    $query_qsj = mysqli_query($conn, $sql_qsj);
    $data_qsj = mysqli_fetch_assoc($query_qsj);

    $totqty_sj = isset($data_qsj["saldo_qty"]) ? $data_qsj["saldo_qty"] : NULL;

    $sql_ms = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'";
    $query_ms = mysqli_query($conn, $sql_ms);
    $data_ms = mysqli_fetch_assoc($query_ms);

    $saldo = isset($data_ms["saldo_akhir"]) ? $data_ms["saldo_akhir"] : NULL;
    $cek_sisa = $saldo - $totqty_sj;

    if($cek_sisa <= 0) {

        $GLOBALS['alert'] = array("Gagal!", "Saldo barang ".$pluid." tidak mencukupi, silahkan so terlebih dahulu", "error", "$page");
        return false;

    }

    mysqli_query($conn, "INSERT INTO detail_surat_jalan (jenis_sj, pluid_sj, from_sj, merk_sj, tipe_sj, sn_sj, at_sj, qty_sj, keterangan_sj) VALUES ('$jenis', '$pluid', '$from', '$merk', '$tipe', '$sn', '$aktiva', '$qty', '$ket')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert data barang Keluar
function InsertBarangKeluarNA($data) {

    global $conn;

    // Input data post
    $page = $data["page-brout"];
    $jenis = "M";
    $temp = htmlspecialchars(mysqli_real_escape_string($conn, $data["pluidna-brout"]));
    $pluid = substr($temp, 8);
    $merk = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["merk-brout"])));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["tipe-brout"])));
    $sn = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["sn-brout"])));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-brout"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-brout"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-brout"]));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $data["qty-brout"]));
    $ket = htmlspecialchars(strtoupper($data["keterangan-brout"]));
    $tgl = date("Y-m-d H:i:s");
    $from = $office.$dept;

    // Check sn di database
    $result = mysqli_query($conn, "SELECT sn_sj FROM detail_surat_jalan WHERE from_sj = '$from' AND pluid_sj ='$pluid' AND head_no_sj IS NULL");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Kode barang ".$pluid." telah ditambahkan", "error", "$page");
        return false;

    }

    $sql_ms = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'";
    $query_ms = mysqli_query($conn, $sql_ms);
    $data_ms = mysqli_fetch_assoc($query_ms);

    $saldo = $data_ms["saldo_akhir"];
    
    if($saldo <= 0) {

        $GLOBALS['alert'] = array("Gagal!", "Barang ".$pluid." tidak memiliki saldo atau stock sama dengan nol, silahkan so terlebih dahulu", "error", "$page");
        return false;

    }

    mysqli_query($conn, "INSERT INTO detail_surat_jalan (jenis_sj, pluid_sj, from_sj, merk_sj, tipe_sj, sn_sj, at_sj, qty_sj, keterangan_sj) VALUES ('$jenis', '$pluid', '$from', '$merk', '$tipe', '$sn', NULL, '$qty', '$ket')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete barang keluar
function DeleteBarangKeluar($data) {

    global $conn;

    $id_sj = mysqli_real_escape_string($conn, $data["del-idsj"]);
    
    mysqli_query($conn, "DELETE FROM detail_surat_jalan WHERE detail_no_sj = '$id_sj'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert proses data barang keluar
function ProsesBarangKeluar($data) {

    global $conn;

    // Input data post
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-brout"]));
    $ofdep_from = htmlspecialchars(mysqli_real_escape_string($conn, $data["from-brout"]));
    $office_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-brout"]));
    $dept_to = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-brout"]));
    $ket_to = htmlspecialchars(strtoupper($data["keterangan-brout"]));
    $tgl = date("Y-m-d H:i:s");
    $date = date("Y-m-d");
    $sts = "N";
    $code = "O";

    $ofdep_to = $office_to.$dept_to;

    $id = "M";
    $idmax = autonum(5, 'no_sj', 'surat_jalan');

    if(strlen($idmax) == 5) {

        // menmbahkan kode referensi dengan number baru pada func auto number
        $nosj = $id.$idmax;

    }
    else {

        $nosj = $id.substr($idmax, 1);

    }

    mysqli_query($conn, "INSERT INTO surat_jalan (no_sj, tanggal_sj, user_sj, asal_sj, tujuan_sj, ket_sj, status_terima_sj) VALUES ('$nosj', '$tgl', '$user', '$ofdep_from', '$ofdep_to', '$ket_to', '$sts')");

    mysqli_query($conn, "UPDATE detail_surat_jalan SET head_no_sj = '$nosj' WHERE jenis_sj = '$id' AND from_sj = '$ofdep_from' AND head_no_sj IS NULL");

    $sql_sj = "SELECT head_no_sj, from_sj, pluid_sj, SUM(qty_sj) AS qty_brg, keterangan_sj FROM detail_surat_jalan WHERE head_no_sj = '$nosj' GROUP BY pluid_sj";
    $query_sj = mysqli_query($conn, $sql_sj);

    if(mysqli_num_rows($query_sj) > 0) {
        while($data_sj = mysqli_fetch_assoc($query_sj)) {
            
            $id = $code.autonum(5, 'no_btb_dpd', 'btb_dpd');
            $ref = $data_sj["head_no_sj"];
            $off = substr($data_sj["from_sj"], 0, 4);
            $dpt = substr($data_sj["from_sj"], 4, 4);
            $plu = $data_sj["pluid_sj"];
            $qty = $data_sj["qty_brg"];
            $ket = $data_sj["keterangan_sj"];

            $sql_ms = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$off' AND ms_id_department = '$dpt' AND pluid = '$plu'";
            $query_ms = mysqli_query($conn, $sql_ms);
            $data_ms = mysqli_fetch_assoc($query_ms);

            $saldo = $data_ms["saldo_akhir"];

            // Insert data to database
            mysqli_query($conn, "INSERT INTO btb_dpd (no_btb_dpd, ref_btb_dpd, office_btb_dpd, dept_btb_dpd, tgl_btb_dpd, pluid_btb_dpd, pic_btb_dpd, penerima_btb_dpd, hitung_btb_dpd, qty_awal_btb_dpd, qty_akhir_btb_dpd, ket_btb_dpd) VALUES ('$id', '$ref', '$off', '$dpt', '$date', '$plu', '$user', '$user', '-', '$saldo', '$qty', '$ket')");
        
            $saldo_akh = ($saldo - $qty);

            mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo_akh' WHERE ms_id_office = '$off' AND ms_id_department = '$dpt' AND pluid = '$plu'");
        }
    }

    $encsj = encrypt($nosj);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTSJK'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-barang-keluar.php?docno=".$encsj."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function Update / Proses Terima Barang Via SJ
function ReceiveBarangMasuk($data) {

    global $conn;

    $ref = mysqli_real_escape_string($conn, $data["modifref-barang"]);
    $no_sj = mysqli_real_escape_string($conn, $data["terima-barang"]);
    $pluid = mysqli_real_escape_string($conn, $data["pluid-barang"]);
    $sn = mysqli_real_escape_string($conn, $data["sn-barang"]);
    $dat = mysqli_real_escape_string($conn, $data["dat-barang"]);
    $user = mysqli_real_escape_string($conn, $data["user-barang"]);
    $posisi = mysqli_real_escape_string($conn, strtoupper($data["posisi-barang"]));
    $terima = mysqli_real_escape_string($conn, strtoupper($data["ket-barang"]));
    $tgl = date("Y-m-d");
    $tglterima = date("Y-m-d H:i:s");
    $code = "I";
    
    $office = substr(mysqli_real_escape_string($conn, $data["offdep-barang"]), 0, 4);
    $dept = substr(mysqli_real_escape_string($conn, $data["offdep-barang"]), 4, 4);

    $sql_st = "SELECT pluid_sj, sn_sj, at_sj FROM detail_surat_jalan WHERE head_no_sj = '$no_sj' AND at_sj IS NOT NULL";
    $query_st = mysqli_query($conn, $sql_st);

    while($data_st = mysqli_fetch_assoc($query_st)) {

        $plu_st = $data_st["pluid_sj"];
        $sn_st = $data_st["sn_sj"];
        $at_st = $data_st["at_sj"];

        mysqli_query($conn, "UPDATE barang_assets SET ba_id_office = '$office', ba_id_department = '$dept', posisi = '$posisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$tglterima' WHERE pluid = '$plu_st' AND sn_barang = '$sn_st' AND no_at = '$at_st'");
    
    }

    $sql_sj = "SELECT head_no_sj, from_sj, pluid_sj, qty_sj, SUM(qty_sj) AS tot_brg FROM detail_surat_jalan WHERE head_no_sj = '$no_sj' GROUP BY pluid_sj";
    $query_sj = mysqli_query($conn, $sql_sj);

    if(mysqli_num_rows($query_sj) > 0) {
        
        while($data_sj = mysqli_fetch_assoc($query_sj)) {
            
            $id = $code.autonum(5, 'no_btb_dpd', 'btb_dpd');
            $ref = $data_sj["head_no_sj"];
            $plu = $data_sj["pluid_sj"];
            $brg = $data_sj["tot_brg"];
            $qty = $data_sj["qty_sj"];

            $sql_ms = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'";
            $query_ms = mysqli_query($conn, $sql_ms);
            $data_ms = mysqli_fetch_assoc($query_ms);
            
            $saldoawal = isset($data_ms["saldo_akhir"]) ? $data_ms["saldo_akhir"] : 0;

            mysqli_query($conn, "INSERT INTO btb_dpd (no_btb_dpd, ref_btb_dpd, office_btb_dpd, dept_btb_dpd, tgl_btb_dpd, pluid_btb_dpd, pic_btb_dpd, penerima_btb_dpd, hitung_btb_dpd, qty_awal_btb_dpd, qty_akhir_btb_dpd, ket_btb_dpd) VALUES ('$id', '$ref', '$office', '$dept', '$tgl', '$plu', '$user', '$user', '+', '$saldoawal', '$qty', '$terima')");

            if ($data_ms) {
                
                $saldo = $data_ms["saldo_akhir"];
                $saldo_akh = ($saldo + $brg);
    
                mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo_akh' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'");    
            }
            else {
                mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid, saldo_akhir) VALUES ('$office', '$dept', '$plu', '$brg')");
            }

        }
    }
        
    mysqli_query($conn, "UPDATE surat_jalan SET ket_terima_sj = '$terima', tgl_terima_sj = '$tglterima', status_terima_sj = 'Y' WHERE no_sj = '$no_sj'");
    mysqli_query($conn, "UPDATE detail_surat_jalan SET status_sj = 'Y', penerima_sj = '$user', tgl_penerimaan = '$tgl' WHERE head_no_sj = '$no_sj'");

    return mysqli_affected_rows($conn);

}
// function Update / Proses Terima Barang Service

// ---------------------------- //

// function Update Service Barang By Check
function ReceiveBarangCheck($data) {

    global $conn;

    // Input data post
    $page = $data["page-servbarang"];
    $id = $data["checkidsj"];
    $date = date("Y-m-d");
    $ref = mysqli_real_escape_string($conn, $data["modifref-servbarang"]);
    $user = htmlspecialchars(isset($data["user-servbarang"]) ? $data["user-servbarang"] : NULL);
    $kondisi = htmlspecialchars(isset($data["kond-servbarang"]) ? $data["kond-servbarang"] : NULL);
    $posisi = htmlspecialchars(strtoupper($data["posisi-servbarang"]));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-servbarang"])));
    $id_ba = implode(", ", $id);
    $prosesdate = date("Y-m-d H:i:s");

    if($user == "" || empty($user)) {
        $GLOBALS['alert'] = array("Gagal!", "Data penerima belum ada yang dipilih", "error", "$page");
        return false;
    }

    if ($kondisi == "" || empty($kondisi)) {
        $GLOBALS['alert'] = array("Gagal!", "Data status perbaikan belum ada yang dipilih", "error", "$page");
        return false;
    }

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    foreach ($id as $id_sj)  {
        $id_sj = str_replace("'", "", $id_sj);
        mysqli_query($conn, "UPDATE detail_surat_jalan SET status_sj = 'Y', kondisi_perbaikan = '$kondisi', penerima_sj = '$user', tgl_penerimaan = '$date', ket_penerimaan_sj = '$ket' WHERE detail_no_sj = '$id_sj'");
    
        $encnoref = encrypt($id_sj);

        echo "<script type=\"text/javascript\">
        window.open('reporting/report-hppb.php?noref=".$encnoref."', '_blank')
        </script>";

    }

    $sql_serv = "SELECT pluid_sj, sn_sj, at_sj, from_sj FROM detail_surat_jalan WHERE detail_no_sj IN ($id_ba)";
    $query_serv = mysqli_query($conn, $sql_serv);

    while($data_serv = mysqli_fetch_assoc($query_serv)) {
        $plu = $data_serv["pluid_sj"];
        $sn = $data_serv["sn_sj"];
        $at = $data_serv["at_sj"];
        $office = substr($data_serv["from_sj"], 0, 4);
        $dept = substr($data_serv["from_sj"], 4);

        mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', posisi = '$posisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$prosesdate' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND sn_barang = '$sn' AND no_at = '$at'");
    }

    return mysqli_affected_rows($conn);
}
// function Update Service Barang By Check

// ---------------------------- //

// Function Delete Barang Service
function CancelBarang($data) {

    global $conn;

    $no = mysqli_real_escape_string($conn, $data["no-brgserv"]);
    $id = mysqli_real_escape_string($conn, $data["id-brgserv"]);

    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-brgserv"]);
    $pluid = mysqli_real_escape_string($conn, $data["plu-brgserv"]);
    $snsj = mysqli_real_escape_string($conn, $data["sn-brgserv"]);
    $at = mysqli_real_escape_string($conn, $data["at-brgserv"]);
    
    mysqli_query($conn, "DELETE FROM detail_surat_jalan WHERE detail_no_sj = '$id'");
    mysqli_query($conn, "UPDATE barang_assets SET user_asset = NULL, referensi_asset = NULL, kondisi = '$kondisi' WHERE pluid = '$pluid' AND sn_barang = '$snsj' AND no_at = '$at'");

    $query_head_sj = mysqli_query($conn, "SELECT head_no_sj FROM detail_surat_jalan WHERE head_no_sj = '$no'");

    if(mysqli_num_rows($query_head_sj) === 0) {
        mysqli_query($conn, "DELETE FROM surat_jalan WHERE no_sj = '$no'");
    }

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function Update / Proses Pemusnahan Aktiva
function ProsesMusnahBarang($data) {

    global $conn;

    $page = $data["page-prosmusnah"];
    $offdep = htmlspecialchars($data["offdep-p3at"]);
    $office = substr($offdep, 0, 4);
    $dept = substr($offdep, 4, 4);
    $kondisi = htmlspecialchars($data["id-kondisi"]);
    $sp3at = htmlspecialchars($data["status-p3at"]);
    $user = htmlspecialchars($data["user-prosmusnah"]);
    $ref = htmlspecialchars($data["ref-p3at"]);
    $date = date("Y-m-d H:i:s");

    $id = htmlspecialchars($data["id-p3at"]);
    $p3at = htmlspecialchars($data["nomor-p3at"]);
    $plu = htmlspecialchars($data["id-plu"]);
    $sn = htmlspecialchars($data["id-sn"]);
    $at = htmlspecialchars($data["id-at"]);
    $nomor = htmlspecialchars($data["id-pemusnahan"]);
    $tgl = htmlspecialchars($data["tgl-pemusnahan"]);

    if($nomor == "" || empty($nomor)) {
        $GLOBALS['alert'] = array("Gagal!", "Nomor Bukti Pemusnahan Tidak Boleh Kosong", "error", "$page");
        return false;
    }

    if ($tgl == "" || empty($tgl)) {
        $GLOBALS['alert'] = array("Gagal!", "Tanggal Bukti Pemusnahan Belum Dipilih", "error", "$page");
        return false;
    }

    mysqli_query($conn, "UPDATE detail_p3at SET nomor_musnah = '$nomor', tgl_approve = '$tgl' WHERE id_detail_p3at = '$id'");

    mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', no_lambung = NULL, user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND sn_barang = '$sn' AND no_at = '$at'");

    $query_p3at = mysqli_query($conn, "SELECT nomor_musnah, tgl_approve FROM detail_p3at WHERE id_head_p3at = '$p3at' AND nomor_musnah IS NULL AND tgl_approve IS NULL");
    
    if(mysqli_num_rows($query_p3at) === 0) {
        mysqli_query($conn, "UPDATE p3at SET status_p3at = '$sp3at' WHERE id_p3at = '$p3at'");
    }

    $query_asset = mysqli_query($conn, "SELECT COUNT(no_at) AS total_asset FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND no_at = '$at' AND kondisi = '$kondisi'");
    $data_asset = mysqli_fetch_assoc($query_asset);
    
    $query_dat = mysqli_query($conn, "SELECT qty_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$at'");
    $data_dat = mysqli_fetch_assoc($query_dat);

    if ($data_asset["total_asset"] == isset($data_dat["qty_dat"]) ? $data_dat["qty_dat"] : NULL) {
        mysqli_query($conn, "UPDATE dat SET status_dat = 'N' WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$at'");
    }

    return mysqli_affected_rows($conn);

}
// End function Update / Proses Pemusnahan Aktiva

// ---------------------------- //

// function Update / Proses Pemusnahan Aktiva By Checkbox
function ProsesMusnahBarangCheck($data) {

    global $conn;

    $page = $data["page-prosmusnah"];
    $kondisi = htmlspecialchars($data["kon-chkprosmusnah"]);
    $sp3at = htmlspecialchars($data["sts-chkprosmusnah"]);
    $user = htmlspecialchars($data["user-chkprosmusnah"]);
    $ref = htmlspecialchars($data["ref-chkprosmusnah"]);
    $date = date("Y-m-d H:i:s");

    $nomor = htmlspecialchars($data["bukti-chkprosmusnah"]);
    $tgl = htmlspecialchars($data["tgl-chkprosmusnah"]);

    if($nomor == "" || empty($nomor)) {
        $GLOBALS['alert'] = array("Gagal!", "Nomor Bukti Pemusnahan Tidak Boleh Kosong", "error", "$page");
        return false;
    }

    if ($tgl == "" || empty($tgl)) {
        $GLOBALS['alert'] = array("Gagal!", "Tanggal Bukti Pemusnahan Belum Dipilih", "error", "$page");
        return false;
    }

    $idmusnah = $data["checkidmusnah"];

    $docno_p3at = array();
    foreach ($idmusnah as $arrdata)  {

        $query_p3at = mysqli_query($conn, "SELECT * FROM detail_p3at WHERE id_detail_p3at = '$arrdata'");
        $data_p3at = mysqli_fetch_assoc($query_p3at);

        $p3at = $data_p3at["id_head_p3at"];
        $office = substr($data_p3at["offdep_p3at"], 0, 4);
        $dept = substr($data_p3at["offdep_p3at"], 4, 4);
        $plu = $data_p3at["pluid_p3at"];
        $sn = $data_p3at["sn_p3at"];
        $at = $data_p3at["at_p3at"];

        mysqli_query($conn, "UPDATE detail_p3at SET nomor_musnah = '$nomor', tgl_approve = '$tgl' WHERE id_detail_p3at = '$arrdata'");

        mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', no_lambung = NULL, user_asset = '$user', referensi_asset = '$ref', modified_asset = '$date' WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$plu' AND sn_barang = '$sn' AND no_at = '$at'");
    
        $query_asset = mysqli_query($conn, "SELECT COUNT(no_at) AS total_asset FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND no_at = '$at' AND kondisi = '$kondisi'");
        $data_asset = mysqli_fetch_assoc($query_asset);
        
        $query_dat = mysqli_query($conn, "SELECT qty_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$at'");
        $data_dat = mysqli_fetch_assoc($query_dat);
    
        if ($data_asset["total_asset"] == isset($data_dat["qty_dat"]) ? $data_dat["qty_dat"] : NULL) {
            mysqli_query($conn, "UPDATE dat SET status_dat = 'N' WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$at'");
        }
        
        $docno_p3at[] = $p3at;
    }

    $arrayfix = array_unique($docno_p3at);
    foreach ($arrayfix as $arrmusnah)  {

        $query_headp3at = mysqli_query($conn, "SELECT id_head_p3at FROM detail_p3at WHERE id_head_p3at = '$arrmusnah' AND nomor_musnah IS NULL AND tgl_approve IS NULL");

        if(mysqli_num_rows($query_headp3at) === 0) {
            
            mysqli_query($conn, "UPDATE p3at SET status_p3at = '$sp3at' WHERE id_p3at = '$arrmusnah'");
        }
    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function proses barcode bronjong
function CetakBarcodeBronjong($data) {

    global $conn;

    // Input data post
    $office = mysqli_real_escape_string($conn, $data["office-barcode"]);
    $dept = mysqli_real_escape_string($conn, $data["dept-barcode"]);
    $urut = mysqli_real_escape_string($conn, $data["urut-barcode"]);
    $no_barcode = mysqli_real_escape_string($conn, $data["nomor-barcode"]);
    
    $docno = autonum(7, 'docno_bb', 'barcode_bronjong');
    $codeNumber = substr($no_barcode, 0, 8);
    $lastFiveDigits = substr($no_barcode, -5);

    $startingNumber = (int)$lastFiveDigits;

    for($i = 0; $i < $urut; $i++) {

        $newNumber = str_pad($startingNumber + $i, 5, '0', STR_PAD_LEFT);
        $newId = $codeNumber . $newNumber . "\n";
        
        // Insert to database
        $query_bb = "INSERT INTO barcode_bronjong (docno_bb, office_bb, dept_bb, nomor_bb) VALUES ('$docno', '$office', '$dept', '$newId')";
        mysqli_query($conn, $query_bb);
    }

    $encbb = encrypt($docno);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTBB'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-barcode-bulky.php?no=".$encbb."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function cetak barcode bronjong

// ---------------------------- //

// function proses BKSE
function ProsesBKSE($data) {

    global $conn;

    // Input data post
    $ref = mysqli_real_escape_string($conn, $data["modifref-bkse"]);
    $office = mysqli_real_escape_string($conn, $data["office-bkse"]);
    $dept = mysqli_real_escape_string($conn, $data["dept-bkse"]);
    $div = mysqli_real_escape_string($conn, $data["div-bkse"]);
    $tgl = mysqli_real_escape_string($conn, $data["tgl-bkse"]);
    $barang = mysqli_real_escape_string($conn, $data["barang-bkse"]);
    $pluid = substr($barang, 8);
    $sn = mysqli_real_escape_string($conn, $data["sn-bkse"]);
    $merk = mysqli_real_escape_string($conn, $data["merk-bkse"]);
    $tipe = mysqli_real_escape_string($conn, $data["tipe-bkse"]);
    $at = mysqli_real_escape_string($conn, $data["aktiva-bkse"]);
    $no = mysqli_real_escape_string($conn, $data["nomor-bkse"]);
    $penempatan = mysqli_real_escape_string($conn, strtoupper($data["penempatan-bkse"]));
    $kerusakan = mysqli_real_escape_string($conn, strtoupper($data["kerusakan-bkse"]));
    $posisi = mysqli_real_escape_string($conn, strtoupper($data["posisi-bkse"]));
    $id = mysqli_real_escape_string($conn, $data["id-bkse"]);
    $user = mysqli_real_escape_string($conn, $data["user-bkse"]);
    $pemakai = mysqli_real_escape_string($conn, strtoupper($data["pemakai-bkse"]));
    $prosesdate = date("Y-m-d H:i:s");

    $idoffice = substr($barang, 0, -14);
    $iddept = substr($barang, 4, -10);
    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-bkse"]);

    // Insert to database
    $query_bkse = "INSERT INTO bkse (nomor_bkse, office_bkse, dept_bkse, div_bkse, tgl_bkse, pluid_bkse, merk_bkse, tipe_bkse, sn_bkse, at_bkse, no_bkse, penempatan_bkse, kerusakan_bkse, user_bkse, pemakai_bkse) VALUES ('$id', '$office', '$dept', '$div', '$tgl', '$pluid', '$merk', '$tipe', '$sn', '$at', '$no', '$penempatan', '$kerusakan', '$user', '$pemakai')";
    mysqli_query($conn, $query_bkse);
    
    mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', posisi = '$posisi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$prosesdate' WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND sn_barang = '$sn'");

    $encbkse = encrypt($id);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTBKSE'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-bkse.php?no=".$encbkse."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function BKSE

// ---------------------------- //

// function proses BKB
function ProsesBKB($data) {

    global $conn;

    // Input data post
    $ref = mysqli_real_escape_string($conn, $data["modifref-bkb"]);
    $office = mysqli_real_escape_string($conn, $data["office-bkb"]);
    $dept = mysqli_real_escape_string($conn, $data["dept-bkb"]);
    $div = mysqli_real_escape_string($conn, $data["div-bkb"]);
    $tgl = mysqli_real_escape_string($conn, $data["tgl-bkb"]);
    $barang = mysqli_real_escape_string($conn, $data["barang-bkb"]);
    $pluid = substr($barang, 8);
    $sn = mysqli_real_escape_string($conn, $data["sn-bkb"]);
    $merk = mysqli_real_escape_string($conn, $data["merk-bkb"]);
    $tipe = mysqli_real_escape_string($conn, $data["tipe-bkb"]);
    $at = mysqli_real_escape_string($conn, $data["aktiva-bkb"]);
    $no = mysqli_real_escape_string($conn, $data["nomor-bkb"]);
    $lokasi = mysqli_real_escape_string($conn, strtoupper($data["lokasi-bkb"]));
    $ket = mysqli_real_escape_string($conn, strtoupper($data["ket-bkb"]));
    $id = mysqli_real_escape_string($conn, $data["id-bkb"]);
    $user = mysqli_real_escape_string($conn, $data["user-bkb"]);
    $prosesdate = date("Y-m-d H:i:s");

    $idoffice = substr($barang, 0, -14);
    $iddept = substr($barang, 4, -10);
    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-bkb"]);

    // Insert to database
    $query_bkb = "INSERT INTO bkb (nomor_bkb, office_bkb, dept_bkb, div_bkb, tgl_bkb, pluid_bkb, merk_bkb, tipe_bkb, sn_bkb, at_bkb, no_bkb, lokasi_bkb, ket_bkb, user_bkb) VALUES ('$id', '$office', '$dept', '$div', '$tgl', '$pluid', '$merk', '$tipe', '$sn', '$at', '$no', '$lokasi', '$ket', '$user')";
    mysqli_query($conn, $query_bkb);
    
    mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi', posisi = '$lokasi', user_asset = '$user', referensi_asset = '$ref', modified_asset = '$prosesdate' WHERE ba_id_office = '$idoffice' AND ba_id_department = '$iddept' AND pluid = '$pluid' AND sn_barang = '$sn'");

    $encbkb = encrypt($id);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTBKB'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-bkb.php?no=".$encbkb."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function BKB

// ---------------------------- //

// Function Delete P3AT
function CancelP3AT($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["no-p3at"]);
    
    mysqli_query($conn, "DELETE FROM detail_p3at WHERE id_head_p3at = '$id'");
    mysqli_query($conn, "DELETE FROM p3at WHERE id_p3at = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// Function Upload Dokumen BA P3AT
function UploadBAP3AT($data) {

    global $conn;

    $page = $data["page-p3at"];
    $no = htmlspecialchars($data["no-p3at"]);
    $p3at = htmlspecialchars($data["id-p3at"]);
    $status = htmlspecialchars($data["status-p3at"]);
    
    $name  = $_FILES["file-p3at"]["name"];
    $size  = $_FILES["file-p3at"]["size"];
    $error = $_FILES["file-p3at"]["error"];
    $tmp   = $_FILES["file-p3at"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "File dokumen belum ada yang dipilih", "error", "$page");
        return false;

    }

    $ekspdfvalid = ["pdf"];
    $ekspdf = explode('.', $name);
    $ekspdf = strtolower(end($ekspdf));

    if(!in_array($ekspdf, $ekspdfvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload bukan format pdf", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 3000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 3MB", "error", "$page");
        return false;
    
    }

    $file = $p3at;
    $file .= '.';
    $file .= $ekspdf;

    move_uploaded_file($tmp, 'files/p3at/' . $file);

    mysqli_query($conn, "UPDATE p3at SET approve_p3at = '$file', status_p3at = '$status' WHERE no_p3at = '$no' ");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function proses PP atas P3AT
function ProsesPPP3AT($data) {

    global $conn;
    global $timezone;

    // Input data post
    $page_success = $data["page_success"];
    $page = $data["page"];
    $refp3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["noref-p3at"]));
    $sts_p3at = htmlspecialchars(mysqli_real_escape_string($conn, $data["status-p3at"]));
    $sproses = htmlspecialchars(mysqli_real_escape_string($conn, $data["status-ppnb"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-ppnb"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-ppnb"]));
    $officeto = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-to"]));
    $deptto = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-to"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-ppnb"]));
    $tgl = htmlspecialchars($data["tgl-to"]);
    $prosesdate = date("Y-m-d H:i:s");
    $keperluan = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["keperluan-ppnb"]))));
    $ofdep = $office.$dept;
    $noref = autonum(5, 'noref', 'pembelian');
    $tahun = substr($tgl, 0, 4);

    if (!isset($data["barangentry"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data barang belum ada yang ditambahkan", "error", "$page");
        return false;
    }
    else {
        $barang = $data["barangentry"];
    }

    $pros = "Y";
    foreach($barang as $value) {
        $arrdata = array(
            $pluid = substr($value['pluid-ppp3at'], 0, 10),
            $merk = strtoupper($value['merk-ppp3at']),
            $tipe = strtoupper($value['tipe-ppp3at']),
            $qty = $value['qty-ppp3at'],
            $estharga = ($value['harga-ppp3at']*$qty),
            $ket = strtoupper($value['keterangan-ppp3at']),
        );

        $data_pp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'"));
        $stock_bgt = isset($data_pp['use_budget']) ? $data_pp['use_budget'] : 0;
        $qtypp = $stock_bgt+$qty;
    
        mysqli_query($conn, "INSERT INTO detail_pembelian (noref, id_offdep, user_pp, plu_id, merk, tipe, qty, harga_pp, keterangan, proses) VALUES ('$noref', '$ofdep', '$user', '$pluid', '$merk', '$tipe', '$qty', '$estharga', '$ket', '$pros')");
        mysqli_query($conn, "UPDATE budget SET use_budget = '$qtypp' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");    
    }

    $id = 'PPM';
    $dateid = date("d/m/Y");
    
    $ppbno = $id.'/'.$noref.'/'.$dateid;
    // Insert pembelian to database
    $resultppb = "INSERT INTO pembelian (noref, ref_musnah, id_office, id_department, ppid, user, office_to, department_to, proses_date, tgl_pengajuan, status_pp, keperluan) VALUES ('$noref', '$refp3at', '$office', '$dept', '$ppbno', '$user', '$officeto', '$deptto', '$prosesdate', '$tgl', '$sproses', '$keperluan')";
    mysqli_query($conn, $resultppb);

    mysqli_query($conn, "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$sproses', '$user', '$prosesdate')");

    $resultp3at = "UPDATE p3at SET status_p3at = '$sts_p3at', noref_pp = '$ppbno', judul_p3at = '$keperluan' WHERE id_p3at = '$refp3at'";
    mysqli_query($conn, $resultp3at);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    $_SESSION["ALERT"] = array("Success!", "PP Atas Pemusnahan Nomor ".$ppbno." Berhasil di buat", "success", "$page_success");

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function insert data ip segment
function InsertIPSegment($data) {

    global $conn;

    // Input data post
    $office = htmlspecialchars($data["office_iseg"]);
    $dept = htmlspecialchars($data["dept_iseg"]);
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["name_iseg"])));

    mysqli_query($conn, "INSERT INTO ip_segment (office_iseg, dept_iseg, name_iseg) VALUES ('$office', '$dept', '$name')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function Delete Segment IP
function DeleteIPSegment($data) {

    global $conn;

    $page = htmlspecialchars($data["page"]);
    $id = htmlspecialchars($data["id_iseg"]);
    
    // Check segemnt di tabel ip address
    $query = mysqli_query($conn, "SELECT seg_ipad FROM ip_address WHERE seg_ipad = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Segment IP Yang Telah Terdaftar di IP Address Tidak Dapat Di Hapus", "error", "$page");
        return false;
    
    }
    
    mysqli_query($conn, "DELETE FROM ip_segment WHERE id_iseg = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert data ip address
function InsertIPAddress($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page"]);
    $office = htmlspecialchars($data["office_ipad"]);
    $dept = htmlspecialchars($data["dept_ipad"]);
    $seg = htmlspecialchars($data["seg_ipad"]);
    $ip = htmlspecialchars(mysqli_real_escape_string($conn, $data["ip_ipad"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["name_ipad"])));
    $status = htmlspecialchars($data["status_ipad"]);

    // Check id
    $query = mysqli_query($conn, "SELECT ip_ipad FROM ip_address WHERE ip_ipad = '$ip'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "IP Address ".$ip." Telah Terdaftar", "error", "$page");
        return false;
    
    }

    mysqli_query($conn, "INSERT INTO ip_address (office_ipad, dept_ipad, seg_ipad, ip_ipad, name_ipad, status_ipad) VALUES ('$office', '$dept', '$seg', '$ip', '$name', '$status')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function Update IP Address
function UpdateIPAddress($data) {

    global $conn;

    $page = htmlspecialchars($data["page"]);
    $id = htmlspecialchars($data["updid_ipad"]);
    $seg = htmlspecialchars($data["updseg_ipad"]);
    $ipold = htmlspecialchars($data["updipold_ipad"]);
    $ip = htmlspecialchars(mysqli_real_escape_string($conn, $data["updip_ipad"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["updname_ipad"])));
    $status = htmlspecialchars($data["updstatus_ipad"]);

    if ($ipold != $ip) {
        // Check id
        $query = mysqli_query($conn, "SELECT ip_ipad FROM ip_address WHERE ip_ipad = '$ip'");
    
        if(mysqli_fetch_assoc($query)) {

            $GLOBALS['alert'] = array("Gagal!", "IP Address ".$ip." Telah Terdaftar", "error", "$page");
            return false;
        
        }
    }

    mysqli_query($conn, "UPDATE ip_address SET seg_ipad = '$seg', ip_ipad = '$ip', name_ipad = '$name', status_ipad = '$status' WHERE id_ipad = '$id'");

    return mysqli_affected_rows($conn);

}
// End function Update IP Address

// ---------------------------- //

// Function Delete IP Address
function DeleteIPAddress($data) {

    global $conn;

    $id = htmlspecialchars($data["delid_ipad"]);
    
    mysqli_query($conn, "DELETE FROM ip_address WHERE id_ipad = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert generate serial number
function GenerateSNBarang($data) {

    global $conn;

    // Input data post
    $office = htmlspecialchars($data["office-sn"]);
    $dept = htmlspecialchars($data["dept-sn"]);
    $pluid = htmlspecialchars($data["barang-sn"]);
    $nomor = htmlspecialchars($data["nomor-sn"]);

    $sql = "INSERT INTO serial_number (office_serial_number, dept_serial_number, pluid_serial_number, nomor_serial_number) VALUES ('$office', '$dept', '$pluid', '$nomor')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert area lokasi cctv
function insert_areacctv($data) {

    global $conn;

    // Input data post
    $office = htmlspecialchars($data["office-area"]);
    $dept = htmlspecialchars($data["dept-area"]);
    $div = htmlspecialchars($data["divisi-area"]);
    $kode = htmlspecialchars(strtoupper($data["kode-area"]));
    $dvr = htmlspecialchars($data["dvr-area"]);
    $chl = htmlspecialchars($data["channel-area"]);

    $sql = "INSERT INTO area_cctv (office_area_cctv, dept_area_cctv, divisi_area_cctv, kode_area_cctv, ip_area_cctv, ch_area_cctv) VALUES ('$office', '$dept', '$div', '$kode', '$dvr', '$chl')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function Delete area lokasi cctv
function delete_areacctv($data) {

    global $conn;

    $id = $data["id-area"];

    // Check nama barang di database
    $result = mysqli_query($conn, "SELECT * FROM layout_cctv WHERE head_id_area_cctv = '$id'");

    if(mysqli_fetch_assoc($result)) {
        $GLOBALS['alert'] = array("<strong>Gagal!</strong> DVR CCTV ".$id." tidak dapat di hapus, karena sudah terdaftar di layout", "danger");
        return false;

    }
    
    mysqli_query($conn, "DELETE FROM area_cctv WHERE id_area_cctv = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert layout cctv
function insert_laycctv($data) {

    global $conn;

    // Input data post
    $area = htmlspecialchars($data["area-cctv"]);
    $bagian = htmlspecialchars($data["bagian-cctv"]);
    $nomor = htmlspecialchars($data["number-cctv"]);
    $lokasi = mysqli_real_escape_string($conn, strtoupper($data["lokasi-cctv"]));
    $channel = htmlspecialchars($data["channel-cctv"]);
    $jenis = htmlspecialchars($data["jenis-cctv"]);

    $sql = "INSERT INTO layout_cctv (head_id_area_cctv, kode_head_bag_cctv, no_lay_cctv, penempatan_lay_cctv, channel_lay_cctv, jenis_lay_cctv) VALUES ('$area', '$bagian', '$nomor', '$lokasi', '$channel', '$jenis')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update layout cctv
function update_laycctv($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-cctv"]);
    $area = htmlspecialchars($data["area-cctv"]);
    $bagian = htmlspecialchars($data["bagian-cctv"]);
    $nomor = htmlspecialchars($data["number-cctv"]);
    $lokasi = mysqli_real_escape_string($conn, strtoupper($data["lokasi-cctv"]));
    $channel = htmlspecialchars($data["channel-cctv"]);
    $jenis = htmlspecialchars($data["jenis-cctv"]);

    mysqli_query($conn, "UPDATE layout_cctv SET head_id_area_cctv = '$area', kode_head_bag_cctv = '$bagian', no_lay_cctv = '$nomor', penempatan_lay_cctv = '$lokasi', channel_lay_cctv = '$channel', jenis_lay_cctv = '$jenis' WHERE id_lay_cctv = '$id'");

    return mysqli_affected_rows($conn);

}
// End function update layout cctv

// ---------------------------- //

// Function delete layout cctv
function delete_laycctv($data) {

    global $conn;

    $id = $data["id-cctv"];
    
    mysqli_query($conn, "DELETE FROM layout_cctv WHERE id_lay_cctv = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert kategori pelanggaran cctv
function InsertCtgPlg($data) {

    global $conn;

    // Input data post
    $cat = htmlspecialchars(strtoupper($data["name-cat"]));

    $sql = "INSERT INTO category_pelanggaran (name_ctg_plg) VALUES ('$cat')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function Delete kategori pelanggaran cctv
function DeleteCtgPlg($data) {

    global $conn;

    $page = $data["page-cat"];
    $id = htmlspecialchars($data["id-cat"]);

    // Check nama barang di database
    $result = mysqli_query($conn, "SELECT id_head_ctg_plg FROM jenis_pelanggaran WHERE id_head_ctg_plg = '$id'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Kategori pelanggaran CCTV ".$id." tidak dapat di hapus, karena telah memiliki jenis pelanggaran", "error", "$page");
        return false;
    }
    
    mysqli_query($conn, "DELETE FROM category_pelanggaran WHERE id_ctg_plg = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert jenis pelanggaran cctv
function InsertJnsPlg($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-cat"]);
    $name = htmlspecialchars(strtoupper($data["name-jns"]));

    $sql = "INSERT INTO jenis_pelanggaran (id_head_ctg_plg, name_jns_plg) VALUES ('$id', '$name')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function edit jenis pelanggaran cctv
function UpdateJnsPlg($data) {

    global $conn;

    // Input data post
    $idjns = htmlspecialchars($data["id-jns"]);
    $idcat = htmlspecialchars($data["id-cat"]);
    $name = htmlspecialchars(strtoupper($data["name-jns"]));

    mysqli_query($conn, "UPDATE jenis_pelanggaran SET id_head_ctg_plg = '$idcat', name_jns_plg = '$name' WHERE id_jns_plg = '$idjns'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //


// Function delete jenis pelanggaran cctv
function DeleteJnsPlg($data) {

    global $conn;

    $id = $data["id-jns"];
    
    mysqli_query($conn, "DELETE FROM jenis_pelanggaran WHERE id_jns_plg = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function edit kategori pelanggaran cctv
function UpdateCtgPlg($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-cat"]);
    $name = htmlspecialchars(strtoupper($data["name-cat"]));

    mysqli_query($conn, "UPDATE category_pelanggaran SET name_ctg_plg = '$name' WHERE id_ctg_plg = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert kejadian pelanggaran cctv
function InsertPelanggaranCCTV($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page-plg"]);
    $tgl = htmlspecialchars($data["tglwkt-plg"]);
    $shift = htmlspecialchars($data["shift-plg"]);
    $office = htmlspecialchars($data["office-plg"]);
    $dept = htmlspecialchars($data["dept-plg"]);
    $div = htmlspecialchars($data["div-plg"]);
    $ctg = htmlspecialchars($data["ctg-plg"]);
    $jns = htmlspecialchars($data["jns-plg"]);
    $dvr = htmlspecialchars($data["server-plg"]);
    $cctv = htmlspecialchars($data["cctv-plg"]);
    $user = htmlspecialchars($data["user-plg"]);
    $kej = htmlspecialchars(strtoupper($data["kejadian-plg"]));
    $ket = htmlspecialchars(strtoupper($data["keterangan-plg"]));
    $fup = htmlspecialchars($data["fup-plg"]);
    // $link = htmlspecialchars($data["link-plg"]);

    $query_ctg = mysqli_query($conn, "SELECT id_ctg_plg, name_ctg_plg FROM category_pelanggaran WHERE id_ctg_plg = '$ctg'");
    $data_ctg = mysqli_fetch_assoc($query_ctg);
    $result_ctg = $data_ctg["id_ctg_plg"].". ".$data_ctg["name_ctg_plg"];

    $query_dvr = mysqli_query($conn, "SELECT A.id_area_cctv, A.kode_area_cctv, A.ip_area_cctv, B.divisi_name FROM area_cctv AS A
    INNER JOIN divisi AS B ON A.divisi_area_cctv = B.id_divisi
    WHERE A.id_area_cctv = '$dvr'");
    $data_dvr = mysqli_fetch_assoc($query_dvr);
    $result_dvr = $data_dvr["kode_area_cctv"]." - ".$data_dvr["divisi_name"]." - ".$data_dvr["ip_area_cctv"];
    
    $dt = substr($tgl, 0, 10);

    $numid = autonum(6, 'no_plg_cctv', 'pelanggaran_cctv');

    $name  = $_FILES["record-plg"]["name"];
    $size  = $_FILES["record-plg"]["size"];
    $error = $_FILES["record-plg"]["error"];
    $tmp   = $_FILES["record-plg"]["tmp_name"];
    
    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload Video File", "error", "$page");
        return false;

    }

    $allowed = ['avi', 'mp4', 'mkv'];
    $eks = explode('.', $name);
    $eks = strtolower(end($eks));

    if(!in_array($eks, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload hanya di izinkan format mp4", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 35000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload hanya di izinkan maksimal 30Mb", "error", "$page");
        return false;
    
    }

    $video = $numid;
    $video .= '.';
    $video .= $eks;

    move_uploaded_file($tmp, 'files/record/' . $video);

    $sql = "INSERT INTO pelanggaran_cctv (no_plg_cctv, tgl_plg_cctv, date_plg_cctv, shift_plg_cctv, office_plg_cctv, dept_plg_cctv, div_plg_cctv, ctg_plg_cctv, jns_plg_cctv, dvr_plg_cctv, lokasi_plg_cctv, id_head_jns_plg, id_head_lay_cctv, user_plg_cctv, kejadian_plg_cctv, ket_plg_cctv, fup_plg_cctv, rekaman_plg_cctv, status_plg_cctv) VALUES ('$numid', '$tgl', '$dt', '$shift', '$office', '$dept', '$div', '$result_ctg', '$jns', '$result_dvr', '$cctv', '$ctg', '$dvr', '$user', '$kej', '$ket', '$fup', '$video', 'S')";

    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update kejadian pelanggaran cctv
function UpdatePelanggaranCCTV($data) {

    global $conn;

    // Input data post
    $page = $data["page-plgupdate"];
    $id = htmlspecialchars($data["id-plgupdate"]);
    $nomor = htmlspecialchars($data["no-plgupdate"]);
    // $tgl = htmlspecialchars($data["tglwkt-plgupdate"]);
    $shift = htmlspecialchars($data["shift-plgupdate"]);
    $divisi = htmlspecialchars($data["div-plgupdate"]);
    $oldvideo = htmlspecialchars($data["video-plgupdate"]);
    $kej = htmlspecialchars(strtoupper($data["kejadian-plgupdate"]));
    $ket = htmlspecialchars(strtoupper($data["keterangan-plgupdate"]));
    // $link = htmlspecialchars($data["link-plg"]);

    $error = $_FILES["record-plg"]["error"];
        
    if ($error === 4) {

        $video = $oldvideo;

    }
    else {

        $video = UploadVideoPelanggaran($nomor, $page);

        if ($video === FALSE) {
            return FALSE;
        }

    }

    mysqli_query($conn, "UPDATE pelanggaran_cctv SET shift_plg_cctv = '$shift', div_plg_cctv = '$divisi', kejadian_plg_cctv = '$kej', ket_plg_cctv = '$ket', rekaman_plg_cctv = '$video' WHERE id_plg_cctv = '$id'");

    return mysqli_affected_rows($conn);

}
// End function update layout cctv

// ---------------------------- //

// Function update video pelanggaran
function UploadVideoPelanggaran($nomor, $page) {

    $name  = $_FILES["record-plg"]["name"];
    $size  = $_FILES["record-plg"]["size"];
    $error = $_FILES["record-plg"]["error"];
    $tmp   = $_FILES["record-plg"]["tmp_name"];

    $allowed = ['avi', 'mp4', 'mkv'];
    $eks = explode('.', $name);
    $eks = strtolower(end($eks));

    if(!in_array($eks, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "Gagal! File yang anda upload bukan format video.", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 35000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "Gagal! File yang di upload tidak boleh lebih dari 20MB.", "error", "$page");
        return false;
    
    }

    $video = $nomor;
    $video .= '.';
    $video .= $eks;

    $dir = 'files/record/';
    $file = $dir.$video;

    if(file_exists($file)) {
        unlink($file);
    }

    move_uploaded_file($tmp, $file);

    return $video;

}
// End function

// ---------------------------- //

// Function delete kejadian pelanggaran cctv
function DeletePelanggaranCCTV($data) {

    global $conn;

    $id = $data["id-plgdelete"];
    $rec = $data["rec-plgdelete"];
    
    $dir = 'files/record/';
    $file = $dir.$rec;
    
    mysqli_query($conn, "DELETE FROM pelanggaran_cctv WHERE id_plg_cctv = '$id'");

    if(file_exists($file)) {
        unlink($file);
    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function follow up kejadian pelanggaran cctv
function FUPPelanggaranCCTV($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-plg"]);
    $no = htmlspecialchars($data["no-plg"]);
    $user = $data["fupuser"];
    $fup = htmlspecialchars($data["fup-sanksi"]);
    $atasan = htmlspecialchars($data["fup-atasan"]);
    $penjelasan = htmlspecialchars(strtoupper($data["fup-penjelasan"]));
    $prosesdate = date("Y-m-d H:i:s");

    $datauser = implode(", ", $user);

    foreach ($user as $u)  {
        mysqli_query($conn, "INSERT INTO user_pelanggaran_cctv (head_no_plg_cctv, username_plg_cctv) VALUES ('$no', '$u')");
    }

    $sql = "UPDATE pelanggaran_cctv SET tersangka_plg_cctv = '$datauser', date_fup_plg_cctv = '$prosesdate', fup_plg_cctv = '$fup', proses_plg_cctv = '$atasan', status_plg_cctv = 'N', penjelasan_plg_cctv = '$penjelasan' WHERE id_plg_cctv = '$id'";
    
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}
// End function update layout cctv

// ---------------------------- //

// function approve pelanggaran cctv
function ApprovePelanggaranCCTV($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-plg"]);
    $fup = htmlspecialchars($data["fup-plg"]);
    $app = htmlspecialchars($data["app-atasan"]);
    $penjelasan = htmlspecialchars($data["fup-penjelasan"]);
    $sts = "Y";

    mysqli_query($conn, "UPDATE pelanggaran_cctv SET status_plg_cctv = '$sts', approve_plg_cctv = '$app', fup_plg_cctv = '$fup', penjelasan_plg_cctv = '$penjelasan' WHERE id_plg_cctv = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function reject pelanggaran cctv
function RejectPelanggaranCCTV($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-plg"]);
    $no = htmlspecialchars($data["no-plg"]);

    mysqli_query($conn, "UPDATE pelanggaran_cctv SET date_fup_plg_cctv = NULL, fup_plg_cctv = '2', tersangka_plg_cctv = NULL, proses_plg_cctv = NULL, status_plg_cctv = 'S', penjelasan_plg_cctv = NULL WHERE id_plg_cctv = '$id'");

    mysqli_query($conn, "DELETE FROM user_pelanggaran_cctv WHERE head_no_plg_cctv = '$no'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Insert KKSO Non Aktiva
function InsertKKSONA($data) {

    global $conn;

    $page = $_POST["page-so"];
    $noso = $_POST["no-so"];
    $user = htmlspecialchars(mysqli_real_escape_string($conn, substr($data["petugas-so"], 0, 10)));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office-so"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dept-so"]));
    $tgl = date("Y-m-d H:i:s");
    $offdep = $office.$dept;
    $barang = $_POST["barangso"];

    $sql_checkso = "SELECT A.no_so, B.pluid_so FROM head_stock_opname AS A
    INNER JOIN detail_stock_opname AS B ON A.no_so = B.no_so_head
    WHERE A.office_so = '$office' AND A.dept_so = '$dept' AND A.jenis_so = 0 AND A.status_so = 'N'";

    $query_checkso = mysqli_query($conn, $sql_checkso);

    while ($data_checkso = mysqli_fetch_assoc($query_checkso)) {

        $dataexist = $data_checkso["pluid_so"];
        if (in_array($dataexist, $barang)) {

            $dataarrtostring = implode(", ", $barang);
            
            $GLOBALS['alert'] = array("Gagal!", "Kode Barang ".$dataexist." sedang dalam proses SO", "error", "$page");
            return false;

        }
    }

    foreach ($barang as $arrdata)  {

        $query_saldo = mysqli_query($conn, "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$arrdata'");
        $data_saldo = mysqli_fetch_assoc($query_saldo);
        $saldo = isset($data_saldo["saldo_akhir"]) ? $data_saldo["saldo_akhir"] : 0;

        $query_ast = mysqli_query($conn, "SELECT COUNT(pluid) AS total FROM barang_assets WHERE ba_id_office = '$office' AND ba_id_department = '$dept' AND pluid = '$arrdata' AND kondisi != '06'");

        while($data_ast = mysqli_fetch_assoc($query_ast)) {

            $asset = $data_ast["total"];

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO detail_stock_opname (no_so_head, pluid_so, saldo_so, asset_so, status_so_detail) VALUES ('$noso', '$arrdata', '$saldo', '$asset', 'N')");

    }

    mysqli_query($conn, "INSERT INTO head_stock_opname (no_so, tgl_so, user_so, office_so, dept_so, jenis_so) VALUES ('$noso', '$tgl', '$user', '$office', '$dept', 0)");

    return mysqli_affected_rows($conn);
    
}
// End function

// ---------------------------- //

// function Update / Reset LHSO Non Aktiva
function ResetLHSONA($data) {

    global $conn;

    $no_so = mysqli_real_escape_string($conn, $data["reset-noso"]);

    mysqli_query($conn, "UPDATE detail_stock_opname SET fisik_so = NULL, keterangan_so = NULL WHERE no_so_head = '$no_so'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Upload LHSO Non Aktiva
function UploadSONA($data) {

    global $conn;

    $page = $_POST["page-so"];
    $noref = $_POST["noref-so"];
    $office = htmlspecialchars($_POST["office-so"]);
    $dept = htmlspecialchars($_POST["dept-so"]);

    $name  = $_FILES["filecsv-so"]["name"];
    $size  = $_FILES["filecsv-so"]["size"];
    $error = $_FILES["filecsv-so"]["error"];
    $tmp   = $_FILES["filecsv-so"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }
    else {
        if (is_uploaded_file($tmp)) {
    
            $file = fopen($tmp, "r");
            $stored = [];
            fgetcsv($file);
    
            while (($line = fgetcsv($file)) !== FALSE) {
    
                $kdbarang = isset($line[0]) ? $line[0] : NULL;
                $fisikbarang = isset($line[1]) ? $line[1] : NULL;
                $ket = isset($line[2]) ? strtoupper($line[2]) : NULL;
    
                //skip current row if it is a duplicate
                if (in_array($kdbarang, $stored)) { continue;}
    
                $query = mysqli_query($conn, "SELECT * FROM detail_stock_opname WHERE no_so_head = '$noref' AND pluid_so = '$kdbarang'");
    
                if (!mysqli_fetch_assoc($query)) {
                    $GLOBALS['alert'] = array("Gagal!", "Data SO tidak matching!", "error", "$page");
                    return false;
                }
    
                // Insert data to database
                $sql_data_so = "UPDATE detail_stock_opname SET fisik_so = '$fisikbarang', keterangan_so = '$ket' WHERE no_so_head = '$noref' AND pluid_so = '$kdbarang'";
                mysqli_query($conn, $sql_data_so);
    
                //remember inserted value
                $stored[] = $kdbarang;
                
            }
    
            fclose($file);
            
        }
    }

    return mysqli_affected_rows($conn);

}
// End function import csv

// ---------------------------- //

// function Adjust SO Non Aktiva
function AdjustSONA($data) {

    global $conn;

    $code = "A";
    $page_success = $_POST["pagesuccess-noso"];
    $page = $_POST["page-noso"];
    $page = mysqli_real_escape_string($conn, $data["page"]);
    $no_so = htmlspecialchars(mysqli_real_escape_string($conn, $data["adjust-noso"]));

    $query_branch = mysqli_query($conn, "SELECT * FROM head_stock_opname WHERE no_so = '$no_so'");
    $data_branch = mysqli_fetch_assoc($query_branch);

    if (!$data_branch) {
        $GLOBALS['alert'] = array("Gagal!", "Tidak ada data draft stock opname", "error", "$page");
        return false;
    }
    else {
        if ($data_branch["status_so"] == "Y") {
            $GLOBALS['alert'] = array("Gagal!", "Data SO ".$no_so." sudah pernah dilakukan Adjust", "error", "$page");
            return false;
        }
    }

    $office = isset($data_branch["office_so"]) ? $data_branch["office_so"] : NULL;
    $dept = isset($data_branch["dept_so"]) ? $data_branch["dept_so"] : NULL;
    $user = isset($data_branch["user_so"]) ? $data_branch["user_so"] : NULL;
    $tgl = isset($data_branch["tgl_so"]) ? substr($data_branch["tgl_so"], 0, 10) : NULL;

    $query_detail = mysqli_query($conn, "SELECT * FROM detail_stock_opname WHERE no_so_head = '$no_so'");
    while($data_detail = mysqli_fetch_assoc($query_detail)){

        $pluid = $data_detail["pluid_so"];
        $saldo = $data_detail["saldo_so"];
        $fisik = $data_detail["fisik_so"];
        $ket = $data_detail["keterangan_so"];

        if ($fisik >= $saldo) {
            $hitung = '+';
            $selisih = $fisik - $saldo;
        }
        elseif ($fisik < $saldo) {
            $hitung = '-';
            $selisih = $saldo - $fisik;
        }

        if ($fisik === NULL) {
            $GLOBALS['alert'] = array("Gagal!", "Terdapat barang yang belum dilakukan opname", "error", "$page");
            return false;
        }

        $id = $code.autonum(5, 'no_btb_dpd', 'btb_dpd');

        $query_cekmstock = mysqli_query($conn, "SELECT pluid FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'");
        $data_cekmstock = mysqli_fetch_assoc($query_cekmstock);

        if (!$data_cekmstock) {
            mysqli_query($conn, "INSERT INTO masterstock (ms_id_office, ms_id_department, pluid, saldo_awal, saldo_akhir) VALUES ('$office', '$dept', '$pluid', 0, '$fisik')"); 
        }
        else {
            mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$fisik' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$pluid'");
        }
        
        mysqli_query($conn, "INSERT INTO btb_dpd (no_btb_dpd, office_btb_dpd, dept_btb_dpd, tgl_btb_dpd, pluid_btb_dpd, pic_btb_dpd, penerima_btb_dpd, hitung_btb_dpd, qty_awal_btb_dpd, qty_akhir_btb_dpd, ket_btb_dpd) VALUES ('$id', '$office', '$dept', '$tgl', '$pluid', '$user', '$user', '$hitung', '$saldo', '$selisih', '$ket')");
    }

    mysqli_query($conn, "UPDATE detail_stock_opname SET status_so_detail = 'Y' WHERE no_so_head = '$no_so'");
    mysqli_query($conn, "UPDATE head_stock_opname SET status_so = 'Y' WHERE no_so = '$no_so'");

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();

    $_SESSION["ALERT"] = array("Success!", "Data SO ".$no_so." Berhasil di Adjust", "success", "$page_success");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Upload Dokumen
function UploadDokumen($data) {

    global $conn;

    $numid = autonum(6, 'no_doc', 'dokumen');

    $office = htmlspecialchars($data["office-doc"]);
    $dept = htmlspecialchars($data["dept-doc"]);
    $div = htmlspecialchars($data["div-doc"]);
    $jenis = htmlspecialchars($data["jenis-doc"]);
    $sub = htmlspecialchars($data["sub-doc"]);
    $tgl = htmlspecialchars($data["tgl-doc"]);
    $nomor = htmlspecialchars(strtoupper($data["nomor-doc"]));
    $ket = htmlspecialchars(strtoupper($data["ket-doc"]));

    $name  = $_FILES["file-doc"]["name"];
    $size  = $_FILES["file-doc"]["size"];
    $error = $_FILES["file-doc"]["error"];
    $tmp   = $_FILES["file-doc"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload Document File", "error");
        return false;

    }

    $ekspdfvalid = ["pdf"];
    $ekspdf = explode('.', $name);
    $ekspdf = strtolower(end($ekspdf));

    if(!in_array($ekspdf, $ekspdfvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di izinkan hanya format pdf", "error");
        return false;

    }

    $maxsize = 1024 * 10000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 10MB", "error");
        return false;
    
    }

    $file = $numid;
    $file .= '.';
    $file .= $ekspdf;

    move_uploaded_file($tmp, 'files/doc/' . $file);

    $sql = "INSERT INTO dokumen (no_doc, office_doc, dept_doc, div_doc, jenis_doc, subjenis_doc, tgl_doc, nomor_doc, ket_doc, file_doc) VALUES ('$numid', '$office', '$dept', '$div', '$jenis', '$sub', '$tgl', '$nomor', '$ket', '$file')";

    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function update dokumen
function UpdateDokumen($data) {

    global $conn;

    $page = $data["upd-pagedoc"];
    $id = htmlspecialchars($data["upd-iddoc"]);
    $no = htmlspecialchars($data["upd-nodoc"]);
    $oldfile = htmlspecialchars($data["upd-oldfiledoc"]);
    $jenis = htmlspecialchars($data["upd-jenisdoc"]);
    $sub = htmlspecialchars($data["upd-subdoc"]);
    $tgl = htmlspecialchars($data["upd-tgldoc"]);
    $nop = htmlspecialchars(strtoupper($data["upd-nomordoc"]));
    $ket = htmlspecialchars(strtoupper($data["upd-ketdoc"]));
    
    $error = $_FILES["docfile-update"]["error"];
        
    if ($error === 4) {

        $file = $oldfile;

    }
    else {

        $file = UploadFileDocument($no, $page);

        if ($file === FALSE) {
            return FALSE;
        }

    }

    mysqli_query($conn, "UPDATE dokumen SET jenis_doc = '$jenis', subjenis_doc = '$sub', tgl_doc = '$tgl', nomor_doc = '$nop', ket_doc = '$ket', file_doc = '$file' WHERE id_doc = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function upload file document
function UploadFileDocument($no, $page) {

    $name  = $_FILES["docfile-update"]["name"];
    $size  = $_FILES["docfile-update"]["size"];
    $error = $_FILES["docfile-update"]["error"];
    $tmp   = $_FILES["docfile-update"]["tmp_name"];

    $allowed = ['pdf'];
    $eks = explode('.', $name);
    $eks = strtolower(end($eks));

    if(!in_array($eks, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang diupload bukan format pdf", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 10000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "Gagal! File yang di upload tidak boleh lebih dari 10MB", "error", "$page");
        return false;
    
    }

    $namefile = $no;
    $namefile .= '.';
    $namefile .= $eks;

    $dir = 'files/doc/';
    $file = $dir.$namefile;

    if(file_exists($file)) {
        unlink($file);
    }

    move_uploaded_file($tmp, $file);

    return $namefile;

}
// End function

// ---------------------------- //

// Function delete dokumen
function DeleteDokumen($data) {

    global $conn;

    $id = $data["id-doc"];
    $name = $data["file-doc"];
    
    $dir = 'files/doc/';
    $file = $dir.$name;
    
    mysqli_query($conn, "DELETE FROM dokumen WHERE id_doc = '$id'");

    if(file_exists($file)) {
        unlink($file);
    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function delete data reset password
function DeleteDataRepass($data) {

    global $conn;

    $id = $data["id-restpass"];
    
    mysqli_query($conn, "DELETE FROM reset_pass WHERE id_reset_pass = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert data signature
function InsertSign($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars($data["page-sign"]);
    $office = htmlspecialchars($data["office-sign"]);
    $dept = htmlspecialchars($data["dept-sign"]);
    
    $deputy = htmlspecialchars(strtoupper($data["deputy-sign"]));
    $deputy_name = htmlspecialchars(strtoupper($data["deputy-name"]));
    $head = htmlspecialchars(strtoupper($data["head-sign"]));
    $head_name = htmlspecialchars(strtoupper($data["head-name"]));
    $vum = htmlspecialchars(strtoupper($data["vum-sign"]));
    $vum_name = htmlspecialchars(strtoupper($data["vum-name"]));
    $area = htmlspecialchars(strtoupper($data["area-sign"]));
    $area_name = htmlspecialchars(strtoupper($data["area-name"]));
    $reg = htmlspecialchars(strtoupper($data["reg-sign"]));
    $reg_name = htmlspecialchars(strtoupper($data["reg-name"]));

    $query_check = mysqli_query($conn, "SELECT office_sign, dept_sign FROM signature WHERE office_sign = '$office' AND dept_sign = '$dept'");
    $data_check = mysqli_fetch_assoc($query_check);

    if ($data_check) {
        $GLOBALS['alert'] = array("Gagal!", " Data Signature ".$office." ".$dept." Sudah Terdaftar", "error", "$page");
        return false;
    }

    if (strlen($deputy) != 3 || strlen($head) != 3 || strlen($vum) != 3 || strlen($area) != 3 || strlen($reg) != 3) {
        $GLOBALS['alert'] = array("Gagal!", " Data inisial pejabat tidak boleh kurang atau lebih dari 3 digit", "error", "$page");
        return false;
    }
    
    $sql = "INSERT INTO signature (office_sign, dept_sign, initial_deputy_sign, name_deputy_sign, initial_dept_sign, name_dept_sign, initial_vum_sign, name_vum_sign, initial_head_sign, name_head_sign, initial_reg_sign, name_reg_sign) VALUES ('$office', '$dept', '$deputy', '$deputy_name', '$head', '$head_name', '$vum', '$vum_name', '$area', '$area_name', '$reg', '$reg_name')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}
// End function insert data signature

// ---------------------------- //

// function Update data signature
function UpdateSign($data) {

    global $conn;

    $id = $data["id-sign"];
    
    $page = htmlspecialchars($data["page-sign"]);
    $deputy = htmlspecialchars(strtoupper($data["deputy-sign"]));
    $deputy_name = htmlspecialchars(strtoupper($data["deputy-name"]));
    $head = htmlspecialchars(strtoupper($data["head-sign"]));
    $head_name = htmlspecialchars(strtoupper($data["head-name"]));
    $vum = htmlspecialchars(strtoupper($data["vum-sign"]));
    $vum_name = htmlspecialchars(strtoupper($data["vum-name"]));
    $area = htmlspecialchars(strtoupper($data["area-sign"]));
    $area_name = htmlspecialchars(strtoupper($data["area-name"]));
    $reg = htmlspecialchars(strtoupper($data["reg-sign"]));
    $reg_name = htmlspecialchars(strtoupper($data["reg-name"]));


    if (strlen($deputy) != 3 || strlen($head) != 3 || strlen($vum) != 3 || strlen($area) != 3 || strlen($reg) != 3) {
        $GLOBALS['alert'] = array("Gagal!", " Data inisial pejabat tidak boleh kurang atau lebih dari 3 digit", "error", "$page");
        return false;
    }

    mysqli_query($conn, "UPDATE signature SET initial_deputy_sign = '$deputy', name_deputy_sign = '$deputy_name', initial_dept_sign = '$head', name_dept_sign = '$head_name', initial_vum_sign = '$vum', name_vum_sign = '$vum_name', initial_head_sign = '$area', name_head_sign = '$area_name', initial_reg_sign = '$reg', name_reg_sign = '$reg_name' WHERE id_sign = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function delete data signature
function DeleteSign($data) {

    global $conn;

    $id = $data["id-sign"];
    
    mysqli_query($conn, "DELETE FROM signature WHERE id_sign = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function Creat Form Equipment Checking
function CreateFormEquipmentChecking($data) {

    global $conn;

    // Input data post
    $no = htmlspecialchars(mysqli_real_escape_string($conn, $data["no-cek"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-cek"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-cek"]));
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, $data["plu-cek"]));
    $date = htmlspecialchars(mysqli_real_escape_string($conn, $data["tgl-cek"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-cek"]));
    $pic = htmlspecialchars(mysqli_real_escape_string($conn, $data["pic-cek"]));
    $kondisi = htmlspecialchars(mysqli_real_escape_string($conn, $data["kondisi-cek"]));
    $ket = htmlspecialchars(strtoupper($data["ket-cek"]));

    // Create an array with the values you want to replace
    $searches = array("\r", "\n", "\r\n");

    // Replace the line breaks with a space
    $string = str_replace($searches, " ", $ket);

    // Replace multiple spaces with one
    $output = preg_replace('!\s+!', ' ', $string);

    // Insert data to database
    mysqli_query($conn, "INSERT INTO equipment_checking (no_equip_check, office_equip_check, dept_equip_check, plu_equip_check, date_equip_check, receive_equip_check, pic_equip_check, kondisi_equip_check, ket_equip_check) VALUES ('$no', '$office', '$dept', '$plu', '$date', '$user', '$pic', '$kondisi', '$output')");
    
    $encno = encrypt($no);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTEQUIPCHECK'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-equipment-checking.php?no=".$encno."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// // function Delete Service Barang By Check
function CancelBarangCheck($data) {

    global $conn;

    $idsj = $data["checkidsj"];

    $no = mysqli_real_escape_string($conn, $data["no-brgserv"]);
    $id = mysqli_real_escape_string($conn, $data["id-brgserv"]);

    $kondisi = mysqli_real_escape_string($conn, $data["kondisi-brgserv"]);
    $pluid = mysqli_real_escape_string($conn, $data["plu-brgserv"]);
    $snsj = mysqli_real_escape_string($conn, $data["sn-brgserv"]);
    $at = mysqli_real_escape_string($conn, $data["at-brgserv"]);
    
    mysqli_query($conn, "UPDATE barang_assets SET kondisi = '$kondisi' WHERE pluid = '$pluid' AND sn_barang = '$snsj' AND no_at = '$at' ");

    $query_head_sj = mysqli_query($conn, "SELECT head_no_sj FROM detail_surat_jalan WHERE head_no_sj = '$no'");

    foreach ($idsthh as $arrdata)  {
        // Update Data STHH Penerima dan jam masuk
        $sql = "DELETE FROM sthh WHERE id_sthh = '$idsj'";
        mysqli_query($conn, $sql);
    }

    if(mysqli_num_rows($query_head_sj) === 0) {
        mysqli_query($conn, "DELETE FROM surat_jalan WHERE no_sj = '$no'");
    }

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function proses PP
function ProsesBarangPP($data) {

    global $conn;
    global $timezone;

    // Input data post
    $page = htmlspecialchars($data["page-ppnb"]);
    $sproses = htmlspecialchars(mysqli_real_escape_string($conn, $data["status-ppnb"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-ppnb"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-ppnb"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-ppnb"]));
    $officeto = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-to"]));
    $deptto = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-to"]));
    $tgl = htmlspecialchars($data["tgl-to"]);
    $keperluan = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["keperluan-ppnb"]))));
    $ofdep = $office.$dept;
    $prosesdate = date("Y-m-d H:i:s");
    $noref = autonum(5, 'noref', 'pembelian');
    $tahun = substr($tgl, 0, 4);

    if (!isset($_POST["kode_barang"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data barang belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    $pros = "Y";
    for($count = 0; $count < count($_POST["kode_barang"]); $count++) {
        $dataarr = array(
            $pluid = substr($_POST["kode_barang"][$count], 0, 10),
            $merk = strtoupper($_POST["merk_barang"][$count]),
            $tipe = strtoupper($_POST["tipe_barang"][$count]),
            $qty = $_POST["qty_barang"][$count],
            $estharga = ($_POST["cost_barang"][$count]*$qty),
            $ket = strtoupper($_POST["ket_barang"][$count])
        );
        
        $query_th = mysqli_query($conn, "SELECT status_budget FROM statusbudget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND status_budget = 'Y'");
        
        if(mysqli_num_rows($query_th) > 0) {
            $data_pp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT use_budget FROM budget WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'"));
            $saldobgt = isset($data_pp['use_budget']) ? $data_pp['use_budget'] : 0;
            $qtypp = $saldobgt+$qty;
            
            mysqli_query($conn, "UPDATE budget SET use_budget = '$qtypp' WHERE id_office = '$office' AND id_department = '$dept' AND tahun_periode = '$tahun' AND plu_id = '$pluid'");    
        }
        
        mysqli_query($conn, "INSERT INTO detail_pembelian (noref, tgl_detail_pp, id_offdep, user_pp, plu_id, merk, tipe, qty, harga_pp, keterangan, proses) VALUES ('$noref', '$tgl', '$ofdep', '$user', '$pluid', '$merk', '$tipe', '$qty', '$estharga', '$ket', '$pros')"); 
    }

    $id = 'PPG';
    $dateid = date("d/m/Y");
    
    $ppbno = $id.'/'.$noref.'/'.$dateid;
    // Insert pembelian to database
    $resultppb = "INSERT INTO pembelian (noref, id_office, id_department, ppid, user, office_to, department_to, proses_date, tgl_pengajuan, status_pp, keperluan) VALUES ('$noref', '$office', '$dept', '$ppbno', '$user', '$officeto', '$deptto', '$prosesdate', '$tgl', '$sproses', '$keperluan')";
    mysqli_query($conn, $resultppb);

    mysqli_query($conn, "INSERT INTO mon_status_pp (mspp_noref, mspp_id_spp, mspp_proses, mspp_date) VALUES ('$noref', '$sproses', '$user', '$prosesdate')");

    $encppb = encrypt($ppbno);

    if (session_status()!==PHP_SESSION_ACTIVE)session_start();
    
    $_SESSION['PRINTPP'] = $_POST;

    echo "<script type=\"text/javascript\">
    window.open('reporting/report-form-pp.php?ppid=".$encppb."', '_blank')
    </script>";

    return mysqli_affected_rows($conn);
}
// End function update stock budget and insert pembelian

// ---------------------------- //

// Function batal terima barang
function CancelBarangMasuk($data) {

    global $conn;

    $id = $data["del-idsj"];
    $office = $data["del-office"];
    $dept = $data["del-dept"];

    $sql_sj = "SELECT from_sj, pluid_sj, SUM(qty_sj) AS tot_brg FROM detail_surat_jalan WHERE head_no_sj = '$id' GROUP BY pluid_sj";
    $query_sj = mysqli_query($conn, $sql_sj);

    if(mysqli_num_rows($query_sj) > 0) {
        
        while($data_sj = mysqli_fetch_assoc($query_sj)) {
            
            $plu = $data_sj["pluid_sj"];
            $brg = $data_sj["tot_brg"];

            $sql_ms = "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'";
            $query_ms = mysqli_query($conn, $sql_ms);
            $data_ms = mysqli_fetch_assoc($query_ms);
            
            if ($data_ms) {
                
                $saldo = $data_ms["saldo_akhir"];
                $saldo_akh = ($saldo + $brg);
    
                mysqli_query($conn, "UPDATE masterstock SET saldo_akhir = '$saldo_akh' WHERE ms_id_office = '$office' AND ms_id_department = '$dept' AND pluid = '$plu'");    
            }
        }
    }

    mysqli_query($conn, "DELETE FROM surat_jalan WHERE no_sj = '$id'");
    mysqli_query($conn, "DELETE FROM detail_surat_jalan WHERE head_no_sj = '$id'");
    mysqli_query($conn, "DELETE FROM btb_dpd WHERE ref_btb_dpd = '$id'");

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// Function Pencarian Data Barang Dengan Select Box
function fill_select_box($data) {

    global $conn;

    $office = substr($data, 0, 4);
    $dept = substr($data, 4, 4);
    $kondisi = substr($data, 8, 2);

    $sql = "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
    INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
    INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
    WHERE A.ba_id_office = '$office' AND A.ba_id_department = '$dept' GROUP BY A.pluid ORDER BY B.NamaBarang ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$office.$dept.$kondisi.$row['pluid'].'">'.$row['pluid'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function Pencarian Data Barang Berdasarkan Kepemilikan DAT
function fill_select_dat($data) {

    global $conn;

    $office = substr($data, 0, 4);
    $dept = substr($data, 4, 4);
    $kondisi = substr($data, 8, 2);

    $sql = "SELECT A.*, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM barang_assets AS A
    INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
    INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
    WHERE LEFT(A.dat_asset, 4) = '$office' AND RIGHT(A.dat_asset, 4) = '$dept' GROUP BY A.pluid ORDER BY B.NamaBarang ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$office.$dept.$kondisi.$row['pluid'].'">'.$row['pluid'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function Pencarian Data Barang Dengan Select Box
function fill_select_brgsj($data) {

    global $conn;

    $office = substr($data, 0, 4);
    $dept = substr($data, 4, 4);

    $sql = "SELECT A.pluid, B.IDBarang, B.NamaBarang, C.IDJenis, C.NamaJenis FROM masterstock AS A
    INNER JOIN mastercategory AS B ON LEFT(A.pluid,6) = B.IDBarang 
    INNER JOIN masterjenis AS C ON RIGHT(A.pluid,4) = C.IDJenis
    WHERE A.ms_id_office = '$office' AND A.ms_id_department = '$dept' ORDER BY B.NamaBarang ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$office.$dept.$row['pluid'].'">'.$row['pluid'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function Pencarian Data Barang Inventaris
function fill_select_kondisi($data) {

    global $conn;

    $sql = "SELECT * FROM kondisi WHERE id_kondisi NOT LIKE '$data'";

    $output = '';
    
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$row['id_kondisi'].'">'.$row['id_kondisi'].' - '.$row['kondisi_name'].'</option>';
        
        }
    }
    return $output;
}

// End function

// ---------------------------- //

// Function Pencarian Data Barang Dengan Select Box
function fill_select_pp() {

    global $conn;


    $sql = "SELECT A.*, B.* FROM mastercategory AS A
    INNER JOIN masterjenis AS B ON A.IDBarang = B.IDBarang ORDER BY A.NamaBarang ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$row['IDBarang'].$row['IDJenis'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'">'.$row['IDBarang'].$row['IDJenis'].' - '.$row['NamaBarang'].' '.$row['NamaJenis'].'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function Pencarian Data Users
function fill_select_users($data) {

    global $conn;

    $office = substr($data, 0, 4);
    $dept = substr($data, 4, 4);

    $sql = "SELECT nik, username, full_name FROM users WHERE id_office = '$office' AND id_department = '$dept' AND id_group NOT LIKE 'GP01' AND full_name IS NOT NULL ORDER BY nik ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$row['nik'].' - '.strtoupper($row['username']).'">'.$row['nik'].' - '.strtoupper($row['full_name']).'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function Pencarian Data Barang Divisi
function fill_select_subdivisi() {

    global $conn;


    $sql = "SELECT A.*, B.* FROM divisi AS A
    INNER JOIN sub_divisi AS B ON A.id_divisi = B.id_divisi ORDER BY A.id_divisi ASC";

    $query = mysqli_query($conn, $sql);
    
    $output = '';

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {

            $output .= '<option value="'.$row['id_sub_divisi']."-".$row['divisi_name'].' > '.$row['sub_divisi_name'].'">'.$row['divisi_name'].' > '.$row['sub_divisi_name'].'</option>';
        
        }
    }

    return $output;

}

// End function

// ---------------------------- //

// Function 
function fill_select_digit($data, $digit) {

    $output = '';

    for($i = 1; $i < $data; $i++) {
        $formattedNumber = str_pad($i, $digit, '0', STR_PAD_LEFT);
        $output .= '<option value="'.$formattedNumber.'">'.$formattedNumber.'</option>';
    }

    return $output;

}

// End function

// ---------------------------- //

// Function delete serial number
function DeleteSNBarang($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-sn"]));
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-sn"]));
    $sn = htmlspecialchars(mysqli_real_escape_string($conn, $data["data-sn"]));

    // Check username di database
    $query = mysqli_query($conn, "SELECT sn_barang FROM barang_assets WHERE sn_barang = '$sn'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Serial Number ".$sn." Telah Terdaftar di Mater Barang Inventaris", "error", "$page");
        return false;

    }

    mysqli_query($conn, "DELETE FROM serial_number WHERE id_serial_number = '$id'");
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function create project
function CreateProject($data) {

    global $conn;
    global $timezone;

    // Input data post
    $page = htmlspecialchars($data["page-project"]);
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-project"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-project"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-project"]));
    $perintah = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["perintah-project"])));
    $tgl = htmlspecialchars($data["tgl-project"]);
    $judul = htmlspecialchars(strtoupper($data["judul-project"]));
    $urgensi = htmlspecialchars($data["urgensi-project"]);

    $docno = autonum(6, 'no_head_project', 'head_project');

    if (!isset($_POST["urutan_project"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data pengerjaan project belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    $name  = $_FILES["file-project"]["name"];
    $size  = $_FILES["file-project"]["size"];
    $error = $_FILES["file-project"]["error"];
    $tmp   = $_FILES["file-project"]["tmp_name"];

    if ($error === 4) {

        $file = '';

    }
    else {

        $ekspdfvalid = ["pdf"];
        $ekspdf = explode('.', $name);
        $ekspdf = strtolower(end($ekspdf));

        if(!in_array($ekspdf, $ekspdfvalid)) {

            $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format pdf", "error", "$page");
            return false;

        }

        $maxsize = 1024 * 10000; // maksimal 1000 KB (1KB = 1024 Byte)

        if($size >= $maxsize || $size == 0) {

            $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 10MB", "error", "$page");
            return false;
        
        }

        $file = $docno;
        $file .= '.';
        $file .= $ekspdf;

        move_uploaded_file($tmp, 'files/project/' . $file);
        
    }

    $pros = "Y";
    for($count = 0; $count < count($_POST["urutan_project"]); $count++) {
        $dataarr = array(
            $urutan = $_POST["urutan_project"][$count],
            $pic = strtoupper($_POST["pic_project"][$count]),
            $pengerjaan = strtoupper($_POST["pengerjaan_project"][$count]),
            $jumlah = $_POST["jumlah_project"][$count],
            $priority = $_POST["priority_project"][$count]
        );

        mysqli_query($conn, "INSERT INTO project_task (ref_project_task, urutan_project_task, pic_project_task, pengerjaan_project_task, jumlah_project_task, priority_project_task) VALUES ('$docno', '$urutan', '$pic', '$pengerjaan', '$jumlah', '$priority')");
    }

    // Insert pembelian to database
    $resultppb = "INSERT INTO head_project (no_head_project, office_head_project, dept_head_project, user_head_project, approve_head_project, tgl_head_project, judul_head_project, urgensi_head_project, doc_head_project) VALUES ('$docno', '$office', '$dept', '$user', '$perintah', '$tgl', '$judul', '$urgensi', '$file')";
    
    mysqli_query($conn, $resultppb);

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function Upload Doc Project
function UploadDocProject($data) {

    global $conn;
    global $timezone;

    // Input data post
    $page = $data["page-project"];
    $id = htmlspecialchars($data["id-project"]);
    $docno = htmlspecialchars($data["no-project"]);
    $docold = htmlspecialchars($data["docold-project"]);

    $name  = $_FILES["doc-project"]["name"];
    $size  = $_FILES["doc-project"]["size"];
    $error = $_FILES["doc-project"]["error"];
    $tmp   = $_FILES["doc-project"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "File dokumen belum ada yang dipilih", "error", "$page");
        return false;

    }

    $ekspdfvalid = ["pdf"];
    $ekspdf = explode('.', $name);
    $ekspdf = strtolower(end($ekspdf));

    if(!in_array($ekspdf, $ekspdfvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format pdf", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 10000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 10MB", "error", "$page");
        return false;
    
    }

    $file = $docno;
    $file .= '.';
    $file .= $ekspdf;

    $dir = 'files/project/'.$file;

    if(file_exists($dir)) {
        unlink($dir);
    }

    $sql = "UPDATE head_project SET doc_head_project = '$file' WHERE id_head_project = '$id'";

    mysqli_query($conn, $sql);

    move_uploaded_file($tmp, $dir);

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function delete project
function CancelProject($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["del-no"]));
    $doc = htmlspecialchars($data["del-doc"]);

    $dir = 'files/project/';
    $file = $dir.$doc;

    if(file_exists($file)) {
        unlink($file);
    }

    mysqli_query($conn, "DELETE FROM project_task WHERE ref_project_task = '$id'");
    mysqli_query($conn, "DELETE FROM head_project WHERE no_head_project = '$id'");
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function delete data task project
function DeleteTaskProject($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-task"]);
    $id = mysqli_real_escape_string($conn, $data["id-task"]);
    $docno = mysqli_real_escape_string($conn, $data["no-task"]);

    $q_rows_item = mysqli_query($conn, "SELECT ref_project_task FROM project_task WHERE ref_project_task = '$docno'");
    $d_rows_item = mysqli_num_rows($q_rows_item);

    if($d_rows_item === 1){

        $GLOBALS['alert'] = array("Gagal!", "Daftar data task project tidak boleh kosong", "error", "$page");
        return false;

    }

    mysqli_query($conn, "DELETE FROM project_task WHERE id_project_task = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert data task
function InsertTaskProject($data) {

    global $conn;

    // Input data post
    $page = $data["page-task"];
    $docno = htmlspecialchars($data["docno-task"]);
    $user = htmlspecialchars($data["user-task"]);
    $urut = htmlspecialchars($data["urut-task"]);
    $pic = htmlspecialchars(strtoupper($data["pic-task"]));
    $ket = htmlspecialchars(strtoupper($data["ket-task"]));
    $jumlah = htmlspecialchars($data["jumlah-task"]);
    $sulit = htmlspecialchars($data["kesulitan-task"]);

    if($jumlah <= 0) {

        $GLOBALS['alert'] = array("Gagal!", "jumlah tidak boleh kurang atau sama dengan 0", "error", "$page");
        return false;

    }

    // Check pluid di database
    $result = mysqli_query($conn, "SELECT ref_project_task FROM project_task WHERE ref_project_task = '$docno' AND urutan_project_task = '$urut'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Urutan tahapan ".$urut." sudah masuk kedalam list project", "error", "$page");
        return false;

    }

    $sql = "INSERT INTO project_task (ref_project_task, urutan_project_task, pic_project_task, pengerjaan_project_task, jumlah_project_task, priority_project_task) VALUES ('$docno', '$urut', '$pic', '$ket', '$jumlah', '$sulit')";
        
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update data task
function UpdateTaskProject($data) {

    global $conn;

    $id = htmlspecialchars($data["id-task"]);
    $no = htmlspecialchars($data["no-task"]);
    $tgl = htmlspecialchars($data["tgl-task"]);
    $user = htmlspecialchars($data["user-task"]);
    $ket = htmlspecialchars(strtoupper($data["ket-task"]));
    $status = "Y";

    mysqli_query($conn, "UPDATE project_task SET user_project_task = '$user', efektif_project_task = '$tgl', ket_project_task = '$ket', status_project_task = '$status' WHERE id_project_task = '$id'");

    $query = mysqli_query($conn, "SELECT status_project_task FROM project_task WHERE ref_project_task = '$no' AND status_project_task = 'N'");

    $result = mysqli_num_rows($query);

    if($result === 0){

        mysqli_query($conn, "UPDATE head_project SET status_head_project = 'Y' WHERE no_head_project = '$no'");

    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function master dat
function InsertMasterDAT($data) {

    global $conn;

    // Input data post
    $page = $data["page-dat"];
    $office = htmlspecialchars($data["office-dat"]);
    $dept = htmlspecialchars($data["dept-dat"]);
    $perolehan = htmlspecialchars($data["perolehan-dat"]);
    $nomor = htmlspecialchars($data["nomor-dat"]);
    $qty = htmlspecialchars($data["qty-dat"]);
    $barang = htmlspecialchars($data["barang-dat"]);

    // Check pluid di database
    $result = mysqli_query($conn, "SELECT no_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$nomor' AND pluid_dat = '$barang'");

    if(mysqli_num_rows($result) === 1) {

        $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$nomor." Sudah Terdaftar", "error", "$page");
        return false;

    }

    $sql = "INSERT INTO dat (office_dat, dept_dat, no_dat, perolehan_dat, qty_dat, pluid_dat) VALUES ('$office', '$dept', '$nomor', '$perolehan', '$qty', '$barang')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update master dat
function UpdateMasterDAT($data) {

    global $conn;

    // Input data post
    $page = $data["page-updkepdat"];
    $id = htmlspecialchars($data["id-updkepdat"]);
    $office = htmlspecialchars($data["office-updkepdat"]);
    $dept = htmlspecialchars($data["dept-updkepdat"]);
    $perolehan = htmlspecialchars($data["tgl-updkepdat"]);
    $oldno = htmlspecialchars($data["oldnomor-updkepdat"]);
    $nomor = htmlspecialchars(strtoupper($data["nomor-updkepdat"]));
    $qty = htmlspecialchars($data["qty-updkepdat"]);
    $barang = htmlspecialchars($data["barang-updkepdat"]);
    $oldstatus = htmlspecialchars($data["oldstatus-updkepdat"]);
    $status = htmlspecialchars(isset($data["status-updkepdat"]) ? $data["status-updkepdat"] : $oldstatus);

    if ($nomor != $oldno) {
        $query_dat = mysqli_query($conn, "SELECT no_dat, qty_dat, status_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$nomor' AND pluid_dat = '$barang'");

        if (mysqli_num_rows($query_dat) === 1) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$nomor." Sudah Terdaftar", "error", "$page");
            return false;
        }

    }

    mysqli_query($conn, "UPDATE dat SET perolehan_dat = '$perolehan', no_dat = '$nomor', qty_dat = '$qty', pluid_dat = '$barang', status_dat = '$status' WHERE id_dat = '$id'");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete master dat
function DeleteMasterDAT($data) {

    global $conn;

    // Input data post
    $page = $data["page-delkepdat"];
    $id = htmlspecialchars($data["id-delkepdat"]);
    $office = htmlspecialchars($data["office-delkepdat"]);
    $dept = htmlspecialchars($data["dept-delkepdat"]);
    $nomor = htmlspecialchars($data["nomor-delkepdat"]);
    $barang = htmlspecialchars($data["barang-delkepdat"]);

    $query = mysqli_query($conn, "SELECT no_at FROM barang_assets WHERE LEFT(dat_asset, 4) = '$office' AND RIGHT(dat_asset, 4) = '$dept' AND no_at = '$nomor'");

    if(mysqli_num_rows($query) > 0 ) {
        
        $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$nomor." telah terdaftar di master barang inventaris", "error", "$page");
        return false;
    }
   
    mysqli_query($conn, "DELETE FROM dat WHERE id_dat = '$id'");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function upload csv master dat
function UploadMasterDAT($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dept"]));

    $name  = $_FILES["file-import"]["name"];
    $size  = $_FILES["file-import"]["size"];
    $error = $_FILES["file-import"]["error"];
    $tmp   = $_FILES["file-import"]["tmp_name"];

    if ($error === 4) {

        $GLOBALS['alert'] = array("Gagal!", "Invalid File, Please Upload CSV File", "error", "$page");
        return false;
    
    }

    $allowed = ['csv'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    if(!in_array($ext, $allowed)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format CSV", "error", "$page");
        return false;
 
    }

    if (is_uploaded_file($tmp)) {

        $file = fopen($tmp, "r");
        $stored = [];
        fgetcsv($file);

        while (($line = fgetcsv($file)) !== FALSE) {

            $nodat = isset($line[0]) ? $line[0] : NULL;
            $perolehan = isset($line[1]) ? $line[1] : NULL;
            $qty = isset($line[2]) ? $line[2] : NULL;
            $pluid = isset($line[3]) ? $line[3] : NULL;

            //skip current row if it is a duplicate
            if (in_array($nodat, $stored)) {continue;}

            $idbarang = substr($pluid, 0, -4);
            $idjenis = substr($pluid, 6);

            $query = mysqli_query($conn, "SELECT IDBarang, IDJenis FROM masterjenis WHERE IDBarang = '$idbarang' AND IDJenis = '$idjenis'");
            $data = mysqli_fetch_assoc($query);

            if (!$data) {
                $GLOBALS['alert'] = array("Gagal!", "Kode Barang ".$pluid." Tidak Terdaftar di Master Barang", "error", "$page");
                return false;
            }

            $query_dat = mysqli_query($conn, "SELECT no_dat FROM dat WHERE office_dat = '$office' AND dept_dat = '$dept' AND no_dat = '$nodat' AND pluid_dat = '$pluid'");

            $data_dat = mysqli_fetch_assoc($query_dat);

            if ($data_dat) {
                $GLOBALS['alert'] = array("Gagal!", "Nomor Aktiva ".$nodat." Sudah Terdaftar di Master DAT", "error", "$page");
                return false;
            }

            mysqli_query($conn, "INSERT INTO dat (office_dat, dept_dat, no_dat, perolehan_dat, qty_dat, pluid_dat) VALUES ('$office', '$dept', '$nodat', '$perolehan', '$qty', '$pluid')");

            //remember inserted value
            $stored[] = $pluid;
            
        }

        fclose($file);

    }
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert data kehadiran
function PostingKehadiran($data) {

    global $conn;
    
    require 'vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('includes/config/client_secret.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $service = new Google_Service_Sheets($client);

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];

    // Input data post
    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-posting"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-posting"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-posting"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-posting"]));
    $nik = substr($user, 0, 10);
    $transk = htmlspecialchars($data["trans-posting"]);
    $date = date("Y-m-d H:i:s");
    $aksi = "I";
    $idmax = autonum(6, 'no_aprv_presensi', 'approval_presensi');
    $doc_app = $aksi."-".$idmax;
    
    if (!isset($_POST["user_hadir"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data kehadiran belum ada yang ditambahkan", "error", "$page");
        return false;
    }

    $query_headiv = mysqli_query($conn, "SELECT B.id_head_divisi, C.name_head_div FROM users AS A 
    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
    WHERE A.nik = '$nik'");

    $data_headiv = mysqli_fetch_assoc($query_headiv);

    $idheadiv = isset($data_headiv["id_head_divisi"]) ? $data_headiv["id_head_divisi"] : NULL;
    $nameheadiv = $data_headiv["name_head_div"];

    $query_mtele = mysqli_query($conn, "SELECT * FROM user_telebot WHERE office_user_tele = '$office' AND role_user_tele = '$transk' AND div_user_tele = '$idheadiv' AND status_user_tele = 'Y'");

    if (mysqli_num_rows($query_mtele) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "User penerima notifkasi telegram bot belum terdaftar", "error", "$page");
        return false;

    }
    else {
        if(mysqli_num_rows($query_mtele) > 0 ) {

            $chatID = array();
            while($data_mtele = mysqli_fetch_assoc($query_mtele)) {
                $chatID[] = $data_mtele["no_user_tele"];
            }
        }
    }

    $final = array();
    for($countcek = 0; $countcek < count($_POST["user_hadir"]); $countcek++) {
        $arrcek = array(
            $nikcek = $_POST["user_hadir"][$countcek],
            $tglcek = $_POST["tgl_hadir"][$countcek]
        );

        $query_cekhadir = mysqli_query($conn, "SELECT nik_presensi, user_presensi FROM presensi WHERE office_presensi = '$office' AND dept_presensi = '$dept' AND nik_presensi = '$nikcek' AND tgl_presensi = '$tglcek'");

        if(mysqli_num_rows($query_cekhadir) > 0 ) {
            
            $GLOBALS['alert'] = array("Gagal!", "User ".$nikcek." Tanggal ".$tglcek." sudah input status kehadiran", "error", "$page");
            return false;
        }

		$final[] = $arrcek;
    }

    if(count(array_unique($final, SORT_REGULAR)) < count($final)) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat pemilihan data user dan tanggal duplicate", "error", "$page");
        return false;
    }

    $values = array();
    
    for($count = 0; $count < count($_POST["user_hadir"]); $count++) {

        $dataarr = array(
            $date,
            $doc_app,
            $docno = autonum(6, 'no_presensi', 'presensi'),
            $office,
            $user,
            $nik_hadir = substr($_POST["user_hadir"][$count], 0, 10),
            $user_hadir = substr($_POST["user_hadir"][$count], 13),
            $bag_hadir = $_POST["bagian_hadir"][$count],
            $tgl_hadir = $_POST["tgl_hadir"][$count],
            $cek_hadir = strtoupper($_POST["cek_hadir"][$count]),
            "",
            $ket_hadir = strtoupper($_POST["ket_hadir"][$count])
        );

		$values[] = $dataarr;

        $sql_inst = "INSERT INTO presensi (aksi_presensi, no_presensi, office_presensi, dept_presensi, input_presensi, nik_presensi, user_presensi, div_presensi, tgl_presensi, cek_presensi, ket_presensi) VALUES ('$doc_app', '$docno', '$office', '$dept', '$user', '$nik_hadir', '$user_hadir', '$bag_hadir', '$tgl_hadir', '$cek_hadir', '$ket_hadir')";

        mysqli_query($conn, $sql_inst);

    }

    // OPERASI CREATE
    $range = "SHEET_PRESENSI_IMS";

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $params = [
        'valueInputOption' => 'RAW'
    ];

    $insert = [
        'insertDataOption' => 'INSERT_ROWS'
    ];

    $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params, $insert);

    mysqli_query($conn, "INSERT INTO approval_presensi (no_aprv_presensi, office_aprv_presensi, date_aprv_presensi, user_aprv_presensi, aksi_aprv_presensi) VALUES ('$doc_app', '$office', '$date', '$user', '$aksi')");

    SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $doc_app, "", $date, $user, $link.$spreadsheetId, "");

    return mysqli_affected_rows($conn);

}   
// End function

// ---------------------------- //

// function update data kehadiran
function EditKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-edit"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-edit"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-edit"]));
    $nik = substr($user, 0, 10);
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-edit"])));
    $transk = htmlspecialchars($data["trans-posting"]);
    $date = date("Y-m-d H:i:s");
    $aksi = "E";
    $idmax = autonum(6, 'no_aprv_presensi', 'approval_presensi');
    $docno = $aksi."-".$idmax;

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];

    $otp = random_int(100000, 999999);
    
    if (!isset($data["edit_id_hadir"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data presensi belum ada yang dipilih", "error", "$page");
        return false;
    }

    $query_headiv = mysqli_query($conn, "SELECT B.id_head_divisi, C.name_head_div FROM users AS A 
    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
    WHERE A.nik = '$nik'");

    $data_headiv = mysqli_fetch_assoc($query_headiv);

    $idheadiv = isset($data_headiv["id_head_divisi"]) ? $data_headiv["id_head_divisi"] : NULL;
    $nameheadiv = $data_headiv["name_head_div"];

    $query_mtele = mysqli_query($conn, "SELECT * FROM user_telebot WHERE office_user_tele = '$office' AND role_user_tele = '$transk' AND div_user_tele = '$idheadiv' AND status_user_tele = 'Y'");

    if (mysqli_num_rows($query_mtele) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "User penerima notifkasi telegram bot belum terdaftar", "error", "$page");
        return false;

    }
    else {
        if(mysqli_num_rows($query_mtele) > 0 ) {

            $chatID = array();
            while($data_mtele = mysqli_fetch_assoc($query_mtele)) {
                $chatID[] = $data_mtele["no_user_tele"];
            }
        }
    }

    for($count = 0; $count < count($_POST["edit_id_hadir"]); $count++) {

        if (isset($data["edit_cek_hadir"][$count])) {
            $update_ceknew = $data["edit_cek_hadir"][$count];
            $update_jamnew = "";
        }
        else {
            if ($data["edit_jamold_hadir"][$count] == "OFF" && $data["edit_jam_hadir"][$count] != "OFF") {
                $update_ceknew = "TUKAR OFF";
                $update_jamnew = $data["edit_jam_hadir"][$count];
            }
            elseif ($data["edit_jamold_hadir"][$count] != "OFF" && $data["edit_jam_hadir"][$count] == "OFF") {
                $update_ceknew = "TUKAR OFF";
                $update_jamnew = $data["edit_jam_hadir"][$count];
            }
            elseif ($data["edit_jamold_hadir"][$count] != "OFF" && $data["edit_jam_hadir"][$count] != "OFF") {
                $update_ceknew = "RUBAH SHIFT";
                $update_jamnew = $data["edit_jam_hadir"][$count];
            }   
        }

        array(
            $update_id = $_POST["edit_id_hadir"][$count],
            $nik_hadir = substr($_POST["edit_user_hadir"][$count], 0, 10),
            $tgl_hadir = $_POST["edit_tgl_hadir"][$count],
            $update_cekold = $_POST["edit_cekold_hadir"][$count],
            $update_ceknew,
            $update_jamnew,
            $update_ketnew = strtoupper($_POST["edit_ket_hadir"][$count])
        );

        mysqli_query($conn, "INSERT INTO data_presensi (no_data_presensi, ref_data_presensi, nik_data_presensi, tgl_data_presensi, cekold_data_presensi, ceknew_data_presensi, jam_data_presensi, ket_data_presensi) VALUES ('$docno', '$update_id', '$nik_hadir', '$tgl_hadir', '$update_cekold', '$update_ceknew', '$update_jamnew', '$update_ketnew')");

    }
    
    mysqli_query($conn, "INSERT INTO approval_presensi (no_aprv_presensi, otp_aprv_presensi, office_aprv_presensi, date_aprv_presensi, user_aprv_presensi, ket_aprv_presensi, aksi_aprv_presensi) VALUES ('$docno', '$otp', '$office', '$date', '$user', '$ket', '$aksi')");

    SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $docno, $otp, $date, $user, $link.$spreadsheetId, $ket);

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //


// function delete data kehadiran
function DeleteKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-delete"]);
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-delete"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-delete"]));
    $nik = substr($user, 0, 10);
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ket-delete"])));
    $transk = htmlspecialchars($data["trans-posting"]);
    $date = date("Y-m-d H:i:s");
    $aksi = "D";
    $idmax = autonum(6, 'no_aprv_presensi', 'approval_presensi');
    $docno = $aksi."-".$idmax;

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];

    $otp = random_int(100000, 999999);

    if (!isset($data["edit_id_hadir"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data presensi belum ada yang dipilih", "error", "$page");
        return false;
    }

    $query_headiv = mysqli_query($conn, "SELECT B.id_head_divisi, C.name_head_div FROM users AS A 
    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
    WHERE A.nik = '$nik'");

    $data_headiv = mysqli_fetch_assoc($query_headiv);

    $idheadiv = isset($data_headiv["id_head_divisi"]) ? $data_headiv["id_head_divisi"] : NULL;
    $nameheadiv = $data_headiv["name_head_div"];

    $query_mtele = mysqli_query($conn, "SELECT * FROM user_telebot WHERE office_user_tele = '$office' AND role_user_tele = '$transk' AND div_user_tele = '$idheadiv' AND status_user_tele = 'Y'");

    if (mysqli_num_rows($query_mtele) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "User penerima notifkasi telegram bot belum terdaftar", "error", "$page");
        return false;

    }
    else {
        if(mysqli_num_rows($query_mtele) > 0 ) {

            $chatID = array();
            while($data_mtele = mysqli_fetch_assoc($query_mtele)) {
                $chatID[] = $data_mtele["no_user_tele"];
            }
        }
    }

    for($count = 0; $count < count($_POST["edit_id_hadir"]); $count++) {
        array(
            $update_id = $_POST["edit_id_hadir"][$count],
            $nik_hadir = substr($_POST["edit_user_hadir"][$count], 0, 10),
            $tgl_hadir = $_POST["edit_tgl_hadir"][$count],
            $update_cekold = $_POST["edit_cekold_hadir"][$count]
        );

        mysqli_query($conn, "INSERT INTO data_presensi (no_data_presensi, ref_data_presensi, nik_data_presensi, tgl_data_presensi, cekold_data_presensi) VALUES ('$docno', '$update_id', '$nik_hadir', '$tgl_hadir', '$update_cekold')");

    }
    
    mysqli_query($conn, "INSERT INTO approval_presensi (no_aprv_presensi, otp_aprv_presensi, office_aprv_presensi, date_aprv_presensi, user_aprv_presensi, ket_aprv_presensi, aksi_aprv_presensi) VALUES ('$docno', '$otp', '$office', '$date', '$user', '$ket', '$aksi')");
    
    SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $docno, $otp, $date, $user, $link.$spreadsheetId, $ket);

    return mysqli_affected_rows($conn);

}   
// End function

// ---------------------------- //

// function approve edit kehadiran
function ApproveEditKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["approve-pagepresensi"]);
    $id = mysqli_real_escape_string($conn, $data["approve-idpresensi"]);
    $user = mysqli_real_escape_string($conn, isset($data["approve-userpresensi"]) ? $data["approve-userpresensi"] : NULL);
    $otp = mysqli_real_escape_string($conn, isset($data["approve-otppresensi"]) ? $data["approve-otppresensi"] : NULL);

    $sql = "SELECT A.*, B.otp_aprv_presensi FROM data_presensi AS A 
    INNER JOIN approval_presensi AS B ON A.no_data_presensi = B.no_aprv_presensi
    WHERE A.no_data_presensi = '$id'";

    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);

    if ($data["otp_aprv_presensi"] != $otp) {
        $GLOBALS['alert'] = array("Gagal!", "Wrong OTP Code!", "error", "$page");
        return false;
    }

    require 'vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('includes/config/client_secret.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = "110xb7dSbyPLWW9HvRkKqv_iWnviAlQnZzg0om8u_SBQ";

    // OPERASI UPDATE
    $search_id = $data["ref_data_presensi"];  // Please set the value you want to search. In this case, the column "E" is searched.
    $update_cek = $data["ceknew_data_presensi"];  // Please set the value you want to put. In this case, the value is put to the column "I".
    $update_jam = $data["jam_data_presensi"];
    $update_ket = $data["ket_data_presensi"];  // Please set the value you want to put. In this case, the value is put to the column "J".
    
    $range = "SHEET_PRESENSI_IMS";
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    $rows = [];
    foreach ($values as $i => $v) {
        if ($v[2] == $search_id) {
            array_push($rows, $i);
        }
    }

    if (count($rows) > 0) {
        $data = Array_map(function($r) use($range, $update_cek, $update_jam, $update_ket) { return new Google_Service_Sheets_ValueRange([
            'range' => $range . "!J" . ($r + 1) . ":L" . ($r + 1),
            'majorDimension' => 'ROWS',
            'values' => [[$update_cek, $update_jam, $update_ket]]
        ]);}, $rows);
        $requestBody = new Google_Service_Sheets_BatchUpdateValuesRequest([
            "valueInputOption" => "USER_ENTERED",
            "data" => $data
        ]);
        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);
    };

    mysqli_query($conn, "UPDATE presensi SET cek_presensi = '$update_cek', jam_presensi = '$update_jam', ket_presensi = '$update_ket' WHERE no_presensi = '$search_id'");
    mysqli_query($conn, "UPDATE approval_presensi SET atasan_aprv_presensi = '$user', status_aprv_presensi = 'Y' WHERE no_aprv_presensi = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function approve delete kehadiran
function ApproveDeleteKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["delete-pagepresensi"]);
    $id = mysqli_real_escape_string($conn, $data["delete-idpresensi"]);
    $user = mysqli_real_escape_string($conn, isset($data["delete-userpresensi"]) ? $data["delete-userpresensi"] : NULL);
    $otp = mysqli_real_escape_string($conn, $data["delete-otppresensi"]);

    $sql_head = "SELECT otp_aprv_presensi FROM approval_presensi WHERE no_aprv_presensi = '$id'";

    $query_head = mysqli_query($conn, $sql_head);
    $data_head = mysqli_fetch_assoc($query_head);

    if ($data_head["otp_aprv_presensi"] != $otp) {
        $GLOBALS['alert'] = array("Gagal!", "Wrong OTP Code!", "error", "$page");
        return false;
    }

    $sql_detail = "SELECT ref_data_presensi FROM data_presensi WHERE no_data_presensi = '$id'";

    $result = array();
    $query_detail = mysqli_query($conn, $sql_detail);
    while($data_detail = mysqli_fetch_assoc($query_detail)) {
        $result[] = $data_detail["ref_data_presensi"];
    }
    
    require 'vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('includes/config/client_secret.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = "110xb7dSbyPLWW9HvRkKqv_iWnviAlQnZzg0om8u_SBQ";

    $range = "SHEET_PRESENSI_IMS";
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    $rows = [];

    foreach ($values as $i => $v) {
        foreach ($result as $d) {
            if ($v[2] == $d) {
                array_push($rows, $i);
            }
        }
    }
    
    if (count($rows) > 0) {
        foreach ($rows as $index) {
            $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
                [
                    "requests" => array(
                        "deleteDimension" => array(
                            "range" => array(
                                "sheetId" => 0,
                                "dimension" => "ROWS",
                                "startIndex" => $index,
                                "endIndex" => $index + 1,
                            )
                        )
                    )
                ]
            );
            $service->spreadsheets->batchUpdate($spreadsheetId, $requestBody);
        }
    }

    foreach ($result as $doc) {
        mysqli_query($conn, "DELETE FROM presensi WHERE no_presensi = '$doc'");
    }

    mysqli_query($conn, "UPDATE approval_presensi SET atasan_aprv_presensi = '$user', status_aprv_presensi = 'Y' WHERE no_aprv_presensi = '$id'");

    return mysqli_affected_rows($conn);

}   
// End function

// ---------------------------- //

// function report data kehadiran
function RepostingKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-repost"]);
    $id_presensi = $data["check_id_hadir"];
    
    require 'vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('includes/config/client_secret.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $service = new Google_Service_Sheets($client);

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);
    $spreadsheetId = $data_sheet["linkid_sheet"];

    $rows_presensi = [];
    foreach ($id_presensi as $i)  {
        $query_presensi = mysqli_query($conn, "SELECT * FROM presensi WHERE no_presensi = '$i'");
        $data_presensi = mysqli_fetch_assoc($query_presensi);
        $dataarr = array(
            $data_presensi["ts_presensi"],
            $data_presensi["aksi_presensi"],
            $data_presensi["no_presensi"],
            $data_presensi["office_presensi"],
            $data_presensi["input_presensi"],
            $data_presensi["nik_presensi"],
            $data_presensi["user_presensi"],
            $data_presensi["div_presensi"],
            $data_presensi["tgl_presensi"],
            $data_presensi["cek_presensi"],
            isset($data_presensi["jam_presensi"]) ? $data_presensi["jam_presensi"] : "",
            $data_presensi["ket_presensi"]
        );
        $rows_presensi[] = $dataarr;
    }

    // OPERASI CREATE
    $range = "SHEET_PRESENSI_IMS";

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $rows_presensi
    ]);

    $params = [
        'valueInputOption' => 'RAW'
    ];

    $insert = [
        'insertDataOption' => 'INSERT_ROWS'
    ];

    $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params, $insert);

    return $result;

}   
// End function

// ---------------------------- //

// function reject kehadiran
function RejectDataKehadiran($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["reject-pagepresensi"]);
    $id = mysqli_real_escape_string($conn, $data["reject-docnopresensi"]);
    $otp = mysqli_real_escape_string($conn, $data["reject-otppresensi"]);

    $sql = "SELECT otp_aprv_presensi FROM approval_presensi WHERE no_aprv_presensi = '$id'";

    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);

    if ($data["otp_aprv_presensi"] != $otp) {
        $GLOBALS['alert'] = array("Gagal!", "Wrong OTP Code!", "error", "$page");
        return false;
    }
    
    if (substr($id, 0, 1) == "U") {
        mysqli_query($conn, "DELETE FROM presensi WHERE aksi_presensi = '$id'");
    }

    mysqli_query($conn, "DELETE FROM data_presensi WHERE no_data_presensi = '$id'");
    mysqli_query($conn, "DELETE FROM approval_presensi WHERE no_aprv_presensi = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function cancel kehadiran
function CancelDataKehadiran($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["reject-docnopresensi"]);
    
    mysqli_query($conn, "DELETE FROM data_presensi WHERE no_data_presensi = '$id'");
    mysqli_query($conn, "DELETE FROM approval_presensi WHERE no_aprv_presensi = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert data master jenis mobil
function InsertJenisMobil($data) {

    global $conn;

    // Input data post
    $jenis = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["jenis-mobil"])));
    $no = autonum(2, 'no_jns_mobil ', 'jenis_mobil');

    mysqli_query($conn, "INSERT INTO jenis_mobil (no_jns_mobil, name_jns_mobil) VALUES ('$no', '$jenis')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete master jenis mobil
function DeleteJenisMobil($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["del-idjnsmobil"]);
    
    mysqli_query($conn, "DELETE FROM jenis_mobil WHERE id_jns_mobil = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function resend approval otp absensi
function ResendOTPAbsensi($data) {

    global $conn;

    $docno = mysqli_real_escape_string($conn, $data["id-resendotp"]);
    $transk = htmlspecialchars($data["trans-resendotp"]);
    $page = $data["page-resendotp"];

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];
    
    $reotp = random_int(100000, 999999);

    mysqli_query($conn, "UPDATE approval_presensi SET otp_aprv_presensi = '$reotp' WHERE no_aprv_presensi = '$docno'");

    $sql = "SELECT no_aprv_presensi, office_aprv_presensi, otp_aprv_presensi, aksi_aprv_presensi, date_aprv_presensi, user_aprv_presensi, ket_aprv_presensi FROM approval_presensi WHERE no_aprv_presensi = '$docno'";

    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    $office = $result["office_aprv_presensi"];
    $aksi = $result["aksi_aprv_presensi"];
    $otp = $result["otp_aprv_presensi"];
    $date = $result["date_aprv_presensi"];
    $user = $result["user_aprv_presensi"];
    $ket = $result["ket_aprv_presensi"];
    $nik = substr($user, 0, 10);

    $query_headiv = mysqli_query($conn, "SELECT B.id_head_divisi, C.name_head_div FROM users AS A 
    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
    WHERE A.nik = '$nik'");

    $data_headiv = mysqli_fetch_assoc($query_headiv);

    $idheadiv = isset($data_headiv["id_head_divisi"]) ? $data_headiv["id_head_divisi"] : NULL;
    $nameheadiv = $data_headiv["name_head_div"];

    $query_mtele = mysqli_query($conn, "SELECT * FROM user_telebot WHERE office_user_tele = '$office' AND role_user_tele = '$transk' AND div_user_tele = '$idheadiv' AND status_user_tele = 'Y'");

    if(mysqli_num_rows($query_mtele) > 0 ) {

        $chatID = array();
        while($data_mtele = mysqli_fetch_assoc($query_mtele)) {
            $chatID[] = $data_mtele["no_user_tele"];
        }
    }

    return SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $docno, $otp, $date, $user, $link.$spreadsheetId, $ket);

}   
// End function

// ---------------------------- //

// function send message telebot
function SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $docno, $otp, $date, $username, $link, $ket) {

    global $conn;

    $query_mtele = mysqli_query($conn, "SELECT * FROM master_telebot");
    $data_mtele = mysqli_fetch_assoc($query_mtele);

    $apiToken = $data_mtele["token_mstr_telebot"];
    $uname = $data_mtele["uname_mstr_telebot"];

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");
    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $subject = $data_sheet["subject_sheet"];
    $link = $data_sheet["link_sheet"].$data_sheet["linkid_sheet"];

    if ($aksi == "I") {
        $desc_aksi = "INPUT ABSENSI";
        $apprv = "<s>PERLU</s> / TIDAK";
    }
    elseif ($aksi == "E") {
        $desc_aksi = "EDIT ABSENSI";
        $apprv = "PERLU / <s>TIDAK</s>";

        // $replyMarkup = [
        //     'inline_keyboard' => [
        //         [
        //             ['text' => 'Yes', 'callback_data' => 'approve_yes'],
        //             ['text' => 'No', 'callback_data' => 'approve_no']
        //         ]
        //     ]
        // ];
        // if (isset($replyMarkup)) {
        //     $dataReply = [
        //         'chat_id' => $dataids,
        //         'text' => "Apakah anda menyetujui pengajuan data tersebut?",
        //         'reply_markup' => json_encode($replyMarkup),
        //         'parse_mode' => 'HTML'
        //     ];
        //     file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($dataReply));
        // }
    }
    elseif ($aksi == "D") {
        $desc_aksi = "DELETE ABSENSI";
        $apprv = "PERLU / <s>TIDAK</s>";
    }
    elseif ($aksi == "U") {
        $desc_aksi = "UPDATE JADWAL";
        $apprv = "PERLU / <s>TIDAK</s>";
    }

    if ($otp === "") {
        $descotp = "-";
        $url = "#";
        $linkname = "-";
    }
    else {
        $descotp = '<tg-spoiler>'.$otp.'</tg-spoiler>';
        $code = encrypt($docno);
        $url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/approval-presensi.php?code=$code";
        $linkname = "LINK";
    }

$string = '<u><b>'.$subject.'</b></u>

<b>AKSI : </b>'.$desc_aksi.'
<b>DOCNO : </b>'.$docno.'

<b>OFFICE CODE : </b>'.$office.'
<b>DIVISI : </b>'.$nameheadiv.'
<b>TANGGAL : </b>'.$date.'
<b>PENGAJUAN : </b><i>'.$username.'</i>
<b>KETERANGAN : </b>'.$ket.'

<b>APPROVAL : </b>'.$apprv.'
<b>OTP CODE : </b>'.$descotp.'
<b>LINK APPROVAL : </b><a href="'.$url.'">'.$linkname.'</a>
<b>LINK GSHEET : </b><a href="'.$link.'">LINK</a>

<b>Note : </b>
<i>Pesan ini hanya notifikasi, untuk melakukan approval melalui link ims approval presensi.</i>';

    foreach($chatID as $dataids) {

        $dataContent = [
            'chat_id' => $dataids,
            'text' => $string,
            'parse_mode' => "html"
        ];

        $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($dataContent));

    }

    return $response;

}
// End function delete

// ---------------------------- //

// function insert master telegrambot
function InsertMasterTeleBot($data) {

    global $conn;

    // Input data post
    $uname = htmlspecialchars($data["uname-masterbot"]);
    $token = htmlspecialchars($data["token-masterbot"]);
    $webhook = htmlspecialchars($data["webhook-masterbot"]);

    mysqli_query($conn, "INSERT INTO master_telebot (uname_mstr_telebot, webhook_mstr_telebot, token_mstr_telebot) VALUES ('$uname', '$webhook', '$token')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert master service API
function InsertMasterServiceAPI($data) {

    global $conn;

    // Input data post
    $name = htmlspecialchars($data["name-apiservice"]);
    $url = htmlspecialchars($data["url-apiservice"]);

    mysqli_query($conn, "INSERT INTO service_api (name_srv_api, url_srv_api) VALUES ('$name', '$url')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete master telegrambot
function DeleteMasterTeleBot($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["iddel-mastertelebot"]);
    
    mysqli_query($conn, "DELETE FROM master_telebot WHERE id_mstr_telebot = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function delete master service API
function DeleteMasterServiceAPI($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["iddel-masterapi"]);
    
    mysqli_query($conn, "DELETE FROM service_api WHERE id_srv_api = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert role transaksi
function InsertRoleTransaksi($data) {

    global $conn;

    // Input data post
    $page = $data["page-insroletelebot"];
    $id = autonum(4, 'no_role_trans', 'role_transaksi');
    $initial = htmlspecialchars(strtoupper($data["initial-insroletelebot"]));
    $rolename = htmlspecialchars($data["name-insroletelebot"]);

    if (strlen($initial) != 3) {
        $GLOBALS['alert'] = array("Gagal!", "Inisial transaksi wajib 3 digit huruf", "error", "$page");
        return false;
    }

    mysqli_query($conn, "INSERT INTO role_transaksi (no_role_trans, inisial_role_trans, name_role_trans) VALUES ('$id', '$initial', '$rolename')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update role transaksi
function UpdateRoleTransaksi($data) {

    global $conn;

    // Input data post
    $page = $data["page-updroletelebot"];
    $id = htmlspecialchars($data["id-updroletelebot"]);
    $initial = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["initial-updroletelebot"])));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $data["name-updroletelebot"]));

    if (strlen($initial) != 3) {
        $GLOBALS['alert'] = array("Gagal!", "Inisial transaksi wajib 3 digit huruf", "error", "$page");
        return false;
    }

    mysqli_query($conn, "UPDATE role_transaksi SET inisial_role_trans = '$initial', name_role_trans = '$name' WHERE id_role_trans = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function delete role transaksi
function DeleteRoleTransaksi($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["id-delroletelebot"]);
    
    mysqli_query($conn, "DELETE FROM role_transaksi WHERE id_role_trans = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert user telebot
function InsertTelebot($data) {

    global $conn;

    // Input data post
    $page = $data["page-insusertelebot"];
    $role = htmlspecialchars($data["role-insusertelebot"]);
    $div = htmlspecialchars($data["div-insusertelebot"]);
    $office = htmlspecialchars($data["office-insusertelebot"]);
    $id = htmlspecialchars($data["id-insusertelebot"]);
    $nik = htmlspecialchars($data["nik-insusertelebot"]);
    $sts = htmlspecialchars($data["status-insusertelebot"]);

    if (strlen($id) != 9) {
        $GLOBALS['alert'] = array("Gagal!", "Harap periksa ID telegram", "error", "$page");
        return false;
    }

    $query_mtele = mysqli_query($conn, "SELECT nik_user_tele, no_user_tele FROM user_telebot WHERE role_user_tele = '$role' AND div_user_tele = '$div' AND office_user_tele = '$office' AND nik_user_tele = '$nik' AND no_user_tele = '$id'");

    if (mysqli_num_rows($query_mtele) === 1) {

        $GLOBALS['alert'] = array("Gagal!", "NIK ".$nik." Telah terdaftar di role transaksi ".$role, "error", "$page");
        return false;

    }

    mysqli_query($conn, "INSERT INTO user_telebot (role_user_tele, div_user_tele, no_user_tele, office_user_tele, nik_user_tele, status_user_tele) VALUES ('$role', '$div', '$id', '$office', '$nik', '$sts')");

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update user telegram
function UpdateTelebot($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["id-updusertelebot"]);
    $sts = mysqli_real_escape_string($conn, $data["status-updusertelebot"]);

    mysqli_query($conn, "UPDATE user_telebot SET status_user_tele = '$sts' WHERE id_user_tele = '$id'");

    return mysqli_affected_rows($conn);

}
// End function update

// ---------------------------- //

// function delete user telegram
function DeleteTelebot($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["id-delusertelebot"]);
    
    mysqli_query($conn, "DELETE FROM user_telebot WHERE id_user_tele = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert perubahan jadwal
function InputPerubahanJadwal($data) {

    global $conn;

    // Input data post
    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-posting"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-posting"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-posting"]));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $data["user-posting"]));
    $nik = substr($user, 0, 10);
    $transk = htmlspecialchars($data["trans-posting"]);
    $bag_hadir = htmlspecialchars($data["bagian-posting"]);
    $date = date("Y-m-d H:i:s");
    $aksi = "U";
    $idmax = autonum(6, 'no_aprv_presensi', 'approval_presensi');
    $doc_app = $aksi."-".$idmax;

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];
    
    $otp = random_int(100000, 999999);
    
    if (!isset($data["edit2_tgl_hadir"])) {
        $GLOBALS['alert'] = array("Gagal!", "Data perubahan jadwal belum ada yang dipilih", "error", "$page");
        return false;
    }

    $query_headiv = mysqli_query($conn, "SELECT B.id_head_divisi, C.name_head_div FROM users AS A 
    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
    WHERE A.nik = '$nik'");

    $data_headiv = mysqli_fetch_assoc($query_headiv);

    $idheadiv = isset($data_headiv["id_head_divisi"]) ? $data_headiv["id_head_divisi"] : NULL;
    $nameheadiv = $data_headiv["name_head_div"];

    $query_mtele = mysqli_query($conn, "SELECT * FROM user_telebot WHERE office_user_tele = '$office' AND role_user_tele = '$transk' AND div_user_tele = '$idheadiv' AND status_user_tele = 'Y'");

    if (mysqli_num_rows($query_mtele) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "User penerima notifkasi telegram bot belum terdaftar", "error", "$page");
        return false;

    }
    else {
        if(mysqli_num_rows($query_mtele) > 0 ) {

            $chatID = array();
            while($data_mtele = mysqli_fetch_assoc($query_mtele)) {
                $chatID[] = $data_mtele["no_user_tele"];
            }
        }
    }

    for($countcek = 0; $countcek < count($data["edit2_nik_hadir"]); $countcek++) {
        array(
            $nikcek = $_POST["edit2_nik_hadir"][$countcek],
            $tglcek = $_POST["edit2_tgl_hadir"][$countcek],
            $jadwalcek = $_POST["edit2_jadwal_hadir"][$countcek],
            $rubahcek = $_POST["edit2_rubah_hadir"][$countcek]
        );

        if ($jadwalcek === $rubahcek) {
            $GLOBALS['alert'] = array("Gagal!", "Jadwal tanggal exist dengan jadwal perubahan tidak boleh sama", "error", "$page");
            return false;
        }

        $query_cekhadir = mysqli_query($conn, "SELECT nik_presensi, user_presensi FROM presensi WHERE office_presensi = '$office' AND dept_presensi = '$dept' AND nik_presensi = '$nikcek' AND tgl_presensi = '$tglcek'");

        if(mysqli_num_rows($query_cekhadir) > 0 ) {
            
            $GLOBALS['alert'] = array("Gagal!", "User ".$nikcek." Tanggal ".$tglcek." sudah input status kehadiran", "error", "$page");
            return false;
        }
    }

    $values = array();
    
    for($count = 0; $count < count($data["edit2_nik_hadir"]); $count++) {

        $dataarr = array(
            $date,
            $doc_app,
            $docno = autonum(6, 'no_presensi', 'presensi'),
            $office,
            $user,
            $nik_hadir = substr($data["edit2_nik_hadir"][$count], 0, 10),
            $user_hadir = substr($data["edit2_nik_hadir"][$count], 13),
            $bag_hadir,
            $tgl_hadir = $data["edit2_tgl_hadir"][$count],
            $cek_hadir = $data["edit2_jadwal_hadir"][$count] == "OFF" ? "TUKAR OFF" : ($data["edit2_rubah_hadir"][$count] == "OFF" ? "TUKAR OFF" : "RUBAH SHIFT"),
            $jam_hadir = strtoupper($data["edit2_rubah_hadir"][$count]),
            $ket_hadir = strtoupper($data["edit2_ket_hadir"][$count])
        );

		$values[] = $dataarr;

        mysqli_query($conn, "INSERT INTO data_presensi (no_data_presensi, ref_data_presensi, nik_data_presensi, tgl_data_presensi, ceknew_data_presensi, jam_data_presensi, ket_data_presensi) VALUES ('$doc_app', '$docno', '$nik_hadir', '$tgl_hadir', '$cek_hadir', '$jam_hadir', '$ket_hadir')");

        mysqli_query($conn, "INSERT INTO presensi (aksi_presensi, no_presensi, office_presensi, dept_presensi, input_presensi, nik_presensi, user_presensi, div_presensi, tgl_presensi, cek_presensi, jam_presensi, ket_presensi, status_presensi) VALUES ('$doc_app', '$docno', '$office', '$dept', '$user', '$nik_hadir', '$user_hadir', '$bag_hadir', '$tgl_hadir', '$cek_hadir', '$jam_hadir', '$ket_hadir', 'Y')");

    }

    mysqli_query($conn, "INSERT INTO approval_presensi (no_aprv_presensi, office_aprv_presensi, otp_aprv_presensi, date_aprv_presensi, user_aprv_presensi, aksi_aprv_presensi) VALUES ('$doc_app', '$office', '$otp', '$date', '$user', '$aksi')");

    SendMessageTelebot($chatID, $office, $nameheadiv, $aksi, $doc_app, $otp, $date, $user, $link.$spreadsheetId, "");

    return mysqli_affected_rows($conn);

}   
// End function

// ---------------------------- //

// function approve perubahan jadwal
function ApprovePerubahanJadwal($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["update-pagepresensi"]);
    $id = mysqli_real_escape_string($conn, $data["update-idpresensi"]);
    $user = mysqli_real_escape_string($conn, isset($data["update-userpresensi"]) ? $data["update-userpresensi"] : NULL);
    $otp = mysqli_real_escape_string($conn, $data["update-otppresensi"]);

    $query_sheet = mysqli_query($conn, "SELECT * FROM sheet");

    if (mysqli_num_rows($query_sheet) === 0) {

        $GLOBALS['alert'] = array("Gagal!", "Link google sheet belum terdaftar", "error", "$page");
        return false;

    }

    $data_sheet = mysqli_fetch_assoc($query_sheet);

    $link = $data_sheet["link_sheet"];
    $spreadsheetId = $data_sheet["linkid_sheet"];

    $sql_head = "SELECT otp_aprv_presensi FROM approval_presensi WHERE no_aprv_presensi = '$id'";

    $query_head = mysqli_query($conn, $sql_head);
    $data_head = mysqli_fetch_assoc($query_head);

    if ($data_head["otp_aprv_presensi"] != $otp) {
        $GLOBALS['alert'] = array("Gagal!", "Wrong OTP Code!", "error", "$page");
        return false;
    }

    $query_detail = mysqli_query($conn, "SELECT * FROM presensi WHERE aksi_presensi = '$id'");
    
    $values = array();

    while($data_detail = mysqli_fetch_assoc($query_detail)) {

        $arrdata = array(
            $data_detail["ts_presensi"],
            $data_detail["aksi_presensi"],
            $data_detail["no_presensi"],
            $data_detail["office_presensi"],
            $data_detail["input_presensi"],
            $data_detail["nik_presensi"],
            $data_detail["user_presensi"],
            $data_detail["div_presensi"],
            $data_detail["tgl_presensi"],
            $data_detail["cek_presensi"],
            $data_detail["jam_presensi"],
            $data_detail["ket_presensi"]
        );
        
        $values[] = $arrdata;
    }

    require 'vendor/autoload.php';

    $client = new Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('includes/config/client_secret.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $service = new Google_Service_Sheets($client);
    
    // OPERASI CREATE
    $range = "SHEET_PRESENSI_IMS";

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $params = [
        'valueInputOption' => 'RAW'
    ];

    $insert = [
        'insertDataOption' => 'INSERT_ROWS'
    ];

    $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params, $insert);
    
    mysqli_query($conn, "UPDATE presensi SET status_presensi = NULL WHERE aksi_presensi = '$id'");
    mysqli_query($conn, "UPDATE approval_presensi SET atasan_aprv_presensi = '$user', status_aprv_presensi = 'Y' WHERE no_aprv_presensi = '$id'");
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert data master aplikasi
function InsertMasterApplication($data) {

    global $conn;

    // Input data post
    $page = $data["page-mstr-app"];
    $office = htmlspecialchars($data["office-mstr-app"]);
    $dept = htmlspecialchars($data["dept-mstr-app"]);

    $code = "A-";

    if (!isset($data["insmstr_jenis_app"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada data master aplikasi yang dipilih", "error", "$page");
        return false;
    }

    if(count(array_unique($data["insmstr_name_app"], SORT_REGULAR)) < count($data["insmstr_name_app"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat penginputan nama aplikasi yang duplicate", "error", "$page");
        return false;
    }

    $peruntukan = array_values($data["insmstr_peruntukan_app"]);
    
    for($c = 0; $c < count($peruntukan); $c++) {
        
        $id = autonum(6, 'code_app', 'master_app');
        $newid = $code.$id;

        foreach ($peruntukan[$c] as $a) {
            $idsub = substr($a.'-', 0, strpos($a, '-'));

            mysqli_query($conn, "INSERT INTO divisi_app (code_div_app, subdiv_div_app) VALUES ('$newid', '$idsub')"); 
        }

        $strdiv = [];

        foreach($peruntukan[$c] as $b) {
            $strdiv[] = substr(strstr($b, '-'), 1);
        }
        
        $res = implode(', ', $strdiv);

        $jenis = $data["insmstr_jenis_app"][$c];
        $name = htmlspecialchars(strtoupper($data["insmstr_name_app"][$c]));
        $dev = $data["insmstr_develop_app"][$c];
        $fungsi = htmlspecialchars(strtoupper($data["insmstr_func_app"][$c]));
        $basis = $data["insmstr_basis_app"][$c];

        mysqli_query($conn, "INSERT INTO master_app (code_app, jenis_app, office_app, dept_app, name_app, develop_app, func_app, peruntukan_app, basis_app) VALUES ('$newid', '$jenis', '$office', '$dept', '$name', '$dev', '$fungsi', '$res', '$basis')");

    }
    
    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update data master aplikasi
function UpdateMasterApplication($data) {

    global $conn;

    // Input data post
    $page = $data["page-upd-mstrapp"];
    $id = $data["id-upd-mstrapp"];
    $off = $data["office-upd-mstrapp"];
    $dpt = $data["dept-upd-mstrapp"];
    $code = htmlspecialchars($data["code-upd-mstrapp"]);
    $name = htmlspecialchars(strtoupper($data["name-upd-mstrapp"]));
    $nameold = htmlspecialchars($data["nameold-upd-mstrapp"]);
    $func = htmlspecialchars(strtoupper($data["func-upd-mstrapp"]));
    $for = $data["for-upd-mstrapp"];

    if ($name != $nameold) {
        $sql = "SELECT name_app FROM master_app WHERE office_app = '$off' AND dept_app = '$dpt' AND name_app = '$name'";
        $query = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($query) > 0 ) {
            $GLOBALS['alert'] = array("Gagal!", "Nama Aplikasi ".$name." Sudah Terdaftar", "error", "$page");
            return false;
        }
    }

    if (isset($data["useupdmstrapp"])) {
        $use = $data["useupdmstrapp"];
        $strdiv = [];
        mysqli_query($conn, "DELETE FROM divisi_app WHERE code_div_app = '$code'");
        foreach($use as $s) {
            $strdiv[] = substr(strstr($s, '-'), 1);
            $strd = substr($s.'-', 0, strpos($s, '-'));
            
            mysqli_query($conn, "INSERT INTO divisi_app (code_div_app, subdiv_div_app) VALUES ('$code', '$strd')"); 
        }
        $res = implode(', ', $strdiv);
    }
    else {
        $res = $for;
    }

    mysqli_query($conn, "UPDATE master_app SET name_app = '$name', func_app = '$func', peruntukan_app = '$res' WHERE id_app = '$id'");
    
    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function delete master aplikasi
function DeleteMasterApplication($data) {

    global $conn;

    $page = mysqli_real_escape_string($conn, $data["page-del-mstrapp"]);
    $code = mysqli_real_escape_string($conn, $data["code-del-mstrapp"]);
    $id = mysqli_real_escape_string($conn, $data["id-del-mstrapp"]);

    $sql = "SELECT id_code_app FROM version_app WHERE id_code_app = '$code'";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Master aplikasi ".$code." telah terdaftar di master version!", "error", "$page");
        return false;
    }
    
    mysqli_query($conn, "DELETE FROM divisi_app WHERE code_div_app = '$code'");
    mysqli_query($conn, "DELETE FROM master_app WHERE id_app = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function insert data versi aplikasi
function InsertVersiApplication($data) {

    global $conn;

    // Input data post
    $page = $data["page-insversi-app"];
    $user = htmlspecialchars($data["user-insversi-app"]);
    $name = substr(htmlspecialchars($data["name-insversi-app"]), 0, 8);
    $rilis = htmlspecialchars($data["rilis-insversi-app"]);
    $version = htmlspecialchars(strtoupper($data["versi-insversi-app"]));
    $fitur = htmlspecialchars(strtoupper($data["fitur-insversi-app"]));
    $info = htmlspecialchars($data["info-insversi-app"]);
    $use = htmlspecialchars($data["use-insversi-app"]);
    $idmanual = autoid("0", "6", "manual_ver_app", "version_app");
    
    $querymstr = mysqli_query($conn, "SELECT name_app FROM master_app WHERE code_app = '$name'");
    $datamstr = mysqli_fetch_assoc($querymstr);

    if (isset($data["web-insversi-app"])) {
        $master = strtolower($data["web-insversi-app"]);
    }
    else {
        if ($_FILES['nonweb-insversi-app']['error'] !== 4) {
    
            $master = UploadMasterApp($name."_".$datamstr["name_app"], $version, $page);
    
            if ($master === FALSE) {
                return FALSE;
            }
        }
    }

    if ($_FILES['manual-insversi-app']['error'] !== 4) {

        $manual = UploadUserManual($idmanual."_".$datamstr["name_app"], $page);

        if ($manual === FALSE) {
            return FALSE;
        }
    }
    else {
        $manual = "";
    }

    if ($use == "Y") {
        mysqli_query($conn, "UPDATE version_app SET use_ver_app = 'N' WHERE id_code_app = '$name'");
    }

    mysqli_query($conn, "INSERT INTO version_app (id_code_app, user_ver_app, rilis_ver_app, version_ver_app, fitur_ver_app, info_ver_app, source_ver_app, use_ver_app, manual_ver_app) VALUES ('$name', '$user', '$rilis', '$version', '$fitur', '$info', '$master', '$use', '$manual')");
    
    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update data versi aplikasi
function UpdateVersiApplication($data) {

    global $conn;

    // Input data post
    $page = $data["page-upd-verapp"];
    $user = htmlspecialchars($data["user-upd-verapp"]);
    $id = $data["id-upd-verapp"];
    $code = htmlspecialchars($data["code-upd-verapp"]);
    $name = htmlspecialchars($data["name-upd-verapp"]);
    $version = htmlspecialchars(strtoupper($data["versi-upd-verapp"]));
    $rilis = htmlspecialchars($data["rilis-upd-verapp"]);
    $fitur = htmlspecialchars(strtoupper($data["fitur-upd-verapp"]));
    $info = isset($data["info-upd-verapp"]) ? htmlspecialchars($data["info-upd-verapp"]) : $data["infohide-upd-verapp"];
    $use = isset($data["use-upd-verapp"]) ? htmlspecialchars($data["use-upd-verapp"]) : $data["usehide-upd-verapp"];
    $apl = $data["apl-upd-verapp"];

    if (isset($data["web-upd-verapp"])) {
        $master = strtolower($data["web-upd-verapp"]);
    }
    else {
        $error = isset($_FILES['nonweb-insversi-app']['error']) ? $_FILES['nonweb-insversi-app']['error'] : NULL;
        if ($error !== 4) {
    
            $master = UploadMasterApp($code."_".$name, $version, $page);
    
            if ($master === FALSE) {
                return FALSE;
            }
        }
        else {
            $master = $apl;
        }
    }

    $codenum = autoid("0", "6", "manual_ver_app", "version_app");
    $errormnl = isset($_FILES['manual-insversi-app']['error']) ? $_FILES['manual-insversi-app']['error'] : NULL;
    $mnl = $data["mnl-upd-verapp"] == "" ? $codenum."_".$name : substr($data["mnl-upd-verapp"], 0, 6)."_".$name;

    if ($errormnl !== 4) {


        $manual = UploadUserManual($mnl, $page);

        if ($manual === FALSE) {
            return FALSE;
        }
    }
    else {
        $manual = $data["mnl-upd-verapp"];
    }

    if ($use == "Y") {
        mysqli_query($conn, "UPDATE version_app SET use_ver_app = 'N' WHERE id_code_app = '$code'");
    }

    mysqli_query($conn, "UPDATE version_app SET user_ver_app = '$user', rilis_ver_app = '$rilis', version_ver_app = '$version', fitur_ver_app = '$fitur', info_ver_app = '$info', source_ver_app = '$master', use_ver_app = '$use', manual_ver_app = '$manual' WHERE id_ver_app = '$id'");
    
    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// Function upload master aplikasi
function UploadMasterApp($source, $version, $page) {

    $name = isset($_FILES['nonweb-insversi-app']['name']) ? $_FILES['nonweb-insversi-app']['name'] : NULL;
    $size = isset($_FILES['nonweb-insversi-app']['size']) ? $_FILES['nonweb-insversi-app']['size'] : NULL;
    $tmp = isset($_FILES['nonweb-insversi-app']['tmp_name']) ? $_FILES['nonweb-insversi-app']['tmp_name'] : NULL;

    $eksvalid = ["zip"];
    $eks = explode('.', $name);
    $eks = strtolower(end($eks));

    if(!in_array($eks, $eksvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format zip", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 30000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 10MB", "error", "$page");
        return false;
    
    }

    $file = $source."_".$version;
    $file .= '.';
    $file .= $eks;

    move_uploaded_file($tmp, 'files/source/' . $file);

    return $file;

}
// End function

// ---------------------------- //

// Function upload user manual
function UploadUserManual($manual, $page) {

    $name = isset($_FILES['manual-insversi-app']['name']) ? $_FILES['manual-insversi-app']['name'] : NULL;
    $size = isset($_FILES['manual-insversi-app']['size']) ? $_FILES['manual-insversi-app']['size'] : NULL;
    $error = isset($_FILES['manual-insversi-app']['error']) ? $_FILES['manual-insversi-app']['error'] : NULL;
    $tmp = isset($_FILES['manual-insversi-app']['tmp_name']) ? $_FILES['manual-insversi-app']['tmp_name'] : NULL;

    $eksvalid = ["pdf", "ppt", "pptx"];
    $eks = explode('.', $name);
    $eks = strtolower(end($eks));

    if(!in_array($eks, $eksvalid)) {

        $GLOBALS['alert'] = array("Gagal!", "File yang anda upload bukan format pdf, ppt atau pptx", "error", "$page");
        return false;

    }

    $maxsize = 1024 * 10000; // maksimal 1000 KB (1KB = 1024 Byte)

    if($size >= $maxsize || $size == 0) {

        $GLOBALS['alert'] = array("Gagal!", "File yang di upload tidak boleh lebih dari 10MB", "error", "$page");
        return false;
    
    }

    $dir = 'files/manual/';

    $filemnl = $dir.$manual;

    if(file_exists($filemnl)) {
        unlink($filemnl);
    }
    
    $file = $manual;
    $file .= '.';
    $file .= $eks;

    move_uploaded_file($tmp, $dir . $file);

    return $file;

}
// End function

// ---------------------------- //

// function delete master version
function DeleteVersiApplication($data) {

    global $conn;

    $id = mysqli_real_escape_string($conn, $data["id-del-verapp"]);
    $source = mysqli_real_escape_string($conn, $data["source-del-verapp"]);
    $manual = mysqli_real_escape_string($conn, $data["manual-del-verapp"]);

    mysqli_query($conn, "DELETE FROM version_app WHERE id_ver_app = '$id'");
    
    if ($manual != "") {
        $dirmnl = 'files/manual/';
        $filemnl = $dirmnl.$manual;
    
        if(file_exists($filemnl)) {
            unlink($filemnl);
        }
    }

    $dirsrc = 'files/source/';
    $filesrc = $dirsrc.$source;

    if(file_exists($filesrc)) {
        unlink($filesrc);
    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function Update Multiple Users
function UpdateDataUserMultiple($data) {

    global $conn;
    
    for($countarr = 0; $countarr < count($data["upduser_id"]); $countarr++) {

        $id = $data["upduser_id"][$countarr];
        $name = $data["upduser_name"][$countarr];
        $office = $data["upduser_office"][$countarr];
        $dept = $data["upduser_dept"][$countarr];
        $div = $data["upduser_divisi"][$countarr];
        $group = $data["upduser_group"][$countarr];
        $level = $data["upduser_level"][$countarr];

        mysqli_query($conn, "UPDATE users SET full_name = '$name', id_office = '$office', id_department = '$dept', id_divisi = '$div', id_group = '$group', id_level = '$level' WHERE id = '$id'");
    }

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

function ProsesPengajuanTablok($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars($data["id-tablokbarang"]);
    $user = htmlspecialchars($data["user-tablokbarang"]);

    $sql = "UPDATE st_dpd_head SET pic_st_dpd = '$user' WHERE id_st_dpd = '$id'";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function insert master indikator penilaian
function InsertMasterIndikatorAssessment($data) {

    global $conn;

    // Input data post
    $page = $data["page-indikator"];
    $office = htmlspecialchars($data["office-indikator"]);
    $numb = htmlspecialchars($data["numb-indikator"]);
    $name = htmlspecialchars(strtoupper($data["name-indikator"]));

    $sql = "SELECT num_ind_assest FROM indicator_assessment WHERE num_ind_assest = '$numb'";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Nomor urutan indikator penilaian ".$numb." telah terdaftar!", "error", "$page");
        return false;
    }

    $sql = "INSERT INTO indicator_assessment (office_ind_assest, num_ind_assest, name_ind_assest) VALUES ('$office', '$numb', '$name')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function insert master indikator penilaian
function UpdateMasterIndikatorAssessment($data) {

    global $conn;

    // Input data post
    $page = $data["page-updindpenilaian"];
    $id = htmlspecialchars($data["id-updindpenilaian"]);
    $numbold = htmlspecialchars($data["numbold-updindpenilaian"]);
    $numb = htmlspecialchars($data["numb-updindpenilaian"]);
    $name = htmlspecialchars(strtoupper($data["name-updindpenilaian"]));

    if ($numb != $numbold) {
        $sql = "SELECT num_ind_assest FROM indicator_assessment WHERE num_ind_assest = '$numb'";
        $query = mysqli_query($conn, $sql);
    
        if(mysqli_num_rows($query) > 0 ) {
            $GLOBALS['alert'] = array("Gagal!", "Nomor urutan indikator penilaian ".$numb." telah terdaftar!", "error", "$page");
            return false;
        }
    }

    mysqli_query($conn, "UPDATE indicator_assessment SET num_ind_assest = '$numb', name_ind_assest = '$name' WHERE id_ind_assest = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Delete master indikator penilaian
function DeleteMasterIndikatorAssessment($data) {

    global $conn;

    $page = $data["page-delindpenilaian"];
    $id = htmlspecialchars($data["id-delindpenilaian"]);

    $result = mysqli_query($conn, "SELECT id_head_ind_assest FROM instrument_assessment WHERE id_head_ind_assest = '$id'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Indikator penilaian ".$id." tidak dapat di hapus, karena telah memiliki instrumen penilaian", "error", "$page");
        return false;
    }
    
    mysqli_query($conn, "DELETE FROM indicator_assessment WHERE id_ind_assest = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete

// ---------------------------- //

// function insert master instrumen penilaian
function InsertMasterInstrumentAssessment($data) {

    global $conn;

    $id = htmlspecialchars($data["id-indikator"]);
    $name = htmlspecialchars(strtoupper($data["name-instrumen"]));
    $poin = $data["poininstrumen"];
    $datapoin = implode(", ", $poin);
    $idmax = autonum(4, 'code_ins_assest', 'instrument_assessment');

    foreach ($poin as $arr)  {
        mysqli_query($conn, "INSERT INTO poin_assessment (id_head_ins_assest, value_poin_assest) VALUES ('$idmax', '$arr')");
    }

    $sql = "INSERT INTO instrument_assessment (code_ins_assest, id_head_ind_assest, name_ins_assest, poin_ins_assest) VALUES ('$idmax', '$id', '$name', '$datapoin')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);
}   
// End function

// ---------------------------- //

// function update master indikator penilaian
function UpdateMasterInstrumentAssessment($data) {

    global $conn;

    // Input data post
    $code = htmlspecialchars($data["id-updateinspenilaian"]);
    $id = htmlspecialchars($data["idn-updateinspenilaian"]);
    $name = htmlspecialchars(strtoupper($data["name-updateinspenilaian"]));
    $poin = $data["poinupdateinspenilaian"];
    $datapoin = implode(", ", $poin);

    mysqli_query($conn, "DELETE FROM poin_assessment WHERE id_head_ins_assest = '$code'");

    foreach ($poin as $arr)  {
        mysqli_query($conn, "INSERT INTO poin_assessment (id_head_ins_assest, value_poin_assest) VALUES ('$code', '$arr')");
    }

    mysqli_query($conn, "UPDATE instrument_assessment SET id_head_ind_assest = '$id', name_ins_assest = '$name', poin_ins_assest = '$datapoin' WHERE code_ins_assest = '$code'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Delete master instrumen penilaian
function DeleteMasterInstrumentAssessment($data) {

    global $conn;

    $id = $data["id-delinspenilaian"];
    
    mysqli_query($conn, "DELETE FROM poin_assessment WHERE id_head_ins_assest = '$id'");
    mysqli_query($conn, "DELETE FROM instrument_assessment WHERE code_ins_assest = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function proses buka penilaian tahunan per divisi
function InsertPeriodePenilaianTahunan($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["department"]));
    $div = $data["divisi"];
    $pic = htmlspecialchars(mysqli_real_escape_string($conn, $data["pic"]));
    $idmax = autonum(4, 'code_sts_assest', 'statusassessment');
    $divisi = implode(", ", $div);
    $date   = date("Y-m-d H:i:s");
 
    $query = mysqli_query($conn, "SELECT * FROM statusassessment WHERE office_sts_assest = '$office' AND dept_sts_assest = '$dept' AND tahun_sts_assest = '$tahun'");

    if($data = mysqli_fetch_assoc($query)) {

        $GLOBALS['alert'] = array("Gagal!", "Office ".$data["office_sts_assest"]." department ".$data["dept_sts_assest"]." sudah create penilaian periode tahun ".$data["tahun_sts_assest"], "error", "$page");
        return false;
    
    }

    $quoteddiv = array_map(function($value) {
        return "'".substr($value, 0, 4)."'";
    }, $div);

    $data_div = implode(", ", $quoteddiv);

    $sql_leader = "SELECT A.lvl_lead_user, A.nik_lead_user, B.nik_head_lead_user, LEFT(B.name_sublead_user, 10) AS nik_lead FROM leader_users AS A
    INNER JOIN subleader_users AS B ON A.nik_lead_user = B.nik_head_lead_user
    WHERE A.office_lead_user = '$office' AND A.dept_lead_user = '$dept' AND A.div_lead_user IN ($data_div)";
    $query_leader = mysqli_query($conn, $sql_leader);

    if(mysqli_num_rows($query_leader) > 0 ) {
        while($data_leader = mysqli_fetch_assoc($query_leader)){
            $j = $data_leader["nik_head_lead_user"];
            $l = $data_leader["nik_lead"];
            $lvl = $data_leader["lvl_lead_user"];
            mysqli_query($conn, "INSERT INTO leader_assessment (officer_leader_assest, junior_leader_assest, lvl_leader_assest, head_code_sts_assest) VALUES ('$l', '$j', '$lvl', '$idmax')");
        }
    }
    else {
        $GLOBALS['alert'] = array("Gagal!", "Tidak ada data leader yang terdaftar, masing-masing user harap mengupdate data leader terlebih dahulu", "error", "$page");
        return false;
    }

    foreach ($div as $arr)  {
        $i = substr($arr, 0, 4);
        mysqli_query($conn, "INSERT INTO divisi_assessment (head_code_sts_assest, head_id_divisi) VALUES ('$idmax', '$i')");
    }

    mysqli_query($conn, "INSERT INTO statusassessment (code_sts_assest, date_sts_assest, office_sts_assest, dept_sts_assest, divisi_sts_assest, tahun_sts_assest, user_sts_assest) VALUES ('$idmax', '$date', '$office', '$dept', '$divisi', '$tahun', '$pic')");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function
function UpdatePeriodePenilaianTahunan($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-updbuatperiodeassest"]));
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-updbuatperiodeassest"]));
    $code = htmlspecialchars(mysqli_real_escape_string($conn, $data["code-updbuatperiodeassest"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun-updbuatperiodeassest"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-updbuatperiodeassest"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-updbuatperiodeassest"]));

    $sql_divisi = "SELECT head_id_divisi FROM divisi_assessment WHERE head_code_sts_assest = '$code'";
    $query_divisi = mysqli_query($conn, $sql_divisi);

    $resultarr = array();
    if(mysqli_num_rows($query_divisi) > 0 ) {
        while($data_divisi = mysqli_fetch_assoc($query_divisi)){
            $resultarr[] = "'".$data_divisi["head_id_divisi"]."'";
        }
    }

    $strdiv = implode(", ", $resultarr);
    $sql_leader = "SELECT A.nik_lead_user, A.lvl_lead_user, B.nik_head_lead_user, LEFT(B.name_sublead_user, 10) AS nik_lead FROM leader_users AS A
    INNER JOIN subleader_users AS B ON A.nik_lead_user = B.nik_head_lead_user
    WHERE A.office_lead_user = '$office' AND A.dept_lead_user = '$dept' AND A.div_lead_user IN ($strdiv)";
    $query_leader = mysqli_query($conn, $sql_leader);

    if(mysqli_num_rows($query_leader) > 0 ) {
        mysqli_query($conn, "DELETE FROM leader_assessment WHERE head_code_sts_assest = '$code'");
        while($data_leader = mysqli_fetch_assoc($query_leader)){
            $j = $data_leader["nik_head_lead_user"];
            $l = $data_leader["nik_lead"];
            $lvl = $data_leader["lvl_lead_user"];
            mysqli_query($conn, "INSERT INTO leader_assessment (lvl_leader_assest, officer_leader_assest, junior_leader_assest, head_code_sts_assest) VALUES ('$lvl', '$l', '$j', '$code')");
        }
    }
    else {
        $GLOBALS['alert'] = array("Gagal!", "Tidak ada data leader yang terdaftar, masing-masing user harap mengupdate data leader terlebih dahulu", "error", "$page");
        return false;
    }

    $final = array();
    $sql_dataassest = "SELECT leader_data_assest, junior_data_assest FROM data_assessment WHERE head_id_sts_assest = '$id'";
    $query_dataassest = mysqli_query($conn, $sql_dataassest);
    if(mysqli_num_rows($query_dataassest) > 0 ) {
        while($data_dataassest = mysqli_fetch_assoc($query_dataassest)){
            $stored_data = array(
                $data_dataassest["junior_data_assest"],
                $data_dataassest["leader_data_assest"],
            );
            $final[] = $stored_data;
        }
    }

    foreach ($final as $i => $v) {
        $rows = $v;
        mysqli_query($conn, "UPDATE leader_assessment SET status_leader_assest = 'Y' WHERE junior_leader_assest = '$rows[0]' AND officer_leader_assest = '$rows[1]' AND head_code_sts_assest = '$code'");
    }

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function finish penilaian tahunan
function FinalPeriodePenilaianTahunan($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-fnsbuatperiodeassest"]));

    mysqli_query($conn, "UPDATE statusassessment SET flag_sts_assest = 'Y' WHERE id_sts_assest = '$id'");
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Delete penilaian tahunan per divisi
function DeletePeriodePenilaianTahunan($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-delbuatperiodeassest"]));
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-delbuatperiodeassest"]));
    $off = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-delbuatperiodeassest"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-delbuatperiodeassest"]));
    $thn = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun-delbuatperiodeassest"]));

    $sql = "SELECT th_data_assest FROM data_assessment WHERE office_data_assest = '$off' AND dept_data_assest = '$dept' AND th_data_assest = '$thn'";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Evaluasi penilaian tahun periode ".$thn." sudah ada yang selesai pengisian, jika ingin tetap menghapus perlu membatalkan pengisian form penilaian!", "error", "$page");
        return false;
    }
    
    mysqli_query($conn, "DELETE FROM leader_assessment WHERE head_code_sts_assest = '$id'");
    mysqli_query($conn, "DELETE FROM divisi_assessment WHERE head_code_sts_assest = '$id'");
    mysqli_query($conn, "DELETE FROM statusassessment WHERE code_sts_assest = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function posting penilaian tahunan
function CreatePenilaianTahunan($data) {

    global $conn;

    $page = htmlspecialchars(mysqli_real_escape_string($conn, $data["page-assesment"]));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $data["tahun-assesment"]));
    $office = htmlspecialchars(mysqli_real_escape_string($conn, $data["office-assesment"]));
    $dept = htmlspecialchars(mysqli_real_escape_string($conn, $data["dept-assesment"]));
    $div = htmlspecialchars(mysqli_real_escape_string($conn, $data["div-assesment"]));
    $lvl = htmlspecialchars(mysqli_real_escape_string($conn, $data["lvl-assesment"]));
    $senior = htmlspecialchars(mysqli_real_escape_string($conn, $data["officer-assesment"]));
    $junior = htmlspecialchars(mysqli_real_escape_string($conn, $data["junior-assesment"]));
    $nilai = htmlspecialchars(mysqli_real_escape_string($conn, $data["poin-assesment"]));
    $mutu = htmlspecialchars(mysqli_real_escape_string($conn, $data["mutu-assesment"]));
    $avg = htmlspecialchars(mysqli_real_escape_string($conn, $data["avg-assesment"]));
    $note = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["note-assesment"])));
    $idmax = autonum(4, 'docno_data_assest', 'data_assessment');
    $date   = date("Y-m-d H:i:s");

    $code = isset($data["code-assesment"]) ? $data["code-assesment"] : NULL;
    $poin = "pointassesment-";
    
    $dataToInsert = [];

    if (isset($code)) {
        foreach ($code as $r) {
            if (isset($data[$poin.$r])) {
                $dataToInsert[$poin.$r] = array(
                    $r,
                    $data[$poin.$r]
                );
            } else {
                $GLOBALS['alert'] = array("Gagal!", "Instrumen penilaian belum terseleksi semua", "error", "$page");
                return false;
            }
        }
    }

    if($nilai == 0) {

        $GLOBALS['alert'] = array("Gagal!", "Nilai poin belum dihitung, harap periksa kembali", "error", "$page");
        return false;

    }

    $sql = "SELECT id_data_assest FROM data_assessment WHERE office_data_assest = '$office' AND dept_data_assest = '$dept' AND div_data_assest = '$div' AND th_data_assest = '$tahun' AND leader_data_assest = '$senior' AND junior_data_assest = '$junior'";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0 ) {
        $GLOBALS['alert'] = array("Gagal!", "Office ".$office." department ".$dept." divisi ".$div." NIK ".$senior." sudah mengisi penilaian periode tahun ".$tahun, "error", "$page");
        return false;
    }

    $sql_thn = "SELECT A.id_sts_assest, A.code_sts_assest FROM statusassessment AS A 
    INNER JOIN divisi_assessment AS B ON A.code_sts_assest = B.head_code_sts_assest
    INNER JOIN leader_assessment AS C ON A.code_sts_assest = C.head_code_sts_assest
    WHERE A.office_sts_assest = '$office' AND A.dept_sts_assest = '$dept' AND B.head_id_divisi = '$div' AND A.tahun_sts_assest = '$tahun' AND C.officer_leader_assest = '$senior' AND C.junior_leader_assest = '$junior'";
    $query_thn = mysqli_query($conn, $sql_thn);

    if(mysqli_num_rows($query_thn) > 0) {
        $data_tahun = mysqli_fetch_assoc( $query_thn);
        $id_tahun = $data_tahun["id_sts_assest"];
        $doc = $data_tahun["code_sts_assest"];
    }
    else {
        $GLOBALS['alert'] = array("Gagal!", "Referensi Penilaian Office ".$office." department ".$dept." divisi ".$div." NIK ".$senior." tahun ".$tahun." tidak ditemukan", "error", "$page");
    }

    foreach ($dataToInsert as $value) {

        $sql_indikator = "SELECT A.num_ind_assest, A.name_ind_assest, COUNT(B.id_head_ind_assest) AS jumlah_grade FROM indicator_assessment AS A
        INNER JOIN instrument_assessment AS B ON A.id_ind_assest = B.id_head_ind_assest
        WHERE A.id_ind_assest = '$value[0]' AND A.office_ind_assest = '$office'";
        $data_indikator = mysqli_fetch_assoc(mysqli_query($conn, $sql_indikator));
        $nm_indikator = $data_indikator["num_ind_assest"].". ".$data_indikator["name_ind_assest"];
        $jm_indikator = $data_indikator["jumlah_grade"];
        $poin_indikator = $value[1];

        if ($poin_indikator >= 85) {
            $mutuFix = 'A';
        } else if ($poin_indikator >= 70) {
            $mutuFix = 'B';
        } else if ($poin_indikator >= 55) {
            $mutuFix = 'C';
        } else if ($poin_indikator >= 30) {
            $mutuFix = 'D';
        }

        $grade = ($poin_indikator / 100 * $jm_indikator);

        mysqli_query($conn, "INSERT INTO sub_data_assessment (head_docno_data_assest, indikator_sub_data_assest, poin_sub_data_assest, grade_sub_data_assest, skala_sub_data_assest, mutu_sub_data_assest) VALUES ('$idmax', '$nm_indikator', '$poin_indikator', '$grade', '$jm_indikator', '$mutuFix')");
    }

    mysqli_query($conn, "INSERT INTO data_assessment (head_id_sts_assest, docno_data_assest, date_data_assest, office_data_assest, dept_data_assest, div_data_assest, lvl_data_assest, th_data_assest, leader_data_assest, junior_data_assest, poin_data_assest, mutu_data_assest, avg_data_assest, note_data_assest) VALUES ('$id_tahun', '$idmax', '$date', '$office', '$dept', '$div', '$lvl', '$tahun', '$senior', '$junior', '$nilai', '$mutu', '$avg', '$note')");

    mysqli_query($conn, "UPDATE leader_assessment SET status_leader_assest = 'Y' WHERE officer_leader_assest = '$senior' AND junior_leader_assest = '$junior' AND head_code_sts_assest = '$doc'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Delete laporan penilaian tahunan
function DeleteLaporanPenilaianTahunan($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-dellapassest"]));
    $code = htmlspecialchars(mysqli_real_escape_string($conn, $data["code-dellapassest"]));
    $junior = htmlspecialchars(mysqli_real_escape_string($conn, $data["junior-dellapassest"]));
    
    $sql= "SELECT docno_data_assest FROM data_assessment WHERE head_id_sts_assest = '$id' AND junior_data_assest = '$junior'";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0 ) {
        while($data = mysqli_fetch_assoc($query)){
            $i = $data["docno_data_assest"];
            mysqli_query($conn, "DELETE FROM sub_data_assessment WHERE head_docno_data_assest = '$i'");
        }
    }

    mysqli_query($conn, "DELETE FROM data_assessment WHERE head_id_sts_assest = '$id' AND junior_data_assest = '$junior'");
    mysqli_query($conn, "UPDATE leader_assessment SET status_leader_assest = 'N' WHERE junior_leader_assest = '$junior' AND head_code_sts_assest = '$code'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function Delete laporan penilaian tahunan
function DeleteLaporanPenilaianTahunanLeader($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-dellapassestlead"]));
    $code = htmlspecialchars(mysqli_real_escape_string($conn, $data["code-dellapassestlead"]));
    $senior = htmlspecialchars(mysqli_real_escape_string($conn, $data["senior-dellapassestlead"]));
    $junior = htmlspecialchars(mysqli_real_escape_string($conn, $data["junior-dellapassestlead"]));

    $sql= "SELECT code_sts_assest FROM statusassessment WHERE id_sts_assest = '$id'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);

    $doc = $data["code_sts_assest"];
    
    mysqli_query($conn, "DELETE FROM sub_data_assessment WHERE head_docno_data_assest = '$code'");
    mysqli_query($conn, "DELETE FROM data_assessment WHERE docno_data_assest = '$code'");
    mysqli_query($conn, "UPDATE leader_assessment SET status_leader_assest = 'N' WHERE junior_leader_assest = '$junior' AND officer_leader_assest = '$senior' AND head_code_sts_assest = '$doc'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function lapor penilaian tahunan
function LaporPenilaianTahunanLeader($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id-laporbuatperiodeassest"]));
    $senior = htmlspecialchars(mysqli_real_escape_string($conn, $data["leader-laporbuatperiodeassest"]));

    mysqli_query($conn, "UPDATE data_assessment SET status_data_assest = 'Y' WHERE head_id_sts_assest = '$id' AND leader_data_assest = '$senior'");
    
    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function get api url
function GetAPIService($api) {

    global $conn;

    $sql= "SELECT url_srv_api FROM service_api WHERE name_srv_api = '$api'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);
    $result = isset($data["url_srv_api"]) ? $data["url_srv_api"] : NULL;

    return $result;
}
// End function

// ---------------------------- //

// function posting tablok pertemanan
function PostingTablokPertemanan($data) {

    global $conn;

    // Input data post
    $page = $data["page-id"];
    $office = $data["office-id"];
    $ip = htmlspecialchars(mysqli_real_escape_string($conn, $data["ip-id"]));
    $tipe = htmlspecialchars(mysqli_real_escape_string($conn, $data["tipe-plano"]));
    $plu = htmlspecialchars(mysqli_real_escape_string($conn, $data["plu-id"]));
    
    if (!isset($data["line_nearest_group"])) {
        $GLOBALS['alert'] = array("Gagal!", "Belum ada data pertemanan yang dipilih", "error", "$page");
        return false;
    }

    if(count(array_unique($data["line_nearest_group"], SORT_REGULAR)) < count($data["line_nearest_group"])) {
        $GLOBALS['alert'] = array("Gagal!", "Terdapat duplikasi data line", "error", "$page");
        return false;
    }

    $dataPost = [
        'PLU' => $plu,
        'IP' => $ip,
        'OFFICEID' => $office,
        'TYPEPLA' => $tipe,
        'LINEPLA' => $data['line_nearest_group'],
        'RAK_PLA' => $data['rak_nearest_group']
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($dataPost),
        ],
    ];

    $apiUrl = GetAPIService('EXPRESS-PG');

    $api = $apiUrl.'/auth/grouprak';
    
    $context  = stream_context_create($options);
    $result = file_get_contents($api, false, $context);
    
    if ($result === FALSE) {
        $GLOBALS['alert'] = array("Gagal!", "Error memposting data ke API", "error", "$page");
        return false;
    }

    // Decode the JSON response
    $responseData = json_decode($result, true);

    if (isset($responseData['message']) && $responseData['message'] === "Data inserted successfully") {
        return true;
    } 
    elseif(isset($responseData['message']) && isset($responseData['data'])) {
        $GLOBALS['alert'] = array("Gagal!", "All data already exist. No new data inserted.", "error", "$page");
        return false;
    }
    else {
        $GLOBALS['alert'] = array("Gagal!", "API tidak mengembalikan data yang valid", "error", "$page");
        return false;
    }
    
}   
// End function

// ---------------------------- //

// ---------------------------------------- End ---------------------------------------- //


















// ---------------------------------------- Functions Dev Modul ---------------------------------------- //


// Function insert data company
function InsertCompany($data) {

    global $conn;

    // Input data post
    $jenis = htmlspecialchars($data["companyjenis"]);
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["companyname"])));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["companyemail"])));
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $data["companyphone"]));

    // Insert data to database
    mysqli_query($conn, "INSERT INTO company (company_jenis, company_name, company_email, company_phone) VALUES ('".$jenis."','".$name."','".$email."','".$phone."')");

    return mysqli_affected_rows($conn);

}
// End function insert data company

// ---------------------------- //

// function update master company
function UpdateCompany($data) {

    global $conn;

    // Input data post
    $id = $data["company-id"];
    $jenis = htmlspecialchars( $data["company-jenis"]);
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $data["company-name"]));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["company-email"])));
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $data["company-phone"]));
    
    $query = "UPDATE company SET company_jenis = '$jenis', company_name = '$name', company_email = '$email', company_phone = '$phone' WHERE company_id = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update company

// ---------------------------- //

// function delete company
function DeleteCompany($data) {

    global $conn;

    $id = htmlspecialchars($data["company-id"]);

    $sql = "DELETE FROM company WHERE company_id = '$id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete company

// ---------------------------- //

// Function insert office modul dev
function insertoffice($data) {

    global $conn;

    // Input data post
    $company = $data["company"];
    $officekode = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["kodeoffice"])));
    $officename = htmlspecialchars(mysqli_real_escape_string($conn, $data["officename"]));
    $shortname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["shortname"])));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["email"])));
    $city = htmlspecialchars(mysqli_real_escape_string($conn, $data["city"]));
    $address = htmlspecialchars(mysqli_real_escape_string($conn, $data["address"]));
    $postalcode = htmlspecialchars(mysqli_real_escape_string($conn, $data["postalcode"]));
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $data["phone"]));

    // Validasi Kode Offcie 4 digit
    if(strlen($officekode) != 4) {

        $GLOBALS['ins_kodelenght'] = "<strong>Gagal!</strong> Kode office wajib 4 digit.";
        return false;

    }

    // Check kode offcie di database
    $result = mysqli_query($conn, "SELECT id_office FROM office WHERE id_office ='$officekode'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_checkcode'] = "<strong>Gagal!</strong> Kode Office telah terdaftar.";
        return false;

    }

    $result = mysqli_query($conn, "SELECT office_name FROM office WHERE office_name ='$officename'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_checkname'] = "<strong>Gagal!</strong> Nama Office telah terdaftar.";
        return false;

    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO office (company_office, id_office, office_name, office_shortname, office_email, office_city, office_address, office_poscode, office_phone) VALUES ('".$company."','".$officekode."','".$officename."','".$shortname."','".$email."','".$city."','".$address."','".$postalcode."','".$phone."')");

    return mysqli_affected_rows($conn);

}
// End function insert data office

// ---------------------------- //

// function update master office
function updateoffice($data) {

    global $conn;

    // Input data post
    $idold = $data["idofficeold"];
    $newid = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["idoffice"])));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $data["officename"]));
    $shortname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["officeshortename"])));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["emailoffice"])));
    $city = htmlspecialchars(mysqli_real_escape_string($conn, $data["city"]));
    $address = htmlspecialchars(mysqli_real_escape_string($conn, $data["address"]));
    $postalcode = htmlspecialchars(mysqli_real_escape_string($conn, $data["postalcode"]));
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $data["phone"]));

    // Validasi PLU Jenis wajib 5 digit
    if(strlen($newid) != 4) {

        $GLOBALS['ins_kodelenght'] = "<strong>Gagal!</strong> Data tidak bisa di ubah, Panjang ID wajib 4 digit.";
        return false;

    }

    if($newid === $idold) {

        $newid = $idold;

    }
    else {

        // Check department name di database
        $result = mysqli_query($conn, "SELECT id_office FROM office WHERE id_office ='$newid'");
        
        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_checkcode'] = "<strong>Gagal!</strong> ID Office telah terdaftar.";
            return false;

        }
    
    }
    
    // Update office to database
    $query = "UPDATE office SET id_office = '$newid', office_name = '$name', office_shortname = '$shortname', office_email = '$email', office_city = '$city', office_address = '$address', office_poscode = '$postalcode', office_phone = '$phone' WHERE id_office = '$idold' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update office

// ---------------------------- //

// function delete office
function deleteoffice($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idoffice"]));

    // Check id office yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_office FROM users WHERE id_office = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Office yang telah memiliki users tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM office WHERE id_office = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete office

// ---------------------------- //

// Function insert department modul dev
function insertdepartment($data) {

    global $conn;

    // Input data post
    $iddept = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddept"]));
    $deptname = htmlspecialchars(mysqli_real_escape_string($conn, $data["departmentname"]));
    $initial = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["initialname"])));

    // Mengecek record id belum ada
    if(strlen($iddept) == 2) {

        // kode department
        $codedept = "DT";

        // menmbahkan kode dept dengan number baru pada func auto id
        $newid = $codedept.$iddept;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO department (id_department, department_name) VALUES ('$newid', '$deptname')");

    }
    else {
    
        // Check department name di database
        $result = mysqli_query($conn, "SELECT department_name FROM department WHERE department_name ='$deptname' OR department_initial ='$initial'");
    
        if(mysqli_fetch_assoc($result)) {
    
            $GLOBALS['ins_deptname'] = "<strong>Gagal!</strong> Nama Department telah terdaftar.";
            return false;
    
        }
    
        // Insert data to database
        mysqli_query($conn, "INSERT INTO department (id_department, department_name, department_initial) VALUES ('$iddept', '$deptname', '$initial')");
    
    }
    
    return mysqli_affected_rows($conn);

}
// End function insert data department

// ---------------------------- //

// function update master department
function updatedepartment($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddeptold"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $data["departmentname"]));
    $initial = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["initialname"])));

    // Check department name di database
    $result = mysqli_query($conn, "SELECT department_name FROM department WHERE department_name ='$name' AND department_initial = '$initial'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['upd_deptname'] = "<strong>Gagal!</strong> Nama Department telah terdaftar.";
        return false;

    }
    
    // Update department to database
    $query = "UPDATE department SET department_name = '$name', department_initial = '$initial' WHERE id_department = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update department

// ---------------------------- //

// function delete department
function deletedepartment($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddept"]));

    // Check id department yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_department FROM users WHERE id_department = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Department yang telah memiliki users tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM department WHERE id_department = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete department

// ---------------------------- //

// Function insert head divisi
function InsertHeadDivisi($data) {

    global $conn;

    // Input data post
    $page = $data["ins-headivpage"];
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["ins-headivname"])));

    // Check divisi name di database
    $result = mysqli_query($conn, "SELECT name_head_div FROM head_divisi WHERE name_head_div ='$name'");

    if(mysqli_fetch_assoc($result)) {
        
        $GLOBALS['alert'] = array("Gagal!", "Nama Divisi telah terdaftar", "error", "$page");
        return false;

    }
    
    $id = "HD";
    $idmax = autonum(2, 'id_head_div', 'head_divisi');
    $newid = $id.$idmax;

    // Insert data to database
    mysqli_query($conn, "INSERT INTO head_divisi (id_head_div, name_head_div) VALUES ('$newid', '$name')");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function update head divisi
function UpdateHeadDivisi($data) {

    global $conn;

    // Input data post
    $page = $data["upd-headivpage"];
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["upd-headivid"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["upd-headivname"])));

    $result = mysqli_query($conn, "SELECT name_head_div FROM head_divisi WHERE name_head_div ='$name'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Nama Head Divisi telah terdaftar", "error", "$page");
        return false;

    }
    
    // Update divisi to database
    $query = "UPDATE head_divisi SET name_head_div = '$name' WHERE id_head_div = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// function delete head divisi
function DeleteHeadDivisi($data) {

    global $conn;

    $page = $data["del-headivpage"];
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["del-headivid"]));

    $query = mysqli_query($conn, "SELECT id_head_divisi FROM divisi WHERE id_head_divisi = '$id'");

    if(mysqli_fetch_assoc($query)) {
        
        $GLOBALS['alert'] = array("Gagal!", "Head Divisi Telah Terdaftar Di Divisi", "error", "$page");
        return false;
    
    }

    mysqli_query($conn, "DELETE FROM head_divisi WHERE id_head_div = '$id'");

    return mysqli_affected_rows($conn);

}
// End function

// ---------------------------- //

// Function insert divisi modul dev
function insertdivisi($data) {

    global $conn;

    // Input data post
    $page = $data["divisipage"];
    $idheadiv = htmlspecialchars(mysqli_real_escape_string($conn, $data["idheaddivisi"]));
    $divname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["divisiname"])));

    $id = "DV";
    $idmax = autonum(2, 'id_divisi', 'divisi');
    $divid = $id.$idmax;

    // Check divisi name di database
    $result = mysqli_query($conn, "SELECT divisi_name FROM divisi WHERE divisi_name ='$divname'");

    if(mysqli_fetch_assoc($result)) {
        
        $GLOBALS['alert'] = array("Gagal!", "Nama Divisi telah terdaftar", "error", "$page");
        return false;

    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO divisi (id_divisi, id_head_divisi, divisi_name) VALUES ('$divid', '$idheadiv', '$divname')");

    return mysqli_affected_rows($conn);

}
// End function insert data divisi

// ---------------------------- //

// function update master divisi
function updatedivisi($data) {

    global $conn;

    // Input data post
    $page = $data["upd-divisipage"];
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddivold"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["divisiname"])));

    // Check divisi name di database
    $result = mysqli_query($conn, "SELECT divisi_name FROM divisi WHERE divisi_name ='$name'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['alert'] = array("Gagal!", "Nama Divisi telah terdaftar", "error", "$page");
        return false;

    }
    
    // Update divisi to database
    $query = "UPDATE divisi SET divisi_name = '$name' WHERE id_divisi = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update divisi

// ---------------------------- //

// function delete divisi
function deletedivisi($data) {

    global $conn;

    $page = $data["del-divisipage"];
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["iddiv"]));

    // Check id divisi yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_divisi FROM users WHERE id_divisi = '$id'");

    if(mysqli_fetch_assoc($query)) {
        
        $GLOBALS['alert'] = array("Gagal!", "Divisi yang telah memiliki users tidak dapat di hapus", "error", "$page");
        return false;
    
    }

    mysqli_query($conn, "DELETE FROM divisi WHERE id_divisi = '$id'");

    return mysqli_affected_rows($conn);

}
// End function delete department

// ---------------------------- //

// Function insert group modul dev
function insertgroup($data) {

    global $conn;

    // Input data post
    $groupid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));
    $groupname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["groupname"])));

    if ( strlen($groupid) == 2) {
        
       // kode group
        $groupcode = "GP";

        // menmbahkan kode group dengan number baru pada func auto id
        $newid = $groupcode.$groupid;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO groups (id_group, group_name) VALUES ('$newid', '$groupname')");

        return mysqli_affected_rows($conn);

    }

    else {

        // Check group name di database
        $result = mysqli_query($conn, "SELECT group_name FROM groups WHERE group_name ='$groupname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_groupname'] = "<strong>Gagal!</strong> Group telah terdaftar.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO groups (id_group, group_name) VALUES ('$groupid', '$groupname')");

        return mysqli_affected_rows($conn);

    }

}
// End function insert data group

// ---------------------------- //

// function update master group
function updategroup($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idgroup"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["groupname"])));

    // Check group name di database
    $result = mysqli_query($conn, "SELECT group_name FROM groups WHERE group_name ='$name'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['upd_groupname'] = "<strong>Gagal!</strong> Nama Group telah terdaftar.";
        return false;

    }
    
    // Update group to database
    $query = "UPDATE groups SET group_name = '$name' WHERE id_group = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update group

// ---------------------------- //

// function delete group
function deletegroup($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idgroup"]));

    // Check id group yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_group FROM users WHERE id_group = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Group yang telah memiliki users tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM groups WHERE id_group = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete group

// ---------------------------- //

// Function insert level modul dev
function insertlevel($data) {

    global $conn;

    // Input data post
    $levelid = htmlspecialchars(mysqli_real_escape_string($conn, $data["levelid"]));
    $levelname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["levelname"])));

    if ( strlen($levelid) == 2) {
    
        // kode level
        $codelevel = "LV";

        // menmbahkan kode level dengan number baru pada func auto id
        $newid = $codelevel.$levelid;
    
        // Insert data to database
        mysqli_query($conn, "INSERT INTO level (id_level, level_name) VALUES ('$newid', '$levelname')");
    
        mysqli_query($conn, "INSERT INTO crud (id_level) VALUES ('$newid')");
    
    }

    else {
                
        // Check level name di database
        $result = mysqli_query($conn, "SELECT level_name FROM level WHERE level_name ='$levelname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_levelname'] = "Gagal! Level telah terdaftar.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO level (id_level, level_name) VALUES ('$levelid', '$levelname')");

        mysqli_query($conn, "INSERT INTO crud (id_level) VALUES ('$levelid')");

    }

    return mysqli_affected_rows($conn);

}
// End function insert data level

// ---------------------------- //

// function update master level
function updatelevel($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idlevel"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes(strtoupper($data["levelname"]))));

    // Check level name di database
    $result = mysqli_query($conn, "SELECT level_name FROM level WHERE level_name ='$name'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['upd_levelname'] = "<strong>Gagal!</strong> Nama Level telah terdaftar.";
        return false;

    }
    
    // Update level to database
    $query = "UPDATE level SET level_name = '$name' WHERE id_level = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update level

// ---------------------------- //

// function delete level
function deletelevel($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idlevel"]));

    // Check id level yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_level FROM users WHERE id_level = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Level yang telah memiliki users tidak dapat di hapus.";
        return false;
    
    }

    $sql_lvl = "DELETE FROM level WHERE id_level = '$id'";
    $query_lvl = mysqli_query($conn, $sql_lvl);

    $sql_crud = "DELETE FROM crud WHERE id_level = '$id'";
    $query_crud = mysqli_query($conn, $sql_crud);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete level

// ---------------------------- //

// Function insert mainmenu modul dev
function insertparentmenu($data) {

    global $conn;

    // Input data post
    $pmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));
    $pmname = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmname"]));
    $pmicon = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmicon"]));

    if (strlen($pmid) == 3) {
    
        // kode parentmenu
        $codepm = "P";

        // menmbahkan kode mainmenu dengan number baru pada func auto id
        $newid = $codepm.$pmid;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO parentmenu (id_parentmenu, parentmenu_name, parentmenu_icon) VALUES ('$newid', '$pmname', '$pmicon')");

    }
    else {

        // Check mainmenu name di database
        $result = mysqli_query($conn, "SELECT parentmenu_name FROM parentmenu WHERE parentmenu_name ='$pmname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_pmname'] = "Gagal! Nama Parent Menu sudah ada.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO parentmenu (id_parentmenu, parentmenu_name, parentmenu_icon) VALUES ('$pmid', '$pmname', '$pmicon')");

    }

    return mysqli_affected_rows($conn);

}
// End function insert data parent menu

// ---------------------------- //

// function update master parent menu
function updateparentmenu($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes($data["pmname"])));
    $icon = mysqli_real_escape_string($conn, $data["pmicon-name"]);
    
    // Update parent menu to database
    $query = "UPDATE parentmenu SET parentmenu_name = '$nama', parentmenu_icon = '$icon' WHERE id_parentmenu = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update parent menu

// ---------------------------- //

// function delete parent menu
function deleteparentmenu($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));

    // Check id level yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_parentmenu FROM akses_parentmenu WHERE id_parentmenu = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Parent Menu yang telah memiliki users group tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM parentmenu WHERE id_parentmenu = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete parent menu

// ---------------------------- //

// Function insert menu modul dev
function insertchildmenu($data) {

    global $conn;

    // Input data post
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));
    $pmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));
    $cmname = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmname"]));

    if (strlen($cmid) == 3) {
    
        // kode child menu
        $codecm = "C";

        // menmbahkan kode menu dengan number baru pada func auto id
        $newid = $codecm.$cmid;

    }

    else {

        $newid = $cmid;

    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO childmenu (id_childmenu, id_parentmenu, childmenu_name, childmenu_akses) VALUES ('$newid', '$pmid', '$cmname', 1)");

    return mysqli_affected_rows($conn);

}
// End function insert data menu

// ---------------------------- //

// function update master parent menu
function updatechildmenu($data) {

    global $conn;

    // Input data post
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes($data["cmname"])));
    
    // Update parent menu to database
    $query = "UPDATE childmenu SET childmenu_name = '$nama' WHERE id_childmenu = '$cmid' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update parent menu

// ---------------------------- //

// function delete parent menu
function deletechildmenu($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));

    // Check id level yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_childmenu FROM akses_childmenu WHERE id_childmenu = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Child Menu yang telah memiliki users group tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM childmenu WHERE id_childmenu = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete child menu

// ---------------------------- //

// Function insert submenu modul dev
function insertgrandchildmenu($data) {

    global $conn;

    // Input data post
    $gmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmid"]));
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));
    $gmname = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmname"]));

    if (strlen($gmid) == 3) {
    
        // kode submenu
        $codegm = "G";

        // menmbahkan kode submenu dengan number baru pada func auto id
        $newid = $codegm.$gmid;
            
        // Insert data to database
        mysqli_query($conn, "INSERT INTO grandchildmenu (id_grandchildmenu, id_childmenu, grandchildmenu_name, grandchildmenu_akses) VALUES ('$newid', '$cmid', '$gmname', 1)");

        return mysqli_affected_rows($conn);
    
    }
    
    else {

        // Check submenu name di database
        $result = mysqli_query($conn, "SELECT grandchildmenu_name FROM grandchildmenu WHERE grandchildmenu_name ='$gmname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_gmname'] = "Gagal! Nama Grandchild Menu sudah ada.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO grandchildmenu (id_grandchildmenu, id_childmenu, grandchildmenu_name, grandchildmenu_akses) VALUES ('$gmid', '$cmid', '$gmname', 1)");

        return mysqli_affected_rows($conn);

    }

}
// End function insert data submenu

// ---------------------------- //

// function update master parent menu
function updategrandchildmenu($data) {

    global $conn;

    // Input data post
    $gmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmid"]));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes($data["gmname"])));
    
    // Update parent menu to database
    $query = "UPDATE grandchildmenu SET grandchildmenu_name = '$nama' WHERE id_grandchildmenu = '$gmid' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update parent menu

// ---------------------------- //

// function update extend grandchild menu
function extendgrandchildmenu($data) {

    global $conn;

    // Input data post
    $gmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmid"]));
    $namaext = htmlspecialchars(mysqli_real_escape_string($conn, stripslashes($data["exname"])));

    $id = "EM";
    $idmax = autonum(2, 'kode_extend_menu', 'extendmenu');
    $newid = $id.$idmax;
    
    // Insert data to database
    $query = "INSERT INTO extendmenu (id_ref_menu, kode_extend_menu, name_extend_menu) VALUES ('$gmid', '$newid', '$namaext')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update parent menu

// ---------------------------- //

// function delete parent menu
function deletegrandchildmenu($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmid"]));

    // Check id level yang berelasi dengan tabel user di database
    $query = mysqli_query($conn, "SELECT id_grandchildmenu FROM akses_grandchildmenu WHERE id_grandchildmenu = '$id'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Grand Child Menu yang telah memiliki users group tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM grandchildmenu WHERE id_grandchildmenu = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete child menu

// ---------------------------- //

// Function insert akses mainmenu modul dev
function insertpm_akses($data) {

    global $conn;

    // Input data post
    $groupid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));
    $pmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));

    // Check mainmenu id di database
    $result = mysqli_query($conn, "SELECT id_group, id_parentmenu FROM akses_parentmenu WHERE id_group = '$groupid' AND id_parentmenu ='$pmid'");
   
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_apmname'] = "Gagal! Parent Menu sudah ada digroup.";
        return false;
    
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO akses_parentmenu (id_group, id_parentmenu) VALUES ('$groupid', '$pmid')");

    return mysqli_affected_rows($conn);


}
// End function insert data akses mainmenu

// ---------------------------- //

// function update master akses parent menu
function updatepm_akses($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idaccpm"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["status"]));
    
    // Update akses parent menu to database
    $query = "UPDATE akses_parentmenu SET parentmenu_status = '$status' WHERE id_akses_pm = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update akses parent menu

// ---------------------------- //

// function delete akses parent menu
function deletepm_akses($data) {

    global $conn;

    $accid = htmlspecialchars(mysqli_real_escape_string($conn, $data["accpmid"]));
    $pmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));
    $gpid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));

    $query = mysqli_query($conn, "SELECT * FROM akses_childmenu WHERE id_group = '$gpid' AND id_parentmenu = '$pmid'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Group dan Parent Menu yang telah memiliki Child Menu tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM akses_parentmenu WHERE id_akses_pm = '$accid'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete parent menu Akses

// ---------------------------- //

// Function insert akses menu modul dev
function insertcm_akses($data) {

    global $conn;

    // Input data post
    $groupid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));
    $pmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["pmid"]));
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));

    // Check childmenu id di database
    $result = mysqli_query($conn, "SELECT id_group, id_parentmenu, id_childmenu FROM akses_childmenu WHERE id_group = '$groupid' AND id_parentmenu ='$pmid' AND id_childmenu = '$cmid'");
   
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_acmname'] = "Gagal! Child Menu sudah ada digroup.";
        return false;
    
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO akses_childmenu (id_group, id_parentmenu, id_childmenu) VALUES ('$groupid', '$pmid', '$cmid')");

    return mysqli_affected_rows($conn);


}
// End function insert data menu

// ---------------------------- //

// function update master akses child menu
function updatecm_akses($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idacccm"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["status"]));
    
    // Update akses child menu to database
    $query = "UPDATE akses_childmenu SET childmenu_status = '$status' WHERE id_akses_cm = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update akses child menu

// ---------------------------- //

// function delete akses child menu
function deletecm_akses($data) {

    global $conn;

    $accid = htmlspecialchars(mysqli_real_escape_string($conn, $data["acccmid"]));
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));
    $gpid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));

    $query = mysqli_query($conn, "SELECT * FROM akses_grandchildmenu WHERE id_group = '$gpid' AND id_childmenu = '$cmid'");

    if(mysqli_fetch_assoc($query)) {

        $GLOBALS['error_delete'] = "<strong>Gagal!</strong> Group dan Child Menu yang telah memiliki Grand Child Menu tidak dapat di hapus.";
        return false;
    
    }

    $result = "DELETE FROM akses_childmenu WHERE id_akses_cm = '$accid'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete child menu Akses

// ---------------------------- //

// Function insert akses submenu modul dev
function insertgm_akses($data) {

    global $conn;

    // Input data post
    $groupid = htmlspecialchars(mysqli_real_escape_string($conn, $data["groupid"]));
    $cmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["cmid"]));
    $gmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["gmid"]));

    // Check submenu id di database
    $result = mysqli_query($conn, "SELECT id_group, id_childmenu, id_grandchildmenu FROM akses_grandchildmenu WHERE id_group = '$groupid' AND id_childmenu = '$cmid' AND id_grandchildmenu = '$gmid'");
   
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_agmname'] = "Gagal! Grand Child Menu sudah ada digroup.";
        return false;
    
    }

    // Insert data to database
    mysqli_query($conn, "INSERT INTO akses_grandchildmenu (id_group, id_childmenu, id_grandchildmenu) VALUES ('$groupid', '$cmid', '$gmid')");

    return mysqli_affected_rows($conn);


}
// End function insert data submenu

// ---------------------------- //

// function update master akses grand child menu
function updategm_akses($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idaccgm"]));
    $status = htmlspecialchars(mysqli_real_escape_string($conn, $data["status"]));
    
    // Update akses grand child menu to database
    $query = "UPDATE akses_grandchildmenu SET grandchildmenu_status = '$status' WHERE id_akses_gm = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update akses grand child menu

// ---------------------------- //

// function delete akses child menu
function deletegm_akses($data) {

    global $conn;

    $acgmid = htmlspecialchars(mysqli_real_escape_string($conn, $data["accgmid"]));

    $result = "DELETE FROM akses_grandchildmenu WHERE id_akses_gm = '$acgmid'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete child menu Akses

// ---------------------------- //

// Function insert mail server
function insertmailserver($data) {

    global $conn;

    // Input data post
    $host = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["host"])));
    $mail = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["email"])));
    $port = htmlspecialchars(mysqli_real_escape_string($conn, $data["port"]));
    $enkripsi = htmlspecialchars(mysqli_real_escape_string($conn, $data["enkripsi"]));
    $pass = mysqli_real_escape_string($conn, $data["password"]);

    $encrypt_pass = encrypt($pass);

    // Insert data to database
    $sql = "INSERT INTO email_server (host, email, port, enkripsi, password) VALUES ('$host', '$mail', '$port', '$enkripsi', '$encrypt_pass')";
    mysqli_query($conn, $sql);

    return mysqli_affected_rows($conn);

}
// End function insert mail server

// ---------------------------- //

// function update mail server
function updatemailserver($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id"]));
    $host = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["host"])));
    $mail = htmlspecialchars(mysqli_real_escape_string($conn, strtolower($data["email"])));
    $port = htmlspecialchars(mysqli_real_escape_string($conn, $data["port"]));
    $enkripsi = htmlspecialchars(mysqli_real_escape_string($conn, $data["enkripsi"]));
    $pass = mysqli_real_escape_string($conn, $data["password"]);

    $encrypt_pass = encrypt($pass);
    
    // Update mail server
    $query = "UPDATE email_server SET host = '$host', email = '$mail', port = '$port', enkripsi = '$enkripsi', password = '$encrypt_pass' WHERE id = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update mail server

// ---------------------------- //

// function delete mail server
function deletemailserver($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["mailid"]));

    $result = "DELETE FROM email_server WHERE id = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete mail server

// ---------------------------- //

// Function insert category
function insertcategory($data) {

    global $conn;

    // Input data post
    $idctg = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["idcategory"])));
    $ctgname = htmlspecialchars(mysqli_real_escape_string($conn, $data["categoryname"]));

    // Mengecek inputan panjang id > 1
    if(strlen($idctg) > 1) {

        $GLOBALS['ins_ctglid'] = "<strong>Gagal!</strong> Data inputan ID yang diterima hanya 1 digit.";
        return false;

    }

    // Check category ID
    $result = mysqli_query($conn, "SELECT IDCategory FROM categorybarang WHERE IDCategory = '$idctg'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_ctgid'] = "<strong>Gagal!</strong> ID Category ".$idctg." telah terdaftar.";
        return false;

    }

    // Check category Name
    $result = mysqli_query($conn, "SELECT CategoryName FROM categorybarang WHERE CategoryName ='$ctgname'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_ctgname'] = "<strong>Gagal!</strong> Nama Category telah terdaftar.";
        return false;

    }
    
    // Insert data to database
    mysqli_query($conn, "INSERT INTO categorybarang (IDCategory, CategoryName) VALUES ('$idctg', '$ctgname')");

    return mysqli_affected_rows($conn);

}
// End Function insert category

// ---------------------------- //

// function update category name
function updatecategory($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idctg"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $data["categoryname"]));

    // Check category Name
    $result = mysqli_query($conn, "SELECT CategoryName FROM categorybarang WHERE CategoryName ='$name'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_ctgname'] = "<strong>Gagal!</strong> Nama Category telah terdaftar.";
        return false;

    }
    
    // Update category name
    $query = "UPDATE categorybarang SET CategoryName = '$name' WHERE id = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update category name

// ---------------------------- //

// function delete category
function deletecategory($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id"]));
    $idctg = mysqli_real_escape_string($conn, $data["idctg"]);

    // Check category used
    $query = "SELECT LEFT(IDBarang, 1) AS id_cut FROM mastercategory";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0 ) {

        while($data = mysqli_fetch_assoc($result)) {

            $id_cut = $data['id_cut'];
            
            if($id_cut == $idctg) {

            $GLOBALS['del_ctgused'] = "<strong>Gagal!</strong> ID Category ".$idctg." sudah terdaftar pada master category.";
            return false;

            }
        }
    }

    $result = "DELETE FROM categorybarang WHERE id = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete category

// ---------------------------- //

// Function insert kondisi
function insertkondisi($data) {

    global $conn;

    // Input data post
    $idcdn = htmlspecialchars(mysqli_real_escape_string($conn, $data["idkondisi"]));
    $cdnname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["kondisinama"])));

    // Mengecek inputan panjang id > 1
    if(strlen($idcdn) > 2) {

        $GLOBALS['ins_cdnlid'] = "<strong>Gagal!</strong> Data inputan ID yang diterima hanya 2 digit.";
        return false;

    }

    if (!is_numeric($idcdn)) {

        $GLOBALS['ins_cdnidnum'] = "<strong>Gagal!</strong> Data yang di inputkan bukan numeric.";
        return false;

    }

    // Check condition ID
    $result = mysqli_query($conn, "SELECT id_kondisi FROM kondisi WHERE id_kondisi = '$idcdn'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_cdnid'] = "<strong>Gagal!</strong> ID Kondisi ".$idcdn." telah terdaftar.";
        return false;

    }

    // Check condition Name
    $result = mysqli_query($conn, "SELECT kondisi_name FROM kondisi WHERE kondisi_name ='$cdnname'");
    
    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['ins_cdnname'] = "<strong>Gagal!</strong> Nama Kondisi ".$cdnname." telah terdaftar.";
        return false;

    }
    
    // Insert data to database
    mysqli_query($conn, "INSERT INTO kondisi (id_kondisi, kondisi_name) VALUES ('$idcdn', '$cdnname')");

    return mysqli_affected_rows($conn);

}
// End Function insert kondisi

// ---------------------------- //

// function update kondisi
function updatekondisi($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idcdn"]));
    $name = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["namekondisi"])));

    // Check kondisi Name
    $result = mysqli_query($conn, "SELECT kondisi_name FROM kondisi WHERE kondisi_name ='$name'");

    if(mysqli_fetch_assoc($result)) {

        $GLOBALS['upd_cdnname'] = "<strong>Gagal!</strong> Nama Kondisi telah terdaftar.";
        return false;

    }
    
    // Update kondisi name
    $query = "UPDATE kondisi SET kondisi_name = '$name' WHERE id = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

}
// End function update kondisi

// ---------------------------- //

// function delete kondisi
function deletekondisi($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id"]));
    $idcdn = htmlspecialchars(mysqli_real_escape_string($conn, $data["idcdn"]));
    $cdnname = htmlspecialchars(mysqli_real_escape_string($conn, $data["cdnname"]));

    // Check condition Name
    $result = mysqli_query($conn, "SELECT * FROM masterbarang WHERE kondisi ='$idcdn'");

    if($data = mysqli_fetch_assoc($result)) {

        $GLOBALS['del_cdnidused'] = "<strong>Gagal!</strong> Kondisi ".$idcdn." - ".$cdnname." telah terdaftar pada master barang";
        return false;

    }

    $result = "DELETE FROM kondisi WHERE id = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete kondisi

// ---------------------------- //

// Function insert satuan
function insert_satuan($data) {

    global $conn;

    // Input data post
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["satuanid"]));
    $sname = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($data["satuan"])));

    if (strlen($id) == 2) {
    
        // kode parentmenu
        $codest = "ST";

        // menmbahkan kode satuan dengan number baru pada func auto id
        $newid = $codest.$id;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO satuan (id_satuan, nama_satuan) VALUES ('$newid', '$sname')");

        return mysqli_affected_rows($conn);

    }
    else {

        // Check satuan name di database
        $result = mysqli_query($conn, "SELECT nama_satuan FROM satuan WHERE nama_satuan ='$sname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_satuanname'] = "Gagal! Nama Satuan sudah ada.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO satuan (id_satuan, nama_satuan) VALUES ('$id', '$sname')");

        return mysqli_affected_rows($conn);

    }

}
// End function insert data satuan

// ---------------------------- //

// function delete satuan
function delete_satuan($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["id"]));
    $idst = htmlspecialchars(mysqli_real_escape_string($conn, $data["idst"]));
    $stname = htmlspecialchars(mysqli_real_escape_string($conn, $data["stname"]));

    // Check Satuan is used
    $result = mysqli_query($conn, "SELECT id_satuan FROM masterjenis WHERE id_satuan ='$idst'");

    if($data = mysqli_fetch_assoc($result)) {

        $GLOBALS['del_satuanid'] = "<strong>Gagal!</strong> Satuan ".$stname." telah terdaftar di master barang";
        return false;

    }

    $result = "DELETE FROM satuan WHERE id = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete satuan

// ---------------------------- //

// Function insert status pembelian
function insert_tspp($data) {

    global $conn;

    // Input data post
    $sid = htmlspecialchars(mysqli_real_escape_string($conn, $data["statusid"]));
    $sname = htmlspecialchars(mysqli_real_escape_string($conn, $data["statusname"]));
    $scolor = htmlspecialchars(mysqli_real_escape_string($conn, $data["colorname"]));

    if (strlen($sid) == 2) {
    
        // kode spp
        $codest = "S";

        // menmbahkan kode spp dengan number baru pada func auto id
        $newid = $codest.$sid;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO status_pembelian (id_spp, status_name, status_warna) VALUES ('$newid', '$sname', '$scolor')");

        return mysqli_affected_rows($conn);

    }
    else {

        // Check spp name di database
        $result = mysqli_query($conn, "SELECT status_name FROM status_pembelian WHERE status_name ='$sname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_statusname'] = "Gagal! Nama status sudah ada.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO status_pembelian (id_spp, status_name, status_warna) VALUES ('$sid', '$sname', '$scolor')");

        return mysqli_affected_rows($conn);

    }

}
// End function insert data spp

// ---------------------------- //

// function delete spp
function delete_tspp($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idspp"]));

    // Check status id is used
    $result = mysqli_query($conn, "SELECT status_pp FROM pembelian WHERE status_pp ='$id'");

    if($data = mysqli_fetch_assoc($result)) {

        $GLOBALS['del_statusid'] = "<strong>Gagal!</strong> Status ID : ".$id." telah terdaftar di pembelian";
        return false;

    }

    $result = "DELETE FROM status_pembelian WHERE id_spp = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function delete spp

// ---------------------------- //

// Function insert status p3at
function InsertStatusP3AT($data) {

    global $conn;

    // Input data post
    $sid = htmlspecialchars(mysqli_real_escape_string($conn, $data["statusid"]));
    $sname = htmlspecialchars(mysqli_real_escape_string($conn, $data["statusname"]));
    $scolor = htmlspecialchars(mysqli_real_escape_string($conn, $data["colorname"]));

    if (strlen($sid) == 2) {
    
        // kode spp
        $codest = "T";

        // menmbahkan kode spp dengan number baru pada func auto id
        $newid = $codest.$sid;

        // Insert data to database
        mysqli_query($conn, "INSERT INTO status_p3at (kode_sp3at, nama_sp3at, warna_sp3at) VALUES ('$newid', '$sname', '$scolor')");

    }
    else {
        // Check spp name di database
        $result = mysqli_query($conn, "SELECT nama_sp3at FROM status_sp3at WHERE nama_sp3at ='$sname'");

        if(mysqli_fetch_assoc($result)) {

            $GLOBALS['ins_statusnamep3at'] = "Gagal! Nama status sudah ada.";
            return false;

        }

        // Insert data to database
        mysqli_query($conn, "INSERT INTO status_p3at (kode_sp3at, nama_sp3at, warna_sp3at) VALUES ('$sid', '$sname', '$scolor')");
    }
    
    return mysqli_affected_rows($conn);
}
// End function

// ---------------------------- //

// function delete status p3at
function DeleteStatusP3AT($data) {

    global $conn;

    $id = htmlspecialchars(mysqli_real_escape_string($conn, $data["idsp3at"]));

    // Check status id is used
    $result = mysqli_query($conn, "SELECT status_p3at FROM p3at WHERE status_p3at ='$id'");

    if($data = mysqli_fetch_assoc($result)) {

        $GLOBALS['del_statusidp3at'] = "<strong>Gagal!</strong> Status ID : ".$id." telah terdaftar di P3AT";
        return false;

    }

    $result = "DELETE FROM status_p3at WHERE id_sp3at = '$id'";
    $query = mysqli_query($conn, $result);

    if ($query) {

        return mysqli_affected_rows($conn);
    
    }

}
// End function

// ---------------------------- //

// ---------------------------------------- End ---------------------------------------- //

?>