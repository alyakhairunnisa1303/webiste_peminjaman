<?php
require_once __DIR__ . '/../config/database.php';

$pass = password_hash('adminuks', PASSWORD_DEFAULT);

$pdo->prepare("
    UPDATE users 
    SET password = :pass 
    WHERE username = 'admin' AND role = 'admin'
")->execute(['pass'=>$pass]);

echo "OK";
