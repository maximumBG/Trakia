<?php
require_once '../includes/auth.php';
redirectIfNotAdmin();

include '../includes/db.php';

$user_id = $_GET['id'] ?? 0;
if ($user_id != $_SESSION['user_id']) {
    $conn->query("DELETE FROM users WHERE id = $user_id");
}

header("Location: dashboard.php");
exit();
?>