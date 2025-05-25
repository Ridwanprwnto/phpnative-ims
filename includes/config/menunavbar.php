<?php if ($id_group == $admin) { ?>

<li class=" navigation-header">
  <span data-i18n="nav.category.layouts">Administrator</span><i class="la la-ellipsis-h ft-minus" data-toggle="tooltip"
  data-placement="right" data-original-title="Layouts"></i>
</li>

<li class=" nav-item"><a href="#"><i class="ft-grid"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Struktur</span></a>
  <ul class="menu-content">
    <li><a class="menu-item" href="index.php?page=<?= encrypt('company');?>" data-i18n="nav.menu_levels.second_level_child.main">Company</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('office');?>" data-i18n="nav.menu_levels.second_level_child.main">Office</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('department');?>" data-i18n="nav.menu_levels.second_level_child.main">Department</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('divisi');?>" data-i18n="nav.menu_levels.second_level_child.main">Divisi</a>
    </li>
  </ul>
</li>
<li class=" nav-item"><a href="#"><i class="ft-users"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Role Access</span></a>
  <ul class="menu-content">
    <li><a class="menu-item" href="index.php?page=<?= encrypt('group');?>" data-i18n="nav.menu_levels.second_level_child.main">Group</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('level');?>" data-i18n="nav.menu_levels.second_level_child.main">Level</a>
    </li>
  </ul>
</li>
<li class=" nav-item"><a href="#"><i class="ft-layout"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Menu Pages</span></a>
  <ul class="menu-content">
    <li><a class="menu-item" href="index.php?page=<?= encrypt('parentmenu');?>" data-i18n="nav.menu_levels.second_level_child.main">Parent Menu</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('childmenu');?>" data-i18n="nav.menu_levels.second_level_child.main">Child Menu</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('grandchildmenu');?>" data-i18n="nav.menu_levels.second_level_child.main">Grand Child Menu</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('extendmenu');?>" data-i18n="nav.menu_levels.second_level_child.main">Extend Menu</a>
    </li>
  </ul>
</li>
<li class=" nav-item"><a href="#"><i class="ft-layers"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Menu Groups</span></a>
  <ul class="menu-content">
    <li><a class="menu-item" href="index.php?page=<?= encrypt('accesspm');?>" data-i18n="nav.menu_levels.second_level_child.main">Access Parent Menu</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('accesscm');?>" data-i18n="nav.menu_levels.second_level_child.main">Access Child Menu</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('accessgm');?>" data-i18n="nav.menu_levels.second_level_child.main">Access Grand Child Menu</a>
    </li>
  </ul>
</li>
<li class=" nav-item"><a href="#"><i class="ft-settings"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Setting</span></a>
  <ul class="menu-content">
    <li><a class="menu-item" href="index.php?page=<?= encrypt('emailserver');?>" data-i18n="nav.menu_levels.second_level_child.main">Mail Server</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('category');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel Category</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('satuan');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel Satuan</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('kondisi');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel Kondisi</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('spp');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel SPP</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('statusp3at');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel Status P3AT</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('crud');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Tabel CRUD</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('mastertelebot');?>" data-i18n="nav.menu_levels.second_level_child.main">Master Telegram BOT</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('apiservices');?>" data-i18n="nav.menu_levels.second_level_child.main">Master API Services</a>
    </li>
    <li><a class="menu-item" href="index.php?page=<?= encrypt('simulasi');?>" data-i18n="nav.menu_levels.second_level_child.main">Simulasi Fitur</a>
    </li>
  </ul>
</li>

<?php } ?>

<li class=" navigation-header">
  <span data-i18n="nav.category.general">General</span><i class="la la-ellipsis-h ft-minus" data-toggle="tooltip"
  data-placement="right" data-original-title="General"></i>
</li>

<li class=" nav-item"><a href="index.php" title="Dashboard"><i class="icon-home"></i><span class="menu-title" data-i18n="nav.menu_levels.main">Dashboard</span></a>
</li>

<!-- main menu content group -->
<?php
  $join_mm = "SELECT parentmenu.*, akses_parentmenu.* FROM akses_parentmenu 
  INNER JOIN parentmenu ON akses_parentmenu.id_parentmenu = parentmenu.id_parentmenu
  WHERE akses_parentmenu.id_group = '$id_group' AND akses_parentmenu.parentmenu_status = 'Y' ORDER BY akses_parentmenu.id_parentmenu ASC";
  $query_mm = mysqli_query($conn, $join_mm);
  while($data_mm = mysqli_fetch_assoc($query_mm)) {
  $mmid = $data_mm['id_parentmenu'];
?>
<li class=" nav-item">
  <a href="index.php?page=<?= encrypt($mmid); ?>" title="<?= $data_mm['parentmenu_name']; ?>"><i class="<?= $data_mm['parentmenu_icon']; ?>"></i><span class="menu-title" data-i18n="nav.horz_nav.main"><?= $data_mm['parentmenu_name']; ?></span></a>
  <?php
    $join_mn = "SELECT childmenu.*, akses_childmenu.* FROM akses_childmenu 
    INNER JOIN childmenu ON akses_childmenu.id_childmenu = childmenu.id_childmenu
    WHERE akses_childmenu.id_group = '$id_group' AND akses_childmenu.id_parentmenu = '$mmid' AND akses_childmenu.childmenu_status = 'Y' ORDER BY akses_childmenu.id_childmenu ASC";
    $query_mn = mysqli_query($conn, $join_mn);
    if(mysqli_num_rows($query_mn) > 0) {
  ?>
  <ul class="menu-content">
    <?php
      while($data_mn = mysqli_fetch_assoc($query_mn)) {
      $mnid = $data_mn['id_childmenu'];
    ?>
    <li>
      <a class="menu-item" href="index.php?page=<?= encrypt($mnid); ?>" title="<?= $data_mn['childmenu_name']; ?>" data-i18n="nav.horz_nav.horizontal_navigation_types.main"><?= str_replace("M", "", $data_mn['id_childmenu']).". ".$data_mn['childmenu_name']; ?></a>
      <?php
        $join_mn = "SELECT grandchildmenu.*, akses_grandchildmenu.* FROM akses_grandchildmenu 
        INNER JOIN grandchildmenu ON akses_grandchildmenu.id_grandchildmenu = grandchildmenu.id_grandchildmenu
        WHERE akses_grandchildmenu.id_group = '$id_group' AND akses_grandchildmenu.id_childmenu = '$mnid' AND akses_grandchildmenu.grandchildmenu_status = 'Y' ORDER BY grandchildmenu.grandchildmenu_name ASC";
        $query_sm = mysqli_query($conn, $join_mn);
        if(mysqli_num_rows($query_sm) > 0) {
      ?>
      <ul class="menu-content">
        <?php
          while($data_sm = mysqli_fetch_assoc($query_sm)) {
        ?>
          <li>
            <a class="menu-item" href="index.php?page=<?= encrypt($data_sm['id_grandchildmenu']); ?>" title="<?= $data_sm['grandchildmenu_name']; ?>" data-i18n="nav.horz_nav.horizontal_navigation_types.horizontal_left_icon_navigation"><?= str_replace("M", "", $data_sm['id_grandchildmenu']).". ".$data_sm['grandchildmenu_name']; ?></a>
          </li>
        <?php
          }
        ?>
      </ul>
      <?php
        }
      ?>
    </li>
    <?php
      }
    ?>
  </ul>
  <?php
    }
  ?>
</li>
<?php
  }
?>