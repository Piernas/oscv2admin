<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $gID = (isset($_GET['gID'])) ? $_GET['gID'] : 1;

  $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
  $cfg_group = tep_db_fetch_array($cfg_group_query);

  require('includes/template_top.php');
  
  $groups_list = $cfgGroups->getValues ();

?>
  <div class="card my-3">
    <div class="card-header" id="page-heading">
      <div class="d-flex justify-content-between">
        <div class="mr-auto pageHeading">
          <?php echo sprintf(TITLE_CONFIGURATION ,$cfg_group['configuration_group_title']); ?>
        </div>
        <div class=" pr-2">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-info text-white"><i class="fas fa-forward fa-lg"></i></span>
                </div>
          <?= tep_draw_pull_down_menu('configuration_group_selection', $groups_list, $gID)?>
          </div>
        </div>
        <div>
          <a class="btn btn-info btn-sm" href="javascript:DisplaySetup();"><i class="fas fa-cog"></i></a>
        </div>
      </div>
    </div>

    <div class="card-body" id="page-content">
      <table class="table table-sm table-striped table-hover">
        <thead>
        <tr class="table-info">
          <th><?php echo TABLE_HEADING_CONFIGURATION_CONSTANT; ?></th>
          <th><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></th>
          <th><?php echo TABLE_HEADING_CONFIGURATION_DESCRIPTION; ?></th>
          <th class="text-center"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></th>
          <th class="actions"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<?php
  $configuration_query = tep_db_query("select configuration_id, configuration_key, configuration_title, configuration_description, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (preg_match('/->/', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include('includes/classes/' . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    $cfgValue = htmlspecialchars($cfgValue);
    
    if (strtolower ($cfgValue) == 'true') {
      $cfgValue = '<i class="fa fa-check fa-lg text-success"></i>';
    } elseif (strtolower($cfgValue) == 'false') {
      $cfgValue = '<i class="fas fa-times fa-lg text-danger"></i>';
    }
?>
        <tr>
          <td><?php echo $configuration['configuration_key']; ?></td>
          <td><?php echo $configuration['configuration_title']; ?></td>
          <td><?= $configuration['configuration_description'] ?></td>
          <td class="text-center" id="val_<?= $configuration['configuration_id'] ?>"><?= $cfgValue ?></td>
          <td class="actions"><a href="javascript:ModalEdit(<?= $configuration['configuration_id'] ?>);"><i class="fas fa-edit fa-lg text-primary"></i></a></td>
        </tr>
<?php
  }
?>
        <tbody>
      </table>
    </div>
  </div>
<?php

  require ("includes/classes/modal.php");
  
  $modal = new modal();
  $modal->button_save = true;
  $modal->button_cancel = true;
  $modal->output();

  require('includes/template_bottom.php');
?>
<script>
    $(function(){
      $('select[name="configuration_group_selection"]').on('change', function () {
          var url = "configuration.php?gID=" + $(this).val();
          if (url) {
              window.location = url;
          }
          return false;
      });
    });
</script>
<script>

  $("#ModalButtonSave").click(function() {
      if ($("[name='configuration_value']").is(':radio')) {
        var value =  ($("[name='configuration_value']:checked").val()); 
      } else {
        var value = ($('[name="configuration_value"]').val());
      }
    
      var ConfigId = ($('input[name="cID"').val());
      
      var params = {"cID" : ConfigId, "configuration_value" : value};
      $.ajax({
        data:  params,
        url:   'configuration.php?action=configuration_save_value',
        type:  'post',
        beforeSend: function () {
          $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
        },
        success:  function (response) {
          $("#val_" + ConfigId).fadeOut(function(){
            $(this).html($(response).filter('#content').html()).fadeIn();
          });


        }
      });
  })

  function ModalEdit(ConfigId) {
    var params = {"cID" : ConfigId, "action" : "configuration_edit_value"};
/*
  $("form > .modal-content").unwrap();
  $(".modal-content").wrap('<form id="configuration_form" name="configuration_form" action="configuration.php?action=configuration_edit_value" method="get">')
*/
    
    $.ajax({
      data:  params,
      url:   'configuration.php',
      type:  'get',
      beforeSend: function () {
        $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
      },
      success:  function (response) {
        $(".modal-title").html($(response).filter('#title').html());
        $(".modal-body").html($(response).filter('#content').html());
        $("#configurationModal").modal('show')
      }
    });
  }
  
  function DisplaySetup() {
    var params = {"action" : "configuration_page_setup"};
    $.ajax({
      data:  params,
      url:   'configuration.php',
      type:  'get',
      beforeSend: function () {
        $(".modal-body").html('<div class="text-center"><i class="fas fa-spinner fa-pulse fa-3x fa-fw text-info"></i><span class="sr-only">Loading...</span></div>');
      },
      success:  function (response) {
        $(".modal-title").html($(response).filter('#title').html());
        $(".modal-body").html($(response).filter('#content').html());
        $("#configurationModal").modal('show')
      }
    });
  }
</script>

<?php
  require('includes/application_bottom.php');
