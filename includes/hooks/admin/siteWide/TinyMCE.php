<?php

  class hook_admin_siteWide_TinyMCE {
    var $version = '1.0.0';
    var $siteend = null;
    
    function listen_injectSiteEnd() {
      $this->siteend = '<!-- tinymce hooked -->' . PHP_EOL;
//      $this->siteend .= '<script src="' . tep_catalog_href_link ('ext/tinymce/tinymce.min.js') . '"></script>' . PHP_EOL;
      $this->siteend .= '<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>';
      return $this->siteend;
   } 
  }