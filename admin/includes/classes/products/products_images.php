<?php
/* 
 * This project really needs a standard file header....
 * 
 * products_images.php
 * includes/classes/products/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the products_images database table. 
   * 
   * Do not instantiate this class directly. Call it using the main products class.
   */
  class products_images {
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
     * Returns the product image data from the database
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link products_images}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $product_data = array();

      $data_query_raw = "
        select 
          * 
        from 
          products_images
        where 
          products_id = '" . $this->product_id . "' 
        order by
          sort_order
      ";
      $data_query = tep_db_query( $data_query_raw );

      while ($data_array = tep_db_fetch_array( $data_query ) ) {
        $product_data[$data_array['sort_order']] = array();
        
        foreach( $data_array as $data_key => $data_value ) {
          $product_data[$data_array['sort_order']][$data_key] = $data_value;
        }

      }
      
      if( count( $product_data ) > 0 ) {
        return $product_data;
      } else {
        return NULL;
      }
    }
    
  }