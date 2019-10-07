<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for setting sort order of a category in categories.php
  
  
*/

  class osC_Actions_categories_sort {
    public static function execute() {

      global $category, $current_category_id, $new_position;
      require_once ('includes/classes/category.php');

      $categories_id = tep_db_prepare_input($_GET['categories_id']);
      $new_position = tep_db_prepare_input($_GET['new_position']);

      $category = new category ($categories_id);
      $current_category_children_query = tep_db_query ("select * from " . TABLE_CATEGORIES . " where parent_id = '" . $current_category_id . "'");

      $old_position = $category->category_data['sort_order'];

      while ($current_category_children = tep_db_fetch_array($current_category_children_query)) {
        if ($new_position < $old_position) {
          if ($current_category_children['sort_order'] >= $new_position && $current_category_children['sort_order'] < $old_position) {
            tep_db_query ("update " . TABLE_CATEGORIES . " set sort_order = sort_order + 1 where sort_order ='" .  $current_category_children['sort_order'] . "'");
          }
          } else if ($new_position > $old_position) {
            if ($current_category_children['sort_order'] > $old_position && $current_category_children['sort_order'] <= $new_position) {
              tep_db_query ("update " . TABLE_CATEGORIES . " set sort_order = sort_order - 1 where sort_order ='" .  $current_category_children['sort_order'] . "'");
            }
          }
      }

      tep_db_query ("update " . TABLE_CATEGORIES . " set sort_order = '" . $new_position . "' where categories_id ='" .  $category->categories_id . "'");

      $category->category_data['sort_order'] = $new_position;

    }
  }
