<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Comyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

  class messageStack {
    var $size = 0;
		
	function __construct() {
      global $messageToStack;

      $this->errors = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i = 0, $n = sizeof($messageToStack); $i < $n; $i++) {
          $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        tep_session_unregister('messageToStack');
      }
    }

    function add($message, $type = 'error') {
      if ($type == 'error') {
        $this->errors[] = array('params' => 'class="alert alert-danger alert-dismissible fade show my-2" role="alert"', 'text' => $message);
      } elseif ($type == 'warning') {
        $this->errors[] = array('params' => 'class="alert alert-warning alert-dismissible fade show my-2" role="alert"', 'text' => $message);
      } elseif ($type == 'success') {
        $this->errors[] = array('params' => 'class="alert alert-success alert-dismissible fade show my-2" role="alert"', 'text' => $message);
      } else {
        $this->errors[] = array('params' => 'class="alert alert-info alert-dismissible fade show my-2" role="alert"', 'text' => $message);
      }

      $this->size++;
    }

    function add_session($message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('text' => $message, 'type' => $type);
    }

    function reset() {
      $this->errors = array();
      $this->size = 0;
    }

    function output() {
      $this->table_data_parameters = 'class="messageBox"';
      return $this->alertBlock($this->errors);
    }
    
        function alertBlock($contents) {
      $alertBox_string = '';
		  
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $alertBox_string .= '  <div';
		  
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params']))
		  $alertBox_string .= ' ' . $contents[$i]['params'];
        
		  $alertBox_string .= '>' . "\n";
          $alertBox_string .= '	<button type="button" class="close" data-dismiss="alert">&times;</button>' . "\n";
          $alertBox_string .= $contents[$i]['text'];
    
          $alertBox_string .= '  </div>' . "\n";
      }

      return $alertBox_string;

    }
  }
?>
