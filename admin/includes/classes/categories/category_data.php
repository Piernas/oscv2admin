<?php
/* 
 * This project really needs a standard file header....
 * 
 * data.php
 * includes/classes/categories/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the categories database table. 
   * 
   * Do not instantiate this class directly. Call it using the main categories class.
   */
  class category_data {
    protected $category_id = array();


    /**
     * Sanitize the input variable
     * 
     * @param integer $category_id
     */
    function __construct( $category_id ) {
      $this->category_id = filter_var( $category_id, FILTER_SANITIZE_NUMBER_INT );
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
      $category_query_raw = "
        select 
          *
        from 
          categories
        where
          categories_id = '" . $this->category_id . "' 
      ";
      $category_query = tep_db_query ($category_query_raw);
      $category_data = tep_db_fetch_array ($category_query);

      return $category_data;
    }    

  }