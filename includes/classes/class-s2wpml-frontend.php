<?php
/**
 * S2WPML_Frontend
 *
 * @author		Nir Goldberg
 * @package		includes/classes
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'S2WPML_Frontend' ) && class_exists( 'S2_Frontend' ) ) :

class S2WPML_Frontend extends S2_Frontend {

	/**
	* add
	*
	* This function adds a public subscriber to the subscribers table
	*
	* @since		1.0.0
	* @param		$email (string)
	* @param		$confirm (boolean)
	* @return		N/A
	*/
	function add( $email = '', $confirm = false ) {

		if ( 1 === $this->filtered )
			return;

		if ( ! is_email( $email ) )
			return false;

		// globals
		global $wpdb;

		// vars
		$current_lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '';

		if ( false !== $this->is_public( $email ) ) {

			// is this an email for a registered user
			$check = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE user_email=%s", $this->email ) );

			if ( $check )
				return;

			if ( $confirm ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $this->public SET active='1', ip=%s, s2wpml_lang=%s WHERE CAST(email as binary)=%s", $this->ip, $current_lang, $email ) );
			} else {
				$wpdb->query( $wpdb->prepare( "UPDATE $this->public SET date=CURDATE(), time=CURTIME(), s2wpml_lang=%s WHERE CAST(email as binary)=%s", $current_lang, $email ) );
			}

		} else {

			$default_cat = s2wpml_core()->assign_default_cat( false );

			if ( $confirm ) {

				global $current_user;
				$wpdb->query( $wpdb->prepare( "INSERT INTO $this->public (email, active, date, time, ip, s2wpml_lang, s2wpml_cat, s2wpml_status) VALUES (%s, %d, CURDATE(), CURTIME(), %s, %s, %s, 1)", $email, 1, $current_user->user_login, $current_lang, $default_cat ) );

			} else {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $this->public (email, active, date, time, ip, s2wpml_lang, s2wpml_cat, s2wpml_status) VALUES (%s, %d, CURDATE(), CURTIME(), %s, %s, %s, 1)", $email, 0, $this->ip, $current_lang, $default_cat ) );
			}

		}

	}

}

endif; // class_exists check

// globals
global $mysubscribe2;

if ( is_null( $mysubscribe2 ) ) {

	$mysubscribe2 = new S2WPML_Frontend();
	$mysubscribe2->s2init();

}