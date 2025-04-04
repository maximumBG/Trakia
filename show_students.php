<?php
include('includes/db.php');

$sql = "SELECT * FROM students ORDER BY id DESC";
$result = $conn->query($sql);
?>

<?php include('includes/header.php'); ?>

<h1 class="text-center text-primary my-5">Списък на потребителите</h1>

<?php
if ($result->num_rows > 0) {
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead class='table-dark'><tr><th>Име</th><th>Фамилия</th><th>Имейл</th><th>Телефон</th><th>Действия</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . ($row["phone"] ?? '-') . "</td>";
        echo "<td>
                <a href='show_student.php?id=" . $row["id"] . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                <a href='generate_qr.php?id=" . $row["id"] . "' class='btn btn-sm btn-success'><i class='fas fa-qrcode'></i></a>
              </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-warning text-center'>Няма записани потребители.</div>";
}
?>

<?php include('includes/footer.php'); ?>