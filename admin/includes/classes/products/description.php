<?php
/* 
 * This project really needs a standard file header....
 * 
 * products_description.php
 * includes/classes/products/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the products_description database table. 
   * 
   * Do not instantiate this class directly. Call it using the main products class.
   */
  class description {
    protected $product_id = 0;
    protected $languages_id = 1;


    /**
     * Sanitize the input variables
     * 
     * @global integer $languages_id
     * @param integer $product_id
     */
    function __construct( $product_id ) {
      global $languages_id;
      
      $this->product_id = filter_var( $product_id, FILTER_SANITIZE_NUMBER_INT );
      $this->languages_id = filter_var( $languages_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Get the language-dependent product data from the database.
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table. 
     * 
     * Reads database table:
     * * {@link products_description}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
//      global $languages;
        $languages = tep_get_languages();

      foreach ($languages as $language) {
        $product_query_raw = "
          select 
            *
          from 
            products_description
          where 
            products_id = '" . $this->product_id . "' 
            and language_id = '" . $language['id'] . "'
        ";
        $product_query = tep_db_query ($product_query_raw);
        $product_data [$language['id']]= tep_db_fetch_array ($product_query);
      }
      return $product_data;
    }    

  }
  