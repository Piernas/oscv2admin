<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2018 osCommerce

  Released under the GNU General Public License
*/

  class osC_Actions {
    public static function parse($module, $_site='shop') {
      $module = basename($module);

      if ( !empty($module) && file_exists(DIR_FS_CATALOG . 'includes/actions/' . $_site . '/' . $module . '.php') ) {
        include(DIR_FS_CATALOG . 'includes/actions/' . $_site . '/' . $module . '.php');

        call_user_func(array('osC_Actions_' . $module, 'execute'));
      }
    }
  }
  