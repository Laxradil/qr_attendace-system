<?php
$dsn = 'pgsql:host=aws-1-ap-northeast-1.pooler.supabase.com;port=5432;dbname=postgres;sslmode=require';
$user = 'postgres.xcofziseyxlkzygqnahv';
$pass = 'z3ajAmX9aAkkXKdY';
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query('SELECT id, name, email, role, password FROM users ORDER BY id');
$stmt->setFetchMode(PDO::FETCH_ASSOC);
foreach ($stmt as $row) {
    echo $row['id'] . ' | ' . $row['name'] . ' | ' . $row['email'] . ' | ' . $row['role'] . ' | ' . $row['password'] . PHP_EOL;
}
?>
