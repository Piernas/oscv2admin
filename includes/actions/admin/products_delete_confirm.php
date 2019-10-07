<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_delete_confirm {
    public static function execute() {
      global $PHP_SELF, $cPath;

      if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
        $product_id = tep_db_prepare_input($_POST['products_id']);
        $product_categories = $_POST['product_categories'];

        for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
          tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
        }

        $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
        $product_categories = tep_db_fetch_array($product_categories_query);

        if ($product_categories['total'] == '0') {
          tep_remove_product($product_id);
        }
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

//      $messageStack->add_session(sprintf (TEXT_MOVE_PRODUCT, $product_name, tep_get_category_name ((int)$new_parent_id, $languages_id)), 'success');
      tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath));
    }
  }