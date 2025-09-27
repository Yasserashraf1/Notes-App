<?php
// newphp/profile/getprofile.php
include"../connect.php";
include"../functions.php";

$db = new Database();
$conn = $db->connect();

$userId = filterRequest("userId");

$stmt = $conn->prepare("SELECT user_id, user_name, user_email, profile_image, created_at FROM users WHERE user_id = ?");
$stmt->execute(array($userId));

$data = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

if($count > 0) {
    echo json_encode(array("status"=> "success", "data" => $data));
} else {
    echo json_encode(array("status"=> "fail", "message" => "User not found"));
}
?>