<?php
require 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE id = " . $_SESSION['user_id'])->fetch_assoc();
?>

<h2>Здравейте, <?= htmlspecialchars($user['first_name']) ?>!</h2>
<!-- Показване на QR код с лични данни -->
<img src="generate_qr.php?id=<?= $user['id'] ?>" alt="Вашият QR код">