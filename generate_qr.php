<?php
// 1. Задължителни проверки в началото
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'phpqrcode.php';

// 2. Проверка за автентикиран потребител
if (!isLoggedIn()) {
    die("Достъпът е отказан! Моля, влезте в системата.");
}

// 3. Вземане на ID от заявката
$id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];

// 4. Защита срещу SQL инжекции
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("Потребителят не е намерен!");
}

// 5. Форматиране на данните за QR кода
$qrData = "BEGIN:VCARD\n";
$qrData .= "VERSION:3.0\n";
$qrData .= "FN:" . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "\n";
$qrData .= "TEL:" . htmlspecialchars($user['phone']) . "\n";
$qrData .= "EMAIL:" . htmlspecialchars($user['email']) . "\n";
$qrData .= "NOTE:Генерирано на " . date('d.m.Y') . "\n";
$qrData .= "END:VCARD";

// 6. Генериране на QR код с правилни headers
header('Content-Type: image/png');
header('Content-Disposition: inline; filename="user_qr.png"');
header('Cache-Control: no-cache, must-revalidate');

// 7. Създаване на QR код
QRcode::png($qrData, null, QR_ECLEVEL_H, 10, 2);
exit();
?>