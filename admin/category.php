<?php

// ACTION: edit_category



  require ('includes/application_top.php');  
  require ('includes/template_top.php');
  require ('includes/classes/category.php');
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $category_id = (int)$_GET['categories_id'];
  $category = new category ($category_id);
?>
  <div class="row">
    <div class="col-sm-12 pageHeading"><i class="fas fa-edit"></i> <?= TEXT_INFO_HEADING_EDIT_CATEGORY ?></div>
        </div>
<?= tep_draw_form('categories', basename($PHP_SELF), 'action=categories_update&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') ?>
<?=  tep_draw_hidden_field('categories_id', $category->categories_id) ?>
<?php
  if ($action == 'edit_category') {


        $languages = tep_get_languages();
        $category_inputs_string = $category_description_string = $category_seo_description_string = $category_seo_keywords_string = $category_seo_title_string = '';
        $columns = (sizeof($languages) > 1 ? "6" : "12"); // returns true

        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']);
          $category_inputs_string .= tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', $category->category_description [$languages[$i]['id']]['categories_name']) . '</div>';

          $category_seo_title_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_title[' . $languages[$i]['id'] . ']', $category->category_description [$languages[$i]['id']]['categories_seo_title']) . '</div>';
          $category_description_string .=  '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '', '', 'style="vertical-align: top;"') . '&nbsp;' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', null, '80', '10', $category->category_description [$languages[$i]['id']]['categories_description']) . '</div>';
          $category_seo_description_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '', '', 'style="vertical-align: top;"') . '&nbsp;' . tep_draw_textarea_field('categories_seo_description[' . $languages[$i]['id'] . ']', null, '80', '10', $category->category_description [$languages[$i]['id']]['categories_seo_description']) . '</div>';
          $category_seo_keywords_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_keywords[' . $languages[$i]['id'] . ']', $category->category_description [$languages[$i]['id']]['categories_seo_keywords'], 'placeholder="' . PLACEHOLDER_COMMA_SEPARATION . '"') . '</div>';
        }
?>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_EDIT_CATEGORIES_NAME ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_inputs_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_EDIT_CATEGORIES_DESCRIPTION ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_description_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_EDIT_CATEGORIES_SEO_TITLE ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_seo_title_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_SEO_DESCRIPTION ?> <i class="fas fa-info-circle" title="<?= TEXT_EDIT_CATEGORIES_SEO_DESCRIPTION ?>" data-toggle="tooltip"></i></div>
  <div class="card-body">
    <div class="row">
      <?=$category_seo_description_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_EDIT_CATEGORIES_IMAGE?></div>
  <div class="card-body">
    <div class="row">
    <div class="col-sm-6">
      <?= tep_image(HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $category->image, $category->category_description [$languages_id]['categories_name']) ?>
      <?= DIR_WS_CATALOG_IMAGES . '<strong>' . $category->image . '</strong>' ?></div>
      <?=  tep_draw_file_field('categories_image') ?>
    </div>
  </div>
</div>
<?= tep_draw_hidden_field('sort_order', $category->category_data['sort_order']) ?>
<div class="text-right"><?=  tep_draw_button(IMAGE_SAVE, 'fa fa-floppy-o') . tep_draw_button(IMAGE_CANCEL, 'fa fa-times', tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath . '&cID=' . $category->categories_id)); ?></div>

<?php

} else {

// ACTION: new_category



?>
        <div class="row">
          <div class="col-sm-12 pageHeading"><i class="fas fa-plus"></i> <?= TEXT_INFO_HEADING_NEW_CATEGORY ?></div>
        </div>
<?= tep_draw_form('newcategory', basename($PHP_SELF), 'action=categories_insert&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') ?>

<?php
        $languages = tep_get_languages();
        $category_inputs_string = $category_description_string = $category_seo_description_string = $category_seo_keywords_string = $category_seo_title_string = '';
        $columns = (sizeof($languages) > 1 ? "6" : "12"); // returns true

        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', null, "required") .'</div>';
          $category_description_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '', '', 'style="vertical-align: top;"') . '&nbsp;' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', null, '80', '10') .'</div>';
          $category_seo_description_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '', '', 'style="vertical-align: top;"') . '&nbsp;' . tep_draw_textarea_field('categories_seo_description[' . $languages[$i]['id'] . ']', null, '80', '10') .'</div>';
          $category_seo_keywords_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_keywords[' . $languages[$i]['id'] . ']', NULL, 'style="width: 300px;" placeholder="' . PLACEHOLDER_COMMA_SEPARATION . '"') .'</div>';
          $category_seo_title_string .= '<div class="col-sm-' . $columns . '">' . tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_title[' . $languages[$i]['id'] . ']') .'</div>';
        }
?>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_EDIT_CATEGORIES_NAME ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_inputs_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_DESCRIPTION ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_description_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_SEO_TITLE ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_seo_title_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_SEO_DESCRIPTION ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_seo_description_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_SEO_KEYWORDS ?></div>
  <div class="card-body">
    <div class="row">
      <?=$category_seo_keywords_string ?>
    </div>
  </div>
</div>

<div class="card card-info">
  <div class="card-header text-white bg-info"><?=TEXT_CATEGORIES_IMAGE?></div>
  <div class="card-body">
    <div class="row">
    <div class="col-sm-6">
      <?=  tep_draw_file_field('categories_image') ?>
    </div>
  </div>
</div>
<?= tep_draw_hidden_field ('sort_order', '9999') ?>

<p><?=  tep_draw_button(IMAGE_SAVE, 'fa fa-floppy-o') . tep_draw_button(IMAGE_CANCEL, 'fa fa-times', tep_href_link(basename($PHP_SELF), 'cPath=' . $cPath)) ?></p>

<?php
    }