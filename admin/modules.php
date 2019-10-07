<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
//  define ('OSCOM_DEVELOP_SHOW_CONSTANTS', true);// TODO: move to configuration
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if(!$action)  {
    require('includes/template_top.php');// moved to avoid problems with template modules
  }

  $set = (isset($_GET['set']) ? $_GET['set'] : '');
  $current_module = (isset($_GET['module']) ? $_GET['module'] : '');

  $modules = $cfgModules->getAll();

  if (empty($set) || !$cfgModules->exists($set)) {
    $set = $modules[0]['code'];
  }

  $module_type = $cfgModules->get($set, 'code');
  $module_directory = $cfgModules->get($set, 'directory');
  $module_language_directory = $cfgModules->get($set, 'language_directory');
  $module_key = $cfgModules->get($set, 'key');;
//  define('HEADING_TITLE', $cfgModules->get($set, 'title'));
  $template_integration = $cfgModules->get($set, 'template_integration');


  $modules_installed = (defined($module_key) ? explode(';', constant($module_key)) : array());

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $class = substr($file, 0, strrpos($file, '.'));
            if (!class_exists($class)) {
              if ( file_exists($module_language_directory . $language . '/modules/' . $module_type . '/' . $file) ) {
                include($module_language_directory . $language . '/modules/' . $module_type . '/' . $file);
              }
              include_once($module_directory . $file);
            }

            if (class_exists($class)) {
              $module = new $class();

              if (in_array( $class . ".php", $modules_installed)) {
                $column_constant =($set == 'boxes') ? constant (module_get_common_prefix ($module->keys()) . 'CONTENT_PLACEMENT'): "";

                $modules['installed'][] = array('code' => $class,
                                                'title' => $module->title,
                                                'group' => $module_directory,
                                                'description' =>$module->description,
                                                'sort_order' => (int)$module->sort_order,
                                                'column' => $column_constant,
                                                'constants_prefix' => module_get_common_prefix ($module->keys())
                                                );

              } else {
                $modules['new'][] = array('code' => $class,
                                              'title' => $module->title,
                                              'group' => $module_directory,
                                              'description' =>$module->description,);
              }
              unset ($module);
            }
          }
      }
    }
    $dir->close();

  }

  if(array_key_exists('installed', $modules)){
    usort($modules['installed'], 'sortbycol');
  }


  if ($action == 'edit' || $action =='details') {

      $class = basename($current_module);
      $module = new $class();
      $module_keys = $module->keys();

        $module_info = array('code' => $module->code,
                             'title' => $module->title,
                             'description' => $module->description,
                             'status' => $module->check(),
                             'signature' => (isset($module->signature) ? $module->signature : null),
                             'api_version' => (isset($module->api_version) ? $module->api_version : null));

     // remove column, enabled y sort_order

      $module_constants = module_get_common_prefix ($module->keys());

      foreach ($module_keys as $key=>$value) {
        if ($value == ($module_constants . "SORT_ORDER")||($value == $module_constants . "CONTENT_PLACEMENT")|| ($value == $module_constants . "STATUS") )
          unset ($module_keys [$key]);
        }
      $module_keys = array_values($module_keys);

      $keys_extra = array();

        for ($j=0, $k=sizeof($module_keys); $j<$k; $j++) {
          $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'");
          $key_value = tep_db_fetch_array($key_value_query);

          $keys_extra[$module_keys[$j]]['title'] = $key_value['configuration_title'];
          $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
          $keys_extra[$module_keys[$j]]['description'] = $key_value['configuration_description'];
          $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
          $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
        }

    $module_info['keys'] = $keys_extra;

    $mInfo = new objectInfo($module_info);
  }

  // ACTIONS
  if (tep_not_null($action)) {
    switch ($action) {
      case 'details':
        echo '<span id="title"><strong><i class="fas fa-info-circle"></i> ' . $mInfo->title  . '</strong></span>';
        echo '<span id="content"><div class="alert alert-info">' .  $mInfo->description . '</div>';

        if (isset($mInfo->signature) && (list($scode, $smodule, $sversion, $soscversion) = explode('|', $mInfo->signature))) {
          echo '<i class="fas fa-info-circle fa-lg text-info"></i>&nbsp;<strong>' . TEXT_INFO_VERSION . '</strong> ' . $sversion . ' (<a href="http://sig.oscommerce.com/' . $mInfo->signature . '" target="_blank">' . TEXT_INFO_ONLINE_STATUS . '</a>)<br>';
        }

        if (isset($mInfo->api_version)) {
          echo '<i class="fas fa-info-circle fa-lg text-info"></i>&nbsp;<strong>' . TEXT_INFO_API_VERSION . '</strong> ' . $mInfo->api_version . '<br>';
        }

        echo '</span>';

        exit();
        break;

      case 'edit':
        $keys = '';
        reset($mInfo->keys);

      foreach ($mInfo->keys as $key => $value) {
          $keys .= '<div class="alert alert-success alert-sm"><strong>' . $value['title'] . '</strong><br>' . $value['description'];

          if ($value['set_function']) {
            eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
          } else {
            $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
          }
          $keys .='</div>';
        }

        echo '<span id="title"><strong><i class="fas fa-cog"></i> ' . $mInfo->title . '</strong></span>';
        echo '<span id="content">' .  $keys . '</span>';

        exit();

      break;

      case 'swap_column':
        $class = basename($current_module);
        $module = new $class();

        $column_constant = module_get_common_prefix ($module->keys()) . 'CONTENT_PLACEMENT';
        $column = constant ($column_constant);
        if ($column == "Left Column") {
          $column = "Right Column";
        } else {
          $column = "Left Column";
        }
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $column . "' where configuration_key = '" . $column_constant . "'");
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set .  '&module=' . $class));

        break;

      case 'reorder':
        $class = basename($current_module);
        $position = $_GET['position'];

      // first we update the position of this module and save its old position:
        foreach ( $modules['installed'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();
            $old_position = $m['sort_order'];
            $position_constant = module_get_common_prefix ($module->keys()) . 'SORT_ORDER';
            $common_group = $m['group'];
            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $position . "' where configuration_key = '" . $position_constant . "'");
            $m['sort_order'] = $position;
            break;
          }
        }


        foreach ( $modules['installed'] as $m ) {
          if ( $m['sort_order'] == $position && $m['group'] == $common_group && $m['code'] != $class ) {
            $position = $_GET['position'];
            $module = new $m['code']();
            $position_constant = module_get_common_prefix ($module->keys()) . 'SORT_ORDER';
            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $old_position . "' where configuration_key = '" . $position_constant . "'");
            $m['sort_order'] = $old_position;
            break;
          }
        }
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set .  '&module=' . $class));
        break;
      case 'save':
        foreach ($_POST['configuration'] as $key => $value) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
        }
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $current_module));
        break;
      case 'install':
      case 'remove':
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        $class = basename($current_module);
        if (file_exists($module_directory . $class . $file_extension)) {
          include_once($module_directory . $class . $file_extension);
          $module = new $class;
          if ($action == 'install') {
            if ($module->check() > 0) { // remove module if already installed
              $module->remove();
            }

            $module->install();

            $modules_installed = explode(';', constant($module_key));

            if (!in_array($class . $file_extension, $modules_installed)) {
              $modules_installed[] = $class . $file_extension;
            }

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = '" . $module_key . "'");
            tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $class));
          } elseif ($action == 'remove') {
            $module->remove();

            $modules_installed = explode(';', constant($module_key));

            if (in_array($class . $file_extension, $modules_installed)) {
              unset($modules_installed[array_search($class . $file_extension, $modules_installed)]);
            }

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = '" . $module_key . "'");
            tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set));
          }
        }
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $class));
        break;

      case 'toggle':
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        $class = basename($current_module);
        if (file_exists($module_directory . $class . $file_extension)) {
          include_once($module_directory . $class . $file_extension);
          $module = new $class;
          $status_constant = module_get_common_prefix ($module->keys()) . 'STATUS';
          $toggle = constant($status_constant) =='True' ? 'False': 'True' ;
          $query = tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $toggle  . "' where configuration_key = '" . $status_constant . "'");
          tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $class));
        }
        break;
    }
  }
// ACTIONS END

  $modules_groups = $cfgModules->getAll();
  foreach ($modules_groups as $modules_group) {
    $groupss[] = array ('id'=>$modules_group['code'], 'text' =>$modules_group['title']);
  }
?>
  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto p-2 pageHeading"><i class="fas fa-cogs"></i> <?= HEADING_TITLE . $cfgModules->get($set, 'title')?></div>
        <div class="p-2 pageHeading">
          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text bg-info text-white"><i class="fas fa-forward fa-lg"></i></span>
              <?= tep_draw_pull_down_menu('module_group_selection', $groupss, $set, $parameters = '')?>
            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="card-body" id="page-content">
<?php

    if(array_key_exists('installed', $modules)){
?>
    <table class="table table-striped table-sm table-hover">
      <thead>
      <tr class="table-info">
<?php
  if ($set == 'shipping') {
?>
        <th class="text-center">*Icon*</th>
<?php
  }
/*
  if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) {
?>
        <th class="d-none d-sm-table-cell"><?= TABLE_HEADING_CLASS ?></th>
<?php
  }
*/
?>
        <th><?= TABLE_HEADING_MODULES; ?></th>
<?php
  if ($set == 'boxes') {
?>
        <th class="text-center"><?= TABLE_HEADING_COLUMN ?></th>
<?php
  }
  if ($set == 'shipping') {
?>
        <th class="text-center">*Zona*</th>
<?php
  }

?>
        <th class="text-center"><?= TABLE_HEADING_SORT_ORDER; ?></th>
        <th class="text-center"><?= TABLE_HEADING_ENABLE; ?></th>
        <th class="actions"><?= TABLE_HEADING_ACTION; ?></th>
      </tr>
      </thead>
      <tbody>
<?php
  $installed_modules = array();
  $group_installed_position ="";

    // Modules installed
    for ($i=0, $n=sizeof($modules['installed']); $i<$n; $i++) {
      $file = $modules['installed'][$i]['code'] . '.php';
      if (file_exists ($module_language_directory . $language . '/modules/' . $module_type . '/' . $file)) {
        include_once ($module_language_directory . $language . '/modules/' . $module_type . '/' . $file);
      }
    include_once($module_directory . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        if (($module->sort_order > 0) && !isset($installed_modules[$module->sort_order])) {
          $installed_modules[$module->sort_order] = $file;
        } else {
          $installed_modules[] = $file;
        }
      }
?>
        <tr class="clickable">
<?php
      if ($set =='shipping') {
        //       <td><?=  $module->icon ? '<img src="' .tep_catalog_href_link ($module->icon) . '" height="30" width="60">':"";
?>
          <td><?=  $module->icon ? tep_image(tep_catalog_href_link ($module->icon)) :""; ?></td>
<?php
      }
/*
      if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) {
?>
          <td class="d-none d-sm-table-cell"><small><?= $module->code; ?></small></td>
<?php
      }
*/
?>
          <td><?= $module->title ?></td>
<?php
    // columna
      if ($set == 'boxes') {
        $column ="";
        if ($modules['installed'][$i]['column'] == "Left Column") {
          $column = " fa-rotate-180";
        }
?>
          <td class="text-center"><a href="<?= tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $module->code . '&action=swap_column') ?>"><i class="fas fa-toggle-on fa-lg text-primary<?= $column ?>"></i></a></td>
<?php
      }

  if ($set == 'shipping') {
?>
          <td class="text-center"><?= (isset($module->zone) ? tep_get_geo_zone_name($module->zone) : "-"); ?></td>
<?php
  }


      $sort_order_constant = module_get_common_prefix ($module->keys()) . 'SORT_ORDER';
      // Changes sort_order to consecutive numbers
      $group_installed_position ++;
      $changed = module_set_sort_order ($sort_order_constant, $group_installed_position, $module->sort_order);
      if ($changed == true) {
        $module->sort_order = $group_installed_position;
      }

      $button_up ="";
      $button_down ="";
      $sort_order ="";
      if (defined($sort_order_constant)) {
        $sort_order = $module->sort_order;
        if ($sort_order > 1)  {
          $button_up = '<a href="' . tep_href_link($PHP_SELF, 'set=' . $set . '&module=' . $module->code . '&action=reorder&position=' . ($sort_order - 1)) . '"><i class="fas fa-chevron-circle-up fa-lg text-primary"></i></a>';
        } else {
          $button_up = '<i class="fas fa-chevron-circle-up fa-lg text-muted"></i>';
        }
        if ($sort_order < count ($modules_installed) )  {
          $button_down = '<a href="' . tep_href_link($PHP_SELF, 'set=' . $set . '&module=' . $module->code . '&action=reorder&position=' . ($sort_order + 1)) . '"><i class="fas fa-chevron-circle-down fa-lg text-primary"></i></a>';
        } else {
          $button_down = '<i class="fas fa-chevron-circle-down fa-lg text-muted"></i>';
        }
      }
?>
          <td class="text-center" nowrap><?= $button_up .  " " . $sort_order . " " . $button_down;?></td>
          <td class="text-center"><?php

      if ($module->check()) {
        $status_constant = module_get_common_prefix ($module->keys()) . 'STATUS';
        if (defined ($status_constant) ) {
          $status_value = constant($status_constant);
          if (strtolower ($status_value)=="true") {
            echo '<i class="fas fa-circle fa-lg text-success"></i>&nbsp;&nbsp;<a href="' . tep_href_link(basename(__FILE__), 'set=' . $set . '&action=toggle&module=' . $class) . '"><i class="far fa-circle fa-lg text-danger"></i></a>';
          } elseif ($module->enabled < 1)  {
            echo '<a href="' .  tep_href_link(basename(__FILE__), 'set=' . $set . '&action=toggle&module=' . $class) . '"><i class="far fa-circle fa-lg text-success"></i></a>&nbsp;&nbsp;<i class="fas fa-circle fa-lg text-danger"></i>';
          }
        }
      }

?></td>
          <td class="actions text-nowrap">
            <a href="javascript:ModalInfo('<?=$class ?>');"><i class="fas fa-info-circle fa-lg text-info"></i></a>
<?php
      // Remove general setup options already available on main page:
        $module_keys =$module->keys();
        foreach ($module_keys as $key=>$value) {
        if ($value == ($modules['installed'][$i]['constants_prefix'] . "SORT_ORDER")||($value == $modules['installed'][$i]['constants_prefix'] . "CONTENT_PLACEMENT")|| ($value == $modules['installed'][$i]['constants_prefix'] . "STATUS") )
          unset ($module_keys [$key]);
        }

        if (sizeof($module_keys)) {
?>
            <a href="javascript:ModalEdit('<?=$class ?>');"><i class="fas fa-cog fa-lg text-primary"></i></a>
<?php
        } else {
?>
            <i class="fas fa-cog fa-lg text-muted"></i>
<?php
        }
?>
            <a href="<?= tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $class . '&action=remove')?>"><i class="fas fa-trash fa-lg text-danger"></i></a>
          </td>
        </tr>
<?php
    }
  }

    ksort($installed_modules);
    $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
    if (tep_db_num_rows($check_query)) {
      $check = tep_db_fetch_array($check_query);
      if ($check['configuration_value'] != implode(';', $installed_modules)) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = '" . $module_key . "'");
      }
    } else {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
    }

    if ($template_integration == true) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
      if (tep_db_num_rows($check_query)) {
        $check = tep_db_fetch_array($check_query);
        $tbgroups_array = explode(';', $check['configuration_value']);
        if (!in_array($module_type, $tbgroups_array)) {
          $tbgroups_array[] = $module_type;
          sort($tbgroups_array);
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $tbgroups_array) . "', last_modified = now() where configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
        }
      } else {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', '" . $module_type . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
      }
    }

?>
      </tbody>
  <?php
         $colspan =5;
//        if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) $colspan++;
        if ($set =='shipping')  $colspan++;


  ?>
      <tfoot>
      <tr>
        <td colspan="<?= $colspan?>" class="table-info smallText"><?= TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
      </tr>
      </tfoot>
    </table>
  <?php
  } else{
  // New Modules available for install
?>
    <div class="alert alert-warning"><i class="fas fa-warning"></i> <strong><?= TABLE_HEADING_NO_INSTALLED_MODULES ?></strong></div>
<?php
  }
if (isset ($modules['new']))  {
  $num_uninstalled = sizeof($modules['new']);
?>
    <table class="table table-striped table-sm table-hover">
      <thead>
      <tr class="table-info">
<?php
        if ($set =='shipping') {
?>
          <th></th>
<?php
        }
/*
  if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) {
?>
        <th class="d-none d-sm-table-cell"><?= TABLE_HEADING_CLASS ?></th>
<?php
  }
  */
          if ($set =='shipping')  $colspan++;

?>
        <th colspan="2"><?= TABLE_HEADING_INSTALLABLE_MODULES; ?></th>
        <th class="actions"><?= TABLE_HEADING_ACTION; ?></th>
      </tr>
      </thead>
      <tbody>
 <?php
    foreach ( $modules['new'] as $m ) { // itera los módulos no instalados
      $module = new $m['code']();
      $buttons = tep_draw_button ('Install', 'fas fa-plus', tep_href_link($PHP_SELF, 'action=install&set=' . $set . '&module=' . $m['code']),null, null, "btn-primary btn-sm");
      $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $m['description']);
      preg_match('(<div\s+class="secWarning">.*?<\/div>)', $m['description'], $warning);
?>
      <tr class="clickable">
<?php
      if ($set =='shipping') {
?>
        <td><?=  $module->icon ? tep_image(tep_catalog_href_link ($module->icon)) :""; ?></td>
<?php

      }
/*
      if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) {
?>
        <td class="hidden-xs"><small><?= $module->code; ?></small></td>

<?php
      }
*/
?>
        <td><?= $m['title']; ?></td>
        <td><div><?= $description;?></div></td>
        <td class="actions text-nowrap"><?= $buttons; ?></td>
      </tr>
<?php
      unset ($module);
    }
?>
      </tbody>
  <?php
    $colspan=3;
//    if (OSCOM_DEVELOP_SHOW_CONSTANTS =='True' ) $colspan++;
    if ($set =='shipping') $colspan++;

  ?>
      <tfoot>
      <tr class="table-info">

        <td colspan="<?= $colspan ?>" class="text-center"><?= ($num_uninstalled > 0 ?  MODULES_AVAILABLE . $num_uninstalled : NO_MODULES_AVAILABLE) ?></td>
      </tr>
      </tfoot>
    </table>

<?php
  } else {
?>
      <div class="col-sm-12 text-center bg-info"><?= TABLE_HEADING_NO_INSTALLABLE_MODULES ?></div>
<?php
  }
?>
    </div>
  </div>
<?php
  function module_get_common_prefix ($prefix_array){
    sort($prefix_array);
    $s1 = $prefix_array[0];
    $s2 = $prefix_array[count($prefix_array)-1];
    $len = min(strlen($s1), strlen($s2));
    for ($i=0; $i<$len && $s1[$i]==$s2[$i]; $i++);
    $prefix = substr($s1, 0, $i);
    preg_match ('/(.*_)*/', $prefix,  $clean_prefix ) ;
    return $clean_prefix[1];
  }

  function module_set_sort_order ($cfg_key_sort_order, $new_order, $old_order) {
    if ((int)$old_order != (int)$new_order ) {
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = " . $new_order. " where configuration_key = '" . $cfg_key_sort_order . "'");
      return true;
    } else {
      return false;
    }
  }
/*
  function sortbyorder($a, $b) {
    return strnatcmp($a['group'] . '-' . (int)$a['sort_order'] . '-' . $a['title'], $b['group'] . '-' . (int)$b['sort_order'] . '-' . $b['title']);
  }
*/
    function sortbycol($a, $b) {
    return strnatcmp($a['group'] . '-' . $a['column'] . '-' . $a['sort_order'], $b['group'] . '-' . $b['column'] . '-' . $b['sort_order']);
  }
?>

<?php

  require ("includes/classes/modal.php");


  $modal = new modal();
  $modal->button_save = true;
  $modal->button_delete = false;
  $modal->button_cancel = true;
  $modal->output();

?>

<?php
$image_cancel = IMAGE_CANCEL;
$image_close = IMAGE_CLOSE;
$jScript = <<<EOD
$('#modulesModal').on('shown.bs.modal', function () {
    $('#modulesModal').scrollTop(0);
});
function ModalInfo(moduleClass){
  $("form > .modal-content").unwrap();
  $("#ButtonCancelText").text("$image_close");
  $("#ModalButtonSave").hide();

   var params = {"module" : moduleClass, "action" : "details", "set" : "$set"};
    $.ajax({
      data:  params,
      url:   'modules.php',
      type:  'get',
      cache: false,
      beforeSend: function () {
        $(".modal-body").html("Procesando, espere por favor...");
      },
      success:  function (response) {
        $(".modal-title").html($(response).filter('#title').html());
        $(".modal-body").html($(response).filter('#content').html());
        $("#modulesModal").modal('show')

      }
    });
}

function ModalEdit(moduleClass){
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="modules_form" name="modules" action="modules.php?action=save&set=$set" method="post">')
  $("#ButtonCancelText").text("$image_cancel");
  $("#ModalButtonSave").show();

   var params = {"module" : moduleClass, "action" : "edit", "set" : "$set"};
    $.ajax({
      data:  params,
      url:   'modules.php',
      type:  'get',
      cache: false,
      beforeSend: function () {
        $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
      },
      success:  function (response) {
        $(".modal-title").html($(response).filter('#title').html());
        $(".modal-body").html($(response).filter('#content').html());
        $("#modulesModal").modal('show')
      }
    });
}
    $(function(){
      $('select[name="module_group_selection"]').on('change', function () {
          var url = 'modules.php?set='+ $(this).val();
          if (url) {
              window.location = url;
          }
          return false;
      });
    });
EOD;

  $oscTemplate->addBlock('<script>' . $jScript . '</script>', 'admin_footer_scripts');
  require('includes/template_bottom.php');

  require('includes/application_bottom.php');
?>
