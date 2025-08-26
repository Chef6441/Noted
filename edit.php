<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
$db = get_db();
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}
$stmt = $db->prepare('SELECT * FROM notes WHERE id = ?');
$stmt->execute([$id]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$note) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $db->prepare('DELETE FROM notes WHERE id = ?')->execute([$id]);
        header('Location: index.php');
        exit;
    } elseif ($action === 'update') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        if ($title !== '' && $description !== '') {
            $updatedAt = date('c');
            $stmt = $db->prepare('UPDATE notes SET title = ?, description = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$title, $description, $updatedAt, $id]);
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
  </head>
  <body>
    <h1>Edit Note</h1>
    <p>Created: <?= htmlspecialchars($note['created_at']) ?></p>

    <form method="post" action="/edit.php?id=<?= $id ?>">
      <p>
        <label for="title"><strong>Title:</strong></label><br>
        <input id="title" name="title" type="text"
               value="<?= htmlspecialchars($note['title']) ?>" size="40" required>
      </p>

      <p>
        <label for="description"><strong>Description:</strong></label><br>
        <textarea id="description" name="description"
                  rows="6" cols="60" required><?= htmlspecialchars($note['description']) ?></textarea>
      </p>

      <p>
        <button type="submit" name="action" value="update">Update</button>
        <button type="submit" name="action" value="delete" onclick="return confirm('Delete this note?');">Delete</button>
      </p>
    </form>

    <p><a href="/index.php">Back</a></p>
  </body>
</html>
