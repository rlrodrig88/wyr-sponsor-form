<?php

$target_dir = "./wp-content/plugins/wyr-sponsor-form/temp/";
$target_file = $target_dir . basename( $_FILES["fileToUpload"]["name"]);
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if the user has uploaded a file 
if(file_exists($_FILES["fileToUpload"]["tmp_name"])) {

    // Check if temp directory has a file in it already and delete it.
    if (count(glob($target_dir)) > 0 ) {
        clear_temp_files();
    }
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
        } else {
            $fileUploadErr = 'File is not an image.';
            $errors++;        
        }
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $fileUploadErr = 'Sorry, your file is too large.';
        $errors++;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg"
    && $imageFileType != "JPEG" && $imageFileType != "gif" && $imageFileType != "GIF" && $imageFileType != "PDF"  && $imageFileType != "pdf"
    && $imageFileType != "EPS" && $imageFileType != "eps") {
        $fileUploadErr = 'Sorry, only JPG, PDF, EPS, PNG & GIF files are allowed.';    
        $errors++;
    }
    
    // if everything is ok, try to upload file
    if ($errors === 0) {
    //    $fileUploadErr = "Sorry, your file was not uploaded.";
    //} else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $fileUploadConfirm = "The file: ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            $fileUploadErr = "Sorry, there was an error uploading your file.";
        }
    }
    
}



?>