<?php
require_once '../includes/auth.php';
redirectIfNotAdmin();

include '../includes/db.php';

$user_id = $_GET['id'] ?? 0;
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    $stmt->execute();
    
    header("Location: dashboard.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Редактиране на потребител</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Потребителско име</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Роля</label>
            <select name="role" class="form-select">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Потребител</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Запази</button>
        <a href="edit_user.php" class="btn btn-secondary">Отказ</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>