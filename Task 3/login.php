<?php
// =============================================
// login.php — Handle Login Authentication
// =============================================

session_start();
include 'db.php';

// Send response as JSON
header('Content-Type: application/json');

// ── Step 1: Get POST data from JavaScript ──
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// ── Step 2: Server-side validation ──
if (empty($email) || empty($password)) {
    echo json_encode([
        "status"  => "error",
        "message" => "All fields are required."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Invalid email format."
    ]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode([
        "status"  => "error",
        "message" => "Password must be at least 6 characters."
    ]);
    exit;
}

// ── Step 3: Check user in database ──
try {
    $stmt = $pdo->prepare("
        SELECT id, name, email, password, role
        FROM users
        WHERE email = ?
          AND status = 'Active'
        LIMIT 1
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ── Step 4: Verify password ──
    if ($user && password_verify($password, $user['password'])) {

        // Save session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['email']   = $user['email'];

        // Log successful login
        $log = $pdo->prepare("
            INSERT INTO login_logs (user_id, email, status, ip_address)
            VALUES (?, ?, 'success', ?)
        ");
        $log->execute([$user['id'], $email, $_SERVER['REMOTE_ADDR']]);

        echo json_encode([
            "status" => "success",
            "name"   => $user['name'],
            "role"   => $user['role']
        ]);

    } else {

        // Log failed attempt
        $log = $pdo->prepare("
            INSERT INTO login_logs (user_id, email, status, ip_address)
            VALUES (NULL, ?, 'failed', ?)
        ");
        $log->execute([$email, $_SERVER['REMOTE_ADDR']]);

        echo json_encode([
            "status"  => "error",
            "message" => "Incorrect email or password."
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status"  => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
exit;
?>
