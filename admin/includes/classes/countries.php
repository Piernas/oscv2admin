<?php
/*
 *
 * countries.php
 *
 * General purpose class for handling countries and zones
 *
 * Copyright 2019 Juan Manuel de Castro
 * Released under the GPL v3.0 License
 *
 *
 *
 */
  class countries {

/*
 * COUNTRIES METHODS
*/

    ////
    // Returns an array with al countries id and names
    function get_countries_list () {
      $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries = tep_db_fetch_array($countries_query)) {
       $countries_list[$countries['countries_id']] = $countries['countries_name'];
      }
      return $countries_list;
    }

    function get_countries_details () {
      $countries_query = tep_db_query("select * from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries = tep_db_fetch_array($countries_query)) {
       $countries_list[$countries['countries_id']] = array('countries_id' =>$countries['countries_id'],
                                                           'countries_name' =>$countries['countries_name'],
                                                           'countries_iso_code_2' =>$countries['countries_iso_code_2'],
                                                           'countries_iso_code_3' =>$countries['countries_iso_code_3'],
                                                           'address_format_id' =>$countries['address_format_id']
                                                          );
      }
      return $countries_list;
    }
    
    ////
    // Edits a country:
    function edit_country ($countries_id, $countries_name, $countries_iso_code_2, $countries_iso_code_3, $address_format_id) {
      tep_db_query("update " . TABLE_COUNTRIES . " set countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) . "', address_format_id = '" . tep_db_input($address_format_id) . "', countries_name = '" . tep_db_input($countries_name) . "' where countries_id = '" . (int)$countries_id . "'");
    }
    
    ////
    // Adds a new country
    function add_country($countries_name, $countries_iso_code_2, $countries_iso_code_3, $address_format_id){
      echo "insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . tep_db_input($address_format_id) . "')";
      tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . tep_db_input($address_format_id) . "')");
      
      
    }
    
    
    ////
    // Removes a country
    function remove_country ($countries_id) {
      tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
    }

/*
 * ADDRESS FORMAT METHODS
*/
    ////
    // Returns an array with address formats # and format
    function get_address_format_list () {
      $address_format_query = tep_db_query("select * from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
      
      while ($address_format = tep_db_fetch_array($address_format_query)) {
        $address_format_array[$address_format['address_format_id']] = $address_format['address_format'];
      }
      return $address_format_array;
    }


/*
 * ZONE METHODS
*/

    ////
    // Returns an array with only countries with zones
    function get_countries_with_zones () {

      // Sets a property containing id and name only for countries that has zones
      $zones_query = tep_db_query("select zone_country_id, c.countries_name from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_country_id = c.countries_id group by zone_country_id");
      while ($zones = tep_db_fetch_array($zones_query)) {
        $countries_with_zones[$zones['zone_country_id']] = $zones['countries_name'];
      }
      return $countries_with_zones;
    }

    ////
    // Returns an array with only countries without zones
    function get_no_zone_countries() {
      return array_diff ($this->get_countries_list() , $this->get_countries_with_zones());
    }

    ////
    // Returns an array with all zones in a country
    function get_country_zones($country_id) {
    // return an array with country zones
      $zones_array = array();
      $zones_query = tep_db_query("select zone_id, zone_name, zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
      while ($zones = tep_db_fetch_array($zones_query)) {
        $zones_array[$zones['zone_id']] = array('code' => $zones['zone_code'],
                                                'name' => $zones['zone_name']);
      }

      return $zones_array;
    }
    
    ////
    // Returns an array with zone details
    function get_zone_data($zone_id) {
      $zones_query = tep_db_query("select zone_id, zone_name, zone_code, zone_country_id from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");
      while ($zone_data = tep_db_fetch_array($zones_query)) {
        return $zone_data;
      }

    }

    ////
    // Edits a zone:
    function edit_zone ($zone_id, $zone_name, $zone_code, $zone_country_id) {
      tep_db_query("update " . TABLE_ZONES . " set zone_country_id = '" . (int)$zone_country_id . "', zone_code = '" . tep_db_input($zone_code) . "', zone_name = '" . tep_db_input($zone_name) . "' where zone_id = '" . (int)$zone_id . "'");
    }

    ////
    // Adds a new zone
    function add_zone ($zone_name, $zone_code, $zone_country_id) {
      tep_db_query("insert into " . TABLE_ZONES . " (zone_country_id, zone_code, zone_name) values ('" . (int)$zone_country_id . "', '" . tep_db_input($zone_code) . "', '" . tep_db_input($zone_name) . "')");
    }

    ////
    // Removes a zone
    function remove_zone ($zone_id) {
      tep_db_query("delete from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");
    }

    ////
    // Removes all zones from a country
    function remove_all_country_zones ($country_id) {
        tep_db_query("delete from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "'");
    }

  }