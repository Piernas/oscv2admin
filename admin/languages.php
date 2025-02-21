<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $code = (isset($_GET['code']) ? $_GET['code'] : '');

  require ('includes/classes/languages_setup.php');


  require('includes/template_top.php');
  require ('includes/classes/modules_pages.php');

  $languages_modules_installed = new modules_pages ('languages_installed');
  $oscTemplate->addBlock('<script>' . $languages_modules_installed->get_javascript() . '</script>', 'admin_footer_scripts');

?>
  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto p-2 pageHeading"><i class="fas fa-language fa-lg"></i> <?= HEADING_TITLE ?></div>

        <div class="py-2">
          <a href="<?= tep_href_link('modules_pages.php?desired_groups=' . urlencode (json_encode(array(array('languages'), array('languages_installed', 'languages_uninstalled'))))) ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a>

        </div>
      </div>
    </div>
    <div class="card-body" id="page-content">
      <table class="table table-sm" id="installed-languages-table">
        <thead>
        <tr class="table-info">
          <?= $languages_modules_installed->get_table_header(); ?>
          <th class="actions"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>

        </tr>
        </thead>
        <tbody>
<?php
  $languages_query_raw = "select languages_id, code from " . TABLE_LANGUAGES . " order by sort_order";
  $languages_query = tep_db_query($languages_query_raw);


  while ($languages = tep_db_fetch_array($languages_query)) {
//    echo $languages['code'];
      $languages_setup = new languages_setup ($languages['code']);

      if (isset($code) && ($languages['code'] == $code) ) {

  ?>
        <tr class="clickable table-success">
<?php
      } else {
  ?>
        <tr class="clickable">
<?php
      }
?>
            <?= $languages_modules_installed->get_table_row($languages['code']); ?>
          <td class="actions">
        <?= $languages_modules_installed->get_action_buttons($languages['code']); ?>
          </td>
        </tr>
<?php
  }
?>
        </tbody>
      </table>
<?php
  $languages_modules_uninstalled = new modules_pages ('languages_uninstalled');

  $uninstalled_array = $languages_setup->uninstalled;
  if (count((array)$uninstalled_array) > 0){
?>
      <table class="table table-sm">
        <thead>
        <tr class="table-info">
          <?= $languages_modules_uninstalled->get_table_header(); ?>
          <th class="actions"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<?php
    foreach ($uninstalled_array as $key => $value) {
?>
        <tr class="clickable">
          <?= $languages_modules_uninstalled->get_table_row($key); ?>
          <td class="actions">
            <?= $languages_modules_uninstalled->get_action_buttons($key); ?>
          </td>
        </tr>

      
      
<?php
    }
?>
        </tbody>
      </table>
    </div>
  </div>
<?php
  }
  require ("includes/classes/modal.php");
  
  $modal = new modal();
  $modal->button_delete = true;
  $modal->button_cancel = true;
  $modal->output();

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
