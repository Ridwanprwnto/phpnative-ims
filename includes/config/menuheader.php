<?php
    if(isset($_GET["page"])) {
        $modul  = $_GET["page"];
        if($_GET["page"] === $modul) {
            $strplus_page = rplplus($modul);
            $decpage = mysqli_real_escape_string($conn, decrypt($strplus_page));
            if($decpage == true) {

                $query_pm = mysqli_query($conn, "SELECT parentmenu.*, akses_parentmenu.* FROM parentmenu 
                INNER JOIN akses_parentmenu ON parentmenu.id_parentmenu = akses_parentmenu.id_parentmenu
                WHERE parentmenu.id_parentmenu = '$decpage' AND akses_parentmenu.id_group = '$id_group' AND akses_parentmenu.parentmenu_status = 'Y'");
                $data_pm = mysqli_fetch_assoc($query_pm);

                $query_cm = mysqli_query($conn, "SELECT childmenu.*, parentmenu.*, akses_childmenu.* FROM childmenu
                INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu
                INNER JOIN akses_childmenu ON childmenu.id_childmenu = akses_childmenu.id_childmenu
                WHERE childmenu.id_childmenu = '$decpage' AND akses_childmenu.id_group = '$id_group' AND akses_childmenu.childmenu_status = 'Y'");
                $data_cm = mysqli_fetch_assoc($query_cm);

                $query_gm = mysqli_query($conn, "SELECT grandchildmenu.*, childmenu.*, parentmenu.*, akses_grandchildmenu.* FROM grandchildmenu
                INNER JOIN childmenu ON grandchildmenu.id_childmenu = childmenu.id_childmenu
                INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu
                INNER JOIN akses_grandchildmenu ON grandchildmenu.id_grandchildmenu = akses_grandchildmenu.id_grandchildmenu
                WHERE grandchildmenu.id_grandchildmenu = '$decpage' AND akses_grandchildmenu.id_group = '$id_group' AND akses_grandchildmenu.grandchildmenu_status = 'Y'");
                $data_gm = mysqli_fetch_assoc($query_gm);

                if ($id_group == $admin) {
                    $cek_datapm = isset($data_pm["id_parentmenu"]) ? $data_pm["id_parentmenu"] : null;
                    $cek_datacm = isset($data_cm["id_childmenu"]) ? $data_cm["id_childmenu"] : null;
                    $cek_datagm = isset($data_gm["id_grandchildmenu"]) ? $data_gm["id_grandchildmenu"] : null;
                    if($decpage === $cek_datapm) {
                    $enc_pm = encrypt($data_pm["id_parentmenu"]);
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_pm["parentmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_pm["parentmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif($decpage === $cek_datacm) { 
                    $enc_cm = encrypt($data_cm["id_childmenu"]);
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_cm["childmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_cm["parentmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_cm["childmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif($decpage === $cek_datagm) {
                    $enc_gm = encrypt($data_gm["id_grandchildmenu"]);
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_gm["grandchildmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_gm["parentmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_gm["childmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_gm["grandchildmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>
                            
                    <?php }
                    elseif($decpage === "profile") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Profile</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Profile
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif($decpage === "username") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Change Username</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Change Username
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif($decpage === "password") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Change Password</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Change Password
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif($decpage === "searchmenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Search Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Search Menu
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif ($decpage === "office") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Office</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Struktur</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Office
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif ($decpage === "company") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Company</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Struktur</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Company
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "department") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Department</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Struktur</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Department
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "divisi") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Divisi</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Struktur</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Divisi
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "group") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Group</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Role Access</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Group
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "level") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Level</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Role Access</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Level
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "parentmenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Parent Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Pages</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Parent Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "childmenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Child Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Pages</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Child Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "grandchildmenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Grand Child Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Pages</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Grand Child Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "extendmenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Extend Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Pages</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Extend Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "profilemenu") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Profile Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Pages</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Profile Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "accesspm") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Access Parent Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Groups</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Acces Parent Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "accesscm") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Access Child Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Groups</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Acces Child Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "accessgm") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Access Grand Child Menu</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Menu Groups</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Acces Grand Child Menu
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "emailserver") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Mail Server</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Mail Server
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                    elseif ($decpage === "category") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Category</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Category
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php
                    }
                    elseif ($decpage === "satuan") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Tabel Satuan</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Tabel Satuan
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php
                    }
                    elseif ($decpage === "kondisi") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Tabel Kondisi</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Tabel Kondisi
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "spp") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Tabel SPP</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Tabel SPP
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "statusp3at") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Tabel Status P3AT</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Tabel Status P3AT
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "crud") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Tabel CRUD</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Tabel CRUD
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "mastertelebot") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master Telegram BOT</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master Telegram BOT
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "apiservices") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Master API Services</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Master API Services
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    elseif ($decpage === "simulasi") {
                    ?>

                        <h3 class="content-header-title mb-0 d-inline-block">Simulasi Fitur</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Administrator</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Setting</a>
                            </li>
                            </li>
                            <li class="breadcrumb-item active">Simulasi Fitur
                            </li>
                            </ol>
                        </div>
                        </div>

                    <?php }
                    else { ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Administrator
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php
                    }
                }
                else {
                    $cek_datapm = isset($data_pm["id_parentmenu"]) ? $data_pm["id_parentmenu"] : null;
                    $cek_datacm = isset($data_cm["id_childmenu"]) ? $data_cm["id_childmenu"] : null;
                    $cek_datagm = isset($data_gm["id_grandchildmenu"]) ? $data_gm["id_grandchildmenu"] : null;
                    if($decpage === $cek_datapm) {
                    $enc_pm = encrypt($data_pm["id_parentmenu"]);
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_pm["parentmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_pm["parentmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif($decpage === $cek_datacm) { 
                    $enc_cm = encrypt($data_cm["id_childmenu"]);
                    ?>
                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_cm["childmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_cm["parentmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_cm["childmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>

                    <?php }
                    elseif($decpage === $cek_datagm) {
                    $enc_gm = encrypt($data_gm["id_grandchildmenu"]);
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block"><?= $data_gm["grandchildmenu_name"]; ?></h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_gm["parentmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#"><?= $data_gm["childmenu_name"]; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= $data_gm["grandchildmenu_name"]; ?>
                            </li>
                        </ol>
                        </div>
                    </div>
                            
                    <?php }
                    elseif($decpage === "profile") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Profile</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Profile
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif($decpage === "username") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Change Username</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Change Username
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif($decpage === "password") {
                    ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Change Password</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                            </li>
                            <li class="breadcrumb-item active">Change Password
                            </li>
                        </ol>
                        </div>
                    </div>
                
                    <?php }
                    elseif($decpage === "searchmenu") {
                        ?>
    
                        <h3 class="content-header-title mb-0 d-inline-block">Search Menu</h3>
                        <div class="row breadcrumbs-top d-inline-block">
                            <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><?= $id_group == $admin ? 'Administrator' : 'General'; ?></a>
                                </li>
                                <li class="breadcrumb-item active">Search Menu
                                </li>
                            </ol>
                            </div>
                        </div>
                    
                    <?php }
                    else { ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">General
                            </li>
                        </ol>
                        </div>
                    </div>
                    
                    <?php
                    }
                }
            }
            else { 
                if ($id_group == $admin) { ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Administrator
                        </li>
                        </ol>
                    </div>
                    </div>
                
                <?php }
                else { ?>

                    <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item active">General
                        </li>
                        </ol>
                    </div>
                    </div>

                <?php
                }
            }
        }
    }
    else { 
        if ($id_group == $admin) {?>
        
            <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
            <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                <li class="breadcrumb-item active">Administrator
                </li>
                </ol>
            </div>
            </div>
        
        <?php }
        else { ?>

            <h3 class="content-header-title mb-0 d-inline-block">Dashboard</h3>
            <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                <li class="breadcrumb-item active">General
                </li>
                </ol>
            </div>
            </div>

        <?php
        }
    }
?>