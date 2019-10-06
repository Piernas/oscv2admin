<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for inserting a new product in product.php
  
  
*/

  class osC_Actions_languages_delete {
    public static function execute() {
      $code = (isset($_GET['code']) ? $_GET['code'] : '');
        $lng_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . $code . "'");
        $lng = tep_db_fetch_array($lng_query);
        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</strong></span>';
        echo '<span id="content">' .TEXT_INFO_DELETE_INTRO . PHP_EOL;
        echo '</span>';
    }
  }
