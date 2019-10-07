<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_move_confirm {
    public static function execute() {
      global $current_category_id, $PHP_SELF, $languages_id, $messageStack;

      $products_id = tep_db_prepare_input($_POST['products_id']);
      $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

      $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
      $duplicate_check = tep_db_fetch_array($duplicate_check_query);
      if ($duplicate_check['total'] < 1) {
        tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
        $messageStack->add_session(sprintf (TEXT_MOVE_PRODUCT, $product_name, tep_get_category_name ((int)$new_parent_id, $languages_id)), 'success');
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

      tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $new_parent_id . '&pID=' . $products_id));

    }
  }