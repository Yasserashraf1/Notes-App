<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$userName = filterRequest("userName");
$userEmail = filterRequest("userEmail");
$userPass = filterRequest("userPass");

// Check if email already exists
$checkStmt = $conn->prepare("SELECT user_id FROM `users` WHERE `user_email`= ?");
$checkStmt->execute(array($userEmail));
$existingUser = $checkStmt->rowCount();

if($existingUser > 0) {
    echo json_encode(array("status"=> "fail", "message" => "Email already exists"));
    exit();
}

$stmt = $conn->prepare("
INSERT INTO `users` (`user_name`,`user_email`,`user_pass`) VALUES (?,?,?)
");

$stmt->execute(array($userName, $userEmail, $userPass));

$count = $stmt->rowCount();
if($count > 0) {
    echo json_encode(array("status"=> "success", "message" => "User registered successfully"));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Registration failed"));
}
?>