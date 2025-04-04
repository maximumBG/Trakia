<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
redirectIfNotAdmin();
require_once  'includes/functions.php';
// Заявка за всички потребители с обработка на грешки
try {
    $stmt = $conn->prepare("SELECT id, user, email, role, created_at FROM users ORDER BY id DESC");
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    die("Грешка при заявка към базата данни: " . $e->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <h2><i class="fas fa-users-cog"></i> Администраторски панел</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Потребител</th>
                    <th>Имейл</th>
                    <th>Роля</th>
                    <th>Регистриран на</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['user']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                            <?= $user['role'] ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Редактирай
                        </a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Сигурни ли сте, че искате да изтриете този потребител?')">
                            <i class="fas fa-trash-alt"></i> Изтрий
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>