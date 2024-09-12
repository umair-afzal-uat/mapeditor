<?php



add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );



function my_theme_enqueue_styles() {

    $parenthandle = 'parent-style'; // This is 'auros-style' for the Auros theme.

    $theme = wp_get_theme();

    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', array(),$theme->parent()->get('Version'));

    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parenthandle ),$theme->get('Version'));

	/*wp_enqueue_style( 'slider', get_stylesheet_directory_uri() . '/css/slider.css', array(), '1.1', 'all');*/

	wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js', array ( 'jquery' ), 1.1, true);

}

add_action( 'woocommerce_after_checkout_validation', 'misha_one_err', 9999, 2); 
function misha_one_err( $fields, $errors ){ 
	// if any validation errors
	if( !empty( $errors->get_error_codes() ) ) {
		// remove all of them
		foreach( $errors->get_error_codes() as $code ) {
			$errors->remove( $code );
		} 
		// add our custom one
		$errors->add( 'validation', 'Please fill all the required fields!' );
	}
}
// add_action('woocommerce_order_status_on-hold', 'email_order_processing_status_for_on_hold', 10, 2 );
// function email_order_processing_status_for_on_hold( $order_id, $order ) {
//     WC()->mailer()->get_emails()['WC_Email_Customer_Processing_Order']->trigger( $order_id );
// }

// add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'send_transactional_email' ), 10, 10 );
// function send_transactional_email( $args = array() ) {
//     global $woocommerce;
//     $woocommerce->mailer();  
//     do_action( current_filter() . '_notification', $args );
// }
// Adds instructions for order emails
// function add_order_email_instructions( $order, $sent_to_admin ) {
 
//   if ( ! $sent_to_admin ) {

//     if ( 'cod' == $order->payment_method ) {
//       // cash on delivery method
//       echo '<p><strong>Instructions:</strong> Full payment is due immediately upon delivery. <em>Cash only, no exceptions</em>.</p>';
//     } else {
//       // other methods (ie credit card)
//       echo '<p><strong>Instructions:</strong> Please look for "Madrigal Electromotive GmbH" on your next credit card statement.</p>';
//     }
//   }
// }
// add_action( 'woocommerce_email_before_order_table', 'add_order_email_instructions', 10, 2 );

// add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ) ); 





















































































?>