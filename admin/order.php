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
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  include('includes/classes/order.php');

  $OSCOM_Hooks->call('orders', 'orderAction');

  require('includes/template_top.php');

  $base_url = ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN;

  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>

<h1 class="pageHeading"><?php echo HEADING_TITLE . ': #' . (int)$oID . ' (' . $order->info['total'] . ')'; ?></h1>

<div style="text-align: right; padding-bottom: 15px;"><?php echo tep_draw_button(IMAGE_ORDERS_INVOICE, 'document', tep_href_link('invoice.php', 'oID=' . $_GET['oID']), null, array('newwindow' => true)) . tep_draw_button(IMAGE_ORDERS_PACKINGSLIP, 'document', tep_href_link('packingslip.php', 'oID=' . $_GET['oID']), null, array('newwindow' => true)) . tep_draw_button(IMAGE_BACK, 'triangle-1-w', tep_href_link('orders.php', tep_get_all_get_params(array('action')))); ?></div>

<div id="orderTabs" style="overflow: auto;">
  <ul id="orderTabsMain" class="nav nav-pills">
    <li class="nav-item"><?php echo '<a  class="nav-link active" href="#section_summary_content" data-toggle="tab">' . TAB_TITLE_SUMMARY . '</a>'; ?></li>
    <li class="nav-item"><?php echo '<a  class="nav-link" href="#section_products_content" data-toggle="tab">' . TAB_TITLE_PRODUCTS . '</a>'; ?></li>
    <li class="nav-item"><?php echo '<a  class="nav-link" href="#section_status_history_content" data-toggle="tab">' . TAB_TITLE_STATUS_HISTORY . '</a>'; ?></li>
  </ul>

  <div class="tab-content">

    <div id="section_summary_content" class="tab-pane fade show active" role="tabpanel">
      <div class="row">
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_CUSTOMER ?></h5></div>
            <div class="card-body">
              <p><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br />'); ?></p>
              <p><?php echo $order->customer['telephone'] . '<br />' . '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_SHIPPING_ADDRESS ?></h5></div>
            <div class="card-body">
              <p><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'); ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_BILLING_ADDRESS ?></h5></div>
            <div class="card-body">
              <p><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'); ?></p>
            </div>
          </div>
        </div>
        
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_PAYMENT_METHOD ?></h5></div>
            <div class="card-body">
              <p><?php echo $order->info['payment_method']; ?></p>
<?php
    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>
              <p><?php echo ENTRY_CREDIT_CARD_TYPE; ?></p>
              <p>php echo $order->info['cc_type']; ?></p>
              <p><?php echo ENTRY_CREDIT_CARD_OWNER; ?></p>
              <p><?php echo $order->info['cc_owner']; ?></p>
              <p><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></p>
              <p><?php echo $order->info['cc_number']; ?></p>
              <p><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></p>
              <p><?php echo $order->info['cc_expires']; ?></p>
<?php
    }
?>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_STATUS ?></h5></div>
            <div class="card-body">
              <p><?php echo $order->info['status'] . '<br />' . (empty($order->info['last_modified']) ? tep_datetime_short($order->info['date_purchased']) : tep_datetime_short($order->info['last_modified'])); ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header bg-info text-light"><h5><?= ENTRY_TOTAL ?></h5></div>
            <div class="card-body">
              <p><?php echo $order->info['total']; ?></p>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div id="section_products_content" class="tab-pane fade" role="tabpanel">
    <div class="card">
    
    <table class="table table-striped table-sm">
      <thead>
      <tr class="table-info">
        <th colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_TAX; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></th>
        <th class="text-right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></th>
      </tr>
      </thead>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td valign="top" class="text-right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '        <td class="text-right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="text-right" valign="top"><strong>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
           '        <td class="text-right" valign="top"><strong>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
           '        <td class="text-right" valign="top"><strong>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
           '        <td class="text-right" valign="top"><strong>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
    </table>
    <table class="table table-sm">

      <tr class="table-info">
        <td align="right"><table border="0" cellspacing="0" cellpadding="2">
<?php
    foreach ( $order->totals as $ot ) {
      echo '          <tr>' . "\n" .
           '            <td class="text-right">' . $ot['title'] . '</td>' . "\n" .
           '            <td class="text-right">' . $ot['text'] . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
?>

        </table></td>
      </tr>
    </table>
  </div>
    </div>

  <div id="section_status_history_content" class="tab-pane fade" role="tabpanel">
    <div class="row">
      <div class="col-md-6">

      <div class="card">
        <?php echo tep_draw_form('status', 'orders.php', tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
          <div class="form-group">
          <label><?php echo ENTRY_STATUS; ?></label> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?>
          </div>
          <div class="form-group">
          <label valign="top"><?php echo ENTRY_ADD_COMMENT; ?></label>
          <?php echo tep_draw_textarea_field('comments', 'soft', '60', '6', null, 'style="width: 100%;"'); ?>
          </div>
          <div class="form-group">
          <label><?php echo ENTRY_NOTIFY_CUSTOMER; ?></label>
          <?php echo tep_draw_checkbox_field('notify', '', true); ?>
          </div>
          <div class="form-group">
          <label><?php echo ENTRY_NOTIFY_COMMENTS; ?></label>
          <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?>
          </div>
          <div class="text-center"><?php echo tep_draw_button(IMAGE_UPDATE, 'disk', null, 'primary'); ?></div>
          </form>
      </div>
    </div>
    <div class="col-md-6">
      <table class="table table-striped table-sm">
        <thead>
          <tr class="table-info">
            <th class="text-center"><strong><?php echo TABLE_HEADING_DATE_ADDED; ?></strong></th>
            <th align="center"><strong><?php echo TABLE_HEADING_STATUS; ?></strong></th>
            <th align="center"><strong><?php echo TABLE_HEADING_COMMENTS; ?></strong></th>
            <th class="text-right"><strong><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></strong></th>
          </tr>
        </thead>
        <tbody>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added desc");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr class="dataTableRow">' . "\n" .
             '            <td valign="top">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td valign="top">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td valign="top">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
             '            <td valign="top" class="text-right">';

        if ($orders_history['customer_notified'] == '1') {
          echo '<i class="fas fa-check fa-lg text-success"></i>';
        } else {
          echo '<i class="fas fa-cross fa-lg text-danger"></i>';
        }

        echo '        </td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr class="dataTableRow">' . "\n" .
             '            <td colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>

    </table>
    </div>
  </div>

    <br />


    </div>
  </div>

<?php
    echo $OSCOM_Hooks->call('orders', 'orderTab');
?>

</div>
<?php
  }

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
