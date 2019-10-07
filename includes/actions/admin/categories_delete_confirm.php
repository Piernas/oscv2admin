<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for editing an existing category in categories.php
  
  
*/

  class osC_Actions_categories_delete_confirm {
    public static function execute() {
      global $PHP_SELF, $cPath, $messageStack;
      if (isset($_POST['categories_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);

        require_once ('includes/classes/category.php');
        $category = new category ($categories_id);

        $categories = tep_get_category_tree($categories_id, '', '0', '', true);
        $products = array();
        $products_delete = array();

        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

          while ($product_ids = tep_db_fetch_array($product_ids_query)) {
            $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
          }
        }

        foreach ($products as $key => $value) {
          $category_ids = '';

          for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
            $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
          }
          $category_ids = substr($category_ids, 0, -2);

          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            $products_delete[$key] = $key;
          }
        }

        // removing categories can be a lengthy process
        tep_set_time_limit(0);
        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          tep_remove_category($categories[$i]['id']);
        }

        foreach ($products_delete as $key) {
          tep_remove_product($key);
        }
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      $messageStack->add_session(sprintf(SUCCESS_CATEGORY_DELETED, $category->info[$languages_id]['name'] ), 'error');

      tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath));
    }
  }