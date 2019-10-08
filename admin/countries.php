<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/classes/countries.php');
  $clsCountries = new countries();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? $_GET['page'] : '');
  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $countries_name = tep_db_prepare_input($_POST['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($_POST['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($_POST['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($_POST['address_format_id']);

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . (int)$address_format_id . "')");

        tep_redirect(tep_href_link('countries.php'));
        break;
      case 'save':
        $countries_id = tep_db_prepare_input($_GET['cID']);
        $countries_name = tep_db_prepare_input($_POST['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($_POST['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($_POST['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($_POST['address_format_id']);

        tep_db_query("update " . TABLE_COUNTRIES . " set countries_name = '" . tep_db_input($countries_name) . "', countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) . "', address_format_id = '" . (int)$address_format_id . "' where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        $countries_id = tep_db_prepare_input($_GET['cID']);

        tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link('countries.php', 'page=' . $_GET['page']));
        break;
    }
  }

  require('includes/template_top.php');
?>

   <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto pageHeading"><i class="fas fa-users fa-lg"></i> <?= HEADING_TITLE ?></div>
          <div class="pr-2"><?php if (empty($action)) echo tep_draw_button(IMAGE_NEW_COUNTRY, 'fas fa-plus-circle', tep_href_link('countries.php', tep_get_all_get_params(array( 'action')) . 'action=new')); ?></div>
        <div>
          <?= tep_draw_form('search', basename($PHP_SELF),"" ,"get" ,'class="form-inline"') ?>
            <div class="form-group form-group-sm">
              <?= tep_draw_input_field('search', null, ' size="20" placeholder="' . HEADING_TITLE_SEARCH . '"') ?>
              <?= tep_draw_hidden_field ('cPath', $cPath); ?>
              <div class="input-group-append"><button class="btn btn-sm btn-info" disabled type="submit"><i class="fas fa-search"></i></button></div>
            </div>
            <?= tep_hide_session_id() ?>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body" id="page-content">
      <table class="table table-sm table-striped table-hover">
        <thead>
        <tr class="table-info">
          <th><?php echo TABLE_HEADING_COUNTRY_NAME; ?></th>
          <th class="text-center">ISO CODE 2</th>
          <th class="text-center">ISO CODE 3</th>
          <th class="actions"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<?php
   $countries_query_raw = "select countries_id from " . TABLE_COUNTRIES . " order by countries_name";  $countries_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);
  $country_data_array = $clsCountries->get_countries_details ();
  while ($countries_list = tep_db_fetch_array($countries_query)) {    
/*  
  if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $countries['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
  
  $cInfo = new objectInfo($countries);
    }

    if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $countries['countries_id']) . '\'">' . "\n";
    }
*/    
       $country = $country_data_array[$countries_list['countries_id']];


?>
              <tr class="clickable">
                <td><?php echo $country['countries_name']; ?></td>
                <td align="center"><?php echo $country['countries_iso_code_2']; ?></td>
                <td align="center"><?php echo $country['countries_iso_code_3']; ?></td>
                  <td class="actions">
                  <a href="javascript:ModalCountryEdit(<?= $country['countries_id'] ?>);"><i class="fas fa-edit fa-lg text-primary"></i></a>
                  <a href="javascript:ModalCountryDelete(<?= $country['countries_id'] ?>);"><i class="fas fa-trash fa-lg text-danger"></i></a>
                  </td>
              </tr>
<?php
  }
  
  
  
?>
               </tbody>
            </table>
    </div>
    <div class="card-footer">
      <?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?>
      <?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>
    </div>
  </div>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</strong>');

      $contents = array('form' => tep_draw_form('countries', 'countries.php', 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . tep_draw_input_field('countries_name'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . '<br />' . tep_draw_input_field('countries_iso_code_2'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . '<br />' . tep_draw_input_field('countries_iso_code_3'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . '<br />' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats()));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('countries.php', 'page=' . $_GET['page'])));
      break;
    case 'edit':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</strong>');

      $contents = array('form' => tep_draw_form('countries', 'countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . tep_draw_input_field('countries_name', $cInfo->countries_name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . '<br />' . tep_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . '<br />' . tep_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . '<br />' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats(), $cInfo->address_format_id));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id)));
      break;
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</strong>');

      $contents = array('form' => tep_draw_form('countries', 'countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $cInfo->countries_name . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id)));
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<strong>' . $cInfo->countries_name . '</strong>');

        $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link('countries.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=delete')));
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . $cInfo->countries_name);
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
        $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
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
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
