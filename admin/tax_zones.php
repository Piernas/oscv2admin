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

  require('includes/template_top.php');


  $tax_zones_class = new tax_zones ();
  $tax_zones_array = $tax_zones_class->tax_zones;


?>

   <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto pageHeading"><i class="fas fa-users fa-lg"></i> <?= HEADING_TITLE ?></div>
          <div class="pr-2">
            <?= tep_draw_button('', 'fas fa-plus-circle', null, null, array('type' => 'button', 'params' => 'onclick="javascript:Modal();"'), 'btn-info btn-sm') ?>
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
?>
            <li class="nav-item"><a class="nav-link<?= $active ?>" data-target ="#section_<?= $tax_zone_id ?>" data-toggle="tab"><?= $tax_zones['geo_zone_name'] ?></a></li>
<?php
    $active="";
/////////    print_r ($tax_zones_array[$tax_zone_id]['geo_zone_countries']);
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
<?php

      if (!$tax_zones_array[$tax_zone_id]['geo_zone_countries']) {
?>
      <div class="alert alert-warning"><?= MESSAGE_NO_COUNTRIES_IN_TAX_ZONE ?></div>
<?php
      }

    foreach ($tax_zones_array[$tax_zone_id]['geo_zone_countries'] as $country_key => $country_values) {

?>
  <h3><span class="flag-icon flag-icon-<?= strtolower( $country_values['country_iso_code_2']) ?> mr-3"></span><?= $country_values['country_name'] ?></h3>
          <table class="table table-sm">
            <thead>
              <tr class="table-info">
              <td><?= TABLE_HEADING_ZONE ?></td>
              <td class="actions"><?= TABLE_HEADING_ACTION ?></td>
              </tr>
            </thead>
          <tbody>
<?php



    foreach ($tax_zones_array[$tax_zone_id]['geo_zone_countries'][$country_key]['country_zones'] as $zone_key => $zone_values) {

?>
            <tr>
              <td><?= $zone_values['zone_name'] ?></td>
              <td class="actions"><a href="javascript:ModalDeleteCountry(<?= $country['countries_id'] ?>);"><i class="fas fa-trash fa-lg text-danger"></i></a></td>

            </tr>
          
          
<?php
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



  require('includes/template_bottom.php');

  require('includes/application_bottom.php');

