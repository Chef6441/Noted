<?php
require 'db.php';
$db = get_db();
$notes = $db->query('SELECT id, title FROM notes ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Noted</title>
</head>
<body>
  <h1>Noted</h1>

  <p><a href="/create.php">Create Note</a></p>

  <ul>
    <?php foreach ($notes as $note): ?>
      <li><a href="/edit.php?id=<?= $note['id']; ?>"><?= htmlspecialchars($note['title']); ?></a></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
