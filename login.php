<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            loginUser($user['id'], $user['username'], $user['role']);
            
            if (isAdmin()) {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: profile.php");
            }
            exit();
        }
    }
    
    $error = "Грешно потребителско име или парола!";
}

include 'includes/header.php';
?>

<!-- Останалата част на формата остава същата -->

require_once 'includes/header.php';
?>

<div class="login-container">
    <h2>Вход в системата</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Потребителско име:</label>
            <input type="text" id="username" name="username" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="password">Парола:</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Вход</button>
    </form>
    
    <div class="mt-3">
        <a href="register.php">Нямате акаунт? Регистрирайте се</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>