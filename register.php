<?php
require __DIR__ . '/config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($name === '')                               $errors[] = 'Имя обязательно';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
    if (mb_strlen($pass) < 6)                       $errors[] = 'Пароль минимум 6 символов';

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = 'Такой email уже занят';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = db()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hash]);

            $_SESSION['user_id']   = db()->lastInsertId();
            $_SESSION['user_name'] = $name;

            header('Location: /dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — Кофеин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark navbar-coffee">
        <div class="container">
            <span class="navbar-brand">☕ Кофеин</span>
        </div>
    </nav>

    <div class="container auth-wrap">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4 text-center">Регистрация</h3>

                <?php foreach ($errors as $err): ?>
                    <div class="alert alert-danger py-2"><?= e($err) ?></div>
                <?php endforeach; ?>

                <form method="post" id="registerForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" value="<?= e($name) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль (мин. 6)</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <button class="btn btn-primary w-100">Создать аккаунт</button>
                </form>

                <p class="mt-3 mb-0 text-center">
                    Уже есть аккаунт? <a href="/login.php">Войти</a>
                </p>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>