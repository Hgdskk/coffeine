<?php
require __DIR__ . '/config.php';
requireAuth();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Личный кабинет — Кофеин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark navbar-coffee">
        <div class="container">
            <span class="navbar-brand">☕ Кофеин</span>
            <div class="text-white">
                Привет, <b><?= e($_SESSION['user_name']) ?></b>
                <a href="/logout.php" class="btn btn-sm btn-outline-light ms-2">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Личный кабинет</h4>
                        <p class="mb-1">Пользователь: <b><?= e($_SESSION['user_name']) ?></b></p>
                        <p class="text-muted mb-4">Управляйте списком поставщиков вашей кофейни.</p>
                        <a href="/suppliers/index.php" class="btn btn-primary">Перейти к поставщикам</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>