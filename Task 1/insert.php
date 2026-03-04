<?php
$conn = new mysqli("localhost","root","","studentdb");

if($conn->connect_error){
    die("Connection failed");
}

$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$dept = $_POST['department'];
$phone = $_POST['phone'];

$sql = "INSERT INTO students(name,email,dob,department,phone)
        VALUES('$name','$email','$dob','$dept','$phone')";

if($conn->query($sql)){
    echo "Student Registered Successfully <br>";
    echo "<a href='index.html'>Back</a>";
}else{
    echo "Error";
}
?>