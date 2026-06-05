<?php
// Restore a soft-deleted news item
require_once 'db.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE news SET is_deleted = 0 WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: deleted_news.php');
exit;
