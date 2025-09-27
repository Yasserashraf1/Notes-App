<?php
// newphp/profile/updateprofileimage.php
include"../connect.php";
include"../functions.php";

$db = new Database();
$conn = $db->connect();

$userId = filterRequest("userId");

if (!isset($_FILES["file"]) || $_FILES["file"]["error"] != 0) {
    echo json_encode(array("status"=> "fail", "message" => "No file uploaded"));
    exit();
}

// Create profiles directory if it doesn't exist
$profileDir = "../upload/profiles/";
if (!is_dir($profileDir)) {
    mkdir($profileDir, 0755, true);
}

// Get current profile image
$stmt = $conn->prepare("SELECT profile_image FROM users WHERE user_id = ?");
$stmt->execute(array($userId));
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImageName = $currentUser['profile_image'] ?? '';

// Custom image upload for profiles
function profileImageUpload($imageRequest) {
    global $msgError;
    $msgError = array();

    if (!isset($_FILES[$imageRequest]) || $_FILES[$imageRequest]['error'] !== UPLOAD_ERR_OK) {
        return "fail";
    }

    $imagename = "profile_" . rand(1000,10000) . "_" . $_FILES[$imageRequest]['name'];
    $imagetmp  = $_FILES[$imageRequest]['tmp_name'];
    $imagesize = $_FILES[$imageRequest]['size'];

    $allowExt = array("jpg", "jpeg", "png", "gif");
    $ext = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowExt)) {
        return "fail";
    }
    
    if ($imagesize > 5 * 1048576) { // 5MB limit
        return "fail";
    }
    
    $uploadDir = "../upload/profiles/";
    
    if (move_uploaded_file($imagetmp, $uploadDir . $imagename)) {
        return $imagename;
    }
    
    return "fail";
}

// Upload new profile image
$newImageName = profileImageUpload("file");
if($newImageName == "fail") {
    echo json_encode(array("status"=> "fail", "message" => "Image upload failed"));
    exit();
}

// Delete old profile image if exists
if (!empty($currentImageName) && file_exists("../upload/profiles/" . $currentImageName)) {
    unlink("../upload/profiles/" . $currentImageName);
}

// Update database with new profile image
$stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
$stmt->execute(array($newImageName, $userId));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array(
        "status"=> "success", 
        "imageName" => $newImageName,
        "imageUrl" => "http://192.168.1.118:8012/newphp/upload/profiles/" . $newImageName,
        "message" => "Profile image updated successfully"
    ));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Database update failed"));
}
?>