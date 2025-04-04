<?php
// Стартира сесията само ако не е активна
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 1 ден
        'read_and_close'  => false,
    ]);
}

function loginUser($user_id, $username, $role) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = $role;
    $_SESSION['logged_in'] = true; // Явно маркиране като логнат
}

function isLoggedIn() {
    return !empty($_SESSION['logged_in']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}
function checkAdminPrivileges() {
    if (!isAdmin()) {
        http_response_code(403);
        die("Достъпът е забранен! Нямате администраторски права.");
    }
}
function redirectIfNotAdmin() {
    if (!isAdmin()) {
        $_SESSION['error'] = "Нямате права за достъп!";
        header("Location: ../index.php");
        exit();
    }
}

?>