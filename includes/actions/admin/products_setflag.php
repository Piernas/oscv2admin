<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_setflag {
    public static function execute() {
      global $PHP_SELF;

      if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
        if (isset($_GET['pID'])) {
          tep_set_product_status($_GET['pID'], $_GET['flag']);
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
      }
      tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
    }
  }