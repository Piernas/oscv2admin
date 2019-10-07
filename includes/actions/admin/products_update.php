<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2018 osCommerce

  Released under the GNU General Public License

  action for updating an existing product in product.php

*/

  class osC_Actions_products_update {
    public static function execute() {
      global $PHP_SELF, $cPath;
      if (isset($_GET['pID'])) $products_id = tep_db_prepare_input($_GET['pID']);
      $products_date_available = tep_db_prepare_input($_POST['products_date_available']);

      $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

      $sql_data_array = array('products_quantity' => (int)tep_db_prepare_input($_POST['products_quantity']),
                              'products_model' => tep_db_prepare_input($_POST['products_model']),
                              'products_price' => tep_db_prepare_input($_POST['products_price']),
                              'products_date_available' => $products_date_available,
                              'products_weight' => (float)tep_db_prepare_input($_POST['products_weight']),
                              'products_status' => tep_db_prepare_input($_POST['products_status']),
                              'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
                              'manufacturers_id' => (int)tep_db_prepare_input($_POST['manufacturers_id']));
      $sql_data_array['products_gtin'] = (tep_not_null($_POST['products_gtin'])) ? str_pad(tep_db_prepare_input($_POST['products_gtin']), 14, '0', STR_PAD_LEFT) : 'null';

      $products_image = new upload('products_image');
      $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
      if ($products_image->parse() && $products_image->save()) {
        $sql_data_array['products_image'] = tep_db_prepare_input($products_image->filename);
      }

      $update_sql_data = array('products_last_modified' => 'now()');

      $sql_data_array = array_merge($sql_data_array, $update_sql_data);

      tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");

      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];

        $sql_data_array = array('products_name' => tep_db_prepare_input($_POST['products_name'][$language_id]),
                                'products_description' => tep_db_prepare_input($_POST['products_description'][$language_id]),
                                'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]));
        $sql_data_array['products_seo_description'] = tep_db_prepare_input($_POST['products_seo_description'][$language_id]);
        $sql_data_array['products_seo_keywords'] = tep_db_prepare_input($_POST['products_seo_keywords'][$language_id]);
        $sql_data_array['products_seo_title'] = tep_db_prepare_input($_POST['products_seo_title'][$language_id]);


        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");

      }

      $pi_sort_order = 0;
      $piArray = array(0);

      foreach ($_FILES as $key => $value) {
// Update existing large product images
        if (preg_match('/^products_image_large_([0-9]+)$/', $key, $matches)) {
          $pi_sort_order++;

          $sql_data_array = array('htmlcontent' => tep_db_prepare_input($_POST['products_image_htmlcontent_' . $matches[1]]),
                                  'sort_order' => $pi_sort_order);

          $t = new upload($key);
          $t->set_destination(DIR_FS_CATALOG_IMAGES);
          if ($t->parse() && $t->save()) {
            $sql_data_array['image'] = tep_db_prepare_input($t->filename);
          }

          tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and id = '" . (int)$matches[1] . "'");

          $piArray[] = (int)$matches[1];
        } elseif (preg_match('/^products_image_large_new_([0-9]+)$/', $key, $matches)) {
// Insert new large product images
          $sql_data_array = array('products_id' => (int)$products_id,
                                  'htmlcontent' => tep_db_prepare_input($_POST['products_image_htmlcontent_new_' . $matches[1]]));

          $t = new upload($key);
          $t->set_destination(DIR_FS_CATALOG_IMAGES);
          if ($t->parse() && $t->save()) {
            $pi_sort_order++;

            $sql_data_array['image'] = tep_db_prepare_input($t->filename);
            $sql_data_array['sort_order'] = $pi_sort_order;

            tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array);

            $piArray[] = tep_db_insert_id();
          }
        }
      }

      $product_images_query = tep_db_query("select image from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "' and id not in (" . implode(',', $piArray) . ")");
      if (tep_db_num_rows($product_images_query)) {
        while ($product_images = tep_db_fetch_array($product_images_query)) {
          $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_IMAGES . " where image = '" . tep_db_input($product_images['image']) . "'");
          $duplicate_image = tep_db_fetch_array($duplicate_image_query);

          if ($duplicate_image['total'] < 2) {
            if (file_exists(DIR_FS_CATALOG_IMAGES . $product_images['image'])) {
              @unlink(DIR_FS_CATALOG_IMAGES . $product_images['image']);
            }
          }
        }

        tep_db_query("delete from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "' and id not in (" . implode(',', $piArray) . ")");
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

      tep_redirect(tep_href_link('categories.php', 'cPath=' . $cPath . '&pID=' . $products_id));

    }
  }