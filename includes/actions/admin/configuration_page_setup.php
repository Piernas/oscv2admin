<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2018 osCommerce

  Released under the GNU General Public License

  action for configuring display of in configure.php

*/

  class osC_Actions_configuration_page_setup {
    public static function execute() {
      if (defined ('CONFIG_SHOW_CONSTANTS')) {
        if (CONFIG_SHOW_CONSTANTS == true) {
          $current_value = true;
        } else {
          $current_value = false;
        } 
      } else {
        $current_value = false;
      }
      
      $contents = tep_draw_form ('setup', 'configuration.php','', 'get');
//      $contents .= TEXT_SHOW_KEYS . TEXT_SHOW_DESCRIPTIONS;
      $contents .= '<label>' . TEXT_SHOW_KEYS. '</label> ' .tep_draw_checkbox_field('cID', true, $current_value);

      $contents .= '</form>';

      
      echo '<span id="title"><strong><i class="fas fa-cog"></i> ' . TEXT_PAGE_SETUP . '</strong></span>';
      echo '<span id="content">' .  $contents . '</span>';
      exit(0);

    }
  }