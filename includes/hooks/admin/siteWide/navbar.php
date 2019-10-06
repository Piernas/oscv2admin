<?php
class hook_admin_siteWide_navbar {
  var $version = '1.0.0';
  
  var $BodyWrapperStart = null;

  function listen_injectNavbar() {
  global $language, $admin, $PHP_SELF;
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;

  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
      $languages_selected_name = $languages[$i]['name'];
    }
  }
?>
  <!-- navbar begins -->
  <nav class="navbar navbar-expand-md sticky-top navbar-dark bg-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="<?= tep_href_link ("index.php") ?>"><i class="fa fa-dashboard"></i>&nbsp;&nbsp;<?=HEADER_TITLE_ADMINISTRATION ?></a>
    <button type="button" id="menu-toggle" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>

      <!-- Links -->
    <div class="navbar-collapse collapse justify-content-stretch" id="navbarSupportedContent">
      <ul class="nav navbar-nav ml-auto">
<?php

  // Reviews button:
  // To be moved to modules

  if (tep_session_is_registered('admin')) {


  $reviews_query = tep_db_query ("SELECT count(*) as num_reviews FROM reviews WHERE date_added >= (now( ) - INTERVAL " . "30 Day" . ") AND reviews_status <>1");
  
  $reviews = tep_db_fetch_array ($reviews_query);
  $number_of_reviews = $reviews['num_reviews'];
      if ($number_of_reviews > 0 ) {
?>
        <li class="nav-item"><button onclick="window.location.href='reviews.php'" type="button" class="btn btn-info navbar-btn"><i class="fas fa-comment fa-sm"></i> <span class="badge badge-light"><?= $number_of_reviews ?></button></span></li>
<?php
      }
  }
// Languages:

  if (sizeof($languages) >1) {
?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbar-collapse-language" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-language"></i> <span class="hidden-xs"><?= $languages_selected; ?></span> <span class="caret"></span></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-collapse-language">
<?php
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
            <a class="dropdown-item" href="<?= tep_href_link(basename($PHP_SELF), 'language=' . $languages[$i]['code']) ?>"><?=$languages[$i]['name'] ?></a>
<?php
    }

?>
          </div>
        </li>';
<?php
  }
  // login:
  if (tep_session_is_registered('admin')) {
?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbar-collapse-login" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <?= $admin['username'] ?><span class="caret"></span></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-collapse-login">
          <a class="dropdown-item" href="<?=  tep_href_link('login.php', 'action=logoff') ?>"><i class="fa fa-sign-out"></i> Log Off</a>
          </div>
        </li>
<?php
  }
?>
      </ul>
    </div>
  </nav>
  <!-- navbar ends -->

<?php
    
    
    $this->BodyWrapperStart .= '<!-- navbar hooked -->' . PHP_EOL;
    $this->BodyWrapperStart .= '<div id=sidebar-wrapper"></div>' . PHP_EOL;

    return false;
  }

}