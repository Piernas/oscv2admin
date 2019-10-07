<?php

  class button_move {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $javascript;
    var $enabled = false;

    function __construct($id=0) {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));
      $this->cfg_key = 'ADMIN_PAGES_BUTTON_' . strtoupper($this->code) . '_';
      $this->javascript = $this->get_javascript();
      $this->title = constant($this->cfg_key . 'TITLE');
      $this->description = constant($this->cfg_key . 'DESCRIPTION');
      
      if ( defined($this->cfg_key . 'STATUS')) {
        $this->enabled = (constant($this->cfg_key . 'STATUS') == 'True');
        $this->sort_order = constant($this->cfg_key . 'SORT_ORDER');
      }
    }
  
    function get_value ($id) {
      global $product;
      $button ="";
      if ($id > 0) {
        // get admin preview key

        $button = '                  <a id="product-move-' . $product->products_id . '" href="javascript:ModalMoveProduct(' . $product->products_id . ');"><i class="fas fa-arrows-alt fa-lg text-primary"></i></a>' . PHP_EOL;
      }
      return $button;
    }
    
    function get_javascript () {
      global $PHP_SELF, $cPath;
      $message_processing = TEXT_AJAX_PROCESSING;
      $message_cancel = IMAGE_CANCEL;
      $url = basename($PHP_SELF);
      $jScript = <<<EOD
function ModalMoveProduct(productsID){
  // Formulario
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="products_form" name="products" action="categories.php?action=products_move_confirm&cPath=$cPath" method="post">')
  // Botones
  $("#ButtonCancelText").text("$message_cancel");
  $("#ModalButtonDelete").hide();
  $("#ModalButtonSave").hide();
  $("#ModalButtonMove").show();
  $("#ModalButtonCopy").hide();

  var params = {"pID" : productsID, "action" : "products_move", "cPath" : "$cPath"};

  $.ajax({
    data:  params,
    url:   '$url',
    type:  'get',
    cache: false,
    beforeSend: function () {
      $(".modal-body").html("$message_processing");
    },
    success:  function (response) {
      $(".modal-title").html($(response).filter('#title').html());
      $(".modal-body").html($(response).filter('#content').html());
      $("#categoriesModal").modal('show')
    }
  });
}
EOD;

      return $jScript;

    }

    function check() {
      return defined(constant($this->cfg_key . 'STATUS'));
    }
    
    function isEnabled() {
      return $this->enabled;
    }

    function set_sort_order($sort_order) {
      $this->sort_order = $sort_order;
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $this->sort_order . "', last_modified = now() where configuration_key = '" .  $this->cfg_key . "SORT_ORDER" . "'");
    }

    function toggle() {
       $this->enabled = !$this->enabled;
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . ($this->enabled ? "True": "False") . "', last_modified = now() where configuration_key = '" .  $this->cfg_key . "STATUS" . "'");
    }
    
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Generic Text Footer Module', '" . $this->cfg_key . 'STATUS' . "', 'True', 'Do you want to enable the Generic Text content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', '" . $this->cfg_key . 'SORT_ORDER' . "', '4', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array(
        $this->cfg_key . 'STATUS', 
        $this->cfg_key . 'SORT_ORDER'
        );
    }
  }