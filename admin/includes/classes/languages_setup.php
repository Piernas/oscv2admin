<?php

  class languages_setup {
    
    var $id, $code, $name, $directory, $image, $sort_order, $installed, $uninstalled;
    
    function __construct () {
      $this->get_info ();

    } 
 
    function get_info () {
      $languages_query = tep_db_query ("select languages_id, name, code, image, directory, sort_order from " . TABLE_LANGUAGES . " order by sort_order");

      // Installed languages
      while ($languages = tep_db_fetch_array ($languages_query)) {
        $code = $languages['code'];
        $this->installed [$code] = array(
                      'id' => $languages['languages_id'],
                      'name' => $languages['name'],
                      'image' => $languages['image'],
                      'directory' => $languages['directory'],
                      'sort_order' => $languages['sort_order']
                      );

      }
      
              
      // Uninstalled languages
     
      foreach (glob(DIR_FS_CATALOG . 'includes/languages/*.ini') as $file) {
        
        $uninstalled = parse_ini_file ($file);

        if(!array_key_exists($uninstalled ['code'], $this->installed)){

        $this->uninstalled [$uninstalled ['code']] = array(
                      'name' => $uninstalled['name'],
                      'image' => $uninstalled['image'],
                      'directory' => $uninstalled['directory'],
                      'sort_order' => $uninstalled['sort_order']
                      );
        }
      }
 // print_r ($this->uninstalled );
 // print_r ($this->installed );


    }

    function uninstall ($code) {
      
      $ID = $this->installed[$code]['id'];

      tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . $ID . "'");
      tep_db_query("delete from " . TABLE_LANGUAGES . " where languages_id = '" . $ID . "'");
      
      $max_value_query = tep_db_query("SELECT max(languages_id)+1 as Max FROM " . TABLE_LANGUAGES . " LIMIT 1");
      $max_value = tep_db_fetch_array ($max_value_query);
      
      tep_db_query("ALTER TABLE  " . TABLE_LANGUAGES . " AUTO_INCREMENT = " . $max_value['Max'] );
    }
    
    function install ($code) {
      global $languages_id;
 
      $name = $this->uninstalled[$code]['name'];
      $image = $this->uninstalled[$code]['image'];
      $directory = $this->uninstalled[$code]['directory'];
      $sort_order = $this->uninstalled[$code]['sort_order'];

      tep_db_query("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order) values ('" . $name . "', '" . $code . "', '" . $image . "', '" . $directory . "', '" . $sort_order . "')");
      $insert_id = tep_db_insert_id();

// create additional categories_description records
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where cd.language_id = '" . $languages_id . "'");
      while ($categories = tep_db_fetch_array($categories_query)) {
        tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . $categories['categories_id'] . "', '" . $insert_id . "', '" . $categories['categories_name'] . "')");
      }

// create additional products_description records
      $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where pd.language_id = '" . $languages_id . "'");
      while ($products = tep_db_fetch_array($products_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . $products['products_id'] . "', '" . $insert_id . "', '" . $products['products_name'] . "', '" . $products['products_description'] . "', '" . $products['products_url'] . "')");
      }

// create additional products_options records
      $products_options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "'");
      while ($products_options = tep_db_fetch_array($products_options_query)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . $products_options['products_options_id'] . "', '" . $insert_id . "', '" . $products_options['products_options_name'] . "')");
      }

// create additional products_options_values records
      $products_options_values_query = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "'");
      while ($products_options_values = tep_db_fetch_array($products_options_values_query)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$products_options_values['products_options_values_id'] . "', '" . $insert_id . "', '" . $products_options_values['products_options_values_name'] . "')");
      }

// create additional manufacturers_info records
      $manufacturers_query = tep_db_query("select m.manufacturers_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . $languages_id . "'");
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
        tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . $manufacturers['manufacturers_id'] . "', '" . $insert_id . "', '" . $manufacturers['manufacturers_url'] . "')");
      }

// create additional orders_status records
      $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
      while ($orders_status = tep_db_fetch_array($orders_status_query)) {
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$orders_status['orders_status_id'] . "', '" . $insert_id . "', '" . $orders_status['orders_status_name'] . "')");
      }

      if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $code . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      }
  }
    
  }