<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'university';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Грешка при свързването с базата данни: " . $conn->connect_error);
}

// Създаване на таблицата ако не съществува
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    birth_date DATE NOT NULL,
    enrollment_year INT(4),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Грешка при създаване на таблица: " . $conn->error);
}
?>