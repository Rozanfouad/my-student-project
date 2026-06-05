<?php
require_once 'db.php';
require_login();

$err = '';
$msg = '';
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $details = trim($_POST['details'] ?? '');
    $user_id = (int)$_SESSION['user_id'];
    $imageName = null;

    if ($title === '' || $category_id <= 0 || $details === '') {
        $err = 'Title, category and details are required.';
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $err = 'Invalid image type. Allowed: ' . implode(', ', $allowed);
            } else {
                if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
                $imageName = uniqid('news_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            }
        }

        if (!$err) {
            $stmt = $pdo->prepare("
                INSERT INTO news (title, category_id, details, image, user_id, is_deleted)
                VALUES (?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([$title, $category_id, $details, $imageName, $user_id]);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<?php include 'header.php'; ?>
<h1>Add News</h1>
<?php if ($err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Category</label>
    <select name="category_id" required>
        <option value="">-- Select --</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Details</label>
    <textarea name="details" required></textarea>

    <label>Image (optional)</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Publish News</button>
</form>
<?php include 'footer.php'; ?>
