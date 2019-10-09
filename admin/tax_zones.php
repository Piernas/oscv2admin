<?php
/*
 *
 * tax_zones.php
 *
 * Tax zones setup page
 *
 * Copyright 2019 Juan Manuel de Castro
 * Released under the GPL v3.0 License
 *
 *
 *
 */


  require('includes/application_top.php');

  require('includes/classes/tax_zones.php');
  $tax_zones_class = new tax_zones ();
  $tax_zones_array = $tax_zones_class->tax_zones;

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

     case 'zone_delete':
        $association_id = (int)$_GET['association_id'];
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_ZONE . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo '<p>' . TEXT_INFO_DELETE_ZONE_INTRO . '</p>' . PHP_EOL;
        echo '</span>';
        exit();
        break;

      case 'zone_delete_confirm':
        $association_id = (int)$_GET['association_id'];
//        $countres_array = $tax_zones_class->get_countries_list ();
//        $country_name = $countres_array[$country_id];
        $tax_zones_class->remove_zone ($association_id);
        $messageStack->add_session(sprintf (MESSAGE_ZONE_DELETED),'success');
        tep_redirect(tep_href_link('tax_zones.php'));
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
            <?= tep_draw_button(TEXT_BUTTON_NEW_TAX_ZONE, 'fas fa-plus-circle', null, null, array('type' => 'button', 'params' => 'onclick="javascript:Modal();"'), 'btn-info btn-sm') ?>
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
    <div class="card-header tab-heading">
          <ul id="contentmoduleTabsMain" class="nav nav-tabs  card-header-tabs">
<?php

  $active=' active';

  foreach ($tax_zones_array as $tax_zone_id => $tax_zones ) {

    if (!$tax_zones_array[$tax_zone_id]['geo_zone_countries']) {
      $warning ='<span class="fas fa-exclamation-triangle text-warning pr-2"></span>';
    } else {
      $warning ="";
    }


?>
            <li class="nav-item"><a class="nav-link<?= $active ?>" data-target ="#section_<?= $tax_zone_id?>" data-toggle="tab"><?= $warning ?><?= $tax_zones['geo_zone_name'] ?></a></li>
<?php
    $active="";
  }
?>
          </ul>
        </div>
    <div class="card-body tab-content" id="page-content">

<?php

  $active='show active';

  foreach ($tax_zones_array as $tax_zone_id => $tax_zones ) {

?>

        <div id="section_<?= $tax_zone_id ?>" class="tab-pane <?= $active ?>">
        
          <div class="d-flex justify-content-between">
          <div><h4 class="pr-3 align-middle"><?= $tax_zones['geo_zone_description'] ?></h4></div>
          <div>
          <button class="btn btn-success" type="button">Add* <i class="fas fa-plus-circle"></i></a></button>
          <button class="btn btn-info" type="button">Edit* <i class="fas fa-edit"></i></a></button>

          <button class="btn btn-danger" type="button">remove* <i class="fas fa-trash"></i></a></button>
          <?= tep_draw_button(IMAGE_REMOVE, 'fas fa-minus-circle fa-lg', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalAddCountry();"'), 'btn-danger') ?> 
          </div>
          </div>
<?php

      if (!$tax_zones_array[$tax_zone_id]['geo_zone_countries']) {
?>
      <div class="alert alert-warning"><?= MESSAGE_NO_COUNTRIES_IN_TAX_ZONE ?></div>
<?php
      }

    foreach ($tax_zones_array[$tax_zone_id]['geo_zone_countries'] as $country_key => $country_values) {

?>

          <hr>
          <div class="d-flex justify-content-between h5">

            <div><span class="flag-icon flag-icon-<?= strtolower( $country_values['country_iso_code_2']) ?> mr-3"></span><?= $country_values['country_name'] ?></div>
            <div>
            <?= tep_draw_button(IMAGE_REMOVE, 'fas fa-plus-circle fa-lg', null, null, array('type' => 'button', 'params' => 'onclick="javascript:ModalAddCountry();"'), 'btn-info btn-danger') ?>
            </div>
          </div>
          <table class="table table-sm">
            <thead>
              <tr class="table-info">
              <td><?= TABLE_HEADING_ID ?></td>
              <td><?= TABLE_HEADING_ZONE ?></td>
              <td class="actions"><?= TABLE_HEADING_ACTION ?></td>
              </tr>
            </thead>
          <tbody>
<?php

    foreach ($tax_zones_array[$tax_zone_id]['geo_zone_countries'][$country_key]['country_zones'] as $zone_key => $zone_values) {

// print_r ($zone_values);

?>
            <tr id="tax_zone_<?= $zone_values['association_id'] ?>">
              <td><?= $zone_key ?></td>
              <td><?= $zone_values['zone_name'] ?></td>
              <td class="actions"><a href="javascript:ModalDeleteTaxZone(<?= $zone_values['association_id'] ?>);"><i class="fas fa-trash fa-lg text-danger"></i></a></td>

            </tr>


<?php

    }
?>
            <tr class="table-info">
              <td></td>

              <td class="text-right">*Select more zones from this country</td>
              <td class="actions"><i class="fas fa-caret-square-down fa-lg text-primary"></i></a></td>

            </tr>
<?php
    $remaining_zones = $tax_zones_class->get_remaining_zones($country_key,$tax_zone_id);

    foreach ($remaining_zones as $zone_key => $zone_name) {
?>

            <tr class="table-warning unused-zone">
              <td><?= $zone_key ?></td>
              <td><?= $zone_name ?></td>
              <td class="actions"><a href="javascript:ModalAddZone(<?= $zone_values['association_id'] ?>);"><i class="fas fa-plus-circle fa-lg text-info"></i></a></td>

            </tr><?php
    }
?>
          </tbody>
          </table>

<?php
// echo "> " .count($tax_zones_array[$tax_zone_id]['geo_zone_countries']) . "<";



    }
?>
          </div>
<?php
    $active='';
  }
?>
          </div>





<?php

  //echo '<pre>';
//  print_r ( $tax_zones_class->tax_zones);
  //echo "</pre>";

  $stylesheet = '<link rel="stylesheet" href="' . tep_catalog_href_link ( 'ext/flag-icon-css/css/flag-icon.min.css') . '">' . PHP_EOL;
  $oscTemplate->addBlock($stylesheet, 'admin_footer_scripts');

  // Loads modal:
  require ("includes/classes/modal.php");

  $modal = new modal();
  $modal->button_save = true;
  $modal->button_delete = true;
  $modal->button_cancel = true;
  $modal->output();
?>
<script>
function ModalDeleteTaxZone(association_id){
  // Formulario
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="tax_zones" name="tax_zones" action="tax_zones.php?action=zone_delete_confirm&association_id=' + association_id + '" method="post">')

  // Botones
  $("#ModalButtonCancel").show();
  $("#ModalButtonSubmit").hide();
  $("#ModalButtonSave").hide();
  $("#ModalButtonDelete").show();


  var params = {"association_id" : association_id, "action" : "zone_delete"};
  $.ajax({
    data:  params,
    url:   'tax_zones.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#tax_zonesModal").modal('show');
    }
  });
}
</script>
<?php
  require('includes/template_bottom.php');

  require('includes/application_bottom.php');

