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
  
  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="mr-auto pageHeading"><i class="fa fa-cogs"></i> <?= HEADING_TITLE ?> </div>
    </div>
    <div class="card-body" id="page-content">
      <div class="row">

<?php
  $modules_groups = $cfgModules->getAll();

  foreach ($modules_groups as $modules_group) {

?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 pb-3">
          <div class="card card-modules">
            <a href="<?= tep_href_link('modules.php', 'set=' .$modules_group['code']) ?>" class="alert-link">
            <div class="card-body text-center"><?= $modules_group['title'] ?></a></div>
          </div>    
        </div>
<?php
}
?>
      </div>
    </div>
  </div>
<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
