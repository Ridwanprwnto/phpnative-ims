<?php

$page = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";

/* Proses Login */
if(isset($_POST["login"])) {
    $user = mysqli_real_escape_string($conn, $_POST["user-name"]);
    $pass = mysqli_real_escape_string($conn, $_POST["user-password"]);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE  nik = '$user' OR username = '$user'");
    //Cek user
    if(mysqli_num_rows($result) === 1 ) {
        //Cek rows data in database
        $rows = mysqli_fetch_assoc($result);
        //Cek user office
        if(!empty($rows["id_office"])) {
            //Cek status user
            if($rows["status"] === 'Y' ) {
                //Cek user department
                if(!empty($rows["id_department"])) {
                    //Cek user divisi
                    if(!empty($rows["id_divisi"])) {
                        //Cek user group
                        if(!empty($rows["id_group"])) {
                            //Cek Akses IP
                            if ($rows["akses_ip"] == 1) {
                                //Cek password
                                if(password_verify($pass, $rows["password"])) {
                                    //Check remember me
                                    if(isset($_POST['remember-me'])) {
                                        // Encrypt cookie
                                        $encuser = encrypt($user);
                                        $encpass = encrypt($pass);
                                        // Create cookie
                                        log_cookie($encuser, $encpass);
                                    }
                                    
                                    $log_date = date("Y-m-d H:i:s");
                                    mysqli_query($conn, "UPDATE users SET last_login = '$log_date' WHERE nik = '$user' OR username = '$user'");
                                    
                                    //Set Session
                                    $_SESSION["user_nik"] = $rows['nik'];
                                    $_SESSION["group"] = $rows['id_group'];
                                    $_SESSION["office"] = $rows['id_office'];
                                    $_SESSION["department"] = $rows['id_department'];
                                    $_SESSION["divisi"] = $rows['id_divisi'];
                                    $_SESSION["user_name"] = $rows['username'];
                                    $_SESSION["fullname"] = $rows['full_name'];
                                    $_SESSION["level"] = $rows['id_level'];

                                    header("location: index.php");
                                    exit();
                                }
                                else {
                                    $alert = array("Gagal!", "Password yang anda masukan salah", "error", "$page");
                                }
                            }
                            elseif($rows["akses_ip"] == 0) {
                                //Cek ip address
                                if($rows["ip_address"] == getUserIP()) {
                                    //Cek password
                                    if(password_verify($pass, $rows["password"])) {
                                        //Check remember me
                                        if(isset($_POST['remember-me'])) {
                                            // Encrypt cookie
                                            $encuser = encrypt($user);
                                            $encpass = encrypt($pass);
                                            // Create cookie
                                            log_cookie($encuser, $encpass);
                                        }
                                        //Set Session
                                        $_SESSION["user_nik"] = $rows['nik'];
                                        $_SESSION["group"] = $rows['id_group'];
                                        $_SESSION["office"] = $rows['id_office'];
                                        $_SESSION["department"] = $rows['id_department'];
                                        $_SESSION["user_name"] = $rows['username'];
                                        $_SESSION["fullname"] = $rows['full_name'];
                                        $_SESSION["level"] = $rows['id_level'];

                                        header("location: index.php");
                                        exit();
                                    }
                                    else {
                                        $alert = array("Gagal!", "Password yang anda masukan salah", "error", "$page");
                                    }
                                }
                                else {
                                    $alert = array("Gagal!", "Akses dibatasi menggunakan IP terdaftar ".$rows["ip_address"], "error", "$page");
                                }
                            }
                            else {
                                $alert = array("Gagal!", "Akses IP tidak terdaftar", "error", "$page");
                            }
                        }
                        else {
                            $alert = array("Gagal!", "User NIK ".$rows["nik"]." belum mempunyai user group", "error", "$page");
                        }
                    }
                    else {
                        $alert = array("Gagal!", "User NIK ".$rows["nik"]." belum mempunyai divisi", "error", "$page");
                    }
                }
                else {
                    $alert = array("Gagal!", "User NIK ".$rows["nik"]." belum mempunyai department", "error", "$page");
                }
            }
            else {
                $alert = array("Gagal!", "User NIK ".$rows["nik"]." belum diverifikasi", "error", "$page");
            }
        }
        else {
            $alert = array("Gagal!", "User NIK ".$rows["nik"]." belum mempunyai office", "error", "$page");
        }
    }
    else {
        $alert = array("Gagal!", "NIK / Username tidak terdaftar", "error", "$page");
    }
}
?>