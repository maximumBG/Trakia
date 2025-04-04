<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Валидация на входните данни
    $username = clean_input($_POST['username'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = clean_input($_POST['first_name'] ?? '');
    $last_name = clean_input($_POST['last_name'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');

    // 2. Проверки за валидност
    if (empty($username)) {
        $errors[] = "Потребителското име е задължително!";
    } elseif (strlen($username) < 4) {
        $errors[] = "Потребителското име трябва да е поне 4 символа!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Невалиден имейл адрес!";
    }

    if (strlen($password) < 6) {
        $errors[] = "Паролата трябва да е поне 6 символа!";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Паролите не съвпадат!";
    }

    // 3. Проверка за съществуващ потребител
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Потребител с това име/имейл вече съществува!";
        }
    }

    // 4. Регистрация при липса на грешки
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user'; // Основна роля
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $email, $password_hash, $first_name, $last_name, $phone, $role);

        if ($stmt->execute()) {
            $success = "Успешна регистрация! Моля влезте в системата.";
            header("Refresh: 3; url=login.php"); // Пренасочване след 3 сек
        } else {
            $errors[] = "Грешка при регистрация: " . $conn->error;
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Регистрация</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php else: ?>
                        <form method="POST" action="register.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Потребителско име*</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($username ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Имейл адрес*</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Парола* (мин. 6 символа)</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Потвърди парола*</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Име</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="<?= htmlspecialchars($first_name ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Фамилия</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="<?= htmlspecialchars($last_name ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?= htmlspecialchars($phone ?? '') ?>">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Регистрирай се</button>
                        </form>
                    <?php endif; ?>
                    
                    <div class="mt-3 text-center">
                        Вече имате акаунт? <a href="login.php">Влезте тук</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>