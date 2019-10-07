<?php
/* 
 * This project really needs a standard file header....
 * 
 * data.php
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
  class data {
    protected $product_id = array();


    /**
     * Sanitize the input variable
     * 
     * @param integer $product_id
     */
    function __construct( $product_id ) {
      $this->product_id = filter_var( $product_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Get the language-independent product data from the database.
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link products}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $product_query_raw = "
        select 
          *
        from 
          products
        where
          products_id = '" . $this->product_id . "' 
      ";
      $product_query = tep_db_query ($product_query_raw);
      $product_data = tep_db_fetch_array ($product_query);

      return $product_data;
    }    

  }