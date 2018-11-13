<?php
/**
 * S2WPML_List_Table
 *
 * @author		Nir Goldberg
 * @package		includes/classes
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'S2WPML_List_Table' ) && class_exists( 'S2_List_Table' ) ) :

class S2WPML_List_Table extends S2_List_Table {

	/**
	* column_default
	*
	* @since		1.0.0
	*/
	function column_default( $item, $column_name ) {

		// globals
		global $current_tab;

		if ( 'registered' === $current_tab ) {
			switch ( $column_name ) {
				case 'email':
					return $item[ $column_name ];
			}
		} else {
			switch ( $column_name ) {
				case 'email':
				case 's2wpml_lang':
				case 's2wpml_cat':
				case 'date':
					return $item[ $column_name ];
			}
		}

	}

	/**
	* get_columns
	*
	* @since		1.0.0
	*/
	function get_columns() {

		// globals
		global $current_tab;

		if ( 'registered' === $current_tab ) {
			if ( is_multisite() ) {
				$columns = array(
					'email' => _x( 'Email', 'column name', 'subscribe2' ),
				);
			} else {
				$columns = array(
					'cb'		=> '<input type="checkbox" />',
					'email' => _x( 'Email', 'column name', 'subscribe2' ),
				);
			}
		} else {
			$columns = array(
				'cb'			=> '<input type="checkbox" />',
				'email'			=> _x( 'Email', 'column name', 'subscribe2' ),
				's2wpml_lang'	=> _x( 'Languages', 'column name', 's2wpml' ),
				's2wpml_cat'	=> _x( 'Categories', 'column name', 's2wpml' ),
				'date'			=> _x( 'Date', 'column name', 'subscribe2' ),
			);
		}

		// return
		return $columns;

	}

	/**
	* get_sortable_columns
	*
	* @since		1.0.0
	*/
	function get_sortable_columns() {

		// globals
		global $current_tab;

		if ( 'registered' === $current_tab ) {
			$sortable_columns = array(
				'email' => array( 'email', true ),
			);
		} else {
			$sortable_columns = array(
				'email'			=> array( 'email', true ),
				's2wpml_lang'	=> array( 's2wpml_lang', false ),
				's2wpml_cat'	=> array( 's2wpml_cat', false ),
				'date'			=> array( 'date', false ),
			);
		}

		// return
		return $sortable_columns;

	}

	/**
	* prepare_items
	*
	* @since		1.0.0
	*/
	function prepare_items() {

		// globals
		global $mysubscribe2, $subscribers, $current_tab;

		if ( is_int( $mysubscribe2->subscribe2_options['entries'] ) ) {
			$per_page = $mysubscribe2->subscribe2_options['entries'];
		} else {
			$per_page = 25;
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$data = array();
		if ( 'public' === $current_tab ) {
			foreach ( (array) $subscribers as $email ) {
				$data[] = array(
					'email'			=> $email,
					's2wpml_lang'	=> $mysubscribe2->pretty_signup_lang( $email ),
					's2wpml_cat'	=> $mysubscribe2->pretty_signup_cat( $email ),
					'date'			=> $mysubscribe2->signup_date( $email ),
				);
			}
		} else {
			foreach ( (array) $subscribers as $subscriber ) {
				$data[] = array(
					'email'	=> $subscriber['user_email'],
					'id'	=> $subscriber['ID'],
				);
			}
		}

		function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'email';
			$order = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';
			$result = strcasecmp( $a[ $orderby ], $b[ $orderby ] );
			return ( 'asc' === $order ) ? $result : -$result;
		}
		usort( $data, 'usort_reorder' );

		if ( isset( $_POST['what'] ) ) {
			$current_page = 1;
		} else {
			$current_page = $this->get_pagenum();
		}

		$total_items = count( $data );
		$data = array_slice( $data,( ($current_page -1 ) * $per_page ), $per_page );
		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items'	=> $total_items,
			'per_page'		=> $per_page,
			'total_pages'	=> ceil( $total_items / $per_page ),
		) );

	}

}

endif; // class_exists check