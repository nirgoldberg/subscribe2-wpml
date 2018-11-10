<?php
/**
 * Helper functions
 *
 * @author		Nir Goldberg
 * @package		includes/api
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * s2wpml_has_setting
 *
 * Alias of s2wpml()->has_setting()
 *
 * @since		1.0.0
 * @param		$name (string)
 * @return		(boolean)
 */
function s2wpml_has_setting( $name = '' ) {

	// return
	return s2wpml()->has_setting( $name );

}

/**
 * s2wpml_get_setting
 *
 * This function will return a value from the settings array found in the s2wpml object
 *
 * @since		1.0.0
 * @param		$name (string)
 * @return		(mixed)
 */
function s2wpml_get_setting( $name, $default = null ) {

	// vars
	$settings = s2wpml()->settings;

	// find setting
	$setting = s2wpml_maybe_get( $settings, $name, $default );

	// filter for 3rd party
	$setting = apply_filters( "s2wpml/settings/{$name}", $setting );

	// return
	return $setting;

}

/**
 * s2wpml_update_setting
 *
 * Alias of s2wpml()->update_setting()
 *
 * @since		1.0.0
 * @param		$name (string)
 * @param		$value (mixed)
 * @return		N/A
 */
function s2wpml_update_setting( $name, $value ) {

	// return
	return s2wpml()->update_setting( $name, $value );

}

/**
 * s2wpml_get_path
 *
 * This function will return the path to a file within the plugin folder
 *
 * @since		1.0.0
 * @param		$path (string) the relative path from the root of the plugin folder
 * @return		(string)
 */
function s2wpml_get_path( $path = '' ) {

	// return
	return S2WPML_PATH . $path;

}

/**
 * s2wpml_get_url
 *
 * This function will return the url to a file within the plugin folder
 *
 * @since		1.0.0
 * @param		$path (string) the relative path from the root of the plugin folder
 * @return		(string)
 */
function s2wpml_get_url( $path = '' ) {

	// define S2WPML_URL to optimize performance
	s2wpml()->define( 'S2WPML_URL', s2wpml_get_setting( 'url' ) );

	// return
	return S2WPML_URL . $path;

}

/**
 * s2wpml_include
 *
 * This function will include a file
 *
 * @since		1.0.0
 * @param		$file (string) the file name to be included
 * @return		N/A
 */
function s2wpml_include( $file ) {

	$path = s2wpml_get_path( $file );

	if ( file_exists( $path ) ) {

		include_once( $path );

	}

}

/**
 * s2wpml_get_view
 *
 * This function will load in a file from the 'includes/admin/views' folder and allow variables to be passed through
 *
 * @since		1.0.0
 * @param		$view_name (string)
 * @param		$args (array)
 * @return		N/A
 */
function s2wpml_get_view( $view_name = '', $args = array() ) {

	// vars
	$path = s2wpml_get_path( "includes/admin/views/{$view_name}.php" );

	if( file_exists( $path ) ) {

		include( $path );

	}

}

/**
 * s2wpml_maybe_get
 *
 * This function will return a variable if it exists in an array
 *
 * @since		1.0.0
 * @param		$array (array) the array to look within
 * @param		$key (key) the array key to look for
 * @param		$default (mixed) the value returned if not found
 * @return		(mixed)
 */
function s2wpml_maybe_get( $array = array(), $key = 0, $default = null ) {

	// return
	return isset( $array[ $key ] ) ? $array[ $key ] : $default;

}

/**
 * s2wpml_get_locale
 *
 * This function is a wrapper for the get_locale() function
 *
 * @since		1.0.0
 * @param		N/A
 * @return		(string)
 */
function s2wpml_get_locale() {

	// return
	return is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();

}

/**
 * s2wpml_parse_markdown
 *
 * A very basic regex-based Markdown parser function based off [slimdown](https://gist.github.com/jbroadway/2836900)
 *
 * @since		1.0.0
 * @param		$text (string)
 * @return		(string)
 */
function s2wpml_parse_markdown( $text = '' ) {

	// trim
	$text = trim($text);

	// rules
	$rules = array (
		'/=== (.+?) ===/'				=> '<h2>$1</h2>',					// headings
		'/== (.+?) ==/'					=> '<h3>$1</h3>',					// headings
		'/= (.+?) =/'					=> '<h4>$1</h4>',					// headings
		'/\[([^\[]+)\]\(([^\)]+)\)/' 	=> '<a href="$2">$1</a>',			// links
		'/(\*\*)(.*?)\1/' 				=> '<strong>$2</strong>',			// bold
		'/(\*)(.*?)\1/' 				=> '<em>$2</em>',					// intalic
		'/`(.*?)`/'						=> '<code>$1</code>',				// inline code
		'/\n\*(.*)/'					=> "\n<ul>\n\t<li>$1</li>\n</ul>",	// ul lists
		'/\n[0-9]+\.(.*)/'				=> "\n<ol>\n\t<li>$1</li>\n</ol>",	// ol lists
		'/<\/ul>\s?<ul>/'				=> '',								// fix extra ul
		'/<\/ol>\s?<ol>/'				=> '',								// fix extra ol
	);

	foreach( $rules as $k => $v ) {
		$text = preg_replace($k, $v, $text);
	}

	// autop
	$text = wpautop($text);

	// return
	return $text;

}