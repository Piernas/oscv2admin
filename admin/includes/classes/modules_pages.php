<?php

  class modules_pages {
    var $columns_modules_class;
    var $buttons_modules_class;
    function __construct ($group) {
      global $language;
      $this->group = $group;

      $this->columns_modules_class = array();

      foreach (glob(DIR_FS_CATALOG . "includes/pages/columns/" . $this->group . "/*.php") as $filename) {
        if ( file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/columns/' . $group . '/' . basename ($filename) )) {
          include(DIR_FS_CATALOG_LANGUAGES . $language . '/pages/columns/' . $group . '/' . basename ($filename));
        }
        $this->columns_modules_class[] = basename ($filename, ".php");
      }

      $this->buttons_modules_class = array();
      foreach (glob(DIR_FS_CATALOG . "includes/pages/buttons/" . $this->group . "/*.php") as $filename) {
        $this->buttons_modules_class[] = basename ($filename, ".php");
      }
    }

    function get_page_header () {
      
      return false;
    }

    function get_table_header () {
      $header = null;
      $column_headers = array();

      foreach ($this->columns_modules_class as $column_header) {

          include_once DIR_FS_CATALOG . "includes/pages/columns/" . $this->group . "/" . $column_header . ".php";
          
          $new_column = new $column_header(0);
          if ($new_column->enabled != true ) {
            $new_column->enabled = false;
            $new_column->sort_order = 0;
          }
          $column_headers[] = (array)$new_column;
      }

      usort($column_headers, function ($a, $b) {return $a['sort_order'] > $b['sort_order'];});

      foreach ($column_headers as $name) {
        if ($name['enabled'] === true){
          $header .= '        <th>' . $name['title'] . '</th>' . PHP_EOL;
        }
      }

      return $header;
    }

    function get_javascript () {
      
      $javascript = null;
      $jscripts = array();

      if ( isset ($this->buttons_modules_class) && count($this->buttons_modules_class) > 0) {
        foreach ($this->buttons_modules_class as $action_script) {
          include_once DIR_FS_CATALOG . "includes/pages/buttons/" . $this->group . "/" . $action_script . ".php";
          $new_script= new $action_script();

          if ($new_script->enabled != true ) { // || !method_exists ($new_script, 'get_javascript')
            $new_script->enabled = false;
            $new_script->sort_order = 0;
          }
          
          if (method_exists ($new_script, 'get_javascript')) $jscripts[] = (array)$new_script;

        }

        usort($jscripts, function ($a, $b) {return $a['sort_order'] > $b['sort_order'];});

        foreach ($jscripts as $value) {
          if ($value['enabled'] === true){
            $javascript .= $value ['javascript'];
          }
        }
      }
      
      return $javascript;
    }



    function get_table_row ($row_id) {
      $row = null;
      $column_values = array();
      foreach ($this->columns_modules_class as $column_header) {
        $new_column = new $column_header($row_id);
        if ($new_column->enabled != true ) {
          $new_column->enabled = false;
          $new_column->sort_order = 0;
        }
        $column_values[] = (array)$new_column;
      }

      usort($column_values, function ($a, $b) {return $a['sort_order'] > $b['sort_order'];});


      foreach ($column_values as $value) {

        if ($value['enabled'] === true){
          $row .= '      <td>' . $value['value'] . '</td>' . PHP_EOL;
        }
      }
      return $row;

    }

    function get_action_buttons ($id) {
      $action_buttons = array();
      $button = null;
      foreach ($this->buttons_modules_class as $action_button) {
        include_once DIR_FS_CATALOG . "includes/pages/buttons/" . $this->group . "/" . $action_button . ".php";
        $new_button = new $action_button($id);

        if (defined ($new_button->cfg_key . 'STATUS')) {
          $new_button->value = $new_button->get_value($id);
          $action_buttons[] = (array)$new_button;
          
        }
      }      

      usort($action_buttons, function ($a, $b) {return $a['sort_order'] > $b['sort_order'];});


      foreach ($action_buttons as $value) {
//                  print_r($value);

        if ($value['enabled'] === true){
          $button .= $value['value'];
        }
      }
      return $button;
    }

  }