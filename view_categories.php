<?php
require_once 'db.php';
require_login();

$cats = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<?php include 'header.php'; ?>
<h1>All Categories</h1>
<p style="margin-bottom:14px;"><a class="btn" href="add_category.php">+ Add Category</a></p>
<table>
    <thead><tr><th>#</th><th>ID</th><th>Name</th></tr></thead>
    <tbody>
    <?php if (empty($cats)): ?>
        <tr><td colspan="3" style="text-align:center;">No categories yet.</td></tr>
    <?php else: foreach ($cats as $i => $c): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= (int)$c['id'] ?></td>
            <td><?= e($c['name']) ?></td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
