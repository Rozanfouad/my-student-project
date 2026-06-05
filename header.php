<?php require_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (!empty($_SESSION['user_id'])): ?>
<nav class="navbar">
    <div class="brand">📰 News System</div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="add_news.php">Add News</a></li>
        <li><a href="add_category.php">Add Category</a></li>
        <li><a href="view_categories.php">Categories</a></li>
        <li><a href="deleted_news.php">Deleted News</a></li>
        <li class="right">Hi, <?= e($_SESSION['user_name']) ?> | <a href="logout.php">Logout</a></li>
    </ul>
</nav>
<?php endif; ?>
<div class="container">
