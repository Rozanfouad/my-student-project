<?php
require_once 'db.php';
require_login();

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $err = 'Category name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        $msg = 'Category added successfully.';
    }
}
?>
<?php include 'header.php'; ?>
<h1>Add Category</h1>
<?php if ($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endif; ?>
<form method="POST">
    <label>Category Name</label>
    <input type="text" name="name" required>
    <button type="submit">Add Category</button>
</form>
<p style="margin-top:14px;"><a href="view_categories.php">View All Categories →</a></p>
<?php include 'footer.php'; ?>
