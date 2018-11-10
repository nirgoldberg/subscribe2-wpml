<?php
/**
 * Admin settings filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		includes/admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 's2wpml_admin_settings' ) ) :

class s2wpml_admin_settings extends s2wpml_admin_settings_page {

	/**
	 * initialize
	 *
	 * This function will setup the settings page data
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function initialize() {

		// settings
		$this->settings = array(

			// slugs
			'parent'				=> 's2',
			'slug'					=> 's2wpml_settings',

			// titles
			'page_title'			=> __( 'Subscribe2 WPML Settings', 's2wpml' ),
			'menu_title'			=> __( 'S2 WPML Settings', 's2wpml' ),

			// tabs
			'tabs'					=> array(
				'general'			=> array(
					'title'				=> 'General',
					'sections'			=> array(
						'general'		=> array(
							'title'			=> 'General Settings',
							'description'	=> ''
						)
					)
				),
				'uninstall'			=> array(
					'title'				=> 'Uninstall',
					'sections'			=> array(
						'uninstall'		=> array(
							'title'			=> 'Uninstall Settings',
							'description'	=> ''
						)
					)
				)
			),
			'active_tab'			=> 'general',

			// fields
			'fields'				=> array(
				array(
					'uid'			=> 's2wpml_uninstall_remove_data',
					'label'			=> 'Remove Data on Uninstall',
					'label_for'		=> 's2wpml_uninstall_remove_data',
					'tab'			=> 'uninstall',
					'section'		=> 'uninstall',
					'type'			=> 'checkbox',
					'options'		=> array(
						'remove'	=> ''
					),
					'helper'		=> __( 'Caution: all data will be removed without any option to restore', 's2wpml' )
				)
			)

		);

	}

}

// initialize
new s2wpml_admin_settings();

endif; // class_exists check