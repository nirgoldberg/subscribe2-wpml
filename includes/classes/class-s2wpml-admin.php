<?php
/**
 * S2WPML_Admin
 *
 * @author		Nir Goldberg
 * @package		includes/classes
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'S2WPML_Admin' ) && class_exists( 'S2_Admin' ) ) :

class S2WPML_Admin extends S2_Admin {

	/**
	 * vars
	 *
	 * @var $signup_langs (array) cache array of subscribers language(s) in a language code format
	 * @var $langs (array) cache array of languages in a native name format
	 * @var $signup_cats (array) cache array of subscribers categories in a category ID format
	 * @var $cats (array) cache array of categories in a category name format
	 */
	var $signup_langs	= array();
	var $langs			= array();
	var $signup_cats	= array();
	var $cats			= array();

	/**
	* subscribers_menu
	*
	* @since		1.0.0
	*/
	function subscribers_menu() {

		require_once( S2WPML_PATH . 'includes/admin/views/subscribers.php' );

	}

	/**
	* signup_lang
	*
	* This function will return a particular subscriber's language(s)
	*
	* @since		1.0.0
	* @param		$email (string)
	* @return		(string)
	*/
	function signup_lang( $email = '' ) {

		if ( '' === $email )
			return false;

		// globals
		global $wpdb;

		if ( empty( $this->signup_langs ) ) {

			$results = $wpdb->get_results( "SELECT email, s2wpml_lang FROM $this->public", ARRAY_N );

			if ( $results ) {
				foreach ( $results as $result ) {
					$this->signup_langs[ $result[0] ] = $result[1];
				}
			}

		}

		// return
		return $this->signup_langs[ $email ];

	}

	/**
	* pretty_signup_lang
	*
	* This function will return a particular subscriber's language(s) using native language name instead of language code
	*
	* @since		1.0.0
	* @param		$email (string)
	* @return		(string)
	*/
	function pretty_signup_lang( $email = '' ) {

		if ( '' === $email )
			return false;

		// globals
		global $sitepress;

		// vars
		$lang = $this->signup_lang( $email );

		if ( ! $lang )
			// return
			return '';

		$lang_arr = explode( ',', $lang );

		foreach ( $lang_arr as $key => $l ) {

			if ( ! array_key_exists( $l, $this->langs ) ) {
				if ( ! isset( $sitepress ) ) {
					$this->langs[ $l ] = $l;
				} else {

					$details = $sitepress->get_language_details( $l );
					$this->langs[ $l ] = $details[ 'native_name' ];

				}
			}

			$lang_arr[ $key ] = $this->langs[ $l ];

		}

		// return
		return implode( ', ', $lang_arr );

	}

	/**
	* signup_cat
	*
	* This function will return a particular subscriber's categories
	*
	* @since		1.0.0
	* @param		$email (string)
	* @return		(string)
	*/
	function signup_cat( $email = '' ) {

		if ( '' === $email )
			return false;

		// globals
		global $wpdb;

		if ( empty( $this->signup_cats ) ) {

			$results = $wpdb->get_results( "SELECT email, s2wpml_cat FROM $this->public", ARRAY_N );

			if ( $results ) {
				foreach ( $results as $result ) {
					$this->signup_cats[ $result[0] ] = $result[1];
				}
			}

		}

		// return
		return $this->signup_cats[ $email ];

	}

	/**
	* pretty_signup_cat
	*
	* This function will return a particular subscriber's categories using category name instead of category ID
	*
	* @since		1.0.0
	* @param		$email (string)
	* @return		(string)
	*/
	function pretty_signup_cat( $email = '' ) {

		if ( '' === $email )
			return false;

		// vars
		$cat = $this->signup_cat( $email );

		if ( ! $cat )
			// return
			return '';

		$cat_arr = explode( ',', $cat );

		foreach ( $cat_arr as $key => $c ) {

			if ( ! array_key_exists( $c, $this->cats ) ) {

				$cat_obj = get_category( $c );
				$this->cats[ $c ] = $cat_obj->name;

			}

			$cat_arr[ $key ] = $this->cats[ $c ];

		}

		// return
		return implode( ', ', $cat_arr );

	}

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

	$mysubscribe2 = new S2WPML_Admin();
	$mysubscribe2->s2init();

}