<?php
include('includes/header.php');

?>

<div class="text-center my-4">
    <h1 class="text-primary">Управление на потребители</h1>
    <p>Добавяй, преглеждай и управлявай потребителите с лекота</p>

    <?php if (isAdmin()): ?> <!-- 🔹 Само за админи -->
        <a href="add_student.php" class="btn btn-success me-2">
            <i class="fas fa-plus"></i> Добави потребител
        </a>
    <?php endif; ?>

    <a href="show_students.php" class="btn btn-info">
        <i class="fas fa-users"></i> Всички потребители
    </a>
</div>


<div class="table-container">
    <h2 class="text-center text-primary">Последни добавени потребители</h2>
    <div class="table-responsive">
        <?php
        $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 5";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table class='table table-striped table-bordered text-center'>";
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
            echo "<p class='text-center text-muted'>Няма записани потребители.</p>";
        }
        ?>
    </div>
</div>

<?php
include('includes/footer.php');
?>
