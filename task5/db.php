<?php
$conn = new mysqli("localhost", "root", "", "payment_db");

if ($conn->connect_error) {
    die("Connection failed");
}
?>