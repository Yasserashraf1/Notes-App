<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$noteId = filterRequest("noteId");

// Get the image name before deleting the record
$stmt = $conn->prepare("SELECT note_image FROM notes WHERE note_id = ?");
$stmt->execute(array($noteId));
$note = $stmt->fetch(PDO::FETCH_ASSOC);
$imagename = $note['note_image'] ?? '';

// Delete the note from database
$stmt = $conn->prepare("DELETE FROM `notes` WHERE `note_id` = ?");
$stmt->execute(array($noteId));

$count = $stmt->rowCount();
if($count > 0) {
    // Delete associated image file if exists
    if (!empty($imagename)) {
        deleteFile("../upload", $imagename);
    }
    echo json_encode(array("status"=> "success", "message" => "Note deleted successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Note not found or delete failed"));
}
?>