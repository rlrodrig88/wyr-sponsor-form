<?PHP

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

  if (empty($_SESSION['post-data']['nameFirst'])) {
    $nameErr = 'Full Name is required';
    $errors++;
  } 

?>