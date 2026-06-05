<?php
require_once 'db.php';
require_login();

$stmt = $pdo->query("
    SELECT n.*, c.name AS category_name, u.name AS author
    FROM news n
    JOIN categories c ON c.id = n.category_id
    JOIN users u ON u.id = n.user_id
    WHERE n.is_deleted = 1
    ORDER BY n.id DESC
");
$news = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<h1>Deleted News</h1>
<table>
    <thead>
        <tr>
            <th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Author</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($news)): ?>
        <tr><td colspan="6" style="text-align:center;">No deleted news.</td></tr>
    <?php else: foreach ($news as $i => $n): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td>
                <?php if (!empty($n['image']) && file_exists('uploads/' . $n['image'])): ?>
                    <img class="thumb" src="uploads/<?= e($n['image']) ?>" alt="">
                <?php else: ?>—<?php endif; ?>
            </td>
            <td><?= e($n['title']) ?></td>
            <td><?= e($n['category_name']) ?></td>
            <td><?= e($n['author']) ?></td>
            <td>
                <a class="btn btn-edit" href="restore_news.php?id=<?= (int)$n['id'] ?>"
                   onclick="return confirm('Restore this news?');">♻️ Restore</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
