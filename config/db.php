<?php
// Load environment variables from .env
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        $_ENV[$name] = $value;
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'php_lms';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '123';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}


//  if ($conn->connect_error) {
//     die("<div class='message error'>Check Database Connection: " . $conn->connect_error . "</div>");
//  } else {
//      echo "<div class='message success'>You are now connected to Math Gineer!</div>";
//  }

