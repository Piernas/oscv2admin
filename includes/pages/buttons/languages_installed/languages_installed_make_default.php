<?php

  class languages_installed_make_default {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $javascript;
    var $enabled = false;

    function __construct($id = 0) {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));
      $this->cfg_key = 'ADMIN_PAGES_BUTTON_' . strtoupper($this->code) . '_';
      $this->javascript = $this->get_javascript();
      
      // Localize:
      $this->title = 'Make default';
      $this->description = 'Make language default';
      if ( defined($this->cfg_key . 'STATUS')) {
        $this->enabled = (constant($this->cfg_key . 'STATUS') == 'True');
        $this->align = constant($this->cfg_key . 'ALIGN');
        $this->sort_order = constant($this->cfg_key . 'SORT_ORDER');
        $this->value = $this->get_value($id);
      }
    }
  
    function get_value ($id) {
      global $languages;
      $button ="assss";
      if (DEFAULT_LANGUAGE == $id) {
        $button = '                  <i class="fas fa-star fa-lg text-success"></i>' . PHP_EOL;
      } else {
        $button = '                  <a href="javascript:MakeDefaultLanguage(\'' . $id . '\')"><i class="far fa-star fa-lg text-disabled"></i></a>' . PHP_EOL;
      }
      return $button;
    }
    
    function get_javascript () {
      global $PHP_SELF, $cPath;
      $message_processing = TEXT_AJAX_PROCESSING;
      $message_close = IMAGE_CLOSE;
      $url = basename($PHP_SELF);
      $jScript = <<<EOD
function MakeDefaultLanguage (code){

  var params = {"code" : code, "action" : "languages_make_default"};

  $.ajax({
    data:  params,
    url:   'languages.php',
    type:  'get',
    cache: false,
    beforeSend: function () {
    },
    success:  function (response) {
      $("#installed-languages-table tbody").html($(response).find("#installed-languages-table tbody").html());
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
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', '" . $this->cfg_key . 'SORT_ORDER' . "', '1', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', '" . $this->cfg_key . 'ALIGN' . "', 'Left', 'Alignment', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Right\', \'Center\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array(
        $this->cfg_key . 'STATUS', 
        $this->cfg_key . 'SORT_ORDER',
        $this->cfg_key . 'ALIGN'
        );
    }
  }