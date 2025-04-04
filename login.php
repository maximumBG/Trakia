<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Ако потребителят е вече влязъл, пренасочи
if (isLoggedIn()) {
    header("Location: profile.php");
    exit();
}

$error = '';

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
            // Явно задаване на всички сесийни променливи
            loginUser($user['id'], $user['username'], $user['role']);
            
            // Записване на лог
            error_log("User {$user['username']} logged in successfully");
            
            // Пренасочване според ролята
            if (isAdmin()) {
                header("Location: admin/admin.php");
            } else {
                header("Location: profile.php");
            }
            exit();
        }
    }
    
    $error = "Грешно потребителско име или парола!";
}

require_once 'includes/header.php';
?>

<!-- HTML форма за вход -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Вход в системата</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Потребителско име</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Парола</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Вход</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="register.php">Нямате акаунт? Регистрирайте се тук</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>