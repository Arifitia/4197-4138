<?php
$dbPath = __DIR__ . '/writable/database.db';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->exec(file_get_contents(__DIR__ . '/base.sql'));
echo "Base créée avec succès dans writable/database.db\n";