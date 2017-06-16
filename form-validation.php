<?PHP
/*
* Validate all form data
*/

function check_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return $data;
}

  // Validate First Name
  if (empty($_SESSION['post-data']['nameFirst'])) {
    $nameFirstErr = 'First Name is required';
    $errors++;
  }  else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['nameFirst'])) {
    $nameFirstErr = "Only letters and white space allowed";
    $errors++;
  } else {
    $_SESSION['post-data']['nameFirst'] = check_input($_SESSION['post-data']['nameFirst']);
  }  
  
  //Validate Business
  $_SESSION['post-data']['business'] = check_input($_SESSION['post-data']['business']);
  
  //Validate Last Name
  if (empty($_SESSION['post-data']['nameLast'])) {
    $nameLastErr = 'Last Name is required';
    $errors++;
  } else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['nameLast'])) {
    $nameLastErr = "Only letters and white space allowed"; 
    $errors++;
  } else {
    $_SESSION['post-data']['nameLast'] = check_input($_SESSION['post-data']['nameLast']);
  } 
  
  // Validate Email
  if (empty($_SESSION['post-data']['email'])) {
    $emailErr = ' Email is required';
    $errors++;
  }  else if (!filter_var($_SESSION['post-data']['email'], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Please use valid email address"; 
    $errors++;
  } else {
    $_SESSION['post-data']['email'] = check_input($_SESSION['post-data']['email']);
  } 
  
  // Validate Phone
  $_SESSION['post-data']['phone'] = check_input($_SESSION['post-data']['phone']);
  
  // Validate Rack Quantities
  if (empty($_SESSION['post-data']['hitch-post-quantity']) && empty($_SESSION['post-data']['corral-quantity'])) {
    $rackErr = 'Please select a rack quantity';
    $errors++;
  } 
  
  // Check for address
  if (empty($_SESSION['post-data']['location-address'])) {
    $locationAddressErr = ' Address is required';
    $errors++;
  } else {
    $_SESSION['post-data']['location-address'] = check_input($_SESSION['post-data']['location-address']);
  }   
  
  // Validate city
  if (empty($_SESSION['post-data']['location-city'])) {
    $locationCityErr = ' City is required';
    $errors++;
  }  else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['location-city'])) {
    $locationCityErr = "Only letters and white space allowed";
    $errors++;
  }  else {
    $_SESSION['post-data']['location-city'] = check_input($_SESSION['post-data']['location-city']);
  } 
  
  // Check for state selection
  if (empty($_SESSION['post-data']['location-state'])) {
    $locationStateErr = 'State is required';
    $errors++;
  } 
  
  // Validate zip code
  if (empty($_SESSION['post-data']['location-zip'])) {
    $locationZipErr = ' Zip Code is required';
    $errors++;
  } else if(preg_match('/^[0-9]{5}$/', $_SESSION['post-data']['location-zip']) == 0){
    $locationZipErr = 'Please enter valid zip code';
    $errors++; 
  }
  
  // Validate Area Description
  $_SESSION['post-data']['area-description'] = check_input($_SESSION['post-data']['area-description']);
  
  // Check for property type selection
  if (empty($_SESSION['post-data']['public-private'])) {
    $propertyTypeErr = 'Please select property type';
    $errors++;
  }
  
  // Validate Property Owner
  $_SESSION['post-data']['property-owner'] = check_input($_SESSION['post-data']['property-owner']);
  
  // Validate Plaque Description
  $_SESSION['post-data']['plaque-description'] = check_input($_SESSION['post-data']['plaque-description']);
  
  // Check for payment type selection
  if (empty($_SESSION['post-data']['payment-type'])) {
    $paymentTypeErr = 'Please select payment type';
    $errors++;
  }
  
  // Check for aggreement to terms and conditions
  if (empty($_SESSION['post-data']['agree'])) {
    $agreeErr = 'Please agree to the terms and conditions';
    $errors++;
  }
  
?>