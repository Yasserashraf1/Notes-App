<?php

define("MB","1048576");

function filterRequest($requestName) {
   return htmlspecialchars(strip_tags($_POST[$requestName]));
}

function imageUpload($imageRequest) {
    global $msgError;
    $msgError = array(); // Initialize error array

    if (!isset($_FILES[$imageRequest]) || $_FILES[$imageRequest]['error'] !== UPLOAD_ERR_OK) {
        $msgError[] = "No file uploaded or upload error";
        return "fail";
    }

    $imagename = rand(1000,10000) . "_" . $_FILES[$imageRequest]['name'];
    $imagetmp  = $_FILES[$imageRequest]['tmp_name'];
    $imagesize = $_FILES[$imageRequest]['size'];

    $allowExt = array("jpg", "jpeg", "png", "gif"); // Remove mp3, pdf for images only
    $ext = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowExt)) {
        $msgError[] = "Invalid file extension. Only jpg, jpeg, png, gif allowed";
        return "fail";
    }
    
    if ($imagesize > 5 * MB) {
        $msgError[] = "File size too large. Max 5MB allowed";
        return "fail";
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = "../upload/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (empty($msgError)) {
        if (move_uploaded_file($imagetmp, $uploadDir . $imagename)) {
            return $imagename;
        } else {
            return "fail";
        }
    } else {
        return "fail";
    }
}

function deleteFile($dir, $imagename) {
    if (!empty($imagename) && $imagename != "fail" && file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
        return true;
    }
    return false;
}

?>