<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for editing an existing configuration option in configuration.php
  Outputs data into bootstrap modal

*/

  class osC_Actions_configuration_edit_value {
    public static function execute() {
        $contents = null;
        $cfg_extra_query = tep_db_query("select configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$_GET['cID']. "'");
        $cfg_extra = tep_db_fetch_array($cfg_extra_query);

        $cInfo = new objectInfo($cfg_extra);
        
        $heading = $cInfo->configuration_title;

        if ($cInfo->set_function) {
          eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
        } else {
          $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
        }

        $contents .= $cInfo->configuration_description . '<br />' . $value_field;
        $contents .= '<input type="hidden" name="cID" value="' . (int)$_GET['cID'] .'">';
   
        echo '<span id="title"><strong><i class="fas fa-info-circle"></i> ' . $heading  . '</strong></span>';
        echo '<span id="content">' .  $contents . '</span>';
      
        exit(0);
    }
  }