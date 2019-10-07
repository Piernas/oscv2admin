<?php
/* 
 * This project really needs a standard file header....
 * 
 * description.php
 * includes/classes/categories/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the categories_description database table. 
   * 
   * Do not instantiate this class directly. Call it using the main categories class.
   */
  class category_description {
    protected $categories_id = 0;
    protected $languages_id = 1;


    /**
     * Sanitize the input variables
     * 
     * @global integer $languages_id
     * @param integer $categories_id
     */
    function __construct( $categories_id ) {
      global $languages_id;
      
      $this->categories_id = filter_var( $categories_id, FILTER_SANITIZE_NUMBER_INT );
      $this->languages_id = filter_var( $languages_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Get the language-dependent product data from the database.
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table. 
     * 
     * Reads database table:
     * * {@link categories_description}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
//      global $languages;
        $languages = tep_get_languages();

      foreach ($languages as $language) {
        $category_query_raw = "
          select 
            *
          from 
            categories_description
          where 
            categories_id = '" . $this->categories_id . "' 
            and language_id = '" . $language['id'] . "'
        ";
        $category_query = tep_db_query ($category_query_raw);
        $category_data [$language['id']]= tep_db_fetch_array ($category_query);
      }
      return $category_data;
    }    

  }
  