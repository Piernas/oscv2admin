<?php

  require('includes/application_top.php');
  require('includes/template_top.php');

?>

  <div class="row">
    <div class="col-12">
      <h1 class="pageHeading"><i class="fa fa-cog"></i> <?= HEADING_TITLE?> </h1>
    </div>
  </div>

  <div class="row">

<?php

foreach ($cfgGroups->getAll () as $group) {
  $group_icon ="";
  if (key_exists ('icon', $group )) $group_icon ='<i class="' . $group['icon'] . ' fa-lg"></i> ';
  
?>
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
    <div class="alert alert-info text-center full-height">
      <a href="<?= $group['link'] ?>" class="alert-link"><?= $group_icon . $group['title'] ?></a>
      <p><?= $group['description'] ?></p>
    </div>
  </div>
<?php

}
?>
  </div>
<?php

  require('includes/template_bottom.php');

  require('includes/application_bottom.php');
