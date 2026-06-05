<?php
// Soft delete: set is_deleted = 1
require_once 'db.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE news SET is_deleted = 1 WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: dashboard.php');
exit;
