<?php

/*
Plugin Name: LDD Business Directory
Plugin URI: http://www.LDDWebDesign.com
Description: Creates a Business Directory for your site
Version: 1.1.3
Author: LDD Web Design
Author URI: http://www.LDDWebDesign.com
License: LDDBD
*/

/* -------------- Core -------------- */

add_action( 'admin_init', 'lddbd_admin_init' );

//Where the stylesheet for the plugin is registered and initialized.
function lddbd_admin_init() {
   /* Register our stylesheet. */
   wp_register_style( 'lddbd_stylesheet', plugins_url('style.css', __FILE__) );
}

global $lddbd_db_version;
$lddbd_db_version = "1.0";

global $wpdb;

//Generates table prefix for main table, documents table, and category table: [prefix]_TABLENAME
global $main_table_name, $doc_table_name, $cat_table_name;
$main_table_name = $wpdb->prefix . "lddbusinessdirectory";
$doc_table_name = $wpdb->prefix . "lddbusinessdirectory_docs";
$cat_table_name = $wpdb->prefix . "lddbusinessdirectory_cats";

				
// Installation function for LDD Business Directory plugin. Sets up the tables in the database for main, documents, and categories.
function lddbd_install() {
	global $wpdb;
	global $lddbd_db_version;
	global $main_table_name, $doc_table_name, $cat_table_name;
	
	//Creates the table that contains all the primary information regarding each business.
	$main_table = "CREATE TABLE $main_table_name (
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	expiresDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	name TINYTEXT NOT NULL,
	description TEXT NOT NULL,
	categories TEXT NOT NULL,
	address_street TEXT NOT NULL,
	address_city TEXT NOT NULL,
	address_state TEXT NOT NULL,
	address_zip CHAR(10) NOT NULL,
	phone CHAR(15) NOT NULL,
	fax CHAR(15),
	email VARCHAR(55) DEFAULT '' NOT NULL,
	contact tinytext NOT NULL,
	url VARCHAR(55) DEFAULT '' NOT NULL,
	facebook VARCHAR(256),
	twitter VARCHAR(256),
	linkedin VARCHAR(256),
	promo ENUM('true', 'false') NOT NULL,
	promoDescription text DEFAULT '',
	logo VARCHAR(256) DEFAULT '' NOT NULL,
	login text NOT NULL,
	password VARCHAR(64) NOT NULL,
	approved ENUM('true', 'false') NOT NULL,
	other_info TEXT,
	UNIQUE KEY id (id)
	);";
	
	//Creates the table that contains documentation/descriptions.
	$doc_table = "CREATE TABLE $doc_table_name (
	doc_id BIGINT(20) NOT NULL AUTO_INCREMENT,
	bus_id BIGINT(20) NOT NULL,
	doc_path VARCHAR(256) NOT NULL,
	doc_name TINYTEXT NOT NULL,
	doc_description LONGTEXT,
	PRIMARY KEY  (doc_id),
	FOREIGN KEY (bus_id) REFERENCES $main_table_name(id)
	);";
	
	//Creates the table that contains a listing of all the categories.
	$cat_table = "CREATE TABLE $cat_table_name(
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	name TINYTEXT NOT NULL,
	count BIGINT(20) NOT NULL,
	PRIMARY KEY  (id)
	);";

	//Loads the file necessary for the function dbDelta()to work.
	//dbDelta(): examines the current table structure, compares it to the desired table structure, and either adds or modifies the table as
	//necessary.
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($main_table);
   dbDelta($doc_table);
   dbDelta($cat_table);
 
   add_option("lddbd_db_version", $lddbd_db_version);
}

function lddbd_install_data() {
   global $wpdb;
   global $main_table_name, $doc_table_name, $cat_table_name;
   $welcome_name = "Test Business";
   $welcome_text = "We sell widgets!";
   $welcome_login = "test_business";
   $welcome_password = "1234";
   

   //$rows_affected = $wpdb->insert( $main_table_name, array( 'createDate' => current_time('mysql'), 'name' => $welcome_name, 'description' => $welcome_text, 'login'=>$welcome_login, 'password'=>$welcome_password ) );
}

register_activation_hook(__FILE__,'lddbd_install');
register_activation_hook(__FILE__,'lddbd_install_data');

include('lddbd_settings.php');

?>