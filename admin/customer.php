<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require ('includes/classes/customer.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $error = false;
  $processed = false;

  $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
                            array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));

  if (tep_not_null($action)) {
    switch ($action) {
      case 'customers_update':
      /*
        $customers_id = tep_db_prepare_input($_GET['cID']);
        $customers_firstname = tep_db_prepare_input($_POST['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($_POST['customers_lastname']);
        $customers_email_address = tep_db_prepare_input($_POST['customers_email_address']);
        $customers_telephone = tep_db_prepare_input($_POST['customers_telephone']);
        $customers_fax = tep_db_prepare_input($_POST['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($_POST['customers_newsletter']);

        $customers_gender = tep_db_prepare_input($_POST['customers_gender']);
        $customers_dob = tep_db_prepare_input($_POST['customers_dob']);

        $default_address_id = tep_db_prepare_input($_POST['default_address_id']);
        $entry_street_address = tep_db_prepare_input($_POST['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($_POST['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($_POST['entry_postcode']);
        $entry_city = tep_db_prepare_input($_POST['entry_city']);
        $entry_country_id = tep_db_prepare_input($_POST['entry_country_id']);

        $entry_company = tep_db_prepare_input($_POST['entry_company']);
        $entry_state = tep_db_prepare_input($_POST['entry_state']);
        if (isset($_POST['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($_POST['entry_zone_id']);

        if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_firstname_error = true;
        } else {
          $entry_firstname_error = false;
        }

        if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_lastname_error = true;
        } else {
          $entry_lastname_error = false;
        }

        if (ACCOUNT_DOB == 'true') {
          if ((strlen($customers_dob) >= ENTRY_DOB_MIN_LENGTH) && ((is_numeric(tep_date_raw($customers_dob)) && @checkdate(substr(tep_date_raw($customers_dob), 4, 2), substr(tep_date_raw($customers_dob), 6, 2), substr(tep_date_raw($customers_dob), 0, 4))) || empty($customers_dob))) {
            $entry_date_of_birth_error = false;
          } else {
            $error = true;
            $entry_date_of_birth_error = true;
          }
        }

        if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_email_address_error = true;
        } else {
          $entry_email_address_error = false;
        }

        if (!tep_validate_email($customers_email_address)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }

        if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_street_address_error = true;
        } else {
          $entry_street_address_error = false;
        }

        if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $entry_post_code_error = true;
        } else {
          $entry_post_code_error = false;
        }

        if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
          $error = true;
          $entry_city_error = true;
        } else {
          $entry_city_error = false;
        }

        if ($entry_country_id == false) {
          $error = true;
          $entry_country_error = true;
        } else {
          $entry_country_error = false;
        }

        if (ACCOUNT_STATE == 'true') {
          if ($entry_country_error == true) {
            $entry_state_error = true;
          } else {
            $zone_id = 0;
            $entry_state_error = false;
            $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "'");
            $check_value = tep_db_fetch_array($check_query);
            $entry_state_has_zones = ($check_value['total'] > 0);
            if ($entry_state_has_zones == true) {
              $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "' and zone_name = '" . tep_db_input($entry_state) . "'");
              if (tep_db_num_rows($zone_query) == 1) {
                $zone_values = tep_db_fetch_array($zone_query);
                $entry_zone_id = $zone_values['zone_id'];
              } else {
                $error = true;
                $entry_state_error = true;
              }
            } else {
              if (strlen($entry_state) < ENTRY_STATE_MIN_LENGTH) {
                $error = true;
                $entry_state_error = true;
              }
            }
         }
      }

      if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
        $error = true;
        $entry_telephone_error = true;
      } else {
        $entry_telephone_error = false;
      }

      $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "' and customers_id != '" . (int)$customers_id . "'");
      if (tep_db_num_rows($check_email)) {
        $error = true;
        $entry_email_address_exists = true;
      } else {
        $entry_email_address_exists = false;
      }

      if ($error == false) {

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_telephone' => $customers_telephone,
                                'customers_fax' => $customers_fax,
                                'customers_newsletter' => $customers_newsletter);

        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;

        if (ACCOUNT_STATE == 'true') {
          if ($entry_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $entry_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $entry_state;
          }
        }

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");

        tep_redirect(tep_href_link('customers.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));

        } else if ($error == true) {
          $cInfo = new objectInfo($_POST);
          $processed = true;
        }
*/
        break;
      case 'customers_delete_confirm':
        $customers_id = tep_db_prepare_input($_GET['cID']);

        if (isset($_POST['delete_reviews']) && ($_POST['delete_reviews'] == 'on')) {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
          }

          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
        }

        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");

        tep_redirect(tep_href_link('customers.php', tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
        $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$_GET['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
    }
  }

  require('includes/template_top.php');

  if ($action == 'edit' || $action == 'update') {
?>
<script type="text/javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
  var customers_email_address = document.customers.customers_email_address.value;
  var entry_street_address = document.customers.entry_street_address.value;
  var entry_postcode = document.customers.entry_postcode.value;
  var entry_city = document.customers.entry_city.value;
  var customers_telephone = document.customers.customers_telephone.value;

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?php if (ACCOUNT_DOB == 'true') { ?>
  if (customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.customers.elements['entry_state'].type != "hidden") {
    if (document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?>) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  }
<?php
  }
?>

  if (document.customers.elements['entry_country_id'].type != "hidden") {
    if (document.customers.entry_country_id.value == 0) {
      error_message = error_message + "<?php echo JS_COUNTRY; ?>";
      error = 1;
    }
  }

  if (customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
  
require ('includes/classes/address.php');
  
  
?>
    <div class="row">
      <div class="col-sm-8 pageHeading"><i class="fas fa-user"></i>&nbsp;<?= HEADING_TITLE ?></div>
      <div class="col-sm-4 pageHeading text-right"></div>
    </div>

 <div class="card ">
  <div class="card-header"> 
    <ul id="customerTabsMain" class="nav nav-pills">
      <li class="nav-item"><?php echo '<a  class="nav-link active" href="#section_personal_content" data-toggle="tab">' . CATEGORY_PERSONAL . '</a>'; ?></li>
      <li class="nav-item"><?php echo '<a  class="nav-link" href="#section_address_content" data-toggle="tab">' . CATEGORY_ADDRESS . '</a>'; ?></li>
    </ul>
  </div>
  <div class="card-body"> 
    <div class="tab-content">
      <div id="section_personal_content" class="tab-pane fade show active" role="tabpanel">
        <div class="row">
          <div class="col-sm-6 col-lg-4">
            <div class="card mb-3">
              <div class="card-header"><?= CATEGORY_PERSONAL ?></div>
              <div class="card-body">
                <div class="form-group">
                  <label><?= ENTRY_FIRST_NAME ?></label>
                  <?= tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true) ?>
                </div>
                <div class="form-group">
                  <label><?= ENTRY_LAST_NAME ?></label>
                  <?= tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true) ?>
                </div>
                <div class="form-group">
                  <label><?= ENTRY_DATE_OF_BIRTH ?></label>
                  <?= tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10" id="customers_dob"', true) ?>
                </div>
                <div class="form-group">
                  <label><?= ENTRY_EMAIL_ADDRESS ?></label>
                  <?= tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true) ?>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-lg-4">
            <div class="card mb-3">
              <div class="card-header"><?= CATEGORY_CONTACT ?></div>
              <div class="card-body">
                <div class="form-group">
                  <label><?= ENTRY_TELEPHONE_NUMBER ?></label>
                  <?= tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true) ?>
                </div>
                <div class="form-group">
                  <label><?= ENTRY_FAX_NUMBER ?></label>
                  <?= tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"') ?>
                </div>
                <div class="form-group">
                  <label><?= ENTRY_NEWSLETTER ?></label>
                  <?= tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0')) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="card mb-3">
            <div class="card-header"><?= CATEGORY_COMPANY ?></div>
            <div class="card-body">
              <div class="form-group">
                <label><?= ENTRY_COMPANY ?></label>
                <?= tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') ?>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php
  $address = new address ($cInfo->customers_id);
?>
      <div id="section_address_content" class="tab-pane fade" role="tabpanel">
        <div class="row">
<?php
    $address_array = $address->address_list;
$CHANGEME ="CAMBIAR";
    foreach ($address_array as $key => $value){       
?>
      <div class="col-sm-6 col-lg-4">

        <div class="card mb-3">
          <div class="card-header"><?= $key ?></div>
          <div class="card-body">
            <div class="form-group">
              <label><?= ENTRY_FIRST_NAME ?></label>
              <?= tep_draw_input_field('entry_firstname', $value['firstname'], 'maxlength="64"', true) ?>
            </div>
            <div class="form-group">
              <label><?= ENTRY_LAST_NAME ?></label>
              <?= tep_draw_input_field('entry_lastname', $value['lastname'], 'maxlength="64"', true) ?>
            </div>
            <div class="form-group">
              <label><?= ENTRY_COMPANY ?></label>
              <?= tep_draw_input_field('entry_company', $value['company'], 'maxlength="64"', true) ?>
            </div>
            
            
            <div class="form-group">
              <label><?= ENTRY_STREET_ADDRESS ?></label>
              <?= tep_draw_input_field('entry_street_address', $value['street_address'], 'maxlength="64"', true) ?>
            </div>

            <div class="form-group">
              <label><?= ENTRY_SUBURB ?></label>
              <?= tep_draw_input_field('entry_suburb', $value['suburb'], 'maxlength="64"', true) ?>
            </div>
            
            <div class="form-group">
              <label><?= ENTRY_POST_CODE ?></label>
              <?= tep_draw_input_field('entry_postcode', $value['postcode'], 'maxlength="64"', true) ?>
            </div>
            <div class="form-group">
              <label><?= ENTRY_CITY ?></label>
              <?= tep_draw_input_field('entry_city', $value['city'], 'maxlength="64"', true) ?>
            </div>
            <div class="form-group">
              <label><?= ENTRY_STATE ?></label>
              <?= tep_draw_input_field('entry_state', $value['state'], 'maxlength="64"', true) ?>
            </div>
            <div class="form-group">
              <label><?= ENTRY_COUNTRY ?></label>
              <?= tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) ?>
            </div>
          </div>
        </div>
      </div>
<?php
    }
?>
      </div>

      </div>
    </div>

  </div>
</div>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
