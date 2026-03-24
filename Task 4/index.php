<?php
include 'db.php';

/* ORDER HISTORY */
$sql = "SELECT 
            c.name,
            p.product_name,
            o.quantity,
            (p.price * o.quantity) AS total_price,
            o.order_date
        FROM Orders o
        JOIN Customers c ON o.customer_id = c.customer_id
        JOIN Products p ON o.product_id = p.product_id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);

/* HIGHEST ORDER */
$sql2 = "SELECT c.name, (p.price * o.quantity) AS total_value
         FROM Orders o
         JOIN Customers c ON o.customer_id = c.customer_id
         JOIN Products p ON o.product_id = p.product_id
         ORDER BY total_value DESC
         LIMIT 1";

$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

/* MOST ACTIVE CUSTOMER */
$sql3 = "SELECT c.name, COUNT(o.order_id) AS total_orders
         FROM Customers c
         JOIN Orders o ON c.customer_id = o.customer_id
         GROUP BY c.customer_id
         ORDER BY total_orders DESC
         LIMIT 1";

$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Management</title>
<style>
body {
    font-family: Arial;
    background: #f4f4f4;
}
.container {
    width: 80%;
    margin: auto;
}
h2, h3 {
    text-align: center;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}
th {
    background: #007BFF;
    color: white;
}
</style>
</head>

<body>

<div class="container">

<h2>Customer Order History</h2>

<h3>🏆 Highest Order: <?php echo $row2['name']; ?> - ₹<?php echo $row2['total_value']; ?></h3>

<h3>🥇 Most Active Customer: <?php echo $row3['name']; ?> (<?php echo $row3['total_orders']; ?> orders)</h3>

<table>
<tr>
    <th>Name</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Date</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['total_price']}</td>
            <td>{$row['order_date']}</td>
          </tr>";
}
?>

</table>

</div>

</body>
</html>