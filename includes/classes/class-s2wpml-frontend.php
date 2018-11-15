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

		s2wpml_core()->add( $email, $confirm );

	}

}

endif; // class_exists check

// globals
global $mysubscribe2;

if ( is_null( $mysubscribe2 ) ) {

	$mysubscribe2 = new S2WPML_Frontend();
	$mysubscribe2->s2init();

}