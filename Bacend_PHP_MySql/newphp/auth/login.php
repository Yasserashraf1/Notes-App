<?php
include"../connect.php";
include"../functions.php";

// Create database connection
$db = new Database();
$conn = $db->connect();

$userEmail = filterRequest("userEmail");
$userPass = filterRequest("userPass");

$stmt = $conn->prepare("
    SELECT * FROM `users` WHERE `user_email`= ? and `user_pass`= ?
");

$stmt->execute(array($userEmail, $userPass));

$data = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

if($count > 0) {
    echo json_encode(array("status"=> "success", "data" => $data));
} else {
    echo json_encode(array("status"=> "fail", "message" => "Invalid email or password"));
}
?>