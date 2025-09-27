<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$userId = filterRequest("userId");

$stmt = $conn->prepare("
    SELECT * FROM `notes` WHERE `user_id`= ? ORDER BY `updated_at` DESC
");

$stmt->execute(array($userId));

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

if($count > 0) {
    echo json_encode(array("status"=> "success", "data" => $data));
} else {
    echo json_encode(array("status"=> "fail", "message" => "No notes found"));
}
?>