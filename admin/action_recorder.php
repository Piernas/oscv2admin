<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir(DIR_FS_CATALOG_MODULES . 'action_recorder/')) {
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_CATALOG_MODULES . 'action_recorder/' . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];

    if (file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/action_recorder/' . $file)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/action_recorder/' . $file);
    }

    include(DIR_FS_CATALOG_MODULES . 'action_recorder/' . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      ${$class} = new $class;
    }
  }

  $modules_array = array();
  $modules_list_array = array(array('id' => '', 'text' => TEXT_ALL_MODULES));

  $modules_query = tep_db_query("select distinct module from " . TABLE_ACTION_RECORDER . " order by module");
  while ($modules = tep_db_fetch_array($modules_query)) {
    $modules_array[] = $modules['module'];

    $modules_list_array[] = array('id' => $modules['module'],
                                  'text' => (is_object(${$modules['module']}) ? ${$modules['module']}->title : $modules['module']));
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'expire':
        $expired_entries = 0;

        if (isset($_GET['module']) && in_array($_GET['module'], $modules_array)) {
          if (is_object(${$_GET['module']})) {
            $expired_entries += ${$_GET['module']}->expireEntries();
          } else {
            $delete_query = tep_db_query("delete from " . TABLE_ACTION_RECORDER . " where module = '" . tep_db_input($_GET['module']) . "'");
            $expired_entries += tep_db_affected_rows();
          }
        } else {
          foreach ($modules_array as $module) {
            if (is_object(${$module})) {
              $expired_entries += ${$module}->expireEntries();
            }
          }
        }

        $messageStack->add_session(sprintf(SUCCESS_EXPIRED_ENTRIES, $expired_entries), 'success');

        tep_redirect(tep_href_link('action_recorder.php'));

        break;
    }
  }

  require('includes/template_top.php');
?>
  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto p-2 pageHeading"><i class="fas fa-microphone fa-lg"></i> <?= HEADING_TITLE ?></div>
        <div class="p-2">
          <?= tep_draw_form('filter', 'action_recorder.php', '', 'get', 'class="form-inline"')?>

            <div class="form-group form-group-sm pr-2">
<?php
  if (isset($_GET['search']) && !empty($_GET['search']))
      echo '              <div class="input-group-prepend"><button class="btn btn-sm btn-info" id="clear"><i class="fa fa-times"></i></button></div>'; 
?>
              <?= tep_draw_input_field('search', null, 'size="20" placeholder="' . HEADING_TITLE_SEARCH . '"') ?>
              <div class="input-group-append"><button class="btn btn-sm btn-info" type="submit"><i class="fas fa-search"></i></button></div>
            </div>
            <div class="form-group form-group-sm pr-2"><?= tep_draw_pull_down_menu('module', $modules_list_array, null, 'onchange="this.form.submit();"')?></div>
              <?php echo tep_draw_button(IMAGE_DELETE, 'fa fa-trash', tep_href_link('action_recorder.php', 'action=expire' . (isset($_GET['module']) && in_array($_GET['module'], $modules_array) ? '&module=' . $_GET['module'] : ''))); ?>
              <?=  tep_hide_session_id()?>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body" id="page-content">

    <table class="table table-sm table-striped table-hover">
      <thead>
      <tr class="table-info">
        <th width="20">&nbsp;</th>
        <th><?php echo TABLE_HEADING_MODULE; ?></th>
        <th><?php echo TABLE_HEADING_CUSTOMER; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_IDENTIFIER; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_DATE_ADDED; ?></th>
      </tr>
      </thead>
      <tbody>
<?php
  $filter = array();

  if (isset($_GET['module']) && in_array($_GET['module'], $modules_array)) {
    $filter[] = " module = '" . tep_db_input($_GET['module']) . "' ";
  }

  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filter[] = " identifier like '%" . tep_db_input($_GET['search']) . "%' ";
  }
  $actions_query_raw = "select * from " . TABLE_ACTION_RECORDER . (!empty($filter) ? " where " . implode(" and ", $filter) : "") . " order by date_added desc";
  $actions_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $actions_query_raw, $actions_query_numrows);
  $actions_query = tep_db_query($actions_query_raw);
  
  while ($actions = tep_db_fetch_array($actions_query)) {
    $module = $actions['module'];

    $module_title = $actions['module'];
    if (is_object(${$module})) {
      $module_title = ${$module}->title;
    }

    if ((!isset($_GET['aID']) || (isset($_GET['aID']) && ($_GET['aID'] == $actions['id']))) && !isset($aInfo)) {
      $actions_extra_query = tep_db_query("select identifier from " . TABLE_ACTION_RECORDER . " where id = '" . (int)$actions['id'] . "'");
      $actions_extra = tep_db_fetch_array($actions_extra_query);

      $aInfo_array = array_merge($actions, $actions_extra, array('module' => $module_title));
      $aInfo = new objectInfo($aInfo_array);
    }
?>
        <tr>
          <td class="text-center"><i class="fa fa-<?= (($actions['success'] == '1') ? 'check text-success' : 'times text-danger'); ?> fa-lg"></i></td>
            <td><?php echo $module_title; ?></td>
            <td><?php echo tep_output_string_protected($actions['user_name']) . ' [' . (int)$actions['user_id'] . ']'; ?></td>
            <td class="text-right"><?php echo tep_output_string_protected($actions['identifier'])  ?></td>
            <td class="text-right"><?php echo tep_datetime_short($actions['date_added']); ?></td>
          </tr>
<?php
  }
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="smallText" valign="top"><?php echo $actions_split->display_count($actions_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ENTRIES); ?></td>
          <td colspan="2" class="smallText" align="right"><?php echo $actions_split->display_links($actions_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], 
          (isset($_GET['module']) && in_array($_GET['module'], $modules_array) && is_object(${$_GET['module']}) ? 'module=' . $_GET['module'] : null) . (isset($_GET['search']) && !empty($_GET['search']) ? '&search=' . $_GET['search'] : null)); ?></td>
        </tr>
      </tfoot>
    </table>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
