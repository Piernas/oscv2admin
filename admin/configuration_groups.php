<?php

  require('includes/application_top.php');
  require('includes/template_top.php');

?>
  <div class="card my-3">
    <div class="card-header" id="page-heading"> 
      <h1 class="pageHeading"><i class="fa fa-cog"></i> <?= HEADING_TITLE?> </h1>
    </div>
    <div class="card-body" id="page-content"> 
      <div class="row">

<?php

  foreach ($cfgGroups->getAll () as $group) {
    $group_icon ="";
    if (key_exists ('icon', $group )) $group_icon ='<i class="' . $group['icon'] . ' fa-lg"></i> ';
  
?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 pb-3">
          <div class="card text-center">
            <div class="card-header card-modules"><a href="<?= $group['link'] ?>" class="alert-link"><?= $group_icon . $group['title'] ?></a></div>
            <div class="card-body">
              
              <p><?= $group['description'] ?></p>
            </div>
          </div>
        </div>
<?php

  }
?>
      </div>    </div>
  </div>
<?php

  require('includes/template_bottom.php');

  require('includes/application_bottom.php');
