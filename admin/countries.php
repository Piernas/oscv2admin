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
  $country_data_array = $clsCountries->get_countries_details ();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? $_GET['page'] : '');
  if (tep_not_null($action)) {
    switch ($action) {


      case 'country_edit_form':
        $country_id = tep_db_prepare_input($_GET['country_id']);
        $country_data = $country_data_array[$country_id];
        $countries_controls = "";

        $address_format_array = $clsCountries->get_address_format_list();
//       print_r (   $address_format_array);
        $demo_data = array('name' => rtrim(ENTRY_NAME, ':'),
              'company' => rtrim(ENTRY_COMPANY, ':'),
              'street_address' => rtrim(ENTRY_STREET_ADDRESS, ':'),
               'suburb' => rtrim(ENTRY_SUBURB, ':'),
               'city' => rtrim(ENTRY_CITY, ':'),
               'postcode' => rtrim(ENTRY_POST_CODE, ':'),
               'state' => rtrim(ENTRY_STATE, ':'),
               'country' => rtrim(ENTRY_COUNTRY, ':'));
        $countries_controls = '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_NAME . '</label>' . tep_draw_input_field('countries_name', $country_data['countries_name'],'required="true"') . '</div>';
        $countries_controls .= '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_CODE_2 . '</label>' . tep_draw_input_field('countries_iso_code_2', $country_data['countries_iso_code_2'],'required="true"') . '</div>';
        $countries_controls .= '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_CODE_3 . '</label>' . tep_draw_input_field('countries_iso_code_3', $country_data['countries_iso_code_3'],'required="true"') . '</div>';
        $countries_controls .= '<div class="form-group" required="true"><label>' . TEXT_INFO_ADDRESS_FORMAT . '</label><select name="address_format_id" class="form-control form-control-sm">';
        foreach ($address_format_array as $id => $format) {
          $countries_controls .= '<option title="' . tep_address_format( $id , $demo_data, true, '', PHP_EOL) . '" value="' . $id;
          if ($country_data['countries_iso_code_3'] == $id) $countries_controls .= " selected";
          $countries_controls .= '" data-toggle="tooltip">' . sprintf (ENTRY_FORMAT_ADDRESS, $id) . '</option>'  . "_" ;
        }
        $countries_controls .= '</select></div>';
        $countries_controls .= tep_draw_hidden_field('country_id', $country_data['countries_id']);
        echo '<div id="title">' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</div>' . PHP_EOL;
        echo '<div id="content">' . $countries_controls . '</div>';

        exit();
        break;

      case 'country_edit':
        $country_id = (int)$_POST['country_id'];
        $countries_name = tep_db_prepare_input($_POST['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($_POST['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($_POST['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($_POST['address_format_id']);
        $clsCountries->edit_country($country_id, $countries_name, $countries_iso_code_2, $countries_iso_code_3, $address_format_id);
        tep_redirect(tep_href_link('countries.php'));
        break;

      case 'country_add_form':
        $countries_controls = "";

        $address_format_array = $clsCountries->get_address_format_list();
        $demo_data = array('name' => rtrim(ENTRY_NAME, ':'),
              'company' => rtrim(ENTRY_COMPANY, ':'),
              'street_address' => rtrim(ENTRY_STREET_ADDRESS, ':'),
               'suburb' => rtrim(ENTRY_SUBURB, ':'),
               'city' => rtrim(ENTRY_CITY, ':'),
               'postcode' => rtrim(ENTRY_POST_CODE, ':'),
               'state' => rtrim(ENTRY_STATE, ':'),
               'country' => rtrim(ENTRY_COUNTRY, ':'));
        $countries_controls = '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_NAME . '</label>' . tep_draw_input_field('countries_name', '','required="true"') . '</div>';
        $countries_controls .= '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_CODE_2 . '</label>' . tep_draw_input_field('countries_iso_code_2', '','required="true"') . '</div>';
        $countries_controls .= '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_CODE_3 . '</label>' . tep_draw_input_field('countries_iso_code_3', $country_data['countries_iso_code_3']) . '</div>';
        $countries_controls .= '<div class="form-group" required="true"><label>' . TEXT_INFO_ADDRESS_FORMAT . '</label><select name="address_format_id" class="form-control form-control-sm">';
        foreach ($address_format_array as $id => $format) {
          $countries_controls .= '<option title="' . tep_address_format( $id , $demo_data, true, '', PHP_EOL) . '" value="' . $id;
          $countries_controls .= '" data-toggle="tooltip">' . sprintf (ENTRY_FORMAT_ADDRESS, $id) . '</option>'  . "_" ;
        }
        $countries_controls .= '</select></div>';
        echo '<div id="title">' . TEXT_INFO_HEADING_NEW_COUNTRY . '</div>' . PHP_EOL;
        echo '<div id="content">' . $countries_controls . '</div>';

        exit();
        break;

      case 'country_add':
        $countries_name = tep_db_prepare_input($_POST['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($_POST['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($_POST['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($_POST['address_format_id']);
        $clsCountries->add_country($countries_name, $countries_iso_code_2, $countries_iso_code_3, $address_format_id);
        $messageStack->add_session(sprintf (MESSAGE_COUNTRY_ADDED, $countries_name),'success');

        tep_redirect(tep_href_link('countries.php?country_id=' . $zone_country_id));
        break;

      case 'country_edit':
        $country_id = (int)$_POST['country_id'];
        $countries_name = tep_db_prepare_input($_POST['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($_POST['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($_POST['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($_POST['address_format_id']);
        $clsCountries->edit_country($country_id, $countries_name, $countries_iso_code_2, $countries_iso_code_3, $address_format_id);
        tep_redirect(tep_href_link('countries.php'));
        break;

      case 'country_delete':
        $country_id = (int)$_GET['country_id'];
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo '<p>' . TEXT_INFO_DELETE_INTRO . '</p>' . PHP_EOL;
        echo '</span>';
        exit();
        break;

      case 'country_delete_confirm':
        $country_id = (int)$_GET['country_id'];
        $countres_array = $clsCountries->get_countries_list ();
        $country_name =$countres_array[$country_id];
        $clsCountries->remove_country ($country_id);
        $messageStack->add_session(sprintf (MESSAGE_COUNTRY_DELETED, $country_name),'success');
        tep_redirect(tep_href_link('countries.php'));
        break;
    }
  }

  require('includes/template_top.php');
?>

   <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto pageHeading"><i class="fas fa-users fa-lg"></i> <?= HEADING_TITLE ?></div>
          <div class="pr-2">
            <?= tep_draw_button(IMAGE_NEW_COUNTRY, 'fas fa-plus-circle', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalAddCountry();"'), 'btn-info btn-sm') ?>
          </div>
          <div>
            <?= tep_draw_form('search', basename($PHP_SELF),"" ,"get" ,'class="form-inline"') ?>
              <div class="form-group form-group-sm">
                <?= tep_draw_input_field('search', null, ' size="20" placeholder="' . HEADING_TITLE_SEARCH . '"') ?>
                <?= tep_draw_hidden_field ('cPath', $cPath); ?>
                <div class="input-group-append">
                <button class="btn btn-sm btn-info" disabled type="submit"><i class="fas fa-search"></i></button></div>
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
          <th><?= TABLE_HEADING_COUNTRY_NAME ?></th>
          <th class="text-center"><?= TABLE_HEADING_COUNTRY_CODE_2 ?></th>
          <th class="text-center"><?= TABLE_HEADING_COUNTRY_CODE_3 ?></th>
          <th class="text-center"><?= TABLE_HEADING_ADDRESS_FORMAT ?></th>
          <th class="actions"><?= TABLE_HEADING_ACTION ?></th>
        </tr>
        </thead>
        <tbody>
<?php
   $countries_query_raw = "select countries_id from " . TABLE_COUNTRIES . " order by countries_name";  $countries_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);

  while ($countries_list = tep_db_fetch_array($countries_query)) {

    $country = $country_data_array[$countries_list['countries_id']];

    if (isset($_GET['country_id']) && ($_GET['country_id'] == $country)) {
      echo "HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH";
    }

/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////

?>
              <tr class="clickable">
                <td><i class="flag-icon flag-icon-<?= strtolower($country['countries_iso_code_2']) ?> mr-2"></i><?php echo $country['countries_name']; ?></td>
                <td align="center"><?php echo $country['countries_iso_code_2']; ?></td>
                <td align="center"><?php echo $country['countries_iso_code_3']; ?></td>
                <td align="center"><?php echo $country['address_format_id']; ?></td>
                  <td class="actions">
                  <a href="javascript:ModalEditCountry(<?= $country['countries_id'] ?>);"><i class="fas fa-edit fa-lg text-primary"></i></a>
                  <a href="javascript:ModalDeleteCountry(<?= $country['countries_id'] ?>);"><i class="fas fa-trash fa-lg text-danger"></i></a>
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
  </div>
<?php

  require ("includes/classes/modal.php");

  $modal = new modal();
  $modal->button_save = true;
  $modal->button_submit = true;
  $modal->button_delete = true;
  $modal->button_cancel = true;
  $modal->output();

  $stylesheet = '<link rel="stylesheet" href="' . tep_catalog_href_link ( 'ext/flag-icon-css/css/flag-icon.min.css') . '">' . PHP_EOL;
  $oscTemplate->addBlock($stylesheet, 'admin_footer_scripts');

?>
<script>
function ModalEditCountry(country_id){
  // Formulario
  $("form > .modal-content").unwrap();
    $(".modal-content").wrap('<form id="countries" name="countries" action="countries.php?action=country_edit" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").show();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").hide();

  var params = {"action" : "country_edit_form", "country_id" : country_id};
  $.ajax({
    data:  params,
    url:   'countries.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#countriesModal").modal('show');

    }
  });
}

function ModalDeleteCountry(country_id){
  // Formulario
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="countries" name="countries" action="countries.php?action=country_delete_confirm&country_id=' + country_id + '" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").hide();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").show();


  var params = {"country_id" : country_id, "action" : "country_delete"};
  $.ajax({
    data:  params,
    url:   'countries.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#countriesModal").modal('show');

    }
  });
}
//
function ModalAddCountry(){
  // Formulario
  $("form > .modal-content").unwrap();
    $(".modal-content").wrap('<form id="countries" name="countries" action="countries.php?action=country_add" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").show();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").hide();

  var params = {"action" : "country_add_form"};
  $.ajax({
    data:  params,
    url:   'countries.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#countriesModal").modal('show')

    }
  });
}
</script>
<?php

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
