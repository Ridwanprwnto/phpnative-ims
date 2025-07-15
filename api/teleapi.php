<?php
    //Include database configuration file
    include '../includes/config/conn.php';
    include '../includes/function/func.php';
    require '../includes/function/tag.php';

    global $conn;

    $query_mtele = mysqli_query($conn, "SELECT token_mstr_telebot, uname_mstr_telebot FROM master_telebot");
    $data_mtele = mysqli_fetch_assoc($query_mtele);

    $token = $data_mtele["token_mstr_telebot"];
    $uname = $data_mtele["uname_mstr_telebot"];

    $apiUrl = "https://api.telegram.org/bot$token/";

    // Mendapatkan update dari Telegram
    $update = json_decode(file_get_contents("php://input"), TRUE);

    // Periksa apakah update tidak null dan memiliki struktur yang diharapkan
    if (isset($update["message"])) {
        // Mendapatkan chat ID dan pesan dari update
        $chatId = $update["message"]["chat"]["id"];
        $message = $update["message"]["text"];

        // Fungsi untuk mengirim pesan
        function sendMessage($chatId, $message) {
            global $apiUrl;
            $url = $apiUrl . "sendMessage?chat_id=$chatId&text=" . urlencode($message);
            file_get_contents($url);
        }

        function sendMessageHtml($data) {
            global $apiUrl;
            $url = $apiUrl . "sendMessage?" . http_build_query($data);
            file_get_contents($url);
        }

        function sendMessageApproval($chatId, $text, $replyMarkup) {
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => json_encode($replyMarkup),
                'parse_mode' => 'HTML'
            ];
            sendMessageHtml($data);
        }

        $query_trn = mysqli_query($conn, "SELECT * FROM role_transaksi");

        if (strpos($message, "/start") === 0) {
            $response = "Welcome to the BOT IMS! Please type /registims to register.";
            sendMessage($chatId, $response);
        }
        elseif (strpos($message, "/registims") === 0) {
            if (mysqli_num_rows($query_trn) > 0) {  
                $no = 1;
                $response = "";
                $subjectMsg = "LIST APPROVAL IMS";  
                while($data_trn = mysqli_fetch_assoc($query_trn)) {
                    
$response .= '<u><b>'.$subjectMsg.'</b></u>

<b>'.$no++.'. /'.$data_trn["no_role_trans"].' : </b>'.$data_trn["name_role_trans"].'

<b>Note : </b>
<i>Untuk melakukan pengajuan pendaftaran approval ketik daftar kode perintah diatas dilanjutkan dengan 10 digit NIK ada contoh : /0001XXXXXXXXXX.</i>';

                }

                $data = [
                    'chat_id' => $chatId,
                    'text' => $response,
                    'parse_mode' => "html"
                ];
        
                sendMessageHtml($data);
            }
            else {
                $response = "Data master approval belum ada yang terdaftar!";
                sendMessage($chatId, $response);
            }
        }
        else {
            if (strlen($message) == 15) {
                $id = substr($message, 1, 4);
                $nik = substr($message, 5, 10);
                $query_code = mysqli_query($conn, "SELECT * FROM role_transaksi WHERE no_role_trans = '$id'");
                if ($query_code) {                
                    $data_code = mysqli_fetch_assoc($query_code);
                    $role = $data_code["no_role_trans"];
                    $trn = $data_code["inisial_role_trans"];

                    $query_user = mysqli_query($conn, "SELECT A.*, C.id_head_div FROM users AS A 
                    INNER JOIN divisi AS B ON A.id_divisi = B.id_divisi
                    INNER JOIN head_divisi AS C ON B.id_head_divisi = C.id_head_div
                    WHERE A.nik = '$nik'");
                    if ($query_user) {                
                        $data_user = mysqli_fetch_assoc($query_user);
                        $office = $data_user["id_office"];
                        $headiv = $data_user["id_head_div"];

                        if (strpos($message, "/".$data_code["no_role_trans"].$data_user["nik"]) === 0) {
                            if ($nik != $data_user["nik"]) {
                                $response = "NIK tidak terdaftar!";
                                sendMessage($chatId, $response);
                            }
                            else {
                                if ($data_user["status"] == "N") {
                                    $response = "NIK anda belum diverifikasi, silahkan hubungi admin";
                                    sendMessage($chatId, $response);
                                }
                                else {
                                    if(empty($data_user["id_department"])) {
                                        $response = "NIK anda belum terdaftar departemen, silahkan hubungi admin";
                                        sendMessage($chatId, $response);
                                    }
                                    else {
                                        if(empty($data_user["id_divisi"])) {
                                            $response = "NIK anda belum terdaftar divisi, silahkan hubungi admin";
                                            sendMessage($chatId, $response);
                                        }
                                        else {
                                            if(empty($data_user["id_group"])) {
                                                $response = "NIK anda belum terdaftar grup, silahkan hubungi admin";
                                                sendMessage($chatId, $response);
                                            }
                                            else {
                                                $query_app = mysqli_query($conn, "SELECT * FROM user_telebot WHERE role_user_tele = '$trn' AND div_user_tele = '$headiv' AND no_user_tele = '$chatId' AND office_user_tele = '$office' AND nik_user_tele = '$nik'");
                                                if (mysqli_num_rows($query_app) > 0) {
$response = '<b>GAGAL!</b>

<i>Pengajuan approval '.$data_code["name_role_trans"].' dengan NIK <b>'.$data_user["nik"].' - '.strtoupper($data_user["username"]).'</b> sudah terdaftar ditabel approval.</i>';
                                                    $data = [
                                                        'chat_id' => $chatId,
                                                        'text' => $response,
                                                        'parse_mode' => "html"
                                                    ];

                                                    sendMessageHtml($data);
                                                }
                                                else {
                                                    mysqli_query($conn, "INSERT INTO user_telebot (role_user_tele, div_user_tele, no_user_tele, office_user_tele, nik_user_tele, status_user_tele) VALUES ('$trn', '$headiv', '$chatId', '$office', '$nik', 'N')");
$response = '<b>SUCCESS!</b>

<i>Pengajuan approval '.$data_code["name_role_trans"].' dengan NIK <b>'.$data_user["nik"].' - '.strtoupper($data_user["username"]).'</b> berhasil di daftarkan. Silahkan hubungi admin untuk dilakukan verifikasi.</i>';
                                                    $data = [
                                                        'chat_id' => $chatId,
                                                        'text' => $response,
                                                        'parse_mode' => "html"
                                                    ];
            
                                                    sendMessageHtml($data);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            $response = "Gagal merespon data user!";
                            sendMessage($chatId, $response);
                        }
                    }
                    else {
                        $response = "Gagal merespon data user!";
                        sendMessage($chatId, $response);
                    }
                }
                else {
                    $response = "Gagal merespon daftar data approval!";
                    sendMessage($chatId, $response);
                }
            }
            // else {
            //     $response = "Command cannot be executed!";
            //     sendMessage($chatId, $response);
            // }
        }

        if (strpos($message, "/approvalims") === 0) {
            $content = '<u><b></b></u>

<b>AKSI : </b>
<b>DOCNO : </b>

<b>OFFICE CODE : </b>
<b>DIVISI : </b>
<b>TANGGAL : </b>
<b>PENGAJUAN : </b><i></i>
<b>KETERANGAN : </b>

<b>APPROVAL : </b>
<b>OTP CODE : </b>
<b>LINK APPROVAL : </b><a href="#">#</a>
<b>LINK GSHEET : </b><a href="#">LINK</a>

<b>Note : </b>
<i>Pesan ini hanya notifikasi, untuk melakukan approval melalui link ims approval presensi.</i>';

            $data = [
                'chat_id' => $chatId,
                'text' => $content,
                'parse_mode' => "html"
            ];
            $text = "Apakah anda menyetujui pengajuan diatas?";
            $replyMarkup = [
                'text' => $text,
                'inline_keyboard' => [
                    [
                        ['text' => 'Yes', 'callback_data' => 'approve_yesAKH001'],
                        ['text' => 'No', 'callback_data' => 'approve_noAKH001']
                    ]
                ]
            ];
            sendMessageHtml($data);
            sendMessageapproval($chatId, $text, $replyMarkup);
        }
    }
    elseif (isset($update["callback_query"])) {
        global $apiUrl;
        $callbackQueryId = $update["callback_query"]["id"];
        $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];
        $callbackMessageId = $update["callback_query"]["message"]["message_id"];
        $callbackData = $update["callback_query"]["data"];
    
        // Hapus pesan callback
        $sendResponseNotif = "answerCallbackQuery?callback_query_id=" . $callbackQueryId . "&text=";
        // Kirim respon ke pengguna
        $sendResponseText = "/sendMessage?chat_id=" . $callbackChatId . "&text=";
        // Menghapus reply markup dari pesan
        $deleteConfirmation = "editMessageReplyMarkup?chat_id=" . $callbackChatId . "&message_id=" . $callbackMessageId;
         // Jika ingin menghapus pesan callback (opsional)
        $deleteText = "deleteMessage?chat_id=" . $callbackChatId . "&message_id=" . $callbackMessageId;

        if ($callbackData == 'approve_yesAKH001') {
            $responseText = "Transaksi disetujui.";
            file_get_contents($apiUrl . $sendResponseNotif . urlencode($responseText));
            file_get_contents($apiUrl . $sendResponseText . $responseText);
            file_get_contents($apiUrl . $deleteConfirmation);
            file_get_contents($apiUrl . $deleteText);
        } elseif ($callbackData == 'approve_noAKH001') {
            $responseText = "Transaksi ditolak.";
            file_get_contents($apiUrl . $sendResponseNotif . urlencode($responseText));
            file_get_contents($apiUrl . $sendResponseText . $responseText);
            file_get_contents($apiUrl . $deleteConfirmation);
            file_get_contents($apiUrl . $deleteText);
        }
    }
    else {
        error_log("No message received or invalid update structure.");
    }
?>