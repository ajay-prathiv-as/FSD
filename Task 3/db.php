<?php
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === "student@school.com" && $password === "123456") {
    echo json_encode([
        "status" => "success",
        "name" => "Ravi Kumar",
        "role" => "Student"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid credentials"
    ]);
}
?>