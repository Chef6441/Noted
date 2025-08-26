# Noted

Simple note taking app built with PHP and SQLite.

## Usage

1. Start the PHP built-in server:
   ```
   php -S localhost:8000
   ```
2. Register a new account at `http://localhost:8000/register.php` and log in via `http://localhost:8000/login.php`.

3. After logging in, open `http://localhost:8000/index.php` to manage your notes.

The app allows you to create, view, edit, and delete notes. Each note has a title and description. Creation and update times are recorded using the user's local datetime via JavaScript. The interface uses plain HTML with no styling.
