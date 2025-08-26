<?php
require 'db.php';
$db = get_db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $datetime = $_POST['current_datetime'] ?? '';
    if ($title !== '' && $description !== '' && $datetime !== '') {
        $stmt = $db->prepare('INSERT INTO notes (title, description, created_at, updated_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $description, $datetime, $datetime]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Note</title>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var now = new Date();
        var datePart = now.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
        var timePartFull = now.toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit', timeZoneName: 'short' });
        var match = timePartFull.match(/(.*) (.*)$/);
        var timePart = match ? match[1] : timePartFull;
        var tzPart = match ? match[2] : '';
        document.getElementById('current_datetime').value = datePart + ' \u2013 ' + timePart + (tzPart ? ' (' + tzPart + ')' : '');
    });
    </script>
</head>
<body>
    <h1>Create Note</h1>
    <form method="post">
        <label>Title</label>
        <input type="text" name="title" required>
        <br>
        <label>Description</label>
        <textarea name="description" required></textarea>
        <input type="hidden" name="current_datetime" id="current_datetime">
        <br>
        <button type="submit">Save</button>
    </form>
    <a href="index.php">Back</a>
</body>
</html>
