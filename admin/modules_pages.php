<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

// TODO:Protect to direct access without prameters



// Extra small devices (portrait phones, less than 576px)
// XS
// Small devices (landscape phones, 576px and up)
// SM
// Medium devices (tablets, 768px and up)
// MD
// Large devices (desktops, 992px and up)
//LG
// Extra large devices (large desktops, 1200px and up)
// XL



  require('includes/application_top.php');

// This block sets:
// $columns_group_array / ${$type . '_group_array'}
//  ${'modules_' . $type}['installed']/['new']: ie. $modules_buttons['installed']

  $json = (isset($_GET['desired_groups']) ? json_decode(urldecode( $_GET['desired_groups']), true) : '');
  $page = $json[0][0]; 

  foreach ($json[1] as $value) {
    $desired_groups['group'][] = $value;
    if (count($json[1]) > 1) {
      // Reads tabs titles if there is more than a group for any type of module
      require (DIR_FS_CATALOG . 'includes/languages/' . $language . '/pages/' . $value . '.php');
      $desired_groups['header'][] = constant('TABLE_HEADING_' . strtoupper($value));
    }
  }
  $path_parts = pathinfo($PHP_SELF);
  $fileName = $path_parts['filename'];
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $types = array('columns', 'buttons');

  foreach ($types as $type) {
    ${$type . '_group_array'} = array();
    ${'modules_' . $type . '_installed'} = (tep_not_null( constant ('MODULE_' . strtoupper ($type) . '_INSTALLED')) ? explode(';', constant ('MODULE_' . strtoupper ($type) . '_INSTALLED')) : array());
    ${'modules_' . $type} = array('installed' => array(), 'new' => array());

    if ($maindir = @dir(DIR_FS_CATALOG . 'includes/pages/' . $type . '/')) {
      while ($group = $maindir->read()) {
        if ( ($group != '.') && ($group != '..') && is_dir(DIR_FS_CATALOG . 'includes/pages/' . $type . '/' . $group)) {

          ${$type . '_group_array'} [$group] = array ('group' =>$group,
                                         'count_installed' => 0,
                                         'count_uninstalled' => 0
                                         );

          if ($dir = @dir(DIR_FS_CATALOG . 'includes/pages/' . $type . '/' . $group)) {
            while ($file = $dir->read()) {
              if (!is_dir(DIR_FS_CATALOG . 'includes/pages/' . $type . '/' . $group . '/' . $file)) {
                if (substr($file, strrpos($file, '.')) == '.php') {
                  $class = substr($file, 0, strrpos($file, '.'));

                  if (!tep_class_exists($class)) {
                    if ( file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group . '/' . $file )) {
                      include(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group . '/' . $file);
                    } else {
// DEBUG - to be dremoved
                      echo "NO EXISTE" . DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group . '/' . $file . "<br>";
                    }
                    include(DIR_FS_CATALOG . 'includes/pages/' . $type . '/' . $group . '/' . $file);
                  }

                  if (tep_class_exists($class)) {
                    $module = new $class();

/////////////////////
// CREATE LANG FILE - REMOVE FOR PRODUCTION
/*
if (!file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group)) {
    mkdir(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group, 0777, true);
}

if ( !file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group . '/' . $file )) {
   
$my_file = DIR_FS_CATALOG_LANGUAGES . $language . '/pages/' . $type . '/' . $group . '/' . $file;
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$data = "<?php
define ('" . $module->cfg_key . 'TITLE' . "','');
define ('" . $module->cfg_key . 'DESCRIPTION' . "','');

";
fwrite($handle, $data);

}
*/
/////////////////////

                    if (in_array($group . '/' . $class, ${'modules_' . $type . '_installed'})) {

                    ${'modules_' . $type}['installed'][] = array('code' => $class,
                                                      'title' => $module->title,
                                                      'group' => $group,
                                                      'sort_order' => (int)$module->sort_order);
                    ${$type . '_group_array'}[$group]['count_installed']++;

                    } else {
                      ${'modules_' . $type}['new'][] = array('code' => $class,
                                                'title' => $module->title,
                                                'group' => $group);
                      ${$type . '_group_array'}[$group]['count_uninstalled']++;
                    }
                  }
                }
              }
            }
            $dir->close();
          }
        }
      }

      $maindir->close();

      usort(${'modules_' . $type}['installed'], '_sortModulesInstalled');
      usort(${'modules_' . $type}['new'], '_sortModuleFiles');
    }


  // Update sort order
    
    $_installed = array();

    foreach ( ${'modules_' . $type}['installed'] as $m ) {
      $_installed[] = $m['group'] . '/' . $m['code'];
    }

    if ( implode(';', $_installed) != constant ('MODULE_' . strtoupper ($type) . '_INSTALLED') ) {
      tep_db_query("update configuration set configuration_value = '" . implode(';', $_installed) . "' where configuration_key = 'MODULE_" . strtoupper ($type) . "_INSTALLED'");
    }
  }


  if (tep_not_null($action)) {
    switch ($action) {

      case 'reorder':
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
        $current_module = (isset($_GET['module']) ? $_GET['module'] : '');
        $class = basename($current_module);
        $position = $_GET['position'];
      // first we update the position of this module and save its old position:
        foreach ( ${'modules_' . $type}['installed'] as $m ) {
          if ( $m['code'] == $current_module ) {
            $module = new $current_module();
            $old_position = $m['sort_order'];
            $position_constant = $module->cfg_key . 'SORT_ORDER';
            $common_group = $m['group'];

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $position . "' where configuration_key = '" . $position_constant . "'");
            $m['sort_order'] = $position;
            break;
          }
        }


        foreach ( ${'modules_' . $type}['installed'] as $m ) {
          if ( $m['sort_order'] == $position && $m['group'] == $common_group && $m['code'] != $current_module ) {
            $position = $_GET['position'];
            $module = new $m['code']();
            $position_constant = $module->cfg_key . 'SORT_ORDER';

            tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $old_position . "' where configuration_key = '" . $position_constant . "'");
            $m['sort_order'] = $old_position;
            break;
          }
        }
        tep_redirect(tep_href_link(basename(__FILE__), tep_get_all_get_params(array ('action','module')). '&module=' . $current_module));
        break;

      case 'toggle':
      $class = basename($_GET['module']);
      $type = $_GET['type'];

        foreach ( ${'modules_' . $type}['installed'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();
            if (method_exists ($module, 'toggle' )) {
              $module->toggle();
            }
            if ($module->enabled) {
?>
  <span id="toggler"><i class="fas fa-circle fa-lg text-success"></i>&nbsp;&nbsp;<a href="javascript:ToggleModule('<?= $module->code ?>', '<?= $type ?>');" data-toggle="tooltip"><i class="far fa-circle fa-lg text-danger"></i></a></span>
  <span id="muted">true</span>
<?php
            } else {
?>
  <span id="toggler"><a href="javascript:ToggleModule('<?= $module->code ?>', '<?= $type ?>');" data-toggle="tooltip"><i class="far fa-circle fa-lg text-success"></i></a>&nbsp;&nbsp;<i class="fas fa-circle fa-lg text-danger"></i></span>
  <span id="muted">false</span>
<?php
            }
            exit();
          }
        }
        break;

      case 'remove':
        $class = basename($_GET['module']);
      $type = $_GET['type'];
        foreach ( ${'modules_' . $type}['installed'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();
            $title = $module->title;
            $module->remove();

          ${'modules_' . $type . '_installed'} = explode(';', constant ('MODULE_' . strtoupper ($type) . '_INSTALLED'));

            if (in_array($m['group'] . '/' . $m['code'], ${'modules_' . $type . '_installed'})) {
              unset(${'modules_' . $type . '_installed'}[array_search($m['group'] . '/' . $m['code'], ${'modules_' . $type . '_installed'})]);
            }

            tep_db_query("update configuration set configuration_value = '" . implode(';', ${'modules_' . $type . '_installed'}) . "' where configuration_key = 'MODULE_" . strtoupper($type) . "_INSTALLED'");
            $messageStack->add_session (sprintf(TEXT_REMOVED, $title), "success");
           // tep_redirect(tep_href_link('modules_pages.php' , tep_get_all_get_params() ));
          }
        }
        tep_redirect(tep_href_link('modules_pages.php',tep_get_all_get_params(array ('action','module')) . 'module=' . $module->code ));
        break;

      case 'modules_pages_install':
        $class = basename($_GET['module']);
        $type = $_GET['type'];

        foreach ( ${'modules_' . $type}['new'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();

            $module->install();

            ${'modules_' . $type . '_installed'}[] = $m['group'] . '/' . $m['code'];

            tep_db_query("update configuration set configuration_value = '" . implode(';', ${'modules_' . $type . '_installed'}) . "' where configuration_key = 'MODULE_" . strtoupper($type) . "_INSTALLED'");

            $messageStack->add_session(sprintf (TEXT_INSTALLED, $module->title),'success');
          }
        }
        tep_redirect(tep_href_link('modules_pages.php',tep_get_all_get_params(array ('action','module')) . 'module=' . $module->code ));
        break;
    }
  }
// actions end

  require('includes/template_top.php');
?>
  <div class="d-flex justify-content-between">
    <div class="mr-auto p-2 pageHeading"><i class="fas fa-cogs"></i> <?= sprintf (HEADING_TITLE_MODULES_PAGES, $page) ?></div>
    <div class="py-2"><a href="<?= tep_href_link($page . ".php" ) ?>" class="btn btn-info btn-sm"><i class="fas fa-undo"></i> <?= TEXT_RETURN ?></a></div>
  </div>

  <div class="row">
<?php
   foreach ($types as $type) {
?>
  <div id="columnsTabs" class="col-lg-6">
  <div class="d-flex justify-content-between">
    <div class="mr-auto p-2 pageHeading"><i class="fas fa-table"></i> <?= constant ('HEADING_TITLE_' . strtoupper($type)) ?></div>
  </div>
    <ul id="ColumnsTabsMain" class="nav nav-pills">
<?php
  ksort  (${$type . '_group_array'}); ///////////////////// aqui????

  if (count ($desired_groups['group']) > 1) {
    $first=' active';
    foreach (${$type . '_group_array'} as $group_item => $group_key) {
      $tab_title_index = array_search($group_item, $desired_groups['group']);
      if ($tab_title_index !== false) {
     
    //  if (in_array($group_item, $desired_groups['group'])) {
  ?>
      <li class="nav-item"><a class="nav-link<?= $first ?>" data-target ="#section_<?= $type ?>_<?= $group_item ?>" data-toggle="tab"><?= $desired_groups['header'][$tab_title_index] ?></a></li>
<?php

      $first = "";
      }
    }
  }
?>
    </ul>
<?php
/////////////
// Setup div:
?>
    <div class="tab-content">
<?php
  $first=' active';

  foreach (${$type . '_group_array'} as $group_item => $group_key) { // Nivel 1 itera los grupos
    $group_installed_position = 0;

    if (in_array($group_item, $desired_groups['group'])) {

?>
    <div id="section_<?= $type ?>_<?= $group_item; ?>" class="tab-pane<?= $first ?>">
      <div class="panel panel-primary">
        <div class="panel-body">
<?php
      if ($group_key['count_installed'] > 0) {
?>
          <table class="table table-sm table-striped table-hover" id="table-<?= $type ?>-<?= $group_item ?>">
            <thead>
            <tr class="table-info">
              <th><?= constant('TABLE_HEADING_' . strtoupper($type)) ?></th>
              <th><?= TABLE_HEADING_DESCRIPTION ?></th>
              <th class="text-center"><?= TABLE_HEADING_SORT_ORDER ?></th>
              <th class="text-center"><?= TABLE_HEADING_STATUS ?></th>
              <th class="actions"><?= TABLE_HEADING_ACTION; ?></th>
            </tr>
            </thead>
            <tbody>
<?php
        foreach (${'modules_' . $type}['installed'] as $m ) {
          if($m['group'] == $group_item) {

            $module = new $m['code']();
    
            $group_installed_position ++;

            // checks sort for each module and set to current order if it's not correlative
//          if ($m['sort_order'] != $group_installed_position && method_exists ($module , 'set_sort_order')) {
            if ($m['sort_order'] != $group_installed_position ) {
              $module->set_sort_order ($group_installed_position);
            }

            if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $module->code))) && !isset($mInfo)) {
              $module_info = array('code' => $module->code,
                                   'title' => $module->title,
                                   'description' => $module->description,
                                   'signature' => (isset($module->signature) ? $module->signature : null),
                                   'api_version' => (isset($module->api_version) ? $module->api_version : null),
                                   'sort_order' => (int)$module->sort_order,
                                   'keys' => array());

              foreach ($module->keys() as $key) {
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

            if (isset($mInfo) && is_object($mInfo) && ($module->code == $mInfo->code) ) {
  ?>
            <tr id="<?= $module->code . $type?>" class="clickable table-success<?= $muted ?>">
<?php
            } else {
  ?>
            <tr id="<?=$type . '_' . $module->code?>" class="clickable<?= $muted ?>">
<?php
            }
  ?>
              <td><?= $module->title ?></td>
              <td><?= $module->description ?></td>
<?php
      // sort order for modules:
      // module, old_position, new_position, group
            $button_up ="";
            $button_down ="";
            
//            if (method_exists($module, "set_sort_order") ) {
            if ($module->sort_order > 1)  {
              $button_up = '<a href="javascript:SortModule(\'' . $module->code . '\',' . ($module->sort_order -1) . ',\'' . $group_item .'\', \'' . $type . '\')"><i class="fa fa-chevron-circle-up fa-lg text-primary"></i></a>';
            } else {
              $button_up = '<i class="fa fa-chevron-circle-up fa-lg text-muted"></i>';
            }

            if ($module->sort_order < $group_key['count_installed']) {
              $button_down = '<a href="javascript:SortModule(\'' . $module->code . '\',' . ($module->sort_order +1) . ',\'' . $group_item .'\', \'' . $type . '\')"><i class="fa fa-chevron-circle-down fa-lg text-primary"></i></a>';
            } else {
              $button_down = '<i class="fa fa-chevron-circle-down fa-lg text-muted"></i>';
            }
//            }
?>
              <td class="text-center" nowrap><?= $button_up  . '&nbsp;<span id="sort-' . $module->sort_order . '">' . $module->sort_order . '</span>&nbsp;' . $button_down ?></td>
              <td id="toggler_<?= $type ?>_<?= $module->code ?>" class="text-center">
<?php
/// end sort


            if (method_exists($module, "toggle")) {
            // module can be toggled

              if ($module->enabled) {
  ?>
                    <i class="fas fa-circle fa-lg text-success"></i>&nbsp;&nbsp;<a href="javascript:ToggleModule('<?= $module->code ?>', '<?= $type ?>');" data-toggle="tooltip"><i class="far fa-circle fa-lg text-danger"></i></a>
<?php
              } else {
  ?>
                    <a href="javascript:ToggleModule('<?= $module->code ?>', '<?= $type ?>');" data-toggle="tooltip"><i class="far fa-circle fa-lg text-success"></i></a>&nbsp;&nbsp;<i class="fas fa-circle fa-lg text-danger"></i>
<?php
              }
            } else {
          // module cannot be toggled
              if ($module->enabled) {
  ?>
                    <i class="fas fa-circle fa-lg text-success"></i>
<?php
              } else {
  ?>
                    <i class="fas fa-circle fa-lg text-danger"></i>
<?php
              }

            }
?>              </td>
              <td class="actions">
                <a href="<?=  tep_href_link('modules_pages.php', 'module=' . $module->code . '&action=remove&type=' . $type . '&desired_groups=' . $_GET['desired_groups'])?>" title="<?= IMAGE_MODULE_REMOVE ?>"  data-toggle="tooltip"><i class="fa fa-trash fa-lg text-danger"></i></a>
              </td>
            </tr>
<?php
          }
        }
?>
            </tbody>
          </table>

<?php
      } else {
?>
            <div class="alert alert-warning"><?= WARNING_NO_INSTALLED_MODULES ?></div>
<?php
      }

      if ($group_key['count_uninstalled'] > 0) {
?>
            <table class="table table-sm table-striped table-hover">
              <thead>
              <tr class="table-info">
                <th><?= TABLE_HEADING_MODULES_UNINSTALLED ?></th>
                <th><?= TABLE_HEADING_DESCRIPTION ?></th>
                <th class="actions" colspan="3"><?php echo TABLE_HEADING_ACTION ?></th>
              </tr>
              </thead>
              <tbody>
<?php
        foreach (${'modules_' . $type}['new'] as $m) {
          if($m['group'] == $group_item) {
            $module = new $m['code']();

            if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $module->code))) && !isset($mInfo)) {
              $module_info = array('code' => $module->code,
                                   'title' => $module->title,
                                   'description' => $module->description,
                                   'signature' => (isset($module->signature) ? $module->signature : null),
                                   'api_version' => (isset($module->api_version) ? $module->api_version : null));

              $mInfo = new objectInfo($module_info);
            }
            
            if (isset($mInfo) && is_object($mInfo) && ($module->code == $mInfo->code) ) {
?>
              <tr class="clickable table-success">
<?php
            } else {
?>
              <tr class="clickable"">
<?php
            }
?>
                <td><?php echo $module->title; ?></td>
                <td><?php echo $module->description; ?></td>
                <td class="actions">
                  <a href="<?= tep_href_link('modules_pages.php',tep_get_all_get_params(array ('action','module')) . 'action=modules_pages_install&module=' . $module->code  . '&type=' . $type)?>" title="<?= IMAGE_MODULE_INSTALL ?>" data-toggle="tooltip"><i class="fa fa-plus-circle fa-lg text-info"></i></a>
                </td>
              </tr>
<?php
          }
        }
?>
              </tbody>
            </table>
<?php
      } else {
?>
            <div class="alert alert-warning"><?= WARNING_NO_UNINSTALLED_MODULES ?></div>
<?php
      }
?>
          </div>
        </div>
      </div>
<?php
      $first="";
    }
  }
?>
    </div>
  </div>
<?php
  } // end foreach type
?>
</div>
<?php

    function _sortModulesInstalled($a, $b) {
      return strnatcmp($a['group'] . '-' . (int)$a['sort_order'] . '-' . $a['title'], $b['group'] . '-' . (int)$b['sort_order'] . '-' . $b['title']);
    }

    function _sortModuleFiles($a, $b) {
      return strnatcmp($a['group'] . '-' . $a['title'], $b['group'] . '-' . $b['title']);
    }


  require('includes/template_bottom.php');
?>
<script>
function ToggleModule (module, group) {

  var params = {"module" : module, "action" : "toggle", 'type' : group};
  var cell = "#toggler_" + group + "_" + module;

  $.ajax({
    data:  params,
    url: 'modules_pages.php',
    type: 'get',
    cache: false,
    beforeSend: function () {
    },
    success:  function (response) {
      $(cell).html($(response).filter('#toggler').html());
      //  $("#toggler_" + type + "_" + module).append(response).find('#toggler');
       if ($(response).filter('#muted').html() == "false") {
         $(cell).parent().addClass('text-muted');
         console.log ("false");
       } else {
         $(cell).parent().removeClass('text-muted');

       }
    }
  });
}
function SortModule (module, position, group, type) {

  var params = { "action" : "reorder", "module" : module,  "position": position, "type": type, "desired_groups" : <?= json_encode($_GET['desired_groups']) ?>};

  $.ajax({
    data:  params,
    url: 'modules_pages.php',
    type: 'get',
    cache: false,
    beforeSend: function () {
    },
    success:  function (response) {
      $("#table-" + type + '-' + group).html($(response).find("#table-" + type + '-' + group).html());
    }
  });
}

</script>
<?php
  require('includes/application_bottom.php');
  