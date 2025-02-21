<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2019 osCommerce

  Released under the GNU General Public License
  
  action for inserting a new product in product.php
  
  
*/

  class osC_Actions_languages_install {
    public static function execute() {
      global $messageStack;

      include_once ('includes/classes/languages_setup.php');

      $code = (isset($_GET['code']) ? $_GET['code'] : '');
      $languages__installer = new languages_setup ('');
      $languages__installer->install ($code);
      $name =($languages__installer->uninstalled[$code]['name']);

      $messageStack->add_session(sprintf(TEXT_INFO_LANGUAGE_INSTALLED, $name), 'success');

      tep_redirect(tep_href_link('languages.php', 'code=' . $code));
    }
  }
