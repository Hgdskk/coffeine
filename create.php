<?php
require __DIR__ . '/../config.php';
requireAuth();

$errors = [];
$data = ['name' => '', 'contact_person' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']           = trim($_POST['name'] ?? '');
    $data['contact_person'] = trim($_POST['contact_person'] ?? '');
    $data['phone']          = trim($_POST['phone'] ?? '');

    if ($data['name'] === '') $errors[] = 'Название обязательно';

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO suppliers (name, contact_person, phone, user_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['name'], $data['contact_person'], $data['phone'], $_SESSION['user_id']]);
        header('Location: /suppliers/index.php?msg=' . urlencode('Поставщик добавлен'));
        exit;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Новый поставщик — Кофеин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark navbar-coffee">
        <div class="container">
            <span class="navbar-brand">☕ Кофеин</span>
        </div>
    </nav>

    <div class="container mt-4" style="max-width:640px;">
        <h3 class="mb-3">Новый поставщик</h3>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2"><?= e($err) ?></div>
        <?php endforeach; ?>

        <div class="card">
            <div class="card-body">
                <form method="post" id="supplierForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Название *</label>
                        <input type="text" name="name" class="form-control" value="<?= e($data['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Контактное лицо</label>
                        <input type="text" name="contact_person" class="form-control" value="<?= e($data['contact_person']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($data['phone']) ?>">
                    </div>
                    <button class="btn btn-primary">Сохранить</button>
                    <a href="/suppliers/index.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>