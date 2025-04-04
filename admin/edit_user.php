<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

redirectIfNotAdmin();

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$user_id) {
    $_SESSION['error'] = "Невалиден ID на потребител";
    header("Location: dashboard.php");
    exit();
}

// Вземане на данни за потребителя
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if (!$user) {
        throw new Exception("Потребителят не е намерен");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: dashboard.php");
    exit();
}

// Обработка на формата
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    try {
        $stmt = $conn->prepare("UPDATE users SET role = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $role, $email, $user_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Потребителят е обновен успешно!";
        header("Location: dashboard.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        $error = "Грешка при обновяване: " . $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <h2><i class="fas fa-user-edit"></i> Редактиране на потребител</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" class="mt-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Потребителско име</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['user']) ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Имейл</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="role" class="form-label">Роля</label>
            <select name="role" class="form-select" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Потребител</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
            </select>
        </div>
        
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Запази промените</button>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Отказ</a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>