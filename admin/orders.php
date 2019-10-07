<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require('includes/classes/currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);

        $order_updated = false;
        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);

        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");

          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link('account_history_info.php', 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }

        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link('orders.php', tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'orders_delete_confirm':
        $oID = tep_db_prepare_input($_GET['oID']);

        tep_remove_order($oID, $_POST['restock']);

        tep_redirect(tep_href_link('orders.php', tep_get_all_get_params(array('oID', 'action'))));
        break;
    }
  }


  include('includes/classes/order.php');

  $OSCOM_Hooks->call('orders', 'orderAction');

  require('includes/template_top.php');

  $base_url = ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN;

  // ORDERS LISTING:
  require ('includes/classes/modules_pages.php');

  $orders_modules = new modules_pages ('orders');
  $oscTemplate->addBlock('<script>' . $orders_modules->get_javascript() . '</script>', 'admin_footer_scripts');

?>

  <div class="d-flex justify-content-between">
    <div class="mr-auto p-2 pageHeading"><i class="fas fa-file-invoice fa-lg"></i> <?= HEADING_TITLE ?></div>
    <div class="py-2 pr-2">
      <?= tep_draw_form('orders', basename($PHP_SELF),"" ,"get" ,'class="form-inline"') ?>

        <div class="form-group form-group-sm">
          <?= tep_draw_input_field('oID', null, ' size="12" placeholder="' . HEADING_TITLE_SEARCH . '"') ?>

          <?= tep_draw_hidden_field('action', 'edit'); ?>

          <div class="input-group-append"><button class="btn btn-sm btn-info" type="submit"><i class="fas fa-search"></i></button></div>
        </div>
        <?= tep_hide_session_id() ?>

      </form>
    </div>

    <div class="py-2 pr-2">
      <?= tep_draw_form('status', basename($PHP_SELF),"" ,"get", 'class="form-inline"') ?>
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
        <span class="input-group-text bg-info text-white"><i class="fas fa-forward fa-lg"></i></span>
        </div>
        <?= tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onchange="this.form.submit();"') ?>
        <?= tep_hide_session_id() ?>
      </div>
      </form>
    </div>
    <div class="py-2">
      <a href="<?= tep_href_link('modules_pages.php?desired_groups=' . urlencode (json_encode(array(array('orders'), array('orders'))))) ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a>

    </div>
  </div>

    <table class="table table-striped table-sm">
    <thead>
      <tr class="table-info">
<?php
      echo $orders_modules->get_table_header ();
?>
        <th class="actions"><?= TABLE_HEADING_ACTION ?>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
<?php
    if (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0)) {
      $status = tep_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id from " . TABLE_ORDERS . " o , " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select orders_id from " . TABLE_ORDERS . " order by orders_id DESC";
    }

    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {

  /*
    if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
    }
*/
    $order = new order ($orders['orders_id']);

?>
<tr class="clickable">
<?php
    // Buttons:
//    print_r($order);
?>

        <?= $orders_modules->get_table_row ($order->info['orders_id']) ?>

        <td class="actions text-nowrap">
<?= $orders_modules->get_action_buttons($order->info['orders_id']); ?>
        </td>
      </tr>
<?php
    }
?>
    </table>

    <div class="row py-3">
      <div class="col-6"><?= $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
      <div class="col-6"><?= $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))) ?></div>
    </div>

<?php

  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_ORDER . '</strong>');

      $contents = array('form' => tep_draw_form('orders', 'orders.php', tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=orders_delete_confirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br /><br /><strong>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</strong>');
      $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('orders.php', tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id)));
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {

      }
      break;
  }



  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
