# $Id$
#
# osCommerce, Open Source E-Commerce Solutions
# http://www.oscommerce.com
#
# Copyright (c) 2018 osCommerce
#
# Released under the GNU General Public License
#
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!

DROP TABLE IF EXISTS action_recorder;
CREATE TABLE action_recorder (
  id int NOT NULL auto_increment,
  module varchar(255) NOT NULL,
  user_id int,
  user_name varchar(255),
  identifier varchar(255) NOT NULL,
  success char(1),
  date_added datetime NOT NULL,
  PRIMARY KEY (id),
  KEY idx_action_recorder_module (module),
  KEY idx_action_recorder_user_id (user_id),
  KEY idx_action_recorder_identifier (identifier),
  KEY idx_action_recorder_date_added (date_added)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS address_book;
CREATE TABLE address_book (
   address_book_id int NOT NULL auto_increment,
   customers_id int NOT NULL,
   entry_gender char(1),
   entry_company varchar(255),
   entry_firstname varchar(255) NOT NULL,
   entry_lastname varchar(255) NOT NULL,
   entry_street_address varchar(255) NOT NULL,
   entry_suburb varchar(255),
   entry_postcode varchar(255) NOT NULL,
   entry_city varchar(255) NOT NULL,
   entry_state varchar(255),
   entry_country_id int DEFAULT '0' NOT NULL,
   entry_zone_id int DEFAULT '0' NOT NULL,
   PRIMARY KEY (address_book_id),
   KEY idx_address_book_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS address_format;
CREATE TABLE address_format (
  address_format_id int NOT NULL auto_increment,
  address_format varchar(128) NOT NULL,
  address_summary varchar(48) NOT NULL,
  PRIMARY KEY (address_format_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS administrators;
CREATE TABLE administrators (
  id int NOT NULL auto_increment,
  user_name varchar(255) binary NOT NULL,
  user_password varchar(60) NOT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS banners;
CREATE TABLE banners (
  banners_id int NOT NULL auto_increment,
  banners_title varchar(64) NOT NULL,
  banners_url varchar(255) NOT NULL,
  banners_image varchar(64) NOT NULL,
  banners_group varchar(10) NOT NULL,
  banners_html_text text,
  expires_impressions int(7) DEFAULT '0',
  expires_date datetime DEFAULT NULL,
  date_scheduled datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  date_status_change datetime DEFAULT NULL,
  status int(1) DEFAULT '1' NOT NULL,
  PRIMARY KEY (banners_id),
  KEY idx_banners_group (banners_group)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS banners_history;
CREATE TABLE banners_history (
  banners_history_id int NOT NULL auto_increment,
  banners_id int NOT NULL,
  banners_shown int(5) NOT NULL DEFAULT '0',
  banners_clicked int(5) NOT NULL DEFAULT '0',
  banners_history_date datetime NOT NULL,
  PRIMARY KEY (banners_history_id),
  KEY idx_banners_history_banners_id (banners_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
   categories_id int NOT NULL auto_increment,
   categories_image varchar(64),
   parent_id int DEFAULT '0' NOT NULL,
   sort_order int(3),
   date_added datetime,
   last_modified datetime,
   PRIMARY KEY (categories_id),
   KEY idx_categories_parent_id (parent_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS categories_description;
CREATE TABLE categories_description (
   categories_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   categories_name varchar(32) NOT NULL,
   categories_description TEXT NULL,
   categories_seo_description TEXT NULL,
   categories_seo_keywords VARCHAR(128) NULL,
   categories_seo_title VARCHAR(128) NULL,
   PRIMARY KEY (categories_id, language_id),
   KEY idx_categories_name (categories_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
  configuration_id int NOT NULL auto_increment,
  configuration_title varchar(255) NOT NULL,
  configuration_key varchar(255) NOT NULL,
  configuration_value text NOT NULL,
  configuration_description varchar(255) NOT NULL,
  configuration_group_id int NOT NULL,
  sort_order int(5) NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  use_function varchar(255) NULL,
  set_function varchar(255) NULL,
  PRIMARY KEY (configuration_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration_group;
CREATE TABLE configuration_group (
  configuration_group_id int NOT NULL auto_increment,
  configuration_group_title varchar(64) NOT NULL,
  configuration_group_description varchar(255) NOT NULL,
  sort_order int(5) NULL,
  visible int(1) DEFAULT '1' NULL,
  PRIMARY KEY (configuration_group_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS countries;
CREATE TABLE countries (
  countries_id int NOT NULL auto_increment,
  countries_name varchar(255) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  address_format_id int NOT NULL,
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies (
  currencies_id int NOT NULL auto_increment,
  title varchar(32) NOT NULL,
  code char(3) NOT NULL,
  symbol_left varchar(12),
  symbol_right varchar(12),
  decimal_point char(1),
  thousands_point char(1),
  decimal_places char(1),
  value float(13,8),
  last_updated datetime NULL,
  PRIMARY KEY (currencies_id),
  KEY idx_currencies_code (code)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
   customers_id int NOT NULL auto_increment,
   customers_gender char(1),
   customers_firstname varchar(255) NOT NULL,
   customers_lastname varchar(255) NOT NULL,
   customers_dob datetime DEFAULT '1970-01-01 00:00:01' NOT NULL,
   customers_email_address varchar(255) NOT NULL,
   customers_default_address_id int,
   customers_telephone varchar(255) NOT NULL,
   customers_fax varchar(255),
   customers_password varchar(60) NOT NULL,
   customers_newsletter char(1),
   PRIMARY KEY (customers_id),
   KEY idx_customers_email_address (customers_email_address)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_basket;
CREATE TABLE customers_basket (
  customers_basket_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  customers_basket_quantity int(2) NOT NULL,
  final_price decimal(15,4),
  customers_basket_date_added char(8),
  PRIMARY KEY (customers_basket_id),
  KEY idx_customers_basket_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_basket_attributes;
CREATE TABLE customers_basket_attributes (
  customers_basket_attributes_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  products_options_id int NOT NULL,
  products_options_value_id int NOT NULL,
  PRIMARY KEY (customers_basket_attributes_id),
  KEY idx_customers_basket_att_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_info;
CREATE TABLE customers_info (
  customers_info_id int NOT NULL,
  customers_info_date_of_last_logon datetime,
  customers_info_number_of_logons int(5),
  customers_info_date_account_created datetime,
  customers_info_date_account_last_modified datetime,
  global_product_notifications int(1) DEFAULT '0',
  password_reset_key char(40),
  password_reset_date datetime,
  PRIMARY KEY (customers_info_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  languages_id int NOT NULL auto_increment,
  name varchar(32)  NOT NULL,
  code char(2) NOT NULL,
  image varchar(64),
  directory varchar(32),
  sort_order int(3),
  PRIMARY KEY (languages_id),
  KEY IDX_LANGUAGES_NAME (name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS manufacturers;
CREATE TABLE manufacturers (
  manufacturers_id int NOT NULL auto_increment,
  manufacturers_name varchar(32) NOT NULL,
  manufacturers_image varchar(64),
  date_added datetime NULL,
  last_modified datetime NULL,
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS manufacturers_info;
CREATE TABLE manufacturers_info (
  manufacturers_id int NOT NULL,
  languages_id int NOT NULL,
  manufacturers_url varchar(255) NOT NULL,
  url_clicked int(5) NOT NULL default '0',
  date_last_click datetime NULL,
  manufacturers_description TEXT NULL,
  manufacturers_seo_description TEXT NULL,
  manufacturers_seo_keywords VARCHAR(128) NULL,
  manufacturers_seo_title VARCHAR(128) NULL,
  PRIMARY KEY (manufacturers_id, languages_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS newsletters;
CREATE TABLE newsletters (
  newsletters_id int NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  module varchar(255) NOT NULL,
  date_added datetime NOT NULL,
  date_sent datetime,
  status int(1),
  locked int(1) DEFAULT '0',
  PRIMARY KEY (newsletters_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  orders_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  customers_name varchar(255) NOT NULL,
  customers_company varchar(255),
  customers_street_address varchar(255) NOT NULL,
  customers_suburb varchar(255),
  customers_city varchar(255) NOT NULL,
  customers_postcode varchar(255) NOT NULL,
  customers_state varchar(255),
  customers_country varchar(255) NOT NULL,
  customers_telephone varchar(255) NOT NULL,
  customers_email_address varchar(255) NOT NULL,
  customers_address_format_id int(5) NOT NULL,
  delivery_name varchar(255) NOT NULL,
  delivery_company varchar(255),
  delivery_street_address varchar(255) NOT NULL,
  delivery_suburb varchar(255),
  delivery_city varchar(255) NOT NULL,
  delivery_postcode varchar(255) NOT NULL,
  delivery_state varchar(255),
  delivery_country varchar(255) NOT NULL,
  delivery_address_format_id int(5) NOT NULL,
  billing_name varchar(255) NOT NULL,
  billing_company varchar(255),
  billing_street_address varchar(255) NOT NULL,
  billing_suburb varchar(255),
  billing_city varchar(255) NOT NULL,
  billing_postcode varchar(255) NOT NULL,
  billing_state varchar(255),
  billing_country varchar(255) NOT NULL,
  billing_address_format_id int(5) NOT NULL,
  payment_method varchar(255) NOT NULL,
  cc_type varchar(20),
  cc_owner varchar(255),
  cc_number varchar(32),
  cc_expires varchar(4),
  last_modified datetime,
  date_purchased datetime,
  orders_status int(5) NOT NULL,
  orders_date_finished datetime,
  currency char(3),
  currency_value decimal(14,6),
  PRIMARY KEY (orders_id),
  KEY idx_orders_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products;
CREATE TABLE orders_products (
  orders_products_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  products_id int NOT NULL,
  products_model varchar(64),
  products_name varchar(64) NOT NULL,
  products_price decimal(15,4) NOT NULL,
  final_price decimal(15,4) NOT NULL,
  products_tax decimal(7,4) NOT NULL,
  products_quantity int(2) NOT NULL,
  PRIMARY KEY (orders_products_id),
  KEY idx_orders_products_orders_id (orders_id),
  KEY idx_orders_products_products_id (products_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_status;
CREATE TABLE orders_status (
   orders_status_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   orders_status_name varchar(32) NOT NULL,
   public_flag int DEFAULT '1',
   downloads_flag int DEFAULT '0',
   PRIMARY KEY (orders_status_id, language_id),
   KEY idx_orders_status_name (orders_status_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_status_history;
CREATE TABLE orders_status_history (
   orders_status_history_id int NOT NULL auto_increment,
   orders_id int NOT NULL,
   orders_status_id int(5) NOT NULL,
   date_added datetime NOT NULL,
   customer_notified int(1) DEFAULT '0',
   comments text,
   PRIMARY KEY (orders_status_history_id),
   KEY idx_orders_status_history_orders_id (orders_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products_attributes;
CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  orders_products_id int NOT NULL,
  products_options varchar(32) NOT NULL,
  products_options_values varchar(32) NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (orders_products_attributes_id),
  KEY idx_orders_products_att_orders_id (orders_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products_download;
CREATE TABLE orders_products_download (
  orders_products_download_id int NOT NULL auto_increment,
  orders_id int NOT NULL default '0',
  orders_products_id int NOT NULL default '0',
  orders_products_filename varchar(255) NOT NULL default '',
  download_maxdays int(2) NOT NULL default '0',
  download_count int(2) NOT NULL default '0',
  PRIMARY KEY  (orders_products_download_id),
  KEY idx_orders_products_download_orders_id (orders_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_total;
CREATE TABLE orders_total (
  orders_total_id int unsigned NOT NULL auto_increment,
  orders_id int NOT NULL,
  title varchar(255) NOT NULL,
  text varchar(255) NOT NULL,
  value decimal(15,4) NOT NULL,
  class varchar(32) NOT NULL,
  sort_order int NOT NULL,
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  products_id int NOT NULL auto_increment,
  products_quantity int(4) NOT NULL,
  products_model varchar(64),
  products_image varchar(64),
  products_price decimal(15,4) NOT NULL,
  products_date_added datetime NOT NULL,
  products_last_modified datetime,
  products_date_available datetime,
  products_weight decimal(5,2) NOT NULL,
  products_status tinyint(1) NOT NULL,
  products_tax_class_id int NOT NULL,
  manufacturers_id int NULL,
  products_ordered int NOT NULL default '0',
  products_gtin CHAR(14) NULL,
  PRIMARY KEY (products_id),
  KEY idx_products_model (products_model),
  KEY idx_products_date_added (products_date_added)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_attributes;
CREATE TABLE products_attributes (
  products_attributes_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  options_id int NOT NULL,
  options_values_id int NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (products_attributes_id),
  KEY idx_products_attributes_products_id (products_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_attributes_download;
CREATE TABLE products_attributes_download (
  products_attributes_id int NOT NULL,
  products_attributes_filename varchar(255) NOT NULL default '',
  products_attributes_maxdays int(2) default '0',
  products_attributes_maxcount int(2) default '0',
  PRIMARY KEY  (products_attributes_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_description;
CREATE TABLE products_description (
  products_id int NOT NULL auto_increment,
  language_id int NOT NULL default '1',
  products_name varchar(64) NOT NULL default '',
  products_description text,
  products_url varchar(255) default NULL,
  products_viewed int(5) default '0',
  products_seo_description text NULL,
  products_seo_keywords varchar(128) NULL,
  products_seo_title varchar(128) NULL,
  PRIMARY KEY  (products_id,language_id),
  KEY products_name (products_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_images;
CREATE TABLE products_images (
  id int NOT NULL auto_increment,
  products_id int NOT NULL,
  image varchar(64),
  htmlcontent text,
  sort_order int NOT NULL,
  PRIMARY KEY (id),
  KEY products_images_prodid (products_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_notifications;
CREATE TABLE products_notifications (
  products_id int NOT NULL,
  customers_id int NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (products_id, customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options;
CREATE TABLE products_options (
  products_options_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_name varchar(32) NOT NULL default '',
  PRIMARY KEY  (products_options_id,language_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options_values;
CREATE TABLE products_options_values (
  products_options_values_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_values_name varchar(64) NOT NULL default '',
  PRIMARY KEY  (products_options_values_id,language_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options_values_to_products_options;
CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int NOT NULL auto_increment,
  products_options_id int NOT NULL,
  products_options_values_id int NOT NULL,
  PRIMARY KEY (products_options_values_to_products_options_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_to_categories;
CREATE TABLE products_to_categories (
  products_id int NOT NULL,
  categories_id int NOT NULL,
  PRIMARY KEY (products_id,categories_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  reviews_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  customers_id int,
  customers_name varchar(255) NOT NULL,
  reviews_rating int(1),
  date_added datetime,
  last_modified datetime,
  reviews_status tinyint(1) NOT NULL default '0',
  reviews_read int(5) NOT NULL default '0',
  PRIMARY KEY (reviews_id),
  KEY idx_reviews_products_id (products_id),
  KEY idx_reviews_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS reviews_description;
CREATE TABLE reviews_description (
  reviews_id int NOT NULL,
  languages_id int NOT NULL,
  reviews_text text NOT NULL,
  PRIMARY KEY (reviews_id, languages_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS sec_directory_whitelist;
CREATE TABLE sec_directory_whitelist (
  id int NOT NULL auto_increment,
  directory varchar(255) NOT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
  sesskey varchar(128) NOT NULL,
  expiry int(11) unsigned NOT NULL,
  value text NOT NULL,
  PRIMARY KEY (sesskey)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS specials;
CREATE TABLE specials (
  specials_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  specials_new_products_price decimal(15,4) NOT NULL,
  specials_date_added datetime,
  specials_last_modified datetime,
  expires_date datetime,
  date_status_change datetime,
  status int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (specials_id),
  KEY idx_specials_products_id (products_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tax_class;
CREATE TABLE tax_class (
  tax_class_id int NOT NULL auto_increment,
  tax_class_title varchar(32) NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_class_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tax_rates;
CREATE TABLE tax_rates (
  tax_rates_id int NOT NULL auto_increment,
  tax_zone_id int NOT NULL,
  tax_class_id int NOT NULL,
  tax_priority int(5) DEFAULT 1,
  tax_rate decimal(7,4) NOT NULL,
  tax_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_rates_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS geo_zones;
CREATE TABLE geo_zones (
  geo_zone_id int NOT NULL auto_increment,
  geo_zone_name varchar(32) NOT NULL,
  geo_zone_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (geo_zone_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS whos_online;
CREATE TABLE whos_online (
  customer_id int,
  full_name varchar(255) NOT NULL,
  session_id varchar(128) NOT NULL,
  ip_address varchar(255) NOT NULL,
  time_entry varchar(14) NOT NULL,
  time_last_click varchar(14) NOT NULL,
  last_page_url text NOT NULL,
  KEY idx_whos_online_session_id (session_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS zones;
CREATE TABLE zones (
  zone_id int NOT NULL auto_increment,
  zone_country_id int NOT NULL,
  zone_code varchar(32) NOT NULL,
  zone_name varchar(255) NOT NULL,
  PRIMARY KEY (zone_id),
  KEY idx_zones_country_id (zone_country_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS zones_to_geo_zones;
CREATE TABLE zones_to_geo_zones (
   association_id int NOT NULL auto_increment,
   zone_country_id int NOT NULL,
   zone_id int NULL,
   geo_zone_id int NULL,
   last_modified datetime NULL,
   date_added datetime NOT NULL,
   PRIMARY KEY (association_id),
   KEY idx_zones_to_geo_zones_country_id (zone_country_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS testimonials;
CREATE TABLE testimonials (
  testimonials_id int NOT NULL auto_increment,
  customers_id INT(11) NOT NULL DEFAULT '0',
  customers_name varchar(255) NOT NULL,
  date_added datetime,
  last_modified datetime,
  testimonials_status tinyint(1) NOT NULL default '1',
  PRIMARY KEY (testimonials_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS testimonials_description;
CREATE TABLE testimonials_description (
  testimonials_id int NOT NULL,
  languages_id int NOT NULL,
  testimonials_text text NOT NULL,
  PRIMARY KEY (testimonials_id, languages_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

# data

# 1 - Default, 2 - USA, 3 - Spain, 4 - Singapore, 5 - Germany
INSERT INTO address_format VALUES (1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country','$city / $country');
INSERT INTO address_format VALUES (2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country','$city, $state / $country');
INSERT INTO address_format VALUES (3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country','$state / $country');
INSERT INTO address_format VALUES (4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country');
INSERT INTO address_format VALUES (5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country','$city / $country');

INSERT INTO banners VALUES (1, 'osCommerce', 'http://www.oscommerce.com', 'banners/oscommerce.gif', 'footer', '', 0, null, null, now(), null, 1);

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Name', 'STORE_NAME', 'osCommerce', 'The name of my store', '1', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Owner', 'STORE_OWNER', 'Harald Ponce de Leon', 'The name of my store owner', '1', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'root@localhost', 'The e-mail address of my store owner', '1', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail From', 'EMAIL_FROM', 'osCommerce <root@localhost>', 'The e-mail address used in (sent) e-mails', '1', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br /><br /><strong>Note: Please remember to update the store zone.</strong>', '1', '6', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Zone', 'STORE_ZONE', '18', 'The zone my store is located in', '1', '7', 'tep_cfg_get_zone_name', 'tep_cfg_pull_down_zone_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Switch To Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language\'s currency when it is changed', '1', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', '1', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', '1', '12', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', '1', '14', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 'Allow guests to tell a friend about a product', '1', '15', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', '1', '17', 'tep_cfg_select_option(array(\'and\', \'or\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Address', 'STORE_ADDRESS', 'Address Line 1\nAddress Line 2\nCountry\nPhone', 'This is the Address of my store used on printable documents and displayed online', '1', '18', 'tep_cfg_textarea(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Phone', 'STORE_PHONE', '555-1234', 'This is the phone number of my store used on printable documents and displayed online', '1', '19', 'tep_cfg_textarea(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', '1', '20', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'false', 'Display prices with tax included (true) or add the tax at the end (false)', '1', '21', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow all Reviews?', 'ALLOW_ALL_REVIEWS', 'false', 'Allow customers to leave reviews on all products (true) or only on products they have purchased (false)', '1', '22', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('First Name', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', '2', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Last Name', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', '2', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Date of Birth', 'ENTRY_DOB_MIN_LENGTH', '10', 'Minimum length of date of birth', '2', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of e-mail address', '2', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Street Address', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5', 'Minimum length of street address', '2', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Company', 'ENTRY_COMPANY_MIN_LENGTH', '2', 'Minimum length of company name', '2', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Post Code', 'ENTRY_POSTCODE_MIN_LENGTH', '4', 'Minimum length of post code', '2', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('City', 'ENTRY_CITY_MIN_LENGTH', '3', 'Minimum length of city', '2', '8', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('State', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', '2', '9', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Telephone Number', 'ENTRY_TELEPHONE_MIN_LENGTH', '3', 'Minimum length of telephone number', '2', '10', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Password', 'ENTRY_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', '2', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '3', 'Minimum length of credit card owner name', '2', '12', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '10', 'Minimum length of credit card number', '2', '13', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Review Text', 'REVIEW_TEXT_MIN_LENGTH', '50', 'Minimum length of review text', '2', '14', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', '2', '15', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', '3', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '20', 'Amount of products to list', '3', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Page Links', 'MAX_DISPLAY_PAGE_LINKS', '5', 'Number of \'number\' links use for page-sets', '3', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', '3', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Manufacturers List', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '0', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', '3', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', '3', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Length of Manufacturers Name', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15', 'Used in manufacturers box; maximum length of manufacturers name to display', '3', '8', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', '3', '9', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Random Reviews', 'MAX_RANDOM_SELECT_REVIEWS', '10', 'How many records to select from to choose one random product review', '3', '10', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Random New Products', 'MAX_RANDOM_SELECT_NEW', '10', 'How many records to select from to choose one random new product to display', '3', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', '3', '12', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', '3', '13', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '14', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', '3', '15', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', '3', '17', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', '3', '18', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Product Quantities In Shopping Cart', 'MAX_QTY_IN_CART', '99', 'Maximum number of product quantities that can be added to the shopping cart (0 for no limit)', '3', '19', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Small Image Width', 'SMALL_IMAGE_WIDTH', '100', 'The pixel width of small images', '4', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Small Image Height', 'SMALL_IMAGE_HEIGHT', '80', 'The pixel height of small images', '4', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Width', 'HEADING_IMAGE_WIDTH', '57', 'The pixel width of heading images', '4', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Height', 'HEADING_IMAGE_HEIGHT', '40', 'The pixel height of heading images', '4', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subcategory Image Width', 'SUBCATEGORY_IMAGE_WIDTH', '100', 'The pixel width of subcategory images', '4', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subcategory Image Height', 'SUBCATEGORY_IMAGE_HEIGHT', '57', 'The pixel height of subcategory images', '4', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Calculate Image Size', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true', 'Calculate the size of images?', '4', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Image Required', 'IMAGE_REQUIRED', 'true', 'Enable to display broken images. Good for development.', '4', '8', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Gender', 'ACCOUNT_GENDER', 'true', 'Display gender in the customers account', '5', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Date of Birth', 'ACCOUNT_DOB', 'true', 'Display date of birth in the customers account', '5', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Company', 'ACCOUNT_COMPANY', 'true', 'Display company in the customers account', '5', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Suburb', 'ACCOUNT_SUBURB', 'true', 'Display suburb in the customers account', '5', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('State', 'ACCOUNT_STATE', 'true', 'Display state in the customers account', '5', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cod.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cod.php;paypal_express.php)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SHIPPING_INSTALLED', 'flat.php', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ACTION_RECORDER_INSTALLED', 'ar_admin_login.php;ar_contact_us.php;ar_reset_password.php;ar_tell_a_friend.php', 'List of action recorder module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SOCIAL_BOOKMARKS_INSTALLED', 'sb_email.php;sb_facebook.php;sb_pinterest.php;sb_twitter.php', 'List of social bookmark module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_CONTENT_NAVBAR_INSTALLED', 'nb_hamburger_button.php;nb_brand.php;nb_shopping_cart.php', 'List of navbar module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Cash On Delivery Module', 'MODULE_PAYMENT_COD_STATUS', 'True', 'Do you want to accept Cash On Delevery payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort order of display.', 'MODULE_PAYMENT_COD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS', 'True', 'Do you want to offer flat rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Shipping Cost', 'MODULE_SHIPPING_FLAT_COST', '5.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Tax Class', 'MODULE_SHIPPING_FLAT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Shipping Zone', 'MODULE_SHIPPING_FLAT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_SHIPPING_FLAT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '2', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Free Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 'Do you want to allow free shipping?', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) VALUES ('Free Shipping For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', 'Provide free shipping for orders over the set amount.', '6', '4', 'currencies->format', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Provide Free Shipping For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free shipping for orders sent to the set destination.', '6', '5', 'tep_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_TELL_A_FRIEND_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Minutes', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', '5', 'Number of minutes to allow login attempts to occur.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Attempts', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS', '3', 'Number of login attempts to allow within the specified period.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Minutes', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', '5', 'Number of minutes to allow password resets to occur.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Attempts', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS', '1', 'Number of password reset attempts to allow within the specified period.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable E-Mail Module', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS', 'True', 'Do you want to allow products to be shared through e-mail?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Facebook Module', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_STATUS', 'True', 'Do you want to allow products to be shared through Facebook?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Twitter Module', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_STATUS', 'True', 'Do you want to allow products to be shared through Twitter?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Pinterest Module', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS', 'True', 'Do you want to allow Pinterest Button?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Layout Position', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION', 'None', 'Horizontal or Vertical or None', '6', '2', 'tep_cfg_select_option(array(\'Horizontal\', \'Vertical\', \'None\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', '7', '1', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Postal Code', 'SHIPPING_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', '7', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', '7', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', '7', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', '7', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Orders Not Matching Defined Shipping Zones ', 'SHIPPING_ALLOW_UNDEFINED_ZONES', 'False', 'Should orders be allowed to shipping addresses not matching defined shipping module shipping zones?', '7', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Product Card Layout', 'IS_PRODUCT_PRODUCTS_LAYOUT', 'card-deck', 'What Layout suits your shop?  See https://getbootstrap.com/docs/4.3/components/card/#card-layout <div class="secWarning">card-columns is a special use case that will not suit most shops as card-columns is very difficult to layout and sort by...</div>', '8', '100', 'tep_cfg_select_option(array(\'card-group\', \'card-deck\', \'card-columns\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Products In Each Row (SM)', 'IS_PRODUCT_PRODUCTS_DISPLAY_ROW_SM', '2', 'How many products should display per Row in SM (Small) viewport?', '8', '110', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Products In Each Row (MD)', 'IS_PRODUCT_PRODUCTS_DISPLAY_ROW_MD', '3', 'How many products should display per Row in MD (Medium) viewport?', '8', '120', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Products In Each Row (LG)', 'IS_PRODUCT_PRODUCTS_DISPLAY_ROW_LG', '4', 'How many products should display per Row in LG (Large) viewport?', '8', '130', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Products In Each Row (XL)', 'IS_PRODUCT_PRODUCTS_DISPLAY_ROW_XL', '4', 'How many products should display per Row in XL (Large) viewport?', '8', '140', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Manufacturer Name (0=disable; 1=enable)','PRODUCT_LIST_MANUFACTURER', '0', 'Allow sorting by Manufacturer Name?', '8', '200', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Model (0=disable; 1=enable)', 'PRODUCT_LIST_MODEL', '0', 'Allow sorting by Product Model?', '8', '210', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Name (0=disable; 1=enable)', 'PRODUCT_LIST_NAME', '1', 'Allow sorting by Product Name?', '8', '220', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Price (0=disable; 1=enable)', 'PRODUCT_LIST_PRICE', '1', 'Allow sorting by Product Price', '8', '230', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Stock (0=disable; 1=enable)', 'PRODUCT_LIST_QUANTITY', '0', 'Allow sorting by Product Quantity (Stock)?', '8', '240', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Weight (0=disable; 1=enable)', 'PRODUCT_LIST_WEIGHT', '0', 'Allow sorting by Product Weight?', '8', '250', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Latest Added (0=disable; 1=enable)', 'PRODUCT_LIST_ID', '1', 'Allow sorting by Latest Added?', '8', '260', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Sales (0=disable; 1=enable)', 'PRODUCT_LIST_ORDERED', '1', 'Allow sorting by Number of Sales?', '8', '270', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Product Image (defunct)', 'PRODUCT_LIST_IMAGE', '0', 'This is a defunct setting.', '8', '280', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Buy Now column (defunct)', 'PRODUCT_LIST_BUY_NOW', '0', 'This is a defunct setting.', '8', '290', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', '8', '300', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '2', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', '8', '310', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check stock level', 'STOCK_CHECK', 'true', 'Check to see if sufficent stock is available', '9', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', '9', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'true', 'Allow customer to checkout even if there is insufficient stock', '9', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '<i class="fas fa-times fa-2x text-danger"></i>', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', '10', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', '10', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', '10', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', '10', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log', '10', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use Cache', 'USE_CACHE', 'false', 'Use caching features', '11', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Cache Directory', 'DIR_FS_CACHE', '/tmp/', 'The directory where the cached files are saved', '11', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', '12', '1', 'tep_cfg_select_option(array(\'sendmail\', \'smtp\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', '12', '2', 'tep_cfg_select_option(array(\'LF\', \'CRLF\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'false', 'Send e-mails in HTML format', '12', '3', 'tep_cfg_select_option(array(\'true\', \'false\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verify e-mail address through a DNS server', '12', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', '12', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable download', 'DOWNLOAD_ENABLED', 'false', 'Enable the products download functions.', '13', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'false', 'Use browser redirection for download. Disable on non-Unix systems.', '13', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Expiry delay (days)' ,'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', '13', '3', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Maximum number of downloads' ,'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', '13', '4', '', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', '14', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Compression Level', 'GZIP_LEVEL', '5', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', '14', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Session Directory', 'SESSION_WRITE_DIRECTORY', '/tmp', 'If sessions are file based, store them in this directory.', '15', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', '15', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', '15', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', '15', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', '15', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'True', 'Prevent known spiders from starting a session.', '15', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Recreate Session', 'SESSION_RECREATE', 'True', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', '15', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Last Update Check Time', 'LAST_UPDATE_CHECK_TIME', '', 'Last time a check for new versions of osCommerce was run', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Logo', 'STORE_LOGO', 'store_logo.png', 'This is the filename of your Store Logo.  This should be updated at admin > configuration > Store Logo', 6, 1000, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Bootstrap Container', 'BOOTSTRAP_CONTAINER', 'container-fluid', 'What type of container should the page content be shown in? See http://getbootstrap.com/css/#overview-container', '16', '1', 'tep_cfg_select_option(array(\'container-fluid\', \'container\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Bootstrap Content', 'BOOTSTRAP_CONTENT', '8', 'What width should the page content default to?  (8 = two thirds width, 6 = half width, 4 = one third width) Note that the Side Column(s) will adjust automatically.', '16', '2', 'tep_cfg_select_option(array(\'8\', \'6\', \'4\'), ', now());

INSERT INTO configuration_group VALUES ('1', 'My Store', 'General information about my store', '1', '1');
INSERT INTO configuration_group VALUES ('2', 'Minimum Values', 'The minimum values for functions / data', '2', '1');
INSERT INTO configuration_group VALUES ('3', 'Maximum Values', 'The maximum values for functions / data', '3', '1');
INSERT INTO configuration_group VALUES ('4', 'Images', 'Image parameters', '4', '1');
INSERT INTO configuration_group VALUES ('5', 'Customer Details', 'Customer account configuration', '5', '1');
INSERT INTO configuration_group VALUES ('6', 'Module Options', 'Hidden from configuration', '6', '0');
INSERT INTO configuration_group VALUES ('7', 'Shipping/Packaging', 'Shipping options available at my store', '7', '1');
INSERT INTO configuration_group VALUES ('8', 'Product Listing', 'Product Listing configuration options', '8', '1');
INSERT INTO configuration_group VALUES ('9', 'Stock', 'Stock configuration options', '9', '1');
INSERT INTO configuration_group VALUES ('10', 'Logging', 'Logging configuration options', '10', '1');
INSERT INTO configuration_group VALUES ('11', 'Cache', 'Caching configuration options', '11', '1');
INSERT INTO configuration_group VALUES ('12', 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', '12', '1');
INSERT INTO configuration_group VALUES ('13', 'Download', 'Downloadable products options', '13', '1');
INSERT INTO configuration_group VALUES ('14', 'GZip Compression', 'GZip compression options', '14', '1');
INSERT INTO configuration_group VALUES ('15', 'Sessions', 'Session options', '15', '1');
INSERT INTO configuration_group VALUES ('16', 'Bootstrap Setup', 'Basic Bootstrap Options', '16', '1');

INSERT INTO countries VALUES (1,'Afghanistan','AF','AFG','1');
INSERT INTO countries VALUES (2,'Albania','AL','ALB','1');
INSERT INTO countries VALUES (3,'Algeria','DZ','DZA','1');
INSERT INTO countries VALUES (4,'American Samoa','AS','ASM','1');
INSERT INTO countries VALUES (5,'Andorra','AD','AND','1');
INSERT INTO countries VALUES (6,'Angola','AO','AGO','1');
INSERT INTO countries VALUES (7,'Anguilla','AI','AIA','1');
INSERT INTO countries VALUES (8,'Antarctica','AQ','ATA','1');
INSERT INTO countries VALUES (9,'Antigua and Barbuda','AG','ATG','1');
INSERT INTO countries VALUES (10,'Argentina','AR','ARG','1');
INSERT INTO countries VALUES (11,'Armenia','AM','ARM','1');
INSERT INTO countries VALUES (12,'Aruba','AW','ABW','1');
INSERT INTO countries VALUES (13,'Australia','AU','AUS','1');
INSERT INTO countries VALUES (14,'Austria','AT','AUT','5');
INSERT INTO countries VALUES (15,'Azerbaijan','AZ','AZE','1');
INSERT INTO countries VALUES (16,'Bahamas','BS','BHS','1');
INSERT INTO countries VALUES (17,'Bahrain','BH','BHR','1');
INSERT INTO countries VALUES (18,'Bangladesh','BD','BGD','1');
INSERT INTO countries VALUES (19,'Barbados','BB','BRB','1');
INSERT INTO countries VALUES (20,'Belarus','BY','BLR','1');
INSERT INTO countries VALUES (21,'Belgium','BE','BEL','1');
INSERT INTO countries VALUES (22,'Belize','BZ','BLZ','1');
INSERT INTO countries VALUES (23,'Benin','BJ','BEN','1');
INSERT INTO countries VALUES (24,'Bermuda','BM','BMU','1');
INSERT INTO countries VALUES (25,'Bhutan','BT','BTN','1');
INSERT INTO countries VALUES (26,'Bolivia','BO','BOL','1');
INSERT INTO countries VALUES (27,'Bosnia and Herzegowina','BA','BIH','1');
INSERT INTO countries VALUES (28,'Botswana','BW','BWA','1');
INSERT INTO countries VALUES (29,'Bouvet Island','BV','BVT','1');
INSERT INTO countries VALUES (30,'Brazil','BR','BRA','1');
INSERT INTO countries VALUES (31,'British Indian Ocean Territory','IO','IOT','1');
INSERT INTO countries VALUES (32,'Brunei Darussalam','BN','BRN','1');
INSERT INTO countries VALUES (33,'Bulgaria','BG','BGR','1');
INSERT INTO countries VALUES (34,'Burkina Faso','BF','BFA','1');
INSERT INTO countries VALUES (35,'Burundi','BI','BDI','1');
INSERT INTO countries VALUES (36,'Cambodia','KH','KHM','1');
INSERT INTO countries VALUES (37,'Cameroon','CM','CMR','1');
INSERT INTO countries VALUES (38,'Canada','CA','CAN','1');
INSERT INTO countries VALUES (39,'Cape Verde','CV','CPV','1');
INSERT INTO countries VALUES (40,'Cayman Islands','KY','CYM','1');
INSERT INTO countries VALUES (41,'Central African Republic','CF','CAF','1');
INSERT INTO countries VALUES (42,'Chad','TD','TCD','1');
INSERT INTO countries VALUES (43,'Chile','CL','CHL','1');
INSERT INTO countries VALUES (44,'China','CN','CHN','1');
INSERT INTO countries VALUES (45,'Christmas Island','CX','CXR','1');
INSERT INTO countries VALUES (46,'Cocos (Keeling) Islands','CC','CCK','1');
INSERT INTO countries VALUES (47,'Colombia','CO','COL','1');
INSERT INTO countries VALUES (48,'Comoros','KM','COM','1');
INSERT INTO countries VALUES (49,'Congo','CG','COG','1');
INSERT INTO countries VALUES (50,'Cook Islands','CK','COK','1');
INSERT INTO countries VALUES (51,'Costa Rica','CR','CRI','1');
INSERT INTO countries VALUES (52,'Cote D\'Ivoire','CI','CIV','1');
INSERT INTO countries VALUES (53,'Croatia','HR','HRV','1');
INSERT INTO countries VALUES (54,'Cuba','CU','CUB','1');
INSERT INTO countries VALUES (55,'Cyprus','CY','CYP','1');
INSERT INTO countries VALUES (56,'Czech Republic','CZ','CZE','1');
INSERT INTO countries VALUES (57,'Denmark','DK','DNK','1');
INSERT INTO countries VALUES (58,'Djibouti','DJ','DJI','1');
INSERT INTO countries VALUES (59,'Dominica','DM','DMA','1');
INSERT INTO countries VALUES (60,'Dominican Republic','DO','DOM','1');
INSERT INTO countries VALUES (61,'East Timor','TP','TMP','1');
INSERT INTO countries VALUES (62,'Ecuador','EC','ECU','1');
INSERT INTO countries VALUES (63,'Egypt','EG','EGY','1');
INSERT INTO countries VALUES (64,'El Salvador','SV','SLV','1');
INSERT INTO countries VALUES (65,'Equatorial Guinea','GQ','GNQ','1');
INSERT INTO countries VALUES (66,'Eritrea','ER','ERI','1');
INSERT INTO countries VALUES (67,'Estonia','EE','EST','1');
INSERT INTO countries VALUES (68,'Ethiopia','ET','ETH','1');
INSERT INTO countries VALUES (69,'Falkland Islands (Malvinas)','FK','FLK','1');
INSERT INTO countries VALUES (70,'Faroe Islands','FO','FRO','1');
INSERT INTO countries VALUES (71,'Fiji','FJ','FJI','1');
INSERT INTO countries VALUES (72,'Finland','FI','FIN','1');
INSERT INTO countries VALUES (73,'France','FR','FRA','1');
INSERT INTO countries VALUES (74,'France, Metropolitan','FX','FXX','1');
INSERT INTO countries VALUES (75,'French Guiana','GF','GUF','1');
INSERT INTO countries VALUES (76,'French Polynesia','PF','PYF','1');
INSERT INTO countries VALUES (77,'French Southern Territories','TF','ATF','1');
INSERT INTO countries VALUES (78,'Gabon','GA','GAB','1');
INSERT INTO countries VALUES (79,'Gambia','GM','GMB','1');
INSERT INTO countries VALUES (80,'Georgia','GE','GEO','1');
INSERT INTO countries VALUES (81,'Germany','DE','DEU','5');
INSERT INTO countries VALUES (82,'Ghana','GH','GHA','1');
INSERT INTO countries VALUES (83,'Gibraltar','GI','GIB','1');
INSERT INTO countries VALUES (84,'Greece','GR','GRC','1');
INSERT INTO countries VALUES (85,'Greenland','GL','GRL','1');
INSERT INTO countries VALUES (86,'Grenada','GD','GRD','1');
INSERT INTO countries VALUES (87,'Guadeloupe','GP','GLP','1');
INSERT INTO countries VALUES (88,'Guam','GU','GUM','1');
INSERT INTO countries VALUES (89,'Guatemala','GT','GTM','1');
INSERT INTO countries VALUES (90,'Guinea','GN','GIN','1');
INSERT INTO countries VALUES (91,'Guinea-bissau','GW','GNB','1');
INSERT INTO countries VALUES (92,'Guyana','GY','GUY','1');
INSERT INTO countries VALUES (93,'Haiti','HT','HTI','1');
INSERT INTO countries VALUES (94,'Heard and Mc Donald Islands','HM','HMD','1');
INSERT INTO countries VALUES (95,'Honduras','HN','HND','1');
INSERT INTO countries VALUES (96,'Hong Kong','HK','HKG','1');
INSERT INTO countries VALUES (97,'Hungary','HU','HUN','1');
INSERT INTO countries VALUES (98,'Iceland','IS','ISL','1');
INSERT INTO countries VALUES (99,'India','IN','IND','1');
INSERT INTO countries VALUES (100,'Indonesia','ID','IDN','1');
INSERT INTO countries VALUES (101,'Iran (Islamic Republic of)','IR','IRN','1');
INSERT INTO countries VALUES (102,'Iraq','IQ','IRQ','1');
INSERT INTO countries VALUES (103,'Ireland','IE','IRL','1');
INSERT INTO countries VALUES (104,'Israel','IL','ISR','1');
INSERT INTO countries VALUES (105,'Italy','IT','ITA','1');
INSERT INTO countries VALUES (106,'Jamaica','JM','JAM','1');
INSERT INTO countries VALUES (107,'Japan','JP','JPN','1');
INSERT INTO countries VALUES (108,'Jordan','JO','JOR','1');
INSERT INTO countries VALUES (109,'Kazakhstan','KZ','KAZ','1');
INSERT INTO countries VALUES (110,'Kenya','KE','KEN','1');
INSERT INTO countries VALUES (111,'Kiribati','KI','KIR','1');
INSERT INTO countries VALUES (112,'Korea, Democratic People\'s Republic of','KP','PRK','1');
INSERT INTO countries VALUES (113,'Korea, Republic of','KR','KOR','1');
INSERT INTO countries VALUES (114,'Kuwait','KW','KWT','1');
INSERT INTO countries VALUES (115,'Kyrgyzstan','KG','KGZ','1');
INSERT INTO countries VALUES (116,'Lao People\'s Democratic Republic','LA','LAO','1');
INSERT INTO countries VALUES (117,'Latvia','LV','LVA','1');
INSERT INTO countries VALUES (118,'Lebanon','LB','LBN','1');
INSERT INTO countries VALUES (119,'Lesotho','LS','LSO','1');
INSERT INTO countries VALUES (120,'Liberia','LR','LBR','1');
INSERT INTO countries VALUES (121,'Libyan Arab Jamahiriya','LY','LBY','1');
INSERT INTO countries VALUES (122,'Liechtenstein','LI','LIE','1');
INSERT INTO countries VALUES (123,'Lithuania','LT','LTU','1');
INSERT INTO countries VALUES (124,'Luxembourg','LU','LUX','1');
INSERT INTO countries VALUES (125,'Macau','MO','MAC','1');
INSERT INTO countries VALUES (126,'Macedonia, The Former Yugoslav Republic of','MK','MKD','1');
INSERT INTO countries VALUES (127,'Madagascar','MG','MDG','1');
INSERT INTO countries VALUES (128,'Malawi','MW','MWI','1');
INSERT INTO countries VALUES (129,'Malaysia','MY','MYS','1');
INSERT INTO countries VALUES (130,'Maldives','MV','MDV','1');
INSERT INTO countries VALUES (131,'Mali','ML','MLI','1');
INSERT INTO countries VALUES (132,'Malta','MT','MLT','1');
INSERT INTO countries VALUES (133,'Marshall Islands','MH','MHL','1');
INSERT INTO countries VALUES (134,'Martinique','MQ','MTQ','1');
INSERT INTO countries VALUES (135,'Mauritania','MR','MRT','1');
INSERT INTO countries VALUES (136,'Mauritius','MU','MUS','1');
INSERT INTO countries VALUES (137,'Mayotte','YT','MYT','1');
INSERT INTO countries VALUES (138,'Mexico','MX','MEX','1');
INSERT INTO countries VALUES (139,'Micronesia, Federated States of','FM','FSM','1');
INSERT INTO countries VALUES (140,'Moldova, Republic of','MD','MDA','1');
INSERT INTO countries VALUES (141,'Monaco','MC','MCO','1');
INSERT INTO countries VALUES (142,'Mongolia','MN','MNG','1');
INSERT INTO countries VALUES (143,'Montserrat','MS','MSR','1');
INSERT INTO countries VALUES (144,'Morocco','MA','MAR','1');
INSERT INTO countries VALUES (145,'Mozambique','MZ','MOZ','1');
INSERT INTO countries VALUES (146,'Myanmar','MM','MMR','1');
INSERT INTO countries VALUES (147,'Namibia','NA','NAM','1');
INSERT INTO countries VALUES (148,'Nauru','NR','NRU','1');
INSERT INTO countries VALUES (149,'Nepal','NP','NPL','1');
INSERT INTO countries VALUES (150,'Netherlands','NL','NLD','1');
INSERT INTO countries VALUES (151,'Netherlands Antilles','AN','ANT','1');
INSERT INTO countries VALUES (152,'New Caledonia','NC','NCL','1');
INSERT INTO countries VALUES (153,'New Zealand','NZ','NZL','1');
INSERT INTO countries VALUES (154,'Nicaragua','NI','NIC','1');
INSERT INTO countries VALUES (155,'Niger','NE','NER','1');
INSERT INTO countries VALUES (156,'Nigeria','NG','NGA','1');
INSERT INTO countries VALUES (157,'Niue','NU','NIU','1');
INSERT INTO countries VALUES (158,'Norfolk Island','NF','NFK','1');
INSERT INTO countries VALUES (159,'Northern Mariana Islands','MP','MNP','1');
INSERT INTO countries VALUES (160,'Norway','NO','NOR','1');
INSERT INTO countries VALUES (161,'Oman','OM','OMN','1');
INSERT INTO countries VALUES (162,'Pakistan','PK','PAK','1');
INSERT INTO countries VALUES (163,'Palau','PW','PLW','1');
INSERT INTO countries VALUES (164,'Panama','PA','PAN','1');
INSERT INTO countries VALUES (165,'Papua New Guinea','PG','PNG','1');
INSERT INTO countries VALUES (166,'Paraguay','PY','PRY','1');
INSERT INTO countries VALUES (167,'Peru','PE','PER','1');
INSERT INTO countries VALUES (168,'Philippines','PH','PHL','1');
INSERT INTO countries VALUES (169,'Pitcairn','PN','PCN','1');
INSERT INTO countries VALUES (170,'Poland','PL','POL','1');
INSERT INTO countries VALUES (171,'Portugal','PT','PRT','1');
INSERT INTO countries VALUES (172,'Puerto Rico','PR','PRI','1');
INSERT INTO countries VALUES (173,'Qatar','QA','QAT','1');
INSERT INTO countries VALUES (174,'Reunion','RE','REU','1');
INSERT INTO countries VALUES (175,'Romania','RO','ROM','1');
INSERT INTO countries VALUES (176,'Russian Federation','RU','RUS','1');
INSERT INTO countries VALUES (177,'Rwanda','RW','RWA','1');
INSERT INTO countries VALUES (178,'Saint Kitts and Nevis','KN','KNA','1');
INSERT INTO countries VALUES (179,'Saint Lucia','LC','LCA','1');
INSERT INTO countries VALUES (180,'Saint Vincent and the Grenadines','VC','VCT','1');
INSERT INTO countries VALUES (181,'Samoa','WS','WSM','1');
INSERT INTO countries VALUES (182,'San Marino','SM','SMR','1');
INSERT INTO countries VALUES (183,'Sao Tome and Principe','ST','STP','1');
INSERT INTO countries VALUES (184,'Saudi Arabia','SA','SAU','1');
INSERT INTO countries VALUES (185,'Senegal','SN','SEN','1');
INSERT INTO countries VALUES (186,'Seychelles','SC','SYC','1');
INSERT INTO countries VALUES (187,'Sierra Leone','SL','SLE','1');
INSERT INTO countries VALUES (188,'Singapore','SG','SGP', '4');
INSERT INTO countries VALUES (189,'Slovakia (Slovak Republic)','SK','SVK','1');
INSERT INTO countries VALUES (190,'Slovenia','SI','SVN','1');
INSERT INTO countries VALUES (191,'Solomon Islands','SB','SLB','1');
INSERT INTO countries VALUES (192,'Somalia','SO','SOM','1');
INSERT INTO countries VALUES (193,'South Africa','ZA','ZAF','1');
INSERT INTO countries VALUES (194,'South Georgia and the South Sandwich Islands','GS','SGS','1');
INSERT INTO countries VALUES (195,'Spain','ES','ESP','3');
INSERT INTO countries VALUES (196,'Sri Lanka','LK','LKA','1');
INSERT INTO countries VALUES (197,'St. Helena','SH','SHN','1');
INSERT INTO countries VALUES (198,'St. Pierre and Miquelon','PM','SPM','1');
INSERT INTO countries VALUES (199,'Sudan','SD','SDN','1');
INSERT INTO countries VALUES (200,'Suriname','SR','SUR','1');
INSERT INTO countries VALUES (201,'Svalbard and Jan Mayen Islands','SJ','SJM','1');
INSERT INTO countries VALUES (202,'Swaziland','SZ','SWZ','1');
INSERT INTO countries VALUES (203,'Sweden','SE','SWE','1');
INSERT INTO countries VALUES (204,'Switzerland','CH','CHE','1');
INSERT INTO countries VALUES (205,'Syrian Arab Republic','SY','SYR','1');
INSERT INTO countries VALUES (206,'Taiwan','TW','TWN','1');
INSERT INTO countries VALUES (207,'Tajikistan','TJ','TJK','1');
INSERT INTO countries VALUES (208,'Tanzania, United Republic of','TZ','TZA','1');
INSERT INTO countries VALUES (209,'Thailand','TH','THA','1');
INSERT INTO countries VALUES (210,'Togo','TG','TGO','1');
INSERT INTO countries VALUES (211,'Tokelau','TK','TKL','1');
INSERT INTO countries VALUES (212,'Tonga','TO','TON','1');
INSERT INTO countries VALUES (213,'Trinidad and Tobago','TT','TTO','1');
INSERT INTO countries VALUES (214,'Tunisia','TN','TUN','1');
INSERT INTO countries VALUES (215,'Turkey','TR','TUR','1');
INSERT INTO countries VALUES (216,'Turkmenistan','TM','TKM','1');
INSERT INTO countries VALUES (217,'Turks and Caicos Islands','TC','TCA','1');
INSERT INTO countries VALUES (218,'Tuvalu','TV','TUV','1');
INSERT INTO countries VALUES (219,'Uganda','UG','UGA','1');
INSERT INTO countries VALUES (220,'Ukraine','UA','UKR','1');
INSERT INTO countries VALUES (221,'United Arab Emirates','AE','ARE','1');
INSERT INTO countries VALUES (222,'United Kingdom','GB','GBR','1');
INSERT INTO countries VALUES (223,'United States','US','USA', '2');
INSERT INTO countries VALUES (224,'United States Minor Outlying Islands','UM','UMI','1');
INSERT INTO countries VALUES (225,'Uruguay','UY','URY','1');
INSERT INTO countries VALUES (226,'Uzbekistan','UZ','UZB','1');
INSERT INTO countries VALUES (227,'Vanuatu','VU','VUT','1');
INSERT INTO countries VALUES (228,'Vatican City State (Holy See)','VA','VAT','1');
INSERT INTO countries VALUES (229,'Venezuela','VE','VEN','1');
INSERT INTO countries VALUES (230,'Viet Nam','VN','VNM','1');
INSERT INTO countries VALUES (231,'Virgin Islands (British)','VG','VGB','1');
INSERT INTO countries VALUES (232,'Virgin Islands (U.S.)','VI','VIR','1');
INSERT INTO countries VALUES (233,'Wallis and Futuna Islands','WF','WLF','1');
INSERT INTO countries VALUES (234,'Western Sahara','EH','ESH','1');
INSERT INTO countries VALUES (235,'Yemen','YE','YEM','1');
INSERT INTO countries VALUES (236,'Yugoslavia','YU','YUG','1');
INSERT INTO countries VALUES (237,'Zaire','ZR','ZAR','1');
INSERT INTO countries VALUES (238,'Zambia','ZM','ZMB','1');
INSERT INTO countries VALUES (239,'Zimbabwe','ZW','ZWE','1');

INSERT INTO currencies VALUES (1,'U.S. Dollar','USD','$','','.',',','2','1.0000', now());
INSERT INTO currencies VALUES (2,'Euro','EUR','','€','.',',','2','1.0000', now());

INSERT INTO languages VALUES (1,'English','en','icon.gif','english',1);

INSERT INTO orders_status VALUES ( '1', '1', 'Pending', '1', '0');
INSERT INTO orders_status VALUES ( '2', '1', 'Processing', '1', '1');
INSERT INTO orders_status VALUES ( '3', '1', 'Delivered', '1', '1');

INSERT INTO sec_directory_whitelist values (null, 'admin/backups');
INSERT INTO sec_directory_whitelist values (null, 'admin/images/graphs');
INSERT INTO sec_directory_whitelist values (null, 'images');
INSERT INTO sec_directory_whitelist values (null, 'images/banners');
INSERT INTO sec_directory_whitelist values (null, 'images/dvd');
INSERT INTO sec_directory_whitelist values (null, 'images/gt_interactive');
INSERT INTO sec_directory_whitelist values (null, 'images/hewlett_packard');
INSERT INTO sec_directory_whitelist values (null, 'images/matrox');
INSERT INTO sec_directory_whitelist values (null, 'images/microsoft');
INSERT INTO sec_directory_whitelist values (null, 'images/samsung');
INSERT INTO sec_directory_whitelist values (null, 'images/sierra');
INSERT INTO sec_directory_whitelist values (null, 'includes/work');
INSERT INTO sec_directory_whitelist values (null, 'pub');

INSERT INTO tax_class VALUES (1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', now(), now());

# USA/Florida
INSERT INTO tax_rates VALUES (1, 1, 1, 1, 7.0, 'FL TAX 7.0%', now(), now());
INSERT INTO geo_zones (geo_zone_id,geo_zone_name,geo_zone_description,date_added) VALUES (1,"Florida","Florida local sales tax zone",now());
INSERT INTO zones_to_geo_zones (association_id,zone_country_id,zone_id,geo_zone_id,date_added) VALUES (1,223,18,1,now());

# USA
INSERT INTO zones VALUES (1,223,'AL','Alabama');
INSERT INTO zones VALUES (2,223,'AK','Alaska');
INSERT INTO zones VALUES (3,223,'AS','American Samoa');
INSERT INTO zones VALUES (4,223,'AZ','Arizona');
INSERT INTO zones VALUES (5,223,'AR','Arkansas');
INSERT INTO zones VALUES (6,223,'AF','Armed Forces Africa');
INSERT INTO zones VALUES (7,223,'AA','Armed Forces Americas');
INSERT INTO zones VALUES (8,223,'AC','Armed Forces Canada');
INSERT INTO zones VALUES (9,223,'AE','Armed Forces Europe');
INSERT INTO zones VALUES (10,223,'AM','Armed Forces Middle East');
INSERT INTO zones VALUES (11,223,'AP','Armed Forces Pacific');
INSERT INTO zones VALUES (12,223,'CA','California');
INSERT INTO zones VALUES (13,223,'CO','Colorado');
INSERT INTO zones VALUES (14,223,'CT','Connecticut');
INSERT INTO zones VALUES (15,223,'DE','Delaware');
INSERT INTO zones VALUES (16,223,'DC','District of Columbia');
INSERT INTO zones VALUES (17,223,'FM','Federated States Of Micronesia');
INSERT INTO zones VALUES (18,223,'FL','Florida');
INSERT INTO zones VALUES (19,223,'GA','Georgia');
INSERT INTO zones VALUES (20,223,'GU','Guam');
INSERT INTO zones VALUES (21,223,'HI','Hawaii');
INSERT INTO zones VALUES (22,223,'ID','Idaho');
INSERT INTO zones VALUES (23,223,'IL','Illinois');
INSERT INTO zones VALUES (24,223,'IN','Indiana');
INSERT INTO zones VALUES (25,223,'IA','Iowa');
INSERT INTO zones VALUES (26,223,'KS','Kansas');
INSERT INTO zones VALUES (27,223,'KY','Kentucky');
INSERT INTO zones VALUES (28,223,'LA','Louisiana');
INSERT INTO zones VALUES (29,223,'ME','Maine');
INSERT INTO zones VALUES (30,223,'MH','Marshall Islands');
INSERT INTO zones VALUES (31,223,'MD','Maryland');
INSERT INTO zones VALUES (32,223,'MA','Massachusetts');
INSERT INTO zones VALUES (33,223,'MI','Michigan');
INSERT INTO zones VALUES (34,223,'MN','Minnesota');
INSERT INTO zones VALUES (35,223,'MS','Mississippi');
INSERT INTO zones VALUES (36,223,'MO','Missouri');
INSERT INTO zones VALUES (37,223,'MT','Montana');
INSERT INTO zones VALUES (38,223,'NE','Nebraska');
INSERT INTO zones VALUES (39,223,'NV','Nevada');
INSERT INTO zones VALUES (40,223,'NH','New Hampshire');
INSERT INTO zones VALUES (41,223,'NJ','New Jersey');
INSERT INTO zones VALUES (42,223,'NM','New Mexico');
INSERT INTO zones VALUES (43,223,'NY','New York');
INSERT INTO zones VALUES (44,223,'NC','North Carolina');
INSERT INTO zones VALUES (45,223,'ND','North Dakota');
INSERT INTO zones VALUES (46,223,'MP','Northern Mariana Islands');
INSERT INTO zones VALUES (47,223,'OH','Ohio');
INSERT INTO zones VALUES (48,223,'OK','Oklahoma');
INSERT INTO zones VALUES (49,223,'OR','Oregon');
INSERT INTO zones VALUES (50,223,'PW','Palau');
INSERT INTO zones VALUES (51,223,'PA','Pennsylvania');
INSERT INTO zones VALUES (52,223,'PR','Puerto Rico');
INSERT INTO zones VALUES (53,223,'RI','Rhode Island');
INSERT INTO zones VALUES (54,223,'SC','South Carolina');
INSERT INTO zones VALUES (55,223,'SD','South Dakota');
INSERT INTO zones VALUES (56,223,'TN','Tennessee');
INSERT INTO zones VALUES (57,223,'TX','Texas');
INSERT INTO zones VALUES (58,223,'UT','Utah');
INSERT INTO zones VALUES (59,223,'VT','Vermont');
INSERT INTO zones VALUES (60,223,'VI','Virgin Islands');
INSERT INTO zones VALUES (61,223,'VA','Virginia');
INSERT INTO zones VALUES (62,223,'WA','Washington');
INSERT INTO zones VALUES (63,223,'WV','West Virginia');
INSERT INTO zones VALUES (64,223,'WI','Wisconsin');
INSERT INTO zones VALUES (65,223,'WY','Wyoming');

# Canada
INSERT INTO zones VALUES (66,38,'AB','Alberta');
INSERT INTO zones VALUES (67,38,'BC','British Columbia');
INSERT INTO zones VALUES (68,38,'MB','Manitoba');
INSERT INTO zones VALUES (69,38,'NF','Newfoundland');
INSERT INTO zones VALUES (70,38,'NB','New Brunswick');
INSERT INTO zones VALUES (71,38,'NS','Nova Scotia');
INSERT INTO zones VALUES (72,38,'NT','Northwest Territories');
INSERT INTO zones VALUES (73,38,'NU','Nunavut');
INSERT INTO zones VALUES (74,38,'ON','Ontario');
INSERT INTO zones VALUES (75,38,'PE','Prince Edward Island');
INSERT INTO zones VALUES (76,38,'QC','Quebec');
INSERT INTO zones VALUES (77,38,'SK','Saskatchewan');
INSERT INTO zones VALUES (78,38,'YT','Yukon Territory');

# Germany
INSERT INTO zones VALUES (79,81,'NDS','Niedersachsen');
INSERT INTO zones VALUES (80,81,'BAW','Baden-Württemberg');
INSERT INTO zones VALUES (81,81,'BAY','Bayern');
INSERT INTO zones VALUES (82,81,'BER','Berlin');
INSERT INTO zones VALUES (83,81,'BRG','Brandenburg');
INSERT INTO zones VALUES (84,81,'BRE','Bremen');
INSERT INTO zones VALUES (85,81,'HAM','Hamburg');
INSERT INTO zones VALUES (86,81,'HES','Hessen');
INSERT INTO zones VALUES (87,81,'MEC','Mecklenburg-Vorpommern');
INSERT INTO zones VALUES (88,81,'NRW','Nordrhein-Westfalen');
INSERT INTO zones VALUES (89,81,'RHE','Rheinland-Pfalz');
INSERT INTO zones VALUES (90,81,'SAR','Saarland');
INSERT INTO zones VALUES (91,81,'SAS','Sachsen');
INSERT INTO zones VALUES (92,81,'SAC','Sachsen-Anhalt');
INSERT INTO zones VALUES (93,81,'SCN','Schleswig-Holstein');
INSERT INTO zones VALUES (94,81,'THE','Thüringen');

# Austria
INSERT INTO zones VALUES (95,14,'WI','Wien');
INSERT INTO zones VALUES (96,14,'NO','Niederösterreich');
INSERT INTO zones VALUES (97,14,'OO','Oberösterreich');
INSERT INTO zones VALUES (98,14,'SB','Salzburg');
INSERT INTO zones VALUES (99,14,'KN','Kärnten');
INSERT INTO zones VALUES (100,14,'ST','Steiermark');
INSERT INTO zones VALUES (101,14,'TI','Tirol');
INSERT INTO zones VALUES (102,14,'BL','Burgenland');
INSERT INTO zones VALUES (103,14,'VB','Voralberg');

# Swizterland
INSERT INTO zones VALUES (104,204,'AG','Aargau');
INSERT INTO zones VALUES (105,204,'AI','Appenzell Innerrhoden');
INSERT INTO zones VALUES (106,204,'AR','Appenzell Ausserrhoden');
INSERT INTO zones VALUES (107,204,'BE','Bern');
INSERT INTO zones VALUES (108,204,'BL','Basel-Landschaft');
INSERT INTO zones VALUES (109,204,'BS','Basel-Stadt');
INSERT INTO zones VALUES (110,204,'FR','Freiburg');
INSERT INTO zones VALUES (111,204,'GE','Genf');
INSERT INTO zones VALUES (112,204,'GL','Glarus');
INSERT INTO zones VALUES (113,204,'JU','Graubünden');
INSERT INTO zones VALUES (114,204,'JU','Jura');
INSERT INTO zones VALUES (115,204,'LU','Luzern');
INSERT INTO zones VALUES (116,204,'NE','Neuenburg');
INSERT INTO zones VALUES (117,204,'NW','Nidwalden');
INSERT INTO zones VALUES (118,204,'OW','Obwalden');
INSERT INTO zones VALUES (119,204,'SG','St. Gallen');
INSERT INTO zones VALUES (120,204,'SH','Schaffhausen');
INSERT INTO zones VALUES (121,204,'SO','Solothurn');
INSERT INTO zones VALUES (122,204,'SZ','Schwyz');
INSERT INTO zones VALUES (123,204,'TG','Thurgau');
INSERT INTO zones VALUES (124,204,'TI','Tessin');
INSERT INTO zones VALUES (125,204,'UR','Uri');
INSERT INTO zones VALUES (126,204,'VD','Waadt');
INSERT INTO zones VALUES (127,204,'VS','Wallis');
INSERT INTO zones VALUES (128,204,'ZG','Zug');
INSERT INTO zones VALUES (129,204,'ZH','Zürich');

# Spain
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'A Coruña','A Coruña');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alava','Alava');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Albacete','Albacete');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alicante','Alicante');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Almeria','Almeria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Asturias','Asturias');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Avila','Avila');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Badajoz','Badajoz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Baleares','Baleares');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Barcelona','Barcelona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Burgos','Burgos');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Caceres','Caceres');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cadiz','Cadiz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cantabria','Cantabria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Castellon','Castellon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ceuta','Ceuta');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ciudad Real','Ciudad Real');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cordoba','Cordoba');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cuenca','Cuenca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Girona','Girona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Granada','Granada');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guadalajara','Guadalajara');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guipuzcoa','Guipuzcoa');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huelva','Huelva');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huesca','Huesca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Jaen','Jaen');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'La Rioja','La Rioja');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Las Palmas','Las Palmas');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Leon','Leon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lleida','Lleida');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lugo','Lugo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Madrid','Madrid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Malaga','Malaga');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Melilla','Melilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Murcia','Murcia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Navarra','Navarra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ourense','Ourense');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Palencia','Palencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Pontevedra','Pontevedra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Salamanca','Salamanca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Santa Cruz de Tenerife','Santa Cruz de Tenerife');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Segovia','Segovia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Sevilla','Sevilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Soria','Soria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Tarragona','Tarragona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Teruel','Teruel');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Toledo','Toledo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valencia','Valencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valladolid','Valladolid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Vizcaya','Vizcaya');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zamora','Zamora');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zaragoza','Zaragoza');

# Header Tags
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_HEADER_TAGS_INSTALLED', 'ht_manufacturer_title.php;ht_category_title.php;ht_product_title.php;ht_robot_noindex.php;ht_datepicker_jquery.php;ht_table_click_jquery.php;ht_breadcrumb_schema.php;ht_product_colorbox.php', 'List of header tag module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Category Title Module', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'True', 'Do you want to allow category titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Title Override?', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow category titles to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Breadcrumb Override?', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SEO_BREADCRUMB_OVERRIDE', 'True', 'Do you want to allow category names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturer Title Module', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS', 'True', 'Do you want to allow manufacturer titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Title Override?', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow manufacturer names to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Breadcrumb Override?', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SEO_BREADCRUMB_OVERRIDE', 'True', 'Do you want to allow manufacturer names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Title Module', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'True', 'Do you want to allow product titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Title Override?', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow product titles to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Breadcrumb Override?', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_BREADCRUMB_OVERRIDE', 'True', 'Do you want to allow product names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Robot NoIndex Module', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_STATUS', 'True', 'Do you want to enable the Robot NoIndex module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_PAGES', 'account.php;account_edit.php;account_history.php;account_history_info.php;account_newsletters.php;account_notifications.php;account_password.php;address_book.php;address_book_process.php;checkout_confirmation.php;checkout_payment.php;checkout_payment_address.php;checkout_process.php;checkout_shipping.php;checkout_shipping_address.php;checkout_success.php;cookie_usage.php;create_account.php;create_account_success.php;login.php;logoff.php;password_forgotten.php;password_reset.php;shopping_cart.php;ssl_check.php;tell_a_friend.php', 'The pages to add the meta robot noindex tag to.', '6', '0', 'ht_robot_noindex_show_pages', 'ht_robot_noindex_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Datepicker jQuery Module', 'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_STATUS', 'True', 'Do you want to enable the Datepicker module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_PAGES', 'advanced_search.php;account_edit.php;create_account.php', 'The pages to add the Datepicker jQuery Scripts to.', '6', '0', 'ht_datepicker_jquery_show_pages', 'ht_datepicker_jquery_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Clickable Table Rows Module', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_STATUS', 'True', 'Do you want to enable the Clickable Table Rows module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_PAGES', 'checkout_payment.php;checkout_shipping.php', 'The pages to add the jQuery Scripts to.', '6', '0', 'ht_table_click_jquery_show_pages', 'ht_table_click_jquery_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Colorbox Script', 'MODULE_HEADER_TAGS_PRODUCT_COLORBOX_STATUS', 'True', 'Do you want to enable the Colorbox Scripts?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_PRODUCT_COLORBOX_PAGES', 'product_info.php', 'The pages to add the Colorbox Scripts to.', '6', '0', 'ht_product_colorbox_show_pages', 'ht_product_colorbox_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Thumbnail Layout', 'MODULE_HEADER_TAGS_PRODUCT_COLORBOX_LAYOUT', '155', 'Layout of Thumbnails', '6', '0', 'ht_product_colorbox_thumbnail_number', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_COLORBOX_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Breadcrumb Schema Module', 'MODULE_HEADER_TAGS_BREADCRUMB_SCHEMA_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_BREADCRUMB_SCHEMA_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Administration Tool Dashboard
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ADMIN_DASHBOARD_INSTALLED', 'd_total_revenue.php;d_total_customers.php;d_orders.php;d_customers.php;d_admin_logins.php;d_security_checks.php;d_latest_news.php;d_latest_addons.php;d_partner_news.php;d_version_check.php;d_reviews.php', 'List of Administration Tool Dashboard module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Administrator Logins Module', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_STATUS', 'True', 'Do you want to show the latest administrator logins on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customers Module', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS', 'True', 'Do you want to show the newest customers on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Latest Add-Ons Module', 'MODULE_ADMIN_DASHBOARD_LATEST_ADDONS_STATUS', 'True', 'Do you want to show the latest osCommerce Add-Ons on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_LATEST_ADDONS_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Latest News Module', 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_STATUS', 'True', 'Do you want to show the latest osCommerce News on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_LATEST_NEWS_SORT_ORDER', '700', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Orders Module', 'MODULE_ADMIN_DASHBOARD_ORDERS_STATUS', 'True', 'Do you want to show the latest orders on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_ORDERS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Security Checks Module', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_STATUS', 'True', 'Do you want to run the security checks for this installation?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Total Customers Module', 'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_STATUS', 'True', 'Do you want to show the total customers chart on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Total Revenue Module', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STATUS', 'True', 'Do you want to show the total revenue chart on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Version Check Module', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_STATUS', 'True', 'Do you want to show the version check results on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Latest Reviews Module', 'MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS', 'True', 'Do you want to show the latest reviews on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_REVIEWS_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Partner News Module', 'MODULE_ADMIN_DASHBOARD_PARTNER_NEWS_STATUS', 'True', 'Do you want to show the latest osCommerce Partner News on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_PARTNER_NEWS_SORT_ORDER', '820', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Boxes
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_BOXES_INSTALLED', 'bm_categories.php;bm_manufacturers.php;bm_whats_new.php;bm_card_acceptance.php;bm_order_history.php;bm_best_sellers.php', 'List of box module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Best Sellers Module', 'MODULE_BOXES_BEST_SELLERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BEST_SELLERS_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BEST_SELLERS_SORT_ORDER', '5030', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Categories Module', 'MODULE_BOXES_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CATEGORIES_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturers Module', 'MODULE_BOXES_MANUFACTURERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_MANUFACTURERS_SORT_ORDER', '1020', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Order History Module', 'MODULE_BOXES_ORDER_HISTORY_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_ORDER_HISTORY_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_ORDER_HISTORY_SORT_ORDER', '5020', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Card Acceptance Module', 'MODULE_BOXES_CARD_ACCEPTANCE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Logos', 'MODULE_BOXES_CARD_ACCEPTANCE_LOGOS', 'paypal_horizontal_large.png;visa.png;mastercard_transparent.png;american_express.png;maestro_transparent.png', 'The card acceptance logos to show.', '6', '0', 'bm_card_acceptance_show_logos', 'bm_card_acceptance_edit_logos(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CARD_ACCEPTANCE_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CARD_ACCEPTANCE_SORT_ORDER', '1060', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable What\'s New Module', 'MODULE_BOXES_WHATS_NEW_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_WHATS_NEW_SORT_ORDER', '1040', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Template Block Groups
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', 'boxes;header_tags', 'This is automatically updated. No need to edit.', '6', '0', now());

# Content Modules
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_CONTENT_INSTALLED', 'account/cm_account_title;account/cm_account_set_password;checkout_success/cm_cs_title;checkout_success/cm_cs_redirect_old_order;checkout_success/cm_cs_thank_you;checkout_success/cm_cs_product_notifications;checkout_success/cm_cs_downloads;checkout_success/cm_cs_continue_button;header/cm_header_logo;footer/cm_footer_information_links;footer_suffix/cm_footer_extra_copyright;footer_suffix/cm_footer_extra_icons;header/cm_header_search;header/cm_header_messagestack;header/cm_header_breadcrumb;index/cm_i_title;index/cm_i_customer_greeting;index/cm_i_text_main;index/cm_i_card_products;index/cm_i_upcoming_products;index_nested/cm_in_title;index_nested/cm_in_category_description;index_nested/cm_in_category_listing;index_nested/cm_in_new_products;index_products/cm_ip_title;index_products/cm_ip_category_manufacturer_description;index_products/cm_ip_product_listing;login/cm_login_title;login/cm_login_form;login/cm_create_account_link;navigation/cm_navbar;product_info/cm_pi_name;product_info/cm_pi_price;product_info/cm_pi_gallery;product_info/cm_pi_description;product_info/cm_pi_date_available;product_info/cm_pi_options_attributes;product_info/cm_pi_buy_button;product_info/cm_pi_reviews;product_info_not_found/cm_pinf_message;shopping_cart/cm_sc_title;shopping_cart/cm_sc_no_products;shopping_cart/cm_sc_product_listing;shopping_cart/cm_sc_order_subtotal;shopping_cart/cm_sc_stock_notice;shopping_cart/cm_sc_checkout', 'This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Set Account Password', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_STATUS', 'True', 'Do you want to enable the Set Account Password module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Local Passwords', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_ALLOW_PASSWORD', 'True', 'Allow local account passwords to be set.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Redirect Old Order Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_STATUS', 'True', 'Should customers be redirected when viewing old checkout success orders?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Redirect Minutes', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES', '60', 'Redirect customers to the My Account page after an order older than this amount is viewed.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Thank You Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_STATUS', 'True', 'Should the thank you block be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Notifications Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_STATUS', 'True', 'Should the product notifications block be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Downloads Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_STATUS', 'True', 'Should ordered product download links be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_SORT_ORDER', '3000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Login Form Module', 'MODULE_CONTENT_LOGIN_FORM_STATUS', 'True', 'Do you want to enable the login form module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_LOGIN_FORM_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New User Module', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_STATUS', 'True', 'Do you want to enable the new user module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Forgot Password Module', 'MODULE_CONTENT_FORGOT_PASSWORD_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FORGOT_PASSWORD_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FORGOT_PASSWORD_SORT_ORDER', '3000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Load three Navbar Modules, let the shopowner install the rest per his/her needs
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Hamburger Button Module', 'MODULE_NAVBAR_HAMBURGER_BUTTON_STATUS', 'True', 'Do you want to add the module to your Navbar?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_NAVBAR_HAMBURGER_BUTTON_CONTENT_PLACEMENT', 'Home', 'This module must be placed in the Home area of the Navbar.', '6', '1', 'tep_cfg_select_option(array(\'Home\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_HAMBURGER_BUTTON_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Brand Module', 'MODULE_NAVBAR_BRAND_STATUS', 'True', 'Do you want to add the module to your Navbar?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_NAVBAR_BRAND_CONTENT_PLACEMENT', 'Home', 'This module must be placed in the Home area of the Navbar.', '6', '1', 'tep_cfg_select_option(array(\'Home\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_BRAND_SORT_ORDER', '505', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Module', 'MODULE_NAVBAR_SHOPPING_CART_STATUS', 'True', 'Do you want to add the module to your Navbar?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_NAVBAR_SHOPPING_CART_CONTENT_PLACEMENT', 'Right', 'Should the module be loaded in the Left or Right or the Home area of the Navbar?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Right\', \'Home\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_SHOPPING_CART_SORT_ORDER', '550', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Navbar
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Navbar Module', 'MODULE_CONTENT_NAVBAR_STATUS', 'True', 'Should the Navbar be shown? ', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Background Colour Scheme', 'MODULE_CONTENT_NAVBAR_STYLE_BG', 'bg-light', 'What background colour should the Navbar have?  See https://getbootstrap.com/docs/4.0/utilities/colors/#background-color', '6', '0', 'tep_cfg_select_option(array(\'bg-primary\', \'bg-secondary\', \'bg-success\', \'bg-danger\', \'bg-warning\', \'bg-info\', \'bg-light\', \'bg-dark\', \'bg-white\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Link Colour Scheme', 'MODULE_CONTENT_NAVBAR_STYLE_FG', 'navbar-light', 'What foreground colour should the Navbar have?  See https://getbootstrap.com/docs/4.0/utilities/colors/#background-color', '6', '0', 'tep_cfg_select_option(array(\'navbar-dark\', \'navbar-light\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Placement', 'MODULE_CONTENT_NAVBAR_FIXED', 'floating', 'Should the Navbar be Placed or Floating? See https://getbootstrap.com/docs/4.0/components/navbar/#placement', '6', '0', 'tep_cfg_select_option(array(\'fixed-top\', \'fixed-bottom\', \'sticky-top\', \'floating\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Placement Offset', 'MODULE_CONTENT_NAVBAR_OFFSET', '4rem', 'Offset if using fixed-* Placement.', '6', '0', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Collapse', 'MODULE_CONTENT_NAVBAR_COLLAPSE', 'navbar-expand-sm', 'When should the Navbar Show? See https://getbootstrap.com/docs/4.0/components/navbar/#how-it-works', '6', '0', 'tep_cfg_select_option(array(\'navbar-expand\', \'navbar-expand-sm\', \'navbar-expand-md\', \'navbar-expand-lg\', \'navbar-expand-xl\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_NAVBAR_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Logo
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Header Logo Module', 'MODULE_CONTENT_HEADER_LOGO_STATUS', 'True', 'Do you want to enable the Logo content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_HEADER_LOGO_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_LOGO_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Search
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Search Box Module', 'MODULE_CONTENT_HEADER_SEARCH_STATUS', 'True', 'Do you want to enable the Search Box content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_HEADER_SEARCH_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_SEARCH_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Stack
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Message Stack Notifications Module', 'MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS', 'True', 'Should the Message Stack Notifications be shown in the header when needed? ', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_MESSAGESTACK_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Breadcrumb
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Header Breadcrumb Module', 'MODULE_CONTENT_HEADER_BREADCRUMB_STATUS', 'True', 'Do you want to enable the Breadcrumb content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_HEADER_BREADCRUMB_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_BREADCRUMB_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Footer Links
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Information Links Footer Module', 'MODULE_CONTENT_FOOTER_INFORMATION_STATUS', 'True', 'Do you want to enable the Information Links content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FOOTER_INFORMATION_CONTENT_WIDTH', '3', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_INFORMATION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
 
# Footer Copyright
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Copyright Details Footer Module', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_STATUS', 'True', 'Do you want to enable the Copyright content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Footer Icons
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Payment Icons Footer Module', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_STATUS', 'True', 'Do you want to enable the Payment Icons content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# ModularAccount
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_ACCOUNT_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_ACCOUNT_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ACCOUNT_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#ModularCheckoutSuccess
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Continue Button', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_SORT_ORDER', '5000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# ModularIndex
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_I_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_I_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_I_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Greeting Module', 'MODULE_CONTENT_CUSTOMER_GREETING_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CUSTOMER_GREETING_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CUSTOMER_GREETING_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '4', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Main Text Module', 'MODULE_CONTENT_TEXT_MAIN_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_TEXT_MAIN_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_TEXT_MAIN_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '4', now());
      
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New Products Module', 'MODULE_CONTENT_CARD_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CARD_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Display', 'MODULE_CONTENT_CARD_PRODUCTS_MAX_DISPLAY', '4', 'Maximum Number of products that should show in this module?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CARD_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Upcoming Products Module', 'MODULE_CONTENT_UPCOMING_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_UPCOMING_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Display', 'MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY', '6', 'Maximum Number of products that should show in this module?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_SORT', 'desc', 'This is the sort order used in the output.', '1', '4', 'tep_cfg_select_option(array(\'asc\', \'desc\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Sort Field', 'MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_FIELD', 'date_expected', 'The column to sort by in the output.', '1', '5', 'tep_cfg_select_option(array(\'products_name\', \'date_expected\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_UPCOMING_PRODUCTS_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#ModularIndex Nested
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_IN_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IN_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Category Description Module', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_STATUS', 'True', 'Should this module be enabled?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '3', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '2', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Category Listing Module', 'MODULE_CONTENT_IN_CATEGORY_LISTING_STATUS', 'True', 'Should this module be enabled?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IN_CATEGORY_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Category Card Layout', 'MODULE_CONTENT_IN_CATEGORY_LISTING_LAYOUT', 'card-deck', 'What Layout suits your shop?  See https://getbootstrap.com/docs/4.3/components/card/#card-layout <div class=\"secWarning\">card-columns is a special use case that will not suit most shops as card-columns is very difficult to layout and sort by...</div>', '6', '3', 'tep_cfg_select_option(array(\'card-group\', \'card-deck\', \'card-columns\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Items In Each Row (SM)', 'MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW_SM', '2', 'How many products should display per Row in SM (Small) viewport?', '6', '4', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Items In Each Row (MD)', 'MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW_MD', '3', 'How many products should display per Row in MD (Medium) viewport?', '6', '4', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Items In Each Row (LG)', 'MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW_LG', '4', 'How many products should display per Row in LG (Large) viewport?', '6', '4', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Items In Each Row (XL)', 'MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW_XL', '6', 'How many products should display per Row in XL (Extra Large) viewport?', '6', '4', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CATEGORY_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '4', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New Products Module', 'MODULE_CONTENT_IN_CARD_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IN_CARD_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Display', 'MODULE_CONTENT_IN_CARD_PRODUCTS_MAX_DISPLAY', '6', 'Maximum Number of products that should show in this module?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CARD_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#ModularIndex Products

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_IP_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IP_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Category/Manufacturer Description Module', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_STATUS', 'True', 'Should this module be enabled?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '3', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '2', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Listing Module', 'MODULE_CONTENT_IP_PRODUCT_LISTING_STATUS', 'True', 'Should this module be enabled?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_IP_PRODUCT_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_PRODUCT_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '4', now());

#Modular Login
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Title Module', 'MODULE_CONTENT_LOGIN_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_LOGIN_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_LOGIN_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#Modular Product Page

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Message Module', 'MODULE_CONTENT_PINF_MESSAGE_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PINF_MESSAGE_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PINF_MESSAGE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Options & Attributes', 'MODULE_CONTENT_PI_OA_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_OA_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Add Helper Text', 'MODULE_CONTENT_PI_OA_HELPER', 'True', 'Should first option in dropdown be Helper Text?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enforce Selection', 'MODULE_CONTENT_PI_OA_ENFORCE', 'True', 'Should customer be forced to select option(s)?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_OA_SORT_ORDER', '80', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Name Module', 'MODULE_CONTENT_PI_NAME_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_NAME_CONTENT_WIDTH', '9', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_NAME_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Price Module', 'MODULE_CONTENT_PI_PRICE_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_PRICE_CONTENT_WIDTH', '3', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_PRICE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Description Module', 'MODULE_CONTENT_PI_DESCRIPTION_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_DESCRIPTION_CONTENT_WIDTH', '8', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_DESCRIPTION_SORT_ORDER', '60', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Gallery Module', 'MODULE_CONTENT_PI_GALLERY_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_GALLERY_CONTENT_WIDTH', '4', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_GALLERY_SORT_ORDER', '65', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Date Available Module', 'MODULE_CONTENT_PI_DATE_AVAILABLE_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_DATE_AVAILABLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Date Style', 'MODULE_CONTENT_PI_DATE_AVAILABLE_STYLE', 'Long', 'How should the date look?', '6', '1', 'tep_cfg_select_option(array(\'Long\', \'Short\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_DATE_AVAILABLE_SORT_ORDER', '70', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Buy Button', 'MODULE_CONTENT_PI_BUY_STATUS', 'True', 'Should this module be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_BUY_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_BUY_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

#Product Reviews
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Reviews Module', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_STATUS', 'True', 'Should the reviews block be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH_EACH', '6', 'What width container should each Review be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Reviews', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_LIMIT', '99', 'How many reviews should be shown?', '6', '1', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_ORDER', 'reviews_rating', 'Display Reviews by Rating (High to Low) or Date Added (New to Old)', '6', '1', 'tep_cfg_select_option(array(\'reviews_rating\', \'date_added\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_SORT_ORDER', '120', 'Sort order of display. Lowest is displayed first.', '6', '0', now());     

#Modular Cart Page
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Title Module', 'MODULE_CONTENT_SC_TITLE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());	
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart No Products Message', 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER', '110', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Product Listing', 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER', '120', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Order SubTotal', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());   
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_SORT_ORDER', '130', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Stock Notice', 'MODULE_CONTENT_SC_STOCK_NOTICE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_STOCK_NOTICE_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_STOCK_NOTICE_SORT_ORDER', '140', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Checkout Button', 'MODULE_CONTENT_SC_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER', '150', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
