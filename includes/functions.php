<?php
/**
 * Функции за сигурност и помощни инструменти
 */

// Генериране на CSRF токен
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Валидация на CSRF токен
function verifyCsrfToken(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            header('HTTP/1.1 403 Forbidden');
            die("Невалидна заявка. CSRF токенът не съвпада.");
        }
    }
}

// Почистване на потребителски вход
function clean_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $data;
}

// Валидация на имейл
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Валидация на телефонен номер (за БГ)
function validate_phone(string $phone): bool {
    return preg_match('/^(\+359|0)[0-9]{9}$/', $phone) === 1;
}

// Валидация на парола (мин. 6 символа, 1 цифра)
function validate_password(string $password): bool {
    return strlen($password) >= 6 && preg_match('/[0-9]/', $password);
}

// Редирект с флаш съобщение
function redirect_with_message(string $url, string $message, string $type = 'success'): void {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
    header("Location: $url");
    exit();
}

// Показване на флаш съобщения
function display_flash_message(): void {
    if (isset($_SESSION['flash'])) {
        $message = htmlspecialchars($_SESSION['flash']['message']);
        $type = htmlspecialchars($_SESSION['flash']['type']);
        echo "<div class='alert alert-$type'>$message</div>";
        unset($_SESSION['flash']);
    }
}

// Логване на административни действия
function log_action(string $action, int $user_id, ?string $details = null): void {
    $log_message = sprintf(
        "[%s] Action: %s | UserID: %d | IP: %s | Details: %s\n",
        date('Y-m-d H:i:s'),
        $action,
        $user_id,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $details ?? 'none'
    );
    
    file_put_contents(__DIR__ . '/../logs/admin_actions.log', $log_message, FILE_APPEND);
}

// Генериране на случайна парола
function generate_random_password(int $length = 12): string {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// Форматиране на дата
function format_date(string $date, string $format = 'd.m.Y H:i'): string {
    $datetime = new DateTime($date);
    return $datetime->format($format);
}

// Проверка за AJAX заявка
function is_ajax_request(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Валидация на потребителско име
function validate_username(string $username): bool {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;
}