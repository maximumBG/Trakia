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
        <h4 class="mb-0"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h4>
    </div>
    <div class="card-body">
        <p><strong>Имейл:</strong> <?php echo $student['email']; ?></p>
        <p><strong>Телефон:</strong> <?php echo $student['phone'] ?? '-'; ?></p>
        <p><strong>Дата на раждане:</strong> <?php echo $student['birth_date']; ?></p>
        <div class="text-center mt-4">
            <a href="generate_qr.php?id=<?php echo $student['id']; ?>" class="btn btn-success">
                <i class="fas fa-qrcode"></i> Генерирай QR код
            </a>
            <a href="show_students.php" class="btn btn-secondary">Назад към потребителите</a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>