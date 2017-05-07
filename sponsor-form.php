<?php
/*
Plugin Name: WYR Sponsor Form Plugin
Plugin URI: http://example.com
Description: Sponsorship form for bike racks
Version: 1.0
Author: Ronnie Rodriguez
*/
//asjdf;jasdk;lj;alsdjfkajdjs

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

//  Page information begins here

function sponsor_form() {
	$output = 
	'<form action="../review.php" method="post">
	<p>Your Name (required) <br/>
	<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" /></p>
	<input type="submit" name="cf-submitted" value="Next" >
	</form>';
//	if(isset($_POST['cf-submitted'])) { 
//			sponsor_form_review();
//		} else {
//			echo $output;
//	}
}

function sponsor_form_review() {
	$output = 
	'<p>This is page 2!<br/>
	<form action="' . esc_url_raw( $_SERVER['REQUEST_URI'] ) . '" method="post">
	<input type="submit" name="back" id="back" value="Back" />
	<input type="submit" name="next" id="next" value="Next" />
	</form>';
	//if(isset($_POST["back"])) { 
	//	sponsor_form();
	//} else 
	if(isset($_POST["next"])) { 
		echo "kzlxjvpoaijrewj";
	//	sponsor_form_payment();
	} else {
		echo $output;
	}
}

function sponsor_form_payment() {
	$output = 'This is page 3!';
	echo $output;
}

function sf_shortcode() {
	ob_start();
	sponsor_form();
	return ob_get_clean();
}

add_shortcode( 'wyr_sponsor_form', 'sf_shortcode' );

?>
