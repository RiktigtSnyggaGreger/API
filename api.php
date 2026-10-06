<?php
header('Content-Type: application/json; charset=utf-8');
//koppla till databasen
require_once __DIR__ . '/db.php';

// Kollar ifall man skickar med ett ID
$id = $_GET['id'] ?? null;
$lang = $_GET['lang'] ?? 'sv';
$tag  = $_GET['tag'] ?? null;
// Hämtar sidan, taggen och JSON-innehållet på samma gång
// I LOVE MARIADB!! <3
$sql = "SELECT 
            p.id, 
            p.titel, 
            t.namn AS tag, 
            JSON_VALUE(pc.content_json, '$.rubrik.$lang') AS rubrik,
            JSON_VALUE(pc.content_json, '$.hero_bild') AS hero_bild
        FROM page p
        LEFT JOIN tag t ON p.tag_id = t.id
        LEFT JOIN page_content pc ON p.id = pc.page_id
        WHERE p.id = ?";

// Super duper PHP kod som ja inte förstår men som funkar
$data = $db->execute_query($sql, [$id])->fetch_assoc();

// Felhantering
if (!$data) {
    http_response_code(404);
    echo json_encode(['error' => 'Sidan hittades inte']);
    exit;
}

// JSON_UNESCAPED_UNICODE = tillåter Å,Ä och Ö
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);