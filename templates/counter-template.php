<?php

if ( ! defined( 'WPINC' ) ) {
	die();
}

$delivery_message = 'You can get this delivered as soon as %s if you order within:';

$delivery_message_filter = apply_filters( 'ccofw_delivery_message', $delivery_message, $calulated_day );

$formatted_delivery_message = sprintf( $delivery_message_filter, ucfirst( $calulated_day ) );

?>

<hr>
<h4 id='cocfw_delivery_message'>
<?php echo esc_html( $formatted_delivery_message ); ?>
</h4>
<div id='cocfw_delivery_countdown' class='cocfw_countdown-circles'>
	<div class='cocfw_wrap'>
		<div class='cocfw_circle' id='cocfw_hours'></div>
		<span>Hours</span>
	</div>
	<div class='cocfw_wrap'>
		<div class='cocfw_circle' id='cocfw_minutes'></div>
		<span>Minutes</span>
	</div> 
	<div class='cocfw_wrap'>
		<div class='cocfw_circle' id='cocfw_seconds'></div>
		<span>Seconds</span>
	</div>
</div>
<hr>
