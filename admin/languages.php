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

  if (tep_not_null($action)) {
    switch ($action) {
/*

      case 'languages_install':
        $languages__installer = new languages_install ('');
        $languages__installer->install ($code);
        $name =($languages__installer->installed[$code]['name']);

        $messageStack->add_session(sprintf(TEXT_INFO_LANGUAGE_INSTALLED, $name), 'success');

        tep_redirect(tep_href_link('languages.php', 'code=' . $code));
      break;
       
      case 'languages_delete_confirm':
        $languages__installer = new languages_install ('');
        $languages__installer->uninstall ($code);
        $name =($languages__installer->installed[$code]['name']);
        
        $messageStack->add_session(sprintf(TEXT_INFO_LANGUAGE_REMOVED, $name ), 'success');
        tep_redirect(tep_href_link('languages.php'));
        break;
      case 'languages_delete':
        $lng_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . $code . "'");
        $lng = tep_db_fetch_array($lng_query);
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</strong></span>';
        echo '<span id="content">' .TEXT_INFO_DELETE_INTRO . PHP_EOL;
        echo '</span>';
        
        exit;
        
        break;*/
/*
      case 'languages_make_default':
        $code = tep_db_prepare_input($_GET['code']);
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
        tep_redirect(tep_href_link('languages.php', 'code=' . $code));
        break;
*/
    }

  }

  require('includes/template_top.php');
  require ('includes/classes/modules_pages.php');

  $languages_modules_installed = new modules_pages ('languages_installed');
  $oscTemplate->addBlock('<script>' . $languages_modules_installed->get_javascript() . '</script>', 'admin_footer_scripts');

?>
  <div class="d-flex justify-content-between">
    <div class="mr-auto p-2 pageHeading"><i class="fas fa-language fa-lg"></i> <?= HEADING_TITLE ?></div>

    <div class="py-2">
      <a href="<?= tep_href_link('modules_pages.php?desired_groups=' . urlencode (json_encode(array(array('languages'), array('languages_installed', 'languages_uninstalled'))))) ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a>

    </div>
  </div>

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
  
<?php
    }
/*
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</strong>');

      $contents = array('form' => tep_draw_form('languages', 'languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_NAME . '<br />' . tep_draw_input_field('name', $lInfo->name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_CODE . '<br />' . tep_draw_input_field('code', $lInfo->code));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_IMAGE . '<br />' . tep_draw_input_field('image', $lInfo->image));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br />' . tep_draw_input_field('directory', $lInfo->directory));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br />' . tep_draw_input_field('sort_order', $lInfo->sort_order));
      if (DEFAULT_LANGUAGE != $lInfo->code) $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id)));
      break;
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</strong>');

      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $lInfo->name . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . (($remove_language) ? tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link('languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm'), 'primary') : '') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id)));
      break;
    default:
      if (is_object($lInfo)) {
        $heading[] = array('text' => '<strong>' . $lInfo->name . '</strong>');

        $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link('languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link('languages.php', 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=delete')) . tep_draw_button(IMAGE_DETAILS, 'info', tep_href_link('define_language.php', 'lngdir=' . $lInfo->directory)));
        $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_CODE . ' ' . $lInfo->code);
        $contents[] = array('text' => '<br />' . tep_image(tep_catalog_href_link('includes/languages/' . $lInfo->directory . '/images/' . $lInfo->image, '', 'SSL'), $lInfo->name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br />' . DIR_WS_CATALOG_LANGUAGES . '<strong>' . $lInfo->directory . '</strong>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table>

<?php
*/

  require ("includes/classes/modal.php");
  
  $modal = new modal();
  $modal->button_delete = true;
  $modal->button_cancel = true;
  $modal->output();

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
