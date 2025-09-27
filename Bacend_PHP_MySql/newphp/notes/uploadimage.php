<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$noteId = filterRequest("noteId");

if (!isset($_FILES["file"]) || $_FILES["file"]["error"] != 0) {
    echo json_encode(array("status"=> "fail", "message" => "No file uploaded"));
    exit();
}

// Get current image to delete it
$stmt = $conn->prepare("SELECT note_image FROM notes WHERE note_id = ?");
$stmt->execute(array($noteId));
$currentNote = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImageName = $currentNote['note_image'] ?? '';

// Upload new image
$newImageName = imageUpload("file");
if($newImageName == "fail") {
    echo json_encode(array("status"=> "fail", "message" => "Image upload failed"));
    exit();
}

// Delete old image if exists
if (!empty($currentImageName)) {
    deleteFile("../upload", $currentImageName);
}

// Update database with new image
$stmt = $conn->prepare("UPDATE `notes` SET `note_image`=? WHERE `note_id`=?");
$stmt->execute(array($newImageName, $noteId));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array(
        "status"=> "success", 
        "imageName" => $newImageName,
        "imageUrl" => "http://192.168.1.118:8080/newphp/upload/" . $newImageName,
        "message" => "Image uploaded successfully"
    ));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Database update failed"));
}
?>