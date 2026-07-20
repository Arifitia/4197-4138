<?php
$pdo = new PDO('sqlite:writable/database.db');
$r = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
foreach ($r as $row) {
    echo $row['name'] . PHP_EOL;
}