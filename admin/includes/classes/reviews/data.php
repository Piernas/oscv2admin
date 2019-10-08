<?php
/* 
 * This project really needs a standard file header....
 * 
 * data.php
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
  class data {
    protected $review_id = array();


    /**
     * Sanitize the input variable
     * 
     * @param integer $review_id
     */
    function __construct( $review_id ) {
      $this->review_id = filter_var( $review_id, FILTER_SANITIZE_NUMBER_INT );
    }
   

    /**
     * Get the language-independent review data from the database.
     * 
     * Returns an associative array of all of the fields in the referenced 
     * table.
     * 
     * Reads tables:
     * * {@link reviews}
     * 
     * @return array  All the data in the database table
     */
    public function get_data() {
      $review_query_raw = "
        select 
          *
        from 
          reviews
        where
          reviews_id = '" . $this->review_id . "' 
      ";
      $review_query = tep_db_query ($review_query_raw);
      $review_data = tep_db_fetch_array ($review_query);

      return $review_data;
    }    

  }