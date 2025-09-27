<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$title = filterRequest("title");
$content = filterRequest("content");
$userId = filterRequest("userId");

$imagename = ""; // Default to empty string
if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $imagename = imageUpload("file");
    if($imagename == "fail") {
        echo json_encode(array("status"=> "fail", "message" => "Image upload failed"));
        exit();
    }
}

$stmt = $conn->prepare("
INSERT INTO `notes` (`note_title`,`note_content`,`user_id`,`note_image`)
VALUES (?,?,?,?)
");

$stmt->execute(array($title, $content, $userId, $imagename));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array("status"=> "success", "message" => "Note added successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Database insert failed"));
}
?>