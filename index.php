<?php
require __DIR__ . '/../config.php';
requireAuth();

$stmt = db()->prepare('SELECT id, name, contact_person, phone FROM suppliers WHERE user_id = ? ORDER BY id DESC');
$stmt->execute([$_SESSION['user_id']]);
$suppliers = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Поставщики — Кофеин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark navbar-coffee">
        <div class="container">
            <span class="navbar-brand">☕ Кофеин</span>
            <div class="text-white">
                <?= e($_SESSION['user_name']) ?>
                <a href="/logout.php" class="btn btn-sm btn-outline-light ms-2">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="m-0">Поставщики</h3>
            <a href="/suppliers/create.php" class="btn btn-success">+ Добавить</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info py-2"><?= e($_GET['msg']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">ID</th>
                            <th>Название</th>
                            <th>Контактное лицо</th>
                            <th>Телефон</th>
                            <th style="width:220px" class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$suppliers): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Пока ничего нет</td></tr>
                    <?php else: foreach ($suppliers as $s): ?>
                        <tr>
                            <td><?= (int)$s['id'] ?></td>
                            <td><?= e($s['name']) ?></td>
                            <td><?= e($s['contact_person'] ?? '—') ?></td>
                            <td><?= e($s['phone'] ?? '—') ?></td>
                            <td class="text-end">
                                <a href="/suppliers/edit.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                <form action="/suppliers/delete.php" method="post" class="d-inline js-delete-form">
                                    <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>