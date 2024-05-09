<?php

if ( ! defined( 'WPINC' ) ) {
	die();
}

$delivery_message_weekend = 'You can get this delivered by %s if you order today.';

$delivery_message_filter_weekend = apply_filters( 'ccofw_delivery_message_for_weekends', $delivery_message_weekend, $calulated_day );

$formatted_delivery_message_weekend = sprintf( $delivery_message_filter_weekend, ucfirst( $calulated_day ) );

?>

<hr>
<h4 id='cocfw_delivery_message'>
	<?php echo esc_html( $formatted_delivery_message_weekend ); ?>
</h4>
<hr>