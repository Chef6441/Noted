<?php
function get_db() {
    $db = new PDO('sqlite:' . __DIR__ . '/notes.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS notes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )');

    $columns = $db->query("PRAGMA table_info(notes)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('user_id', $columns)) {
        $db->exec('ALTER TABLE notes ADD COLUMN user_id INTEGER NOT NULL DEFAULT 0');
    }

    return $db;
}
?>
