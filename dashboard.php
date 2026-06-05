<?php
require_once 'db.php';
require_login();

// Fetch all active news with category + author
$stmt = $pdo->query("
    SELECT n.*, c.name AS category_name, u.name AS author
    FROM news n
    JOIN categories c ON c.id = n.category_id
    JOIN users u ON u.id = n.user_id
    WHERE n.is_deleted = 0
    ORDER BY n.id DESC
");
$news = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<h1>Dashboard - All News</h1>
<p style="margin-bottom:16px;">
    <a href="add_news.php" class="btn">+ Add News</a>
    <a href="add_category.php" class="btn">+ Add Category</a>
</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($news)): ?>
        <tr><td colspan="6" style="text-align:center;">No news yet.</td></tr>
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
            <td class="actions">
                <a class="btn btn-edit" href="edit_news.php?id=<?= (int)$n['id'] ?>">✏️ Edit</a>
                <a class="btn btn-delete" href="delete_news.php?id=<?= (int)$n['id'] ?>"
                   onclick="return confirm('Delete this news?');">🗑️ Delete</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
