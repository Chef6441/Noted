<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
$db = get_db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    if ($title !== '' && $description !== '') {
        $datetime = date('c');
        $stmt = $db->prepare('INSERT INTO notes (title, description, created_at, updated_at, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$title, $description, $datetime, $datetime, $_SESSION['user_id']]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Note</title>
</head>
<body>
    <h1>Create Note</h1>
    <form method="post">
        <label>Title</label>
        <input type="text" name="title" required>
        <br>
        <label>Description</label>
        <textarea name="description" required></textarea>
        <br>
        <button type="submit">Save</button>
    </form>
    <a href="index.php">Back</a>
</body>
</html>
