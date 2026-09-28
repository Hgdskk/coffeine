<?php
require __DIR__ . '/../config.php';
requireAuth();

$id = (int)($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM suppliers WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$supplier = $stmt->fetch();

if (!$supplier) {
    http_response_code(404);
    exit('Поставщик не найден или недоступен');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = trim($_POST['name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');

    if ($name === '') $errors[] = 'Название обязательно';

    if (!$errors) {
        $stmt = db()->prepare('UPDATE suppliers SET name = ?, contact_person = ?, phone = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$name, $contact_person, $phone, $id, $_SESSION['user_id']]);
        header('Location: /suppliers/index.php?msg=' . urlencode('Изменения сохранены'));
        exit;
    }

    $supplier['name']           = $name;
    $supplier['contact_person'] = $contact_person;
    $supplier['phone']          = $phone;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактирование — Кофеин</title>
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
        <h3 class="mb-3">Редактировать поставщика #<?= (int)$supplier['id'] ?></h3>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2"><?= e($err) ?></div>
        <?php endforeach; ?>

        <div class="card">
            <div class="card-body">
                <form method="post" id="supplierForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Название *</label>
                        <input type="text" name="name" class="form-control" value="<?= e($supplier['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Контактное лицо</label>
                        <input type="text" name="contact_person" class="form-control" value="<?= e($supplier['contact_person'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($supplier['phone'] ?? '') ?>">
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                    <a href="/suppliers/index.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>