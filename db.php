<?php
try {
    $db = new mysqli("db", "db", "db", "min_api");
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }
} catch (Exception $error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $error->getMessage()]);
    exit;
}   
