<?php
// Стартиране на сесията и включване на необходимите файлове
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Генерира нов CSRF токен
}

require_once 'auth.php';
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Управление на потребители</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #2c3e50 !important;
        }
        .nav-link {
            color: #ecf0f1 !important;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: #f39c12 !important;
            transform: translateY(-2px);
        }
        .dropdown-menu {
            background-color: #34495e;
        }
        .dropdown-item {
            color: #ecf0f1;
        }
        .dropdown-item:hover {
            background-color: #2c3e50;
            color: #f39c12;
        }
        .badge-role {
            background-color: #e74c3c;
            font-size: 0.7rem;
            vertical-align: super;
        }
        .list-group-item.active {
    background-color: #2c3e50;
    border-color: #2c3e50;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
}
.card-header {
    font-weight: 500;
}
.card-header:first-child {
    border-radius: 0.35rem 0.35rem 0 0;
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/Trakia/index.php">
                <i class="fas fa-users-cog"></i> User System
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Trakia/profile.php">
                                <i class="fas fa-home"></i> Начало
                            </a>
                        </li>
                        
                        <?php if (isAdmin()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-crown"></i> Админ
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Табло</a></li>
                                    <li><a class="dropdown-item" href="show_students.php"><i class="fas fa-users"></i> Потребители</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog"></i> Настройки</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="generate_qr.php">
                                <i class="fas fa-qrcode"></i> Моят QR код
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> 
                                <?= htmlspecialchars($_SESSION['username'] ?? 'Гост', ENT_QUOTES, 'UTF-8') ?>
                                <?php if (isAdmin()): ?>
                                    <span class="badge badge-role">Admin</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Профил</a></li>
                                <li><a class="dropdown-item" href="admin/settings.php"><i class="fas fa-cog"></i> Настройки</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Изход</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Вход
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus"></i> Регистрация
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <script>
// AJAX изтриване
function deleteUser(userId) {
    if (confirm('Сигурни ли сте?')) {
        fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${userId}&csrf_token=<?= $_SESSION['csrf_token'] ?>`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Грешка при изтриване');
            }
        });
    }
}
</script>

    <div class="container">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">