<?php
/**
 * Frontend form functions
 *
 * @author		Nir Goldberg
 * @package		includes/api
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * s2wpml_form
 *
 * This function filters the form HTML.
 * Manipulate the action URL
 *
 * @since		1.0.0
 * @param		$form (string)
 * @return		(string)
 */
function s2wpml_form ( $form ) {

	// get action URL
	preg_match( '/(action=")([^"]*)(.*)/', $form, $matches );
	$url = ( $matches && isset( $matches[2] ) ) ? $matches[2] : '';

	// get post ID
	$id = ( $url) ? url_to_postid( $url ) : '';

	// get translated post ID
	$id = ( $id ) ? apply_filters( 'wpml_object_id', $id, get_post_type( $id ), true ) : '';

	// get translated action URL
	$link = ( $id ) ? get_permalink( $id ) : '';

	// return
	return ( $link ) ? preg_replace( '/(action=")([^"]*)(.*)/', "$1{$link}$3", $form ) : $form;

}
add_filter( 's2_form', 's2wpml_form' );