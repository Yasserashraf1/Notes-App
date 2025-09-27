<?php
// newphp/profile/updateprofile.php
include"../connect.php";
include"../functions.php";

$db = new Database();
$conn = $db->connect();

$userId = filterRequest("userId");
$userName = filterRequest("userName");

$stmt = $conn->prepare("UPDATE users SET user_name = ? WHERE user_id = ?");
$stmt->execute(array($userName, $userId));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array("status"=> "success", "message" => "Profile updated successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Failed to update profile"));
}
?>
