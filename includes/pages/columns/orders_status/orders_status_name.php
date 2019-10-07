<?php

  class orders_status_name {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct($id = 0) {
      global $languages_id, $language;
      
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->cfg_key = 'ADMIN_PAGES_COLUMN_' . strtoupper($this->code) . '_';
      $this->title = constant ($this->cfg_key .  'TITLE');
      $this->description = constant ($this->cfg_key . 'DESCRIPTION');

      
      if ( defined($this->cfg_key . 'STATUS')) {
        $this->enabled = (constant($this->cfg_key . 'STATUS') == 'True');
        $this->align = constant($this->cfg_key . 'ALIGN');
        $this->sort_order = constant($this->cfg_key . 'SORT_ORDER');
        $this->value = $this->get_value($id);
      }
    }
  
    function get_value ($id) {
      global $languages_id;
      $status_name ="";
      if ($id > 0) {
        $orders_status_query = tep_db_query ("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' and orders_status_id = " . $id);
        while ($orders_status = tep_db_fetch_array($orders_status_query)) {
          $status_name = $orders_status['orders_status_name'];
        }
        if (DEFAULT_ORDERS_STATUS_ID == $id) {
          $status_name = '<strong>' . $status_name . ' (' . TEXT_DEFAULT . ')</strong>';
        }
        
      }
        return $status_name;

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
