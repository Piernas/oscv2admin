<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for editing an existing category in categories.php
  
  
*/

  class osC_Actions_categories_update {
    public static function execute() {
      global $current_category_id, $PHP_SELF, $cPath;

        if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $sort_order = tep_db_prepare_input($_POST['sort_order']);

        $sql_data_array = array('sort_order' => (int)$sort_order);

        $update_sql_data = array('last_modified' => 'now()');

        $sql_data_array = array_merge($sql_data_array, $update_sql_data);

        tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");


        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $categories_name_array = $_POST['categories_name'];
          $categories_description_array = $_POST['categories_description'];
          $categories_seo_description_array = $_POST['categories_seo_description'];
          $categories_seo_keywords_array = $_POST['categories_seo_keywords'];
          $categories_seo_title_array = $_POST['categories_seo_title'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]));
          $sql_data_array['categories_description'] = tep_db_prepare_input($categories_description_array[$language_id]);
          $sql_data_array['categories_seo_description'] = tep_db_prepare_input($categories_seo_description_array[$language_id]);
          $sql_data_array['categories_seo_keywords'] = tep_db_prepare_input($categories_seo_keywords_array[$language_id]);
          $sql_data_array['categories_seo_title'] = tep_db_prepare_input($categories_seo_title_array[$language_id]);

          tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");

        }

        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($categories_image->parse() && $categories_image->save()) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath . '&cID=' . $categories_id));
    }
  }
