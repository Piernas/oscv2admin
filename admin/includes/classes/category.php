<?php
/* 
 * 
 * category.php
 * includes/classes/
 *
 * Copyright 2019 Juan M de Castro
 * For single category handling
 *
 * Based on products_master.php
 * Copyright 2018 James C Keebaugh
 * Released under the GPL v3.0 License
 * 
 */


  /**
   * This class provides information from the database about a category
   * 
   * 
   * Usage:
   * 
   * Return all available data on a single category:
   * $category_data = new products_master( 521 );
   * This returns an array of data for the category with an ID of 521. 
   * 
   * 
   * Return selected data on a single category:
   * $category_data = new products_master( 521, array( 'products_description','reviews','reviews_description' ) );
   * This returns an array of data from the products_description, reviews, and
   * reviews_description database tables for the product with an ID of 521. 
   * 
   * 
   * Note: Any of the above that includes the update_category_viewed subclass will
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
   *             [categories_id] => 521
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

  class category {
//    protected $id_array = array();
    public $categories_id = 0;
    protected $desired_class_array = array();
    protected $existing_class_array = array();

    
    /**
     * Gather the parameters and make certain they are arrays.
     * 
     * @param integer/array $category_id
     * @param string/array $subclasses
     */
    
    function __construct( $category_id, $subclasses = 'all' ) {
      $this->categories_id = (int)$category_id;
//      $this->id_array = (array) $category_id;
      $this->desired_class_array = (array) $subclasses;
      $this-> get_category_data();
    }
   
    
    /**
     * Traverse the includes/classes/categories directory and return an array
     *   of all of the class files found
     * 
     * @return array  Contains all the subclasses found
     */
    
    protected function get_available_subclass_array( ) {
      $subclass_directory = dirname(__FILE__) . '/categories';
      
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
     *   a given category_id
     * 
     * @param integer $category_id
     * @return boolean true  (The real output is in $this->category_data)
     */
    
    protected function get_subclass_data( $category_id ) {
      $this->get_available_subclass_array();
      $category_data = array();
      
      foreach( $this->existing_class_array as $subclass ) {
        if( $this->desired_class_array[0] == 'all' or in_array( $subclass, $this->desired_class_array ) ) {
          include_once( 'includes/classes/categories/' . $subclass . '.php');
          $class_instance = new $subclass( $category_id );
          $subclass_category_data = $class_instance->get_data();
          
          if( !empty( $subclass_category_data ) and is_array( $subclass_category_data ) and count( $subclass_category_data ) > 0 ) {
            $category_data = array_merge ( $category_data, $subclass_category_data );
          }
$this->$subclass =$subclass_category_data;
        }
      }
      
      return $category_data;
    }
    
    /**
     * Returns all of the requested category data in an array
     *   indexed by the category_id
     * 
     * @return array  The requested data
     */
    public function get_category_data() {
      $category_data = array();
      
//      foreach( $this->id_array as $category_id ) {
        $category_data = $this->get_subclass_data( $this->categories_id );
//      }
      
      return $category_data;
    }

    function count_subcategories ($categories_id = null) {
      $categories_count = 0;

      $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $this->categories_id . "'");
      while ($categories = tep_db_fetch_array($categories_query)) {
        $categories_count++;
        $categories_count += tep_childs_in_category_count($categories['categories_id']);
      }

      return $categories_count;
    }
    
    
    function count_products ($categories_id = null, $include_deactivated = false) {
      if ($categories_id == null) $categories_id = $this->categories_id;

      $products_count = 0;

      if ($include_deactivated) {
        $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'");
      } else {
        $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $categories_id . "'");
      }

      $products = tep_db_fetch_array($products_query);

      $products_count += $products['total'];

      $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'");
      if (tep_db_num_rows($childs_query)) {

        while ($childs = tep_db_fetch_array($childs_query)) {
          $products_count += $this->count_products($childs['categories_id'], $include_deactivated);
        }
      }

      return $products_count;
    }
    
    function get_path($current_category_id = null) {
      global $cPath_array;

      if ($current_category_id == '') {
        $cPath_new = implode('_', $cPath_array);
      } else {
        if (empty($cPath_array) ) {
          $cPath_new = $current_category_id;
        } else {
          $cPath_new = '';
          $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
          $last_category = tep_db_fetch_array($last_category_query);

          $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
          $current_category = tep_db_fetch_array($current_category_query);

          if ($last_category['parent_id'] == $current_category['parent_id']) {
            for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
              $cPath_new .= '_' . $cPath_array[$i];
            }
          } else {
            for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
              $cPath_new .= '_' . $cPath_array[$i];
            }
          }

          $cPath_new .= '_' . $current_category_id;

          if (substr($cPath_new, 0, 1) == '_') {
            $cPath_new = substr($cPath_new, 1);
          }
        }
      }

      return 'cPath=' . $cPath_new;
    }
    
    function get_parent () {
      $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $this->categories_id . "'");
      $current_category = tep_db_fetch_array($current_category_query);
      return $current_category['parent_id'];
    }
    
    
  }

/*
  class category {
    var $id, $image, $parent_id, $sort_order, $date_added, $last_modified, $info;

    function __construct($category_id) {
      $this->id = (int)$category_id;
      $this->query();

    }

    function query () {
//      global $languages_id;
      $category_query = tep_db_query("SELECT * FROM " . TABLE_CATEGORIES . " WHERE categories_id = '" .  $this->id . "'");

      $category = tep_db_fetch_array($category_query);
      $this->image = $category['categories_image'];
      $this->parent_id = $category['parent_id'];
      $this->sort_order = $category['sort_order'];
      $this->date_added = $category['date_added'];
      $this->last_modified = $category['last_modified'];

      $category_description_query = tep_db_query("SELECT * FROM ". TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = '" . $this->id . "'");

      while ($category_description = tep_db_fetch_array($category_description_query)) {
        $this->info [$category_description['language_id']] = array(
                                      'name' => $category_description['categories_name'],
                                      'description' => $category_description['categories_description'],
                                      'seo_description' => $category_description['categories_seo_description'],
                                      'seo_keywords' => $category_description['categories_seo_keywords'],
                                      'seo_title' => $category_description['categories_seo_title'],
        );
      }
    }



    function count_subcategories ($categories_id = null) {
      $categories_count = 0;

      $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $this->id . "'");
      while ($categories = tep_db_fetch_array($categories_query)) {
        $categories_count++;
        $categories_count += tep_childs_in_category_count($categories['categories_id']);
      }

      return $categories_count;
    }

    function count_products ($categories_id = null, $include_deactivated = false) {
      if ($categories_id == null) $categories_id = $this->id;

      $products_count = 0;

      if ($include_deactivated) {
        $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.categories_id = p2c.categories_id and p2c.categories_id = '" . $categories_id . "'");
      } else {
        $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.categories_id = p2c.categories_id and p.products_status = '1' and p2c.categories_id = '" . $categories_id . "'");
      }

      $products = tep_db_fetch_array($products_query);

      $products_count += $products['total'];

      $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'");
      if (tep_db_num_rows($childs_query)) {

        while ($childs = tep_db_fetch_array($childs_query)) {
          $products_count += $this->count_products($childs['categories_id'], $include_deactivated);
        }
      }

      return $products_count;
    }

    function get_path($current_category_id = null) {
      global $cPath_array;

      if ($current_category_id == '') {
        $cPath_new = implode('_', $cPath_array);
      } else {
        if (empty($cPath_array) ) {
          $cPath_new = $current_category_id;
        } else {
          $cPath_new = '';
          $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
          $last_category = tep_db_fetch_array($last_category_query);

          $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
          $current_category = tep_db_fetch_array($current_category_query);

          if ($last_category['parent_id'] == $current_category['parent_id']) {
            for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
              $cPath_new .= '_' . $cPath_array[$i];
            }
          } else {
            for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
              $cPath_new .= '_' . $cPath_array[$i];
            }
          }

          $cPath_new .= '_' . $current_category_id;

          if (substr($cPath_new, 0, 1) == '_') {
            $cPath_new = substr($cPath_new, 1);
          }
        }
      }

      return 'cPath=' . $cPath_new;
    }
  }


  function change_position ($new_position) {
    global $current_category_id;

    if ($new_position > $this->sort_order) {
      $val1 = $this->sort_order;
      $val2 = $new_position;
    } else if ($this->sort_order > $new_position) {
      $val1 = $new_position;
      $val2 = $this->sort_order;
    }

    $current_category_children_query = tep_db_query ("select sort_order from " . TABLE_CATEGORIES . " where parent_id = '" . $current_category_id . "'");

    while ($current_category_children = tep_db_fetch_array($current_category_children_query)) {
      if ($current_category_children['sort_order'] >= $val1 && $current_category_children['sort_order'] < $val2) {
        tep_db_query ("update " . TABLE_CATEGORIES . " set sort_order = sort_order + 1 where sort_order ='" .  $current_category_children['sort_order'] . "'");
      }
    }
    tep_db_query ("update " . TABLE_CATEGORIES . " set sort_order = '" . $new_position . "' where categories_id ='" .  $this->id . "'");
    $this->sort_order = $new_position;
  }
  
  */