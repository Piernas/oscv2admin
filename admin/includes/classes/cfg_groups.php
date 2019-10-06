<?php
  class cfg_groups {
    var $_modules = array();

    function __construct() {
      global $PHP_SELF;

      $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title, configuration_group_description from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
      while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
        $config_sections[] = array(
          'id' => $configuration_groups['cgID'],
          'title' => $configuration_groups['configuration_group_title'],
          'description' => $configuration_groups['configuration_group_description'],
          'link' => tep_href_link('configuration.php', 'gID=' . $configuration_groups['cgID'])
        );
      }

      $this->sections =$config_sections;

    }
    function getAll() {
      return $this->sections;
    }
    function getValues () {
      foreach ($this->sections as $key => $value) {
        $sections_array[] = array('id' => $value['id'],
                                      'text' => $value['title']);
      }
        return $sections_array;

    }
  }
