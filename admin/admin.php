<?php
require_once '../includes/auth.php';
redirectIfNotAdmin();

include '../includes/db.php';
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Администраторски панел</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Потребител</th>
                    <th>Имейл</th>
                    <th>Роля</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Редактирай</a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Сигурни ли сте?')">Изтрий</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>