<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <h2>Здравейте, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    
    <div class="card mt-4">
        <div class="card-header">
            <h4>Вашият профил</h4>
        </div>
        <div class="card-body">
            <?php if (isAdmin()): ?>
                <div class="alert alert-info">
                    Вие имате администраторски права!
                </div>
            <?php endif; ?>
            
            <p>Потребителско име: <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p>Роля: <?= htmlspecialchars($_SESSION['user_role']) ?></p>
            
            <a href="logout.php" class="btn btn-danger">Изход</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>