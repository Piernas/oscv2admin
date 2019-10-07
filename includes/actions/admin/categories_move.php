<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2018 osCommerce

  Released under the GNU General Public License

  action for updating an existing product in product.php

*/

  class osC_Actions_categories_move {
    public static function execute() {
      global $languages_id, $current_category_id;
        $cPath = (int)$_GET['cID'];
        require_once ('includes/classes/category.php');
        $category = new category ($cPath);
        echo '<span id="title"><strong><i class="fas fa-arrows fa-lg"></i> ' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</strong></span>';
        echo '<span id="content">' . PHP_EOL;
        echo tep_draw_hidden_field('categories_id', $cPath);
        echo '<p>' . sprintf(TEXT_MOVE_CATEGORIES_INTRO, $category->category_description [$languages_id]['categories_name']) . '</p>' . PHP_EOL;
        echo '<p>' .  sprintf(TEXT_MOVE, $category->category_description [$languages_id]['categories_name']) . '</p>' . PHP_EOL;
        echo tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . PHP_EOL;
        echo '</span>';
    }

  }