<?php
require __DIR__ . '/../config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /suppliers/index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = db()->prepare('DELETE FROM suppliers WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);

header('Location: /suppliers/index.php?msg=' . urlencode('Поставщик удалён'));
exit;