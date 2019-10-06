<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>

<?php
  echo $OSCOM_Hooks->call('siteWide', 'injectBeforeFooter');


  require('includes/footer.php');

  echo $OSCOM_Hooks->call('siteWide', 'injectSiteEnd');
?>

<br />
  </div> <!-- bodyWrapper //-->
<?php 
  echo $OSCOM_Hooks->call('siteWide', 'injectAfterFooter');
  
  echo $OSCOM_Hooks->call('siteWide', 'injectSiteEnd');
  
  echo $oscTemplate->getBlocks('admin_footer_scripts'); 
  ?>

</body>
</html>
