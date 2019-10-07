<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for updating an existing configuration option in configuration.php

*/

  class osC_Actions_configuration_save_value {
    public static function execute() {

        $cVal = tep_db_prepare_input($_POST['configuration_value']);
        $cID = tep_db_prepare_input($_POST['cID']);

        // Update config value
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($cVal) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");

        // Convert values from use_function:
        $cfg_extra_query = tep_db_query("select configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . $cID. "'");
        $cfg_extra = tep_db_fetch_array($cfg_extra_query);

        $cInfo = new objectInfo($cfg_extra);

        if ($cInfo->use_function) {
          
          $use_function = $cInfo->use_function;
          if (preg_match('/->/', $use_function)) {
            $class_method = explode('->', $use_function);
            if (!is_object(${$class_method[0]})) {
              include('includes/classes/' . $class_method[0] . '.php');
              ${$class_method[0]} = new $class_method[0]();
            }
            $output = tep_call_function($class_method[1], $cInfo->configuration_value, ${$class_method[0]});
          } else {
            $output = tep_call_function($use_function, $cInfo->configuration_value);
          }
        } else {
          $output = $cInfo->configuration_value;

        }

        // convert boolean to icon:

        if (strtolower ($output) == 'true') {
          $output = '<i class="fa fa-check fa-lg text-success"></i>';
        } elseif (strtolower($output) == 'false') {
          $output = '<i class="fa fa-times fa-lg text-danger"></i>';
        }

        // output new value
        echo '<span id="content">' . $output . '</span>';
   
        exit(0);
    }
  }