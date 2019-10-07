<?php
/* 
 * 
 * product.php
 * includes/classes/
 *
 * Copyright 2019 Juan M de Castro
 * For single product handling
 *
 * Based on products_master.php
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * This class provides information from the database about a product
   * 
   * 
   * Usage:
   * 
   * Return all available data on a single product:
   * $product_data = new products_master( 521 );
   * This returns an array of data for the product with an ID of 521. 
   * 
   * 
   * Return selected data on a single product:
   * $product_data = new products_master( 521, array( 'products_description','reviews','reviews_description' ) );
   * This returns an array of data from the products_description, reviews, and
   * reviews_description database tables for the product with an ID of 521. 
   * 
   * 
   * Note: Any of the above that includes the update_product_viewed subclass will
   * increment the value of the products_viewed field for the product(s) represented.
   * This includes a request for all product data.
   * 
   * 
   * Results format:
   * 
   * Data is returned in an array . The data is in a (mostly) flat key-value array, with the 
   * database field name as the key. Example:
   * 
   * Array  // 521 is the products ID
   *         (
   *             [products_id] => 521
   *             [products_quantity] => 10
   *             ......
   *             
   * 
   * Tables that have (or can have) more than one row associated with the product
   * (products_images, options, options_data, reviews, reviews_description) are 
   * returned in an array that is also indexed by the table name. Example:
   * 
   * Array
   * (
   *  [options] => Array
   *      (
   *          [products_options_id] => 2
   *          [language_id] => 1
   *          ......
   *          [2] => Array
   *              (
   *                  [options_data] => Array
   *                      (
   *                          [products_options_values_id] => 114
   *                          [language_id] => 1
   *                          ......
   * 
   */

  class product {
//    protected $id_array = array();
    public $products_id = 0;
    protected $desired_class_array = array();
    protected $existing_class_array = array();

    
    /**
     * Gather the parameters and make certain they are arrays.
     * 
     * @param integer/array $product_ids
     * @param string/array $subclasses
     */
    
    function __construct( $product_ids, $subclasses = 'all' ) {
      $this->products_id = (int)$product_ids;
//      $this->id_array = (array) $product_ids;
      $this->desired_class_array = (array) $subclasses;
      $this-> get_product_data();
    }
   
    
    /**
     * Traverse the includes/classes/products directory and return an array
     *   of all of the class files found
     * 
     * @return array  Contains all the subclasses found
     */
    
    protected function get_available_subclass_array( ) {
      $subclass_directory = dirname(__FILE__) . '/products';
      
      // Read in the contents of the Subclass directory
			$directory = dir( $subclass_directory );
      
			while ($file_name = $directory->read()) {
				// Break down the filename to get the parts we need
        $parts_array = explode('.', $file_name);
        // There may be more than one dot, so find the last one
        $last = count( $parts_array ) - 1;
        $extension = $parts_array[ $last ];
        // Add only PHP files
        if( $extension === 'php' ) {
          $this->existing_class_array[] = basename( $file_name, '.php' );
        }

			}
			$directory->close();
      
      return true;
    }
    
    
    /**
     * Step through the subclasses and get the data from each one for
     *   a given product_id
     * 
     * @param integer $product_id
     * @return boolean true  (The real output is in $this->product_data)
     */
    
    protected function get_subclass_data( $product_id ) {
      $this->get_available_subclass_array();
      $product_data = array();
      
      foreach( $this->existing_class_array as $subclass ) {
        if( $this->desired_class_array[0] == 'all' or in_array( $subclass, $this->desired_class_array ) ) {
          include_once( 'includes/classes/products/' . $subclass . '.php');
          $class_instance = new $subclass( $product_id );
          $subclass_product_data = $class_instance->get_data();
          
          if( !empty( $subclass_product_data ) and is_array( $subclass_product_data ) and count( $subclass_product_data ) > 0 ) {
            $product_data = array_merge ( $product_data, $subclass_product_data );
          }
$this->$subclass =$subclass_product_data;
        }
      }
      
      return $product_data;
    }
    
    /**
     * Returns all of the requested product data in an array
     *   indexed by the product_id
     * 
     * @return array  The requested data
     */
    public function get_product_data() {
      $product_data = array();
      
//      foreach( $this->id_array as $product_id ) {
        $product_data = $this->get_subclass_data( $this->products_id );
//      }
      
      return $product_data;
    }
    
  }