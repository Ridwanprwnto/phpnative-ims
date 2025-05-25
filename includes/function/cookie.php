<?php

// Check cookie
if(isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    $decid = decrypt($id);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE nik = '$decid' OR username = '$decid'");
    $row = mysqli_fetch_assoc($result);

    if($decid === $row['nik'] || $decid === $row['username']) {
        $deckey = decrypt($key);
        if(password_verify($deckey, $row["password"])) {
            $cookie_user = $decid;
            $cookie_pass = $deckey;
        }
    }
}

?>