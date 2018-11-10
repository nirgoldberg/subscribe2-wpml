<?php
/**
 * S2WPML_Core
 *
 * @author		Nir Goldberg
 * @package		includes
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'S2WPML_Core' ) ) :

class S2WPML_Core {

	/**
	* __construct
	*
	* A dummy constructor to ensure is only initialized once
	*
	* @since		1.0.0
	* @param		N/A
	* @return		N/A
	*/
	function __construct() {

		/* Do nothing here */

	}

	/**
	* alter_table_add_lang
	*
	* alter table subscribe2 - add lang column
	*
	* @since		1.0.0
	* @param		N/A
	* @return		N/A
	*/
	function alter_table_add_lang() {

		// globals
		global $wpdb;

		// vars
		$table = $wpdb->prefix . s2wpml_get_setting( 'table_name' );

		$row = $wpdb->get_results(
			"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
			 WHERE TABLE_NAME = '$table' AND COLUMN_NAME = 'lang'"
		);

		if( empty( $row ) ) {

			$wpdb->query( "ALTER TABLE $table ADD COLUMN lang VARCHAR(64) NOT NULL DEFAULT '' AFTER email" );

		}

	}

}

/**
* s2wpml_core
*
* The main function responsible for returning the one true instance
*
* @since		1.0.0
* @param		N/A
* @return		(object)
*/
function s2wpml_core() {

	// globals
	global $s2wpml_core;

	// initialize
	if( ! isset( $s2wpml_core ) ) {

		$s2wpml_core = new S2WPML_Core();

	}

	// return
	return $s2wpml_core;

}

// initialize
s2wpml_core();

endif; // class_exists check