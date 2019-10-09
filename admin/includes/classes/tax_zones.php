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
    }
    
    function get_tax_zones () {
      $zones_query = tep_db_query("
      select gz.geo_zone_id, gz.geo_zone_name, 
      a.zone_country_id, c.countries_name, c.countries_iso_code_2,
      a.zone_id, z.zone_name, 
      a.association_id 
      from zones_to_geo_zones a left join countries c on a.zone_country_id = c.countries_id left join zones z on a.zone_id = z.zone_id right join geo_zones gz on gz.geo_zone_id = a.geo_zone_id ");
      
      while ($zones = tep_db_fetch_array($zones_query)) {

        $this->tax_zones [$zones['geo_zone_id']]['geo_zone_name'] =  $zones['geo_zone_name'];
        if ($zones['zone_country_id']) {
          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_name'] =   $zones['countries_name'];
          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_iso_code_2'] =   $zones['countries_iso_code_2'];
        } else {
          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'] = array();
        }
          
          
          
         ;
        if ($zones['zone_country_id']) {
          if (!$zones['zone_name']) $zones['zone_name'] = TEXT_ALL_ZONES;
          $this->tax_zones [$zones['geo_zone_id']]['geo_zone_countries'][$zones['zone_country_id']]['country_zones'][$zones['zone_id']] =  array('zone_name' => $zones['zone_name'], 'association_id' => $zones['association_id']);
        };
      }
      return $this->tax_zones;
    }
  }