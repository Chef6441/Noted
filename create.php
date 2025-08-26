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
        $now = date('c');
        $stmt = $db->prepare('INSERT INTO notes (title, description, created_at, updated_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $description, $now, $now]);
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Create Note</title>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Capture the current time in ISO format for reliable parsing
        document.getElementById('current_datetime').value = new Date().toISOString();
    });
    </script>
</head>
<body>

    <h1>Create Note</h1>

    <form method="post" action="/create.php">
      <p>
        <label for="title"><strong>Title:</strong></label><br>
        <input id="title" name="title" type="text" size="40" required>
      </p>

      <p>
        <label for="description"><strong>Description:</strong></label><br>
        <textarea id="description" name="description" rows="6" cols="60" required></textarea>
      </p>

      <p>
        <button type="submit">Save</button>
      </p>
    </form>

    <p><a href="/index.php">Back</a></p>
  </body>
</html>
