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

  ////////////////////////////////////////


  $currencies = new currencies();

  $product_id = (isset($_GET['pID']) ? (int)$_GET['pID'] : '');

  $product = new product ($product_id);

  require('includes/template_top.php');

  $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                   'text' => $manufacturers['manufacturers_name']);
  }

  $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
  $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
  while ($tax_class = tep_db_fetch_array($tax_class_query)) {
    $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                               'text' => $tax_class['tax_class_title']);
  }

  $languages = tep_get_languages();

//  if (!isset($product->data['products_status'])) $product->data_status = false;
  
  switch ($product->data['products_status']) {
    case '0': $in_status = false; $out_status = true; break;
    case '1':
    default: $in_status = true; $out_status = false;
  }

  $form_action = (isset($_GET['pID'])) ? 'products_update' : 'products_insert';
?>


<?php
  if (isset($_GET['pID'])) {
    $title = '<i class="fa fa-edit fa-lg"></i> ' . sprintf(TEXT_EDIT_PRODUCT, $product->products_id , tep_output_generated_ul_category_path($product_id, 'product')) ;
  } else {
    $title = '<i class="fa fa-plus fa-lg"></i> ' . sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id));
  }
?>
    <?php echo tep_draw_form('new_product', 'product.php', 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); ?>
    <div class="card my-3">
      <div id="page-heading" class="card-header">
        <div class="d-flex justify-content-between">
          <div class="col-sm-8 pageHeading"><?= $title ?></div>
          <div class="col-sm-4 pageHeading  text-right"><?= tep_draw_button(IMAGE_SAVE, 'fas fa-save') . tep_draw_button(IMAGE_CANCEL, 'fas fa-times', tep_href_link('categories.php', 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')),null,null,'btn-info btn-sm ml-2'); ?></div>
        </div>
      </div>
      <div id="tabs-heading" class="card-header">

      <ul id="productTabsMain" class="nav nav-tabs card-header-tabs">
        <li class="nav-item"><a class="nav-link active" data-target="#section_general_content" data-toggle="tab"><?= SECTION_HEADING_GENERAL ?></a></li>
        <li class="nav-item"><a class="nav-link" data-target="#section_data_content" data-toggle="tab"><?= SECTION_HEADING_DATA ?></a></li>
        <li class="nav-item"><a class="nav-link" data-target="#section_images_content" data-toggle="tab"><?= SECTION_HEADING_IMAGES ?></a></li>
      </ul>
      </div>

      <div class="tab-content">
        <div id="section_general_content" class="tab-pane fade show active" role="tabpanel">
          <div class="card">
            <div class="card-body">
              <div id="productLanguageTabs">
<?php
/*
                <ul class="nav nav-pills">

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
                  <li class="nav-item"><a class="nav-link<?= ($i === 0 ? ' active' : '') ?>" data-target="#section_general_content_<?= $languages[$i]['directory'] ?>" data-toggle="tab"><?= tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '','','',false) . '&nbsp;' . $languages[$i]['name'] ?></a></li>
<?php
    }

                </ul>
    */
?>
                <div class="tab-content">
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      
      if (sizeof($languages) > 1) {
        $language_identifier = '<div class="input-group-prepend"><span class="input-group-text">' . $languages[$i]['code'] . '</span></div>';
      } else {
        $language_identifier ="";
      }
?>
      <div class="row">
        <div class="col-md-6">
          <label><?= TEXT_PRODUCTS_NAME ?></label>
          <div class="form-group">
            <div class="input-group input-group-sm">
              <?= $language_identifier . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (empty($product->products_id) ? '' : $product->description[$languages[$i]['id']]['products_name']), 'required'); ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">

      <div class="form-group">
        <label><?= TEXT_PRODUCTS_URL . '(' . $languages[$i]['name'] . ')' . ' <small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>' ?></label><?= tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (empty($product->products_id) ? '' : stripslashes($product->description[$languages[$i]['id']]['products_url']))); ?>
      </div>
      </div>
      </div>
<?php
    }
?>
    <div class="row">
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
      <div class="col-lg-6">
      <div class="form-group">
        <label><?= TEXT_PRODUCTS_DESCRIPTION . '(' . $languages[$i]['name'] . ')' ?></label><?= tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', null, '70', '7', (empty($product->products_id) ? '' : $product->description[$languages[$i]['id']]['products_description']), 'data-edit="editable"'); ?>
      </div>
      </div>
<?php
    }
?>

    </div>

    <div class="row">

<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

?>
        <div class="col-md-6">
          <label><?= TEXT_PRODUCTS_SEO_TITLE ?></label>
          <div class="form-group">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend"><span class="input-group-text"><?= $languages[$i]['code'] ?></span></div>
              <?= tep_draw_input_field('products_seo_title[' . $languages[$i]['id'] . ']', (empty($product->products_id) ? '' :$product->description[$languages[$i]['id']]['products_seo_title'])) ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <label><?= TEXT_PRODUCTS_SEO_KEYWORDS ?></label>
          <div class="form-group">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend"><span class="input-group-text"><?= $languages[$i]['code'] ?></span></div>
              <?= tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (empty($product->products_id) ? '' : stripslashes($product->description[$languages[$i]['id']]['products_url']))); ?>
            </div>
          </div>
        </div>
<?php
    }
?>
    </div>
    <div class="row">

<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

?>
        <div class="col-md-6">

                      <div class="form-group">
                        <label><?= TEXT_PRODUCTS_SEO_DESCRIPTION ?> <i class="fa fa-info-circle fa-lg text-info" title="<?= TEXT_PRODUCTS_SEO_DESCRIPTION_TOOLTIP?>" data-toggle="tooltip"></i></label><?= tep_draw_textarea_field('products_seo_description[' . $languages[$i]['id'] . ']', null, '70', '10', (empty($product->products_id) ? '' : $product->description[$languages[$i]['id']]['products_seo_description'])) ?>
                      </div>
                      </div>
<?php
    }
?>
      </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php
/*

        <div id="section_seo_content" class="tab-pane fade" role="tabpanel">
          <div class="card">
            <div class="card-body">
              <div id="seoLanguageTabs">
                <ul class="nav nav-tabs">
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
                <li class="nav-item"><a class="nav-link<?= ($i === 0 ? ' active' : '') ?>" data-target="#section_seo_content_<?= $languages[$i]['directory'] ?>" data-toggle="tab"><?= tep_image(tep_catalog_href_link('includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], '','','',false) . '&nbsp;' . $languages[$i]['name'] ?></a></li>
<?php
    }
?>
                </ul>

                <div class="tab-content">
<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

                  <div id="section_seo_content_<?php echo $languages[$i]['directory']; ?>" class="tab-pane <?= ($i === 0 ? 'active' : ''); ?>">
                      <div class="form-group">
                        <label><?= TEXT_PRODUCTS_SEO_TITLE ?> <i class="fa fa-info-circle fa-lg text-info" title="<?= TEXT_PRODUCTS_SEO_TITLE_TOOLTIP?>" data-toggle="tooltip"></i></label><?= tep_draw_input_field('products_seo_title[' . $languages[$i]['id'] . ']', (empty($product->products_id) ? '' :$product->description[$languages[$i]['id']]['products_seo_title'])) ?>
                      </div>
                      <div class="form-group">
                        <label><?= TEXT_PRODUCTS_SEO_DESCRIPTION ?> <i class="fa fa-info-circle fa-lg text-info" title="<?= TEXT_PRODUCTS_SEO_DESCRIPTION_TOOLTIP?>" data-toggle="tooltip"></i></label><?= tep_draw_textarea_field('products_seo_description[' . $languages[$i]['id'] . ']', null, '70', '10', (empty($product->products_id) ? '' : $product->description[$languages[$i]['id']]['products_seo_description'])) ?>
                      </div>
                      <div class="form-group">
                       <label><?= TEXT_PRODUCTS_SEO_KEYWORDS ?> <i class="fa fa-info-circle fa-lg text-info" title="<?= TEXT_PRODUCTS_SEO_KEYWORDS_TOOLTIP?>" data-toggle="tooltip"></i></label><?= tep_draw_input_field('products_seo_keywords[' . $languages[$i]['id'] . ']', $product->description[$languages[$i]['id']]['products_seo_keywords'], 'placeholder="' . PLACEHOLDER_COMMA_SEPARATION . '"'); ?>
                      </div>
                  </div>
<?php
    }
?>
                </div>
              </div>
            </div>
          </div>
        </div>
*/
?>
<script>
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
/*
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('#products_date_available').datepicker({
  dateFormat: 'yy-mm-dd'
});
*/
</script>
        <div id="section_data_content" class="tab-pane fade" role="tabpanel">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_STATUS ?></label><br /><?= tep_draw_radio_field('products_status', '1', $in_status) ?>&nbsp;<?= TEXT_PRODUCT_AVAILABLE ?>&nbsp;<?= tep_draw_radio_field('products_status', '0', $out_status) ?>&nbsp;<?= TEXT_PRODUCT_NOT_AVAILABLE; ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_MODEL?></label><?= tep_draw_input_field('products_model', $product->data['products_model']); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_GTIN ?></label><?= tep_draw_input_field('products_gtin', $product->data['products_gtin']); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_DATE_AVAILABLE ?></label><?= tep_draw_input_field('products_date_available', $product->data['products_date_available'], 'id="products_date_available"', 'date'); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_QUANTITY ?></label><?= tep_draw_input_field('products_quantity', $product->data['products_quantity']); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_TAX_CLASS ?></label><?= tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $product->data['products_tax_class_id'], 'onchange="updateGross()"'); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_PRICE_NET ?></label><?= tep_draw_input_field('products_price', $product->data['products_price'], 'onkeyup="updateGross()"'); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_PRICE_GROSS ?></label><?= tep_draw_input_field('products_price_gross', $product->data['products_price'], 'onkeyup="updateNet()"'); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_MANUFACTURER ?></label><?= tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $product->data['manufacturers_id']); ?></div>
                <div class="form-group col-sm-6 col-md-4 col-lg-3"><label><?= TEXT_PRODUCTS_WEIGHT ?></label><?= tep_draw_input_field('products_weight', $product->data['products_weight']); ?></div>
                <?= tep_draw_hidden_field('products_date_added', (tep_not_null($product->data['products_date_added']) ? $product->data['products_date_added'] : date('Y-m-d')))?>
              </div>
            </div>
          </div>
        </div>

<script type="text/javascript">
updateGross();
</script>

        <div id="section_images_content" class="tab-pane" role="tabpanel">
        <div class="card">
        <div class="card-body">
          <div class="piList row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="card">
                <div class="card-header text-white bg-primary mb-3"><?= TEXT_PRODUCTS_MAIN_IMAGE . ' <small>(' . SMALL_IMAGE_WIDTH . ' x ' . SMALL_IMAGE_HEIGHT . 'px)</small>' ?> </div>
                <div class="card-body">
                  <div class="form-group">
<?php
//  $product->get_images();
?>
                    <div class="text-center">
<?php
  if (!is_null($product->data['products_image'])) {
?>
                      <a href="<?= HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $product->data['products_image'] ?>" target="_blank" id="new-image-href-0">
                      <?= tep_image(HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $product->data['products_image'], $product->description[$languages_id]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'id="new-image-0"', false, 'center-block')  ?>
                      <p id="new-image-filename-0"><?= $product->data['products_image'] ?></p>
                      </a>
<?php
  } else {
?>
                      <a target="_blank" id="new-image-href-0">
                      <img src="images/no_image.png"  alt="<?= TEXT_NO_IMAGE ?>" width="<?= SMALL_IMAGE_WIDTH ?>" height="<?= SMALL_IMAGE_HEIGHT ?>" />
                      <p id="new-image-filename-0"><?= TEXT_NO_IMAGE ?></p>
                      </a>
<?php
  }
?>
                      <?= tep_draw_file_field('products_image', false, 'onchange="loadImage(this, 0)"'); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<?php
    $pi_counter = 0;
if (isset ($product->products_images)) {
    foreach ($product->products_images as $pi) {
      $pi_counter++;

?>
              <div class="col-sm-6 col-md-4 col-lg-3" id="piId<?= $pi_counter ?>">
                <div class="card">
                  <div class="card-header text-white bg-info mb-3">
                    <div class="row">
                      <div class="col-sm-9"><?= TEXT_PRODUCTS_LARGE_IMAGE ?></div>
                      <div class="col-sm-3">
                        <a href="#" onclick="showPiDelConfirm('<?= $pi_counter ?>');return false;" style="float: right;"><i class="fa fa-trash fa-lg text-white"></i></a>
                        <i class="fa fa-arrows fa-lg"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center">
                      <a href="<?= HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $pi['image'] ?>" id="new-image-href-<?= $pi['id'] ?>" target="_blank">
                      <?= tep_image(HTTPS_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $pi['image'], $pi['image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'id="new-image-' . $pi['id'] . '"', false, 'center-block')  ?>
                      <p id="new-image-filename-<?= $pi['id'] ?>"><?= $pi['image'] ?></p>
                      </a>
                      <?= tep_draw_file_field('products_image_large_' .$pi['id'], false, 'onchange="loadImage(this, ' . $pi['id'] . ')"'); ?>
                      <br />
                      <?= TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT ?>
                      <?= tep_draw_textarea_field('products_image_htmlcontent_' . $pi['id'], null, '70', '3', $pi['htmlcontent']) ?>
                    </div>
                  </div>
                </div>
              </div>
<?php
    }
}
?>
            <div class="col-sm-6 col-md-4 col-lg-3 text-center" id="last">
              <?= tep_draw_button(TEXT_PRODUCTS_ADD_LARGE_IMAGE, 'fa fa-plus fa-lg', null, null, array('type' => 'button', 'params'=>'onclick="addNewPiForm();"'),"btn-info") ?>
            </div>
          </div>
          </div>
          </div>
        </div>
      </div>
    </div>
    </form>
<?php
  include ("includes/classes/modal.php");
  
  $modal = new modal('productModal');
  $modal->button_save = true;
  $modal->button_delete = false;
  $modal->button_cancel = true;
  $modal->output();


?>



<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
<script type="text/javascript">
/*
$('.piList').sortable({
  containment: 'parent'
});
*/
var piSize = <?php echo $pi_counter; ?>;

function addNewPiForm() {
  piSize++;
  content = '<div class="col-sm-6 col-md-4 col-lg-3"  id="piId' + piSize +  '"><div class="card card-info mb-3"><div class="card-header text-white bg-info mb-3"><div class="row"><div class="col-sm-9"><?= TEXT_PRODUCTS_LARGE_IMAGE ?></div>';
  content += '<div class="col-sm-3"><a href="#" onclick="showPiDelConfirm(' + piSize + ');return false;" style="float: right;"><i class="fa fa-trash fa-lg text-white"></i></a><i class="fa fa-arrows fa-lg"></i></div></div></div>';
  content += '<div class="card-body"><div class="text-center"><img id="new-image-' + piSize + '" src="images/no_image.png" height="<?= SMALL_IMAGE_HEIGHT ?>" width="<?= SMALL_IMAGE_WIDTH ?>" />';
  content += '<p id="new-image-filename-' + piSize + '"><?= TEXT_NO_IMAGE ?></p>';
  content += '<label class="btn btn-info"><?= TEXT_BROWSE ?><input class="d-none" type="file" onchange="loadImage(this, ' + piSize+ ')" name="products_image_large_new_' + piSize + '" /></label><br /> <?= TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT ?><textarea class="form-control input-sm" name="products_image_htmlcontent_new_' + piSize + '" wrap="soft" cols="70" rows="3"></textarea>';
  content += '</div>';
  content += '</div></div></div>';
//  $('.piList').append(content)
  $('#last').before(content)
}

function loadImage(input, piSize) {
  var reader = new FileReader();
  reader.onload = function(e) {
    $('#new-image-' + piSize).attr('src', e.target.result);
  }
  reader.readAsDataURL(input.files[0]);
  var fullPath = input.value;
  var filename = fullPath.replace(/^.*[\\\/]/, '');
  $('#new-image-filename-' + piSize).html(filename);
  //  Browsers doe not allow to get the file path so we remove the href
  $('#new-image-href-' + piSize).removeAttr("href");
}

var piDelConfirmId = 0;

function showPiDelConfirm(piId) {
  piDelConfirmId = piId;
  $("#productModal").modal('show');
}

$(document).on("click", "#ModalButtonDelete", function(event){
  $('#piId' + piDelConfirmId).effect('blind').remove();
  $("#productModal").modal('hide');

});

</script>
<script>
/*
$(":submits").click(function () {
    $('input:invalid').each(function () {
        // Find the tab-pane that this element is inside, and get the id
        var closest = $(this).closest('.tab-pane');
        var id = closest.attr('id');
        // Find the link that corresponds to the pane and have it show
        $('a[data-target="#' + id + '"]').tab('show');

        // Only want to do it once
        return false;
    });
});
*/
$(":submit").click(function () {
    $('input:invalid').each(function () {
        // Find the tab-pane that this element is inside, and get the id
        var closest1 = $(this).closest('.tab-pane');
        var id1 = closest1.attr('id');
        var closest2 = closest1.parent().closest('.tab-pane');
        var id2 = closest2.attr('id');
console.log (id1 +id2);
        
        // Find the link that corresponds to the pane and have it show
        $('a[data-target="#' + id1 + '"]').tab('show');

        $('a[data-target="#' + id2 + '"]').tab('show');

        // Only want to do it once
        return false;
    });
});
  tinymce.init({
    selector: 'textarea[data-edit="editable"]',
    menubar:false,
    statusbar: false,
    height: 500,
    forced_root_block : 'p',
    theme: 'silver',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table contextmenu directionality',
      'emoticons template paste colorpicker textpattern imagetools codesample toc'
    ],
    toolbar1: 'styleselect fontsizeselect| bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image '
   });
</script>