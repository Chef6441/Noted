<?php
require 'db.php';
$db = get_db();
$notes = $db->query('SELECT id, title, created_at, updated_at FROM notes ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Noted</title>
</head>
<body>
    <h1>Noted</h1>
    <a href="create.php">Create Note</a>
    <ul>
    <?php foreach ($notes as $note): ?>
        <li>
            <a href="edit.php?id=<?php echo $note['id']; ?>"><?php echo htmlspecialchars($note['title']); ?></a>
            (created: <?php echo $note['created_at']; ?>, updated: <?php echo $note['updated_at']; ?>)
        </li>
    <?php endforeach; ?>
    </ul>
</body>
</html>
