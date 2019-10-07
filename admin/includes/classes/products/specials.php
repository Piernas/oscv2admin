<?php
/* 
 * This project really needs a standard file header....
 * 
 * specials.php
 * includes/classes/products/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the products database table. 
   * 
   * Do not instantiate this class directly. Call it using the main products class.
   */
  class specials {
    protected $product_id =  array();


    /**
     * Sanitize the input variable
     * 
     * @param integer $product_id
     */
    function __construct( $product_id ) {
      $this->product_id = filter_var( $product_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Returns the specials data from the database
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link specials}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $product_data = array();
      $special_price_query_raw = "
        select 
          * 
        from 
          specials
        where 
          products_id = '" . $this->product_id . "' 
          and status = 1
      ";
      $special_price_query = tep_db_query( $special_price_query_raw );
      $product_data = tep_db_fetch_array( $special_price_query );
      
      if( isset ($product_data) && count( $product_data ) > 0 ) {
        return $product_data;
      } else {
        return NULL;
      }
    }
    

  }