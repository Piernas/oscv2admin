<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License

  action for copying an existing product in categories.php

*/

  class osC_Actions_products_move {
    public static function execute() {
      global $current_category_id, $languages_id;
      
      $product_id = (isset($_GET['pID']) ? (int)$_GET['pID'] : '');
      require ('includes/classes/product.php');
      $product = new product ($product_id);

      echo '<span id="title"><strong><i class="fas fa-arrows-alt fa-lg"></i> ' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</strong></span>';
      echo '<span id="content">' . PHP_EOL;
      echo '<p>' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $product->description[$languages_id]['products_name']) . '</p>' . PHP_EOL;
      echo '<p>' .  TEXT_INFO_CURRENT_CATEGORIES . '</p>' . PHP_EOL;
      echo '<strong>' . tep_output_generated_ul_category_path($product_id, 'product') . '</strong>' . PHP_EOL;
      sprintf(TEXT_MOVE, $product->description[$languages_id]['products_name']) . PHP_EOL;
      echo tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . PHP_EOL;
      echo tep_draw_hidden_field('products_id', $product->products_id) . PHP_EOL;

      echo '</span>';
      exit();

    }
  }