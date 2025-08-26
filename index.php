<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
$db = get_db();
$stmt = $db->prepare('SELECT id, title, created_at, updated_at FROM notes WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Noted</title>
</head>
<body>
    <h1>Noted</h1>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Logout</a></p>
    <a href="create.php">Create Note</a>
    <ul>
    <?php foreach ($notes as $note): ?>
      <li><a href="/edit.php?id=<?= $note['id']; ?>"><?= htmlspecialchars($note['title']); ?></a></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
