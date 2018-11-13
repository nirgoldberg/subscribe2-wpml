<?php
/**
 * Subscribe2 WPML Uninstall
 *
 * @author		Nir Goldberg
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit; // Exit if accessed directly

// vars
$remove_data = get_option( 's2wpml_uninstall_remove_data' );

if ( $remove_data && in_array( 'remove', $remove_data ) ) {
	// remove plugin data
	s2wpml_remove_data();
}

/**
 * s2wpml_remove_data
 *
 * This function will delete options and database plugin data
 *
 * @since		1.0.0
 * @param		N/A
 * @return		N/A
 */
function s2wpml_remove_data() {

	// delete plugin options
	s2wpml_remove_options_data();

	// delete database plugin data
	s2wpml_remove_db_data();

}

/**
* s2wpml_remove_options_data
*
* This function will delete plugin options
*
* @since		1.0.0
* @param		N/A
* @return		N/A
*/
function s2wpml_remove_options_data() {

	// globals
	global $wpdb;

	// vars
	$options_table	= $wpdb->prefix . 'options';
	$options		= array(
		's2wpml_general_default_lang',
		's2wpml_uninstall_remove_data'
	);

	// append language based options
	$general_default_cat_options = $wpdb->get_results(
		"SELECT option_name FROM $options_table
		 WHERE option_name like 's2wpml_general_default_cat%'", ARRAY_N
	);

	foreach ( $general_default_cat_options as $option ) {
		$options[] = $option[0];
	}

	foreach ( $options as $option ) {
		delete_option( $option );
	}

}

/**
* s2wpml_remove_db_data
*
* This function will delete database plugin data
*
* @since		1.0.0
* @param		N/A
* @return		N/A
*/
function s2wpml_remove_db_data() {

	// globals
	global $wpdb;

	// vars
	$table = $wpdb->prefix . 'subscribe2';

	$lang_col = $wpdb->get_results(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_NAME = '$table' AND COLUMN_NAME = 's2wpml_lang'"
	);

	$cat_col = $wpdb->get_results(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_NAME = '$table' AND COLUMN_NAME = 's2wpml_cat'"
	);

	$status_col = $wpdb->get_results(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_NAME = '$table' AND COLUMN_NAME = 's2wpml_status'"
	);

	if( ! empty( $lang_col ) ) {
		$wpdb->query( "ALTER TABLE $table DROP COLUMN s2wpml_lang" );
	}

	if( ! empty( $cat_col ) ) {
		$wpdb->query( "ALTER TABLE $table DROP COLUMN s2wpml_cat" );
	}

	if( ! empty( $status_col ) ) {
		$wpdb->query( "ALTER TABLE $table DROP COLUMN s2wpml_status" );
	}

}