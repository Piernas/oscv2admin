<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  $login_request = true;

  require('includes/application_top.php');
  require('includes/functions/password_funcs.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

// prepare to logout an active administrator if the login page is accessed again
  if (tep_session_is_registered('admin')) {
    $action = 'logoff';
  }

  if (tep_not_null($action)) {
    switch ($action) {
      case 'login_process':
        if (tep_session_is_registered('redirect_origin') && isset($redirect_origin['auth_user']) && !isset($_POST['username'])) {
          $username = tep_db_prepare_input($redirect_origin['auth_user']);
          $password = tep_db_prepare_input($redirect_origin['auth_pw']);
        } else {
          $username = tep_db_prepare_input($_POST['username']);
          $password = tep_db_prepare_input($_POST['password']);
        }

        $actionRecorder = new actionRecorderAdmin('ar_admin_login', null, $username);

        if ($actionRecorder->canPerform()) {
          $check_query = tep_db_query("select id, user_name, user_password from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($username) . "'");

          if (tep_db_num_rows($check_query) == 1) {
            $check = tep_db_fetch_array($check_query);

            if (tep_validate_password($password, $check['user_password'])) {
// migrate old hashed password to new phpass password
              if (tep_password_type($check['user_password']) != 'phpass') {
                tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_password = '" . tep_encrypt_password($password) . "' where id = '" . (int)$check['id'] . "'");
              }

              tep_session_register('admin');

              $admin = array('id' => $check['id'],
                             'username' => $check['user_name']);

              $actionRecorder->_user_id = $admin['id'];
              $actionRecorder->record();

              if (tep_session_is_registered('redirect_origin')) {
                $page = $redirect_origin['page'];

                $get_string = http_build_query($redirect_origin['get']);

                tep_session_unregister('redirect_origin');

                tep_redirect(tep_href_link($page, $get_string));
              } else {
                tep_redirect(tep_href_link('index.php'));
              }
            }
          }

          if (isset($_POST['username'])) {
            $messageStack->add(ERROR_INVALID_ADMINISTRATOR, 'error');
          }
        } else {
          $messageStack->add(sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES') ? (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES : 5)));
        }

        if (isset($_POST['username'])) {
          $actionRecorder->record(false);
        }

        break;

      case 'logoff':
        tep_session_unregister('admin');

        if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
          tep_session_register('auth_ignore');
          $auth_ignore = true;
        }

        tep_redirect(tep_href_link('index.php'));

        break;

      case 'create_admin':
        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");

        if (tep_db_num_rows($check_query) == 0) {
          $username = tep_db_prepare_input($_POST['username']);
          $password = tep_db_prepare_input($_POST['password']);

          if ( !empty($username) ) {
            tep_db_query("insert into " . TABLE_ADMINISTRATORS . " (user_name, user_password) values ('" . tep_db_input($username) . "', '" . tep_db_input(tep_encrypt_password($password)) . "')");
          }
        }

        tep_redirect(tep_href_link('login.php'));

        break;
    }
  }


  $admins_check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");
  if (tep_db_num_rows($admins_check_query) < 1) {
    $messageStack->add(TEXT_CREATE_FIRST_ADMINISTRATOR, 'warning');
  }

  require('includes/template_top.php');
?>


<?php

  if (tep_db_num_rows($admins_check_query) > 0) {
    $action ="login_process";
    $title = HEADING_TITLE;
    $button = BUTTON_LOGIN;
  } else {
    $action ="create_admin";
    $title = TEXT_CREATE_FIRST_ADMINISTRATOR;
    $button = BUTTON_LOGIN;
  }
?>
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto my-5">
      <div class="card ">
        <div class="card-header">
        <?= $title  ?>
        </div>
        <div class="card-body">
          <?= tep_draw_form('login', 'login.php', 'action=' . $action) ?>
            <div class="form-group"><?=  tep_draw_input_field('username','','placeholder="' . TEXT_USERNAME . '"')?></div>
            <div class="form-group"><?=  tep_draw_input_field('password','','placeholder="' . TEXT_PASSWORD . '"')?></div>
          <button class="btn btn-lg btn-primary btn-block"><?= $button ?></button>
        </form>
        </div>
      </div>
    </div>
  </div>



<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
