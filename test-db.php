<?php
/**
 * Database Connection Test
 * Visit this file in your browser to test the database connection
 */

require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Test if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠ Database is empty. Run schema.sql to create tables.</p>";
    } else {
        echo "<p style='color: green;'>✓ Tables found: " . implode(', ', $tables) . "</p>";
    }
    
    // Count cards
    $stmt = $pdo->query("SELECT COUNT(*) FROM cards");
    $count = $stmt->fetchColumn();
    echo "<p> Cards in database: <strong>$count</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Check your config.php credentials!</strong></p>";
}

echo "<hr>";
echo "<h3>Current Config:</h3>";
echo "<ul>";
echo "<li>DB_HOST: " . DB_HOST . "</li>";
echo "<li>DB_NAME: " . DB_NAME . "</li>";
echo "<li>DB_USER: " . DB_USER . "</li>";
echo "<li>DB_PASS: " . str_repeat('*', strlen(DB_PASS)) . "</li>";
echo "</ul>";
