<?php
$conn = new mysqli("localhost","root","","studentdb");

$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
</head>
<body>

<h2>Student Records</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>DOB</th>
    <th>Dept</th>
    <th>Phone</th>
</tr>

<?php
while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['dob']}</td>
            <td>{$row['department']}</td>
            <td>{$row['phone']}</td>
          </tr>";
}
?>

</table>

<br>
<a href="index.html">Back</a>

</body>
</html>