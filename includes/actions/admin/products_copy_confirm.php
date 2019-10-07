<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_copy_confirm {
    public static function execute() {
      global $current_category_id, $messageStack, $PHP_SELF;
      require ('includes/classes/product.php');

      if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $categories_id = tep_db_prepare_input($_POST['categories_id']);

        if ($_POST['copy_as'] == 'link') {
          $action_description = TEXT_LINK_PRODUCT;
          if ($categories_id != $current_category_id) {
            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
            }
          } else {
            $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
          }
        } elseif ($_POST['copy_as'] == 'duplicate') {
          $action_description = TEXT_DUPLICATE_PRODUCT;
          $product_query = tep_db_query("select products_quantity, products_model, products_image, products_price, products_date_available, products_weight, products_tax_class_id, manufacturers_id, products_gtin from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $product = tep_db_fetch_array($product_query);

          tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model,products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_gtin) values ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_price']) . "',  now(), " . (empty($product['products_date_available']) ? "null" : "'" . tep_db_input($product['products_date_available']) . "'") . ", '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "', '" . tep_db_input($product['products_gtin']) . "')");
          $dup_products_id = tep_db_insert_id();

          $description_query = tep_db_query("select language_id, products_name, products_description, products_url, products_seo_title, products_seo_description, products_seo_keywords from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
          while ($description = tep_db_fetch_array($description_query)) {
            if ((int)$description['language_id']  == (int)$languages_id) $product_name = tep_db_input($description['products_name']);
            tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url, products_viewed, products_seo_title, products_seo_description, products_seo_keywords) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0', '" . tep_db_input($description['products_seo_title']) . "', '" . tep_db_input($description['products_seo_description']) . "', '" . tep_db_input($description['products_seo_keywords']) . "')");
          }

          $product_images_query = tep_db_query("select image, htmlcontent, sort_order from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "'");
          while ($product_images = tep_db_fetch_array($product_images_query)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_IMAGES . " (products_id, image, htmlcontent, sort_order) values ('" . (int)$dup_products_id . "', '" . tep_db_input($product_images['image']) . "', '" . tep_db_input($product_images['htmlcontent']) . "', '" . tep_db_input($product_images['sort_order']) . "')");
          }

          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
          $products_id = $dup_products_id;
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
      }
      $messageStack->add_session(sprintf ($action_description, $product_name, tep_get_category_name ($categories_id, $languages_id)), 'success');

      tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $categories_id . '&pID=' . $products_id));
    }
  }