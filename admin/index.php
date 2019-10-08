<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  require('includes/template_top.php');
?>
    <div class="row py-3">
<?php
  if ( defined('MODULE_ADMIN_DASHBOARD_INSTALLED') && tep_not_null(MODULE_ADMIN_DASHBOARD_INSTALLED) ) {
    $adm_array = explode(';', MODULE_ADMIN_DASHBOARD_INSTALLED);
  IF (COUNT($adm_array)>0) {
      $col = 0;

      for ( $i=0, $n=sizeof($adm_array); $i<$n; $i++ ) {
        $adm = $adm_array[$i];

        $class = substr($adm, 0, strrpos($adm, '.'));

        if ( !class_exists($class) ) {
          include('includes/languages/' . $language . '/modules/dashboard/' . $adm);
          include('includes/modules/dashboard/' . $class . '.php');
        }

        $ad = new $class();

        if ( $ad->isEnabled() ) {
          if ($col < 1) {
            echo '          <tr>' . "\n";
          }

          $col++;

          if ($col <= 2) {
            echo '            <td width="50%" valign="top">' . "\n";
          }

          echo $ad->getOutput();

          if ($col <= 2) {
            echo '            </td>' . "\n";
          }

          if ( !isset($adm_array[$i+1]) || ($col == 2) ) {
            if ( !isset($adm_array[$i+1]) && ($col == 1) ) {
              echo '            <td width="50%" valign="top">&nbsp;</td>' . "\n";
            }

            $col = 0;

            echo '  </tr>' . "\n";
          }
        }
      }
    }
  }
?>
    </div>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
