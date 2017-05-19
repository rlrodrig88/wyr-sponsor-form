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

// Load stylesheet
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );

function wpse_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'styles', $plugin_url . 'css/styles.css' );
}

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

/* 
*  Page information begins here
*/

// Display Sponsor Form
function sponsor_form() {
    $errors = 0;
    $nameErr = '';
	// Check for form submission
	if(isset($_POST["form-submit"])) { 
		// create an array of all $_POST variables
		$_SESSION['post-data'] = $_POST;
		// validate user input
	    include 'form-validation.php';		
		// if entries are valid, let review user input
		if ($errors === 0) {
            return sponsor_form_review();
		}
	} else if(isset($_POST['review'])) { 
		sponsor_form_payment_check();
	} 
	
$output = 
	'<form action="" method="post">
  <h3>Sponsor Information</h3>
  <p>* required field</p>
  <div class="fields">
    <div class="row">
      <div class="field">
       <div class="field-label">First Name * '. $nameErr . '</div>
       <input id="name" class="entry" name="nameFirst" type="text" />
      </div>
      <div class="field">
       <div class="field-label">Last Name *</div>
       <input id="name" class="entry" name="nameLast" type="text" />
      </div>
    </div>
 </div>

 <div class="row">
    <div class="field">  
     <div class="field-label">Business</div>
     <input id="business" class="entry" name="business" type="text" />
  </div>
</div>

<div class="row">
  <div class="field"> 
   <div class="field-label">Email *</div> 
   <input id="email" class="entry" name="email" type="text" />
</div>

<div class="field">  
   <div class="field-label">Phone</div>   
   <input id="phone" class="entry" name="phone" type="text" />
</div>    
</div>

<h3>Rack Information</h3>
<div class="row">
   <div class="rack-item">
      <img class="alignnone wp-image-251 size-thumbnail" src="http://www.whereyarack.org/wp-content/uploads/2017/03/Entergy_Audubon-150x150.jpg" alt="" width="150" height="150" /> 
      <div class="quantity-field">
         <div class="field-label">Hitch Post</div>
         <div class="field-label">(secures 2 bikes)</div>         
         Qty<input id="hitch-post-quantity" name="hitch-post-quantity" class="quantity" type="number" />
      </div>
   </div>   
   <div class="rack-item">
      <img class="alignnone wp-image-253 size-thumbnail" src="http://www.whereyarack.org/wp-content/uploads/2017/03/IMG_1258-150x150.jpg" alt="" width="150" height="150" />
      <div class="quantity-field">     
         <div class="field-label">Corral</div>
         <div class="field-label">(secures 12 bikes)</div>          
         Qty<input id="corral-quantity" name="corral-quantity" class="quantity" type="number" />
      </div>
   </div>   
</div>
   
<h3>Rack Location</h3>
<div class="row">
   <div class="field">  
      <div class="field-label">Address * </div>  
      <input id="location-address" name="location-address" class="entry" type="text" />
   </div>
   <div class="field">  
      <div class="field-label">City * </div>  
      <input id="location-city" name="location-city" class="entry" type="text" />
   </div>

   <div class="row-small">
      <div class="field">  
         <div class="field-label">State *</div>  
         <input id="location-state" name="location-state" type="text" />
      </div>
      <div class="field">  
         <div class="field-label">Zip Code *</div>  
         <input id="location-zip" name="location-zip" type="text" />
      </div>  
   </div>
   <div class="field-label">Area Description</div>  
   <textarea rows="4" id="location-area" name="area-description" class="entry" type="text"></textarea>
</div>

Public Land *<input id="public" name="public-private" type="radio" value="public"/>
Private Property *<input id="private" name="public-private" type="radio" value="private"/>
Property Owner (if private) <input id="property-owner" name="property-owner" type="text" />

<h3>Plaque Information</h3>
Description<input id="plaque-description" name="plaque-description" type="text" />
Upload an Image <input id="image-upload" type="file" />

<h3>Payment Information *</h3>
Credit Card<input id="credit-card" type="radio" name="payment-type" value="credit-card"/>
Check<input id="check" type="radio" name="payment-type" value="check"/>

<h3>Terms and Conditions</h3>
<ol class="terms">
  <li>If located on public property, the bicycle rack is donated to and becomes the property of the public entity or authority</li>
  <li>My sponsorship lasts the lifespan of the bicycle rack, which is estimated to be approximately 10-20 years. If the bicycle rack is damaged, the Young Leadership Council and City are not responsible for its replacement.</li>
  <li>While all efforts will be made to accommodate the sponsor\'s location preference, the exact placement of my sponsored bicycle rack will be at the discretion of the Young Leadership Council and the land owner.</li>
  <li>My sponsored bicycle rack may need to be relocated temporarily or permanently due to construction, utility or circulation conflicts.</li>
  <li>The dedication plaque shall not be used for commercial advertising on public property</li>
  <li>I am responsible for carefully reviewing the dedication plaque design before I approve it, and if I wish to change the design after I have approved it and the order has been placed, I will pay for a new plaque.</li>
  <li>If plaque and/or location data is not supplied to Where Ya\' Rack? within 3 months of request, Where Ya\' Rack? will use the known location or known plaque design and best fulfill the remainder of the sponsor\'s request.</li>
</ol>
<strong>I agree *<input id="agree" name="agree" type="checkbox" /></strong>

<input id="next" name="form-submit" type="submit" value="next" />

</form>';
echo $output;
}

// Let user review the completed form
function sponsor_form_review() {
	$output = 
	'<p>Please review and confirm your information:<p/>
	<h3>Sponsor Information</h3>' 
	. $_SESSION['post-data']['nameFirst'] . ' ' . $_SESSION['post-data']['nameLast'] . '</br>'
	. $_SESSION['post-data']['business'] . '</br>' 
	. $_SESSION['post-data']['email'] . '</br>' 
	. $_SESSION['post-data']['phone'] . '</br>
	<h3>Rack Information</h3>
	<p>Hitch Post Racks: ' . $_SESSION['post-data']['hitch-post-quantity'] . '
	<p>Corrals: ' . $_SESSION['post-data']['hitch-post-quantity'] . '	
	<h3>Rack Location</h3>
	<h3>Plaque Information</h3>
	<h3>Payment Information</h3>
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
	<form action="" method="post">
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
