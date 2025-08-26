<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
$db = get_db();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}
$stmt = $db->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$note) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $db->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?')->execute([$id, $_SESSION['user_id']]);
        header('Location: index.php');
        exit;
    } elseif ($action === 'update') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        if ($title !== '' && $description !== '') {
            $datetime = date('c');
            $stmt = $db->prepare('UPDATE notes SET title = ?, description = ?, updated_at = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$title, $description, $datetime, $id, $_SESSION['user_id']]);
            header('Location: index.php');
            exit;
        }
    }
}
$createdAt = new DateTime($note['created_at']);
$createdAtDisplay = $createdAt->format('M j, Y, g:i a');
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
    <p>Created: <time datetime="<?= htmlspecialchars($note['created_at']) ?>"><?= htmlspecialchars($createdAtDisplay) ?></time></p>

    <form method="post" action="/edit.php">
      <input type="hidden" name="id" value="<?= $id ?>">
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
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('created_at');
        var stored = el.dataset.createdAt;
        var date = new Date(stored);
        var datePart = date.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
        var timePartFull = date.toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit', timeZoneName: 'short' });
        var match = timePartFull.match(/(.*) (.*)$/);
        var timePart = match ? match[1] : timePartFull;
        var tzPart = match ? match[2] : '';
        el.textContent = datePart + ' \u2013 ' + timePart + (tzPart ? ' (' + tzPart + ')' : '');
      });
    </script>
  </body>
</html>
