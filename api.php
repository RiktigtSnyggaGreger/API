<?php
header('Content-Type: application/json; charset=utf-8');
$db = new mysqli("db", "db", "db", "min_api");

// Kollar ifall man skickar med ett ID
$id = $_GET['id'] ?? null;
// Hämtar sidan, taggen och JSON-innehållet på samma gång
$sql = "SELECT 
            p.id, 
            p.titel, 
            p.sprak, 
            t.namn AS tag, 
            pc.content_json
        FROM page p
        LEFT JOIN tag t ON p.tag_id = t.id
        LEFT JOIN page_content pc ON p.id = pc.page_id
        WHERE p.id = ?";

// Super duper PHP kod som ja inte förstår men som funkar
$data = $db->execute_query($sql, [$id])->fetch_assoc();

// Packa upp JSON-fältet så det inte skickas som en dubbel-escapad sträng
if ($data['content_json']) {
    $data['content_json'] = json_decode($data['content_json']);
}
// JSON_UNESCAPED_UNICODE = tillåter Å,Ä och Ö
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);