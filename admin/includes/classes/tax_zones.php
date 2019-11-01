<?php
/*
 *
 * geo_zones.php
 *
 * Class for handling tax zones
 *
 * Copyright 2019 Juan Manuel de Castro
 * Released under the GPL v3.0 License
 *
 *
 *
 */

  class tax_zones {

    function __construct () {
      $this->get_tax_zones();
      $this->get_zone_names();
    }

    function get_tax_zones () {
      $zones_query = tep_db_query("
        SELECT gz.geo_zone_id, gz.geo_zone_name, gz.geo_zone_description,
        a.zone_country_id, c.countries_name, c.countries_iso_code_2,
        a.zone_id, z.zone_name,
        a.association_id
        FROM zones_to_geo_zones a
        LEFT JOIN countries c on a.zone_country_id = c.countries_id
        LEFT JOIN zones z on a.zone_id = z.zone_id
        RIGHT JOIN geo_zones gz
        ON gz.geo_zone_id = a.geo_zone_id ");

      while ($zones = tep_db_fetch_array($zones_query)) {

        $this->tax_zones [$zones['geo_zone_id']]['geo_zone_name'] =  $zones['geo_zone_name'];
        $this->tax_zones [$zones['geo_zone_id']]['geo_zone_description'] =  $zones['geo_zone_description'];
        if ($zones['zone_country_id']) {

          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_name'] =   $zones['countries_name'];

          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_iso_code_2'] =   $zones['countries_iso_code_2'];

          if (!$zones['zone_name']) $zones['zone_name'] = TEXT_ALL_ZONES;

          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_zones'][$zones['zone_id']] =  array('zone_name' => $zones['zone_name'], 'association_id' => $zones['association_id']);

        } else {
          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'] = array();
        }

      }
      return $this->tax_zones;
    }

    function get_zone_names () {
      $zone_names_query = tep_db_query ('SELECT zone_id, zone_name FROM zones ORDER BY zone_id');
      $this->zone_names_array[0] = TEXT_ALL_ZONES;
      while ($zone_names = tep_db_fetch_array($zone_names_query)) {
        $this->zone_names_array[$zone_names['zone_id']] = $zone_names['zone_name'];
      }
      return $this->zone_names_array;
    }

    function get_remaining_zones ($country_id, $tax_zone_id) {
      $returned = array();

      $not_present_query = tep_db_query ('SELECT zone_id, zone_country_id
              FROM
               (
                 SELECT zones_to_geo_zones.zone_id, zones_to_geo_zones.zone_country_id
                 FROM zones_to_geo_zones
                   WHERE zones_to_geo_zones.geo_zone_id = ' . (int)$tax_zone_id . '
                   AND zone_country_id = ' . (int)$country_id . '
                 UNION ALL
                 SELECT zones.zone_id, zones.zone_country_id
                   FROM zones
                      WHERE zones.zone_country_id = ' . (int)$country_id . '
              )  t
              GROUP BY zone_id, zone_country_id
              HAVING COUNT(*) = 1
              ORDER BY zone_id');

      while($zones = tep_db_fetch_array($not_present_query)){
        if ($zones['zone_id'] != 0) {
          $returned [$zones['zone_id']] = $this->zone_names_array [$zones['zone_id']];
        }
      }

      return $returned;
    }

    function remove_zone ($association_id) {
      tep_db_query("delete from zones_to_geo_zones where association_id = '" . (int)$association_id . "'");
    }

    function add_tax_zone ($tax_zone_name, $tax_zone_description) {
      $tax_zone_name = tep_db_prepare_input ($tax_zone_name);
      $tax_zone_description = tep_db_prepare_input($tax_zone_description);

      tep_db_query("insert into " . TABLE_GEO_ZONES . " (geo_zone_name, geo_zone_description, date_added) values ('" . tep_db_input($tax_zone_name) . "', '" . tep_db_input($tax_zone_description) . "', now())");
//      $new_zone_id = tep_db_insert_id();
    }

    function add_zone_to_tax_zone ($zone_id, $tax_zone_id) {
        $tax_zone_id = tep_db_prepare_input($tax_zone_id);
        $country_id = tep_db_prepare_input($country_id);
        $zone_id = tep_db_prepare_input($zone_id);

        tep_db_query("insert into " . TABLE_ZONES_TO_GEO_ZONES . " (zone_country_id, zone_id, geo_zone_id, date_added) values ('" . (int)$country_id . "', '" . (int)$zone_id . "', '" . (int)$tax_zone_id . "', now())");
//        $new_subzone_id = tep_db_insert_id();

    }

    function country_has_zones ($country_id) {
      $num_zones_query = tep_db_query ('SELECT COUNT(*) as num_zones FROM zones WHERE zone_country_id = ' . (int)$country_id);
      $num_zones = tep_db_fetch_array($num_zones_query);
      return $num_zones['num_zones'];
    }
  }



























  
  function ztep_prepare_country_zones_pull_down($country_id = '') {


    $zones = ztep_get_country_zones($country_id);

    if (sizeof($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
    }

    return $zones;
  }

// return an array with country zones
  function ztep_get_country_zones($country_id) {
    $zones_array = array();
    $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);
    }

    return $zones_array;
  }
  
  
  ////
// Returns an array with countries
// TABLES: countries
  function ztep_get_countries($default = '') {
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => '',
                                 'text' => $default);
    }
    $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries = tep_db_fetch_array($countries_query)) {
      $countries_array[] = array('id' => $countries['countries_id'],
                                 'text' => $countries['countries_name']);
    }

    return $countries_array;
  }
/*
////
// Alias function for Store configuration values in the Administration Tool
  function ztep_cfg_pull_down_country_list($country_id) {
    return tep_draw_pull_down_menu('configuration_value', tep_get_countries(), $country_id);
  }

  function ztep_cfg_pull_down_zone_list($zone_id) {
    return tep_draw_pull_down_menu('configuration_value', tep_get_country_zones(STORE_COUNTRY), $zone_id);
  }

  function ztep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }

    return tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
  }
*/