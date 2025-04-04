<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

redirectIfNotAdmin();

// Валидация на CSRF токен
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Невалидна заявка";
    header("Location: admin.php");
    exit();
}

$user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$user_id) {
    $_SESSION['error'] = "Невалиден ID на потребител";
    header("Location: admin.php");
    exit();
}

// Защита срещу самоизтриване
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Не можете да изтриете собствения си профил!";
    header("Location: admin.php");
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Потребителят е изтрит успешно!";
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "Грешка при изтриване: " . $e->getMessage();
}

header("Location: admin.php");
exit();
?>