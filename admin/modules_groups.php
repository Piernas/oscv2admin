<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/template_top.php');
?>
  <div class="row">
    <div class="col-sm-12"><h1 class="pageHeading"><i class="fa fa-cogs"></i> <?= HEADING_TITLE ?> </h1></div>
  </div>
  
  <style>
.row.display-flex {
  display: flex;
  flex-wrap: wrap;
}
.row.display-flex > [class*='col-'] {
  display: flex;
  flex-direction: column;
}
</style>
  <div class="row display-flex">
<?php
  $modules_groups = $cfgModules->getAll();
  $group_description =null;
  $group_icon ="";
  foreach ($modules_groups as $modules_group) {
  if (isset ($modules_groups['icon'])) {  
    $group_icon ='<i class="fa fa-' . $modules_groups['icon'] . ' fa-lg"></i> ';
  };
//  if (!is_null ($group['description'])) $group_description = '<p>' . $group['description'] . '</p>';

?>
  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="alert alert-info text-center full-height">
      <a href="<?= tep_href_link('modules.php', 'set=' .$modules_group['code']) ?>" class="alert-link"><?= $group_icon . $modules_group['title'] ?></a><?= $group_description ?>
    </div>
  </div>
<?php
}
?>
</div>
<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
