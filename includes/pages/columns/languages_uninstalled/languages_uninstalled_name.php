<?php

  class languages_uninstalled_name {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct($code = 0) {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));
      $this->cfg_key = 'ADMIN_PAGES_COLUMN_' . strtoupper($this->code) . '_';
      $this->title = constant($this->cfg_key . 'TITLE');
      $this->description = constant($this->cfg_key . 'DESCRIPTION');

      
      if ( defined($this->cfg_key . 'STATUS')) {
        $this->enabled = (constant($this->cfg_key . 'STATUS') == 'True');
        $this->align = constant($this->cfg_key . 'ALIGN');
        $this->sort_order = constant($this->cfg_key . 'SORT_ORDER');
        $this->value = $this->get_value($code);
      }
    }
  
    function get_value ($code) {
      global $languages_setup;
      $style ="";
      if ($code != '0') {
        if (DEFAULT_LANGUAGE == $code) {
          $style = " (default)";
        }

        return $languages_setup->uninstalled[$code]['name'] . $style;
      }
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
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', '" . $this->cfg_key . 'ALIGN' . "', 'Center', 'Alignment', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Right\', \'Center\'), ', now())");
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
