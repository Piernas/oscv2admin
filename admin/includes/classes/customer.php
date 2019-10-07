<?php

  class customer {
    public $customer_id = 0;
    protected $desired_class_array = array();
    protected $existing_class_array = array();

    function __construct( $customer_id, $subclasses = 'all' ) {

      $this->customer_id = (int)$customer_id;
      $this->desired_class_array = (array) $subclasses;
      $this-> get_customer_data();
    }
    
    protected function get_available_subclass_array( ) {
      $subclass_directory = dirname(__FILE__) . '/customers';
      
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
    
    protected function get_subclass_data( $customer_id ) {
      $this->get_available_subclass_array();
      $customer_data = array();
      
      foreach( $this->existing_class_array as $subclass ) {
        if( $this->desired_class_array[0] == 'all' or in_array( $subclass, $this->desired_class_array ) ) {
          include_once( 'includes/classes/customers/' . $subclass . '.php');
          $class_instance = new $subclass( $customer_id );
          $subclass_customer_data = $class_instance->get_data();
          
          if( !empty( $subclass_customer_data ) and is_array( $subclass_customer_data ) and count( $subclass_customer_data ) > 0 ) {
            $customer_data = array_merge ( $customer_data, $subclass_customer_data );
          }
          $this->$subclass = $subclass_customer_data;
        }
      }
      return $customer_data;
    }
    public function get_customer_data() {
      $category_data = array();
      
//      foreach( $this->id_array as $customer_id ) {
        $category_data = $this->get_subclass_data( $this->customer_id );
//      }
      
      return $category_data;
    }
  }
