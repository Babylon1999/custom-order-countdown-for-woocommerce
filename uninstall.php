<?php
/** When uninstalling the plugin, delete options. */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'cocfw_render' );
delete_option( 'cocfw_store_close_time' );

$days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
foreach ( $days as $day ) {
	$option_name_before_14 = 'cocfw_' . strtolower( $day ) . '_before_14';
	$option_name_after_14  = 'cocfw_' . strtolower( $day ) . '_after_14';

		delete_option( $option_name_before_14 );
		delete_option( $option_name_after_14 );
}
