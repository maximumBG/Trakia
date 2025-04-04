<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление на потребители</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    
    <div class="container">
        <a class="navbar-brand" href="index.php">Управление на потребители</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Начало</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_student.php"><i class="fas fa-plus"></i> Добави потребител</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show_students.php"><i class="fas fa-users"></i> Всички потребители</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">