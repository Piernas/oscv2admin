<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
  
  $oscTemplate->buildBlocks();
  
  $OSCOM_Hooks->call('siteWide', 'injectRedirects');
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<base href="<?php echo ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN; ?>" />
<!--[if IE]><script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js', '', 'SSL'); ?>"></script><![endif]-->
<?php 
echo $OSCOM_Hooks->call('siteWide', 'injectSiteStart');
echo $oscTemplate->getBlocks('admin_header_scripts');

?>
</head>
<body>
  <?php echo $oscTemplate->getContent('admin_navigation'); // navbar ?>

<?php require('includes/header.php'); ?>

  <?= $OSCOM_Hooks->call('siteWide', 'injectNavbar') ?>

<div id="bodyWrapper" class="d-flex">
  <?= $OSCOM_Hooks->call('siteWide', 'injectBodyWrapperStart'); ?>
<?php
  echo $OSCOM_Hooks->call('siteWide', 'injectBodyContentStart');
?>
  <div id="BodyContent" class="container-fluid">
<!-- alertes here -->
<?php 

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }

