<?php
/* 
 * This project really needs a standard file header....
 * 
 * categories.php
 * includes/classes/products/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the categories database table. 
   * 
   * Do not instantiate this class directly. Call it using the main products class.
   */
  class categories {
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
     * Returns the categories data from the database
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link products_to_categories}
     * * {@link categories}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $product_data = array();

      $data_query_raw = "
        select 
          c.* 
        from 
          products_to_categories p2c
          join categories c
            on (c.categories_id = p2c.categories_id)
        where 
          p2c.products_id = '" . $this->product_id . "' 
      ";
      $data_query = tep_db_query( $data_query_raw );

      $key = 0;
      while ($data_array = tep_db_fetch_array( $data_query ) ) {
        $product_data = array();
        
        foreach( $data_array as $data_key => $data_value ) {
//          $product_data['categories'][$key][$data_key] = $data_value;
          $product_data[$data_key] = $data_value;
        }
       
        $key++;
      }
      
      if( count( $product_data ) > 0 ) {
        return $product_data;
      } else {
        return NULL;
      }
    }
    
  }
  