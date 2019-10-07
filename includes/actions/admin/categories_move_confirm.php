<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2018 osCommerce

  Released under the GNU General Public License

  action for updating an existing product in product.php

*/

  class osC_Actions_categories_move_confirm {
    public static function execute() {
      global $PHP_SELF, $cPath, $languages_id;
      if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

        $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

        if (in_array($categories_id, $path)) {
          $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

          tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath . '&cID=' . $categories_id));
        } else {
          tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
          $messageStack->add_session(sprintf (SUCCESS_CATEGORY_MOVED, tep_get_category_name ($categories_id, $languages_id) , tep_get_category_name($new_parent_id, $languages_id)), 'success');
          tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        }
      }

    }
  }