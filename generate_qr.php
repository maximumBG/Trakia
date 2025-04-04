<?php
// 1. Изчистване на изходните буфери
while (ob_get_level()) ob_end_clean();

// 2. Задаване на headers за изображение
header('Content-Type: image/png');
header('Content-Disposition: inline; filename="qr_profile.png"');
header('Content-Type: image/png; charset=utf-8');

// 3. Включване на необходимите файлове
require_once __DIR__.'/phpqrcode.php';
include('includes/db.php');

// 4. Вземане на ID от заявката
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 5. Заявка към базата данни
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $qrText = "BEGIN:VCARD\n";
        $qrText .= "VERSION:3.0\n";
        $qrText .= "N:".$user['last_name'].";".$user['first_name']."\n";
        $qrText .= "FN:".$user['first_name']." ".$user['last_name']."\n";
        $qrText .= "TEL:".$user['phone']."\n";
        $qrText .= "EMAIL:".$user['email']."\n";
        $qrText .= "BDAY:".$user['birth_date']."\n";
        $qrText .= "END:VCARD";
        

        // 7. Генериране на QR код
        QRcode::png(
            $qrText,
            null,             // Не записва във файл
            QR_ECLEVEL_H,     // Висока корекция на грешки
            12,              // Размер
            4,               // Марж
            false,           // Не показва директно
            0xFFFFFF,        // Бял фон
            0x000000         // Черен QR код
        );
        exit();
    }
}

// 8. Грешка - съобщение в QR код
QRcode::png('Грешка: Невалиден потребител', null, QR_ECLEVEL_L, 10, 2);
exit();
?>