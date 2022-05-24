<?php

include 'Database/MySQL.php';
use Database\MySQL;

$connection = new MySQL('localhost','tech','root','');
$db = $connection->use();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "CREATE TABLE users ( id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(40) NOT NULL, year_of_birth VARCHAR(40) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP )";
$db->exec($sql);
echo "users table created successfully";