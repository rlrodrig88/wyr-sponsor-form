<?php
/*
Plugin Name: WYR Sponsor Form Plugin
Plugin URI: https://github.com/rlrodrig88/wyr-sponsor-form
Description: Sponsorship form for bike racks.  Creates and sends a PDF of the completed form via email for notification.  Allows for mailed checks or credit card payment.
Version: 1.0
Author: Ronnie Rodriguez
*/

// PDF creator script
require('fpdf/fpdf.php');  // MAKE SURE YOU HAVE THIS LINE

// Set up session 
add_action('init', 'start_session', 1);
add_action('wp_logout', 'end_session');
add_action('wp_login', 'end_session');
add_action('end_session_action', 'end_session');

function end_session() {
	session_destroy();
}

function start_session() {
	if(!session_id()) {
		session_start();
	}
}

/* Page information begins here
*
*  Display sponsor form
*/
function sponsor_form() {
	$output = 
	'<form action="" method="post">
	<p>Your Name (required) <br/>
	<input type="text" name="first-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["first-name"] ) ? esc_attr( $_POST["first-name"] ) : '' ) . '" size="40" /></p>
	<input type="submit" name="form-submit" value="Next" >
	</form>';
	if(isset($_POST["form-submit"])) { 
		// create an array of all $_POST variables
		$_SESSION['post-data'] = $_POST;
		// review user input
		sponsor_form_review();
		//sponsor_form_payment_check();
	} else if(isset($_POST['review'])) { 
		sponsor_form_payment_check();
	} else {
			echo $output;
	}
}

// Let user review the completed form
function sponsor_form_review() {
	$output = 
	'<p>This is page 2!<p/>' 
	. $_SESSION['post-data']['first-name'] . '
	<form action="" method="post">
	<input type="submit" name="review" id="next" value="Next" />
	</form>';
	echo $output;
}

// Display paypal button and let user proceed for credit card payment
function sponsor_form_payment_credit() {
	include 'create-pdf.php';
	$output = '<p>This is page 3A!</p>';
}

// Display payment and address information for checks
function sponsor_form_payment_check() {
	include 'create-pdf.php';	
	$output = '<p>This is page 3B!<p>
	<p>Please make checks for sponsorships payable to: Young Leadership Council
	Where Ya’ Rack? c/o Young Leadership Council, PO Box 56909, New Orleans, LA 70156</p>
	<p>We will be in touch as soon as we receive payment and can schedule your rack installation.</p>
	<p>Thank you for your sponsorship!</p>
	<form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post">
	<input type="submit" name="home" id="next" value="Home" />
	</form>';
	echo $output;
}

// Create shortcode for the plugin
function sf_shortcode() {
	ob_start();
	sponsor_form();
	return ob_get_clean();
}

add_shortcode('wyr_sponsor_form', 'sf_shortcode' );

?>
