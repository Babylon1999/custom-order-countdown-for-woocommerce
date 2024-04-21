<?php

if ( ! defined( 'WPINC' ) ) {
	die();
}

?>

<hr>
<h4 id='cocfw_delivery_message'>
	You can get this delivered as soon as <?php echo esc_html( ucfirst( $delivery_message ) ); ?> if you order within:
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
