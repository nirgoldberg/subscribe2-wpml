<?php
/**
 * Admin mail submission functions
 *
 * @author		Nir Goldberg
 * @package		includes/api
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * s2wpml_send_public_subscribers
 *
 * This function filters the public susbcriber list to be notified with a new post published.
 * The function checks each public subscriber against his registered language(s) and categories
 * in order to find a match to the given post ID
 *
 * @since		1.0.0
 * @param		$public (array)
 * @param		$post_id (int)
 * @return		(array)
 */
function s2wpml_send_public_subscribers( $public, $post_id ) {

	// globals
	global $wpdb;

	// vars
	$table = $wpdb->prefix . s2wpml_get_setting( 'table_name' );

	// get post language
	$post_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id );

	if ( ! $post_language_details )
		return $public;

	$lang = $post_language_details[ 'language_code' ];

	if ( ! $lang )
		return $public;

	// get post categories
	$categories = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );

	// get all (confirmed) public subscribers info
	$results = $wpdb->get_results( "SELECT email, s2wpml_lang, s2wpml_cat FROM $table WHERE active='1'", ARRAY_N );

	if ( ! $results )
		return $public;

	foreach ( $results as $result ) {

		// check subscriber registered language
		$subscriber_lang = explode( ',', $result[1] );

		if ( ! $subscriber_lang || ! in_array( $lang, $subscriber_lang ) ) {

			// unset subscriber and continue
			if ( ( $key = array_search( $result[0], $public ) ) !== false ) {
				unset( $public[ $key ] );
			}

			continue;

		}

		// check subscriber registered
		$susbcriber_cat = explode( ',', $result[2] );

		if ( empty( $categories ) && ! empty( $susbcriber_cat ) ||
			 ! empty( $categories ) && empty( $susbcriber_cat ) ||
			 ! empty( $categories ) && ! empty( $susbcriber_cat) && ! count( array_intersect( $categories, $susbcriber_cat ) ) ) {

			// unset subscriber
			if ( ( $key = array_search( $result[0], $public ) ) !== false ) {
				unset( $public[ $key ] );
			}

		}

	}

	// return
	return $public;

}
add_filter( 's2_send_public_subscribers', 's2wpml_send_public_subscribers', 10, 2 );