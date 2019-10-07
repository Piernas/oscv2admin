<?php
/* 
 * This project really needs a standard file header....
 * 
 * manufacturer.php
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
  class manufacturer {
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
     * Returns the manufacturers data from the database
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link products}
     * * {@link manufacturers}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $data_query_raw = "
        select 
          m.* 
        from
          products p
          join manufacturers m
            on ( m.manufacturers_id = p.manufacturers_id )
        where 
          p.products_id = '" . $this->product_id . "' 
      ";
      $data_query = tep_db_query( $data_query_raw );
      $data_array = tep_db_fetch_array( $data_query );
      
      return $data_array;
    }
    
  }