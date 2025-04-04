<?php
include('includes/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-warning text-center' role='alert'>Няма такъв потребител в базата данни.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger text-center' role='alert'>Не е подаден валиден идентификатор за потребител.</div>";
    exit();
}
?>

<?php include('includes/header.php'); ?>

<h1 class="text-center text-primary my-5">Преглед на потребител</h1>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><?php echo htmlspecialchars($student['first_name'].' '.$student['last_name']); ?></h4>
    </div>
    <div class="card-body">
        <?php
        $profilePicture = $student['profile_image'] ?? null;
        $defaultImage = 'uploads/default_profile.png';
        
        // Проверка за основната снимка
        if ($profilePicture && file_exists("uploads/".$profilePicture)) {
            $imagePath = "uploads/".$profilePicture;
        } 
        // Проверка за default снимка
        elseif (file_exists($defaultImage)) {
            $imagePath = $defaultImage;
        }
        // Ако няма никаква снимка
        else {
            $imagePath = null;
        }
        ?>
        
        <div class="text-center mb-4">
            <?php if ($imagePath): ?>
                <img src="<?php echo $imagePath; ?>" 
                     alt="Профилна снимка на <?php echo htmlspecialchars($student['first_name'].' '.$student['last_name']); ?>" 
                     class="img-thumbnail" 
                     style="max-width: 200px; max-height: 200px;">
            <?php else: ?>
                <div class="img-thumbnail d-inline-block" style="width:200px;height:200px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-user fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
            
            <?php if (!$profilePicture): ?>
                <p class="text-muted mt-2">Няма качена профилна снимка</p>
            <?php endif; ?>
        </div>
        
        <p><strong>Имейл:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Телефон:</strong> <?php echo !empty($student['phone']) ? htmlspecialchars($student['phone']) : '-'; ?></p>
        <p><strong>Дата на раждане:</strong> <?php echo htmlspecialchars($student['birth_date']); ?></p>
        
        <div class="text-center mt-4">
            <a href="generate_qr.php?id=<?php echo $student['id']; ?>" class="btn btn-success">
                <i class="fas fa-qrcode"></i> Генерирай QR код
            </a>
            <a href="show_students.php" class="btn btn-secondary">Назад към потребителите</a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>