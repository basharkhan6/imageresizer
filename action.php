<?php
require "SimpleImage.php";

$message = "";

$target_dir = "uploads/";
$target_file = $target_dir . rand(10000, 90000) . basename($_FILES["fileToUpload"]["name"]);
$imageOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $imageOk = 1;
    } else {
        $message .= "File is not an image.";
        $imageOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $message .= "Please rename your file. It's already exists.";
    $imageOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $message .= "Sorry, your file is too large.";
    $imageOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $imageOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($imageOk == 0) {
    $message = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    $fileName = store_uploaded_image("fileToUpload", 400, 500, $target_file);
    $message = "Successfully Resized.";
    $message .= '<a href="http://imageresizer.tk/' . $fileName . '">see</a>';
}


echo $message;

function store_uploaded_image($html_element_name, $new_img_width, $new_img_height, $target_file) {

    $image = new SimpleImage();
    $image->load($_FILES[$html_element_name]['tmp_name']);
    $image->resize($new_img_width, $new_img_height);
    $image->save($target_file);
    return $target_file; //return name of saved file in case you want to store it in you database or show confirmation message to user

}

?>