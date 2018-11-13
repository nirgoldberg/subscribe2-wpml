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
	* extend_subscribe2_table
	*
	* This function will extend the subscribe2 table:
	* alter table - add s2wpml_lang column
	* alter table - add s2wpml_status column
	*
	* @since		1.0.0
	* @param		N/A
	* @return		N/A
	*/
	function extend_subscribe2_table() {

		// globals
		global $wpdb;

		// vars
		$table = $wpdb->prefix . s2wpml_get_setting( 'table_name' );

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

		if( empty( $lang_col ) ) {
			$wpdb->query( "ALTER TABLE $table ADD COLUMN s2wpml_lang VARCHAR(64) NOT NULL DEFAULT ''" );
		}

		if( empty( $cat_col ) ) {
			$wpdb->query( "ALTER TABLE $table ADD COLUMN s2wpml_cat VARCHAR(64) NOT NULL DEFAULT ''" );
		}

		if( empty( $status_col ) ) {
			$wpdb->query( "ALTER TABLE $table ADD COLUMN s2wpml_status TINYINT(1) NOT NULL DEFAULT 0" );
		}

	}

	/**
	* assign_default_lang
	*
	* This function will assign the default language(s) for already registered subscribers.
	* This function will be called right after updating the default language option
	*
	* @since		1.0.0
	* @param		N/A
	* @return		N/A
	*/
	function assign_default_lang() {

		// globals
		global $wpdb;

		// vars
		$table			= $wpdb->prefix . s2wpml_get_setting( 'table_name' );
		$default_lang	= get_option( 's2wpml_general_default_lang' );
		$default_lang	= implode( ',', (array) $default_lang );

		$query = "UPDATE $table SET s2wpml_lang='$default_lang' WHERE s2wpml_status=0";
		$wpdb->query( $query );

	}

	/**
	* assign_default_cat
	*
	* This function will assign the default categories for registered subscribers.
	* This function will be called right after updating the default cat option
	*
	* @since		1.0.0
	* @param		N/A
	* @return		N/A
	*/
	function assign_default_cat() {

		// globals
		global $wpdb;

		// vars
		$table			= $wpdb->prefix . s2wpml_get_setting( 'table_name' );
		$langs			= s2wpml_get_active_languages();
		$default_cat	= array();

		if ( $langs ) {
			foreach ( $langs as $key => $lang ) {

				$cats = get_option( 's2wpml_general_default_cat_' . $key );

				if ( $cats ) {

					foreach ( $cats as $key => $cat ) {
						$cats[ $key ] = str_replace( 'cat_', '', $cat );
					}

					$default_cat = array_merge( $default_cat, $cats );

				}

			}
		} else {
			$default_cat = get_option( 's2wpml_general_default_cat' );
		}

		$default_cat = implode( ',', (array) $default_cat );

		$query = "UPDATE $table SET s2wpml_cat='$default_cat'";
		$wpdb->query( $query );

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