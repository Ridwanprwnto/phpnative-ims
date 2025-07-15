<?php

    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $monthrn = date('Y-m');

?>

<?php
// <!-- Dashboard Group Administrator -->
if ($id_group == $arrgroup[0]) { ?>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
    <div class="row">
        <div class="col-12 mt-3 mb-1">
            <h4 class="text-uppercase">Data Structure</h4>
            <p>Summary of all data structure.</p>
        </div>
        </div>
        <div class="row">
        <div class="col-xl-6 col-md-12">
            <div class="card overflow-hidden">
            <div class="card-content">
                <div class="card-body cleartfix">
                <div class="media align-items-stretch">
                    <div class="align-self-center">
                    <a href="index.php?page=<?= encrypt('company');?>" target=""><i class="la la-industry success font-large-2 mr-2"></i></a>
                    </div>
                    <div class="media-body">
                    <h4>Company</h4>
                    <span>Total Data Company</span>
                    </div>
                    <div class="align-self-center">
                    <?php
                    $q_cp = mysqli_query($conn, "SELECT COUNT(company_id) AS total_company FROM company");
                    $d_cp = mysqli_fetch_assoc($q_cp);
                    ?>
                    <h1><?= $d_cp["total_company"]; ?></h1>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
            <div class="card-content">
                <div class="card-body cleartfix">
                <div class="media align-items-stretch">
                    <div class="align-self-center">
                    <a href="index.php?page=<?= encrypt('office');?>" target=""><i class="la la-building-o info font-large-2 mr-2"></i></a>
                    </div>
                    <div class="media-body">
                    <h4>Office</h4>
                    <span>Total Data Office</span>
                    </div>
                    <div class="align-self-center">
                    <?php
                    $q_office = mysqli_query($conn, "SELECT COUNT(id_office) AS total_office FROM office");
                    $d_office = mysqli_fetch_assoc($q_office);
                    ?>
                    <h1><?= $d_office["total_office"]; ?></h1>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('department');?>" target=""><i class="la la-area-chart warning font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Department</h4>
                            <span>Total Data Department</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_dept = mysqli_query($conn, "SELECT COUNT(id_department) AS total_dept FROM department");
                            $d_dept = mysqli_fetch_assoc($q_dept);
                            ?>
                            <h1><?= $d_dept["total_dept"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('divisi');?>" target=""><i class="la la-suitcase primary font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Divisi</h4>
                            <span>Total Data Divisi</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_div = mysqli_query($conn, "SELECT COUNT(id_divisi) AS total_divisi FROM divisi");
                            $d_div = mysqli_fetch_assoc($q_div);
                            ?>
                            <h1><?= $d_div["total_divisi"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- // stats with subtitle section end -->

    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data ETC</h4>
                <p>Summary of all data other.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                <div class="card-content">
                    <div class="card-body">
                    <div class="media d-flex">
                        <div class="align-self-center">
                        <i class="la la-gear success font-large-2 float-left"></i>
                        </div>
                        <div class="media-body text-right">
                        <?php
                            $q_katbar = mysqli_query($conn, "SELECT COUNT(id) AS total_katbar FROM categorybarang");
                            $d_katbar = mysqli_fetch_assoc($q_katbar);
                            ?>
                        <h3><?= $d_katbar["total_katbar"];?></h3>
                        <span>Master kategori Barang</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                <div class="card-content">
                    <div class="card-body">
                    <div class="media d-flex">
                        <div class="align-self-center">
                        <i class="la la-cube info font-large-2 float-left"></i>
                        </div>
                        <div class="media-body text-right">
                        <?php
                            $q_master_kat = mysqli_query($conn, "SELECT COUNT(IDBarang) AS total_masterkat FROM mastercategory");
                            $d_master_kat = mysqli_fetch_assoc($q_master_kat);
                            ?>
                        <h3><?= $d_master_kat["total_masterkat"];?></h3>
                        <span>Master Jenis Barang</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                <i class="la la-dropbox warning font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-right">
                                <?php
                                    $q_master_jns = mysqli_query($conn, "SELECT COUNT(IDJenis) AS total_masterjenis FROM masterjenis");
                                    $d_master_jns = mysqli_fetch_assoc($q_master_jns);
                                    ?>
                                <h3><?= $d_master_jns["total_masterjenis"];?></h3>
                                <span>Master Barang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="la la-users primary font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-right">
                                    <?php
                                    $q_users = mysqli_query($conn, "SELECT COUNT(nik) AS total_users FROM users");
                                    $d_users = mysqli_fetch_assoc($q_users);
                                    ?>
                                <h3><?= $d_users["total_users"]; ?></h3>
                                <span>Data Users</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Minimal statistics section end -->
<?php }
// <!-- Dashboard Group Support -->
elseif ($id_group == $arrgroup[1]) { ?>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
    <div class="row">
        <div class="col-12 mt-3 mb-1">
            <h4 class="text-uppercase">Data Transaction</h4>
            <p>Summary of all data transaction.</p>
        </div>
        </div>
        <div class="row">
        <div class="col-xl-6 col-md-12">
            <div class="card overflow-hidden">
            <div class="card-content">
                <div class="card-body cleartfix">
                <div class="media align-items-stretch">
                    <div class="align-self-center">
                    <a href="index.php?page=<?= encrypt('G006');?>" target=""><i class="icon-note success font-large-2 mr-2"></i></a>
                    </div>
                    <div class="media-body">
                    <h4>Serah Terima Handheld</h4>
                    <span>All Data Serah Terima Handheld</span>
                    </div>
                    <div class="align-self-center">
                    <?php
                    $q_hh = mysqli_query($conn, "SELECT COUNT(no_pinjam) AS hhpinjam FROM sthh WHERE id_office = '$office_id' AND id_department = '$dept_id' AND datein IS NULL AND penerima IS NULL");
                    $d_hh = mysqli_fetch_assoc($q_hh);
                    ?>
                    <h1><?= $d_hh["hhpinjam"]; ?></h1>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
            <div class="card-content">
                <div class="card-body cleartfix">
                <div class="media align-items-stretch">
                    <div class="align-self-center">
                    <a href="index.php?page=<?= encrypt('G012');?>" target=""><i class="icon-basket info font-large-2 mr-2"></i></a>
                    </div>
                    <div class="media-body">
                    <h4>Daftar Permohonan Pembelian</h4>
                    <span>All Data Permohonan Pembelian</span>
                    </div>
                    <div class="align-self-center">
                    <?php
                    $q_pp = mysqli_query($conn, "SELECT COUNT(id_pembelian) AS total_pembelian FROM pembelian WHERE id_office = '$office_id' AND id_department = '$dept_id' AND status_pp NOT LIKE '$arrsp[10]'");
                    $d_pp = mysqli_fetch_assoc($q_pp);
                    ?>
                    <h1><?= $d_pp["total_pembelian"]; ?></h1>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G062');?>" target=""><i class="icon-fire warning font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                                <h4>Monitoring Pemusnahan Barang</h4>
                                <span>All Data Sarana Elektrikal (P3AT)</span>
                            </div>
                            <div class="align-self-center">
                                <?php
                                    $q_listp3at = mysqli_query($conn, "SELECT COUNT(status_p3at) AS total_p3at FROM p3at WHERE office_p3at = '$office_id' AND dept_p3at = '$dept_id' AND status_p3at != 'T03'");
                                    $d_listp3at = mysqli_fetch_assoc($q_listp3at);
                                ?>
                                <h1><?= $d_listp3at["total_p3at"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('G026');?>" target=""><i class="icon-ghost danger font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Monitoring Data Barang Hilang</h4>
                            <span>All Data Sarana Elektrikal Hilang / Belum Ditemukan</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_hilang = mysqli_query($conn, "SELECT COUNT(id_ba) AS total_hilang FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' AND kondisi = '$arrcond[6]'");
                            $d_hilang = mysqli_fetch_assoc($q_hilang);
                            ?>
                            <h1><?= $d_hilang["total_hilang"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('G023');?>" target=""><i class="icon-wrench primary font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Monitoring Data Pengajuan Perbaikan</h4>
                            <span>All Data Sarana Elektrikal Pengajuan Perbaikan / Rekomendasi Pemusnahan</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_perbaikan = mysqli_query($conn, "SELECT COUNT(id_ba) AS total_perbaikan FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' AND kondisi = '$arrcond[3]'");
                            $d_perbaikan = mysqli_fetch_assoc($q_perbaikan);
                            ?>
                            <h1><?= $d_perbaikan["total_perbaikan"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('G096');?>" target=""><i class="icon-bar-chart info font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Monitoring Data Project</h4>
                            <span>All Data Pending Task List Project</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_project = mysqli_query($conn, "SELECT COUNT(no_head_project ) AS total_project FROM head_project WHERE office_head_project = '$office_id' AND dept_head_project = '$dept_id' AND status_head_project = 'N'");
                            $d_project = mysqli_fetch_assoc($q_project);
                            ?>
                            <h1><?= $d_project["total_project"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- // stats with subtitle section end -->
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Chart</h4>
                <p>Summary of all data chart.</p>
            </div>
        </div>
        <!-- Bar Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Kondisi Barang Sarana Elektrikal</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                        <canvas id="bar-chart-kondisi" height="3200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Table</h4>
                <p>Summary of all data table.</p>
            </div>
        </div>
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Barang Under Stock</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stock</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql_sunder = "SELECT A.pluid, A.saldo_akhir, B.NamaBarang, C.NamaJenis, D.nama_satuan FROM masterstock AS A
                            INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
                            INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
                            INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
                            WHERE A.ms_id_office = '$office_id' AND A.ms_id_department = '$dept_id' AND A.saldo_akhir < 6 ORDER BY A.saldo_akhir ASC";
                            $query_sunder = mysqli_query($conn, $sql_sunder);
                            if(mysqli_num_rows($query_sunder) > 0 ) {
                            while($data_sunder = mysqli_fetch_assoc($query_sunder)){ 
                            
                                $barang = $data_sunder['pluid'];
                                $desc = $data_sunder['NamaBarang']." ".$data_sunder['NamaJenis'];
                                $satuan = $data_sunder['nama_satuan'];
                                $saldo = $data_sunder['saldo_akhir'];

                                if ($saldo >= 3 && $saldo < 5 ) {
                                    $color_stck = "class='bg-warning white'";
                                }
                                elseif ($saldo < 3 ) {
                                    $color_stck = "class='bg-danger white'";
                                }
                                else {
                                    $color_stck = "";
                                }

                            ?>
                            <tr <?= $color_stck; ?>>
                                <td><?= $no++; ?></td>
                                <td><?= $barang; ?></td>
                                <td><?= $desc; ?></td>
                                <td><?= $satuan; ?></td>
                                <td><?= $saldo; ?></td>
                            </tr>
                            <?php } 
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom row colors end -->
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar DAT VS Fisik Sarana Elektrikal</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>DAT</th>
                            <th>Fisik</th>
                            <th>Selisih</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nol = [0, 0, 0];
                            $sql_selisih = "SELECT A.pluid, COUNT(A.pluid) AS saldo_asset, B.NamaBarang, C.NamaJenis FROM barang_assets AS A
                            INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
                            INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
                            WHERE A.ba_id_office = '$office_id' AND A.ba_id_department = '$dept_id' AND A.kondisi NOT LIKE '$arrcond[5]' GROUP BY A.pluid";

                            $query_selisih = mysqli_query($conn, $sql_selisih);
                            if(mysqli_num_rows($query_selisih) > 0 ) {
                            while($data_selisih = mysqli_fetch_assoc($query_selisih)){ 
                            

                                $plu = $data_selisih["pluid"];
                                $desc = $data_selisih['NamaBarang']." ".$data_selisih['NamaJenis'];
                                $asset = $data_selisih["saldo_asset"];             
                                
                                $data_fisik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT saldo_akhir FROM masterstock WHERE ms_id_office = '$office_id' AND ms_id_department = '$dept_id' AND pluid = '$plu'"));

                                $fisik = isset($data_fisik["saldo_akhir"]) ? $data_fisik["saldo_akhir"] : 0;
                                $selisih = ($fisik - $asset);

                                if ($asset != $fisik ) {
                                    if ($selisih < 0) {
                                        $color_selisih = "class='bg-danger white'";
                                    }
                                    else {
                                        $color_selisih = "";
                                    }
                                    ?>
                                    <tr <?= $color_selisih; ?>>
                                        <td><?= $no++; ?></td>
                                        <td><?= $plu; ?></td>
                                        <td><?= $desc; ?></td>
                                        <td><?= $asset; ?></td>
                                        <td><?= $fisik; ?></td>
                                        <td><?= $selisih; ?></td>
                                    </tr>
                                    <?php
                                }
                                } 
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom row colors end -->
    </section>
    <!-- // Minimal statistics section end -->
<?php }
// <!-- Dashboard Group Approval -->
elseif ($id_group == $arrgroup[2]) { ?>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Transaction</h4>
                <p>Summary of all data transaction.</p>
            </div>
        </div>
        <!-- Pie charts section start -->
        <div class="row">
            <!-- Doughnut Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G012');?>" target=""><i class="icon-basket-loaded icon font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                <h4>Daftar Permohonan Pembelian</h4>
                                <span>All Data Permohonan Pembelian</span>
                                </div>
                                <div class="align-self-center">
                                <?php
                                $q_pp = mysqli_query($conn, "SELECT COUNT(id_pembelian) AS total_pembelian FROM pembelian WHERE id_office = '$office_id' AND id_department = '$dept_id' AND status_pp NOT LIKE '$arrsp[10]'");
                                $d_pp = mysqli_fetch_assoc($q_pp);
                                ?>
                                <h1><?= $d_pp["total_pembelian"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
            <!-- Doughnut Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G082');?>" target=""><i class="icon-eye warning font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                <h4>Daftar Pelanggaran CCTV</h4>
                                <span>Menunggu Approval</span>
                                </div>
                                <div class="align-self-center">
                                <?php
                                $q_sts_plg = mysqli_query($conn, "SELECT COUNT(status_plg_cctv) AS jumlah_fup_pel FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND status_plg_cctv = 'N'");
                                $d_sts_plg = mysqli_fetch_assoc($q_sts_plg);
                                ?>
                                <h1><?= $d_sts_plg["jumlah_fup_pel"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Chart</h4>
                <p>Summary of all data chart.</p>
            </div>
        </div>
        <div class="row">
            <!-- Simple Pie Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status Follow Up Pelanggaran CCTV</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <canvas id="chart-sp" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
            <!-- Simple Pie Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Kategori Terekam Pelanggaran CCTV</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <canvas id="categ-chart" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
        </div>
        <!-- Column Stacked Chart -->
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Status Follow Up Pelanggaran CCTV Perbulan</h4>
                  <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                      <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                  </div>
                </div>
                <div class="card-content collapse show">
                  <div class="card-body">
                    <canvas id="chart-bar-fupplg" height="400"></canvas>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- Bar Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Pelanggaran CCTV Perbagian</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                        <canvas id="bar-chart-plg" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Table</h4>
                <p>Summary of all data table.</p>
            </div>
        </div>
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Users Tercatat Pelanggaran CCTV Bulan <?= date('F Y', strtotime($monthrn)) ?> </h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bagian</th>
                                        <th>User</th>
                                        <th>Teguran Lisan</th>
                                        <th>Surat Teguran</th>
                                        <th>Surat Perigatan</th>
                                        <th>Tidak Terindikasi</th>
                                        <th>Jumlah Pelanggaran</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $nocctv = 1;
                                        $nol = [0, 0, 0, 0, 0];
                                        $sql_userp = "SELECT A.no_plg_cctv, A.office_plg_cctv, A.dept_plg_cctv, user_pelanggaran_cctv.username_plg_cctv, C.nik, C.full_name, D.divisi_name FROM pelanggaran_cctv AS A 
                                        INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv 
                                        LEFT JOIN users AS C ON LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) = C.nik 
                                        LEFT JOIN divisi AS D ON C.id_divisi = D.id_divisi 
                                        WHERE A.office_plg_cctv = '$office_id' AND A.dept_plg_cctv = '$dept_id' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') GROUP BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ORDER BY COUNT(LEFT(user_pelanggaran_cctv.username_plg_cctv, 10)) DESC";

                                        $query_userp = mysqli_query($conn, $sql_userp);
                                        if(mysqli_num_rows($query_userp) > 0 ) {
                                            while($data_userp = mysqli_fetch_assoc($query_userp)) { 
                                                
                                                $user_detail = substr($data_userp["username_plg_cctv"], 0, 10);

                                                $user_plg_cctv = $data_userp['username_plg_cctv'];
                                                $bagian_plg_cctv = isset($data_userp['divisi_name']) ? $data_userp['divisi_name'] : "-";
                                                $office_plg_cctv = $data_userp['office_plg_cctv'];
                                                $dept_plg_cctv = $data_userp['dept_plg_cctv'];

                                                $sql_1 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tl FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '1' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_1));

                                                $sql_3 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_st FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '3' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk3 = mysqli_fetch_assoc(mysqli_query($conn, $sql_3));
                                                
                                                $sql_4 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_sp FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '4' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk4 = mysqli_fetch_assoc(mysqli_query($conn, $sql_4));
                                                
                                                $sql_5 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tt FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '5' AND LEFT(B.username_plg_cctv, 10) = '$user_detail' ";
                                                $snk5 = mysqli_fetch_assoc(mysqli_query($conn, $sql_5));
                                            
                                                $jumlah_tl = $snk1['jumlah_tl'];
                                                $jumlah_st = $snk3['jumlah_st'];
                                                $jumlah_sp = $snk4['jumlah_sp'];
                                                $jumlah_tt = $snk5['jumlah_tt'];
                                                $jumlah_plg_cctv = $jumlah_tl + $jumlah_st + $jumlah_sp + $jumlah_tt;
                                                
                                                if ($jumlah_plg_cctv >= 3 && $jumlah_plg_cctv <= 5 ) {
                                                    $color_stck = "class='bg-warning white'";
                                                    $color_txt = "white";
                                                }
                                                elseif ($jumlah_plg_cctv >= 5 ) {
                                                    $color_stck = "class='bg-danger white'";
                                                    $color_txt = "white";
                                                }
                                                else {
                                                    $color_stck = "";
                                                }
                                                $color_txt = "secondary";
                                                ?>
                                                    <tr <?= $color_stck; ?>>
                                                        <td><?= $nocctv++; ?></td>
                                                        <td><?= $bagian_plg_cctv; ?></td>
                                                        <td><a title="Show Detail Data Pelanggaran User : <?= $user_plg_cctv; ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" class="text-<?= $color_txt; ?> detail_spc" name="detail_spc" id="<?= $office_plg_cctv.$dept_plg_cctv.$monthrn.$user_plg_cctv; ?>"><?= $user_plg_cctv; ?></a></td>
                                                        <td><?= $jumlah_tl; ?></td>
                                                        <td><?= $jumlah_st; ?></td>
                                                        <td><?= $jumlah_sp; ?></td>
                                                        <td><?= $jumlah_tt; ?></td>
                                                        <td><strong><?= $jumlah_plg_cctv; ?></strong></td>
                                                    </tr>
                                                <?php
                                                    $jmlall_tl = ($nol[0] += $jumlah_tl);
                                                    $jmlall_st = ($nol[1] += $jumlah_st);
                                                    $jmlall_sp = ($nol[2] += $jumlah_sp);
                                                    $jmlall_tt = ($nol[3] += $jumlah_tt);
                                                    $total = ($nol[4] += $jumlah_plg_cctv);
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td colspan='3'><strong>Jumlah Data</strong></td>
                                            <td><?= isset($jmlall_tl) ? $jmlall_tl : NULL; ?></td>
                                            <td><?= isset($jmlall_st) ? $jmlall_st : NULL; ?></td>
                                            <td><?= isset($jmlall_sp) ? $jmlall_sp : NULL; ?></td>
                                            <td><?= isset($jmlall_tt) ? $jmlall_tt : NULL; ?></td>
                                            <td><strong><?= isset($total) ? $total : NULL;; ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom row colors end -->
    </section>
    <!-- // Minimal statistics section end -->
<?php }
// <!-- Dashboard Group Supervisor -->
elseif ($id_group == $arrgroup[3]) { ?>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Transaction</h4>
                <p>Summary of all data transaction.</p>
            </div>
        </div>
        <!-- Pie charts section start -->
        <div class="row">
            <!-- Doughnut Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G012');?>" target=""><i class="icon-basket-loaded icon font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                <h4>Daftar Permohonan Pembelian</h4>
                                <span>All Data Permohonan Pembelian</span>
                                </div>
                                <div class="align-self-center">
                                <?php
                                $q_listpp = mysqli_query($conn, "SELECT COUNT(noref) AS total_pp FROM pembelian WHERE id_office = '$office_id' AND id_department = '$dept_id'");
                                $d_listpp = mysqli_fetch_assoc($q_listpp);
                                ?>
                                <h1><?= $d_listpp["total_pp"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
            <!-- Doughnut Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G079');?>" target=""><i class="icon-eye warning font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                <h4>Daftar Pelanggaran CCTV</h4>
                                <span>All Data Menunggu Follow Up</span>
                                </div>
                                <div class="align-self-center">
                                <?php
                                $q_sts_plg = mysqli_query($conn, "SELECT COUNT(status_plg_cctv) AS jumlah_fup_pel FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND status_plg_cctv = 'S'");
                                $d_sts_plg = mysqli_fetch_assoc($q_sts_plg);
                                ?>
                                <h1><?= $d_sts_plg["jumlah_fup_pel"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Table</h4>
                <p>Summary of all data table.</p>
            </div>
        </div>
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Rekap Absensi Harian Tanggal <?= date("d M Y"); ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Bagian</th>
                            <th>User</th>
                            <th>Alasan Tidak Hadir</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $datenow = date("Y-m-d");
                            $nobsen = 1;
                            $sql_absensi = "SELECT * FROM presensi WHERE office_presensi = '$office_id' AND dept_presensi = '$dept_id' AND tgl_presensi = '$datenow' AND cek_presensi != 'TUKAR OFF' AND cek_presensi != 'RUBAH SHIFT' ORDER BY nik_presensi ASC";

                            $query_absensi = mysqli_query($conn, $sql_absensi);
                            if(mysqli_num_rows($query_absensi) > 0 ) {
                                while($data_absensi = mysqli_fetch_assoc($query_absensi)) { 
                                
                                    $divisi_absensi = $data_absensi['div_presensi'];
                                    $hadir_absensi = $data_absensi['cek_presensi'];
                                    $user_absensi = $data_absensi['nik_presensi']." - ".$data_absensi['user_presensi'];
                                    $keterangan_absensi = $data_absensi['ket_presensi'] == "" ? "-" : $data_absensi['ket_presensi'];
                                    
                                    if ($hadir_absensi == "ALPA") {
                                        $color_absensi = "class='bg-danger white'";
                                        $txt_absensi = "white";
                                    }
                                    elseif ($hadir_absensi == "CUTI" || $hadir_absensi == "CUTI MENDADAK") {
                                        $color_absensi = "class='bg-warning white'";
                                        $txt_absensi = "white";
                                    }
                                    elseif ($hadir_absensi == "SAKIT") {
                                        $color_absensi = "class='bg-info white'";
                                        $txt_absensi = "white";
                                    }
                                    else {
                                        $color_absensi = "";
                                        $txt_absensi = "secondary";
                                    }

                                    ?>
                                    <tr <?= $color_absensi; ?>>
                                        <td><?= $nobsen++; ?></td>
                                        <td><?= $divisi_absensi; ?></td>
                                        <td><?= $user_absensi; ?></td>
                                        <td><?= $hadir_absensi; ?></td>
                                        <td><?= $keterangan_absensi; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom row colors end -->
    </section>
    <!-- // Minimal statistics section end -->
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Chart</h4>
                <p>Summary of all data chart.</p>
            </div>
        </div>
        <!-- Pie charts section start -->
        <div class="row">
            <!-- Simple Pie Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status Follow Up Pelanggaran CCTV</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <canvas id="chart-sp" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
            <!-- Simple Pie Chart -->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Kategori Terekam Pelanggaran CCTV</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <canvas id="categ-chart" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Simple Pie Chart -->
        </div>
        <!-- Column Stacked Chart -->
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Status Follow Up Pelanggaran CCTV Perbulan</h4>
                  <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                      <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                  </div>
                </div>
                <div class="card-content collapse show">
                  <div class="card-body">
                    <canvas id="chart-bar-fupplg" height="400"></canvas>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </section>
    <!-- Minimal statistics section start -->
    <section id="minimal-statistics">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Table</h4>
                <p>Summary of all data table.</p>
            </div>
        </div>
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h4 class="card-title">Daftar Users Tercatat Pelanggaran CCTV Bulan <?= date('F Y', strtotime($monthrn)) ?> </h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bagian</th>
                                        <th>User</th>
                                        <th>Teguran Lisan</th>
                                        <th>Surat Teguran</th>
                                        <th>Surat Perigatan</th>
                                        <th>Tidak Terindikasi</th>
                                        <th>Jumlah Pelanggaran</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $nocctv = 1;
                                        $nol = [0, 0, 0, 0, 0];
                                        $sql_userp = "SELECT A.no_plg_cctv, A.office_plg_cctv, A.dept_plg_cctv, user_pelanggaran_cctv.username_plg_cctv, C.nik, C.full_name, D.divisi_name FROM pelanggaran_cctv AS A 
                                        INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv 
                                        LEFT JOIN users AS C ON LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) = C.nik 
                                        LEFT JOIN divisi AS D ON C.id_divisi = D.id_divisi 
                                        WHERE A.office_plg_cctv = '$office_id' AND A.dept_plg_cctv = '$dept_id' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') GROUP BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ORDER BY COUNT(LEFT(user_pelanggaran_cctv.username_plg_cctv, 10)) DESC";

                                        $query_userp = mysqli_query($conn, $sql_userp);
                                        if(mysqli_num_rows($query_userp) > 0 ) {
                                            while($data_userp = mysqli_fetch_assoc($query_userp)) { 
                                                
                                                $user_detail = substr($data_userp["username_plg_cctv"], 0, 10);

                                                $user_plg_cctv = $data_userp['username_plg_cctv'];
                                                $bagian_plg_cctv = isset($data_userp['divisi_name']) ? $data_userp['divisi_name'] : "-";
                                                $office_plg_cctv = $data_userp['office_plg_cctv'];
                                                $dept_plg_cctv = $data_userp['dept_plg_cctv'];

                                                $sql_1 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tl FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '1' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_1));

                                                $sql_3 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_st FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '3' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk3 = mysqli_fetch_assoc(mysqli_query($conn, $sql_3));
                                                
                                                $sql_4 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_sp FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '4' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                                $snk4 = mysqli_fetch_assoc(mysqli_query($conn, $sql_4));
                                                
                                                $sql_5 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tt FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '5' AND LEFT(B.username_plg_cctv, 10) = '$user_detail' ";
                                                $snk5 = mysqli_fetch_assoc(mysqli_query($conn, $sql_5));
                                            
                                                $jumlah_tl = $snk1['jumlah_tl'];
                                                $jumlah_st = $snk3['jumlah_st'];
                                                $jumlah_sp = $snk4['jumlah_sp'];
                                                $jumlah_tt = $snk5['jumlah_tt'];
                                                $jumlah_plg_cctv = $jumlah_tl + $jumlah_st + $jumlah_sp + $jumlah_tt;
                                                
                                                if ($jumlah_plg_cctv >= 3 && $jumlah_plg_cctv <= 5 ) {
                                                    $color_stck = "class='bg-warning white'";
                                                    $color_txt = "white";
                                                }
                                                elseif ($jumlah_plg_cctv >= 5 ) {
                                                    $color_stck = "class='bg-danger white'";
                                                    $color_txt = "white";
                                                }
                                                else {
                                                    $color_stck = "";
                                                }
                                                $color_txt = "secondary";
                                                ?>
                                                    <tr <?= $color_stck; ?>>
                                                        <td><?= $nocctv++; ?></td>
                                                        <td><?= $bagian_plg_cctv; ?></td>
                                                        <td><a title="Show Detail Data Pelanggaran User : <?= $user_plg_cctv; ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" class="text-<?= $color_txt; ?> detail_spc" name="detail_spc" id="<?= $office_plg_cctv.$dept_plg_cctv.$monthrn.$user_plg_cctv; ?>"><?= $user_plg_cctv; ?></a></td>
                                                        <td><?= $jumlah_tl; ?></td>
                                                        <td><?= $jumlah_st; ?></td>
                                                        <td><?= $jumlah_sp; ?></td>
                                                        <td><?= $jumlah_tt; ?></td>
                                                        <td><strong><?= $jumlah_plg_cctv; ?></strong></td>
                                                    </tr>
                                                <?php
                                                    $jmlall_tl = ($nol[0] += $jumlah_tl);
                                                    $jmlall_st = ($nol[1] += $jumlah_st);
                                                    $jmlall_sp = ($nol[2] += $jumlah_sp);
                                                    $jmlall_tt = ($nol[3] += $jumlah_tt);
                                                    $total = ($nol[4] += $jumlah_plg_cctv);
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td colspan='3'><strong>Jumlah Data</strong></td>
                                            <td><?= isset($jmlall_tl) ? $jmlall_tl : NULL; ?></td>
                                            <td><?= isset($jmlall_st) ? $jmlall_st : NULL; ?></td>
                                            <td><?= isset($jmlall_sp) ? $jmlall_sp : NULL; ?></td>
                                            <td><?= isset($jmlall_tt) ? $jmlall_tt : NULL; ?></td>
                                            <td><strong><?= isset($total) ? $total : NULL;; ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom row colors end -->
    </section>
    <!-- // Minimal statistics section end -->
<?php } 
// <!-- Dashboard Group CCTV -->
elseif ($id_group == $arrgroup[7]) { ?>
<!-- stats with subtitle section start -->
<section id="stats-subtitle">
    <div class="row">
        <div class="col-12 mt-3 mb-1">
            <h4 class="text-uppercase">Data Transaction</h4>
            <p>Summary of all data transaction.</p>
        </div>
    </div>
    <!-- Pie charts section start -->
    <div class="row">
        <!-- Doughnut Chart -->
        <div class="col-md-12 col-sm-12">
            <div class="card">
            <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                            <a href="index.php?page=<?= encrypt('G076');?>" target=""><i class="icon-eye warning font-large-2 mr-2"></i></a>
                            </div>
                            <div class="media-body">
                            <h4>Daftar Pelanggaran CCTV</h4>
                            <span>All Data Pelanggaran CCTV</span>
                            </div>
                            <div class="align-self-center">
                            <?php
                            $q_plg_cctv = mysqli_query($conn, "SELECT COUNT(no_plg_cctv) AS jumlah_pel_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id'");
                            $d_plg_cctv = mysqli_fetch_assoc($q_plg_cctv);
                            ?>
                            <h1><?= $d_plg_cctv["jumlah_pel_cctv"]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- stats with subtitle section start -->
<section id="stats-subtitle">
    <div class="row">
        <div class="col-12 mt-3 mb-1">
            <h4 class="text-uppercase">Data Chart</h4>
            <p>Summary of all data chart.</p>
        </div>
    </div>
    <!-- Pie charts section start -->
    <div class="row">
        <!-- Simple Pie Chart -->
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Kategori Terekam Pelanggaran CCTV</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <canvas id="categ-chart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Simple Pie Chart -->
        <!-- Simple Pie Chart -->
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Status Follow Up Pelanggaran CCTV</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <canvas id="chart-sp" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Simple Pie Chart -->
    </div>
    <!-- Column Stacked Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4 class="card-title">Status Follow Up Pelanggaran CCTV Perbulan</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                <canvas id="chart-bar-fupplg" height="400"></canvas>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- Bar Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Pelanggaran CCTV Perbagian</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                    <canvas id="bar-chart-plg" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Minimal statistics section start -->
<section id="minimal-statistics">
    <div class="row">
        <div class="col-12 mt-3 mb-1">
            <h4 class="text-uppercase">Data Table</h4>
            <p>Summary of all data table.</p>
        </div>
    </div>
    <!-- Custom row colors start -->
    <div class="row" id="row-color">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Users Tercatat Pelanggaran CCTV Bulan <?= date('F Y', strtotime($monthrn)) ?> </h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bagian</th>
                                    <th>User</th>
                                    <th>Teguran Lisan</th>
                                    <th>Surat Teguran</th>
                                    <th>Surat Perigatan</th>
                                    <th>Tidak Terindikasi</th>
                                    <th>Jumlah Pelanggaran</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $nocctv = 1;
                                    $nol = [0, 0, 0, 0, 0];
                                    $sql_userp = "SELECT A.no_plg_cctv, A.office_plg_cctv, A.dept_plg_cctv, user_pelanggaran_cctv.username_plg_cctv, C.nik, C.full_name, D.divisi_name FROM pelanggaran_cctv AS A 
                                    INNER JOIN user_pelanggaran_cctv ON A.no_plg_cctv = user_pelanggaran_cctv.head_no_plg_cctv 
                                    LEFT JOIN users AS C ON LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) = C.nik 
                                    LEFT JOIN divisi AS D ON C.id_divisi = D.id_divisi 
                                    WHERE A.office_plg_cctv = '$office_id' AND A.dept_plg_cctv = '$dept_id' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') GROUP BY LEFT(user_pelanggaran_cctv.username_plg_cctv, 10) ORDER BY COUNT(LEFT(user_pelanggaran_cctv.username_plg_cctv, 10)) DESC";

                                    $query_userp = mysqli_query($conn, $sql_userp);
                                    if(mysqli_num_rows($query_userp) > 0 ) {
                                        while($data_userp = mysqli_fetch_assoc($query_userp)) { 
                                            
                                            $user_detail = substr($data_userp["username_plg_cctv"], 0, 10);

                                            $user_plg_cctv = $data_userp['username_plg_cctv'];
                                            $bagian_plg_cctv = isset($data_userp['divisi_name']) ? $data_userp['divisi_name'] : "-";
                                            $office_plg_cctv = $data_userp['office_plg_cctv'];
                                            $dept_plg_cctv = $data_userp['dept_plg_cctv'];

                                            $sql_1 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tl FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '1' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                            $snk1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_1));

                                            $sql_3 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_st FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '3' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                            $snk3 = mysqli_fetch_assoc(mysqli_query($conn, $sql_3));
                                            
                                            $sql_4 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_sp FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '4' AND LEFT(B.username_plg_cctv, 10) = '$user_detail'";
                                            $snk4 = mysqli_fetch_assoc(mysqli_query($conn, $sql_4));
                                            
                                            $sql_5 ="SELECT COUNT(A.fup_plg_cctv) AS jumlah_tt FROM pelanggaran_cctv AS A INNER JOIN user_pelanggaran_cctv AS B ON A.no_plg_cctv = B.head_no_plg_cctv WHERE A.office_plg_cctv = '$office_plg_cctv' AND A.dept_plg_cctv = '$dept_plg_cctv' AND LEFT(A.tgl_plg_cctv, 7) = '$monthrn' AND A.status_plg_cctv IN ('N', 'Y') AND A.fup_plg_cctv = '5' AND LEFT(B.username_plg_cctv, 10) = '$user_detail' ";
                                            $snk5 = mysqli_fetch_assoc(mysqli_query($conn, $sql_5));
                                        
                                            $jumlah_tl = $snk1['jumlah_tl'];
                                            $jumlah_st = $snk3['jumlah_st'];
                                            $jumlah_sp = $snk4['jumlah_sp'];
                                            $jumlah_tt = $snk5['jumlah_tt'];
                                            $jumlah_plg_cctv = $jumlah_tl + $jumlah_st + $jumlah_sp + $jumlah_tt;
                                            
                                            if ($jumlah_plg_cctv >= 3 && $jumlah_plg_cctv <= 5 ) {
                                                $color_stck = "class='bg-warning white'";
                                                $color_txt = "white";
                                            }
                                            elseif ($jumlah_plg_cctv >= 5 ) {
                                                $color_stck = "class='bg-danger white'";
                                                $color_txt = "white";
                                            }
                                            else {
                                                $color_stck = "";
                                            }
                                            $color_txt = "secondary";
                                            ?>
                                                <tr <?= $color_stck; ?>>
                                                    <td><?= $nocctv++; ?></td>
                                                    <td><?= $bagian_plg_cctv; ?></td>
                                                    <td><a title="Show Detail Data Pelanggaran User : <?= $user_plg_cctv; ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" class="text-<?= $color_txt; ?> detail_spc" name="detail_spc" id="<?= $office_plg_cctv.$dept_plg_cctv.$monthrn.$user_plg_cctv; ?>"><?= $user_plg_cctv; ?></a></td>
                                                    <td><?= $jumlah_tl; ?></td>
                                                    <td><?= $jumlah_st; ?></td>
                                                    <td><?= $jumlah_sp; ?></td>
                                                    <td><?= $jumlah_tt; ?></td>
                                                    <td><strong><?= $jumlah_plg_cctv; ?></strong></td>
                                                </tr>
                                            <?php
                                                $jmlall_tl = ($nol[0] += $jumlah_tl);
                                                $jmlall_st = ($nol[1] += $jumlah_st);
                                                $jmlall_sp = ($nol[2] += $jumlah_sp);
                                                $jmlall_tt = ($nol[3] += $jumlah_tt);
                                                $total = ($nol[4] += $jumlah_plg_cctv);
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td colspan='3'><strong>Jumlah Data</strong></td>
                                        <td><?= isset($jmlall_tl) ? $jmlall_tl : NULL; ?></td>
                                        <td><?= isset($jmlall_st) ? $jmlall_st : NULL; ?></td>
                                        <td><?= isset($jmlall_sp) ? $jmlall_sp : NULL; ?></td>
                                        <td><?= isset($jmlall_tt) ? $jmlall_tt : NULL; ?></td>
                                        <td><strong><?= isset($total) ? $total : NULL;; ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Custom row colors end -->
</section>
<!-- // Minimal statistics section end -->
<?php } 
// <!-- Dashboard Group Reporting -->
elseif ($id_group == $arrgroup[9]) { ?>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Transaction</h4>
                <p>Summary of all data transaction.</p>
            </div>
        </div>
        <!-- Pie charts section start -->
        <!-- Doughnut Chart -->
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G012');?>" target=""><i class="icon-basket-loaded icon font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                    <h4>Daftar Permohonan Pembelian</h4>
                                    <span>All Data Permohonan Pembelian</span>
                                </div>
                                <div class="align-self-center">
                                    <?php
                                        $q_listpp = mysqli_query($conn, "SELECT COUNT(noref) AS total_pp FROM pembelian WHERE id_office = '$office_id' AND id_department = '$dept_id'");
                                        $d_listpp = mysqli_fetch_assoc($q_listpp);
                                    ?>
                                    <h1><?= $d_listpp["total_pp"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="align-self-center">
                                <a href="index.php?page=<?= encrypt('G062');?>" target=""><i class="icon-fire danger font-large-2 mr-2"></i></a>
                                </div>
                                <div class="media-body">
                                    <h4>Daftar Monitoring Pemusnahan</h4>
                                    <span>All Data Monitoring Pemusnahan (P3AT)</span>
                                </div>
                                <div class="align-self-center">
                                    <?php
                                        $q_listp3at = mysqli_query($conn, "SELECT COUNT(status_p3at) AS total_p3at FROM p3at WHERE office_p3at = '$office_id' AND dept_p3at = '$dept_id' AND status_p3at != 'T03'");
                                        $d_listp3at = mysqli_fetch_assoc($q_listp3at);
                                    ?>
                                    <h1><?= $d_listp3at["total_p3at"]; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Simple Pie Chart -->
    </section>
    <!-- stats with subtitle section start -->
    <section id="stats-subtitle">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Data Table</h4>
                <p>Summary of all data table.</p>
            </div>
        </div>
        <!-- Custom row colors start -->
        <div class="row" id="row-color">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Barang Under Stock</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stock</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            $sql_sunder = "SELECT A.pluid, A.saldo_akhir, B.NamaBarang, C.NamaJenis, D.nama_satuan FROM masterstock AS A
                            INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
                            INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
                            INNER JOIN satuan AS D ON B.id_satuan = D.id_satuan
                            WHERE A.ms_id_office = '$office_id' AND A.ms_id_department = '$dept_id' AND A.saldo_akhir < 6 ORDER BY A.saldo_akhir ASC";
                            $query_sunder = mysqli_query($conn, $sql_sunder);
                            if(mysqli_num_rows($query_sunder) > 0 ) {
                            while($data_sunder = mysqli_fetch_assoc($query_sunder)){ 
                            
                                $barang = $data_sunder['pluid'];
                                $desc = $data_sunder['NamaBarang']." ".$data_sunder['NamaJenis'];
                                $satuan = $data_sunder['nama_satuan'];
                                $saldo = $data_sunder['saldo_akhir'];

                                if ($saldo >= 3 && $saldo < 5 ) {
                                    $color_stck = "class='bg-warning white'";
                                }
                                elseif ($saldo < 3 ) {
                                    $color_stck = "class='bg-danger white'";
                                }
                                else {
                                    $color_stck = "";
                                }

                            ?>
                            <tr <?= $color_stck; ?>>
                                <td><?= $no++; ?></td>
                                <td><?= $barang; ?></td>
                                <td><?= $desc; ?></td>
                                <td><?= $satuan; ?></td>
                                <td><?= $saldo; ?></td>
                            </tr>
                            <?php } 
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom row colors end -->
    </section>
<?php }
else { ?>
    <section id="interactive-charts">
        <div class="row">
            <div class="col-12 mt-1 mb-3">
                <h4>Home</h4>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card-body">
                                    <div class="card-header bg-transparent border-0">
                                        <h2 class="error-code text-center">IMS</h2>
                                        <h3 class="text-uppercase text-center">Welcome!</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Interactive charts section end -->
<?php } ?>

<!-- Modal Read -->
<div class="modal fade text-left" id="detailSanksiPelanggaranCCTV" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <form action="" method="post">
            <div class="modal-header bg-secondary white">
                <h4 class="modal-title white"
                    id="myModalLabel">Detail Data Approval User Terekam Pelanggaran CCTV</h4>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_readdatasnkplg">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- End Modal -->

<script>

$(document).ready(function(){
    $(document).on('click', '.detail_spc', function(){  
        var nomor_id = $(this).attr("id");  
        if(nomor_id != '') {  
            $.ajax({
                url:"action/datarequest.php",
                method:"POST",  
                data:{DETAILDATASANKSIPLG: nomor_id},  
                success:function(data){  
                    $('#modal_readdatasnkplg').html(data);
                    $('#detailSanksiPelanggaranCCTV').modal('show');
                }  
            });
        }
    });
});

// ------------------------------
$(window).on("load", function(){

    //Get the context of the Chart canvas element we want to select
    var ctx = $("#bar-chart-kondisi");

    // Chart Options
    var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each horizontal bar to be 2px wide and green
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'left'
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        legend: {
            position: 'top',
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }]
        },
        title: {
            display: false,
            text: 'Chart.js Horizontal Bar Chart'
        }
    };

    // Chart Data
    var chartData = {
        labels: [
        <?php
            $no = 1;
            $sql_lbl_baik = "SELECT A.pluid, B.NamaBarang, C.NamaJenis FROM barang_assets AS A
            INNER JOIN mastercategory AS B ON LEFT(A.pluid, 6) = B.IDBarang
            INNER JOIN masterjenis AS C ON RIGHT(A.pluid, 4) = C.IDJenis
            WHERE A.ba_id_office = '$office_id' AND A.ba_id_department = '$dept_id' GROUP BY A.pluid ORDER BY A.pluid ASC";
            $query_lbl_baik = mysqli_query($conn, $sql_lbl_baik);

            while($data_lbl_baik = mysqli_fetch_assoc($query_lbl_baik)) {
                $desc = $no++.". ".$data_lbl_baik["pluid"]." - ".$data_lbl_baik["NamaBarang"]." ".$data_lbl_baik["NamaJenis"]." ";
                echo "'".$desc."'".", ";
            }
        ?>
        ],
        datasets: [
            {
                label: "Baik",
                data: [
                <?php 
                    $sql_baik = "SELECT SUM(IF(kondisi = '01', 1, 0)) AS baik FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_baik = mysqli_query($conn, $sql_baik);
        
                    while($data_baik = mysqli_fetch_assoc($query_baik)) {
                        echo $data_baik["baik"].", ";
                    }
                ?>
                ],
                backgroundColor: "#28D094",
                hoverBackgroundColor: "rgba(22,211,154,.9)",
                borderColor: "transparent"
            },
            {
                label: "Cadangan",
                data: [
                <?php 
                    $sql_cad = "SELECT SUM(IF(kondisi = '02', 1, 0)) AS cad FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_cad = mysqli_query($conn, $sql_cad);
        
                    while($data_cad = mysqli_fetch_assoc($query_cad)) {
                        echo $data_cad["cad"].", ";
                    }
                ?>
                ],
                backgroundColor: "#00BFFF",
                hoverBackgroundColor: "rgb(43, 191, 254)",
                borderColor: "transparent"
            },
            {
                label: "Rusak",
                data: [
                <?php 
                    $sql_rusak = "SELECT SUM(IF(kondisi = '03', 1, 0)) AS rusak FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_rusak = mysqli_query($conn, $sql_rusak);
        
                    while($data_rusak = mysqli_fetch_assoc($query_rusak)) {
                        echo $data_rusak["rusak"].", ";
                    }
                ?>
                ],
                backgroundColor: "#DC143C",
                hoverBackgroundColor: "rgb(220, 20, 60)",
                borderColor: "transparent"
            },
            {
                label: "Service",
                data: [
                <?php 
                    $sql_service = "SELECT SUM(IF(kondisi = '04', 1, 0)) AS service FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_service = mysqli_query($conn, $sql_service);
        
                    while($data_service = mysqli_fetch_assoc($query_service)) {
                        echo $data_service["service"].", ";
                    }
                ?>
                ],
                backgroundColor: "#FF8C00",
                hoverBackgroundColor: "rgb(251, 140, 1)",
                borderColor: "transparent"
            },
            {
                label: "P3AT",
                data: [
                <?php 
                    $sql_p3at = "SELECT SUM(IF(kondisi = '05', 1, 0)) AS p3at FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_p3at = mysqli_query($conn, $sql_p3at);
        
                    while($data_p3at = mysqli_fetch_assoc($query_p3at)) {
                        echo $data_p3at["p3at"].", ";
                    }
                ?>
                ],
                backgroundColor: "#8B4513",
                hoverBackgroundColor: "rgb(139, 69, 19)",
                borderColor: "transparent"
            },
            {
                label: "Musnah",
                data: [
                <?php 
                    $sql_musnah = "SELECT SUM(IF(kondisi = '06', 1, 0)) AS musnah FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_musnah = mysqli_query($conn, $sql_musnah);
        
                    while($data_musnah= mysqli_fetch_assoc($query_musnah)) {
                        echo $data_musnah["musnah"].", ";
                    }
                ?>
                ],
                backgroundColor: "#000000",
                hoverBackgroundColor: "rgb(0, 0, 0)",
                borderColor: "transparent"
            },
            {
                label: "Hilang",
                data: [
                <?php 
                    $sql_hilang = "SELECT SUM(IF(kondisi = '07', 1, 0)) AS hilang FROM barang_assets WHERE ba_id_office = '$office_id' AND ba_id_department = '$dept_id' GROUP BY pluid ORDER BY pluid ASC";
                    $query_hilang = mysqli_query($conn, $sql_hilang);
        
                    while($data_hilang= mysqli_fetch_assoc($query_hilang)) {
                        echo $data_hilang["hilang"].", ";
                    }
                ?>
                ],
                backgroundColor: "#6A5ACD",
                hoverBackgroundColor: "rgb(106, 90, 205)",
                borderColor: "transparent"
            }
        ]
    };

    var config = {
        type: 'horizontalBar',

        // Chart Options
        options : chartOptions,

        data : chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);

});


// Column stacked chart
// ------------------------------
$(window).on("load", function(){

    // Get the context of the Chart canvas element we want to select
    var ctx = $("#chart-bar-fupplg");

    // Chart Options
    var chartOptions = {
        title:{
            display:false,
            text:"Status Follow Up Pelanggaran CCTV Perbulan"
        },
        tooltips: {
            mode: 'label'
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        scales: {
            xAxes: [{
                stacked: true,
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                stacked: true,
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }]
        }
    };

    <?php
        $yms = array();
        $mnowstr = date('Y-m');
        for($x = 2; $x >= 1; $x--) {
            $ym = date('F Y', strtotime($mnowstr . " -$x month"));
            $yms[] = '"'.$ym.'"';
        }
        
        $mpast = implode(", ", $yms);
        $mnow = '"'.date('F Y').'"';

        $ymv = array();
        $mnownum = date('Y-m');
        for($i = 2; $i >= 1; $i--) {
            $mi = date('Y-m', strtotime($mnownum . " -$i month"));
            $ymv[] = $mi;
        }
    ?>

    // Chart Data
    var chartData = {
        labels: [<?= $mpast; ?>, <?= $mnow; ?>],
        datasets: [{
            label: "Belum Follow Up",
            data: [
                <?php 
                    $sql_m1 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[0]' AND status_plg_cctv = 'S'";
                    $query_m1 = mysqli_query($conn, $sql_m1);
                    echo mysqli_num_rows($query_m1);
                ?>, 
                <?php 
                    $sql_m2 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[1]' AND status_plg_cctv = 'S'";
                    $query_m2 = mysqli_query($conn, $sql_m2);
                    echo mysqli_num_rows($query_m2);
                ?>, 
                <?php 
                    $sql_m3 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mnownum' AND status_plg_cctv = 'S'";
                    $query_m3 = mysqli_query($conn, $sql_m3);
                    echo mysqli_num_rows($query_m3);
                ?>
            ],
            backgroundColor: "#F98E76",
            hoverBackgroundColor: "rgba(249,142,118,.8)",
            borderColor: "transparent"
        }, {
            label: "Sudah Follow Up Belum Approve",
            data: [
                <?php 
                    $sql_m1 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[0]' AND status_plg_cctv = 'N'";
                    $query_m1 = mysqli_query($conn, $sql_m1);
                    echo mysqli_num_rows($query_m1);
                ?>, 
                <?php 
                    $sql_m2 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[1]' AND status_plg_cctv = 'N'";
                    $query_m2 = mysqli_query($conn, $sql_m2);
                    echo mysqli_num_rows($query_m2);
                ?>, 
                <?php 
                    $sql_m3 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mnownum' AND status_plg_cctv = 'N'";
                    $query_m3 = mysqli_query($conn, $sql_m3);
                    echo mysqli_num_rows($query_m3);
                ?>
            ],
            backgroundColor: "#28D094",
            hoverBackgroundColor: "rgba(22,211,154,.8)",
            borderColor: "transparent"
        },
        {
            label: "Sudah Follow Up dan Approve",
            data: [
                <?php 
                    $sql_m1 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[0]' AND status_plg_cctv = 'Y'";
                    $query_m1 = mysqli_query($conn, $sql_m1);
                    echo mysqli_num_rows($query_m1);
                ?>, 
                <?php 
                    $sql_m2 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$ymv[1]' AND status_plg_cctv = 'Y'";
                    $query_m2 = mysqli_query($conn, $sql_m2);
                    echo mysqli_num_rows($query_m2);
                ?>, 
                <?php 
                    $sql_m3 = "SELECT status_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mnownum' AND status_plg_cctv = 'Y'";
                    $query_m3 = mysqli_query($conn, $sql_m3);
                    echo mysqli_num_rows($query_m3);
                ?>
            ],
            backgroundColor: "#5175E0",
            hoverBackgroundColor: "rgba(81,117,224,.8)",
            borderColor: "transparent"
        }]
    };

    var config = {
        type: 'bar',

        // Chart Options
        options : chartOptions,

        data : chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);

});

// ------------------------------
$(window).on("load", function(){

    //Get the context of the Chart canvas element we want to select
    var ctx = $("#bar-chart-plg");

    // Chart Options
    var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each horizontal bar to be 2px wide and green
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'left'
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        legend: {
            position: 'top',
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }]
        },
        title: {
            display: false,
            text: 'Chart.js Horizontal Bar Chart'
        }
    };

    // Chart Data
    var chartData = {
        <?php $mounth_check = date("Y-m"); ?>
        labels: ["<?= date('F Y', strtotime($mounth_check)) ?> "],
        datasets: [
            {
                label: "Administrasi",
                data: [
                <?php 
                    $sql_adm = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[0]'";
                    $query_adm = mysqli_query($conn, $sql_adm);
                    echo mysqli_num_rows($query_adm);
                ?>
                ],
                backgroundColor: "#28D094",
                hoverBackgroundColor: "rgba(22,211,154,.9)",
                borderColor: "transparent"
            },
            {
                label: "Warehouse",
                data: [
                <?php 
                    $sql_wh = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[1]'";
                    $query_wh = mysqli_query($conn, $sql_wh);
                    echo mysqli_num_rows($query_wh);
                ?>
                ],
                backgroundColor: "#00BFFF",
                hoverBackgroundColor: "rgb(43, 191, 254)",
                borderColor: "transparent"
            },
            {
                label: "Receiving",
                data: [
                <?php 
                    $sql_rcv = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[2]'";
                    $query_rcv = mysqli_query($conn, $sql_rcv);
                    echo mysqli_num_rows($query_rcv);
                ?>
                ],
                backgroundColor: "#FFD700",
                hoverBackgroundColor: "rgb(253, 215, 3)",
                borderColor: "transparent"
            },
            {
                label: "Retur",
                data: [
                <?php 
                    $sql_rtr = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[3]'";
                    $query_rtr = mysqli_query($conn, $sql_rtr);
                    echo mysqli_num_rows($query_rtr);
                ?>
                ],
                backgroundColor: "#FF8C00",
                hoverBackgroundColor: "rgb(251, 140, 1)",
                borderColor: "transparent"
            },
            {
                label: "Issuing",
                data: [
                <?php 
                    $sql_iss = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[4]'";
                    $query_iss = mysqli_query($conn, $sql_iss);
                    echo mysqli_num_rows($query_iss);
                ?>
                ],
                backgroundColor: "#DC143C",
                hoverBackgroundColor: "rgb(220, 20, 60)",
                borderColor: "transparent"
            },
            {
                label: "Driver",
                data: [
                <?php 
                    $sql_drv = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[5]'";
                    $query_drv = mysqli_query($conn, $sql_drv);
                    echo mysqli_num_rows($query_drv);
                ?>
                ],
                backgroundColor: "#8B4513",
                hoverBackgroundColor: "rgb(139, 69, 19)",
                borderColor: "transparent"
            },
            {
                label: "Perishable",
                data: [
                <?php 
                    $sql_prs = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[6]'";
                    $query_prs = mysqli_query($conn, $sql_prs);
                    echo mysqli_num_rows($query_prs);
                ?>
                ],
                backgroundColor: "#6A5ACD",
                hoverBackgroundColor: "rgb(106, 90, 205)",
                borderColor: "transparent"
            },
            {
                label: "Delivery",
                data: [
                <?php 
                    $sql_dlv = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[7]'";
                    $query_dlv = mysqli_query($conn, $sql_dlv);
                    echo mysqli_num_rows($query_dlv);
                ?>
                ],
                backgroundColor: "#C0C0C0",
                hoverBackgroundColor: "rgb(192, 192, 192)",
                borderColor: "transparent"
            },
            {
                label: "Bakery",
                data: [
                <?php 
                    $sql_bkr = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[8]'";
                    $query_bkr = mysqli_query($conn, $sql_bkr);
                    echo mysqli_num_rows($query_bkr);
                ?>
                ],
                backgroundColor: "#DB7093",
                hoverBackgroundColor: "rgb(219, 112, 147)",
                borderColor: "transparent"
            },
            {
                label: "Security",
                data: [
                <?php 
                    $sql_sec = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[9]'";
                    $query_sec = mysqli_query($conn, $sql_sec);
                    echo mysqli_num_rows($query_sec);
                ?>
                ],
                backgroundColor: "#000000",
                hoverBackgroundColor: "rgb(0, 0, 0)",
                borderColor: "transparent"
            },
            {
                label: "Lain-Lain",
                data: [
                <?php 
                    $sql_lln = "SELECT div_plg_cctv FROM pelanggaran_cctv WHERE office_plg_cctv = '$office_id' AND dept_plg_cctv = '$dept_id' AND LEFT(tgl_plg_cctv, 7) = '$mounth_check' AND div_plg_cctv = '$arrdiv[10]'";
                    $query_lln = mysqli_query($conn, $sql_lln);
                    echo mysqli_num_rows($query_lln);
                ?>
                ],
                backgroundColor: "#2E8B57",
                hoverBackgroundColor: "rgb(46, 139, 87)",
                borderColor: "transparent"
            }
        ]
    };

    var config = {
        type: 'horizontalBar',

        // Chart Options
        options : chartOptions,

        data : chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);

});

$(window).on("load", function(){

    <?php
    $sql_1 = "SELECT status_fup.name_sts_fup, COUNT(pelanggaran_cctv.status_plg_cctv) AS spcctv FROM status_fup LEFT JOIN pelanggaran_cctv ON status_fup.kode_sts_fup = pelanggaran_cctv.status_plg_cctv WHERE status_fup.kode_sts_fup = 'S' AND pelanggaran_cctv.office_plg_cctv = '$office_id' AND pelanggaran_cctv.dept_plg_cctv = '$dept_id'";
    $query_1 = mysqli_query($conn, $sql_1) or die(mysqli_error($conn));
    
    $sql_2 = "SELECT status_fup.name_sts_fup, COUNT(pelanggaran_cctv.status_plg_cctv) AS spcctv FROM status_fup LEFT JOIN pelanggaran_cctv ON status_fup.kode_sts_fup = pelanggaran_cctv.status_plg_cctv WHERE status_fup.kode_sts_fup = 'N'  AND pelanggaran_cctv.office_plg_cctv = '$office_id' AND pelanggaran_cctv.dept_plg_cctv = '$dept_id'";
    $query_2 = mysqli_query($conn, $sql_2) or die(mysqli_error($conn));

    $sql_3 = "SELECT status_fup.name_sts_fup, COUNT(pelanggaran_cctv.status_plg_cctv) AS spcctv FROM status_fup LEFT JOIN pelanggaran_cctv ON status_fup.kode_sts_fup = pelanggaran_cctv.status_plg_cctv WHERE status_fup.kode_sts_fup = 'Y'  AND pelanggaran_cctv.office_plg_cctv = '$office_id' AND pelanggaran_cctv.dept_plg_cctv = '$dept_id'";
    $query_3 = mysqli_query($conn, $sql_3) or die(mysqli_error($conn));

    $array = array();
    while($data_1 = mysqli_fetch_assoc($query_1)) $array[] = $data_1;
    while($data_2 = mysqli_fetch_assoc($query_2)) $array[] = $data_2;
    while($data_3 = mysqli_fetch_assoc($query_3)) $array[] = $data_3;
    
    //mengubah data array menjadi format json
    $data_arr = json_encode($array);
    ?>

    //array untuk chart label dan chart data
    var data = <?= $data_arr; ?>;
    var isi_labels = [];
    var isi_data = [];
    var TotalJml = 0;
    
    //menghitung total jumlah item
    data.forEach(function (obj) {
        TotalJml += Number(obj["spcctv"]);
    });

    //push ke dalam array isi label dan isi data
    var spcctv = 0;
    $(data).each(function(i){         
        isi_labels.push(data[i].name_sts_fup); 
        //jml item dalam persentase
        isi_data.push(((data[i].spcctv/TotalJml) * 100).toFixed(2));
    });

    var ctx = document.getElementById('chart-sp').getContext('2d');
    var myPieChart = new Chart(ctx, {
        //chart akan ditampilkan sebagai pie chart
        type: 'pie',
        data: {
            //membuat label chart
            labels: isi_labels,
            datasets: [{
                label: 'Status Pelanggaran CCTV',
                //isi chart
                data: isi_data,
                //membuat warna pada chart
                backgroundColor: [
                    'rgb(250, 69, 1)',
                    'rgb(40, 178, 170)',
                    'rgb(70, 130, 180)'
                ],
                borderWidth: 0, //this will hide border
            }]
        },
        options: {
            //konfigurasi tooltip
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var labels = data.labels[tooltipItem.index];
                        var currentValue = dataset.data[tooltipItem.index];
                        return labels+": "+currentValue+" %";
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            responsiveAnimationDuration:500,
        }
    });
});


$(window).on("load", function(){

    <?php

    $sql_1 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 1 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";

    $sql_2 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 2 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";

    $sql_3 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 3 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";
    
    $sql_4 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 4 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";
    
    $sql_5 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 5 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";
    
    $sql_6 = "SELECT A.name_ctg_plg, COUNT(A.id_ctg_plg) AS jumlahctg FROM category_pelanggaran AS A 
    INNER JOIN jenis_pelanggaran AS B ON A.id_ctg_plg = B.id_head_ctg_plg 
    INNER JOIN pelanggaran_cctv AS C ON B.id_jns_plg = C.id_head_jns_plg 
    WHERE A.id_ctg_plg = 6 AND C.office_plg_cctv = '$office_id' AND C.dept_plg_cctv = '$dept_id'";

    $query_1 = mysqli_query($conn, $sql_1) or die(mysqli_error($conn));
    $query_2 = mysqli_query($conn, $sql_2) or die(mysqli_error($conn));
    $query_3 = mysqli_query($conn, $sql_3) or die(mysqli_error($conn));
    $query_4 = mysqli_query($conn, $sql_4) or die(mysqli_error($conn));
    $query_5 = mysqli_query($conn, $sql_5) or die(mysqli_error($conn));
    $query_6 = mysqli_query($conn, $sql_6) or die(mysqli_error($conn));

    $array = array();
    while($data_1 = mysqli_fetch_assoc($query_1)) $array[] = $data_1;
    while($data_2 = mysqli_fetch_assoc($query_2)) $array[] = $data_2;
    while($data_3 = mysqli_fetch_assoc($query_3)) $array[] = $data_3;
    while($data_4 = mysqli_fetch_assoc($query_4)) $array[] = $data_4;
    while($data_5 = mysqli_fetch_assoc($query_5)) $array[] = $data_5;
    while($data_6 = mysqli_fetch_assoc($query_6)) $array[] = $data_6;

    $data_arr = json_encode($array);

    ?>

    //array untuk chart label dan chart data
    var data = <?= $data_arr; ?>;
    var isi_labels = [];
    var isi_data = [];
    var TotalJml = 0;
    //menghitung total jumlah item
    data.forEach(function (obj) {
        TotalJml += Number(obj["jumlahctg"]);
    });

    //push ke dalam array isi label dan isi data
    var jumlahctg = 0;
    $(data).each(function(i){         
        isi_labels.push(data[i].name_ctg_plg); 
        //jml item dalam persentase
        isi_data.push(((data[i].jumlahctg/TotalJml) * 100).toFixed(2));
    });

    var ctx = document.getElementById('categ-chart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        //chart akan ditampilkan sebagai pie chart
        type: 'pie',
        data: {
            //membuat label chart
            labels: isi_labels,
            datasets: [{
                label: 'Summary Kategori Terekam Pelanggaran CCTV',
                //isi chart
                data: isi_data,
                //membuat warna pada chart
                backgroundColor: [
                    'rgb(250, 69, 1)',
                    'rgb(252, 165, 3)',
                    'rgb(40, 178, 170)',
                    'rgb(219, 112, 147)',
                    'rgb(176, 196, 222)',
                    'rgb(43, 191, 254)'
                ],
                borderWidth: 0, //this will hide border
            }]
        },
        options: {
            //konfigurasi tooltip
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var labels = data.labels[tooltipItem.index];
                        var currentValue = dataset.data[tooltipItem.index];
                        return labels+": "+currentValue+" %";
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            responsiveAnimationDuration:500,
        }
    });
});
</script>