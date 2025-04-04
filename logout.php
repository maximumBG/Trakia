<?php
session_start();

// Изчистване на всички сесийни променливи
$_SESSION = [];

// Изтриване на сесийната бисквитка
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Унищожаване на сесията
session_destroy();

// Пренасочване към login.php
header("Location: login.php");
exit();
