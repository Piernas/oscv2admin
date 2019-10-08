<?php
/* 
 * This project really needs a standard file header....
 * 
 * manufacturer.php
 * includes/classes/reviews/
 * 
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * Get the data from the reviews database table. 
   * 
   * Do not instantiate this class directly. Call it using the main reviews class.
   */
  class product_name {
    protected $review_id = array();


    /**
     * Sanitize the input variable
     * 
     * @param integer $review_id
     */
    function __construct( $review_id ) {
      global $languages_id;
      $this->review_id = filter_var( $review_id, FILTER_SANITIZE_NUMBER_INT );
      $this->languages_id = filter_var( $languages_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Returns the manufacturers data from the database
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link reviews}
     * * {@link manufacturers}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      global $languages_id;

            $data_query_raw = "
          select 
            products_name
          from 
            products_description p
            join reviews r
            on ( r.products_id = p.products_id )
        where 
          r.reviews_id = '" . $this->review_id . "'
        and
          language_id = '" . $languages_id . "
          ' 
      ";
      $data_query = tep_db_query( $data_query_raw );
      $data_array = tep_db_fetch_array( $data_query );
      return $data_array;
    }
    
  }