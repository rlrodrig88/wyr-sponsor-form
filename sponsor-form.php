<?php
/*
Plugin Name: WYR Sponsor Form Plugin
Plugin URI: https://github.com/rlrodrig88/wyr-sponsor-form
Description: Sponsorship form for bike racks.  Support image file upload.  Creates and sends a PDF of the completed form along with uploaded image via email to project team.  Allows for mailed checks or credit card payment.
Version: 1.0
Author: Ronnie Rodriguez
*/

// Load PDF creator script
require('fpdf/fpdf.php'); 

// Load plugin stylesheet
add_action( 'wp_enqueue_scripts', 'load_plugin_css', 50 );

function load_plugin_css() {
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

// Delete previous form PDF file and any uploaded images
// Clear all previous $_POST variables
function clear_form() {
    $folder = './wp-content/plugins/wyr-sponsor-form/temp';
    $files = glob($folder . '/*');
    foreach($files as $file){
        if(is_file($file)){
            unlink($file);
        }
    } 
    $_SESSION['post-data'] = array();
	$_POST = array();    
}

// Display Sponsor Form
function sponsor_form() {
    $errors = 0;
    $nameFirstErr = $nameLastErr = $emailErr = $rackErr = '';
    $locationAddressErr = $locationCityErr = $locationStateErr = $locationZipErr = '';
    $propertyTypeErr = $fileUploadErr = $paymentTypeErr = $agreeErr = '';

	// Check for form submission
	if(isset($_POST["form-submit"])) {
		// create an array of all $_POST variables
		$_SESSION['post-data'] = $_POST;
		// validate user input
	    include 'form-validation.php';
	    // validate and upload plaque design file (if selected)
	    include 'file-upload.php';
		// if all entries are valid, let review user input
		if ($errors == 0) {
            return sponsor_form_review();
		}
	// user input has been approved, proceed to selected payment
	} else if (isset($_POST["review-submit"])) {
	    if($_SESSION['post-data']['payment-type'] === 'credit') {
		    return sponsor_form_payment_credit();
		} else if ($_SESSION['post-data']['payment-type'] === 'check') {
		    return sponsor_form_payment_check();
		} else echo "No Payment Selected!";
	}
	
    $output = 
    '<div id="main">
    <form id="sponnsor-form" action="" method="post" enctype="multipart/form-data">
    <p>* required field</p>
    <div class="section">Sponsor Information</div>
    <div class="row">
        <div class="field" id="nameFirst-field">
            <div class="field-label">First Name * <span class="required">'. $nameFirstErr . '</span></div>
            <input id="nameFirst" class="entry" name="nameFirst" type="text" value="' . $_SESSION['post-data']['nameFirst'] . '"/>
        </div>
        <div class="field" id="nameLast-field">
             <div class="field-label">Last Name * <span class="required">'. $nameLastErr . '</span></div>
             <input id="nameLast" class="entry" name="nameLast" type="text" value="' . $_SESSION['post-data']['nameLast'] . '"/>
        </div>
    </div>
    <div class="row">
        <div class="field" id="business-field">  
            <div class="field-label">Business</div>
            <input id="business" class="entry" name="business" type="text" value="' . $_SESSION['post-data']['business'] . '"/>
        </div>
    </div>
    <div class="row">    
        <div class="field" id="email-field"> 
            <div class="field-label">Email * <span class="required">'. $emailErr . '</span></div> 
            <input id="email" class="entry" name="email" type="text" value="' . $_SESSION['post-data']['email'] . '"/>
        </div>
        <div class="field" id="phone-field">  
            <div class="field-label">Phone</div>   
            <input id="phone" class="entry" name="phone" type="text" value="' . $_SESSION['post-data']['phone'] . '"/>
        </div>    
    </div>
    
    <div class="section">Rack Information *</div><span class="required">'. $rackErr . '</span>
    <div class="row">
        <div class="rack-item">
            <div><img src="http://www.whereyarack.org/wp-content/uploads/2017/03/Entergy_Audubon-150x150.jpg" alt="" width="150" height="150" /></div>
            <div class="quantity-field">
                <div class="quantity-label">Hitch Post</div>
                <div class="quantity-label small-text">(2 bikes)</div> 
                <div class="quantity">
                    <div class="field-label">Qty</div>
                    <input id="hitch-post-quantity" name="hitch-post-quantity" class="quantity-entry" type="number" value="' . $_SESSION['post-data']['hitch-post-quantity'] . '"/>
                </div>
            </div>
        </div>   
        <div class="rack-item">
            <div><img class="alignnone wp-image-253 size-thumbnail" src="http://www.whereyarack.org/wp-content/uploads/2017/03/IMG_1258-150x150.jpg" alt="" width="150" height="150" /></div>
            <div class="quantity-field">     
                <div class="quantity-label">Corral</div>
                <div class="quantity-label small-text">(12 bikes)</div>
                <div class="quantity">
                    <div class="field-label">Qty</div>
                    <input id="corral-quantity" name="corral-quantity" class="quantity-entry" type="number" value="' . $_SESSION['post-data']['corral-quantity'] . '"/>
                </div>
            </div>
        </div>   
    </div>
       
    <div class="section">Rack Location</div>
    <div class="row">
        <div class="field" id="address-field">  
            <div class="field-label">Address * <span class="required">'. $locationAddressErr . '</span></div>  
            <input id="location-address" name="location-address" class="entry" type="text" value="' . $_SESSION['post-data']['location-address'] . '"/>
        </div>
    </div>
    <div class="row">    
        <div class="field" id="city-field">  
            <div class="field-label">City * <span class="required">'. $locationCityErr . '</span></div>  
            <input id="location-city" name="location-city" class="entry" type="text" value="' . $_SESSION['post-data']['location-city'] . '"/>
        </div>
        <div class="field" id="state-field">  
            <div class="field-label">State * <span class="required">'. $locationStateErr . '</span></div>' . '
            <select id="location-state" name="location-state" class="entry" selected="LA" value="' . $_SESSION['post-data']['location-state'] . '" >
            	<option value="AL">AL</option>
            	<option value="AK">AK</option>
            	<option value="AZ">AZ</option>
            	<option value="AR">AR</option>
            	<option value="CA">CA</option>
            	<option value="CO">CO</option>
            	<option value="CT">CT</option>
            	<option value="DE">DE</option>
            	<option value="DC">DC</option>
            	<option value="FL">FL</option>
            	<option value="GA">GA</option>
            	<option value="HI">HI</option>
            	<option value="ID">ID</option>
            	<option value="IL">IL</option>
            	<option value="IN">IN</option>
            	<option value="IA">IA</option>
            	<option value="KS">KS</option>
            	<option value="KY">KY</option>
            	<option value="LA" selected="selected">LA</option>
            	<option value="ME">ME</option>
            	<option value="MD">MD</option>
            	<option value="MA">MA</option>
            	<option value="MI">MI</option>
            	<option value="MN">MN</option>
            	<option value="MS">MS</option>
            	<option value="MO">MO</option>
            	<option value="MT">MT</option>
            	<option value="NE">NE</option>
            	<option value="NV">NV</option>
            	<option value="NH">NH</option>
            	<option value="NJ">NJ</option>
            	<option value="NM">NM</option>
            	<option value="NY">NY</option>
            	<option value="NC">NC</option>
            	<option value="ND">ND</option>
            	<option value="OH">OH</option>
            	<option value="OK">OK</option>
            	<option value="OR">OR</option>
            	<option value="PA">PA</option>
            	<option value="RI">RI</option>
            	<option value="SC">SC</option>
            	<option value="SD">SD</option>
            	<option value="TN">TN</option>
            	<option value="TX">TX</option>
            	<option value="UT">UT</option>
            	<option value="VT">VT</option>
            	<option value="VA">VA</option>
            	<option value="WA">WA</option>
            	<option value="WV">WV</option>
            	<option value="WI">WI</option>
            	<option value="WY">WY</option>
            </select>
        </div>
        <div class="field" id="zip-field">  
            <div class="field-label">Zip Code * <span class="required">'. $locationZipErr . '</span></div>  
            <input id="location-zip" name="location-zip" class="entry" type="text" value="' . $_SESSION['post-data']['location-zip'] . '"/>
        </div>  
    </div>
    <div class="row">
        <div class="field full-width">
            <div class="field-label">Area Description</div>  
            <textarea rows="3" id="location-area" name="area-description" class="entry" type="text">' . $_SESSION['post-data']['area-description'] . '</textarea>
        </div>    
    </div>
        <span class="required">'. $propertyTypeErr . '</span>
    <div class="row">
        <div class="field-label">
            <div class="radio-field">
                <div class="radio-label">Public Land * </div>
                <input id="public" name="public-private" class="radio-entry" type="radio" value="public"/>
            </div>
            <div class="radio-field">
                <div class="radio-label">Private Property * </div>
                <input id="private" name="public-private" class="radio-entry" type="radio" value="private" />
            </div>
        </div> 
    </div>
    <div class="row">    
        <div class="field" id="property-owner-field">
            <div class="field-label">Property Owner (if private)</div>
            <input id="property-owner" name="property-owner" class="entry" type="text" value="' . $_SESSION['post-data']['property-owner'] . '"/>
        </div>    
    </div>
    
    <div class="section">Plaque Information</div>
    <div class="row">
        <div class="field full-width">
            <div class="field-label">Description</div>
            <textarea rows="3" id="plaque-description" name="plaque-description" class="entry" type="text">' . $_SESSION['post-data']['plaque-description'] . '</textarea>
        </div>
    </div>
    <div class="row">
        <div class="field">
            <div class="field-label small-text">If you have an image that you would like to incorporate into the plaque design, please attach it here.</br>Larger files will take longer to upload.</div>
            <input id="image-upload" name="fileToUpload" class="entry" type="file" />
            <span class="required">'. $fileUploadErr . '</span>
        </div>
    </div>
    
    <div class="section">Payment Information * </div>
    <div class="row">
        <div class="field-label">
            <div class="radio-field">
                <div class="radio-label">Credit Card</div>
                <input id="credit" name="payment-type" type="radio" class="radio-entry" type="radio" value="credit"/>
            </div>
            <div class="radio-field">
                <div class="radio-label">Check</div>
                <input id="check" name="payment-type" type="radio" class="radio-entry" type="radio" value="check"/>
            </div>    
        </div>
    </div>
    
    <span class="required">'. $paymentTypeErr . '</span>
    
    <div class="full-width">
        <div class="section">Terms and Conditions</div>
        <ol class="terms">
            <li>If located on public property, the bicycle rack is donated to and becomes the property of the public entity or authority</li>
            <li>My sponsorship lasts the lifespan of the bicycle rack, which is estimated to be approximately 10-20 years. If the bicycle rack is damaged, the Young Leadership Council and City are not responsible for its replacement.</li>
            <li>While all efforts will be made to accommodate the sponsor\'s location preference, the exact placement of my sponsored bicycle rack will be at the discretion of the Young Leadership Council and the land owner.</li>
            <li>My sponsored bicycle rack may need to be relocated temporarily or permanently due to construction, utility or circulation conflicts.</li>
            <li>The dedication plaque shall not be used for commercial advertising on public property</li>
            <li>I am responsible for carefully reviewing the dedication plaque design before I approve it, and if I wish to change the design after I have approved it and the order has been placed, I will pay for a new plaque.</li>
            <li>If plaque and/or location data is not supplied to Where Ya\' Rack? within 3 months of request, Where Ya\' Rack? will use the known location or known plaque design and best fulfill the remainder of the sponsor\'s request.</li>
        </ol>
    </div>
    <div class="radio-field">
        <div class="radio-label"><strong>I agree *</strong></div>
        <input id="agree" class="radio-entry" name="agree" type="checkbox" />
    </div>
    <span class="required">'. $agreeErr . '</span>
    </br></br>
    <input class="nav-button" id="next" name="form-submit" type="submit" value="Next" />
    </form>';
    echo $output;
}

// Let user review the completed form
function sponsor_form_review() {
	$output = 
	'<h2>Please review and confirm your information:</h2>
	<div class="section">Sponsor Information</div>' 
	. $_SESSION['post-data']['nameFirst'] . ' ' . $_SESSION['post-data']['nameLast'] . '</br>'
	. $_SESSION['post-data']['business'] . '</br>' 
	. $_SESSION['post-data']['email'] . '</br>' 
	. $_SESSION['post-data']['phone'] . '</br>
	<div class="section">Rack Information</div>
	<p>Hitch Post Racks: ' . $_SESSION['post-data']['hitch-post-quantity'] . '
	<p>Corrals: ' . $_SESSION['post-data']['corral-quantity'] . '	
	<div class="section">Rack Location</div>'
	. $_SESSION['post-data']['location-address'] .'</br>'
	. $_SESSION['post-data']['location-state'] .  ' ' . $_SESSION['post-data']['location-city'] . ' ' . $_SESSION['post-data']['location-zip'] . '</br>'
	. $_SESSION['post-data']['area-description'] .'</br>'
	. $_SESSION['post-data']['public-private'] .' Property</br>	
	<div class="section">Plaque Information</div>'
	. $_SESSION['post-data']['plaque-description'] .'</br>
	<p>Uploaded Files:  ' . basename( $_FILES["fileToUpload"]["name"]) . '</p>
	<div class="section">Payment Information</div>'
    . $_SESSION['post-data']['payment-type'] . '</br></br>	
	<form action="" method="post">
	<input class="nav-button" type="button" onclick="window.history.back()" value="Back" />
	<input class="nav-button" type="submit" name="review-submit" id="next" value="Submit" />
	</form>
	</div>';
	echo $output;
}

// Display paypal button and let user proceed for credit card payment
// PDF is created in temp directory, ready to email
function sponsor_form_payment_credit() {
    $total = $_SESSION['post-data']['hitch-post-quantity'] * 300 + $_SESSION['post-data']['corral-quantity'] * 2500;
	$output = ' 
	<p>Your total is: &#160 &#160 <strong>$' . $total . '.00</strong</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input name="business" type="hidden" value="natasha@ylcnola.org" />
    <input name="cmd" type="hidden" value="_donations" />
    <input name="item_name" type="hidden" value="Where Ya\' Rack? - New Sponsorship" />
    <input name="item_number" type="hidden" value="Bike Racks" />
    <input name="amount" type="hidden" value="' . $total . '" />
    <input name="currency_code" type="hidden" value="USD" />
    <input id="pay" style="font-size: 20px;" name="pay" type="submit" value="Pay Now" /></br>
    <img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" /></form><img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="Buy now with PayPal" />';
	echo $output;
	// attach PDF and drawing file and send email
	include 'create-pdf.php';
	clear_form();
}

// Display payment and address information for checks
// PDF is created in temp directory, ready to email
function sponsor_form_payment_check() {
    $total = $_SESSION['post-data']['hitch-post-quantity'] * 300 + $_SESSION['post-data']['corral-quantity'] * 2500;
	$output = '
	<p>Your total is: &#160 &#160 <strong>$' . $total . '.00</strong><p>
	<p>Please make checks for sponsorships payable to: </br>
	Where Ya’ Rack? c/o Young Leadership Council, PO Box 56909</br>
	New Orleans, LA 70156</p>
	<p>We will be in touch as soon as we receive payment and can schedule your rack installation.</p>
	<p>Thank you for your sponsorship!</p>';	
	echo $output;
	// attach PDF and drawing file and send email
	include 'create-pdf.php';
	clear_form();
}

// Create shortcode for the plugin
function sf_shortcode() {
	ob_start();
	sponsor_form();
	return ob_get_clean();
}

add_shortcode('wyr_sponsor_form', 'sf_shortcode' );

?>
