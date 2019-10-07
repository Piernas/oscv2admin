<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_copy {
    public static function execute() {
      global $current_category_id;
      require ('includes/classes/product.php');
      $product_id = (isset($_GET['pID']) ? (int)$_GET['pID'] : '');

      $product = new product ($product_id);
      echo '<span id="title"><strong><i class="fas fa-arrows fa-lg"></i> ' . TEXT_INFO_HEADING_COPY_TO . '</strong></span>';
      echo '<span id="content">' . PHP_EOL;
      echo '<p>' . TEXT_INFO_COPY_TO_INTRO . PHP_EOL;
      echo '<p>' .  TEXT_INFO_CURRENT_CATEGORIES . '</p>' . PHP_EOL;
      echo '<strong>' . tep_output_generated_ul_category_path($product_id, 'product') . '</strong>' . PHP_EOL;
      echo '<div class="form-group">';
      echo '<p>' .  TEXT_CATEGORIES . '</p>' . PHP_EOL;
      echo tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id) . PHP_EOL;
      echo '</div>';
      echo '<p>' .  TEXT_HOW_TO_COPY . '</p >';
      echo '<div class="radio"><label>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '</label></div>';
      echo '<div class="radio"><label>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE . '</label></div>';
      echo tep_draw_hidden_field('products_id', $product->products_id) . PHP_EOL;
      echo '</span>';
    }
  }