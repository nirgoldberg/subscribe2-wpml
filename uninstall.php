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

	// vars
	$options = array(
		's2wpml_uninstall_remove_data'
	);

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

	$row = $wpdb->get_results(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_NAME = '$table' AND COLUMN_NAME = 'lang'"
	);

	if( ! empty( $row ) ) {

		$wpdb->query( "ALTER TABLE $table DROP COLUMN lang" );

	}

}