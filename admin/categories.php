<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require('includes/classes/currencies.php');
  require ('includes/classes/product.php');
  require_once ('includes/classes/category.php');


  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $product_id = (isset($_GET['pID']) ? (int)$_GET['pID'] : '');


// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!tep_is_writable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  require('includes/template_top.php');

//  $category = new category((int)$current_category_id);

  if ($current_category_id=="0")  {
    $category_name =  TEXT_TOP_CATEGORY;
  } else {
    $category_name = '<span style="white-space:nowrap;">' . tep_output_generated_category_breadcrumb ($current_category_id) . '</span>';
  }

?>
  <div class="d-flex justify-content-between">
<?php
    if (isset($_GET['search'])) {
?>
   <div class="mr-auto p-2 pageHeading"><i class="fas fa-search fa-lg"></i> <?= sprintf (HEADING_SEARCH_RESULTS, $_GET['search']) ?></div>
<?php
    } else{
?>
   <div class="mr-auto p-2 pageHeading"><i class="fas fa-folder fa-lg"></i> <?= HEADING_TITLE . ": " . $category_name; ?></div>
<?php
    }
?>
   <div class="p-2">
      <?= tep_draw_form('search', basename($PHP_SELF),"" ,"get" ,'class="form-inline"') ?>

        <div class="form-group form-group-sm">
          <?= tep_draw_input_field('search', null, ' size="20" placeholder="' . HEADING_TITLE_SEARCH . '"') ?>

          <?= tep_draw_hidden_field ('cPath', $cPath); ?>

          <div class="input-group-append"><button class="btn btn-sm btn-info" type="submit"><i class="fas fa-search"></i></button></div>
        </div>
        <?= tep_hide_session_id() ?>

      </form>
    </div>

    <div class="py-2 pr-2">
      <?= tep_draw_form('goto', basename($PHP_SELF),"" ,"", 'class="form-inline"', 'get') ?>
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
        <span class="input-group-text bg-info text-white"><i class="fas fa-forward fa-lg"></i></span>
        </div>
        <?= tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onchange="this.form.submit();"') ?>
        <?= tep_hide_session_id() ?>
      </div>
      </form>
    </div>
    <div class="py-2">
      <a href="<?= tep_href_link('modules_pages.php?desired_groups=' . urlencode (json_encode(array(array('categories'), array('categories_products', 'categories_categories'))))) ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a>
    </div>
  </div>


<?php

  require ('includes/classes/modules_pages.php');
  $categories_modules = new modules_pages ('categories_categories');

  $categories_count = 0;

  if (isset($_GET['search'])) {
    $search = tep_db_prepare_input($_GET['search']);

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
  } else {
    $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "' order by sort_order");
  }
  $num_categories = tep_db_num_rows($categories_query);
  if ($num_categories > 0){
?>
    <!-- categories table begins -->
    <table class="table table-sm table-striped table-hover" id="categories-table">
      <thead>
      <tr class="table-info">
<?php
      echo $categories_modules->get_table_header ();
?>        <th class="text-center"><?php echo TABLE_HEADING_SORT_ORDER; ?></th>
        <th class="actions"><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
      </thead>
      <tbody>
<?php
  }

    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;

      $category = new category($categories['categories_id']);
      // Get parent_id for subcategories if search
      if (isset($_GET['search'])) {
         $cPath= $category->get_parent();
      } else {
      // check sort order for consecutive numbers
        if ($category->category_data['sort_order'] != $categories_count) {
          $category->category_data['sort_order'] = $categories_count;
          tep_db_query("update categories set sort_order = '" . $categories_count ."' where categories_id = '" . $category->categories_id . "'");
        }
      }

      // Categories table:

    $warning_empty_category = ($category->count_subcategories() < 1 && $category->count_products() < 1 ? "danger" : "primary");


    // sort order for categories:
    if ($category->category_data['sort_order'] > 1)  {
      $button_up = '<a href="javascript:ModalSortCategory(' . $category->categories_id . ',' . ($category->category_data['sort_order'] - 1) . ',' . $category->category_data['sort_order'] . ')"><i class="fas fa-chevron-circle-up fa-lg text-primary"></i></a>';
    } else {
      $button_up = '<i class="fas fa-chevron-circle-up fa-lg text-muted"></i>';
    }

    if ($category->category_data['sort_order'] < $num_categories)  {
      $button_down = '<a href="javascript:ModalSortCategory(' . $category->categories_id . ',' . ($category->category_data['sort_order'] + 1) . ',' . $category->category_data['sort_order'] . ')"><i class="fas fa-chevron-circle-down fa-lg text-primary"></i></a>';
    } else {
      $button_down = '<i class="fas fa-chevron-circle-down fa-lg text-muted"></i>';
    }


    if ( $action == "categories_sort" && $new_position == $category->category_data['sort_order']) {
      $html_class ="clickable table-success";
    } else {
      $html_class ="clickable";
    }




?>
      <tr class="<?= $html_class ?>">

<?php 

    echo $categories_modules->get_table_row ($category->categories_id);

    if(isset($_GET['search'])) {
?>
        <td></td>
<?php
    } else {
?>
        <td class="text-center" nowrap><?= $button_up  . '&nbsp;<span id="sort-' . $category->category_data['sort_order'] . '">' . $category->category_data['sort_order'] . '</span>&nbsp;' . $button_down ?></td>
<?php
    }

// begin buttons for category actions:
?>
        <td class="actions text-nowrap">
<?= $categories_modules->get_action_buttons($category->categories_id); ?>
        </td>
      </tr>

<?php
// buttons for category actions end

    }
?>
    <!-- categories table ends -->
    </tbody>
  </table>
<?php
    $products_count = 0;
    if (isset($_GET['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_gtin, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and((pd.products_name like '%" . tep_db_input($search) . "%') || (p.products_model like '%" . tep_db_input($search) . "%') ||  (p.products_gtin like '%" . tep_db_input($search) . "%')) order by pd.products_name");
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
    }

    $num_products = tep_db_num_rows($products_query);

    if ($num_products > 0) {

    // tabla:

//  require ('includes/classes/modules_pages.php');

  $products_modules = new modules_pages ('categories_products');

  $oscTemplate->addBlock('<script>' . $products_modules->get_javascript() . '</script>', 'admin_footer_scripts');


// echo $products_modules->get_javascript();

?>

    <!-- products table begins -->
    <table class="table table-sm table-striped table-hover" id="products-table">
    <thead>
      <tr class="table-info">
<?php
      echo $products_modules->get_table_header ();
?>
        <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
      </tr>
    </thead>

    <tbody>
<?php

    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $product = new product ($products['products_id']);

// Get categories_id for product if search
      if (isset($_GET['search'])) $cPath = $products['categories_id'];
?>
    <tr class="clickable">
<?php
      echo $products_modules->get_table_row ($products['products_id']);

?>
      <td class="text-center"><?php
      if ($product->data['products_status'] == '1') {
        echo '<i class="fas fa-circle fa-lg text-success"></i>' . '&nbsp;&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), 'action=products_setflag&flag=0&pID=' . $product->products_id . '&cPath=' . $cPath) . '">' . '<i class="far fa-circle fa-lg text-danger"></i>' . '</a>';
      } else {
        echo '<a href="' . tep_href_link(basename($PHP_SELF), 'action=products_setflag&flag=1&pID=' . $product->products_id . '&cPath=' . $cPath) . '">' . '<i class="far fa-circle fa-lg text-success"></i>' . '</a>&nbsp;&nbsp;' . '<i class="fas fa-circle fa-lg text-danger"></i>';
      }

      $product_categories = tep_generate_category_path($product->products_id, 'product');
      for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
        $category_path = '';
        for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
          $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
        }
        $category_path = substr($category_path, 0, -16);
      }
?></td>
      <td class="actions text-nowrap">
<?= $products_modules->get_action_buttons($products['products_id']); ?>
      </td>
    </tr>
<?php
    }
?>
  </tbody>
  </table>
    <!-- products table ends -->

<?php
    } else {
      if ($categories_count == 0 && !isset($_GET['search'])) {

?>
      <div class="alert alert-warning alert-dismissible"><?= TEXT_EMPTY_CATEGORY . ". " . TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
<?php
      } elseif (isset($_GET['search'])) {
?>
      <div class="alert alert-warning alert-dismissible"><?= TEXT_EMPTY_SEARCH ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
<?php
      } else {
?>
      <div class="alert alert-warning alert-dismissible"><?= TEXT_NO_CHILD_PRODUCTS ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
<?php

      }
    }

    $cPath_back = '';
    if (isset ($cPath_array) && sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';


?>

          <div class="d-flex justify-content-between">
            <div class="p-2">
              <h6><span class="badge badge-primary"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count ?></span></h6>              <h6><span class="badge badge-primary"><?= TEXT_PRODUCTS .'&nbsp;' . $products_count ?></span></h6>
            </div>
            <div class="p-2 text-right">
              <?php
      if (isset ($cPath_array) && sizeof($cPath_array) > 0) {
        echo tep_draw_button(IMAGE_BACK, 'far fa-arrow-alt-circle-left', tep_href_link(basename($PHP_SELF), $cPath_back . 'cID=' . $current_category_id));
      }
      if (!isset($_GET['search'])) {
        echo tep_draw_button(IMAGE_NEW_CATEGORY, 'fa fa-plus', tep_href_link('category.php', 'cPath=' . $cPath . '&action=new_category')) . tep_draw_button(IMAGE_NEW_PRODUCT, 'fa fa-plus', tep_href_link('product.php', 'cPath=' . $cPath . '&action=new_product'));
      } else {
        echo tep_draw_button(IMAGE_REMOVE_FILTER, 'fa fa-filter', tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath));
      }
?>
            </div>
          </div>

<?php

  include ("includes/classes/modal.php");

  $modal = new modal('categoriesModal');
  $modal->button_save = true;
  $modal->button_cancel = true;
  $modal->output();

?>


<?php

  $message_processing = TEXT_AJAX_PROCESSING;


  $jScript = <<<EOD
function ModalSortCategory (categoryID, new_position, old_position) {

  var params = {"categories_id" : categoryID, "action" : "categories_sort", "new_position" : new_position, "cPath" : "$cPath"};

  $.ajax({
    data:  params,
    url:   'categories.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html("$message_processing");
    },
    success:  function (response) {
      $("#categories-table tbody").html($(response).find("#categories-table tbody").html());
    }

  });
}

EOD;

  $oscTemplate->addBlock('<script>' . $categories_modules->get_javascript() . $jScript . '</script>', 'admin_footer_scripts');


  require('includes/template_bottom.php');
  require('includes/application_bottom.php');

