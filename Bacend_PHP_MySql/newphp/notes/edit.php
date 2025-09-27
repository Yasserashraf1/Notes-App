<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$noteId = filterRequest("noteId");
$title = filterRequest("title");
$content = filterRequest("content");

// Get current image name first
$stmt = $conn->prepare("SELECT note_image FROM notes WHERE note_id = ?");
$stmt->execute(array($noteId));
$currentNote = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImageName = $currentNote['note_image'] ?? '';

$imageName = $currentImageName; // Keep current image by default

// Check if user wants to remove image
if(isset($_POST["removeImage"]) && $_POST["removeImage"] == "true") {
    // Delete current image if exists
    if (!empty($currentImageName)) {
        deleteFile("../upload", $currentImageName);
    }
    $imageName = ""; // Set to empty string to remove from database
}
// Check if new image is uploaded
else if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    // Delete old image if exists
    if (!empty($currentImageName)) {
        deleteFile("../upload", $currentImageName);
    }
    
    // Upload new image
    $newImageName = imageUpload("file");
    if($newImageName != "fail") {
        $imageName = $newImageName;
    } else {
        echo json_encode(array("status"=> "fail", "message" => "Image upload failed"));
        exit();
    }
}

$stmt = $conn->prepare("
UPDATE `notes` SET `note_title`=?,`note_content`=?, `note_image`=? WHERE `note_id`=?
");

$stmt->execute(array($title, $content, $imageName, $noteId));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array("status"=> "success", "message" => "Note updated successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Database update failed"));
}
?>