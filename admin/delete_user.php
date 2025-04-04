<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

redirectIfNotAdmin();

// 1. Проверка на CSRF токена
session_start();
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = "Невалиден CSRF токен!";
    header("Location: dashboard.php");
    exit();
}

// 2. Валидация на ID на потребителя
$user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$user_id) {
    $_SESSION['error'] = "Невалидно ID на потребител!";
    header("Location: dashboard.php");
    exit();
}

// 3. Предотвратяване на самоизтриване
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Не можете да изтриете собствения си профил!";
    header("Location: dashboard.php");
    exit();
}

// 4. Изтриване от базата данни с логване на грешки
try {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Потребителят е изтрит успешно!";
    } else {
        $_SESSION['error'] = "Грешка при изтриване!";
        error_log("SQL Error: " . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Грешка при изтриване: " . $e->getMessage();
    error_log("Exception: " . $e->getMessage());
}

// 5. Пренасочване обратно
header("Location: dashboard.php");
exit();
?>
