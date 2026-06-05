<?php
require_once 'db.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ? AND is_deleted = 0");
$stmt->execute([$id]);
$news = $stmt->fetch();
if (!$news) { header('Location: dashboard.php'); exit; }

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $details = trim($_POST['details'] ?? '');
    $imageName = $news['image']; // keep old image by default

    if ($title === '' || $category_id <= 0 || $details === '') {
        $err = 'Title, category and details are required.';
    } else {
        // New image uploaded?
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $err = 'Invalid image type.';
            } else {
                if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
                $imageName = uniqid('news_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                // Optionally delete the old file
                if (!empty($news['image']) && file_exists('uploads/' . $news['image'])) {
                    @unlink('uploads/' . $news['image']);
                }
            }
        }

        if (!$err) {
            $stmt = $pdo->prepare("
                UPDATE news SET title = ?, category_id = ?, details = ?, image = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $category_id, $details, $imageName, $id]);
            header('Location: dashboard.php');
            exit;
        }
    }
    // refresh local copy for redisplay
    $news['title'] = $title;
    $news['category_id'] = $category_id;
    $news['details'] = $details;
}
?>
<?php include 'header.php'; ?>
<h1>Edit News</h1>
<?php if ($err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" value="<?= e($news['title']) ?>" required>

    <label>Category</label>
    <select name="category_id" required>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= $c['id'] == $news['category_id'] ? 'selected' : '' ?>>
                <?= e($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Details</label>
    <textarea name="details" required><?= e($news['details']) ?></textarea>

    <label>Current Image</label>
    <?php if (!empty($news['image']) && file_exists('uploads/' . $news['image'])): ?>
        <img src="uploads/<?= e($news['image']) ?>" style="width:140px;border-radius:6px;">
    <?php else: ?>
        <p>—</p>
    <?php endif; ?>

    <label>Replace Image (optional)</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Update News</button>
</form>
<?php include 'footer.php'; ?>
