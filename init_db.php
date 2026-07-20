<?php
$dbPath = __DIR__ . '/writable/database.db';

if (file_exists($dbPath)) {
    @unlink($dbPath);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->exec(file_get_contents(__DIR__ . '/base.sql'));
echo "Base créée avec succès dans writable/database.db\n";