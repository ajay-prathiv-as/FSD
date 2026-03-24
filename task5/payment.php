<?php
include "db.php";

$amount = $_POST['amount'];

$conn->begin_transaction();

try {
    // Check balance
    $result = $conn->query("SELECT balance FROM accounts WHERE id = 1");
    $row = $result->fetch_assoc();

    if ($row['balance'] < $amount) {
        throw new Exception("Insufficient Balance");
    }

    // Deduct from user
    $conn->query("UPDATE accounts SET balance = balance - $amount WHERE id = 1");

    // Add to merchant
    $conn->query("UPDATE accounts SET balance = balance + $amount WHERE id = 2");

    $conn->commit();

    echo "✅ Payment Successful";

} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Payment Failed: " . $e->getMessage();
}
?>