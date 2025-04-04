<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn(); // Увери се, че потребителят е влязъл в системата
require_once 'includes/db.php';

// Проверка дали формата е изпратена
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    // Получаване на данни от сесията и формата
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_image'];
    
    // Папка, където ще се качват снимките
    $upload_dir = 'uploads/';
    
    // Разрешени типове изображения
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Проверка дали файлът е валиден
    if (in_array($file['type'], $allowed_types) && $file['size'] < 5000000) { // 5MB максимален размер
        // Генериране на уникално име за файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = "user_$user_id.$extension"; // Име на файла с ID на потребителя
        $filepath = $upload_dir . $filename;
        
        // Преместване на файла в целевата директория
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Актуализиране на базата данни с новото изображение
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->bind_param("si", $filename, $user_id);
            $stmt->execute();
        } else {
            echo "Грешка при качването на снимката.";
        }
    } else {
        echo "Невалиден файл или твърде голям размер на снимката.";
    }
}

// Пренасочване към профила след качване
header("Location: profile.php");
exit();
?>
