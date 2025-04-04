<?php
session_start();

function loginUser($user) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'logged_in' => true
    ];
}

function isLoggedIn() {
    return isset($_SESSION['user']['logged_in']) && $_SESSION['user']['logged_in'] === true;
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }
}
?>