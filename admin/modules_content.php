<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License

  Version 1.1

*/

define ('OSCOM_APP_PAYPAL_LOGIN_SORT_ORDER', 'sss');


define ("TEXT_CONFIGURATION_TAB", "config.");
define ('WARNING_NO_INFORMATION_CLASS', 'pppp');
define('TEXT_MODULE_NO_MODULES', 'No modules ');
define('TEXT_MODULE_INSTALLED', ' installed');
define('TEXT_MODULE_UNINSTALLED', ' avaliable for install');

define('TABLE_HEADING_WIDTH', 'Width');
define ('BUTTON_CONFIGURE', 'Configure %s');
define ('BUTTON_REMOVE', 'Remove %s');
define('BUTTON_DISABLE', 'Disable');
define('BUTTON_ENABLE', 'Disable');
define ('BUTTON_CONFIRM_UNINSTALL', 'Do you want to remove the module?');
define ('TEXT_MODULE_DISABLED', 'Disabled');
define ('TABLE_HEADING_STATUS', 'Status');


  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  switch ($action) {

    case "modules_content_toggle":
      // Hack to disable module
      // It would be cleaner to have a method within each module to toggle on or off instead of guessing the status constant.
  
      $class = $_GET['module'];

        require (DIR_FS_CATALOG . "includes/modules/content/" . $_GET['group'] . "/" . $class . ".php");
        require (DIR_FS_CATALOG . "includes/languages/" . $language . "/modules/content/" . $_GET['group'] . "/" . $class . ".php");
        $module = new $class;
        $status_constant = get_common_prefix ($module->keys()) . 'STATUS';
        $toggle = constant($status_constant) =='True' ? 'False': 'True' ;
        $query = tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $toggle  . "' where configuration_key = '" . $status_constant . "'");
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $class));
      exit();
      break;

    case 'modules_content_edit':
      $keys ="";
      require (DIR_FS_CATALOG . "includes/modules/content/" . $_GET['group'] . "/" . $_GET['module'] . ".php");
      require (DIR_FS_CATALOG . "includes/languages/" . $language . "/modules/content/" . $_GET['group'] . "/" . $_GET['module'] . ".php");
      $module = new $_GET['module']();
      $keys = '<span id="title" ><i class="fas fa-cogs"></i>&nbsp;' . $module->title . '</span><span id="content">';

      foreach ($module->keys() as $key) {

        $key = tep_db_prepare_input($key);

        $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from configuration where configuration_key = '" . tep_db_input($key) . "'");
        $key_value = tep_db_fetch_array($key_value_query);

        $keys .= '<div class="alert alert-success alert-sm"><strong>' . $key_value['configuration_title'] . '</strong><br>' . $key_value['configuration_description'];

        if ($key_value['set_function']) {
          eval('$keys .= ' . $key_value['set_function'] . "'" . $key_value['configuration_value'] . "', '" . $key . "');");
        } else {
          $keys .= tep_draw_input_field('configuration[' . $key . ']', $key_value['configuration_value']);
        }
        $keys .='</div></span>';
      }
      echo $keys;

      exit();
      break;
    
    case 'modules_content_save':
      foreach ($_POST['configuration'] as $key => $value) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
      }
      
      $current_module = (isset($_GET['module']) ? $_GET['module'] : '');

      tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set . '&module=' . $current_module));
      break;
  }


  $group_array = array();

  // Read the configuration modules currently installed:
  $check_query = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_CONTENT_INSTALLED' limit 1");

  // If there are no modules currently installed the configuration table entry is made
  // I'm not sure in what cases this could be necessary...
  if (tep_db_num_rows($check_query) < 1) {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_CONTENT_INSTALLED', '', 'This is automatically updated. No need to edit.', '6', '0', now())");
    define('MODULE_CONTENT_INSTALLED', '');
  }
  $modules_installed = (tep_not_null(MODULE_CONTENT_INSTALLED) ? explode(';', MODULE_CONTENT_INSTALLED) : array());
  $modules = array('installed' => array(), 'new' => array());

  if ($maindir = @dir(DIR_FS_CATALOG_MODULES . 'content/')) {
    while ($group = $maindir->read()) {
      if ( ($group != '.') && ($group != '..') && is_dir(DIR_FS_CATALOG_MODULES . 'content/' . $group)) {

        $group_array [$group] = array ('group' =>$group,
                                       'count' => 0
                                       );

        if ($dir = @dir(DIR_FS_CATALOG_MODULES . 'content/' . $group)) {
          while ($file = $dir->read()) {
            if (!is_dir(DIR_FS_CATALOG_MODULES . 'content/' . $group . '/' . $file)) { // es un archivo
            // get common prefix for files in dir - for testing prefixes
            $filenames_array[$group][]=$file;

              if (substr($file, strrpos($file, '.')) == '.php') {
                $class = substr($file, 0, strrpos($file, '.'));

                if (!class_exists($class)) {
                  if ( file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/content/' . $group . '/' . $file) ) {
                    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/content/' . $group . '/' . $file);
                  }

                  include(DIR_FS_CATALOG_MODULES . 'content/' . $group . '/' . $file);
                }

                if (class_exists($class)) {
                  $module = new $class();

                  if (in_array($group . '/' . $class, $modules_installed)) {
                    $modules['installed'][] = array('code' => $class,
                                                    'title' => $module->title,
                                                    'group' => $group,
                                                    'description' =>$module->description,
                                                    'sort_order' => (int)$module->sort_order);
                    $group_array [$group]['count']++;

                  } else {
                    $modules['new'][] = array('code' => $class,
                                              'title' => $module->title,
                                              'group' => $group);
                  }
                        unset ($module);

                }
              }
            }
          }

          $dir->close();
        }
      }
    }

    $maindir->close();

    usort($modules['installed'], '_sortContentModulesInstalled');
    usort($modules['new'], '_sortContentModuleFiles');
  }

// Update sort order in MODULE_CONTENT_INSTALLED
  $_installed = array();

  foreach ( $modules['installed'] as $m ) {
    $_installed[] = $m['group'] . '/' . $m['code'];
  }

  if ( implode(';', $_installed) != MODULE_CONTENT_INSTALLED ) {
    tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");
  }


  $current_module = (isset($_GET['module']) ? $_GET['module'] : '');
  $class = basename($current_module);


  if (tep_not_null($action)) {
    switch ($action) {
      case 'modules_content_reorder':
        $position = $_GET['position'];

      // first we update the position of this module and save its old position:
        foreach ( $modules['installed'] as $modules_installed ) {
          if ( $modules_installed['code'] == $class ) {
            $module = new $class();
            $old_position = $modules_installed['sort_order'];
            $position_constant = get_common_prefix ($module->keys()) . 'SORT_ORDER';
            $common_group = $modules_installed['group'];
            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $position . "' where configuration_key = '" . $position_constant . "'");
            $modules_installed['sort_order'] = $position;
            break;
          }
        }
        
      // Then we update the ,pdule that was previously in the selected position:
        foreach ( $modules['installed'] as $modules_installed ) {
          if ( $modules_installed['sort_order'] == $position && $modules_installed['group'] == $common_group && $modules_installed['code'] != $class ) {
            $position = $_GET['position'];
            $module = new $modules_installed['code']();
            $position_constant = get_common_prefix ($module->keys()) . 'SORT_ORDER';
            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $old_position . "' where configuration_key = '" . $position_constant . "'");
            $modules_installed['sort_order'] = $old_position;
            break;
          }
        }
        tep_redirect(tep_href_link(basename(__FILE__), 'set=' . $set .  '&module=' . $class));
        break;
  
    case 'modules_content_change_width':
      foreach ( $modules['installed'] as $modules_installed ) {

        if ( $modules_installed['code'] == $class ) {
          $new_width = $_GET['width'];
          $module = new $class();
          $content_width_constant_name = get_common_prefix ($module->keys()) . 'CONTENT_WIDTH';
          tep_db_query("update configuration set configuration_value = '" . $new_width . "' where configuration_key = '" . $content_width_constant_name . "'");
          break;
        }
      }
      tep_redirect(tep_href_link('modules_content.php', 'module=' . $class));
    break;
      case 'modules_content_install':
        foreach ( $modules['new'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();

            $module->install();

            $modules_installed[] = $m['group'] . '/' . $m['code'];

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");
          }
        }

        tep_redirect(tep_href_link('modules_content.php', 'module=' . $class));

        break;

      case 'modules_content_uninstall':
        $class = basename($_GET['module']);

        foreach ( $modules['installed'] as $mi ) {
          if ( $mi['code'] == $class ) {
            $module = new $class();

            $module->remove();

            $modules_installed = explode(';', MODULE_CONTENT_INSTALLED);

            if (in_array($mi['group'] . '/' . $mi['code'], $modules_installed)) {
              unset($modules_installed[array_search($mi['group'] . '/' . $mi['code'], $modules_installed)]);
            }

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");

            tep_redirect(tep_href_link(basename($PHP_SELF)));
          }
        }
        tep_redirect(tep_href_link(basename($PHP_SELF), 'module=' . $class));

        break;
      

    }
  }

  require('includes/template_top.php');
?>
    <div id="contentmoduleTabs">
      <div class="row">
        <div class="col-sm-8 pageHeading"><i class="fas fa-cogs"></i>&nbsp;<?= HEADING_TITLE ?></div>
        <div class="col-sm-4 pageHeading text-right"></div>
      </div>
      <div class="card">
        <div class="card-header">
          <ul id="contentmoduleTabsMain" class="nav nav-tabs  card-header-tabs">
<?php

  $active=' active';

  foreach ($group_array as $group_item => $group_key) {
    $group_title = $group_item;
?>
            <li class="nav-item"><a class="nav-link<?= $active ?>" data-target ="#section_<?= $group_item ?>" data-toggle="tab"><?= $group_title ?></a></li>
<?php
    $active="";
  }
?>
          </ul>
        </div>
<?php
/////////////
// Setup div:
?>
        <div class="card-body tab-content">
<?php
  $active='show active';

  foreach ($group_array as $group_item => $group_key) {
    // Level 1 iterate groups
    $group_installed_position = 0;
    $group_uninstalled_position = 0;
?>
          <div id="section_<?= $group_item; ?>" class="tab-pane <?= $active ?>">
            <div class="row">
              <div class="col-12">
                <table id="table-<?= $group_item; ?>" class="table table-sm table-striped">
                  <thead>
                    <tr class="table-info">
                      <th><?php echo TABLE_HEADING_MODULES . TEXT_MODULE_INSTALLED; ?></th>
                      <th class="text-center"><?php echo TABLE_HEADING_SORT_ORDER; ?></th>
                      <th class="text-center"><?php echo TABLE_HEADING_WIDTH; ?></th>
                      <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
                      <th class="actions"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
    foreach ($modules['installed'] as $mi ) {
      // Level 2 iterates installed modules
      $module = new $mi['code']();

      if ($group_item == $module->group) {
        $group_installed_position ++;
        // reordena ls módulos
        $sort_order_constant = get_common_prefix ($module->keys()) . 'SORT_ORDER';
        $changed = module_set_sort_order ($sort_order_constant, $group_installed_position, $module->sort_order);
        if ($changed ==true) {
          $module->sort_order = $group_installed_position;
        }
        if ((isset($_GET['module']) && ($_GET['module'] == $module->code)))  { // agregar && ($action == 'configure')
          $module_info = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'signature' => (isset($module->signature) ? $module->signature : null),
                               'api_version' => (isset($module->api_version) ? $module->api_version : null),
                               'sort_order' => (int)$module->sort_order,
                               'keys' => array());

          foreach ($module->keys() as $key) {// Nivel 3 itera las claves del módulo actual de #2
            $key = tep_db_prepare_input($key);

            $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from configuration where configuration_key = '" . tep_db_input($key) . "'");
            $key_value = tep_db_fetch_array($key_value_query);

            $module_info['keys'][$key] = array('title' => $key_value['configuration_title'],
                                               'value' => $key_value['configuration_value'],
                                               'description' => $key_value['configuration_description'],
                                               'use_function' => $key_value['use_function'],
                                               'set_function' => $key_value['set_function']);
          }
          $mInfo = new objectInfo($module_info);
        }

        $muted = $module->enabled ? '':' text-muted';

        $module_enabled = $module->enabled ? 1 : 0;




        if (isset($mInfo) && is_object($mInfo) && ($module->code == $mInfo->code) ) {
?>
                <tr class="clickable table-success<?= $muted ?>">
<?php
          $selected =" selected";
        } else {
?>
                <tr class="clickable<?= $muted ?>">
<?php
          $selected ="";
        }
        // trick to remove warnings from descriptions
        // These should be in a separate variable IMHO...
        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);

?>
                  <td title="<?= htmlspecialchars($description) . " - " . $module->code ?>" data-toggle="tooltip"><?= $module->title ?></td>
<?php
//////////////
// Sort order:
/////////////////
//// NEW
      // sort order for modules :
      // module, old_position, new_position, group
//        $sort_order_constant= get_common_prefix ($module->keys()) . 'SORT_ORDER';

//            $button_up ="";
//            $button_down ="";
            
            if ($module->sort_order > 1)  {
              $button_up = '<a href="javascript:SortModule(\'' . $module->code . '\',' . ($module->sort_order -1) . ',\'' . $group_item .'\')"><i class="fas fa-chevron-circle-up fa-lg text-primary"></i></a>';
            } else {
              $button_up = '<i class="fas fa-chevron-circle-up fa-lg text-muted"></i>';
            }

            if ($module->sort_order < $group_key['count']) {
              $button_down = '<a href="javascript:SortModule(\'' . $module->code . '\',' . ($module->sort_order +1) . ',\'' . $group_item . '\')"><i class="fas fa-chevron-circle-down fa-lg text-primary"></i></a>';
            } else {
              $button_down = '<i class="fas fa-chevron-circle-down fa-lg text-muted"></i>';
            }
?>
                  <td class="text-center" nowrap><?= $button_up  . '&nbsp;<span id="sort-' . $module->sort_order . '">' . $module->sort_order . '</span>&nbsp;' . $button_down ?></td>
<?php
/// end sort


///////////////////////
// module width change:

        $content_width_constant_name = get_common_prefix ($module->keys()) . 'CONTENT_WIDTH';
        $plus ="";
        $minus ="";
        if (defined($content_width_constant_name)) {
          $content_width_string = constant ($content_width_constant_name);

          $valid_values_query = tep_db_query("select set_function from configuration where configuration_key = '". $content_width_constant_name ."'");
          $valid_values = tep_db_fetch_array ($valid_values_query);

          preg_match('/.*?\(.*?\((.*?)\)/', $valid_values['set_function'], $result);
          $replace_characters = array ("'", " ", '"');
          $results = str_replace($replace_characters, "",$result[1]);

          $valid_content_width_values_array = explode(',', $results);
          sort ($valid_content_width_values_array);

          if (is_numeric ($content_width_string)) {
            // check previous and next values
            $index = array_search($content_width_string, $valid_content_width_values_array);
            if($index != FALSE) {
              $plus = array_key_exists ($index + 1,$valid_content_width_values_array)? (int)$valid_content_width_values_array[$index + 1]: 0;
              $minus = array_key_exists ($index - 1,$valid_content_width_values_array)? (int)$valid_content_width_values_array[$index - 1]: 0;
              $content_width_integer = (int)$content_width_string;
            }
          } else {
            //  A string, can you believe it??... :-( pffffff let's wonder...
            switch (substr($content_width_string, 0, 4)) {
              case 'Half':
                $plus = 'Full';
                $minus = 0 ;
                $content_width_integer = 6;
                break;
              case 'Full':
                $plus = 0;
                $minus ='Half';
                $content_width_integer = 12;

                break;
              default: // No idea...
                $plus = 0;
                $minus = 0;
                $content_width_integer = 99;  // Who knows...
                break;
            }
          }
        } else {
          // can't use change function - no config value :-(
          $content_width_integer = 99;  // Who knows...
        }

        if ($content_width_integer !=99) {
          $display_content_width = $content_width_integer;

          if (((int)$plus > 0) or ((string)$plus == 'Full'))  {
            $button_plus = '<a href="javascript:ChangeModuleWidth(\'' . $module->code . '\',' . $plus  . ',\'' . $group_item .'\')"><i class="fas fa-plus-circle fa-lg text-primary"></i></a>';
          } else {
            // no se puede aumentar
             $button_plus = '<i class="fas fa-plus-circle fa-lg text-muted"></i>';
          }

          if ((int)$minus > 0|| (string)$minus == 'Half')  {
            $button_minus = '<a href="javascript:ChangeModuleWidth(\'' . $module->code . '\',' . $minus  . ',\'' . $group_item .'\')"><i class="fas fa-minus-circle fa-lg text-primary"></i></a>';
           } else {
            $button_minus = '<i class="fas fa-minus-circle" fa-lg text-muted></i>';
          }
        } else {
          $display_content_width = '<i class="fas fa-ban fa-lg text-danger"></i >';
          $button_plus ="";
          $button_minus ="";
        }

?>
                  <td class="text-center" nowrap><?= $button_minus . " " . $display_content_width . " " . $button_plus ?></td>
                  <td class="text-center" nowrap>
<?php

// module width change end
//////////////////////////

        if (($module_enabled ==1)) {
?>
                    <i class="fas fa-circle fa-lg text-success"></i>&nbsp;&nbsp;<a href="javascript:toggleModule('<?= $module->code ?>','<?= $group_item ?>')" ><i class="far fa-circle fa-lg text-danger"></i></a>
<?php
        } else {
?>
                    <a href="javascript:toggleModule('<?= $module->code ?>','<?=  $group_item ?>')"><i class="far fa-circle fa-lg text-success"></i></a>&nbsp;&nbsp;<i class="fas fa-circle fa-lg text-danger"></i>
<?php
        }
?>
                  </td>
                  <td class="actions text-nowrap">
                    <i class="fas fa-info-circle fa-lg text-primary" title="<?= htmlspecialchars($description) ?>" data-toggle="tooltip"></i>&nbsp;
                    <a title="<?= sprintf (BUTTON_CONFIGURE , $module->title) ?>" data-toggle="tooltip" data-placement="top" href="javascript:ModalEditModule('<?= $module->code ?>', '<?= $module->group ?>');"><i class="fas fa-cog fa-lg text-primary"></i></a>&nbsp;
                    <a  data-toggle="tooltip" data-placement="top" title="<?= sprintf(BUTTON_REMOVE, $module->title) ?>" href="<?= tep_href_link('modules_content.php', 'action=modules_content_uninstall&module=' . $module->code) ?>" onclick="return confirm('<?= addslashes (BUTTON_CONFIRM_UNINSTALL) ?>')"><i class="fas fa-trash fa-lg text-primary"></i></a>
                  
                  </td>
                </tr>
<?php

      }
      unset ($module);
    }

    if ($group_installed_position == 0) {
?>
                <tr>
                  <td colspan="4"><?php echo TEXT_MODULE_NO_MODULES . TEXT_MODULE_INSTALLED; ?></td>
                </tr>
<?php
    }
?>
                </tbody>
              </table>
              <table class="table table-sm table-striped">
                <thead>
                  <tr class="table-info">
                    <th colspan="3"><?php echo TABLE_HEADING_MODULES . TEXT_MODULE_UNINSTALLED; ?></th>
                    <th class="text-center"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
<?php

    foreach ( $modules['new'] as $m ) { // itera los módulos no instalados

      $module = new $m['code']();
        if ($group_item == $module->group) {
        $group_uninstalled_position ++;
?>
                <tr>
<?php
        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);
?>
                  <td><?php echo $module->title; ?></td>
                  <td colspan="2" align="center"><div><?php echo $description;?></div></td>
                  <td align="center" id="buttons"><?= tep_draw_button ('Install', 'fas fa-plus', tep_href_link('modules_content.php', 'action=modules_content_install&module=' . $module->code)) ?></td>
                </tr>
<?php
      }
      unset ($module);
    }

    if ($group_uninstalled_position == 0) {
?>
                <tr>
                  <td colspan="4"><div class="alert alert-warning text-center m-0"><?php echo TEXT_MODULE_NO_MODULES . TEXT_MODULE_UNINSTALLED; ?></div></td>
                </tr>

<?php
    } else {
?>

<?php
    }
?>
                </tbody>
              </table>
                  <p class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . DIR_FS_CATALOG_MODULES . 'content/' .  $group_item . '/'; ?></p>

            </div>
          </div>
        </div>

<?php
      $active='';
  }
?>
        </div>
      </div>
    </div>
<?php

  // Loads modal:
  require ("includes/classes/modal.php");

  $modal = new modal();
  $modal->button_save = true;
  $modal->button_cancel = true;
  $modal->output();



?>
<!-- Javascript for modules_content.php -->
<script>
function ModalEditModule(moduleClass, ModuleGroup){
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="modules_form" name="modules" action="modules_content.php?action=modules_content_save" method="post">')
  $("#ButtonCancelText").text("$image_cancel");
  $("#ModalButtonSave").show();

   var params = { "action" : "modules_content_edit", "module" : moduleClass, "group": ModuleGroup};
    $.ajax({
      data:  params,
      url:   'modules_content.php',
      type:  'get',
      cache: false,
      beforeSend: function () {
        $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
      },
      success:  function (response) {
        $(".modal-title").html($(response).filter('#title').html());
        $(".modal-body").html($(response).filter('#content').html());
        $("#modules_contentModal").modal('show')
      }
    });
}

function toggleModule(module, group){
  var params = { "action" : "modules_content_toggle", "module" : module, "group": group};

  $.ajax({
    data:  params,
    url: 'modules_content.php',
    type: 'get',
    cache: false,
    beforeSend: function () {
      document.body.style.cursor = 'progress';
    },
    success:  function (response) {
      $("#table-" + group).html($(response).find("#table-" + group).html());
      document.body.style.cursor = 'default';
    }
  });
}

function SortModule (module, position, group) {

  var params = { "action" : "modules_content_reorder", "module" : module,  "position": position };

  $.ajax({
    data:  params,
    url: 'modules_content.php',
    type: 'get',
    cache: false,
    beforeSend: function () {
      document.body.style.cursor = 'progress';
    },
    success:  function (response) {
      $("#table-" + group).html($(response).find("#table-" + group).html());
      document.body.style.cursor = 'default';
    }
  });
}
  function ChangeModuleWidth (module, width, group) {

  var params = { "action" : "modules_content_change_width", "module" : module,  "width": width };

  $.ajax({
    data:  params,
    url: 'modules_content.php',
    type: 'get',
    cache: false,
    beforeSend: function () {
      document.body.style.cursor = 'progress';
    },
    success:  function (response) {
      $("#table-" + group).html($(response).find("#table-" + group).html());
      document.body.style.cursor = 'default';
    }
  });
  
}
</script>
<!-- End Javascript for modules_content.php -->
<?php
  require('includes/template_bottom.php');

  require('includes/application_bottom.php');



  function get_common_prefix ($prefix_array){
  // from http://stackoverflow.com/a/35838357/3615311
  // modified to check if the last common character is "_"
    sort($prefix_array);

    $s1 = $prefix_array[0];
    $s2 = $prefix_array[count($prefix_array)-1];
    $len = min(strlen($s1), strlen($s2));

    for ($i = 0; $i < $len && $s1[$i] == $s2[$i]; $i++);

    $prefix = substr($s1, 0, $i);

    preg_match ('/(.*_)*/', $prefix,  $clean_prefix ) ;
    return $clean_prefix[1];

}

  function module_set_sort_order ($cfg_key_sort_order, $new_order, $old_order) {

    if ((int)$old_order != (int)$new_order ) {
      tep_db_query("update configuration set configuration_value = " . $new_order. " where configuration_key = '" . $cfg_key_sort_order . "'");
      return true;
    } else {
      return false;
    }
  }

    function _sortContentModulesInstalled($a, $b) {
      return strnatcmp($a['group'] . '-' . (int)$a['sort_order'] . '-' . $a['title'], $b['group'] . '-' . (int)$b['sort_order'] . '-' . $b['title']);
    }

    function _sortContentModuleFiles($a, $b) {
      return strnatcmp($a['group'] . '-' . $a['title'], $b['group'] . '-' . $b['title']);
    }



?>
