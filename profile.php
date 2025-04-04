<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();
require_once 'includes/db.php';
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT user, role, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="container mt-4">
    <h2>Здравейте, <?= htmlspecialchars($user['user']) ?>!</h2>
    <div class="card mt-4">
        <div class="card-header">
            <h4>Вашият профил</h4>
        </div>
        <div class="card-body text-center">
            <img src="uploads/<?= $user['profile_image'] ?: 'default.png' ?>" alt="Profile Picture" class="rounded-circle" width="150">
            <form action="upload_profile_image.php" method="POST" enctype="multipart/form-data" class="mt-3">
                <input type="file" name="profile_image" accept="image/*" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary">Качи снимка</button>
            </form>
            <p class="mt-3">Потребителско име: <?= htmlspecialchars($user['user']) ?></p>
            <p>Роля: <?= htmlspecialchars($user['role']) ?></p>
            <a href="logout.php" class="btn btn-danger">Изход</a>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

<!--upload_profile_image.php
<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_image'];
    $upload_dir = 'uploads/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($file['type'], $allowed_types) && $file['size'] < 5000000) { // 5MB макс.
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = "user_$user_id.$extension";
        $filepath = $upload_dir . $filename;
        
        move_uploaded_file($file['tmp_name'], $filepath);
        
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        $stmt->execute();
    }
}
header("Location: profile.php");
exit();
