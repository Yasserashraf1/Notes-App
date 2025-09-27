<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$noteId = filterRequest("noteId");

// Get current image name first
$stmt = $conn->prepare("SELECT note_image FROM notes WHERE note_id = ?");
$stmt->execute(array($noteId));
$currentNote = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImageName = $currentNote['note_image'] ?? '';

// Delete image file if exists
if (!empty($currentImageName)) {
    deleteFile("../upload", $currentImageName);
}

// Update database to remove image reference
$stmt = $conn->prepare("UPDATE `notes` SET `note_image`='' WHERE `note_id`=?");
$stmt->execute(array($noteId));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array("status"=> "success", "message" => "Image removed successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Failed to update database"));
}
?>