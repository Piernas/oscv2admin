<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for inserting a new product in product.php
  
  
*/

  class osC_Actions_languages_make_default {
    public static function execute() {
      $code = (isset($_GET['code']) ? $_GET['code'] : '');
      $code = tep_db_prepare_input($_GET['code']);
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      tep_redirect(tep_href_link('languages.php', 'code=' . $code));
    }
  }
