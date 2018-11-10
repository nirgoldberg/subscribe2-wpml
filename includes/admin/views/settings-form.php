<?php
/**
 * Admin settings form HTML content
 *
 * @author		Nir Goldberg
 * @package		includes/admin/views
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// extract args
extract( $args );

?>

<div class="s2wpml-admin-box">

	<?php if ( $section[ 'title' ] || $section[ 'description' ] ) : ?>

		<div class="title">

			<?php
				echo $section[ 'title' ]		? '<h3>' . $section[ 'title' ] . '</h3>'					: '';
				echo $section[ 'description' ]	? '<p class="desc">' . $section[ 'description' ] . '</p>'	: '';
			?>

		</div>

	<?php endif; ?>

	<div class="content">
		<table class="form-table">

			<?php do_settings_fields( $options_group_id, $section_id ); ?>

		</table>
	</div>

</div><!-- .s2wpml-admin-box -->