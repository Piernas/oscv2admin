<?php

class hook_admin_siteWide_sidebar {
  var $version = '1.0.0';

  function listen_injectBodyWrapperStart() {
      global $active_app_title;
    global $active_app_title;
    $page_title = $active_app_title . " - " . STORE_NAME;
    global $language, $cfgModules, $PHP_SELF;
    $group_id = (isset($_GET['group']) ? (int)$_GET['group'] : '');
    $set = (isset($_GET['set']) ? $_GET['set'] : '');
    $gID = (isset($_GET['gID']) ? (int)$_GET['gID'] : '');

  if (tep_session_is_registered('admin')) {

    $cl_box_groups = array();

    if ($dir = @dir(DIR_FS_ADMIN . 'includes/boxes')) {
      $files = array();

      while ($file = $dir->read()) {
        if (!is_dir($dir->path . '/' . $file)) {
          if (substr($file, strrpos($file, '.')) == '.php') {
            $files[] = $file;
          }
        }
      }

      $dir->close();

      natcasesort($files);

      foreach ( $files as $file ) {
        if ( file_exists(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file) ) {
          include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file);
        }
        include($dir->path . '/' . $file);
      }
    }

//    include ("menu_icons.php");
  for($i = 0; $i < count($cl_box_groups); $i++) {
    switch ($cl_box_groups[$i]['heading']) {
       case BOX_HEADING_ORDERS:
       $cl_box_groups[$i]['icon'] = 'fas fa-shopping-cart';
       $cl_box_groups[$i]['sort_order'] = "01";
       break;
       case BOX_HEADING_CUSTOMERS:
       $cl_box_groups[$i]['icon'] = 'fas fa-user';
       $cl_box_groups[$i]['sort_order'] = "02";
       break;
       case BOX_HEADING_CATALOG:
       $cl_box_groups[$i]['icon'] = 'fas fa-gift';
       $cl_box_groups[$i]['sort_order'] = "03";
       break;
       case BOX_HEADING_REPORTS:
       $cl_box_groups[$i]['icon'] = 'fas fa-chart-bar';
       $cl_box_groups[$i]['sort_order'] = "04";
       break;
       case BOX_HEADING_TOOLS:
       $cl_box_groups[$i]['icon'] = 'fas fa-wrench';
       $cl_box_groups[$i]['sort_order'] = "05";
       break;
       case BOX_HEADING_MODULES:
       $cl_box_groups[$i]['icon'] = 'fas fa-cogs';
       $cl_box_groups[$i]['sort_order'] = "07";
       break;
       case BOX_HEADING_CONFIGURATION:
       $cl_box_groups[$i]['icon'] = 'fas fa-cog';
       $cl_box_groups[$i]['sort_order'] = "08";
       break;
       case BOX_HEADING_LOCALIZATION:
       $cl_box_groups[$i]['icon'] = 'fas fa-globe';
       $cl_box_groups[$i]['sort_order'] = "09";
       break;
       case BOX_HEADING_LOCATION_AND_TAXES:
       $cl_box_groups[$i]['icon'] = 'fas fa-percent';
       $cl_box_groups[$i]['sort_order'] = "10";
       break;
       case MODULES_ADMIN_MENU_PAYPAL_HEADING:
       $cl_box_groups[$i]['icon'] = 'fab fa-paypal';
       $cl_box_groups[$i]['sort_order'] = "11";
       break;
       default:
       $cl_box_groups[$i]['sort_order'] = "99";
       break;
    }
  }

    function menu_sort_admin_boxes($a, $b) {
      // sort alphabetically
      // return strcasecmp($a['heading'], $b['heading']);
      // sort by sort_order (see menu_icons.php)
      return strcasecmp($a['sort_order'], $b['sort_order']);
    }

    usort($cl_box_groups, 'menu_sort_admin_boxes');

    function menu_sort_admin_boxes_links($a, $b) {
      return strcasecmp($a['title'], $b['title']);
    }

    foreach ( $cl_box_groups as &$group ) {
      usort($group['apps'], 'menu_sort_admin_boxes_links');
    }


$active="";

$active_app="";

$active_group = "";

  if (isset($_SESSION['admin'])) {

// find active group:
    $counter=0;
    foreach ($cl_box_groups as $groups) {
      foreach ($groups['apps'] as $app) {
        $active_group_title = $groups['heading'];
        if ($app['code'] == $PHP_SELF) {
          break 2;
        }
      }
      $counter++;
    }


?>
<!-- sidebar starts -->
  <div id="sidebar-wrapper" class="toggled">
    <nav id="sidebar-nav">
      <ul  id="menu" class="list-unstyled">
<?php
    $dashboard = 1;

    foreach ($cl_box_groups as $groups) {

      if  ($counter == $active) {
        $active_group =" active";
        $dashboard = 0;
      } else {
          $active_group ="";
      }
      $icon = ($groups['icon'] ? $groups['icon']:"fa-dot-circle-o")
?>
        <li class="sidebar-item<?= $active_group ?>"><a href="#submenu<?= $active ?>" data-toggle="collapse"><span class="text-center"><i class="<?= $icon ?> sidebar-icon"></i> <?= $groups['heading'] ?></span></a>
          <ul id="submenu<?= $active ?>" class="list-unstyled collapse">
<?php
      foreach ($groups['apps'] as $app) {
        if ($app['code'] == 'configuration.php') {
          $parameters = parse_url($app['link']);
          parse_str($parameters['query'], $query);

          if ($query['gID'] == $gID) {
            $active_app =" active";
            $active_app_title = $$active_group_title . ": " . $app['title'];
          } else {
            $active_app ="";
          }
        } elseif ($app['code'] == 'modules.php') {
          $parameters = parse_url($app['link']);
          parse_str($parameters['query'], $query);

          if ($query['set'] == $set) {
            $active_app =" active";
            $active_app_title = $active_group_title . ": " . $app['title'];
          } else {
            $active_app ="";
          }
        } elseif ($app['code'] == 'paypal.php') {
          $active_app ="";
        } else {
          if ($app['code'] == $PHP_SELF) {
            $active_app =" active";
            $active_app_title = $app['title'];
          } else {
            $active_app ="";
          }
        }
?>
            <li class="sidebar-subitem<?= $active_app ?>"><a href="<?= $app['link'] ?>"><?= $app['title'] ?></a></li>
<?php
      }
?>
          </ul>
        </li>
<?php
      $active++;
    }
  }

  if ($dashboard==1) $active_app_title ="Dashboard";
?>
        <div class="dropdown-divider"></div>
        <li class="sidebar-item" data-toggle="collapse" id="sidebar-collapse">
            <a id ="sidebar-collapse-content" href="#"><span class="text-center"><i class="fa fa-angle-double-right sidebar-icon"></i> Expanded menu</span></a>
        </li>
      </ul>
    </nav>
  </div>
  
<!-- sidebar end -->

<?php
      }
    }
    
  function listen_injectSiteEnd() {
    global $active_app_title;
    $page_title = $active_app_title . " - " . STORE_NAME;


$this->listen_injectSiteEnd = <<<EOD
<script>
 swapCollapseButton();
 
  $(document).ready(function() {
    $("body").removeClass("preload");
     initMenu();
     document.title = "$page_title";
  });

  $("#menu-toggle").click(function(e) {
   e.preventDefault();
   $("#sidebar-wrapper").toggleClass("toggled");
  });

  // Sets new sidebar state on click
  $("#sidebar-collapse").click(function(e) {
     e.preventDefault();
     $("#sidebar-wrapper").toggleClass("toggled-large");
     $("#menu ul").hide();
     localStorage.setItem("oscSidebar", $("#sidebar-wrapper").attr("class"));
     swapCollapseButton();
 });

 $("#sidebar-wrapper").mouseleave(
    function() {
    $("#sidebar-wrapper.toggled-large #menu ul").hide("slow");
    }
 );

function swapCollapseButton () {
  sidebarState =localStorage.getItem("oscSidebar");
  console.log ("SWapcollapse: " + sidebarState);
  console.log ($("#sidebar-wrapper").attr("class"));
  if ( sidebarState =="toggled toggled-large") {
    $("#sidebar-collapse-content").html ('<span class="text-center"><i class="fas fa-angle-double-right sidebar-icon"></i> Expand menu</span>');
  } else {
    $("#sidebar-collapse-content").html ('<span class="text-center"><i class="fas fa-thumbtack sidebar-icon"></i> Collapse menu</span>');
  }
  
  $("#sidebar-wrapper").addClass(sidebarState);
}

function initMenu() {
  var sidebarState = localStorage.getItem("oscSidebar");
   $("#menu ul").hide();
   $("#menu ul").children(".current").parent().show();
   $("#menu li a").click(
      function() {
         var thisElement = $(this);
         var checkElement = $(this).next();
         if ((checkElement.is("ul")) && (checkElement.is(":visible"))) {
            checkElement.slideUp("normal");
            return false;
         }
         if ((checkElement.is("ul")) && (!checkElement.is(":visible"))) {
            $("#menu ul:visible").slideUp("normal");
            checkElement.slideDown("normal");
            return false;
         }
      }
   );
}
</script>
<style>
#sidebar-nav {
  width: 270px; 
}

@media(max-width:767px) {
  #sidebar-wrapper.toggled #sidebar-nav {
    width: 0px;
  }
  #sidebar-collapse {
    display: none;
  }
}

@media(min-width:768px) {
 
  #sidebar-wrapper.toggled-large #sidebar-nav {
    width: 50px;
    -webkit-transition: width 0.5s ease 0s;
    -moz-transition: width 0.5s ease 0s;
    -o-transition: width 0.5s ease 0s;
    transition: width 0.5s ease 0s;
  }
  #sidebar-wrapper.toggled-large:hover #sidebar-nav{
    width: 270px; 
  }
}
</style>
EOD;
  return $this->listen_injectSiteEnd;
    }
  }