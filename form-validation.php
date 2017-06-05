<?PHP

  // Validate First Name
  if (empty($_SESSION['post-data']['nameFirst'])) {
    $nameFirstErr = 'First Name is required';
    $errors++;
  }  else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['nameFirst'])) {
    $nameFirstErr = "Only letters and white space allowed";
    $errors++;
  }
  //Validate Last Name
  if (empty($_SESSION['post-data']['nameLast'])) {
    $nameLastErr = 'Last Name is required';
    $errors++;
  } else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['nameLast'])) {
    $nameLastErr = "Only letters and white space allowed"; 
    $errors++;
  } 
  // Validate Email
  if (empty($_SESSION['post-data']['email'])) {
    $emailErr = ' Email is required';
    $errors++;
  }  else if (!filter_var($_SESSION['post-data']['email'], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Please use valid email address"; 
    $errors++;
  }
  // Validate Rack Quantities
  if (empty($_SESSION['post-data']['hitch-post-quantity']) && empty($_SESSION['post-data']['corral-quantity'])) {
    $rackErr = 'Please select a rack quantity';
    $errors++;
  }
  // Check for address
  if (empty($_SESSION['post-data']['location-address'])) {
    $locationAddressErr = ' Address is required';
    $errors++;
  }  
  // Validate city
  if (empty($_SESSION['post-data']['location-city'])) {
    $locationCityErr = ' City is required';
    $errors++;
  }  else if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION['post-data']['location-city'])) {
    $locationCityErr = "Only letters and white space allowed";
    $errors++;
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
  // Check for property type selection
  if (empty($_SESSION['post-data']['public-private'])) {
    $propertyTypeErr = 'Please select property type';
    $errors++;
  }
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