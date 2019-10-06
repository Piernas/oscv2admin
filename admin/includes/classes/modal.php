<?php

  class modal {

    var $content, $button_save, $button_submit, $button_delete, $button_cancel, $button_copy, $button_move;

// class constructor

    function __construct() {
      global $PHP_SELF;
      $path_parts = pathinfo($PHP_SELF);
      $this->name = $path_parts['filename'];
    }

    function output () {
      global $oscTemplate;
      $button_save = $button_submit = $button_delete = $button_cancel = $button_copy = $button_move = '';

      if ($this->button_save == true) $button_save = '          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="ModalButtonSave"><i class="fas fa-save fa-lg"></i> '.  IMAGE_SAVE . '</button>' . PHP_EOL;

      if ($this->button_submit == true) $button_submit = '          <button type="submit" class="btn btn-outline-secondary" id="ModalButtonSubmit"><i class="fas fa-save fa-lg"></i> '.  IMAGE_SAVE . '</button>' . PHP_EOL;

      if ($this->button_delete == true)  $button_delete = '          <button type="submit" class="btn btn-outline-secondary" id="ModalButtonDelete"><i class="fas fa-trash fa-lg"></i> ' . IMAGE_DELETE .'</button>' . PHP_EOL;

      if ($this->button_copy == true)  $button_copy = '          <button type="submit" class="btn btn-outline-secondary" id="ModalButtonCopy"><i class="fas fa-clone fa-lg"></i> ' . IMAGE_COPY .'</button>' . PHP_EOL;
      
      if ($this->button_move == true)  $button_move = '          <button type="submit" class="btn btn-outline-secondary" id="ModalButtonMove"><i class="fas fa-arrows fa-lg"></i> ' . IMAGE_MOVE .'</button>' . PHP_EOL;

      if ($this->button_cancel == true)  $button_cancel = '          <button type="button" class="btn btn-outline-secondary" id="ModalButtonCancel" data-dismiss="modal"><i class="fas fa-times fa-lg"></i> ' . IMAGE_CANCEL . '</button>' . PHP_EOL;

      $content = <<<EOD
<div class="modal" tabindex="-1" role="dialog" id="{$this->name}Modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
{$button_save}{$button_submit}{$button_delete}{$button_copy}{$button_move}{$button_cancel}
        </div>
    </div>
  </div>
</div>
EOD;

      $jscript = '<script>
  $("#ModalButtonSave").click(function(e) {
    $(this).parent().parent().parent().submit();
  })
</script>';
      $oscTemplate->addBlock ($jscript, 'admin_footer_scripts');      

      echo $content;
    }
  }