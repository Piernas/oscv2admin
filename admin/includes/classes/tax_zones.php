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

    function country_has_zones ($country_id) {
      $num_zones_query = tep_db_query ('SELECT COUNT(*) as num_zones FROM zones WHERE zone_country_id = ' . (int)$country_id);
      $num_zones = tep_db_fetch_array($num_zones_query);
      return $num_zones['num_zones'];
    }
    function zones_to_add () {

    }
  }