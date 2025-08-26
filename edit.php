<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
$db = get_db();
$id = $_GET['id'] ?? null;
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
    if (isset($_POST['delete'])) {
        $db->prepare('DELETE FROM notes WHERE id = ?')->execute([$id]);
        header('Location: index.php');
        exit;
    } else {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $datetime = $_POST['current_datetime'] ?? '';
        if ($title !== '' && $description !== '' && $datetime !== '') {
            $stmt = $db->prepare('UPDATE notes SET title = ?, description = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$title, $description, $datetime, $id]);
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Note</title>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('current_datetime').value = new Date().toISOString();
    });
    </script>
</head>
<body>
    <h1>Edit Note</h1>
    <p>Created: <?php echo $note['created_at']; ?></p>
    <form method="post">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
        <br>
        <label>Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($note['description']); ?></textarea>
        <input type="hidden" name="current_datetime" id="current_datetime">
        <br>
        <button type="submit">Update</button>
    </form>
    <form method="post" onsubmit="return confirm('Delete this note?');">
        <input type="hidden" name="delete" value="1">
        <button type="submit">Delete</button>
    </form>
    <a href="index.php">Back</a>
</body>
</html>
