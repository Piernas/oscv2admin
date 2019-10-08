<?php
/* 
 * 
 * Copyright 2019 Juan Manuel de Castro
 * Released under the GPL v3.0 License
 * 
 */

  require('includes/application_top.php');
  require('includes/classes/countries.php');
  
  $zones = new countries ();
  $countries_with_zones = $zones->get_countries_with_zones();

  if (isset($_GET['country_id'])) echo "<script>window.onload = function() {localStorage.removeItem('lastTab')};;</script>";

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action)) {
    switch ($action) {
      
      case 'zone_add_get_form':
      
        $zones_countries = $zones->get_no_zone_countries();
        
        foreach ($zones_countries as $id => $text) {
          $countries_array [] = array ('id' => $id, 'text' => $text);
        }
        
        $zones_controls = '<div class="form-group"><label>' . TEXT_INFO_ZONES_NAME . '</label>' . tep_draw_input_field('zone_name', '','required="true"') . '</div>';
        $zones_controls .= '<div class="form-group"><label>' . TEXT_INFO_ZONES_CODE . '</label>' . tep_draw_input_field('zone_code', '') . '</div>';
        $zones_controls .= '<div class="form-group"><label>' . TEXT_INFO_COUNTRY_NAME . '</label>' . tep_draw_pull_down_menu('zone_country_id', $countries_array) . '</div>';;
        echo '<div id="title">' . TEXT_INFO_HEADING_NEW_ZONE . '</div>' . PHP_EOL;
        echo '<div id="content">' . $zones_controls . '</div>';

        exit();
        break;
      
      case 'zone_add':
        $zone_name = tep_db_prepare_input($_POST['zone_name']);
        $zone_code = tep_db_prepare_input($_POST['zone_code']);
        $zone_country_id = (int)$_POST['zone_country_id'];
        $zones->add_zone($zone_name, $zone_code, $zone_country_id);
        $messageStack->add_session(sprintf (MESSAGE_ZONE_ADDED, $zone_name),'success');

        tep_redirect(tep_href_link('zones.php?country_id=' . $zone_country_id));
        break;
      
      case 'zone_add_to_country_get_form':
        $country_id = (int)$_GET['country_id'];
        $zones_controls = '<div class="form-group"><label>' . TEXT_INFO_ZONES_NAME . '</label>' . tep_draw_input_field('zone_name', '','required="true"') . '</div>';
        $zones_controls .= '<div class="form-group"><label>' . TEXT_INFO_ZONES_CODE . '</label>' . tep_draw_input_field('zone_code', '') . '</div>';
        $zones_controls .= tep_draw_hidden_field('zone_country_id', $country_id);

        echo '<div id="title">' . TEXT_INFO_HEADING_NEW_ZONE . '</div>' . PHP_EOL;
        echo '<div id="content">' . $zones_controls . '</div>';

        exit();
        break;


      case 'zone_edit_get_form':
        $zone_id = tep_db_prepare_input($_GET['zone_id']);
        $zone_data = $zones->get_zone_data($zone_id);
        $zones_controls = "";
        
        $zones_controls .= '<div class="form-group"><label>' . TEXT_INFO_ZONES_NAME . '</label>' . tep_draw_input_field('zone_name', $zone_data['zone_name'],'required="true"') . '</div>';
        $zones_controls .= '<div class="form-group"><label>' . TEXT_INFO_ZONES_CODE . '</label>' . tep_draw_input_field('zone_code', $zone_data['zone_code']) . '</div>';
        $zones_controls .= tep_draw_hidden_field('zone_country_id', $zone_data['zone_country_id']);
        $zones_controls .= tep_draw_hidden_field('zone_id', $zone_data['zone_id']);
        echo '<div id="title">' . TEXT_INFO_HEADING_EDIT_ZONE . '</div>' . PHP_EOL;
        echo '<div id="content">' . $zones_controls . '</div>';

        exit();
        
        break;

      case 'zone_edit':
        $zone_id = (int)$_POST['zone_id'];
        $zone_country_id = (int)$_POST['zone_country_id'];
        $zone_name = tep_db_prepare_input($_POST['zone_name']);
        $zone_code = tep_db_prepare_input($_POST['zone_code']);
        $zones->edit_zone($zone_id, $zone_name, $zone_code, $zone_country_id);
        tep_redirect(tep_href_link('zones.php'));
        break;

      case 'zone_delete':
        $zone_id = (int)$_GET['zone_id'];
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_ZONE . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo '<p>' . TEXT_INFO_DELETE_INTRO . '</p>' . PHP_EOL;
        echo '</span>';
        exit();
        break;

      case 'zone_delete_all':
        $country_id = (int)$_GET['country_id'];
        $countries_array = $zones->get_countries_list();
        $country_name = $countries_array [$country_id];
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . sprintf(TEXT_INFO_HEADING_REMOVE_ALL_ZONES, $country_name) . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo '<p>' . sprintf(TEXT_INFO_DELETE_ALL_INTRO, $country_name) . '</p>' . PHP_EOL;
        echo '</span>';
        exit();
        break;


      case 'zone_delete_confirm':
        $zone_id = tep_db_prepare_input($_GET['zone_id']);
        $country_id = (int)$_GET['country_id'];
        $zones->remove_zone ($zone_id);
        $messageStack->add_session(sprintf (MESSAGE_ZONE_DELETED),'success');

        if(in_array ($country_id,$zones->countries_with_zones)){
          tep_redirect(tep_href_link('zones.php?country_id=' . $country_id));
        } else {
          tep_redirect(tep_href_link('zones.php'));
        }
        break;

      case 'zone_delete_all_confirm':
      
        $country_id = (int)$_GET['country_id'];
        $countries_array = $zones->get_countries_list();
        $country_name = $countries_array [$country_id];
        $zones->remove_all_country_zones ($country_id);
        $messageStack->add_session(sprintf (MESSAGE_ZONES_DELETED, $country_name),'success');

        tep_redirect(tep_href_link('zones.php'));
        break;

    }
  }


  require('includes/template_top.php');
?>

  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto pageHeading"><i class="fas fa-cogs"></i>&nbsp;<?= HEADING_TITLE ?></div>
        <div><?= tep_draw_button(TEXT_INFO_HEADING_NEW_ZONE_COUNTRY, 'fas fa-plus-circle', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalAddZone();"'), 'btn-info brn-sm') ?></div>
      </div>
    </div>

    <div class="card-header" id="page-heading">
      <ul id="ColumnsTabsMain" class="nav nav-tabs card-header-tabs">
<?php

  if (!isset($_GET['country_id'])) {
      $active = ' active';
      
  } else {
    $active = '';    
  }
  
  foreach ($countries_with_zones as $country_id => $country_name) {

    if (isset($_GET['country_id']) && $country_id == $_GET['country_id']) $active = ' active';

?>
        <li class="nav-item"><a class="nav-link<?= $active ?>" data-target ="#section_<?= $country_id ?>" data-toggle="tab"><?= $country_name ?></a></li>
<?php
    $active = "";
  }
?>
      </ul>
    </div>
    <div class="card-body" id="page-content">
      <div class="tab-content">
<?php

    if (!isset($_GET['country_id'])) {
      $active = ' active';
    } else {
      $active = '';    
    }
  
    foreach ($countries_with_zones as $country_id => $country_name) {
      $zones_array = $zones->get_country_zones($country_id);


      if (isset($_GET['country_id']) && $country_id == $_GET['country_id']) $active = ' active';

?>
        <div id="section_<?= $country_id ?>" class="tab-pane<?= $active ?>">
          <table class="table table-sm table-striped table-hover" id="table-$country_id">
            <thead>
            <tr class="table-info">
              <th><?= TABLE_HEADING_ZONE_NAME ?></th>
              <th><?= TABLE_HEADING_ZONE_CODE ?></th>
              <th class="actions"><?= TABLE_HEADING_ACTION; ?></th>
            </tr>
            </thead>
            <tbody>
<?php
    foreach ($zones_array as $zone_id => $zone_properties) {
?>
            <tr class="clickable">
                <td><?= $zone_properties['name'] ?></td>
                <td><?= $zone_properties['code'] ?></td>
                <td class="actions">
                  <a href="javascript:ModalEditZone('<?= $zone_id ?>');" title="<?= TEXT_INFO_HEADING_EDIT_ZONE ?>" data-toggle="tooltip"><i class="fas fa-edit text-info fa-lg"></i></a>
                  <a href="javascript:ModalDeleteZone('<?= $zone_id ?>', '<?= $country_id ?>');" title="<?= TEXT_INFO_HEADING_DELETE_ZONE ?>" data-toggle="tooltip"><i class="fas fa-trash text-danger fa-lg"></i></a>
                </td>
            </tr>
<?php
    }
?>
            </tbody>
          </table>
          <div>
            <div class=" float-right">
            <?= tep_draw_button(sprintf(TEXT_INFO_HEADING_NEW_ZONE_IN_COUNTRY, $country_name), 'fas fa-plus-circle', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalAddZoneToCountry(\'' . $country_id . '\');"'), 'btn-info brn-sm') ?>
            <?= tep_draw_button(sprintf(TEXT_INFO_HEADING_REMOVE_ALL_ZONES, $country_name), 'fas fa-trash', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalDeleteAllCountryZones(\'' . $country_id . '\');"'), 'btn-danger brn-sm mr-2') ?>
            </div>
          </div>
        </div>
<?php

    $active = "";

  }
  
?>
      </div>
    </div>
  </div>
<script>
function ModalDeleteAllCountryZones (country_id){

console.log("999");
  // Formulario
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="zones" name="zones" action="zones.php?action=zone_delete_all_confirm&country_id=' + country_id + '" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").hide();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").show();


  var params = {"country_id" : country_id, "action" : "zone_delete_all"};
  $.ajax({
    data:  params,
    url:   'zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#zonesModal").modal('show');

    }
  });
}

function ModalAddZoneToCountry(country_id){

  // Formulario
  $("form > .modal-content").unwrap();
    $(".modal-content").wrap('<form id="zones" name="zones" action="zones.php?action=zone_add" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").show();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").hide();

  var params = {"action" : "zone_add_to_country_get_form", "country_id" : country_id};
  $.ajax({
    data:  params,
    url:   'zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#zonesModal").modal('show')

    }
  });
}

function ModalAddZone(){
  // Formulario
  $("form > .modal-content").unwrap();
    $(".modal-content").wrap('<form id="zones" name="zones" action="zones.php?action=zone_add" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").show();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").hide();

  var params = {"action" : "zone_add_get_form"};
  $.ajax({
    data:  params,
    url:   'zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#zonesModal").modal('show')

    }
  });
}
function ModalEditZone(zone_id){
  // Formulario
  $("form > .modal-content").unwrap();
    $(".modal-content").wrap('<form id="zones" name="zones" action="zones.php?action=zone_edit" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").show();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").hide();

  var params = {"action" : "zone_edit_get_form", "zone_id" : zone_id};
  $.ajax({
    data:  params,
    url:   'zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#zonesModal").modal('show');

    }
  });
}
function ModalDeleteZone(zone_id, country_id){
  // Formulario
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="zones" name="zones" action="zones.php?action=zone_delete_confirm&zone_id=' + zone_id + '&country_id=' + country_id + '" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").hide();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").show();


  var params = {"zone_id" : zone_id, "action" : "zone_delete"};
  $.ajax({
    data:  params,
    url:   'zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#zonesModal").modal('show');

    }
  });
}
</script>
<?php

  require ("includes/classes/modal.php");
  
  $modal = new modal();
  $modal->button_save = true;
  $modal->button_submit = true;
  $modal->button_delete = true;
  $modal->button_cancel = true;
  $modal->output();
?>



<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
