<?php

require_once __DIR__ . '/../config.php';

$dsn = 'mysql:host=' . DB_HOST
     . ';dbname=' . DB_NAME
     . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);
    exit('Database connection failed.');
}