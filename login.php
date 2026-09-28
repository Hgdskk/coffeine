<?php
require __DIR__ . '/config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, name, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header('Location: /dashboard.php');
        exit;
    }
    $error = 'Неверный email или пароль';
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — Кофеин</title>
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
                <h3 class="mb-4 text-center">Вход в систему</h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Войти</button>
                </form>

                <p class="mt-3 mb-0 text-center">
                    Нет аккаунта? <a href="/register.php">Регистрация</a>
                </p>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>