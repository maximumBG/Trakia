<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

redirectIfNotLoggedIn();

// Инициализация на променливи
$success = '';
$errors = [];
$user_id = $_SESSION['user_id'];

// Вземане на текущите данни на потребителя
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Обработка на формата за промяна на данни
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $phone = clean_input($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Валидация
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Невалиден имейл адрес!";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $email, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Профилът е обновен успешно!";
            header("Location: settings.php");
            exit();
        } else {
            $errors[] = "Грешка при обновяване: " . $conn->error;
        }
    }
}

// Обработка на формата за промяна на парола
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!password_verify($current_password, $user['password'])) {
        $errors[] = "Текущата парола е неправилна!";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Новата парола и потвърждението не съвпадат!";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "Паролата трябва да е поне 6 символа!";
    }

    if (empty($errors)) {
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password_hash, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Паролата е променена успешно!";
            header("Location: settings.php");
            exit();
        } else {
            $errors[] = "Грешка при промяна на парола: " . $conn->error;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="/Trakia/profile.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user"></i> Моят профил
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-cog"></i> Настройки
                </a>
                <?php if (isAdmin()): ?>
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt"></i> Админ панел
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-9">
            <h2><i class="fas fa-cog"></i> Настройки на профила</h2>
            <hr>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Основна информация</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Име</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Фамилия</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Имейл адрес</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> Запази промените
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h4>Смяна на парола</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Текуща парола</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Нова парола</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Потвърди нова парола</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn btn-warning">
                            <i class="fas fa-key"></i> Смени паролата
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>