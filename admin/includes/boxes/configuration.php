<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'heading' => BOX_HEADING_CONFIGURATION,
    'apps' => array(
      array(
        'code' => 'configuration_groups.php',
        'title' => BOX_CONFIGURATION_CONFIGURATION,
        'link' => tep_href_link('configuration_groups.php')
      ),
      array(
        'code' => 'modules_groups.php',
        'title' => BOX_HEADING_MODULES,
        'link' => tep_href_link('modules_groups.php')
      ),
      array(
        'code' => 'modules_content.php',
        'title' => BOX_HEADING_MODULES_CONTENT,
        'link' => tep_href_link('modules_content.php')
      ),
      array('code' => 'modules_hooks.php',
        'title' => BOX_HEADING_MODULES_HOOKS,
        'link' => tep_href_link('modules_hooks.php')
      ),
      array(
        'code' => 'administrators.php',
        'title' => BOX_CONFIGURATION_ADMINISTRATORS,
        'link' => tep_href_link('administrators.php')
      ),
      array(
        'code' => 'store_logo.php',
        'title' => BOX_CONFIGURATION_STORE_LOGO,
        'link' => tep_href_link('store_logo.php'),
      )
    )
  );

?>
