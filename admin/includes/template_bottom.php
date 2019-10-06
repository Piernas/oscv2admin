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
?>
<hr>
<?php
  require('includes/footer.php');
  
  echo $OSCOM_Hooks->call('siteWide', 'injectBodyContentEnd');
      ?>
      
    </div> <!-- BodyContent//-->

    
    <?php 
  echo $OSCOM_Hooks->call('siteWide', 'injectBodyWrapperEnd');
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
