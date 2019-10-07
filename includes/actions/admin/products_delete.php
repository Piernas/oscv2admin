<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_delete {
    public static function execute() {
      global $languages_id;

      require_once ('includes/classes/product.php');

      $product_id = (isset($_GET['pID']) ? (int)$_GET['pID'] : '');
      $product = new product ($product_id);

      echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_PRODUCT . ": " . $product->description[$languages_id]['products_name'] . '</strong></span>';
      echo '<span id="content">' . PHP_EOL;
      echo '<p>' . TEXT_DELETE_PRODUCT_INTRO . '</p>';
      echo '<input type="hidden" name="products_id" value="' . $product->products_id . '" />';
      
      
      
      $product_categories_string = TEXT_DELETE_FROM_CATEGORIES . '<br />';
      $product_categories = tep_generate_category_path($product->products_id, 'product');
      
      if (sizeof($product_categories)>1) {
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br />';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        echo '<br />' . $product_categories_string;
      }
      echo '</span>';
      exit();
    }
  }