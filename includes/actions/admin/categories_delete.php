<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for editing an existing category in categories.php
  
  
*/

  class osC_Actions_categories_delete {
    public static function execute() {
      global $cPath;
      if (isset($_GET['categories_id'])) {
        $cPath = tep_db_prepare_input($_GET['categories_id']);

        require_once ('includes/classes/category.php');
        $category = new category ($cPath);

        echo '<span id="title"><strong><i class="fas fa-trash fa-lg"></i> ' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo '<p>' . TEXT_DELETE_CATEGORY_INTRO . '</p>' . PHP_EOL;
        echo tep_draw_hidden_field('categories_id', $cPath);
        if ($category->count_subcategories() > 0) echo '<div class="alert alert-danger">' . sprintf(TEXT_DELETE_WARNING_CHILDS, $category->count_subcategories()) . '</div>';
        if ($category->count_products() > 0) echo '<div class="alert alert-danger">' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $category->count_products()) . '</div>';
        echo '</span>';
        exit;
      }
    }
  }