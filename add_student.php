<?php
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birth_date = $_POST['birth_date'];

    $sql = "INSERT INTO students (first_name, last_name, email, phone, birth_date)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$birth_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center' role='alert'>Потребителят беше успешно добавен!</div>";
    } else {
        echo "<div class='alert alert-danger text-center' role='alert'>Грешка: " . $conn->error . "</div>";
    }
}
?>

<?php include('includes/header.php'); ?>

<h1 class="text-center text-primary my-5">Добави нов потребител</h1>

<form method="POST">
    <div class="mb-3">
        <label for="first_name" class="form-label">Първо име</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>

    <div class="mb-3">
        <label for="last_name" class="form-label">Фамилно име</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Имейл</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Телефон</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>

    <div class="mb-3">
        <label for="birth_date" class="form-label">Дата на раждане</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Добави потребител</button>
</form>

<?php include('includes/footer.php'); ?>