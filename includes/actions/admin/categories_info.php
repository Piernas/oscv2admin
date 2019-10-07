<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for inserting a new product in product.php
  
  
*/

  class osC_Actions_categories_info {
    public static function execute() {
      global $languages_id ;
      $category_id = (int)$_GET['categories_id'];
      require_once ('includes/classes/category.php');
      $category = new category ($category_id);
// print_r ($category);
      echo '<span id="title"><strong><i class="fas fa-info-circle fa-lg"></i> Category info</strong></span>';
      echo '<span id="content">' . PHP_EOL;
      $languages = tep_get_languages();
// print_r ($languages);
    if ($category->category_data['categories_image']) {
      $category_image = '<div class="card">';
      $category_image .= '<div class="card-body text-center">' . $category->category_data['categories_image'];
      $category_image .= tep_image(HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $category->category_data['categories_image'], $category->category_description [$languages_id]['categories_name']) . '<br>';
      $category_image .= DIR_WS_CATALOG_IMAGES . '<strong>' . $category->category_data['categories_image'] . '</strong>' ;
      $category_image .= '</div>';
      $category_image .= '</div>';
      echo  $category_image;
    } else {
        echo "no image";
////////////////////////////////////////////////////////////////////////////////
    }
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description = '<div class="card">';
        $category_description .= '<div class="card-header">';
        $category_description .= tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp' . $languages[$i]['name'];
        $category_description .='</div>';
        $category_description .= '<div class="card-body">';
        $category_description .= '<strong>Title: </strong>' . $category->category_description [$languages[$i]['id']]['categories_name'] .'<br>';
        $category_description .= '<strong>Description: </strong>' . $category->category_description [$languages[$i]['id']]['categories_description'] .'<br>';
        $category_description .= '<strong>SEO Title: </strong>' . $category->category_description [$languages[$i]['id']]['categories_seo_title'] .'<br>';
        $category_description .= '<strong>SEO description: </strong>' . $category->category_description [$languages[$i]['id']]['categories_seo_description'] .'<br>';
        $category_description .= '</div>';
        $category_description .= '</div>';
        echo $category_description;
      }

      echo '</span>' . PHP_EOL;
      exit;
    }
  }
