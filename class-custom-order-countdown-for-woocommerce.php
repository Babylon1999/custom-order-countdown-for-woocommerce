<?php
/**
 * Plugin Name: Custom Order Countdown for WooCommerce
 * Description: This is a simple plugin that will add a countdown to the WooCommerce product page.
 * Version: 1.0.0
 * Author: Saif H. Hassan
 * Author URI: https://saif-hassan.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: custom-order-countdown-for-woocommerce
 */

use AutomateWoo\Fields\Date;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

add_action(
	'plugins_loaded',
	array( 'Custom_Order_Countdown_For_WooCommerce', 'init' ),
	9
);

register_activation_hook( __FILE__, array( 'Custom_Order_Countdown_For_WooCommerce', 'cocfw_activate' ) );

class Custom_Order_Countdown_For_WooCommerce {

	public function __construct() {
		add_filter(
			'woocommerce_get_settings_products',
			array( $this, 'cocfw_register_section_for_settings' ),
			10,
			2
		);

		add_filter(
			'woocommerce_get_sections_products',
			array( $this, 'cocfw_register_settings_page' ),
			10,
			1
		);

		add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'display_delivery_message' ) );
	}

	public static function init() {
		$class = __CLASS__;
		new $class();
	}

	public static function cocfw_activate() {
		$default_options = array(
			'cocfw_render'           => 'no',
			'cocfw_store_close_time' => '14:00',
		);

		foreach ( $default_options as $option_name => $default_value ) {
			if ( ! get_option( $option_name ) ) {
				add_option( $option_name, $default_value );
			}
		}

		$days          = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
		$default_value = 'monday';

		foreach ( $days as $day ) {
			$option_name_before_14 = 'cocfw_' . $day . '_before_14';
			$option_name_after_14  = 'cocfw_' . $day . '_after_14';

			if ( ! get_option( $option_name_before_14 ) ) {
				add_option( $option_name_before_14, $default_value );
			}

			if ( ! get_option( $option_name_after_14 ) ) {
				add_option( $option_name_after_14, $default_value );
			}
		}
	}

	// Create a section.
	public function cocfw_register_settings_page( $sections ) {
		$sections['cocfw_section'] = __( 'Custom Order Countdown for WooCommerce', 'custom-order-countdown-for-woocommerce' );
		return $sections;
	}

	public function cocfw_register_section_for_settings( $settings, $current_section ) {
		if ( 'cocfw_section' !== $current_section ) {
			return $settings;
		}

		$days_options = array(
			'monday'    => __( 'Monday', 'woocommerce' ),
			'tuesday'   => __( 'Tuesday', 'woocommerce' ),
			'wednesday' => __( 'Wednesday', 'woocommerce' ),
			'thursday'  => __( 'Thursday', 'woocommerce' ),
			'friday'    => __( 'Friday', 'woocommerce' ),
			'saturday'  => __( 'Saturday', 'woocommerce' ),
			'sunday'    => __( 'Sunday', 'woocommerce' ),
		);

		$days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );

		$custom_settings[] =
		array(
			'name' => 'Custom Shipping Countdown Plugin Settings',
			'type' => 'title',
		);

		$custom_settings[] = array(
			'title'   => sprintf( __( 'Enable/Disable the countdown', 'woocommerce' ) ),
			'id'      => 'cocfw_render',
			'type'    => 'checkbox',
			'default' => 'yes',
		);

		$hours      = 24;
		$interval   = 60;
		$start_hour = 0;

		$hours_array = array();
		// For loop to create the dropdown options for the hours.
		for ( $i = 0; $i < $hours * 60; $i += $interval ) {
			$hour   = $start_hour + floor( $i / 60 );
			$minute = $i % 60;

			$time = sprintf( '%02d:%02d', $hour % 24, $minute );

			$hours_array[ $time ] = $time;
		}

		$custom_settings[] = array(
			'title'   => sprintf( __( 'When does the store close?', 'woocommerce' ) ),
			'id'      => 'cocfw_store_close_time',
			'type'    => 'select',
			'options' => $hours_array,
		);

		foreach ( $days as $day ) {
			$custom_settings[] = array(
				'title'   => sprintf( 'If Ordered %s (Before ' . get_option( 'cocfw_store_close_time' ) . ')', $day ),
				'id'      => 'cocfw_' . strtolower( $day ) . '_before_14',
				'type'    => 'select',
				'css'     => '   display: flex;flex-flow: column wrap;background-color: #66FF99;',
				'options' => $days_options,
			);

			$custom_settings[] = array(
				'title'   => sprintf( 'If Ordered %s (After ' . get_option( 'cocfw_store_close_time' ) . ')', $day ),
				'id'      => 'cocfw_' . strtolower( $day ) . '_after_14',
				'type'    => 'select',
				'css'     => '   display: flex;flex-flow: column wrap;background-color: #ffb6c1;',
				'options' => $days_options,
			);
		}

		$custom_settings[] = array(
			'type' => 'sectionend',
		);

		return array_merge( $settings, $custom_settings );
	}

	public function display_delivery_message() {
		$store_close_time = get_option( 'cocfw_store_close_time' );

		$next_day_map = array(
			'Monday'    => array(
				$store_close_time => get_option( 'cocfw_monday_before_14' ),
				'after'           => get_option( 'cocfw_monday_after_14' ),
			),
			'Tuesday'   => array(
				$store_close_time => get_option( 'cocfw_tuesday_before_14' ),
				'after'           => get_option( 'cocfw_tuesday_after_14' ),
			),
			'Wednesday' => array(
				$store_close_time => get_option( 'cocfw_wednesday_before_14' ),
				'after'           => get_option( 'cocfw_wednesday_after_14' ),
			),
			'Thursday'  => array(
				$store_close_time => get_option( 'cocfw_thursday_before_14' ),
				'after'           => get_option( 'cocfw_thursday_after_14' ),
			),
			'Friday'    => array(
				$store_close_time => get_option( 'cocfw_friday_before_14' ),
				'after'           => get_option( 'cocfw_friday_after_14' ),
			),
			'Saturday'  => array(
				$store_close_time => get_option( 'cocfw_saturday_before_14' ),
				'after'           => get_option( 'cocfw_saturday_after_14' ),
			),
			'Sunday'    => array(
				$store_close_time => get_option( 'cocfw_sunday_before_14' ),
				'after'           => get_option( 'cocfw_sunday_after_14' ),
			),
		);

		$current_day  = date_i18n( 'l' );
		$current_time = current_time( 'H:i' );
		$current_date = date_i18n( 'Y-m-d' );

		// This is for the html template.
		$delivery_message = $current_time < $store_close_time ? $next_day_map[ $current_day ][ $store_close_time ] : $next_day_map[ $current_day ]['after'];

		$current_timestamp = current_time( 'timestamp' );
		// Here's the magic we need for the JS counter.
		if ( $current_time < $store_close_time ) {
			$delivery_date_string = $current_date . ' ' . $store_close_time;
		} else {
			$delivery_date_string = date_i18n( 'Y-m-d', strtotime( '+1 day' ) ) . ' ' . $store_close_time;
		}

		$date_time_next_delivery = strtotime( $delivery_date_string );

		$countdown = $date_time_next_delivery - $current_timestamp;

		if ( get_option( 'cocfw_render' ) === 'yes' ) {
			include plugin_dir_path( __DIR__ ) . 'custom-order-countdown-for-woocommerce/templates/counter-template.php';
			wp_enqueue_style( 'custom-order-countdown-for-woocommerce', plugin_dir_url( __FILE__ ) . 'css/countdown-counter.css', array(), '1.0.0', 'all' );

			wc_enqueue_js(
				"
                function updateDeliveryMessage(countdown) {
                    var seconds = countdown;
                    var hours = Math.floor(seconds / 3600);
                    var minutes = Math.floor((seconds % 3600) / 60);
                    seconds = seconds % 60;

                    document.getElementById('cocfw_hours').textContent = String(hours).padStart(2, '0');
                    document.getElementById('cocfw_minutes').textContent = String(minutes).padStart(2, '0');
                    document.getElementById('cocfw_seconds').textContent = String(seconds).padStart(2, '0');

                    if (countdown > 0) {
                        setTimeout(function() {
                            updateDeliveryMessage(countdown - 1);
                        }, 1000);
                    }
                }
                
              updateDeliveryMessage(" . esc_js( $countdown ) . ');
            '
			);
		}
	}
}
