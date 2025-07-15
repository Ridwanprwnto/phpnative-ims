<?php

    $page_id = $_GET['page'];
    $id = $_SESSION['user_nik'];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    $sname = isset($_POST["searchmenu"]) ? $_POST["searchmenu"] : NULL;
    $gname = isset($_POST["groupmenu"]) ? $_POST["groupmenu"] : NULL;

    $menu = mysqli_real_escape_string($conn, $sname);
    $grp = mysqli_real_escape_string($conn, $gname);

    $sql_menu_search = "SELECT grandchildmenu.grandchildmenu_name, childmenu.childmenu_name, parentmenu.parentmenu_name, akses_grandchildmenu.* FROM grandchildmenu
    INNER JOIN childmenu ON grandchildmenu.id_childmenu = childmenu.id_childmenu
    INNER JOIN parentmenu ON childmenu.id_parentmenu = parentmenu.id_parentmenu
    INNER JOIN akses_grandchildmenu ON grandchildmenu.id_grandchildmenu = akses_grandchildmenu.id_grandchildmenu
    WHERE akses_grandchildmenu.id_group = '$grp' AND akses_grandchildmenu.grandchildmenu_status = 'Y' AND grandchildmenu.grandchildmenu_name LIKE '%$menu%' ORDER BY grandchildmenu.grandchildmenu_name ASC";

    $query_menu_search = mysqli_query($conn, $sql_menu_search);

?>

<!-- Basic form layout section start -->
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-basic">Listing Menu</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <div class="card-text">
                        <?php
                        $no = 1;
                        if(mysqli_num_rows($query_menu_search) > 0 ) {
                        ?>
                        <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Menu Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($data_menu_search = mysqli_fetch_assoc($query_menu_search)){
                                    ?>
                                    <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data_menu_search["id_grandchildmenu"]; ?></td>
                                    <td>
                                        <?= $data_menu_search["parentmenu_name"]." > ".$data_menu_search["childmenu_name"]." > "?><strong><?= $data_menu_search["grandchildmenu_name"]; ?></strong>
                                    </td>
                                    <td>
                                    <a title="<?= $data_menu_search["grandchildmenu_name"]; ?>" href="index.php?page=<?= encrypt($data_menu_search["id_grandchildmenu"]); ?>" class="btn btn-float btn-info"><i class="ft-search"></i>
                                        <span>Open</span>
                                    </a>
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
                            include ("includes/templates/error-404.php");
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic form layout section end -->