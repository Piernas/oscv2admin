<?php


  class address {
    var $address_list;

    function __construct($customer_id) {
      $this->query($customer_id);
    }

    function remove ($id) {
      
    }

    function addnew () {
      
    }    
    function query($customer_id) {
      
      $address_query = tep_db_query ("select * from address_book where customers_id = " . $customer_id);
      while ($address = tep_db_fetch_array($address_query)) {
        $this->address_list[$address['address_book_id']] = array(
//                                'id' => $address['address_book_id'],
                                'gender' => $address['entry_gender'],
                                'company' => $address['entry_company'],
                                'firstname' => $address['entry_firstname'],
                                'lastname' => $address['entry_lastname'],
                                'street_address' => $address['entry_street_address'],
                                'suburb' => $address['entry_suburb'],
                                'postcode' => $address['entry_postcode'],
                                'city' => $address['entry_city'],
                                'state' => $address['entry_state'],
                                'country_id' => $address['entry_country_id'],
                                'zone_id' => $address['entry_zone_id'],
                                'gender' => $address['entry_gender']);

    
      }
    
    }
  }